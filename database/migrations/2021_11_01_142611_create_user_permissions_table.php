<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            
            //Идентификаторы
            $table->unsignedBigInteger('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedBigInteger('role_id')->references('id')->on('roles')->onDelete('cascade');

            //Разрешения
            $table->boolean('manage_users')->default(false);
            $table->boolean('manage_settings')->default(false);
            $table->boolean('manage_payments')->default(false);
            $table->boolean('view_journal')->default(true);
            $table->json('view_fields');
        });

        DB::table('user_permissions')->update([
            'view_fields' => ['email', 'city', 'host'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
}
