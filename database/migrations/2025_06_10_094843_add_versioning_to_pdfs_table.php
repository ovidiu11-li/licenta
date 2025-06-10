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
        Schema::table('pdfs', function (Blueprint $table) {
            $table->integer('version')->default(1)->after('stored_name');
            $table->foreignId('parent_pdf_id')->nullable()->after('version')->constrained('pdfs')->onDelete('cascade');
            $table->boolean('is_current')->default(true)->after('parent_pdf_id');
            $table->index(['user_id', 'is_current']);
            $table->index(['parent_pdf_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pdfs', function (Blueprint $table) {
            $table->dropForeign(['parent_pdf_id']);
            $table->dropIndex(['user_id', 'is_current']);
            $table->dropIndex(['parent_pdf_id']);
            $table->dropColumn(['version', 'parent_pdf_id', 'is_current']);
        });
    }
};
