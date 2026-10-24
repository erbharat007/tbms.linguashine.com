<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\VendorApplication;
use App\Models\VendorInvoice;
use App\Models\VendorPayable;
use App\Models\VendorPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Render the role-tailored dashboard with relevant KPIs, quick info, and actions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function __invoke(Request $request): View
    {
        $user = Auth::user();
        $roleName = $user->role?->name ?? 'User';
        $isLocked = (bool) ($user->role?->is_locked ?? false);

        // Classify user role into distinct dashboard profiles
        if ($isLocked || str_contains(strtolower($roleName), 'admin')) {
            $dashboardType = 'admin';
            $roleLabel = 'Administrator';
            $roleBadgeClass = 'badge-primary';
            $roleIcon = 'fas fa-shield-alt';
        } elseif (str_contains(strtolower($roleName), 'project manager') || $user->is_manager) {
            $dashboardType = 'pm';
            $roleLabel = 'Project Manager';
            $roleBadgeClass = 'badge-info';
            $roleIcon = 'fas fa-tasks';
        } elseif (str_contains(strtolower($roleName), 'accountant') || str_contains(strtolower($roleName), 'finance')) {
            $dashboardType = 'accountant';
            $roleLabel = 'Accountant';
            $roleBadgeClass = 'badge-success';
            $roleIcon = 'fas fa-coins';
        } else {
            $dashboardType = 'general';
            $roleLabel = $roleName;
            $roleBadgeClass = 'badge-secondary';
            $roleIcon = 'fas fa-user';
        }

        // Global System Statistics
        $projectCount = Project::count();
        $inProgressProjectsCount = Project::where('status', 'in_progress')->count();
        $completedProjectsCount = Project::where('status', 'completed')->count();
        $draftProjectsCount = Project::where('status', 'draft')->count();
        $cancelledProjectsCount = Project::where('status', 'cancelled')->count();

        $clientCount = Client::count();
        $vendorCount = Vendor::count();
        $assignableVendorCount = Vendor::where('is_assignable', true)->count();
        $pendingApplicationsCount = VendorApplication::where('status', 'pending')->count();

        $totalReceivables = (float) Project::sum('total_to_receive');
        $totalPayables = (float) Project::sum('total_to_pay');
        $totalProfit = (float) Project::sum('profit');
        $avgProfitMargin = $totalReceivables > 0 ? round(($totalProfit / $totalReceivables) * 100, 1) : 0.0;

        $payableCount = VendorPayable::count();
        $payableTotal = (float) VendorPayable::sum('amount');
        $invoiceTotal = (float) VendorInvoice::sum('total');
        $paymentTotal = (float) VendorPayment::sum('amount');

        // Upcoming delivery deadlines across production
        $upcomingDeadlines = Project::with(['client', 'primaryManager'])
            ->where('status', 'in_progress')
            ->whereNotNull('deadline_at')
            ->orderBy('deadline_at', 'asc')
            ->take(5)
            ->get();

        $data = [
            'user' => $user,
            'roleName' => $roleName,
            'roleLabel' => $roleLabel,
            'roleBadgeClass' => $roleBadgeClass,
            'roleIcon' => $roleIcon,
            'dashboardType' => $dashboardType,
            'projectCount' => $projectCount,
            'inProgressProjectsCount' => $inProgressProjectsCount,
            'completedProjectsCount' => $completedProjectsCount,
            'draftProjectsCount' => $draftProjectsCount,
            'cancelledProjectsCount' => $cancelledProjectsCount,
            'clientCount' => $clientCount,
            'vendorCount' => $vendorCount,
            'assignableVendorCount' => $assignableVendorCount,
            'pendingApplicationsCount' => $pendingApplicationsCount,
            'totalReceivables' => $totalReceivables,
            'totalPayables' => $totalPayables,
            'totalProfit' => $totalProfit,
            'avgProfitMargin' => $avgProfitMargin,
            'payableCount' => $payableCount,
            'payableTotal' => $payableTotal,
            'invoiceTotal' => $invoiceTotal,
            'paymentTotal' => $paymentTotal,
            'upcomingDeadlines' => $upcomingDeadlines,
        ];

        // Specific datasets tailored per role
        if ($dashboardType === 'admin') {
            $data['recentProjects'] = Project::with(['client', 'primaryManager', 'tags'])
                ->latest('updated_at')
                ->take(6)
                ->get();
            $data['recentClients'] = Client::latest()->take(5)->get();
        } elseif ($dashboardType === 'pm') {
            // Projects assigned to this manager
            $myProjectsQuery = Project::where(function ($q) use ($user) {
                $q->where('primary_manager_id', $user->id)
                  ->orWhere('secondary_manager_id', $user->id);
            });

            $myProjectsCount = $myProjectsQuery->count();
            $myActiveProjectsCount = (clone $myProjectsQuery)->where('status', 'in_progress')->count();

            // If user has directly assigned projects, show them; otherwise fallback to active production
            $pmProjects = ($myProjectsCount > 0 ? (clone $myProjectsQuery) : Project::query())
                ->with(['client', 'primaryManager', 'tags'])
                ->whereIn('status', ['in_progress', 'draft'])
                ->orderBy('deadline_at', 'asc')
                ->take(7)
                ->get();

            $data['myProjectsCount'] = $myProjectsCount;
            $data['myActiveProjectsCount'] = $myActiveProjectsCount;
            $data['pmProjects'] = $pmProjects;
            $data['approachingDeadlinesCount'] = Project::where('status', 'in_progress')
                ->whereNotNull('deadline_at')
                ->where('deadline_at', '<=', now()->addDays(7))
                ->count();
        } elseif ($dashboardType === 'accountant') {
            $data['unpaidInvoices'] = VendorInvoice::with('vendor')
                ->where('status', '!=', 'paid')
                ->latest('due_date')
                ->take(6)
                ->get();
            $data['unpaidInvoicesCount'] = VendorInvoice::where('status', '!=', 'paid')->count();
            $data['unpaidInvoicesTotal'] = (float) VendorInvoice::where('status', '!=', 'paid')->sum('total');
            $data['recentPayments'] = VendorPayment::with(['vendor', 'paymentMethod'])
                ->latest('payment_date')
                ->take(5)
                ->get();
            $data['pendingPayables'] = VendorPayable::with('vendor')
                ->whereIn('status', ['pending', 'draft'])
                ->latest()
                ->take(5)
                ->get();
        } else {
            // General / Staff
            $data['generalProjects'] = Project::with(['client', 'primaryManager', 'tags'])
                ->where('status', 'in_progress')
                ->latest()
                ->take(6)
                ->get();
        }

        return view('dashboard', $data);
    }
}


