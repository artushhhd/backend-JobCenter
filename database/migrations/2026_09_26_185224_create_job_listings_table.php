<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->string('company');
            $table->string('department')->nullable();
            $table->string('category')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'internship'])->default('full_time');
            $table->enum('experience_level', ['entry', 'mid', 'senior', 'lead'])->default('mid');

            $table->text('description')->nullable();
            $table->json('responsibilities')->nullable();
            $table->json('requirements')->nullable();
            $table->json('skills')->nullable();

            $table->unsignedInteger('salary_min')->nullable();
            $table->unsignedInteger('salary_max')->nullable();
            $table->enum('pay_period', ['hourly', 'daily', 'weekly', 'monthly', 'annual'])->default('annual');
            $table->boolean('show_salary_range')->default(true);

            $table->enum('work_mode', ['onsite', 'hybrid', 'remote'])->default('hybrid');
            $table->string('location')->nullable();
            $table->string('location_note')->nullable();

            $table->enum('application_method', ['email', 'link', 'platform'])->default('platform');
            $table->date('deadline')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('featured')->default(false);
            $table->boolean('publish_to_marketplace')->default(true);
            $table->boolean('notify_matching_candidates')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index(['status', 'featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
