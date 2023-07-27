<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    // este metodo solo recibe la foto, le pone 100px de ancho y alto,  y luego la guarda en la ruta public/uploads/gfkjasghfkjais.jpg.
    // solo subimos la foto, el nombre de la foto lo guarda en la base de datos es el controlador PostController
    public function store(Request $request) {
        $imagen = $request->file('file');
        $nombreImagen = Str::uuid().".".$imagen->extension();
        $imagenServidor = Image::make($imagen);
        $imagenServidor->fit(1000, 1000);
        // creamos el string del path donde donde queremos guardar esa foto. public/uploads/gfkjasghfkjais.jpg.
        // la carpeta uploads la creamos manualmente
        $imagenPath = public_path('uploads').'/'.$nombreImagen;
        $imagenServidor->save($imagenPath);

        return response()->json(['imagen' => $nombreImagen ]);
    }
}
