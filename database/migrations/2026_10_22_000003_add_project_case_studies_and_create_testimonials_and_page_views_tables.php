<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('category', 80)->nullable()->index();
            $table->string('role')->nullable();
            $table->text('problem')->nullable();
            $table->longText('solution')->nullable();
            $table->text('results')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 300)->nullable();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('role', 120)->nullable();
            $table->string('company', 120)->nullable();
            $table->text('quote');
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_published', 'sort_order']);
        });

        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->date('visit_date');
            $table->string('path', 500);
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();

            $table->unique(['visit_date', 'path']);
            $table->index('visit_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
        Schema::dropIfExists('testimonials');

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropColumn([
                'category',
                'role',
                'problem',
                'solution',
                'results',
                'seo_title',
                'seo_description',
            ]);
        });
    }
};
