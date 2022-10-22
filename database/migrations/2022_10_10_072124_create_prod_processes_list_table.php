<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdProcessesListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_processes_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id')->comment('產品Id');
            $table->string('process_id')->comment('製程Id');
            $table->integer('order')->default(0)->comment('排序');
            $table->integer('process_time')->comment('製程花費時間');
            $table->integer('max_slot')->comment('最大可製作數量');
            $table->integer('min_slot')->comment('最小可製作數量');
            $table->boolean('state')->default(0)->comment('狀態(0 is false)');
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
        Schema::dropIfExists('prod_processes_list');
    }
}
