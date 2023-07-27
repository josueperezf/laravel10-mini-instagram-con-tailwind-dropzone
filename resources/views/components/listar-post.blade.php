@if ($posts->count() > 0)
    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 " >
        @foreach ($posts as $post)
            <div>
                {{-- la siguiente ruta del enlace es algo como josueperezf/post/2 ejemplo --}}
                <a href="{{ route('posts.show', ['user'=>$post->user->username, 'post'=> $post->id]) }}" >
                    <img src="{{ asset('uploads/'.$post->imagen) }}" alt="Imagen del post {{ $post->titulo }}"  />
                </a>
            </div>
        @endforeach
    </div>
    <div>
        {{--
            el css para los botones de la paginacion, se coloco en el archivo tailwind.config.js,
            se coloco lo que sugiere la pagina https://laravel.com/docs/10.x/pagination
        --}}
        {{ $posts->links() }}
    </div>
@else
    <p class="text-gray-600 uppercase text-sm text-center font-bold" >
        No hay posts
    </p>
@endif
