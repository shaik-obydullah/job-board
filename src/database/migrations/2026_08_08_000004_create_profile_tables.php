<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('school');
            $table->string('degree')->nullable();
            $table->string('field_of_study')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

        Schema::create('employments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('job_title');
            $table->string('company_name');
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('issuing_organization')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('credential_id')->nullable();
            $table->string('credential_url')->nullable();
            $table->timestamps();
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('proficiency')->default('professional_working');
            $table->timestamps();
        });

        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('organization');
            $table->string('role')->nullable();
            $table->string('year')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('issuer')->nullable();
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('hobbies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('user_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('job_title')->nullable();
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('relationship')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_references');
        Schema::dropIfExists('hobbies');
        Schema::dropIfExists('awards');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('portfolios');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('employments');
        Schema::dropIfExists('educations');
    }
};
