<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSincronazedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlite')->create('sincronazed_products', function (Blueprint $table) {
            $table->id();
            $table->integer('codprod');
            $table->string('descricao');
            $table->string('undvenda');
            $table->string('cod_barra');
            $table->decimal('estoque', 14, 2);
            $table->decimal('vlr_venda', 14, 2);
            $table->string('controlado');
            $table->char('antibio', 1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sincronazed_products');
    }
}
