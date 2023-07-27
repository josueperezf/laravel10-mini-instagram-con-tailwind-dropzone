@extends('./layouts/app')
@section('titulo', 'Crear una nueva publicacion' )


{{--
    @push es como el @section, pero es especializado para colocar css y js,
    esto lo definimos en el archivo layouts/app,
    para este ejemplo cargamos el css para dropzone
--}}
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endpush

@section('contenido')
    <div class="md:flex md:items-center">
        <div class="md:w-1/2 px-10">
            {{-- la clase css dropzone es de dropzone, no la creamos en el proeycto --}}
            {{-- con el id dropzone es que manejamos el dropzone desde el archivo app.js --}}
            <form action="{{ route('imagenes.store') }}" method="POST" enctype="multipart/form-data" id="form-dropzone" class="dropzone border-dashed border-2 w-full h-96 rounded flex flex-col justify-center items-center">
                @csrf
            </form>
        </div>

        <div class="md:w-1/2 bg-white p-10 rounded-lg shadow-xl mt-10 md:mt-0">
            <form method="POST" action="{{ route('posts.store') }}" >
                @csrf
                <div class="mb-5">
                    <label for="titulo" class="mb-2 block uppercase text-gray-500 font-bold" >Titulo</label>
                    <input
                        id="titulo" name="titulo" type="text" placeholder="Titulo del post"
                        class="border p-3 w-full rounded-lg @error('titulo') border-red-500 @enderror "
                        value="{{ old('titulo') }}"
                    />
                    @error('titulo')
                        <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">
                            {{-- laravel automaticamente, si tiene un error para este input, lo asigna a una variable con el nombre $message --}}
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="descripcion" class="mb-2 block uppercase text-gray-500 font-bold" >Descripcion</label>
                    <textarea
                        id="descripcion" name="descripcion" placeholder="Descripcion"
                        class="border p-3 w-full rounded-lg @error('descripcion') border-red-500 @enderror "
                    >{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">
                            {{-- laravel automaticamente, si tiene un error para este input, lo asigna a una variable con el nombre $message --}}
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-5">
                    <input name="imagen" id="imagen" type="hidden" value="{{ old('imagen') }}"  />
                    {{-- esto es por si se trata de guardar y no envian la imagen, recordemos que se toma la iamgen en el form que tiene dropzone, y despues mediante js se guarda en este input hidden --}}
                    @error('imagen')
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
                    Crear Publicación
                </button>
            </form>
        </div>
    </div>
@endsection

{{-- cargo el js que se encarga de dropzone para cargar imagenes --}}
@push('scripts')
    @vite('resources/js/dropzone-create-post.js')
@endpush
