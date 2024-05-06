<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_nft_mint', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id tự động tăng');
            $table->string('address_nft', 255)->comment('địa chỉ address nhận nft');
            $table->bigInteger('ticket_id')->comment('id tự động tăng của bảng ticket collection');
            $table->timestamp('created_at')->useCurrent()->comment('Ngày tạo');
            $table->text('digest')->comment('response của contract');
            $table->string('tx_hash', 255)->comment('hash của transaction khi mint');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_nft_mint');
    }
};
