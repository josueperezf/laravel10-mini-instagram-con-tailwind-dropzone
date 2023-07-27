@extends('./layouts/app')
@section('titulo', 'Perfil: '.$user->username )

@section('contenido')
    <div class="flex justify-center">
        <div class="w-full md:w-8/12 lg:w-6/12 flex flex-col items-center md:flex-row ">
            <div class="w-8/12 lg:w-6/12 px-5" >
                @if ($user->imagen)
                    <img src="{{ asset('perfiles').'/'.$user->imagen }}" alt="imagen usuario"/>
                @else
                    <img src="{{ asset('img/usuario.svg') }}" alt="imagen usuario" />
                @endif
            </div>
            <div class="md:w-8/12 lg:w-6/12 px-5 flex flex-col items-center md:justify-center md:items-start py-10 md:py10"  >
                <p class="text-gray-700 text-2xl " >
                    {{ $user->username }}
                    @auth
                        @if ($user->id === auth()->user()->id)
                            <span class="inline-block">
                                <a
                                    href="{{ route('perfil.index') }}"
                                    class="text-gray-500 hover:text-gray-600 cursor-pointer"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                            </span>
                        @endif
                    @endauth
                </p>

                <p class="text-gray-800 text-sm mb-3 font-bold mt-5">
                    {{-- cuandos siguen a este usuario --}}
                    {{ $user->seguidores->count() }} <span class="font-normal" >
                        {{-- LA SIGUIENTE LINEA ES UN HELPER DE LARAVEL, QUE AYUDA A MANEJAR LAS CANTIDADES, EJEMPLO SI ES UNO QUE DIGA SEGUIDOR, SI ES VARIOS QUE DIGA SEGUIDORES --}}
                        @choice('Seguidor|Seguidores', $user->seguidores->count())
                    </span>
                </p>

                <p class="text-gray-800 text-sm mb-3 font-bold">
                    {{-- cuandos usuario sigue este usuario --}}
                    {{ $user->siguiendo->count() }} <span class="font-normal" >Siguiendo</span>
                </p>

                <p class="text-gray-800 text-sm mb-3 font-bold">
                    {{-- el siguiente bloque lo programe yo porque no sabia, lo deje para recordarme ya que sifunciona pero no es la mejor manera --}}
                    {{-- {{ count($posts) }} --}}
                    {{ $user->posts->count() }}
                    <span class="font-normal" >Posts</span>
                </p>

                {{-- inicio del bloque para seguir y dejar de seguir --}}
                @auth
                    {{-- es para no permita que uno se siga a sí mismo --}}

                    {{-- verificamos si el usuario es seguido por el usuario logueado, si sigue, entonces mostramos el formulario de dejar de seguir --}}
                    @if ($user->id !== auth()->user()->id)
                        {{-- auth()->user() es la instancia de el usuario, auth()->user es para obtener algun valor --}}
                        @if ($user->seguidoPor(auth()->user()))
                            <form method="POST" action="{{ route('users.seguir.destroy', ['user'=>$user]) }}" >
                                @csrf
                                {{-- method spoofing  --}}
                                @method('delete')
                                <button type="submit" class="bg-red-600 text-white uppercase rounded-lg px-3 py-1 cursor-pointer text-xs font-bold"  >
                                    Dejar de Seguir
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('users.seguir.store', ['user'=>$user]) }}" >
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white uppercase rounded-lg px-3 py-1 cursor-pointer text-xs font-bold"  >
                                    Seguir
                                </button>
                            </form>
                        @endif
                    @endif

                @endauth
                {{-- fin del bloque para seguir y dejar de seguir --}}

            </div>
        </div>
    </div>

    <section class="container mx-auto mt-10" >
        <h2 class="text-4xl text-center font-black my-10" >Publicaciones</h2>
        {{-- yo hace lo pensaba hacer count($posts), pero laravel ya lo tiene incorporado con las colecciones  $posts->count() --}}
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
            esto se hace ejecutando en la terminal el comando: sail php artisan view:clear , este comando es solo para que la vista toma las variables que se le envian

            ya con esto seria suficiente para poder utilizar el componente en cuantos lugares queramos

        --}}

        <x-listar-post :posts="$posts"  />
    </section>
@endsection
