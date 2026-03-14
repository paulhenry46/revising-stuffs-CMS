<?php

namespace App\Listeners;

use App\Models\EmailDomainRule;
use Illuminate\Auth\Events\Verified;

class ApplyEmailDomainRules
{
    public function handle(Verified $event): void
    {
        $user = $event->user;

        $email = $user->email;
        $domain = '@' . substr($email, strrpos($email, '@') + 1);

        $rule = EmailDomainRule::where('domain', $domain)->first();

        if (!$rule) {
            return;
        }

        if ($rule->role === 'contributor') {
            if (!$user->hasRole('contributor')) {
                $user->syncRoles(['student', 'contributor']);
            }
        } elseif ($rule->role === 'co-admin') {
            if (!$user->hasRole('co-admin') && !$user->hasRole('admin')) {
                $user->syncRoles(['co-admin', 'student', 'contributor']);
                $curriculaIds = $rule->curricula->pluck('id')->toArray();
                if (!empty($curriculaIds)) {
                    $user->managedCurricula()->syncWithoutDetaching($curriculaIds);
                }
            }
        }
    }
}
