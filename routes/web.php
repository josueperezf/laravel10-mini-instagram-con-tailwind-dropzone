<?php

use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SeguidorController;

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

// la siguiente linea se comento, para que para aprender algo nuevo, el profesor dijo que como HomeController tendra un solo metodo, se le puede crear un metodo __invoke() que es como tipo contructor, se ejecuta automaticamente llamemos a la clase HomeController
// Route::get('/', [HomeController::class, 'index'])->name('home');
// la siguiente linea es igual a la anterior comentada, pero en esta el cambiamos el nombre del metodo del controlador, de index() por __invoke que se ejecuta automaticamente
Route::get('/', HomeController::class)->name('home')->middleware('auth');

// si deseamos cambiar la ruta, en vez de ir a cada lugar, solo falta cambiar aqui, ya qye como tiene name, todo se ajusta, es buena practica
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']); //->name('login.store');

Route::post('/logout', [LogoutController::class, 'store'])->name('logout');


/** INICIO PARA RUTAS DE PERFIL */
//ejemplo http://localhost/editar-perfil
Route::get('/editar-perfil', [PerfilController::class, 'index'])->name('perfil.index')->middleware('auth');
Route::post('/editar-perfil', [PerfilController::class, 'store'])->name('perfil.store')->middleware('auth');
/** FIN PARA RUTAS DE PERFIL */


// importante, como colocamos entre llaves el nombre de un modelo, laravel internamente hara una consulta a la base de datos de un usuario con el id que le pasen
// route model binding
// de la siguiente forma comentada busca el usuario por el id
// Route::get('/{user}', [PostController::class, 'index'])->name('posts.index')->middleware('auth');
// de esta forma busca el usuario por un campo en particular
Route::get('/{user:username}', [PostController::class, 'index'])->name('posts.index')->middleware('auth');

Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');

// la siguiente ruta es algo como josueperezf/post/2 ejemplo
// intenamente laravel va a la base de datos y busca el usuario por username, y busca un post por ese id
// a este metodo pueden entrar sin estar autenticado, pero el formulario de comentario no sera visible, ademas no podra dar like
Route::get('/{user:username}/posts/{post}', [PostController::class, 'show'])->name('posts.show'); //
Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy')->middleware('auth');


// PARA COMENTARIOS - debe estar logueado el usuario
// para guardar los comentarios, segun el profesor, lo hara en esta ruta pero en vez de ir al controlador post, va al controlador comentario
Route::post('/{user:username}/posts/{post}', [ComentarioController::class, 'store'])->name('comentarios.store')->middleware('auth');


// Eliminar post

Route::post('/imagenes', [ImagenController::class, 'store'])->name('imagenes.store')->middleware('auth');




/* INICIO PARA LIKES */
// para el manejo de los likes, recordemos que se coloca {post} para que laravel internamente haga una busqueda en la base de dato de ese post y me lo de en el metodo del controlador
// para mi deberi de llamarse 'like.store' en lugar de posts.like.store, pero lo mantengo como la clase
// ->middleware('auth'); es para estar autenticado
Route::post('/posts/{post}/likes', [LikeController::class, 'store'])->name('posts.like.store')->middleware('auth');

// para eliminar un like de un post
Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy'])->name('posts.like.destroy')->middleware('auth');

/* FIN PARA LIKES */




/** INICIO DE SEGUIDORES DE USUARIOS */
// el nomnbre del metodo lo decidi yo, el profesor iba a colocar index y no le encontre sentido, asi que será store porque asi lo decidi
Route::post('/{user:username}/seguir', [SeguidorController::class, 'store'])->name('users.seguir.store')->middleware('auth');
Route::delete('/{user:username}/dejar-de-seguir', [SeguidorController::class, 'destroy'])->name('users.seguir.destroy')->middleware('auth');
/** FIN DE SEGUIDORES DE USUARIOS */
