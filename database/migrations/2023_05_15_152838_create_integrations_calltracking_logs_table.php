<?php

use App\Models\Project\Integrations\Calltracking\Phone;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegrationsCalltrackingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrations_calltracking_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects');

            $table->foreignIdFor(Phone::class);
            $table->json('log');

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
        Schema::dropIfExists('integrations_calltracking_logs');
    }
}
