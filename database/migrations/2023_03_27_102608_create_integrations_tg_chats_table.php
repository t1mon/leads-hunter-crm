<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegrationsTgChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrations_tg_chats', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('project_id')->nullable(); //Если класс является общим, ему не нужно указывать номер проекта
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->string('title')->nullable();
            $table->string('type')->nullable();
            $table->string('chat_id')->nullable();
            $table->string('invite');
            $table->string('format');
            $table->boolean('confirmed')->default(false);
            $table->boolean('enabled')->default(true);

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
        Schema::dropIfExists('integrations_tg_chats');
    }
}
