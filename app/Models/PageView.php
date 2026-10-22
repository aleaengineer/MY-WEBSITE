<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'visit_date',
        'path',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'views' => 'integer',
        ];
    }
}
