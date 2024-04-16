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
        Schema::create('task_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('task_id')->index();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('banner_url')->nullable();
            $table->tinyInteger('type')->default(0)->comment('0: Session, 1: Booth, 2: Hub...');
            $table->tinyInteger('max_job')->default(0)->comment('Số nhiệm vụ hoàn thành');
            $table->boolean('status')->default(true)->comment('true: show, false: hidden');
            $table->timestamps();
        });

        Schema::create('task_event_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('task_event_id')->index();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('task_event_rewards', function (Blueprint $table) {
            $table->uuid('task_id')->index();
            $table->uuid('reward_id')->index();
            $table->tinyInteger('amount')->default(1)->comment('Số lượng giải');
            $table->timestamps();
        });

        Schema::create('user_join_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('task_event_detail_id')->index();
            $table->string('agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        Schema::create('user_event_bonuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('task_event_id')->index();
            $table->string('code')->nullable();
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
        Schema::dropIfExists('task_events');
        Schema::dropIfExists('task_event_details');
        Schema::dropIfExists('task_event_rewards');
        Schema::dropIfExists('user_join_events');
        Schema::dropIfExists('user_event_bonuses');
    }
};
