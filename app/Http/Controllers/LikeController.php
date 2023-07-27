<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    // para dar like en un post, se hace en la vista posts.show
    public function store(Post $post, Request $request) {
        // verificamos si el usuario ya le ha dado like a ese post

        // yo hice esta respuesta, que retorna la cantidad, pero el profesor creo un metodo en el modelo que retorna true o falso, mejor que el que yo hice, es mas limpio

        // mi codigo poco limpio
        /**
        $likesDeUsuarioPorEsPost = Like::where('post_id', $post->id)
                    ->where('user_id', auth()->user()->id)
                    ->count();
        */
        // el hizo esto $post->checkLike(auth()->user()), se ahorra incluso crear la variable $likesDeUsuarioPorEsPost
        // por lo que veo este tipo de consultas o metodos en el modelo '$post->checkLike(auth()->user())' solo los crea para consultas, si hay qye hacer un delete o algo asi, entonces no lo hace

        // si lo hay likes del usuario logueado, le agregamos like
        if (!$post->checkLike(auth()->user())) {
            // el  'post_id' => $post->id, no se coloca ya que por la relacion que tienen el modelo, laravel saber internamente como debe funcionar
            $post->likes()->create([
                'user_id' => auth()->user()->id
            ]);
        }


        // iria a la pantalla http://localhost/josueperezf/posts/3 el 3 es solo ejemplo, va a la pantalla anterior a ser llamado este metodo
        // return back()->with('mensaje', 'Like almacenado exitosamente'); // la variable with se va en las variables de session en la vista posts.show se puede ver como se utiliza
        // el profesor coloco el mismo back que yo pero no le coloco mensaje, ya que con que la vista muestre el corazon cambie de color, ya el usuario sabe que si logro lo que queria
        return back();
    }

    public function destroy(Post $post) {
        // verifico si realmente el usuario actual ha dado like a este post
        if ($post->checkLike(auth()->user())) { // este if no lo coloco el profesor, pero yo si lo considero necesario para confirmar
            $post->likes()->where('user_id', auth()->user()->id)->delete();
        }
        return back();
    }
}
