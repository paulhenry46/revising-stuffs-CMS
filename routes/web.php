<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Public\ReadPostController;
use App\Http\Controllers\Public\ReadCardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\RssController;
use App\Http\Controllers\PushNotificationsController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Public\ReadUserController;
use App\Http\Controllers\PushNotif;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StepController;
use App\Models\School;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

//RSS Routes
Route::get('/rss/all', [RssController::class, 'posts'])->name('rss.posts');//General RSS including all the levels and courses
Route::get('/rss/{user}', [RssController::class, 'user'])->name('rss.user');//RSS specific to one user

//Routes USED to use the notifications push
if(env('FirebasePush')){
Route::patch('/fcm-token', [PushNotificationsController::class, 'updateToken'])->name('push.fcmToken');
}

//Public Routes
Route::name('post.public.')->group(function() {
    //The route to accces to all courses of all levels
    Route::get('/course/{curriculum_chosen}/{level_chosen}/{course_chosen}', [ReadPostController::class, 'course'])
    ->name('courseView')
    ->where(['level' => '[a-z0-9-]+'])
    ->where(['course' => '[a-z0-9-]+'])
    ->where(['curriculum' => '[a-z0-9-]+']);
    //Other routes
    Route::get('/news', [ReadPostController::class, 'news'])->name('news');
    Route::get('/library', [ReadPostController::class, 'library'])->name('library');
    Route::get('/favorites', [ReadPostController::class, 'favorites'])->name('favorites');

    Route::prefix('/post/{slug}-{post}')->where(['slug' => '[a-z0-9-]+'])->group(function () {
            Route::get('/', [ReadPostController::class, 'view'])->name('view');
            Route::post('/addComment', [CommentController::class, 'store'])->name('comment.create');

            Route::prefix('/cards')->name('cards.')->group(function() {
                Route::get('/', [ReadCardController::class, 'show'])->name('show');
                Route::get('/export', [CardController::class, 'export'])->name('export');
                Route::get('/learn', [ReadCardController::class, 'learn'])->name('learn');
                Route::get('/quiz', [ReadCardController::class, 'quiz'])->name('quiz');
                    });
            });
        });

        Route::prefix('/u/{user}')->group(function () {
            Route::get('/', [ReadUserController::class, 'view'])->name('users.public.view');
            });

Route::name('about.')->prefix('/about')->group(function() {
        Route::get('/', [AboutController::class, 'index'])->name('about');
        Route::get('/licensing', [AboutController::class, 'licensing'])->name('licensing');
        Route::get('/legal', [AboutController::class, 'legal'])->name('legal');
                });

//Users Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/user/cursus', [UserController::class, 'cursusForm'])->name('user.update-cursus-form');
    Route::post('/user/cursus', [UserController::class, 'cursusUpdate'])->name('user.update-cursus');
    Route::group(['middleware' => ['can:manage courses']], function () {
        Route::resource('courses', CourseController::class)->except(['show']);
        Route::resource('types', TypeController::class)->except(['show']);
    });
    Route::group(['middleware' => ['can:manage all comments']], function () {
        Route::get('/comments/moderate', [CommentController::class, 'moderate'])->name('comments.moderate');
    });
    Route::group(['middleware' => ['can:publish all posts']], function () {
        Route::get('/posts/moderate', [PostController::class, 'moderate'])->name('posts.moderate');
    });
    Route::group(['middleware' => ['can:manage all posts']], function () {
        Route::get('/posts/all', [PostController::class, 'all'])->name('posts.all');
        Route::get('/settings', [AdminSettingsController::class, 'show'])->name('settings');
        Route::get('/settings/backups/files', [AdminSettingsController::class, 'createZipOfStorage'])->name('settings.downloadFiles');
        Route::get('/settings/backups/DB', [AdminSettingsController::class, 'createBackupOfDB'])->name('settings.downloadDB');
    });
    Route::group(['middleware' => ['can:manage levels']], function () {
        Route::resource('levels', LevelController::class)->except(['show']);
    });
    Route::group(['middleware' => ['can:manage users']], function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
    Route::group(['middleware' => ['can:manage curricula']], function () {
        Route::resource('curricula', CurriculumController::class)->except(['show', 'index']);
    });

    Route::group(['middleware' => ['can:manage schools']], function () {
        Route::resource('schools', SchoolController::class)->except(['show', 'index']);
    });

    //Groups
    Route::group(['middleware' => ['can:manage groups']], function () {
        Route::prefix('/groups')->group(function () {
            Route::get('/', [GroupController::class, 'index'])->name('groups.index');
            Route::get('/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
            Route::get('/create', [GroupController::class, 'create'])->name('groups.create');
        });
    });
    
    //Used to send datas for quiz-mode in cards
    Route::post('/step', [StepController::class, 'create'])->name('step.add');

    Route::group(['middleware' => ['can:manage own posts']], function () {
        //Manage the posts
        Route::resource('posts', PostController::class);

        //See the comments on your posts
        Route::get('/comments', [CommentController::class, 'index'])->name('comments.show');

        //Manage the files attached to the posts
        Route::controller(FileController::class)->prefix('/posts/{post}/files')->name('files.')->group(function () {
            Route::get('/', 'index')->name('index');

            Route::prefix('/complementary')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::delete('/{file}/delete', 'destroy')->name('destroy');
            });
            Route::prefix('/primary')->group(function () {
                Route::get('/create', 'createPrimary')->name('primary.create');
                Route::post('/', 'storePrimary')->name('primary.store');
                
              
                
                Route::get('/update', 'editPrimary')->name('edit');
                Route::get('/update', 'editPrimary')->name('edit');
                Route::put('/edit', 'updatePrimary')->name('update');
            });
        });

        //Manage the cards attached to the posts
        Route::controller(CardController::class)->prefix('/posts/{post}/cards')->name('cards.')->group(function () {
            Route::get('/', 'index')->name('index');

            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');

            Route::get('/import', 'import')->name('import');
            Route::post('/import', 'storeImport')->name('store.import');

            Route::delete('/{card}/delete', 'destroy')->name('destroy');

            Route::get('/{card}/update', 'edit')->name('edit');
            Route::put('/{card}/edit', 'update')->name('update');
            Route::post('/step', 'step')->name('step');
        });
    });
});
