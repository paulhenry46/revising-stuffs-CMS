<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\CreateThumbnail;
use App\Models\CoAdminLog;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\File;
use App\Models\Level;
use App\Models\Post;
use App\Models\Type;
use App\Services\LatexPackService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use ZipArchive;

class CoAdminController extends Controller
{
    /**
     * Get the IDs of curricula managed by the authenticated co-admin.
     */
    private function getManagedCurriculaIds(): array
    {
        return auth()->user()->getManagedCurriculaIds();
    }

    /**
     * Get levels belonging to the co-admin's managed curricula.
     */
    private function getAccessibleLevels()
    {
        return Level::whereIn('curriculum_id', $this->getManagedCurriculaIds())->get();
    }

    /**
     * Get level IDs belonging to the co-admin's managed curricula.
     */
    private function getAccessibleLevelIds(): array
    {
        return Level::whereIn('curriculum_id', $this->getManagedCurriculaIds())->pluck('id')->toArray();
    }

    /**
     * Get courses accessible to the co-admin (linked to at least one of their levels).
     */
    private function getAccessibleCourses()
    {
        $levelIds = $this->getAccessibleLevelIds();
        return Course::where('id', '!=', 1)
            ->whereHas('levels', fn($q) => $q->whereIn('levels.id', $levelIds))
            ->get();
    }

    /**
     * Get accessible course IDs.
     */
    private function getAccessibleCourseIds(): array
    {
        return $this->getAccessibleCourses()->pluck('id')->toArray();
    }

    /**
     * Authorize that the authenticated user is a co-admin (and not an admin).
     */
    private function authorizeCoAdmin(): void
    {
        $user = auth()->user();
        abort_unless(
            $user->hasRole('co-admin') && !$user->hasRole('admin'),
            403,
            __('Access denied.')
        );
    }

    /**
     * Record a co-admin activity log entry.
     */
    private function logAction(string $action, string $subjectType, ?int $subjectId, string $subjectLabel): void
    {
        CoAdminLog::create([
            'user_id'       => auth()->id(),
            'action'        => $action,
            'subject_type'  => $subjectType,
            'subject_id'    => $subjectId,
            'subject_label' => $subjectLabel,
        ]);
    }

    /**
     * Authorize that a course is accessible to the co-admin.
     */
    private function authorizeCourseAccess(Course $course): void
    {
        abort_unless(
            in_array($course->id, $this->getAccessibleCourseIds()),
            403,
            __('You do not have access to this course.')
        );
    }

    /**
     * Authorize that a type is accessible to the co-admin.
     */
    private function authorizeTypeAccess(Type $type): void
    {
        // Types with course_id=1 are "all courses" – only admins manage those
        abort_unless(
            $type->course_id !== 1 && in_array($type->course_id, $this->getAccessibleCourseIds()),
            403,
            __('You do not have access to this type.')
        );
    }

    /**
     * Display the co-admin management panel.
     */
    public function index()
    {
        $this->authorizeCoAdmin();

        $levelIds = $this->getAccessibleLevelIds();
        $courses = $this->getAccessibleCourses();
        $courseIds = $courses->pluck('id')->toArray();
        $types = Type::whereIn('course_id', $courseIds)->get();
        $managedCurricula = auth()->user()->managedCurricula;

        return view('co-admin.index', compact('courses', 'types', 'managedCurricula'));
    }

    // -------------------------------------------------------------------------
    // Courses
    // -------------------------------------------------------------------------

    public function createCourse()
    {
        $this->authorizeCoAdmin();

        $course = new Course;
        $course->id = 0;
        $levels = $this->getAccessibleLevels();
        return view('co-admin.courses.edit', compact('course', 'levels'));
    }

