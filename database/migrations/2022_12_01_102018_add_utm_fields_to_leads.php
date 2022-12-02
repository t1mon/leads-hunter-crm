<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUtmFieldsToLeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('utm_medium')->after('utm')->nullable();
            $table->string('utm_source')->after('utm_medium')->nullable();
            $table->string('utm_campaign')->after('utm_source')->nullable();
            $table->string('utm_content')->after('utm_campaign')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('utm_medium');
            $table->dropColumn('utm_source');
            $table->dropColumn('utm_campaign');
            $table->dropColumn('utm_content');
        });
    }
}
