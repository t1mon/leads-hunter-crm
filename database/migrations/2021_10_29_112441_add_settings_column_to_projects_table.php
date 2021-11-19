<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSettingsColumnToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('settings')->after('name');
        });

        DB::table('projects')->update([
            'settings' => [
                'email' => [
                    'fields' => [],
                    'enabled' => false,
                ],
                
                'telegram' => [
                    'fields' => [],
                    'enabled' => false,
                ],

                'timezone' => 'UTC',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('settings');
        });
    }
}
