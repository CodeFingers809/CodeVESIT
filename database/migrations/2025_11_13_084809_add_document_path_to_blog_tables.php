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
        Schema::table('blog_requests', function (Blueprint $table) {
            $table->string('document_path')->nullable()->after('content');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->string('document_path')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_requests', function (Blueprint $table) {
            $table->dropColumn('document_path');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('document_path');
        });
    }
};
