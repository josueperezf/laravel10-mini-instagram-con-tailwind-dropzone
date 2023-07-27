<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comentario;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{

    // si ocurre un not found o algo asi, no entrara al metodo, ya que eso lo valida laravel internamente
    public function store(Request $request, User $user, Post $post) {
        //$user lo necesita solo para hacer el redirect por asi decirlo
        // validar
        $this->validate($request, [
            'comentario' => 'required|min:2|max:255',
        ]);

        // GUARDAR
        // con esto evito colocar el user_id, lo pone automanticamente. recordemos que en el $request laravel pone automaticamente la persona que esta logueada
        // CON user() obtenego el usuario logueado,
        // con user()->comentarios() digo que quiero trabajar con los comentarios del usuario logueado, especificamente crear

        //* IMPORTANTE
        // $request->user()-> y el $user no son lo mismo, ya que juan puede estar comentando los post de maria
        $request->user()->comentarios()->create([
            'comentario' => Str::of($request->comentario)->trim(),
            'post_id' => $post->id,
        ]);


        // yo lo habia hecho asi, pero el prof lo realizo diferente. funcionan igual
        // return view('posts.show', [ 'post' => $post, 'user' => $user ])->with('mensaje', 'Comentario almacenado exitosamente');
        return back()->with('mensaje', 'Comentario almacenado exitosamente'); // la variable with se va en las variables de session en la vista posts.show se puede ver como se utiliza
    }
}
