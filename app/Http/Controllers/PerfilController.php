<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

// PARA MI PARA EDITAR UN USUARIO DEBERIA DE TENER EL METODO EDIT, Y PARA GUARDAR LOS CAMBIOS SERIA BIEN EL UPDATE, PERO NI MODO, LO HAGO COMO EL PROFESOR
class PerfilController extends Controller
{
    // no se porque pero esta utilizando esta como si fuera para photos.edit, pero él es quien sabe
    public function index() {
        return view('perfil.index' );
    }


    // AQUI ESTAMOS EDITANDO EL USUARIO QUE ESTA LOGUEADO
    public function store(Request $request) {
        // modificar el request, debido a que el nombre de usuario se utilizara en la url, ejemplo http://localhost/magdalena-rey, para colocar ese guion es la siguiente linea de codigo, se hace antes, para que ejecute las validaciones con los cambios que le hicimos al request
        $request->request->add(['username' => Str::of($request->get('username'))->lower()->trim()->slug('-')]);

        $this->validate($request, [
            'username' => [
                'required',
                'unique:users,username,'.auth()->user()->id, // campo unico es username
                'min:3',
                'max:30',
                'not_in:twitter,editar-perfil,xxx',
            ]
        ]);

        // verificamos si hay una imagen o no en el request para editar la foto del usuario
        if ($request->imagen) { // guardamos la foto en la carpeta public/perfiles
            $imagen = $request->file('imagen');
            $nombreImagen = Str::uuid().".".$imagen->extension();
            $imagenServidor = Image::make($imagen);
            $imagenServidor->fit(1000, 1000);
            // creamos el string del path donde donde queremos guardar esa foto. public/uploads/gfkjasghfkjais.jpg.
            // la carpeta uploads la creamos manualmente
            $imagenPath = public_path('perfiles').'/'.$nombreImagen;
            $imagenServidor->save($imagenPath);
        }

        // guardar cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? ''; // IMPORTANTE esto es como cuando haces en js hacemos => nombreImagen || ''. que tome una opcion o la otra, pero le da mas importancia de izquierda a derecha
        $usuario->save();

        return redirect()->route('posts.index', $usuario->username);
    }
}