    public function storeCourse(Request $request)
    {
        $this->authorizeCoAdmin();

        $request->validate([
            'name'   => 'bail|required|min:3',
            'color'  => 'bail|required|min:4',
            'lang'   => 'max:2',
            'levels' => 'required|array|min:1',
        ]);

        // Ensure all selected levels belong to the co-admin's curricula
        $accessibleLevelIds = $this->getAccessibleLevelIds();
        $selectedLevels = array_filter((array) $request->levels, fn($id) => in_array($id, $accessibleLevelIds));

        $course = new Course;
        $course->name  = $request->name;
        $course->color = $request->color;
        $course->lang  = $request->lang;
        $course->slug  = Str::slug($request->name, '-');
        $course->save();

        $course->levels()->attach($selectedLevels);

        $this->logAction('created_course', 'Course', $course->id, $course->name);

        return redirect()->route('co-admin.index')->with('message', __('The course has been created.'));
    }

    public function editCourse(Course $course)
    {
        $this->authorizeCoAdmin();
        $this->authorizeCourseAccess($course);

        $levels = $this->getAccessibleLevels();
        return view('co-admin.courses.edit', compact('course', 'levels'));
    }

    public function updateCourse(Request $request, Course $course)
    {
        $this->authorizeCoAdmin();
        $this->authorizeCourseAccess($course);

        $request->validate([
            'name'   => 'bail|required|min:3',
            'color'  => 'bail|required|min:4',
            'lang'   => 'max:2',
            'levels' => 'required|array|min:1',
        ]);

        $course->name  = $request->name;
        $course->color = $request->color;
        $course->lang  = $request->lang;
        $course->slug  = Str::slug($request->name, '-');
        $course->save();

        // Only sync levels within the co-admin's accessible curricula,
        // preserving any existing links to levels outside their scope.
        $accessibleLevelIds = $this->getAccessibleLevelIds();
        $selectedLevels = array_filter((array) $request->levels, fn($id) => in_array($id, $accessibleLevelIds));

        // Detach only the levels the co-admin manages, then re-attach selected ones
        $course->levels()->detach($accessibleLevelIds);
        $course->levels()->attach($selectedLevels);

        $this->logAction('updated_course', 'Course', $course->id, $course->name);

        return redirect()->route('co-admin.index')->with('message', __('The course has been modified.'));
    }

    public function destroyCourse(Course $course)
    {
        $this->authorizeCoAdmin();
        $this->authorizeCourseAccess($course);

        if ($course->posts()->count() === 0) {
            $courseName = $course->name;
            $courseId = $course->id;
            $course->delete();
            $this->logAction('deleted_course', 'Course', $courseId, $courseName);
            return redirect()->route('co-admin.index')->with('message', __('The course has been deleted.'));
        }

        return redirect()->route('co-admin.index')->with('warning', __('The course has posts attached. Please delete all of these posts or change their course before deleting this category.'));
    }

    // -------------------------------------------------------------------------
    // Types
    // -------------------------------------------------------------------------

    public function createType()
    {
        $this->authorizeCoAdmin();

        $type = new Type;
        $type->id = 0;
        $courses = $this->getAccessibleCourses();
        return view('co-admin.types.edit', compact('type', 'courses'));
    }

    public function storeType(Request $request)
    {
        $this->authorizeCoAdmin();

        $request->validate([
            'name'      => 'bail|required|min:3',
            'color'     => 'bail|required|min:4',
            'course_id' => 'required|integer',
        ]);

        // Ensure the selected course is accessible to the co-admin
        $accessibleCourseIds = $this->getAccessibleCourseIds();
        abort_unless(in_array($request->course_id, $accessibleCourseIds), 403, __('You do not have access to this course.'));

        $type = new Type;
        $type->name      = $request->name;
        $type->color     = $request->color;
        $type->slug      = Str::slug($request->name, '-');
        $type->course_id = $request->course_id;
        $type->save();

        $this->logAction('created_type', 'Type', $type->id, $type->name);

        return redirect()->route('co-admin.index')->with('message', __('The type has been created.'));
    }

    public function editType(Type $type)
    {
        $this->authorizeCoAdmin();
        $this->authorizeTypeAccess($type);

        $courses = $this->getAccessibleCourses();
        return view('co-admin.types.edit', compact('type', 'courses'));
    }

