<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoAdminLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'subject_label',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
