<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramIDSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_ids', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->string('name'); //Имя контакта
            $table->string('number')->nullable(); //Идентификатор контакта
            $table->string('type'); //Тип контакта (канал или личка)
            $table->boolean('approved')->default(false); //Подтверждение канала (когда контакт добавляется к роботу)

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
        Schema::dropIfExists('telegram_ids');
    }
}
