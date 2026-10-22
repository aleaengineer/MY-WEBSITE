<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->dropUnique('page_views_visit_date_path_unique');
            $table->string('path', 500)->change();
            $table->unique(['visit_date', 'path']);
        });
    }

    public function down(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->dropUnique('page_views_visit_date_path_unique');
            $table->string('path', 255)->change();
            $table->unique(['visit_date', 'path']);
        });
    }
};
