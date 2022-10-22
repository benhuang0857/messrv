<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_id')->comment('規格Id');
            $table->string('product_code')->comment('產品代碼');
            $table->string('product_name')->comment('產品名稱');
            $table->string('pic_path')->nullable()->comment('產品照片');
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
        Schema::dropIfExists('products');
    }
}
