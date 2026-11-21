<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table): void {
            $table->id(); $table->foreignId('parent_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->string('title'); $table->string('slug')->unique(); $table->longText('body')->nullable();
            $table->string('status', 24)->default('draft')->index(); $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('show_in_menu')->default(false); $table->timestamps(); $table->softDeletes();
        });
        Schema::create('menu_items', function (Blueprint $table): void {
            $table->id(); $table->foreignId('parent_id')->nullable()->constrained('menu_items')->nullOnDelete();
            $table->string('title'); $table->string('url', 2048)->nullable();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0); $table->boolean('is_active')->default(true); $table->timestamps();
        });
        Schema::create('news', function (Blueprint $table): void {
            $table->id(); $table->string('title'); $table->string('slug')->unique(); $table->text('excerpt')->nullable();
            $table->longText('body'); $table->string('status', 24)->default('draft')->index(); $table->timestamp('published_at')->nullable()->index();
            $table->string('cover_path')->nullable(); $table->timestamps(); $table->softDeletes();
        });
        Schema::create('text_blocks', function (Blueprint $table): void {
            $table->id(); $table->string('code')->unique(); $table->string('title'); $table->longText('body')->nullable();
            $table->boolean('is_active')->default(true); $table->timestamps();
        });
        Schema::create('banners', function (Blueprint $table): void {
            $table->id(); $table->string('title'); $table->string('image_path'); $table->string('target_url', 2048)->nullable();
            $table->string('placement', 64)->default('home'); $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true); $table->timestamp('starts_at')->nullable(); $table->timestamp('ends_at')->nullable(); $table->timestamps();
        });
        Schema::create('faqs', function (Blueprint $table): void {
            $table->id(); $table->string('question'); $table->longText('answer')->nullable(); $table->string('author_name')->nullable();
            $table->string('author_email')->nullable(); $table->string('status', 24)->default('pending')->index(); $table->timestamps();
        });
        Schema::create('polls', function (Blueprint $table): void {
            $table->id(); $table->string('title'); $table->text('question'); $table->string('status', 24)->default('draft')->index();
            $table->timestamp('starts_at')->nullable(); $table->timestamp('ends_at')->nullable(); $table->timestamps();
        });
        Schema::create('poll_options', function (Blueprint $table): void {
            $table->id(); $table->foreignId('poll_id')->constrained()->cascadeOnDelete(); $table->string('title');
            $table->unsignedInteger('sort_order')->default(0); $table->timestamps();
        });
        Schema::create('poll_votes', function (Blueprint $table): void {
            $table->id(); $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('poll_option_id')->constrained()->cascadeOnDelete(); $table->string('voter_key', 64);
            $table->timestamp('created_at')->useCurrent(); $table->unique(['poll_id', 'voter_key']);
        });
        Schema::create('receptions', function (Blueprint $table): void {
            $table->id(); $table->string('reference')->unique(); $table->string('subject'); $table->string('full_name');
            $table->string('email'); $table->string('phone')->nullable(); $table->longText('message');
            $table->string('status', 24)->default('received')->index(); $table->text('staff_note')->nullable();
            $table->timestamp('processed_at')->nullable(); $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['receptions','poll_votes','poll_options','polls','faqs','banners','text_blocks','news','menu_items','pages'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
