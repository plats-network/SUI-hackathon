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
        Schema::create('nft_mints', function (Blueprint $table) {
            $table->id();
            $table->string('address_nft')->nullable();
            $table->string('nft_title')->nullable();
            $table->string('nft_symbol')->nullable();
            $table->string('nft_uri')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('seed')->nullable();
            $table->string('private_organizer')->nullable();
            $table->string('address_organizer')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->default(1)->nullable();
            $table->string('metadata')->nullable();
            $table->string('booth_id')->nullable();
            $table->string('task_id')->nullable();
            $table->string('session_id')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nft_mints');
    }
};
