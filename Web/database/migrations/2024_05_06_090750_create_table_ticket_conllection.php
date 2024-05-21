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
        Schema::create('ticket_collection', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('id tự động tăng');
            $table->string('title', 255)->nullable()->comment('Tiêu đề địa ticket');
            $table->text('description')->nullable()->comment('description của ticket');
            $table->string('group', 255)->nullable()->comment('Loại vé ticket(VIP,stand,...)');
            $table->bigInteger('amount')->default(0)->comment('Số lượng vé phát hành');
            $table->string('photo', 255)->nullable()->comment('Link ảnh loại vé');
            $table->bigInteger('available_amount')->default(0)->comment('Số lượng vé đã sử dụng');
            $table->uuid('task_id')->nullable()->comment('id của bảng tasks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_collection');
    }
};
