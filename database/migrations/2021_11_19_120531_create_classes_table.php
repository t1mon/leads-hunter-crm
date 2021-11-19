<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); //Общий или частный
            $table->unsignedInteger('color'); //Цвет хранится в виде 32-битного целого числа (например #ADFF2F)
            $table->unsignedInteger('project_id')->nullable(); //Если класс является общим, ему не нужно указывать номер проекта
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads_classes');
    }
}
