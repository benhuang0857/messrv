<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('batch_code')->comment('明細編號');
            $table->string('run_id')->comment('工作紀錄Id');
            $table->string('prod_processes_list_id')->comment('產品製程Id');
            $table->string('doer_id')->comment('操作者Id');
            $table->integer('quantity')->default(1)->comment('執行數量');
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
        Schema::dropIfExists('batches');
    }
}
