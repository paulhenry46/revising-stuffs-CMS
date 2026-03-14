<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Level;
use App\Models\Type;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

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

        return view('co-admin.index', compact('courses', 'types'));
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
}
