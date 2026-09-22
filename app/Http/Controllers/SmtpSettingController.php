<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSmtpSettingRequest;
use App\Models\SmtpSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SmtpSettingController extends Controller
{
    /**
     * Show the current SMTP configuration (or the "not configured" state).
     */
    public function show(): View
    {
        return view('settings.smtp.show', [
            'smtp' => SmtpSetting::current(),
        ]);
    }

    /**
     * Show the SMTP configuration form.
     */
    public function edit(): View
    {
        return view('settings.smtp.edit', [
            'smtp' => SmtpSetting::current(),
        ]);
    }

    /**
     * Create or update the single SMTP configuration row.
     */
    public function update(UpdateSmtpSettingRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $smtp = SmtpSetting::current();

        // Keep the existing password when the field is left blank on update.
        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($smtp) {
            $smtp->update($data);
        } else {
            SmtpSetting::create($data);
        }

        return redirect()->route('smtp.show')
            ->with('success', 'SMTP server configuration saved.');
    }
}
