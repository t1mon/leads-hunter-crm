<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('journal', function (Blueprint $table) {
            // Очистка таблицы
            DB::table('journal')->truncate();

            // // Удалить старые колонки
            $table->dropColumn('data');
            $table->dropColumn('date');

            // // Создать новые колонки
            $table->unsignedSmallInteger('type');
            $table->unsignedInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->text('text');
            $table->timestamp('created_at');

            // Индексация
            $table->index('project_id');
            $table->index(['type', 'project_id']);
            $table->index(['type', 'project_id', 'created_at']);
            $table->index(['project_id', 'created_at']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journal', function (Blueprint $table) {
            // Удалить новые колонки
            $table->dropColumn('type');
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
            $table->dropColumn('text');
            $table->dropColumn('created_at');
            

            // Вернуть старые колонки
            $table->json('data');
            $table->timestampTz('date');
        });
    }
}
