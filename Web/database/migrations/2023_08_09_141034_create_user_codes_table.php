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
        Schema::create('user_codes', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('user_id');
            $table->uuid('task_id');
            $table->tinyInteger('type')->default(0)->comment('0: Sesion, 1: Booth');
            $table->integer('number_code')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });

        Schema::table('task_event_details', function (Blueprint $table) {
            $table->dropColumn('is_answer');
            $table->dropColumn('question_text');
            $table->boolean('is_required')->default(false)->comment('Bắt buộc or không');
            $table->string('question')->nullable()->comment('Câu hỏi 1');
            $table->string('a1')->nullable()->comment('Câu trả lời 1');
            $table->string('a2')->nullable()->comment('Câu trả lời 2');
            $table->string('a3')->nullable()->comment('Câu trả lời 3');
            $table->string('a4')->nullable()->comment('Câu trả lời 4');
            $table->boolean('is_a1')->default(false)->comment('Đáp án đúng a1');
            $table->boolean('is_a2')->default(false)->comment('Đáp án đúng a2');
            $table->boolean('is_a3')->default(false)->comment('Đáp án đúng a3');
            $table->boolean('is_a4')->default(false)->comment('Đáp án đúng a4');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_codes');
    }
};
