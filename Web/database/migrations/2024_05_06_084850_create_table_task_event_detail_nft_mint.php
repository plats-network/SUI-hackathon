<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_event_detail_nft_mint', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id tự động tăng');
            $table->string('address_nft', 255)->comment('địa chỉ nft');
            $table->unsignedBigInteger('task_event_detail_id')->comment('id của bảng task_event_details');
            $table->timestamp('created_at')->useCurrent()->comment('ngày tạo');
            $table->unsignedBigInteger('task_id')->comment('id của bảng tasks');
            $table->string('tx_hash', 255)->comment('hash của transaction khi mint');
            $table->text('digest')->comment('response của contract');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_event_detail_nft_mint');
    }
};
