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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->uuid('user_id')->nullable();
            $table->uuid('nft_id')->nullable();
            $table->string('email')->nullable();
            $table->double('amount')->nullable()->default(0);
            $table->text('description')->nullable();
            $table->date("due_date")->nullable()->default(null);
            $table->string('invoice_number')->unique();
            $table->boolean('paid')->default(false);
            $table->string('link')->nullable();
            $table->text('stripe_payment_id')->nullable();
            //Network, Mainnet, Testnet
            $table->string('network')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_details');
    }
};
