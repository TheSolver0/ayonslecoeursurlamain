<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\{AuthController,DashboardController,PostController,AlbumController,SettingController};
Route::get('/',[PublicController::class,'home'])->name('home');
Route::get('/actualites',[PublicController::class,'news'])->name('news');
Route::get('/actualites/{slug}',[PublicController::class,'article'])->name('article');
Route::get('/galerie',[PublicController::class,'gallery'])->name('gallery');
Route::get('/galerie/{slug}',[PublicController::class,'album'])->name('album');
Route::middleware('guest')->group(function(){
 Route::get('/admin/connexion',[AuthController::class,'login'])->name('login');
 Route::post('/admin/connexion',[AuthController::class,'authenticate'])->middleware('throttle:20,1')->name('login.store');
});
Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function(){
 Route::get('/',DashboardController::class)->name('dashboard');
 Route::post('/deconnexion',[AuthController::class,'logout'])->name('logout');
 Route::put('/mot-de-passe',[AuthController::class,'password'])->name('password');
 Route::resource('posts',PostController::class)->except('show');
 Route::delete('/posts/{post}/photos/{photo}',[PostController::class,'removePhoto'])->name('post-photos.destroy');
 Route::resource('albums',AlbumController::class)->except('show');
 Route::put('/albums/{album}/photos/{photo}',[AlbumController::class,'photo'])->name('photos.update');
 Route::delete('/albums/{album}/photos/{photo}',[AlbumController::class,'removePhoto'])->name('photos.destroy');
 Route::get('/parametres',[SettingController::class,'edit'])->name('settings.edit');
 Route::put('/parametres',[SettingController::class,'update'])->name('settings.update');
});