    public function updateType(Request $request, Type $type)
    {
        $this->authorizeCoAdmin();
        $this->authorizeTypeAccess($type);

        $request->validate([
            'name'      => 'bail|required|min:3',
            'color'     => 'bail|required|min:4',
            'course_id' => 'required|integer',
        ]);

        // Ensure the selected course is accessible to the co-admin
        $accessibleCourseIds = $this->getAccessibleCourseIds();
        abort_unless(in_array($request->course_id, $accessibleCourseIds), 403, __('You do not have access to this course.'));

        $type->name      = $request->name;
        $type->color     = $request->color;
        $type->slug      = Str::slug($request->name, '-');
        $type->course_id = $request->course_id;
        $type->save();

        $this->logAction('updated_type', 'Type', $type->id, $type->name);

        return redirect()->route('co-admin.index')->with('message', __('The type has been modified.'));
    }

    public function destroyType(Type $type)
    {
        $this->authorizeCoAdmin();
        $this->authorizeTypeAccess($type);

        if ($type->posts()->count() === 0) {
            $typeName = $type->name;
            $typeId = $type->id;
            $type->delete();
            $this->logAction('deleted_type', 'Type', $typeId, $typeName);
            return redirect()->route('co-admin.index')->with('message', __('The type has been deleted.'));
        }

        return redirect()->route('co-admin.index')->with('warning', __('The type has posts attached. Please delete all of these posts or change their type before deleting this category.'));
    }

    // -------------------------------------------------------------------------
    // Post Pack (LaTeX / PDF generation)
    // -------------------------------------------------------------------------

    public function packCreate()
    {
        abort_unless(config('features.latex_enabled', false), 404, __('The LaTeX feature is not enabled on this instance.'));
        $this->authorizeCoAdmin();

        $levelIds        = $this->getAccessibleLevelIds();
        $managedCurricula = auth()->user()->managedCurricula;

        $posts = Post::where('published', 1)
            ->whereIn('level_id', $levelIds)
            ->with(['user', 'course', 'level', 'files'])
            ->orderBy('course_id')
            ->orderBy('title')
            ->get();

        return view('co-admin.pack.create', compact('posts', 'managedCurricula'));
    }

