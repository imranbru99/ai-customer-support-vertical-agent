<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->boolean('enabled')->default(true);
            $table->boolean('is_core')->default(false);
            $table->unsignedInteger('hours_saved_weekly')->default(0);
            $table->timestamps();
        });

        Schema::create('industry_verticals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sector')->nullable();
            $table->string('tone')->default('professional');
            $table->unsignedInteger('sla_minutes')->default(240);
            $table->decimal('deflection_target', 5, 2)->default(0.55);
            $table->string('knowledge_pack')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('industry_vertical_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('requester_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject');
            $table->text('body')->nullable();
            $table->string('channel')->default('email');
            $table->string('priority')->default('medium');
            $table->string('status')->default('new');
            $table->string('intent')->nullable();
            $table->decimal('ai_score', 5, 2)->default(0);
            $table->text('ai_summary')->nullable();
            $table->json('tags')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->unsignedInteger('first_response_minutes')->nullable();
            $table->timestamp('escalated_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('knowledge_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('industry_vertical_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->string('locale')->default('en');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('helpful_votes')->default(0);
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        Schema::create('escalation_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('industry_vertical_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('trigger_intent')->nullable();
            $table->string('min_priority')->default('high');
            $table->decimal('ai_score_threshold', 5, 2)->default(0.75);
            $table->string('target_queue')->default('tier-2');
            $table->string('notify_role')->default('manager');
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_assign')->default(true);
            $table->text('instructions')->nullable();
            $table->timestamps();
        });

        Schema::create('csat_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('score');
            $table->text('comment')->nullable();
            $table->string('channel')->default('email');
            $table->boolean('recovery_needed')->default(false);
            $table->timestamp('recovered_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('macros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('industry_vertical_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('shortcut')->nullable();
            $table->string('subject')->nullable();
            $table->text('body');
            $table->json('variables')->nullable();
            $table->unsignedInteger('usage_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ai_answer_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('knowledge_article_id')->nullable()->constrained()->nullOnDelete();
            $table->text('draft_reply');
            $table->decimal('confidence', 5, 2)->default(0.5);
            $table->string('model_provider')->default('laravel-ai-hub');
            $table->string('status')->default('suggested');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_answer_suggestions');
        Schema::dropIfExists('macros');
        Schema::dropIfExists('csat_surveys');
        Schema::dropIfExists('escalation_rules');
        Schema::dropIfExists('knowledge_articles');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('industry_verticals');
        Schema::dropIfExists('module_settings');
    }
};
