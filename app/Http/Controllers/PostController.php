<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use App\Policies\PostPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    //* este metodo se llama con route model binding, esto quiere decir que el parametro que recibe por url lo utiliza para buscar en la base de datos si existe un usuario por ese campo, si existe lo asigna a la variable $user
    public function index(User $user)
    {
        // $posts =  $user->posts()->get();
        // ->latest() es para ordenar el resultado del mas resiente al mas viejo
        $posts =  $user->posts()->latest()->paginate(3);
        return view('dashboard', compact(['user', 'posts']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('posts.create');
    }

    // la foto como tal la almacena el controlador de imagen, aqui solo llegan un string con la ruta donde esta la imagen
    public function store(Request $request)
    {

        $this->validate($request, [
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'imagen' => 'required|max:255',
        ]);
        try {
            // foma 1 de guardar
            // Post::create([
                //     'titulo'        => Str::of($request->get('titulo'))->lower()->trim(),
                //     'descripcion'   => Str::of($request->get('descripcion'))->trim(),
                //     'imagen'        => $request->get('imagen'),
                //     'user_id'       => auth()->user()->id
                // ]);

                //IMPORTANTE: EN el request tambien va el usuario que esta logueado

            // foma 2 de guardar un post
            $request->user()->posts()->create([
                'titulo'        => Str::of($request->get('titulo'))->lower()->trim(),
                'descripcion'   => Str::of($request->get('descripcion'))->trim(),
                'imagen'        => $request->get('imagen'),
                // 'user_id'       => auth()->user()->id // lo comente porque ya lo toma automaticamente  por la forma en la que llegamos a la remacion
            ]);


        } catch (\Throwable $th) {
            Log::debug($th);
        }

        return redirect()->route('posts.index', auth()->user()->username );
    }

    //* como en archivo. web.php se le coloco a la ruta {post}, entonces internamente hace una consulta a la db y retorna al este metodo show ya el post que obtuvo de la base de datos
    //* en pocas palabras, como el route recibe dos parametros, y ve que se llaman como nombres de modelos, internamente va a la db y trae la data correspondiente a ellos, y la asigna a los valores correspondientes
    public function show(User $user, Post $post) {
        return view('posts.show', [ 'post' => $post, 'user' => $user ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post) {
        // verificamos que quien trata de eliminar el post es el autor del mismo, lo comparamos con la sesion abierta
        // con la siguiente linea laravel va a llamar a el post que este asociado al modelo de post,
        // y especificamente ejecutara metodo delete que este alli, este retorna un true si tiene permiso de eliminar y salso en caso contrario 'PostPolicy'
        $this->authorize('delete', $post);
        $post->delete();

        // ahora se debe eliminar la imagen asociada al post
        $imagen_path = public_path('uploads/'.$post->imagen);
        if (File::exists($imagen_path)) {
            // la siguiente linea comentada la puse yo, pero el prof utilizo otra
            // File::delete($imagen_path);
            unlink($imagen_path);
        }

        // por lo que veo solo en los metodos destroy del controlador es que hace el redirect
        return redirect()->route('posts.index', auth()->user()->username);
    }
}
