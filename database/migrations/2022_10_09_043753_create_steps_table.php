<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tool_id')->comment('機台ID');
            $table->string('name')->comment('步驟名稱');
            $table->integer('max_slot')->comment('最大可製作數量');
            $table->integer('min_slot')->comment('最小可製作數量');
            $table->integer('step_time')->comment('步驟花費時間');
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
        Schema::dropIfExists('steps');
    }
}
