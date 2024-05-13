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
        Schema::table('task_event_detail_nft_mint', function (Blueprint $table) {
            $table->string('address_nft', 255)->nullable()->comment('địa chỉ address nhận nft')->change();
            $table->renameColumn('digest', 'txt_hash')->comment('txthash response của contract');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_event_detail_nft_mint', function (Blueprint $table) {
            $table->dropColumn('address_nft')->nullable(false)->change();
        });
    }
};
