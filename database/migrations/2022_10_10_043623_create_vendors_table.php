<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name')->comment('客戶公司名稱');
            $table->string('contact_name')->comment('客戶姓名(聯絡人)');
            $table->string('phone')->nullable()->comment('電話');
            $table->string('mobile')->nullable()->comment('手機號碼');
            $table->string('gui_number')->comment('統一編號');
            $table->string('address')->comment('地址');
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
        Schema::dropIfExists('vendors');
    }
}
