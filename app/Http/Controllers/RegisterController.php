<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    public function index() {
        return view('auth.crear-cuenta');
    }

    public function store(Request $request) {

        // modificar el request, debido a que el nombre de usuario se utilizara en la url, ejemplo el username viene 'magdalena rey' y hay que convertirlo en 'magdalena-rey' http://localhost/magdalena-rey, para colocar ese guion es la siguiente linea de codigo, se hace antes, para que ejecute las validaciones con los cambios que le hicimos al request
        $request->request->add(['username' => Str::of($request->get('username'))->lower()->trim()->slug('-')]);

        // Validaciones
        $this->validate($request, [
            'name' => 'required|min:3|max:30',
            'username' => 'required|unique:users|min:3|max:30',
            'email' => 'required|email|unique:users|max:60',
            'password' => 'required|min:6|confirmed', //confirmed es algo ya de laravel que valida que exista password y password_confirmation, y que tengan el mismo valor. el input obligatoriamente se debe llamar password_confirmation
        ]);

        // IMPORTANTE, LUEGO DE LARAVEL 8, EL CAMPO PASSWORD LO ENCRIPTA AUTOMATICAMENTE  GRACIAS AL $casts que tiene en el modelo 'User'
        $user = User::create([
            'name' => Str::of($request->get('name'))->lower()->trim(),
            'username' =>  $request->get('username'),
            'email' => Str::of($request->get('email'))->lower()->trim(),
            'password' => $request->get('password'), // Hash::make($request->get('password'))
            ]);

        // Autenticamos el usuario que se acaba de registrar
        // forma 1
        // Auth::login($user);
        // forma 2
        auth()->attempt([
            'email' => Str::of($request->get('email'))->lower()->trim(),
            'password' => $request->get('password'),
        ]);

        return redirect()->route('posts.index', auth()->user()->username );
        // return redirect()->route('posts.index',$request->get('username') );
    }
}
