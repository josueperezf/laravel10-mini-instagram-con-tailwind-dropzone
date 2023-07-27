<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
/**
 * EXPLICANDO GRAFICAMENTE LA RELACION ENTRE USUARIOS Y SEGUIDORES
 *
 *
 * TABLAS
 *
-------             -----------------
|users|-----------> | seguidors      |
|id   |---          |user_id         |
|     |  |          |                |
|     |  |----->    |user_seguidor_id|
|_____|             |________________|
*/

//* IMPORTANTE
/*
NO UTILIZAMOS EL MODELO DE SEGUIDOR PARA EJECUTAR EL METODO DE ALMACENAR SEGUIDORES,
DEBIDO A QUE EN EL MODELO DE USUARIO SE CREARON TRES METODOS
1. VER LOS SEGUIDORES DE UN USUARIO EN PARTICULAR
2. VER QUIEN NOS SIGUE
3. SABER SI SEGUIMOS A ALGUIEN O NO, RETORNA BOOLEAN

DE ESTA FORMA PARA ALMACENAR NO UTILIZAMOS EL MODELO SEGUIDOR SI NO EL USER, YA QUE DE ESTA FORMA SABE SI ESTAMOS REGISTRANDO SEGUIDOR Y SEGUIDO
*/

class SeguidorController extends Controller
{
    // se coloca User porque en el router de web.php dijimos que recibiria el usuario, asi que el framework internamente va a la base de datos y me trae el usuario asociado a ese username y me lo asigna en el variable $user
    public function store(User $user) {
        // $user es el usuario a quien vamos a seguir
        // seguidores es un metodo que creamos en el modelo de User
        // se coloca attach y no create(), debido a que la relacion como de n a m
        // el usuario autenticado 'josueperezf' va a seguir al perfil que estemoos visitando http://localhost/magdalena
        // la siguiente linea comentada tambien funciona, la quite porque la otra me ayuda mas a entender que campo es al que se le asignara el id
        // $user->seguidores()->attach(auth()->user()->id);
        $user->seguidores()->attach(['user_seguidor_id' => auth()->user()->id]);
        return back();
    }

    public function destroy(User $user) {
        // al usuario $user lo deja de seguir el usuario logueado
        // la siguiente linea comentada tambien funciona, la quite porque la otra me ayuda mas a entender que campo es al que se le asignara el id
        // $user->seguidores()->detach(auth()->user()->id);
        $user->seguidores()->detach(['user_seguidor_id' => auth()->user()->id]);
        return back();
    }
}
