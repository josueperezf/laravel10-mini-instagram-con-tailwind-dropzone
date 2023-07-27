<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LikePost extends Component {

    public $post;
    public $isLiked; // boolean, para saber si tiene like o no el post o publicacion en nuestra version de instagram
    public $totalLikes;


    // este metodo es del cliclo de vida, algo como el onInit de angular, lo creamos solo para que esa variable llegue a la vista y saber si pintar el corazon de rojo porque ya dieron like o sin relleno porque no han dado like
    // este metodo se llama cada vez que se mande a llamar este componente likePost, esta funcion se ejecuta automaticamente, algo como si fuera un contructor
    public function mount(): void {
        $this->isLiked = $this->post->checkLike(auth()->user());
        $this->totalLikes = $this->post->likes->count();
    }

    // este metodo no retorna nada, pero sus variables llegan a la vista, entonces son esas variables las que hacen el cambios alli
    public function like() {
        //la siguiente linea es como si dijeramos $post->checkLike(auth()->user()), checkLike es un metodo del modelo de post para saber si un usuario ya dio like a un post o no
        if ($this->post->checkLike(auth()->user())) {
            // si entra aqui, es que el usuario ya le dio like y quiere quitar el like que dio
            $this->post->likes()->where('user_id', auth()->user()->id)->delete();
            $this->isLiked = false;
            $this->totalLikes--;
        } else {
            // si no ha dado like a el post, entonces guardamos ese like
            $this->post->likes()->create(['user_id' => auth()->user()->id]);
            $this->isLiked = true;
            $this->totalLikes++;
        }
    }


    public function render() {
        return view('livewire.like-post');
    }
}
