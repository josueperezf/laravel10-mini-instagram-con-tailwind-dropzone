@extends('./layouts/app')
@section('titulo', 'Editar perfil de '.auth()->user()->username )

@section('contenido')
    <div class="md:flex md:justify-center" >
        <div class="md:w-1/2 bg-white shadow p-6" >

            {{--  para mi no seria perfil.store si no perfil.update ya qye estamos editando, pero bueno, el profe decidio manejarlo como un store --}}
            <form class="mt-10 md:mt-0" method="POST" action="{{ route('perfil.store') }}" enctype="multipart/form-data" >
                @csrf
                <div class="mb-5">
                    <label for="username" class="mb-2 block uppercase text-gray-500 font-bold" >Username</label>
                    <input
                        id="username" name="username" type="text" placeholder="Tu nombre de usuario"
                        class="border p-3 w-full rounded-lg @error('username') border-red-500 @enderror "
                        value="{{ auth()->user()->username }}"
                    />
                    @error('username')
                        <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">
                            {{-- laravel automaticamente, si tiene un error para este input, lo asigna a una variable con el nombre $message --}}
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{--  no validamos la imagenes ya no es obligatorio --}}
                <div class="mb-5">
                    <label for="imagen" class="mb-2 block uppercase text-gray-500 font-bold" >Imagen Perfil</label>
                    <input
                        id="imagen" name="imagen" type="file"
                        class="border p-3 w-full rounded-lg  "
                        value="{{ auth()->user()->imagen }}"
                        accept=".jpg, .jpeg, .png"
                    />
                </div>

                <button
                    type="submit"
                    class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg"
                >
                    Guardar cambios
                </button>
            </form>
        </div>
    </div>
@endsection
