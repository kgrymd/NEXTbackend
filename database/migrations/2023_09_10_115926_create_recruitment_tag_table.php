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
        Schema::create('recruitment_tag', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('recruitment_id')
                ->constrained('recruitments')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table
                ->foreignId('tag_id')
                ->constrained('tags')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // $table->timestamps();

            $table->unique(['recruitment_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitment_tag');
    }
};
