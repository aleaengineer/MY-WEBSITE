<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioProfile extends Model
{
    public const DEFAULT_HERO_DESCRIPTION = 'NOC Engineer & Network Administrator yang membangun aplikasi web untuk efisiensi operasional ISP.';

    protected $fillable = [
        'hero_description',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            ['hero_description' => self::DEFAULT_HERO_DESCRIPTION],
        );
    }
}
