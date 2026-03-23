<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('answers')) {
            Schema::create('answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('admin_id')->constrained('admins')->cascadeOnUpdate()->cascadeOnDelete();
                $table->foreignId('contact_message_id')->constrained('contact_messages')->cascadeOnUpdate()->cascadeOnDelete();
                $table->text('message');
                $table->timestamps();
            });
        }

        $foreignKeyExists = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'contact_messages')
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->where('CONSTRAINT_NAME', 'contact_messages_answer_id_foreign')
            ->exists();

        if (!$foreignKeyExists) {
            Schema::table('contact_messages', function (Blueprint $table) {
                $table->foreign('answer_id')->references('id')->on('answers')->nullOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
