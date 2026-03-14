<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EmailDomainRule extends Model
{
    use HasFactory;

    protected $fillable = ['domain', 'role'];

    public function curricula(): BelongsToMany
    {
        return $this->belongsToMany(Curriculum::class, 'email_domain_rule_curriculum');
    }
}
