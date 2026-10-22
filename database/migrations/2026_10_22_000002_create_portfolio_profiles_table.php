<?php

use App\Models\PortfolioProfile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_profiles', function (Blueprint $table) {
            $table->id();
            $table->text('hero_description');
            $table->timestamps();
        });

        DB::table('portfolio_profiles')->insert([
            'id' => 1,
            'hero_description' => PortfolioProfile::DEFAULT_HERO_DESCRIPTION,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_profiles');
    }
};
