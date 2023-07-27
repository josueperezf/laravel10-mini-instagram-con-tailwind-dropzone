@extends('./layouts/app')
@section('titulo', 'Página Principal')


@section('contenido')
    <div class="container">

        {{--
            se notó que hay codigo que se repite en varios archivos blade,
            para evitar ello se creo un componente mediante el comando: sail php artisan make:component ListarPost
            este comando crea dos archivo.
                1. uno en la carpeta view/ListarPost.php que es el archivo de recibir los parametros para que los pueda recibir la vista.
                2. el otro archivo que se crea la vista como tal, la crea en resources/view/listar-post.blade.php que es donde pegamos el html que vamos a reutilizar

            para poder utilizarla debemos colocar una etiqueta html con <x-Nombre-de-la-vista /> algo similar a react,
            para que esta vista pueda recibir variables, se debe colocar dos puntos y el nombre de la variable que se envia :posts="$posts".
            la variable que se envia llega primero al archivo php. para este ejemplo se llama 'ListarPost',
            ese archivo debe recibir los parametros que le enviamos en el html '$posts' en el constructor y colocarlo en una variable interna de esa clase, algo como $this->posts = $posts;
            luego de hacer lo anterior diriamos que ya esta listo, pero no, se debe ejecutar un comando en la terminal para limpiar algo parecido a la cache para que tome los cambios,
            esto se hace ejecutando en la terminal el comando: sail php artisan view:clear

            ya con esto seria suficiente para poder utilizar el componente en cuantos lugares queramos

        --}}

        <x-listar-post :posts="$posts"  />
    </div>
@endsection
