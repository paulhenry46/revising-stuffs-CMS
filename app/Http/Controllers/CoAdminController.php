<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\Level;
use App\Models\Post;
use App\Models\Type;
use App\Models\File;
use App\Models\Card;
use App\Models\Deck;
use App\Models\User;
use App\Jobs\CreateThumbnail;
use App\Services\LatexPackService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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

        return redirect()->route('co-admin.index')->with('message', __('The course has been modified.'));
    }

    public function destroyCourse(Course $course)
    {
        $this->authorizeCoAdmin();
        $this->authorizeCourseAccess($course);

        if ($course->posts()->count() === 0) {
            $course->delete();
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

        return redirect()->route('co-admin.index')->with('message', __('The type has been modified.'));
    }

    public function destroyType(Type $type)
    {
        $this->authorizeCoAdmin();
        $this->authorizeTypeAccess($type);

        if ($type->posts()->count() === 0) {
            $type->delete();
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

        return redirect()->route('co-admin.index', ['tab' => 'settings'])->with('message', __('Settings updated.'));
    }

    // -------------------------------------------------------------------------
    // Export / Import
    // -------------------------------------------------------------------------

    /**
     * Show the export form for selecting a curriculum.
     */
    public function showExport()
    {
        $this->authorizeCoAdmin();
        $managedCurricula = auth()->user()->managedCurricula;
        return view('co-admin.export', compact('managedCurricula'));
    }

    /**
     * Export the curriculum selected in the export form.
     */
    public function exportSelectedCurriculum(Request $request)
    {
        $this->authorizeCoAdmin();

        $request->validate([
            'curriculum_id' => 'required|integer',
        ]);

        $curriculaIds = $this->getManagedCurriculaIds();
        abort_unless(in_array((int) $request->curriculum_id, $curriculaIds), 403, __('Access denied.'));

        $curriculum = Curriculum::findOrFail($request->curriculum_id);
        return $this->exportCurriculum($curriculum);
    }

    /**
     * Export the selected curriculum as a ZIP archive containing:
     *  - data.json  (curriculum, levels, courses, types, posts, files, users)
     *  - All PDF / attachment files uploaded for that curriculum.
     */
    public function exportCurriculum(Curriculum $curriculum)
    {
        $this->authorizeCoAdmin();
        abort_unless(in_array($curriculum->id, $this->getManagedCurriculaIds()), 403, __('You do not have access to this curriculum.'));

        // ── Collect data ──────────────────────────────────────────────────────
        $levels  = $curriculum->levels()->get();
        $levelIds = $levels->pluck('id')->toArray();

        // Courses linked to at least one level of this curriculum
        $courses = Course::whereHas('levels', fn($q) => $q->whereIn('levels.id', $levelIds))->get();
        $courseIds = $courses->pluck('id')->toArray();

        $types = Type::whereIn('course_id', $courseIds)->get();

        $posts = Post::whereIn('level_id', $levelIds)->get();

        $postIds = $posts->pluck('id')->toArray();

        // Files attached to those posts
        $files = File::whereIn('post_id', $postIds)
            ->get()
            ->filter(fn($file) => !$this->isThumbnailPath($file->file_path))
            ->values();

        // Decks and cards attached to posts (cards export)
        $decks = Deck::where('deckable_type', Post::class)
            ->whereIn('deckable_id', $postIds)
            ->get();
        $deckIds = $decks->pluck('id')->toArray();

        $cards = Card::whereIn('post_id', $postIds)->get();
        $cardIds = $cards->pluck('id')->toArray();

        $cardDeckPivot = DB::table('card_deck')
            ->whereIn('deck_id', $deckIds)
            ->whereIn('card_id', $cardIds)
            ->get()
            ->map(fn($row) => [
                'card_id' => $row->card_id,
                'deck_id' => $row->deck_id,
            ])
            ->toArray();

        // Users who authored posts in this curriculum (email + name only)
        $users = User::whereIn('id', $posts->pluck('user_id')->filter()->unique()->toArray())
            ->get(['id', 'name', 'email']);

        // Courses → levels pivot for this curriculum only
        $courseLevelPivot = [];
        foreach ($courses as $course) {
            foreach ($course->levels as $level) {
                if (in_array($level->id, $levelIds)) {
                    $courseLevelPivot[] = ['course_id' => $course->id, 'level_id' => $level->id];
                }
            }
        }

        $postsForExport = $posts->map(function (Post $post) {
            return [
                'id'           => $post->id,
                'title'        => $post->title,
                'description'  => $post->description,
                'quizlet_url'  => $post->quizlet_url,
                'dark_version' => (bool) $post->dark_version,
                'cards'        => (bool) $post->cards,
                'thanks'       => $post->thanks,
                'published'    => (bool) $post->published,
                'pinned'       => (bool) $post->pinned,
                'slug'         => $post->slug,
                'course_id'    => $post->course_id,
                'level_id'     => $post->level_id,
                'user_id'      => $post->user_id,
                'type_id'      => $post->type_id,
                'group_id'     => $post->group_id,
                'early_access' => (bool) $post->early_access,
                'school_id'    => $post->school_id,
                'created_at'   => optional($post->created_at)->toIso8601String(),
                'updated_at'   => optional($post->updated_at)->toIso8601String(),
            ];
        })->values()->toArray();

        $data = [
            'curriculum'        => $curriculum->toArray(),
            'levels'            => $levels->toArray(),
            'courses'           => $courses->toArray(),
            'course_level'      => $courseLevelPivot,
            'types'             => $types->toArray(),
            'posts'             => $postsForExport,
            'files'             => $files->toArray(),
            'decks'             => $decks->map(fn($deck) => [
                'id'            => $deck->id,
                'name'          => $deck->name,
                'slug'          => $deck->slug,
                'color'         => $deck->color,
                'deckable_id'   => $deck->deckable_id,
                'deckable_type' => $deck->deckable_type,
            ])->values()->toArray(),
            'cards'             => $cards->map(fn($card) => [
                'id'      => $card->id,
                'front'   => $card->front,
                'back'    => $card->back,
                'post_id' => $card->post_id,
            ])->values()->toArray(),
            'card_deck'         => $cardDeckPivot,
            'users'             => $users->toArray(),
            'exported_at'       => now()->toIso8601String(),
            'rscms_version'     => '1.0',
        ];

        // ── Build ZIP ─────────────────────────────────────────────────────────
        $zipFileName = 'RSCMS-curriculum-' . Str::slug($curriculum->name) . '-' . date('Y-m-d') . '.zip';
        $zipPath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive;
        abort_unless($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true, 500, __('Could not create ZIP archive.'));

        // Add JSON data file
        $zip->addFromString('data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Add the physical files stored in the public disk
        foreach ($files as $file) {
            $storagePath = Storage::disk('public')->path($file->file_path);
            if (file_exists($storagePath)) {
                $zip->addFile($storagePath, 'files/' . $file->file_path);
            }
        }

        $zip->close();

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Show the curriculum import form.
     */
    public function showImport()
    {
        $this->authorizeCoAdmin();
        $managedCurricula = auth()->user()->managedCurricula;
        return view('co-admin.import', compact('managedCurricula'));
    }

    /**
     * Import a curriculum from a ZIP archive previously exported by RSCMS.
     *
     * The ZIP must contain:
     *  - data.json
     *  - files/{original_file_path}   (optional, best-effort)
     */
    public function importCurriculum(Request $request)
    {
        $this->authorizeCoAdmin();

        $request->validate([
            'zip_file' => 'required|file|mimes:zip|max:102400',
        ]);

        $uploadedZip = $request->file('zip_file');
        $tmpDir = storage_path('app/import-tmp-' . Str::uuid());
        mkdir($tmpDir, 0700, true);

        try {
            $zip = new ZipArchive;
            abort_unless($zip->open($uploadedZip->getRealPath()) === true, 422, __('Could not open the ZIP archive.'));
            $zip->extractTo($tmpDir);
            $zip->close();

            $jsonPath = $tmpDir . '/data.json';
            abort_unless(file_exists($jsonPath), 422, __('The ZIP archive does not contain a valid data.json file.'));

            // Prevent memory exhaustion from an unexpectedly large JSON file
            abort_unless(filesize($jsonPath) < 50 * 1024 * 1024, 422, __('The data.json file is too large to process.'));

            $data = json_decode(file_get_contents($jsonPath), true);
            abort_unless(is_array($data) && isset($data['curriculum'], $data['levels'], $data['courses'], $data['posts']), 422, __('The data.json file is invalid or corrupted.'));

            // ── Import curriculum ─────────────────────────────────────────────
            $newCurriculum = Curriculum::create([
                'name'        => $data['curriculum']['name'],
                'slug'        => $this->uniqueSlug('curricula', $data['curriculum']['slug']),
                'description' => $data['curriculum']['description'] ?? null,
                'app_name'    => $data['curriculum']['app_name'] ?? null,
            ]);

            // Attach the importing co-admin to the new curriculum
            auth()->user()->managedCurricula()->attach($newCurriculum->id);

            // ── Import levels ─────────────────────────────────────────────────
            $levelIdMap = []; // old id → new id
            foreach ($data['levels'] as $levelData) {
                $newLevel = new Level;
                $newLevel->name          = $levelData['name'];
                $newLevel->slug          = $this->uniqueSlug('levels', $levelData['slug']);
                $newLevel->curriculum_id = $newCurriculum->id;
                $newLevel->save();
                $levelIdMap[$levelData['id']] = $newLevel->id;
            }

            // ── Import courses ────────────────────────────────────────────────
            $courseIdMap = []; // old id → new id
            foreach ($data['courses'] as $courseData) {
                // Skip the "all courses" sentinel (id=1)
                if ($courseData['id'] === 1) {
                    continue;
                }
                $newCourse = new Course;
                $newCourse->name  = $courseData['name'];
                $newCourse->slug  = $this->uniqueSlug('courses', $courseData['slug']);
                $newCourse->color = $courseData['color'];
                $newCourse->lang  = $courseData['lang'] ?? null;
                $newCourse->save();
                $courseIdMap[$courseData['id']] = $newCourse->id;
            }

            // Restore course–level pivot
            foreach ($data['course_level'] ?? [] as $pivot) {
                $newCourseId = $courseIdMap[$pivot['course_id']] ?? null;
                $newLevelId  = $levelIdMap[$pivot['level_id']] ?? null;
                if ($newCourseId && $newLevelId) {
                    $course = Course::find($newCourseId);
                    $course?->levels()->syncWithoutDetaching([$newLevelId]);
                }
            }

            // ── Import types ──────────────────────────────────────────────────
            $typeIdMap = []; // old id → new id
            foreach ($data['types'] as $typeData) {
                $newCourseId = $courseIdMap[$typeData['course_id']] ?? null;
                if (!$newCourseId) {
                    continue;
                }
                $newType = new Type;
                $newType->name      = $typeData['name'];
                $newType->slug      = $this->uniqueSlug('types', $typeData['slug']);
                $newType->color     = $typeData['color'];
                $newType->course_id = $newCourseId;
                $newType->save();
                $typeIdMap[$typeData['id']] = $newType->id;
            }

            // ── Import users (email lookup / create placeholder) ───────────────
            $userIdMap = []; // old id → new User id
            foreach ($data['users'] ?? [] as $userData) {
                $existingUser = User::where('email', $userData['email'])->first();
                if ($existingUser) {
                    $userIdMap[$userData['id']] = $existingUser->id;
                } else {
                    // Create a placeholder account; the user can reset the password
                    $newUser = User::create([
                        'name'     => $userData['name'],
                        'email'    => $userData['email'],
                        'password' => bcrypt(Str::random(32)),
                    ]);
                    $userIdMap[$userData['id']] = $newUser->id;
                }
            }

            // ── Import posts ──────────────────────────────────────────────────
            $postIdMap = []; // old id → new id
            foreach ($data['posts'] as $postData) {
                $newLevelId  = $levelIdMap[$postData['level_id']] ?? null;
                $newCourseId = $courseIdMap[$postData['course_id']] ?? null;
                $newTypeId   = $typeIdMap[$postData['type_id']] ?? null;
                $newUserId   = $userIdMap[$postData['user_id']] ?? null;

                if (!$newLevelId || !$newCourseId) {
                    continue;
                }

                if (!$newTypeId) {
                    $newTypeId = Type::where('course_id', $newCourseId)->value('id');
                }
                if (!$newTypeId) {
                    continue;
                }

                $newPost = new Post;
                $newPost->title        = $postData['title'];
                $newPost->description  = $postData['description'] ?? $postData['title'];
                $newPost->quizlet_url  = $postData['quizlet_url'] ?? null;
                $newPost->dark_version = (bool) ($postData['dark_version'] ?? false);
                $newPost->cards        = (bool) ($postData['cards'] ?? false);
                $newPost->thanks       = $postData['thanks'] ?? 0;
                $newPost->published    = (bool) ($postData['published'] ?? false);
                $newPost->pinned       = (bool) ($postData['pinned'] ?? false);
                $newPost->early_access = (bool) ($postData['early_access'] ?? false);
                $newPost->slug         = $this->uniqueSlug('posts', $postData['slug'] ?? Str::slug($postData['title']));
                $newPost->course_id    = $newCourseId;
                $newPost->level_id     = $newLevelId;
                $newPost->type_id      = $newTypeId;
                $newPost->user_id      = $newUserId ?? auth()->id();
                $newPost->group_id     = $postData['group_id'] ?? 2;
                $newPost->school_id    = null;
                $newPost->save();
                $postIdMap[$postData['id']] = $newPost->id;
            }

            // ── Import decks ─────────────────────────────────────────────────
            $deckIdMap = []; // old id → new id
            foreach ($data['decks'] ?? [] as $deckData) {
                $oldPostId = $deckData['deckable_id'] ?? null;
                $newPostId = $oldPostId ? ($postIdMap[$oldPostId] ?? null) : null;
                if (!$newPostId) {
                    continue;
                }

                $newDeck = new Deck;
                $newDeck->name          = $deckData['name'] ?? 'main';
                $newDeck->slug          = $deckData['slug'] ?? null;
                $newDeck->color         = $deckData['color'] ?? null;
                $newDeck->deckable_id   = $newPostId;
                $newDeck->deckable_type = Post::class;
                $newDeck->save();

                $deckIdMap[$deckData['id']] = $newDeck->id;
            }

            // ── Import cards ─────────────────────────────────────────────────
            $cardIdMap = []; // old id → new id
            foreach ($data['cards'] ?? [] as $cardData) {
                $newPostId = $postIdMap[$cardData['post_id']] ?? null;
                if (!$newPostId) {
                    continue;
                }

                $newCard = new Card;
                $newCard->front   = $cardData['front'];
                $newCard->back    = $cardData['back'];
                $newCard->post_id = $newPostId;
                $newCard->save();
                $cardIdMap[$cardData['id']] = $newCard->id;
            }

            foreach ($data['card_deck'] ?? [] as $pivot) {
                $newCardId = $cardIdMap[$pivot['card_id']] ?? null;
                $newDeckId = $deckIdMap[$pivot['deck_id']] ?? null;
                if ($newCardId && $newDeckId) {
                    $deck = Deck::find($newDeckId);
                    $deck?->cards()->syncWithoutDetaching([$newCardId]);
                }
            }

            $postsWithCards = collect($data['cards'] ?? [])
                ->pluck('post_id')
                ->unique()
                ->toArray();
            foreach ($postsWithCards as $oldPostId) {
                $newPostId = $postIdMap[$oldPostId] ?? null;
                if ($newPostId) {
                    Post::whereKey($newPostId)->update(['cards' => true]);
                }
            }

            // ── Import files (metadata + physical files) ───────────────────────
            $primaryFilesByPost = [];
            foreach ($data['files'] ?? [] as $fileData) {
                if (empty($fileData['file_path']) || $this->isThumbnailPath($fileData['file_path'] ?? '')) {
                    continue;
                }
                $newPostId = $postIdMap[$fileData['post_id']] ?? null;
                if (!$newPostId) {
                    continue;
                }

                // Try to remap the file path to the new curriculum/level/course slugs
                $newFilePath = $this->remapFilePath($fileData['file_path'], $data, $levelIdMap, $courseIdMap, $newCurriculum);

                // Copy physical file from the extracted ZIP using streams to avoid memory pressure
                $extractedFilePath = $tmpDir . '/files/' . $fileData['file_path'];
                if (file_exists($extractedFilePath)) {
                    $stream = fopen($extractedFilePath, 'rb');
                    Storage::disk('public')->put($newFilePath, $stream);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }

                    $fileType = (string) ($fileData['type'] ?? '');
                    if (Str::contains($fileType, 'primary light')) {
                        $primaryFilesByPost[$newPostId] = $newFilePath;
                    } elseif (Str::contains($fileType, 'primary') && !isset($primaryFilesByPost[$newPostId])) {
                        $primaryFilesByPost[$newPostId] = $newFilePath;
                    }
                }

                $newFile = new File;
                $newFile->name           = $fileData['name'];
                $newFile->file_path      = $newFilePath;
                $newFile->type           = $fileData['type'];
                $newFile->post_id        = $newPostId;
                $newFile->download_count = 0;
                $newFile->save();
            }

            // Rebuild thumbnails for imported posts
            foreach ($primaryFilesByPost as $newPostId => $pdfPath) {
                $post = Post::find($newPostId);
                if (!$post) {
                    continue;
                }
                $folder = $post->level->curriculum->slug . '/' . $post->level->slug . '/' . $post->course->slug;
                $filename_thumbnail = $post->id . '-' . $post->slug . '.thumbnail.png';
                dispatch(new CreateThumbnail($pdfPath, $filename_thumbnail, $folder));
            }
        } finally {
            // Clean up temp directory
            $this->rmdirRecursive($tmpDir);
        }

        return redirect()->route('co-admin.index', ['tab' => 'settings'])->with('message', __('Curriculum imported successfully.'));
    }

    // -------------------------------------------------------------------------
    // Private helpers for import
    // -------------------------------------------------------------------------

    /**
     * Return a slug that is unique in the given table, appending a numeric
     * suffix if necessary.
     */
    private function uniqueSlug(string $table, string $base): string
    {
        $slug      = $base;
        $suffix    = 1;
        while (DB::table($table)->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix++;
        }
        return $slug;
    }

    /**
     * Remap a file_path from the exported curriculum to the new curriculum's
     * slug/level/course structure.
     *
     * Original format: {curriculum_slug}/{level_slug}/{course_slug}/{filename}
     */
    private function remapFilePath(string $originalPath, array $data, array $levelIdMap, array $courseIdMap, Curriculum $newCurriculum): string
    {
        $parts = explode('/', $originalPath);
        if (count($parts) < 4) {
            return $newCurriculum->slug . '/' . implode('/', array_slice($parts, 1));
        }

        // Identify old level slug and course slug from the path
        $oldLevelSlug  = $parts[1] ?? null;
        $oldCourseSlug = $parts[2] ?? null;
        $filename      = implode('/', array_slice($parts, 3));

        // Find matching new level and course slugs
        $newLevelSlug  = $oldLevelSlug;
        $newCourseSlug = $oldCourseSlug;

        foreach ($data['levels'] as $levelData) {
            if ($levelData['slug'] === $oldLevelSlug && isset($levelIdMap[$levelData['id']])) {
                $newLevel = Level::find($levelIdMap[$levelData['id']]);
                if ($newLevel) {
                    $newLevelSlug = $newLevel->slug;
                }
                break;
            }
        }

        foreach ($data['courses'] as $courseData) {
            if ($courseData['slug'] === $oldCourseSlug && isset($courseIdMap[$courseData['id']])) {
                $newCourse = Course::find($courseIdMap[$courseData['id']]);
                if ($newCourse) {
                    $newCourseSlug = $newCourse->slug;
                }
                break;
            }
        }

        return $newCurriculum->slug . '/' . $newLevelSlug . '/' . $newCourseSlug . '/' . $filename;
    }

    /**
     * Determine whether a file path represents a thumbnail file.
     */
    private function isThumbnailPath(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        return Str::contains($path, '.thumbnail.')
            || Str::endsWith($path, 'thumbnail.png');
    }

    /**
     * Recursively remove a directory and its contents.
     */
    private function rmdirRecursive(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->rmdirRecursive($path) : unlink($path);
        }
        rmdir($dir);
    }
}
