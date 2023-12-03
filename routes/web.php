<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AboutController;

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
});

//Public Routes
Route::name('post.public.')->group(function() {
    //The route to accces to all courses of all levels
    Route::get('/courses/{level_chosen}/{course_chosen}', [PostController::class, 'CourseView'])
    ->name('courseView')
    ->where(['level' => '[a-z0-9-]+'])
    ->where(['course' => '[a-z0-9-]+']);
    //Other routes
    Route::get('/news', [PostController::class, 'viewNews'])->name('news');
    Route::get('/library', [PostController::class, 'Library'])->name('library');
    Route::get('/favorites', [PostController::class, 'viewFavorites'])->name('favorites');

    Route::prefix('/post/{slug}-{post}')->where(['slug' => '[a-z0-9-]+'])->group(function () {
            Route::get('/', [PostController::class, 'viewPublic'])->name('view');
            Route::post('/addComment', [CommentController::class, 'store'])->name('comment.create');

            Route::prefix('/cards')->name('cards.')->group(function() {
                Route::get('/', [CardController::class, 'showPublic'])->name('show');
                Route::get('/learn', [CardController::class, 'learnPublic'])->name('learn');
                Route::get('/quiz', [CardController::class, 'quizPublic'])->name('quiz');
                    });
            });
        });
Route::name('about.')->prefix('/about')->group(function() {
        Route::get('/licensing', [AboutController::class, 'licensing'])->name('licensing');
     // Route::get('/team', [PostController::class, 'Library'])->name('team');
            
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
    Route::group(['middleware' => ['can:manage courses']], function () {
        Route::resource('courses', CourseController::class)->except(['show']);
    });
    Route::group(['middleware' => ['can:manage all comments']], function () {
        Route::get('/moderateComments', [CommentController::class, 'indexModerator'])->name('comments.moderate');
    });
    Route::group(['middleware' => ['can:manage all posts']], function () {
        Route::get('/moderatePosts', [PostController::class, 'indexModerator'])->name('posts.moderate');
    });
    Route::group(['middleware' => ['can:manage levels']], function () {
        Route::resource('levels', LevelController::class)->except(['show']);
    });
    Route::group(['middleware' => ['can:manage users']], function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
    Route::group(['middleware' => ['can:manage own posts']], function () {
        //Manage the posts
        Route::resource('posts', PostController::class);

        //See the comments on your posts
        Route::get('/comments', [CommentController::class, 'index'])->name('comments.show');

        //Manage the files attached to the posts
        Route::controller(FileController::class)->prefix('/posts/{post}/files')->name('files.')->group(function () {
            Route::get('/', 'index')->name('index');

            Route::prefix('/Complementary')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::delete('/{file}/delete', 'destroy')->name('destroy');
            });
            Route::prefix('/Primary')->group(function () {
                Route::get('/create', 'createPrimary')->name('primary.create');
                Route::post('/', 'storePrimary')->name('primary.store');
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
        });
    });
});
