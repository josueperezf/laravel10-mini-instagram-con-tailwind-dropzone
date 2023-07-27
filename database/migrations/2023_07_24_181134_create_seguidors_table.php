<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seguidors', function (Blueprint $table) {
            $table->id();
            // usuario que sigue -> seguido
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // si elimino un usuario, que tambien se elimine todos la lista de usuarios a quien el sigue
            // usuario al que siguen -> seguidor
            // IMPORTANTE  EN EL constrained Se le coloca el nombre de la tabla, ya que en esta tabla ambos campos hacen referencia a la tabla usuario. como este campo no se llama user_id, entonces hay que decirle a laravel manualmente a que tabla hace referencia este campo
            // el profesor le coloco algo como seguidor_id, se ve bien, pero para mi que estoy aprendiendo, cuando vuelva a ver este codigo, lo entenderé mas si el nombre del campo me dice algo
            $table->foreignId('user_seguidor_id')->constrained('users')->onDelete('cascade'); // si elimino un post, que tambien se elimine todos los comentarios que tenga ese post o publicacion
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguidors');
    }
};
