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
            $table->uuid('id')->primary()->comment('id tự động tăng');
            $table->text('address_nft')->comment('địa chỉ address nhận nft');
            $table->uuid('task_event_detail_id')->nullable()->comment('id của bảng task_event_details');
            $table->timestamp('created_at')->useCurrent()->comment('ngày tạo');
            $table->uuid('task_id')->nullable()->comment('id của bảng tasks');
            $table->text('digest')->comment('digest response của contract');
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
