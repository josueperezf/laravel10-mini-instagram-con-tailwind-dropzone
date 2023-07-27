<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(): View {
        // obtenemos a quienes seguimos
        // pluck hace que de la data que tengamos nos retorne solo ciertos campos, para este ejemplo es solo el id, creo que se utiliza solbre todo para armar <select ></select> y cosas asis
        $ids = auth()->user()->siguiendo->pluck('id')->toArray();
        // ->latest() es para ordenar el resultado del mas resiente al mas viejo
        $posts = Post::whereIn('user_id', $ids)->latest()->paginate(20);
        return view('home', compact('posts'));
    }

}
