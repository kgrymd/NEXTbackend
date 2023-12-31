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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recruitment_id')->constrained('recruitments')->onDelete('cascade');
            $table->boolean('is_approved')->default(false);
            $table->dateTime('joined_at')->nullable();
            $table->timestamps();

            // user_idとrecruitment_idの組み合わせをユニーク制約として追加
            $table->unique(['user_id', 'recruitment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
