<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

// este policy se creo para que tenga la logica de que solo puede eliminar un post la persona que esta autenticada y sea quien creó ese post
// para utilizar el policy, ejemplo en el controlador, vamos a colocar algo como $this->auth
class PostPolicy
{
    // en $user policy nos asina a la persona que tiene la sesion abierta
    public function delete(User $user, Post $post): bool {
        return $post->user_id === $user->id;
    }
}
