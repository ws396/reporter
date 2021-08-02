<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects_users', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();;
            $table->foreign('user_id')
                ->references('id')
                ->on('users')->onDelete('cascade');
            $table->bigInteger('project_id')->unsigned();;
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')->onDelete('cascade');
            $table->boolean('is_lead')->default(false);

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
        Schema::dropIfExists('projects_users');
    }
}
