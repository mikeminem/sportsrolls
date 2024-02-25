<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableWebpage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webpages', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->integer('tournament_id')->unsigned();
            $table->integer('page_category_id')->default(0);

            $table->string('title',120);
            $table->string('slug',120);
            $table->string('description',240);
            $table->string('image',120);
            $table->text('content');

            $table->enum('status', [
                'En Desarrollo' =>'En Desarrollo', 
                'Por aprobar'   =>'Por aprobar',
                'Aprobar'       =>'Aprobar',
            ])->default('En Desarrollo');

            $table->enum('type', [
                'Pagina Web'    =>'Pagina Web', 
                'Anuncio'       =>'Anuncio', 
                'Convocatoria'  =>'Convocatoria'
            ])->default('Pagina Web');
            
            $table->enum('location', [
                'Encabezado'        =>'Encabezado', 
                'Pie de Pagina'     =>'Pie de Pagina', 
                'Lado Izquierdo'    =>'Lado Izquierdo',
                'Lado Derecho'      =>'Lado Derecho',
                'Abajo'             =>'Abajo',
                'No Ubicación'      => 'No Ubicación'
            ]);
            
            $table->integer('is_deleted')->default(0);
            $table->integer('views')->default(0);
            $table->integer('prints')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('dislikes')->default(0);

            $table->string('facebook',240);
            $table->string('instagram',240);
            $table->string('twitter',240);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webpages');
    }
}
