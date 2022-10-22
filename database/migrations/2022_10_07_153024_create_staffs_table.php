<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_id')->comment('工號');
            $table->string('name')->comment('姓名');
            $table->string('department')->comment('部門');
            $table->string('job_title')->comment('職稱');
            $table->string('gender')->comment('性別');
            $table->boolean('state')->default(0)->comment('狀態(0 is false)');
            $table->text('note')->nullable()->comment('註記');
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
        Schema::dropIfExists('staffs');
    }
}
