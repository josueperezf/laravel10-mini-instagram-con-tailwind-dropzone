## devStagram

es un proyecto basico que realiza un leve ejemplo de instagram.
este ejemplo se creo con docker, por ende para correrlo debemos tener docker desktop corriendo y teniendo configurado wsl para este ejemplo con ubuntu.

si no recordamos como fue que creamos el docker para este proyecto aqui va una documentacion personal


## pasos para levantar el proyecto

1. tener el ambiente docker y demas, si no lo tenemos, aqui esta una [guia](#pasos-para-configurar-docker-para-proyecto-laravel-9)
2. despues de tener todo bien del paso 1, debemos loguearnos en una terminal ```wsl --distribution Ubuntu --user josue```
3. estando alli debemo lenvantar el contenedor, luego de hacer el paso 1, en esa terminal debemos movernos a la carpeta del proyecto, y ejecutar ```./vendor/bin/sail up```
4. debemos abrir otra terminal, ejecutar el paso 2, y alli correr el comando  ```./vendor/bin/sail npm run dev```, si tenemos el alias creado, podemos correr ```sail npm run dev``` que es una version mas corta del mismo comando
5. si hicimos algun cambio, ejemplo cambiamos una clase css y no vemos los cambios, podemos pararnos en la terminal donde tenemos corriendo el npm, y presionar la tecla ```r```, esto refresca
6. si queremo ejecutar otros comandos, ejemplo correr migraciones, crear controladores con artisan o lo que sea, tendriamos que abrir otra terminal, hacer el paso dos y alli si ejecutar el comando, claro despues de movernos hasta la carpeta del proyecto, si tenemos el alias colocamos sail, si no pues nos toca coloca ./vendor/bin/sail antes de cada comando que vayamos a ejecutar

## comandos basicos
para correr cualquiera de estos proyecto se debe tener docker corriendo, luego en estar logueados en una terminar con: ```wsl --distribution Ubuntu --user josue``` y alli podemos ejecutar lo que queramos, si no tenemos docker o wsl, entonces podemos instalarlo segun esta [guia](#pasos-para-configurar-docker-para-proyecto-laravel-9)

1. para crear un modelo, migracion y controlador al mismo tiempo: ```sail php artisan make:model --migration --controller Comentario``` crea comentarioController
2. crear una migracion para hacer alter table ```sail php artisan make:migration add_imagen_to_users_table```  debe finalizar en ```users_table``` para que laravel nos cargue automaticamente la referencia a la tabla, en este caso ```users```. IMPORTANTE, si en el metodo 'up' de ese archivo hacemos lo de crear la columna imangen, en el metodo 'down' de ese mismo archivo debemos hacer el de quitar esa misma columna imagen por si decidimos  hacer rollback de la migracion
3. para correr las migraciones o solo las que no hayamos corrido aún ```sail php artisan migrate```
4. para listar todos las rutas con los controladores encargados ```sail php artisan route:list ```
5. para deshacer la ultima migracion que se corrio ```sail php artisan migrate:rollback --step=1```
6. para deshacer las ultima 2 migraciones que se corrio ```sail php artisan migrate:rollback --step=2```
7. para deshacer todas las migraciones ```sail php artisan migrate:rollback```
8. policy: sirve para poder limitaciones de acceso, por ejemplo que un post o publicacion, solo pueda ser eliminado o modificado por el usuario que lo creo.
   * para crear un policy: ```sail php artisan make:policy PostPolicy --model=Post``` el model es para indicar a que modelo o tabla de base de datos estara relacionado. este comando genera un ejemplo de un poslicy, y lista algunos metodo que se podrian controlar, como create, update, etc, nosotros borramos los que no necesitamos y colocamos nuestro codigo en el metodo que necesitemos, para este ejemplo en el delete
9. para crear componentes blade reutiliza codigo html, muy util ```sail php artisan make:component ListarPost```,  [Para mas detalles de como crear un componente blade](#reutilizar-html-en-vistas-blade-con-paso-de-variables-mediante-components)


## ejemplo de validaciones en laravel
1. el username es requerido, debe ser unico, minimo 3 caracteres, maximo 30, y en su contenido no su valor no puede ser ejemplo: twitter,editar-perfil,xxx y muchos mas, solo no debe llevar espacios en blanco entre las coma y la palabra 'twitter, editar-perfil' LO ANTERIOR NO DEBE SER, DEBE SER PEGADO

```
$this->validate($request, [
    'username' => [
        'required',
        'unique:users',
        'min:3',
        'max:30',
        'not_in:twitter,editar-perfil,xxx',
    ]
]);
```
2.  ejemplo que el username sea unico pero al momento de editar un usuario

```
$this->validate($request, [
    'username' => [
        'unique:users,username,'.auth()->user()->id,  // campo unico es username, users hace referencia al nombre de la tabla
    ]
]);
```

3. validar ejemplo el genero de un usuario, aunque en este proyecto no se maneja, es solo un ejemplo, de que solo puede ser El strin MASCULINO O FEMENINO
```
$this->validate($request, [
    'username' => [
        'required',
        'unique:users',
        'min:3',
        'max:30',
        'in:MASCULINO,FEMENINO',
    ]
]);
```


## reutilizar html en vistas blade con paso de variables, mediante components

se notó que hay codigo que se repite en varios archivos blade,
para evitar ello se creo un componente mediante el comando: ```sail php artisan make:component ListarPost```
este comando crea dos archivo.
    1. uno en la carpeta view/ListarPost.php que es el archivo de recibir los parametros para que los pueda recibir la vista.
    2. el otro archivo que se crea la vista como tal, la crea en resources/view/listar-post.blade.php que es donde pegamos el html que vamos a reutilizar

para poder utilizarla debemos colocar una etiqueta html con ```<x-Nombre-de-la-vista />``` algo similar a react,
para que esta vista pueda recibir variables, se debe colocar dos puntos y el nombre de la variable que se envia ```:posts="$posts"```.
la variable que se envia llega primero al archivo php. para este ejemplo se llama 'ListarPost',
ese archivo debe recibir los parametros que le enviamos en el html ```$posts``` en el constructor y colocarlo en una variable interna de esa clase, algo como ```$this->posts = $posts;```
luego de hacer lo anterior diriamos que ya esta listo, pero no, se debe ejecutar un comando en la terminal para limpiar algo parecido a la cache para que tome los cambios,
esto se hace ejecutando en la terminal el comando: ```sail php artisan view:clear```

ya con esto seria suficiente para poder utilizar el componente en cuantos lugares queramos




## configuracion de docker si no la tenemos

sail: laravel utiliza docker en las versiones mas recientes. sail es una herramienta que trae laravel hace sencillo comunicarnos con docker.
sail es un cli para comunicarnos, interactuar con los archivos docker para arrancar tus servicios, llamar artisan, o instalar dependencias de NPM

### PASOS PARA CONFIGURAR DOCKER PARA PROYECTO LARAVEL 9

1. debemos tener Docker Desktop
2. debemos tener WSL2 instalado
3. debemos crear una distribucion, es decir un sistema operativo si no lo tenemos, para ello:
	a. levantar un power shell como administrador
	b. ejecutar el comando ```wsl.exe -l -v```
	c. ejecutamos ```wsl --list --online```
	d. ejecutamos ```wsl --install -d Ubuntu``` al terminar no mostrara una terminal y nos pedira un nombre de usuario para ubunto, para este ejemplo fue ``` user josue y password '2763867v```
	e. cerramos la terminal
4. abrimos un power shell con nuestro sistema operativo linux que creamos, para ello ejecutamos en el power shell el comando ```wsl --distribution Ubuntu --user josue``` recordemos que es dependiendo a nuestra distribucion y demas. el siguiente no lo ejecutes solo es ejemplo ```wsl --distribution <Distribution Name> --user <User Name>``` cambiar el ```<Distribution Name>``` por el nombre de nuestra distribucion de linux. se entiende
5. colocamos ```wsl --set-default Ubuntu 2```     => el dos es para indicarle qye trabaje con la version 2
6. ejecutamos ```wsl.exe --set-default-version 2```
7. debemos abrir docker desktop, ir a la tuerca 'configuracion', alli ir a 'resources', en ese lugar buscamos 'WSL integration', entonces veremos algo donde diga ubuntu, lo habilitamos, aplicamos y reiniciamos



###  PASOS PARA CREAR UN PROYECTO LARAVEL CON LA CONFIGURACION ANTERIOR DE DOCKER

1. ABRIR DOCKER
2. Abrir una power shell, y alli colocar ```wsl --distribution Ubuntu --user josue```
3. nos movemos a la carpeta donde queramos crear el proyecto, ejemplo 'C:/Users/josue/OneDrive/Documentos/' 
4. ejecutamos el comando ```curl -s https://laravel.build/example-app | bash```    => tenemos que esperar. al final nos puede pedir la clave que es 2763867v para el usuario josue de ubuntu que tenemos en docker desktop


### PASOS PARA LEVANTAR O CREAR EL CONTENEDOR PARA EL PROYECTO LARAVEL QUE ACABAMOS DE CREAR EN DOCKER

1. Debemos tener docker desktop corriendo
2. verificamos si tenemos el contenedor de nuestro proyecto laravel corriendo, para ello
	a. debemos abrir docker desktop y el containers ver si aparece el nombre del proyecto  'laravel-instagr' era instagram pero no lo escribi bien cuando lo cree
	b. si aparece le presionamos donde salga el play o correr o run o algo asi y listo, no seguimos haciendo mas pasos, de lo contrario, si no aparece entonces hacemos al siguiente paso
3. SOLO LA PRIMERA VEZ PARA CREAR LAS IMAGENES, Debemos abrir un power shell y loguearnos ```wsl --distribution Ubuntu --user josue```
	a. debemos desde la consola movernos a la carpeta donde tenemos el proyecto, y alli ejecutar  './vendor/bin/sail up'
	b. para abrir el proyecto colocamos en el navegador http://127.0.0.1/


### PASOS PARA REMOVER EL CONTENEDOR EL PROYECTO LARAVEL CON DOCKER QUE TENEMOS CORRIENDO

FORMA 1
1. Debemos abrir la power shell, loguearnos ```wsl --distribution Ubuntu --user josue```, movernos a la carpeta donde esta el proyecto ejemplo 'C:\Users\josue\OneDrive\Documentos\proyectos-laravel-10'
2. estando en esa carpeta ejecutamos ```./vendor/bin/sail down```

FORMA 2 segun josue
Abrimos docker desktop, vamos a los containerS y alli le presionamos la papelera y ya. aunque desde mi punto de vista no tiene sentido borrar el contenedor cada vez que dejamos de trabajar en el proyecto por ese dia,
considero que con detenerlo es mas que suficiente



### PASOS PARA CREAR ALIAS DE SAIL

para este proyecto se creo un alias para no tener que estar escribiendo ```./vendor/bin/sail up``` si no solo sail up, claro debemos estar en la carpeta del proyecto primero y estar en la power shell y loguearnos ```wsl --distribution Ubuntu --user josue```,
	luego de ello ya en vez de colocar php artisan migrate , colocamos ``` sail php artisan migrate ```
	o en vez de npm install se coloca ``` sail npm install ```
	si no tuvieramos el alias  ```./vendor/bin/sail npm install``` , ``` ./vendor/bin/sail php artisan migrate```






### COMO EJECUTAR COMANDOS DE ARTISAN O NPM EN NUESTRO PROECTO EN DOCKER
al crear nuestro proyecto ya automaticamente nos instala mysql, npm, php etc

Para ello laravel nos facilita la herramienta llamada sail, para utilizarla tenemos dos opciones 
1. **sin alias **
abrir power shell, loguearnos en nuesto ubuntu de docker ```wsl --distribution Ubuntu --user josue ```, luego nos movemos a la carpeta del proyecto desde la terminal, y ejecutamos ejemplo
	a. para levantar el container 		=> ``` ./vendor/bin/sail up ```
	b. para eliminar el container docker 	=> ``` ./vendor/bin/sail down ```
	c. para instalar todo lo de npm 	=> ```./vendor/bin/sail npm install```
	d. para correr la migracion de laravel a la base de datos => ```./vendor/bin/sail php artisan migrate```
	e. para ver que version de php tiene nuestro proyecto, debemos tener nuestro proyecto corriendo ``` ./vendor/bin/sail up ```, entonces desde otra terminal, power shell, loguearnos ```wsl --distribution Ubuntu --user josue```, podemos colocar el comando:  ``` ./vendor/bin/sail up ```
	f. para saber que version de npm tenemos, hacemos lo mismo del paso 5, pero el comando seria ```./vendor/bin/sail npm -v```

1. **con alias **
a. debemos tener el alias, si no lo tenemos creado debemos:
	1. abrir power shell, loguearnos ```wsl --distribution Ubuntu --user josue```, estando alli colocamos el siguiente comando ```sudo nano ~/.bashrc``` alli nos pedira el password 2763867v
	2. bajamos con las flechas del teclado hasta el final, alli debemos dar par de enter y escribir todo lo siguiente: ```alias sail="./vendor/bin/sail"```
	3. debemos presionar ```control o``` para guardar los cambios y ```control x``` para salir
	4. ahora colocamos en la terminal => ```source ~/.bashrc``` para que refresque los cambios, despues ya podemos utilizar el alias
b. si ya tenemos, abrir power shell, loguearnos ```wsl --distribution Ubuntu --user josue```, estando alli colocamos colocar el comando que necesitemos, ejemplo:
	1. para levantar el container 		=> ```sail up``` en lugar de estar colocando ``` ./vendor/bin/sail up```
	2. para eliminar el container docker 	=> ```sail down```
	3. para instalar todo lo de npm 	=> ```sail npm install```
	4. para correr la migracion de laravel a la base de datos => ```sail php artisan migrate```
	5. para ver que version de php tiene nuestro proyecto, debemos tener nuestro proyecto corriendo ```sail up```, entonces desde otra terminal, power shell, loguearnos ```wsl --distribution Ubuntu --user josue```, podemos colocar el comando ```sail php -v```
	6. para saber que version de npm tenemos, hacemos lo mismo del paso 5, pero el comando seria ```sail npm -v```


## plugins

debemos tener docker desktop corriendo 
como este proyecto se creó con docker, entonces para instalar los comando debemos abrir una terminal, despues colocar ```wsl --distribution Ubuntu --user josue``` para loguearnos en la terminal, ya estando alli si podemos ejecutar los comandos. 

es de destacar que laravel posee en sus vendor una herramienta que nos ayuda con todo lo de docker que se llama sail, podemos acceder a ella mediante una cd ./vendor/bin/sail o con un alias para con colocar en la terminal sail estariamos diciendo ./vendor/bin/sail, si queremos crear el alias, [Pasos para crear alias de sail](#pasos-para-crear-alias-de-sail)

Importante, antes de ejecutar los comandos, debemos desde la terminal movernos a la carpeta donde esta nuestro proyecto

1. instalar tailwindcss documentacion oficial <https://tailwindcss.com/docs/guides/laravel>
   *  ```sail npm install -D tailwindcss postcss autoprefixer``` lo mismo que escribir ```./vendor/bin/sail npm install -D tailwindcss postcss autoprefixer ```
   *  ahora debemos ejecutar ```sail npx  tailwindcss init  -p```` para que cree los archivos de configuracion de tailwindcss
   *  en el archivo ```tailwind.config.js``` que nos acaba de crear, le agregamos la ruta donde va a estar el archivo que será el template principal del proyecto, para este ejemplo es: ```./resources/views/layout/app.blade.php``` o podemos decir que se aplique en todas las plantillas .blade, quedando el archivo ```tailwind.config.js``` algo como:
        ```
        export default {
        content: [
            // "./resources/views/layout/app.blade.php"
            "./resources/**/*.blade.php",
            "./resources/**/*.js",
        ],
        theme: {
            extend: {},
        },
        plugins: [],
        }

        ```
    * ahora abrimos el archivo ```app.css``` que esta en la carpeta ```resources/css```, y pegamos lo siguiente
        ```
        @tailwind base;
        @tailwind components;
        @tailwind utilities;
        ```
    * ahora para modo desarrollo, corremos en la terminal el comando ```sail npm run dev ```
2. para que las validaciones salgan en español, vamos la siguiente ruta <https://github.com/MarcoGomesr/laravel-validation-en-espanol>, descargamos el .zip, y en la carpeta resource de nuestro proyecto, creamos una carpeta llamada 'lang' y otra 'es', quedando algo como ```resources/lang/es``` y pegamos lo descargado descomprimido. de todos modos en el readme del plugin ya esta la documentacion en español

3. recordemos que debemos estar logueado en wsl y en la carpeta del proyecto, alli ejecutamos  ```sail npm install --save dropzone``` dropzone, sirve para adjuntar imagenes. pagina oficial,  https://docs.dropzone.dev/getting-started/installation/npm-or-yarn. su css esta en ```<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />```

4. manejo de imagenes desde php 'sube, recorta etc': ``` sail composer require intervention/image  ``` agregamos un plugin o paquete proyecto, no al frontend si no al backend. documentacion oficial <https://image.intervention.io/v2/introduction/installation> aqui muestra como integrar con laravel. debemos abrir el archivo app.php de la carpeta config, y agregar en los providers ```Intervention\Image\ImageServiceProvider::class ```, en ese mismo archivo, donde estan los ```aliases``` se agrega ```'Image' => Intervention\Image\Facades\Image::class```. de todos modos, en el enlace anterior explica en detalle

5. para algunos iconos, se utilizo <https://heroicons.com/> pero no se instalo, solo se fue alli y se copio el svg que la pagina generaba

6. livewire es un componente de laravel super potente que hace que laravel con blade hagan llamadas tipo ajax sin tener que refescar la pagina. como los pasos de uso son mas de una linea aqui esta el  [enlace de documentacion que cree sobre livewire](#laravel-livewire)








## laravel livewire

peticiones tipo ajax sin refrescar pagina, algo como un select dependiente, tipo pais, estado, municipio y esas cosas. tambien sive para mas cosas, depende de lo que se requiera, para este ejemplo es para renderizar el corazon de darle like a las publicaciones de los usuarios, sin tener que refrescar la pagina con cada like que le dan

este es un ejemplo de como utilizarlo, desde instalar a como utilizarlo de forma secilla

documentacion oficial <https://laravel-livewire.com/docs/2.x/loading-states>

1. instalacion: para ello ejecutamos en una terminal, el comando: ```sail composer require livewire/livewire```

2. luego del paso anterior, donde tenemos nuestra plantilla del proyecto, para este ejemplo se llama app.blade.php, antes de cerrar la etiqueta header del html vamos a pegar esto ```@livewireStyles```, y al final de la etiqueta body, vamos a pegar ```@livewireScripts```

3. crear un primer componente: para este ejemplo se crea un componente para hacer like en los post o publicaciones de este proyecto, sin tener que refrescar toda la pagina. para hacer ello:
   1. ejecutamos: ```sail php artisan make:livewire like-post```
   2. al ejecutar este comando si pregunta en ingles algo como ```Would you like to show some love by starring the repo? (yes/no)``` le decimos que ```no```.
   3. al terminar de hacer el paso 2, nos habra creado 2 archivos:
      * uno en la carpeta ```app/http/livewire/``` para este ejemplo el archivo se llamaria ```LikePost ``` . este archivo tiene funcionalidad parecido a los componentes, ese archivo podemos consultar bd, o tener validaciones si deseamos
      * otro es la vista como tal, ese está en la ruta: ```resources/views/livewire```, esta vista debe retornar un div, o contenedor unico que envuelva todo ese html, algo como los Fragment de react

4. pasos para pasar una variable desde una vista blade a nuestro archivo livewire
   * debemos abrir el archivo relacionado a la vista que nos genero livewire, para este ejemplo es: ```LikePost```, y alli creamos una variable como publica, algo como ```public $post;```
   * en la vista padre 'donde vamos a utilizar nuestro fragmento de codigo livewire', para este ejemplo ```show.blade.php```, pegamos ```<livewire:like-post :post="$post"  />```, donde ```like-post``` es la vista blade html que va a renderizar, y ```post``` es la variable que recibira la vista

5. eventos: luego de tener las vista generada, debemos tener un evento que se encargue de llamar al backend, estos eventos son iguales que los html, solo que se escriben diferente, ejemplo:
    en el siguiente codigo muestra que al hacer click en el boton llamara a un metodo llamado like() de la clase 'LikePost', y que mientras el backend response de va a deshabilitar el boton 
```
<button wire:click="like"  wire:loading.attr="disabled">
```
6. importante, refrescar la pagina: para que el componente 'like-blade.php' se actualice en el navegador despues de ir al backend 'LikePost.php', las propiedades que esten alli y no sean las que enviamos como parametro cambien. para el ejemplo de los likes, creamos una propiedad de la clase llamada public $isLiked, la cual tendra un valor bolean y nos ayuda a determinar si pintamos de rojo o no el corazon. en ese mismo archivo tenemos una propiedad llamada public ```$totalLikes``` donde solo si ella cambia, se refresca en el html el total de likes que tiene el post, si no los actualizamos el html se mantiene intacto
   
7. ejemplo del contenido del archivo ```LikePost.php```
```
<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LikePost extends Component {

    public $post;
    public $isLiked; // boolean, para saber si tiene like o no el post o publicacion en nuestra version de instagram
    public $totalLikes;


    // este metodo es del cliclo de vida, algo como el onInit de angular, lo creamos solo para que esa variable llegue a la vista y saber si pintar el corazon de rojo porque ya dieron like o sin relleno porque no han dado like
    // este metodo se llama cada vez que se mande a llamar este componente likePost, esta funcion se ejecuta automaticamente, algo como si fuera un contructor
    public function mount(): void {
        $this->isLiked = $this->post->checkLike(auth()->user());
        $this->totalLikes = $this->post->likes->count();
    }

    // este metodo no retorna nada, pero sus variables llegan a la vista, entonces son esas variables las que hacen el cambios alli
    public function like() {
        //la siguiente linea es como si dijeramos $post->checkLike(auth()->user()), checkLike es un metodo del modelo de post para saber si un usuario ya dio like a un post o no
        if ($this->post->checkLike(auth()->user())) {
            // si entra aqui, es que el usuario ya le dio like y quiere quitar el like que dio
            $this->post->likes()->where('user_id', auth()->user()->id)->delete();
            $this->isLiked = false;
            $this->totalLikes--;
        } else {
            // si no ha dado like a el post, entonces guardamos ese like
            $this->post->likes()->create(['user_id' => auth()->user()->id]);
            $this->isLiked = true;
            $this->totalLikes++;
        }
    }


    public function render() {
        return view('livewire.like-post');
    }
}

```

8. ejemplo del contenido de la vista ```like-post.blade.php```
```
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

```


# pasos para hacer del deployment o pasar a produccion un proyecto laravel

este proyecto es laravel 10 

1. ejecutamos ```npm run build``` para comprimir el js y css, lo generado se coloca en la carpeta ```public/build```
2. abrir el .gitignore y quitar esto ```/public/build``` esto es para que git si suba lo que se compile para produccion, esto es para no tener que compilar 'ejecutar el pasado comando' en la terminal del servidor a donde vayamos a subir, igual este paso es opcional, si queremos o no ejecutar el paso 1 en el servidor como tal
3. hay mas pasos pero no los segui haciendo porque eran para railway y ademas el profesor los estaba pasando a la rama master y no me gusto


# pasos para hacer un deploy endom cloud

<https://domcloud.co/>

1. desde un navegador debemos loguearnos, preferiblemente desde github, para que tome el proyecto desdea alli
2. estando en la url <https://my.domcloud.co/user/host/> hacemos click donde dice ```agregar website```
3. ahora hacemos click en  ```No, I want to start from scratch```, ya que no queremos utilizar ningun template, ya que nuestro proyecto ya tiene laravel instalado
4. donde dice ```custom template```, borramos todo el texto que va alli
5. aqui esta la guia de los pasos <https://blogjc.vercel.app/post/easy-deploy-php-laravel> debemos bajar con el scroll y seguir leyendo. debemos estar atento si estamos utilizando livewire 2 o 3. si es que lo estamos utilizando
