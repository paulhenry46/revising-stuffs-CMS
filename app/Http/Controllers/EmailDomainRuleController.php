<?php

namespace App\Http\Controllers;

use App\Models\EmailDomainRule;
use Illuminate\Http\Request;

class EmailDomainRuleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'domain'    => ['required', 'string', 'max:255', 'regex:/^@[\w.-]+\.[a-zA-Z]{2,}$/', 'unique:email_domain_rules,domain'],
            'role'      => ['required', 'in:contributor,co-admin'],
            'curricula' => ['array'],
            'curricula.*' => ['exists:curricula,id'],
        ]);

        $rule = EmailDomainRule::create([
            'domain' => $request->domain,
            'role'   => $request->role,
        ]);

        if ($request->role === 'co-admin') {
            $rule->curricula()->sync($request->input('curricula', []));
        }

        cache()->forget('contributor_domain_rules');

        return redirect()->route('settings')->with('message', __('The email domain rule has been created.'));
    }

    public function update(Request $request, EmailDomainRule $emailDomainRule)
    {
        $request->validate([
            'domain'    => ['required', 'string', 'max:255', 'regex:/^@[\w.-]+\.[a-zA-Z]{2,}$/', 'unique:email_domain_rules,domain,' . $emailDomainRule->id],
            'role'      => ['required', 'in:contributor,co-admin'],
            'curricula' => ['array'],
            'curricula.*' => ['exists:curricula,id'],
        ]);

        $emailDomainRule->update([
            'domain' => $request->domain,
            'role'   => $request->role,
        ]);

        if ($request->role === 'co-admin') {
            $emailDomainRule->curricula()->sync($request->input('curricula', []));
        } else {
            $emailDomainRule->curricula()->detach();
        }

        cache()->forget('contributor_domain_rules');

        return redirect()->route('settings')->with('message', __('The email domain rule has been updated.'));
    }

    public function destroy(EmailDomainRule $emailDomainRule)
    {
        $emailDomainRule->delete();

        cache()->forget('contributor_domain_rules');

        return redirect()->route('settings')->with('message', __('The email domain rule has been deleted.'));
    }
}
