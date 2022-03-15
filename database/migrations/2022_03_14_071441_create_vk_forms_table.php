<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVKFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vk_forms', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('project_id')->nullable(); //Идентификатор проекта
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->string('url'); //Адрес, на который приходит заявка
            $table->string('confirmation_response'); //Ответ, который должен придти в ответ на подтверждение
            $table->string('group_id'); //Идентификаторы группы, от которых будут приходить заявки
            $table->string('host'); //Посадочная, которая будет добавляться к лиду
            $table->string('source')->nullable(); //Источник, который будет добавляться к лиду
            $table->boolean('enabled')->default(true); //Указывает, включен ли приём данных с формы
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
        Schema::dropIfExists('vk_forms');
    }
}
