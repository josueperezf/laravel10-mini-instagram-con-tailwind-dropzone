{{-- documentacion oficinal https://laravel-livewire.com/docs/2.x/loading-states --}}

{{--
    MUY IMPORTANTE:

    este codigo se renderiza o se actualiza, cada vez que haga las llamadas al backend 'LikePost.php', y en este una de sus propiedades cambie,
    por ejemplo: en ese archivo tenemos las variables
    public $isLiked
    public $totalLikes

    cada vez que usa de esas cambie su valor, toda esta vista html se dibujara de nuevo para refrescar su contenido, esto es muy importante
--}}

{{-- este tipo de vistas deben retornar obligatoriamente un solo elemento, ej un div, un p, table, lo que sea, pero que sea una etiqueta html que envuelva todo  --}}
<div>
    <div class="flex gap-2">
        {{--
            el siguiente boton utiliza livewire, de este modo ya utiliza ajax automaticamente
            con el wire decimos que vamos a utilizar un evento, el cual será click,
            y decimos que al hacer click llame al metodo like de la clase LikePost.php,
            la url al que hace la llamada la maneja laravel automaticamente,
        --}}

        {{--
            la variable $isLiked no me la envia la vista donde estan llamando este componente,
            si no que el valor de $isLiked se le asigna en el archivo LikePost.php en el metodo mount() que es algo parecido al onInit() de angular,
            es facil de entender
        --}}


        {{--  este div se coloca solo cuando esta cargando <https://laravel-livewire.com/docs/2.x/loading-states> --}}
        <div wire:loading class="animate-spin h-5 w-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
        </div>

        {{--  si colocando esto 'wire:loading.remove' hacemos desaparecer el boton 'el corazon' hasta que termine de responder el backend --}}
        {{--  si colocando esto 'wire:loading.attr="disabled"' hacemos se deshabilite el boton 'el corazon' hasta que termine de responder el backend --}}
        <button wire:click="like"  wire:loading.remove>
            <svg xmlns="http://www.w3.org/2000/svg" fill="{{ ($isLiked ) ? 'red' : 'white' }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
        </button>

        {{-- es para mostrar el numero de like que tiene ese post, si los tiene --}}
        <p class="font-bold" >  {{ $totalLikes }} <span class="font-normal"> likes </span></p>
    </div>
</div>
