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
            $table->uuid('id')->primary()->comment('id tự động tăng');
            $table->string('address_nft', 255)->comment('địa chỉ address nhận nft');
            $table->uuid('ticket_id')->nullable()->comment('id tự động tăng của bảng ticket collection');
            $table->timestamp('created_at')->useCurrent()->comment('Ngày tạo');
            $table->text('digest')->comment('digest response của contract');
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
