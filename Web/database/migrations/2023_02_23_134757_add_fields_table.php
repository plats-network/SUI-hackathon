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
        Schema::table('tasks', function($table) {
            $table->string('address', 255)->nullable()->comment('Thêm data khi là events')->after('type');
            $table->string('lat')->nullable()->comment('Thêm data khi là events')->after('address');
            $table->string('lng')->nullable()->comment('Thêm data khi là events')->after('lat');
            $table->string('slug')->nullable()->comment('slug')->after('lng');
        });

        Schema::create('user_event_likes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('task_id')->index();
            $table->timestamps();
        });

        Schema::create('event_user_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable()->comment('Nếu user đã đang ký thì khác null');
            $table->uuid('task_id')->index()->comment('Event id');
            $table->string('name')->nullable()->comment('Họ và tên');
            $table->string('phone')->default(0)->comment('Thời gian trả lời đúng các câu hỏi');
            $table->string('email')->nullable()->comment('Email người nhận vé dự sự kiện');
            $table->integer('type')->default(0)->comment('0: User, 1: Guest');
            $table->timestamps();
        });

        Schema::create('event_send_mail_histories', function (Blueprint $table) {
            // Logs gui vé event đến user thành công hay thất bại
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->uuid('task_id')->index();
            $table->boolean('status')->default(false)->comment('Gửi mail thành công thì status = true');
            $table->integer('type')->default(0)->comment('0: user đã đăng ký, 1: user chưa đăng ký chỉ nhận vé (guest)');
            $table->boolean('retry_num')->integer()->default(0)->comment('Số lần gửi mail thất bại');
            $table->timestamps();
        });

        Schema::create('quizs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('task_id')->index();
            $table->string('name')->nullable()->comment('Câu hỏi');
            $table->integer('time_quiz')->default(10)->comment('thời gian trả lời câu hỏi');
            $table->integer('order')->default(0)->comment('Thứ tự sắp xếp');
            $table->boolean('status')->default(true)->comment('Ẩn/Hiện');
            $table->timestamps();
        });

        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('quiz_id')->index();
            $table->string('name')->nullable()->comment('Câu trả lời cho câu hỏi');
            $table->boolean('status')->default(false)->comment('true: Đúng, fasle: Sai => 4 câu trả lời chỉ 1 câu đúng');
            $table->timestamps();
        });

        Schema::create('user_quiz_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('quiz_id')->index();
            $table->uuid('quiz_answer_id')->index();
            $table->boolean('status')->default(false)->comment('true: Đúng, fasle: Sai');
            $table->integer('time_answer')->default(0)->comment('Thời gian trả lời đúng các câu hỏi');
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
        Schema::dropIfExists('user_event_likes');
        Schema::dropIfExists('event_user_tickets');
        Schema::dropIfExists('event_send_mail_histories');
        Schema::dropIfExists('quizs');
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('user_quiz_answers');
    }
};
