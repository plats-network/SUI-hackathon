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
        Schema::create('user_rewards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('reward_id')->index();
            $table->unsignedInteger('amount')->default(0);
            $table->timestamps();
        });

        Schema::create('user_reward_temps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('reward_id')->index();
            $table->unsignedInteger('amount')->default(0);
            $table->boolean('status')->default(false)->comment('false: chưa chuyển, true: đã chuyển sang maintray');
            $table->timestamps();
        });

        Schema::create('user_task_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('task_id')->index();
            $table->uuid('reward_id')->nullable()->comment('Reward nếu có');
            $table->uuid('source_id')->index()->comment('checkin, social, event...');
            $table->tinyInteger('type')->default(0)->comment('0: checkin, 1: social, 2: event...');
            $table->unsignedInteger('amount')->default(0);
            $table->tinyInteger('status')->default(0)->comment('0: review, 1: done, 2: reject');
            $table->string('checkin_img')->nullable()->comment('Nếu checkin');
            $table->string('ip_address')->nullable();
            $table->string('agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_rewards');
        Schema::dropIfExists('user_task_histories');
        Schema::dropIfExists('user_reward_temps');
    }
};
