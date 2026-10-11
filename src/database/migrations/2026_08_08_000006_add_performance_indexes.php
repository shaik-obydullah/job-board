<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->index('is_featured');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('is_verified');
        });

        Schema::table('job_categories', function (Blueprint $table) {
            $table->index('is_active');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('type');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->index(['job_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['job_id', 'status']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['type']);
        });

        Schema::table('job_categories', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_verified']);
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex(['is_featured']);
        });
    }
};
