<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_id')->comment('工號');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('department')->comment('部門');
            $table->string('job_title')->comment('職稱');
            $table->string('gender')->comment('性別');
            $table->boolean('state')->default(0)->comment('狀態(0 is false)');
            $table->text('note')->nullable()->comment('註記');
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
