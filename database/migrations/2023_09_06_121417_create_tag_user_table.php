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
        Schema::create('tag_user', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('tag_id')
                ->constrained('tags')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table
                ->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // $table->timestamps();

            $table->unique(['tag_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        // Schema::table('tag_user', function (Blueprint $table) {
        //     $table->dropForeign(['tag_id']);
        //     $table->dropForeign(['user_id']);
        // });

        // Schema::dropIfExists('tag_user');
        Schema::disableForeignKeyConstraints();  // 外部キー制約を無効にする

        Schema::table('tag_user', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('tag_user');

        Schema::enableForeignKeyConstraints();  // 外部キー制約を再度有効にする
    }
};
