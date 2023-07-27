@extends('layouts.app')
@section('titulo', 'Ver post '.$post->titulo )

@section('contenido')
    <div class="container mx-auto md:flex " >
        <div class="md:w-1/2">
            <img src="{{ asset('uploads').'/'.$post->imagen }}" alt="imagen del post: {{ $post->titulo }}"  />

            <div class="pt-3 flex items-center gap-4">
                {{-- recordemos que el action es el name que le dimos al route en el archivo web.php --}}
                {{--  solo le puede dar like las personas logueadas --}}
                @auth
                    {{-- livewire es un componentes que se instala en laravel mediante componser, en el archivo readme.md tengo mas documentacion sobre el tema --}}
                    {{-- like-post es el nombre de la vista que va a renderizar --}}
                    <livewire:like-post :post="$post"  />
                @endauth
            </div>

            <div class="pt-3">
                <p class="font-bold"> {{ $post->user->username }} </p>
                {{-- https://carbon.nesbot.com/docs/ fuente de diffForHumans para el manekjo de fecha, hay mas metodos alli --}}
                <p class="text-sm text-gray-500"> {{ $post->created_at->diffForHumans() }} </p>
                <p class="mt-5"> {{ $post->descripcion }} </p>
            </div>


            @auth

                {{-- saber si la persona con sesion abierta es la misma que creo el post --}}
                @if ($post->user_id === auth()->user()->id )
                    <form method="POST" action="{{ route('posts.destroy', ['post' => $post->id]) }}" >
                        {{--  metodo spoofing => permite agregar a formularios peticiones del tipo put, delete --}}
                        @method('delete')
                        @csrf
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 p-2 transition-colors cursor-pointer uppercase font-bold mt-4  text-white rounded-lg text-sm"
                        >
                        Elminar
                    </button>
                    </form>
                @endif
            @endauth
        </div>

        <div class="md:w-1/2 p-5">
            <div class="shadow bg-white p-5 mb-5" >
                @auth
                    <p class="text-xl font-bold text-center mb-4">Agrega un nuevo comentario</p>
                    @if (session('mensaje'))
                        <div class=" bg-green-500 p-2 rounded-lg mb-6 text-white text-center uppercase font-bold" >{{ session('mensaje') }}</div>
                    @endif

                    {{-- el action es algo como  http://localhost/josueperezf/posts/2 --}}
                    <form  method="POST" action="{{ route('comentarios.store', ['user'=>$user->username, 'post'=> $post->id]) }}"  >
                        @csrf
                        <div class="mb-5">
                            <label for="comentario" class="mb-2 block uppercase text-gray-500 font-bold" >Añade un comentario</label>
                            <textarea
                                id="comentario" name="comentario" placeholder="Agrega un comentario"
                                class="border p-3 w-full rounded-lg @error('comentario') border-red-500 @enderror "
                            >{{ old('comentario') }}</textarea>
                            @error('comentario')
                                <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">
                                    {{-- laravel automaticamente, si tiene un error para este input, lo asigna a una variable con el nombre $message --}}
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <button
                            type="submit"
                            class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg"
                        >
                            Guardar Comentario
                        </button>
                    </form>
                @endauth

                {{-- Monstamos comentarios --}}
                <div class="bg-white shadow mb-5 max-h-96 overflow-y-scroll mt-10" >
                    @if ($post->comentarios->count() == 0)
                        <p class="p-10 text-center" >No hay comentarios aun</p>
                    @endif

                    {{-- aqui solo entra si hay comentarios --}}
                    @foreach ($post->comentarios as $comentario)
                    <div class="p-5 border-gray-300 border-b">
                        {{-- puede ser  ['user'=>$comentario->user] o  ['user'=>$comentario->user->username] --}}
                        <a class="font-bold cursor-pointer" href="{{ route('posts.index', ['user'=>$comentario->user]) }}"  >{{ $comentario->user->username }}</a>
                        <p>{{ $comentario->comentario }}</p>
                        <p class="text-sm text-gray-500" >{{ $comentario->created_at->diffForHumans() }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
