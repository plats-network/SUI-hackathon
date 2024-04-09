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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->tinyInteger('role')->default(USER_ROLE);
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('wallet_address')->nullable();
            $table->string('wallet_name')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->char('confirmation_code', 6)->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('discord')->nullable();
            $table->string('telegram')->nullable();
            $table->integer('gender')->nullable()->comment('0: Nam, 1: Nu, 2: Khac');
            $table->date('birth')->nullable()->comment('năm sinh');
            $table->string('avatar_path')->nullable()->comment('ảnh đại diện');
            $table->boolean('check_avatar')->default(false)->comment('duyện ảnh đại diện');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
