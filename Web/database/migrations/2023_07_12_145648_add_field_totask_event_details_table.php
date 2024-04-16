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
        Schema::table('task_event_details', function (Blueprint $table) {
            $table->boolean('is_question')->default(false)->comment('Có câu hỏi không')->after('status');
            $table->text('question_text')->nullable()->comment('')->after('is_question');
            $table->boolean('is_answer')->default(false)->comment('Cau tra loi')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
