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
        Schema::table('nft_mints', function (Blueprint $table) {
            $table->string('nft_category')->nullable();
            $table->text('nft_res')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nft_mints', function (Blueprint $table) {
            $table->dropColumn('nft_category');
            $table->dropColumn('nft_res');
        });
    }
};
