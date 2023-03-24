<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegrationsTgBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrations_tg_bots', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedInteger('project_id')->nullable(); //Если класс является общим, ему не нужно указывать номер проекта
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            
            $table->string('username');
            $table->string('api_token');
            $table->string('webhook_token')->nullable();
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
        Schema::dropIfExists('integrations_tg_bots');
    }
}
