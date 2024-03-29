<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            //$table->integer('taskgiver_id');
            $table->integer('lasteditor_id')->nullable();
            //$table->integer('task_executor_user_id');
            $table->integer('project_id');
            //$table->integer('team_id')->default(0);
            $table->dateTime('task_start');
            $table->dateTime('task_end');
            $table->string('task_worktime');
            $table->integer('task_status');
            $table->mediumText('task_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
