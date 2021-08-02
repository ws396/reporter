<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_users', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();;
            $table->foreign('user_id')
                ->references('id')
                ->on('users')->onDelete('cascade');
            $table->bigInteger('task_id')->unsigned();;
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')->onDelete('cascade');
            $table->boolean('is_taskgiver')->default(false);

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
        Schema::dropIfExists('tasks_users');
    }
}
