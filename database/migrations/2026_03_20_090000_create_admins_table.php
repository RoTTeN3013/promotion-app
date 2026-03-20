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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->json('permissions')->nullable();
            $table->string('password');
            $table->timestamps();
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->foreign('created_by')->references('id')->on('admins')->cascadeOnUpdate()->restrictOnDelete();
        });

        Schema::table('exports', function (Blueprint $table) {
            $table->dropForeign(['exported_by']);
            $table->foreign('exported_by')->references('id')->on('admins')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exports', function (Blueprint $table) {
            $table->dropForeign(['exported_by']);
            $table->foreign('exported_by')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
        });

        Schema::dropIfExists('admins');
    }
};
