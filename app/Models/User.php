<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Post;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token', // este campo es para el checkbox de si quiere que se le recuerde la contraseña o no, eso lo hace laravel automaticamente
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // casts es para los inputs que tengan un trato especial, ejemplo: email_verified_at lo coloca internamente y el encriptado de la contraseña, le decimos el metodo con el que lo hará 'hashed'
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];




    /*
        USUARIOS Y SEGUIDORES SE RELACIONAN DE N A M, pero no como la conocemos,
        sino especial, ya que en la tabla seguidores existiran dos id de usuarios, claro con diferente nombre para diferenciarlo,
        cuando ocurre este este caso SE DA UN TRATO ESPECIAL,

        tabla seguidors => campos --> 'user_id', 'user_seguidor_id'

        RECORDEMOS QUE EN LA TABLA SEGUIDORS APARECERAN DOS VECES EL ID DE USUARIO,
        user_id => COMO SEGUIDO
        user_seguidor_id => como seguidor

        EN EL MODELO QUE CONSIDEREMOS MAS IMPORTANTE EN TERMINOS DE RELACION,
        ALLI PONDREMOS LOS METODOS QUE MANEJARAN LAS RELACIONES, para este caso es el modelo User.

        NOTA: como no es una relacion n a m normal,
        entonces la relacionamos con el nombre de la tabla 'seguidors',
        y el modelo que queremos que nos devulva 'User:CLASS'
    */



    // lista quienes me siguen
    // devuelve un modelo de User, donde el usuario dueño de la instancia actual $this-> sea igual al user_id en la tabla seguidors
    public function seguidores() {
        // seguidors => es el nombre de la tabla de la db donde tiene que buscar la relacion
        // this-> es igual a user_id para el momento de hacer consultas
        return $this->belongsToMany(User::class, 'seguidors', 'user_id', 'user_seguidor_id');
        /*                              |               |           |                   |
                                        |               |           |                   |
        tipo de dato que retornará <----|               |           |                   |
                                                        |           |                   |
        nombre de la tabla con la que se relacionará <--|           |                   |
                                                                    |                   |
                                                                    |                   |
        campo principal, automaticamente sera                       |                   |
        $this->id del de la instancia de usuario User               |                   |
        ejemplo. $user->seguidores()                                |                   |
        7->seguidores->count() ---> num d seguidores dl usuario 7   |                   |
        la consulta seria a la tabla donde  user_id=7       <-------|                   |
                                                                                        |
                                                                                        |
                                                                                        |
        campo secundario, que es con el será el retorno                                 |
        ejemplo. si el usuario 7, llamemozlo Rihana tiene 10 seguidores,                |
        el id de esos seguidores corresponderan al campo user_seguidor_id,              |
        de la tabla 'seguidors'.                                                        |
        ejemplo 1 this = usuario 7:                                                     |
        si this->seguidores->attach(5);  al usuario 7 agregandole el seguidor 5         |
        ejemplo 2 this = usuario 7:                                                     |
        $this->seguidores()->get()->toArray(); lista todos los seguidores del usuario 7 |
        la anterior trae todos los campos de la tabla 'seguidors' donde el user_id = 7, |
        user_seguidor_id aparecera 10 veces, esas 10 id los busca el tabla users,       |
        y el retorno es los usuario correspondiente a esos 10 ids       <---------------|
        */
    }

    // lista los usarios a los que estamos siguiendo
    public function siguiendo() {
        // seguidors => es el nombre de la tabla de la db donde tiene que buscar la relacion
        // this-> es igual a user_seguidor_id para el momento de hacer consultas
        return $this->belongsToMany(User::class, 'seguidors', 'user_seguidor_id', 'user_id');
        /*                              |               |               |               |
                                        |               |               |               |
        tipo de dato que retornará <----|               |               |               |
                                                        |               |               |
        nombre de la tabla con la que se relacionará <--|               |               |
                                                                        |               |
                                                                        |               |
        campo principal, automaticamente sera                           |               |
        $this->id del de la instancia de usuario User                   |               |
        ejemplo. $user->siguiendo()                                     |               |
        7->siguiendo->count() --> num usuarios a quien sigue el user 7  |               |
        la consulta seria a la tabla donde  user_seguidor_id=7      <---|               |
                                                                                        |
                                                                                        |
                                                                                        |
        campo secundario, que es con el será el retorno                                 |
        ejemplo. si el usuario 7, llamemozlo Rihana sigue a 3 usuarios,                 |
        el id de esos siguiendo corresponderan al campo user_id,                        |
        de la tabla 'seguidors'.                                                        |
        ejemplo 1 this = usuario 7:                                                     |
        si this->siguiendo->attach(5);  al usuario 7 comenzo a seguir al usuario 5      |
        ejemplo 2 this = usuario 7:                                                     |
        $this->siguiendo()->get()->toArray(); lista usuarios q esta siguiendo el user 7 |
        la anterior trae todos los campos de la tabla 'seguidors',                      |
        donde el user_seguidor_id = 7, user_seguidor_id aparecera 3 veces,              |
        esos 3 id los busca el tabla users,                                             |
        y el retorno son los usuario correspondiente a esos 3 ids       <---------------|
        */
    }



    // verifica si un usuario sigue a otro o no. contains es para preguntar si lo contiene o no. retorna boolean
    public function seguidoPor(User $user): bool {
        // como esta comentado lo hice yo y no me funciono
        // return $this->seguidores->contains('user_seguidor_id', $user->id);

        return $this->seguidores->contains( $user->id);
    }



    /** inicio para relaciones entre las tablas */

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function comentarios() {
        return $this->hasMany(Comentario::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }
    /** fin para relaciones entre las tablas */
}
