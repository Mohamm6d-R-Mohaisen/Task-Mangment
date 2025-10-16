
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\CommentController;


    /* ------------------------------------- Auth Routes --------------------------------- */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('show-login');
        Route::post('login', [LoginController::class, 'login'])->name('login');
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    });
   /* ------------------------------------- Admin Dashboard --------------------------------- */
   Route::group(['middleware' => [ 'admin', 'auth:admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {


    Route::get('/home', [HomeController::class, 'index'])->name('home');
    /* ------------------------------------- Settings Routes --------------------------------- */
        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', [SettingController::class , 'index'])->name('index');
        Route::post('update', [SettingController::class , 'update'])->name('update');
    });
    /* ------------------------------------- Settings Routes --------------------------------- */
      /* ------------------------------------- User Routes --------------------------------- */
      Route::resource('users', UserController::class);
      Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
          Route::get('data/datatables', [UserController::class, 'datatable'])->name('datatable');
          Route::post('activate/{id}', [UserController::class, 'activate'])->name('active');
          Route::post('bluck/delete', [UserController::class , 'bluckDestroy'])->name('bluck_delete');
      });
            /* ------------------------------------- User Routes --------------------------------- */

      /* ------------------------------------- Admin Routes --------------------------------- */
      Route::resource('admins', AdminController::class);
      Route::group(['prefix' => 'admins', 'as' => 'admins.'], function () {
          Route::get('data/datatables', [AdminController::class, 'datatable'])->name('datatable');
          Route::post('activate/{id}', [AdminController::class, 'activate'])->name('active');
          Route::post('bluck/delete', [AdminController::class , 'bluckDestroy'])->name('bluck_delete');
      });

        /* ------------------------------------- Role Routes --------------------------------- */
        Route::resource('roles', RoleController::class);
        Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
            Route::get('data/datatables', [RoleController::class, 'datatable'])->name('datatable');
        });
        /* ------------------------------------- Role Routes --------------------------------- */


       /* ------------------------------------- Project Routes --------------------------------- */
       Route::resource('projects', ProjectController::class);
       Route::group(['prefix' => 'projects', 'as' => 'projects.'], function () {
           Route::get('data/datatables', [ProjectController::class , 'datatable'])->name('datatable');
       });
       /* ------------------------------------- Project Routes --------------------------------- */

  /* ------------------------------------- Task Routes --------------------------------- */
       Route::resource('tasks', TaskController::class);
       Route::group(['prefix' => 'tasks', 'as' => 'tasks.'], function () {
           Route::get('data/datatables', [TaskController::class , 'datatable'])->name('datatable');
       });
       /* ------------------------------------- Task Routes --------------------------------- */
 /* ------------------------------------- Comments Routes --------------------------------- */
       Route::resource('comments', CommentController::class);
       Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
       Route::get('data/datatables', [CommentController::class , 'datatable'])->name('datatable');
       });
       /* ------------------------------------- Comment Routes --------------------------------- */

   });
