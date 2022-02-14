<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTransferencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferencias', function (Blueprint $table) {
            $table->id();
            
            $table->decimal('valor', 15,2);

            $table->unsignedBigInteger('pagador_user_id');
            $table->foreign('pagador_user_id')->references('id')->on('users');

            $table->unsignedBigInteger('beneficiario_user_id');
            $table->foreign('beneficiario_user_id')->references('id')->on('users');

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
        Schema::dropIfExists('transferencias');
    }
}
