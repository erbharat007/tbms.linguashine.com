<?php

namespace App\Http\Controllers;

use App\Models\ReminderSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReminderSettingController extends Controller
{
    /**
     * Display the reminders settings.
     */
    public function index(): View
    {
        $reminders = ReminderSetting::current();

        return view('settings.reminders.index', compact('reminders'));
    }

    /**
     * Update the reminder settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_deadline' => 'nullable|boolean',
            'job_deadline' => 'nullable|boolean',
            'client_invoice_due' => 'nullable|boolean',
            'vendor_invoice_due' => 'nullable|boolean',
            'quote_expiration' => 'nullable|boolean',
            'project_deadline_days' => 'required|integer|min:1|max:30',
            'job_deadline_days' => 'required|integer|min:1|max:30',
            'client_invoice_days' => 'required|integer|min:1|max:60',
            'vendor_invoice_days' => 'required|integer|min:1|max:60',
            'quote_expiration_days' => 'required|integer|min:1|max:30',
        ]);

        $validated['project_deadline'] = $request->boolean('project_deadline');
        $validated['job_deadline'] = $request->boolean('job_deadline');
        $validated['client_invoice_due'] = $request->boolean('client_invoice_due');
        $validated['vendor_invoice_due'] = $request->boolean('vendor_invoice_due');
        $validated['quote_expiration'] = $request->boolean('quote_expiration');

        $reminders = ReminderSetting::current();
        $reminders->update($validated);

        return redirect()->route('reminders.index')->with('status', 'Reminder settings updated successfully.');
    }
}
