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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('creator_id')->nullable();
            $table->string('name')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('banner_url')->nullable();
            $table->dateTimeTz('start_at')->nullable();
            $table->dateTimeTz('end_at')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0:draft, 1:public');
            $table->tinyInteger('type')->default(0);
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
          
        });

        Schema::create('task_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('task_id')->index();
            $table->uuid('reward_id')->index();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('amount')->default(0)->comment('Từ 0 -> 10.000 reward');
            $table->unsignedTinyInteger('job_num')->default(1)->comment('Số lượng job hoàn thành để nhận đc reward');
            $table->unsignedTinyInteger('order')->default(0);
            $table->unsignedTinyInteger('status')->default(0)->comment('0: Draft, 1: Public');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('task_location_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('task_location_id')->index();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->boolean('status')->default(true)->comment('false: ẩn, true: hiện');
            $table->unsignedTinyInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('task_socials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('task_id')->index();
            $table->uuid('reward_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('platform')->default(0)->comment('0: Twitter, 1: Fb, 2: Telegram, 3: Discord');
            $table->tinyInteger('type')
                ->default(0)
                ->index()
                ->comment('0. Follow/Like(Page FB), 1. Share/Retweet, 2. Tweet/Post, 3. Join(Discord, Telegram)');
            $table->string('url')->nullable()->comment('Url for type social task');
            $table->integer('amount')->nullable();
            $table->tinyInteger('order')->default(0);
            $table->boolean('status')->default(true)->comment('false: ẩn, true: hiện');
            $table->boolean('lock')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('task_galleries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('task_id')->index();
            $table->string('url_image');
            $table->boolean('status')->default(true)->comment('false: hidden, true: show');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('task_guides', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('task_id')->index()->comment('');
            $table->string('url_image')->nullable()->comment('');
            $table->integer('order')->default(0)->comment('Thứ tự hiển thị');
            $table->boolean('status')->default(true)->comment('false: hidden, true: show');
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
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_locations');
        Schema::dropIfExists('task_location_jobs');
        Schema::dropIfExists('task_socials');
        Schema::dropIfExists('task_galleries');
        Schema::dropIfExists('task_guides');
    }
};
