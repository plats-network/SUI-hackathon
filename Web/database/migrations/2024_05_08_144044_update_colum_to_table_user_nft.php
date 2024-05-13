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
        Schema::table('user_nft', function (Blueprint $table) {
            $table->string('nft_mint_id', 255)->nullable()->comment('id của  bảng task_event_detail_nft_mint và ticket_nft_mint')->change();
            $table->uuid('id')->nullable()->comment('id tự động tăng')->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_nft', function (Blueprint $table) {
            $table->integer('nft_mint_id')->nullable(false)->change();
            $table->integer('id')->nullable(false)->change();
        });
    }
};
