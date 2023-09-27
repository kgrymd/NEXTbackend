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
        Schema::create('chat_groups', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name', 30)->nullable();
            $table->foreignId('recruitment_id')->nullable()->constrained(); // 追加2023/09/19
            $table->unsignedSmallInteger('month')->nullable(); // 月を表すカラムの追加
            $table->unsignedSmallInteger('year')->nullable(); // 年を表すカラムの追加
            $table->timestamps();
            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_groups');
    }
};
