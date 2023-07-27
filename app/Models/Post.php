<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'user_id',
    ];




    // verificamos si un usuario en particular le ha dado like a un post o no
    public function checkLike(User $user): bool { // ESTE METODO RETORNA UN BOOLEAN TRUE SI ha dado like, False en caso contrario
        // verificamos si en los likes que contiene este post, hay alguno que pertenezca al usuario pasado como parametro
        return $this->likes->contains('user_id', $user->id);
    }


    public function user() {
        return $this->belongsTo(User::class);
    }

    // un post o publicacion puede tener muchos comentarios
    public function comentarios() {
        return $this->hasMany(Comentario::class);
    }

    // de un post pueden hacer muchos likes
    public function likes() {
        return $this->hasMany(Like::class);
    }
}
