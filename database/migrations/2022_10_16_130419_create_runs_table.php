<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('runs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('run_code')->comment('工作編號');
            $table->string('maker_id')->comment('創建者Id');
            $table->string('product_id')->comment('產品Id');
            $table->integer('quantity')->default(1)->comment('總執行數量');
            $table->integer('each_quantity')->default(1)->comment('每批數量');
            $table->datetime('start_time')->comment('開始時間');
            $table->datetime('end_time')->comment('開始時間');
            $table->integer('run_second')->default(0)->comment('實際秒數');
            $table->string('state')->default('peddning')->comment('狀態(pending/approve/disapprove/cancel)');
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
        Schema::dropIfExists('runs');
    }
}
