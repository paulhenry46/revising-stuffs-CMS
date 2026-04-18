<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfExcerpt extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'toc' => 'array',
    ];

    protected function asJson($value, $flags = 0)
    {
        return json_encode($value, $flags | JSON_UNESCAPED_UNICODE);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