    public function packGenerate(Request $request)
    {
        abort_unless(config('features.latex_enabled', false), 404, __('The LaTeX feature is not enabled on this instance.'));
        $this->authorizeCoAdmin();

        $request->validate([
            'pack_title'    => 'required|string|max:200',
            'post_ids'      => 'required|array|min:1',
            'post_ids.*'    => 'integer|exists:posts,id',
            'curriculum_id' => 'required|integer',
        ]);

        // Ensure the chosen curriculum is managed by this co-admin
        $curriculaIds = $this->getManagedCurriculaIds();
        abort_unless(in_array((int) $request->curriculum_id, $curriculaIds), 403, __('Access denied.'));

        $curriculum = Curriculum::findOrFail($request->curriculum_id);

        // Fetch posts in the order chosen by the user, restricting to accessible published posts
        $levelIds  = $this->getAccessibleLevelIds();
        $postsById = Post::whereIn('id', $request->post_ids)
            ->whereIn('level_id', $levelIds)
            ->where('published', 1)
            ->with(['user', 'files'])
            ->get()
            ->keyBy('id');

        $posts = collect($request->post_ids)
            ->filter(fn($id) => $postsById->has($id))
            ->map(fn($id) => $postsById->get($id))
            ->values()
            ->all();

        if (empty($posts)) {
            return back()->with('warning', __('No valid posts selected.'));
        }

        try {
            $service = new LatexPackService();
            $result  = $service->generate($request->pack_title, $posts, $curriculum);

            $pdfPath = $result['pdfPath'];
            $tmpDir  = $result['tmpDir'];

            // Read the file into memory so we can clean up before sending
            $pdfContent = file_get_contents($pdfPath);
            $service->cleanup($tmpDir);

            $filename = Str::slug($request->pack_title) . '.pdf';

            return response($pdfContent, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\RuntimeException $e) {
            return back()->with('warning', __('PDF generation failed.') . ' ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // Curriculum Settings
    // -------------------------------------------------------------------------

    public function updateCurriculumSettings(Request $request, Curriculum $curriculum)
    {
        $this->authorizeCoAdmin();
        abort_unless(in_array($curriculum->id, $this->getManagedCurriculaIds()), 403, __('You do not have access to this curriculum.'));

        $request->validate([
            'app_name' => 'nullable|string|max:100',
        ]);

        $curriculum->app_name = $request->app_name ?: null;
        $curriculum->save();

        $this->logAction('updated_curriculum_settings', 'Curriculum', $curriculum->id, $curriculum->name);

        return redirect()->route('co-admin.index', ['tab' => 'settings'])->with('message', __('Settings updated.'));
    }

    // -------------------------------------------------------------------------
    // Bulk ZIP Import
    // -------------------------------------------------------------------------

    public function bulkImportCreate()
    {
        $this->authorizeCoAdmin();

        $levels = $this->getAccessibleLevels();
        $courseIds = $this->getAccessibleCourseIds();
        $types = Type::whereIn('course_id', $courseIds)->get();

        return view('co-admin.bulk-import', compact('levels', 'types'));
    }

    public function bulkImportStore(Request $request)
    {
        $this->authorizeCoAdmin();

        $request->validate([
            'zip_file'  => 'required|file|mimes:zip',
            'level_id'  => 'required|integer|exists:levels,id',
            'type_id'   => 'required|integer|exists:types,id',
        ]);

        // Ensure the selected level and type belong to the co-admin's scope
        $accessibleLevelIds = $this->getAccessibleLevelIds();
        abort_unless(in_array((int) $request->level_id, $accessibleLevelIds), 403, __('You do not have access to this level.'));

        $accessibleCourseIds = $this->getAccessibleCourseIds();
        $type = Type::findOrFail($request->type_id);
        abort_unless(in_array($type->course_id, $accessibleCourseIds), 403, __('You do not have access to this type.'));

        $level = Level::findOrFail($request->level_id);
        $curriculum = $level->curriculum;

        // Create a secure temporary directory with restricted permissions
        $tmpBase = sys_get_temp_dir();
        $tmpDir  = tempnam($tmpBase, 'rscms_bulk_');
        unlink($tmpDir);
        mkdir($tmpDir, 0700, true);

        try {
            $zip     = new ZipArchive;
            $zipPath = $request->file('zip_file')->getRealPath();

            if ($zip->open($zipPath) !== true) {
                return back()->with('warning', __('Could not open the ZIP file.'));
            }

            // Extract each entry individually to guard against ZIP slip attacks
            $realTmpDir = realpath($tmpDir);
            // Maximum uncompressed size per entry: 50 MB
            $maxEntryBytes = 50 * 1024 * 1024;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);

                // Reject entries with obviously dangerous names
                if (str_contains($entryName, "\0")) {
                    continue;
                }

                // Guard against zip bombs: check uncompressed size before extracting
                $stat = $zip->statIndex($i);
                if ($stat !== false && $stat['size'] > $maxEntryBytes) {
                    continue;
                }

                // Build destination directory and confirm it stays within $tmpDir
                $destDir = $tmpDir . DIRECTORY_SEPARATOR . dirname($entryName);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0700, true);
                }
                $realDestDir = realpath($destDir);
                if ($realDestDir === false || strncmp($realDestDir, $realTmpDir, strlen($realTmpDir)) !== 0) {
                    continue; // Skip unsafe paths
                }

                // Skip directory entries
                if (str_ends_with($entryName, '/')) {
                    continue;
                }

                // Build and validate the final file path
                $targetFile = $realDestDir . DIRECTORY_SEPARATOR . basename($entryName);
                $realTargetDir = realpath(dirname($targetFile));
                if ($realTargetDir === false || strncmp($realTargetDir, $realTmpDir, strlen($realTmpDir)) !== 0) {
                    continue; // Skip unsafe file paths
                }

                file_put_contents($targetFile, $zip->getFromIndex($i));
            }
            $zip->close();

            $user = auth()->user();
            $importedCount = 0;
            $skippedCourses = [];

            // Allowed file extensions for primary posts
            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];

            // Build a map of accessible courses keyed by normalised name
            $accessibleCourses = $this->getAccessibleCourses()
                ->keyBy(fn(Course $c) => mb_strtolower(trim($c->name)));

            // Iterate over top-level directories in the extracted ZIP
            $iterator = new \DirectoryIterator($tmpDir);
            foreach ($iterator as $courseDir) {
                if ($courseDir->isDot() || !$courseDir->isDir()) {
                    continue;
                }

                $courseName = $courseDir->getFilename();
                // Skip macOS metadata folders
                if ($courseName === '__MACOSX') {
                    continue;
                }
                $courseKey = mb_strtolower(trim($courseName));
                if (!$accessibleCourses->has($courseKey)) {
                    $skippedCourses[] = $courseName;
                    continue;
                }

                $course = $accessibleCourses->get($courseKey);
                $folder = $curriculum->slug . '/' . $level->slug . '/' . $course->slug;

                // Iterate over files inside the course directory
                $fileIterator = new \DirectoryIterator($courseDir->getPathname());
                foreach ($fileIterator as $fileInfo) {
                    if ($fileInfo->isDot() || !$fileInfo->isFile()) {
                        continue;
                    }

                    $extension = strtolower($fileInfo->getExtension());

                    // Only process allowed file types
                    if (!in_array($extension, $allowedExtensions, true)) {
                        continue;
                    }

                    $titleRaw = pathinfo($fileInfo->getFilename(), PATHINFO_FILENAME);
                    $title    = $titleRaw;
                    $slug     = Str::slug($titleRaw, '-');

                    // Ensure slug uniqueness
                    $baseSlug    = $slug;
                    $slugCounter = 1;
                    while (Post::where('slug', $slug)->exists()) {
                        $slug = $baseSlug . '-' . $slugCounter++;
                    }

                    // Create the Post record
                    $post = new Post;
                    $post->title        = $title;
                    $post->description  = '';
                    $post->type_id      = $type->id;
                    $post->school_id    = $user->school_id;
                    $post->dark_version = false;
                    $post->published    = false;
                    $post->pinned       = false;
                    $post->thanks       = 0;
                    $post->cards        = false;
                    $post->slug         = $slug;
                    $post->course_id    = $course->id;
                    $post->level_id     = $level->id;
                    $post->user_id      = $user->id;
                    $post->save();

                    // Store the file using a stream to avoid loading it entirely into memory
                    $storedFilename = $post->id . '-' . $post->slug . '.light.' . $extension;
                    $storedPath     = $folder . '/' . $storedFilename;
                    $stream = fopen($fileInfo->getPathname(), 'rb');
                    Storage::disk('public')->put($storedPath, $stream);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }

                    // Create the File record (primary light)
                    $file = new File;
                    $file->type      = 'primary light';
                    $file->name      = $storedFilename;
                    $file->file_path = $storedPath;
                    $file->post_id   = $post->id;
                    $file->save();

                    // Dispatch thumbnail generation
                    $thumbnailFilename = $post->id . '-' . $post->slug . '.thumbnail.png';
                    dispatch(new CreateThumbnail($storedPath, $thumbnailFilename, $folder));

                    $this->logAction('bulk_imported_post', 'Post', $post->id, $post->title);

                    $importedCount++;
                }
            }
        } finally {
            // Always clean up the temporary directory
            $this->deleteTmpDir($tmpDir);
        }

        if ($importedCount === 0 && empty($skippedCourses)) {
            return redirect()->route('co-admin.index')->with('warning', __('No posts were imported. The ZIP may be empty or contain no matching course folders.'));
        }

        $message = trans_choice(':count post(s) imported successfully.', $importedCount, ['count' => $importedCount]);
        if (!empty($skippedCourses)) {
            $message .= ' ' . __('The following course folders were not found and were skipped: :courses.', ['courses' => implode(', ', $skippedCourses)]);
            return redirect()->route('co-admin.index')->with('warning', $message);
        }

        return redirect()->route('co-admin.index')->with('message', $message);
    }

    /**
     * Recursively delete a temporary directory.
     */
    private function deleteTmpDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($items as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }
        rmdir($dir);
    }
}
