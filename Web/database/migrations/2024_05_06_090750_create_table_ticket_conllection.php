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
        Schema::create('table_ticket_collection', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id tự động tăng');
            $table->string('title', 255)->comment('Tiêu đề địa ticket');
            $table->text('description')->comment('description của ticket');
            $table->string('group', 255)->comment('Loại vé ticket(VIP,stand,...)');
            $table->bigInteger('amount')->comment('Số lượng vé phát hành');
            $table->string('photo',255)->comment('Link ảnh loại vé');
            $table->bigInteger('available_amount')->comment('Số lượng vé khả dụng');
            $table->bigInteger('task_id')->comment('id của bảng tasks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_ticket_collection');
    }
};
