<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads_comments', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('user_id');

            $table->unsignedBigInteger('lead_id'); //Пришлось взять тип BIGINT, потому что в таблице leads такой же
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');

            $table->unsignedInteger('project_id');

            $table->text('comment_body');

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
        Schema::dropIfExists('leads_comments');
    }
}
