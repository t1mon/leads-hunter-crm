<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegrationsEmailReaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrations_email_readers', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->unsignedInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->string('subject');
            $table->string('email');
            $table->string('password');
            $table->string('host');
            $table->text('template');
            $table->boolean('enabled')->default(false);
            $table->unsignedInteger('interval')->default(1);
            $table->unsignedInteger('mails_per_time')->default(10); //Количество писем, считываемых за раз
            $table->boolean('mark_as_read')->default(true);

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
        Schema::dropIfExists('integrations_email_reader');
    }
}
