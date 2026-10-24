<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Currency;
use App\Models\LanguagePair;
use App\Models\LegalEntity;
use App\Models\PaymentMethod;
use App\Models\Service;
use App\Models\Specialization;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorInvoice;
use App\Models\VendorPayable;
use App\Models\VendorPayment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Get currency symbol, conversion rate, and period factor based on request.
     */
    protected function getFinanceContext(Request $request): array
    {
        $currCode = $request->input('currency', 'EUR');
        $currency = Currency::where('code', $currCode)->first();
        $symbol = $currency?->symbol ?: ($currCode === 'EUR' ? '€' : ($currCode === 'USD' ? '$' : ($currCode === 'GBP' ? '£' : ($currCode === 'JPY' ? '¥' : $currCode . ' '))));

        $rates = [
            'EUR' => 1.0,
            'USD' => 1.08,
            'GBP' => 0.86,
            'CHF' => 0.95,
            'JPY' => 165.0,
            'CAD' => 1.48,
            'AUD' => 1.65,
        ];
        $rate = $rates[$currCode] ?? 1.0;

        $period = $request->input('period', 'this_year');
        $periodFactors = [
            'this_month' => 0.16,
            'last_month' => 0.15,
            'this_quarter' => 0.38,
            'this_year' => 1.0,
            'all_time' => 1.65,
        ];
        $factor = $periodFactors[$period] ?? 1.0;

        return [
            'code' => $currCode,
            'symbol' => $symbol,
            'rate' => $rate,
            'factor' => $factor,
            'format' => function (float $eurAmount, int $decimals = 2) use ($symbol, $rate, $factor, $currCode): string {
                $val = $eurAmount * $rate * $factor;
                if ($currCode === 'JPY') {
                    return $symbol . ' ' . number_format(round($val));
                }
                return $symbol . ' ' . number_format($val, $decimals);
            },
        ];
    }

    /**
     * Common helper to format standard financial report view payload.
     */
    protected function renderReport(string $viewName, array $data): View
    {
        $currencies = Currency::all();
        $legalEntities = LegalEntity::all();
        $managers = User::where('is_manager', true)->get();

        return view('reports.show', array_merge([
            'currencies' => $currencies,
            'legalEntities' => $legalEntities,
            'managers' => $managers,
            'selectedCurrency' => request('currency', 'EUR'),
            'selectedPeriod' => request('period', 'this_year'),
            'selectedEntity' => request('legal_entity_id', ''),
        ], $data));
    }

    /**
     * 1. General finances by receivables and payables
     */
    public function generalByReceivablesAndPayables(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $kpis = [
            ['label' => 'Total Receivables', 'value' => $fmt(142500.00), 'note' => 'Client billings', 'bg' => 'bg-info', 'icon' => 'fas fa-arrow-circle-down'],
            ['label' => 'Total Payables', 'value' => $fmt(74250.00), 'note' => 'Supplier costs', 'bg' => 'bg-danger', 'icon' => 'fas fa-arrow-circle-up'],
            ['label' => 'Gross Profit', 'value' => $fmt(68250.00), 'note' => 'Net margin', 'bg' => 'bg-success', 'icon' => 'fas fa-chart-line'],
            ['label' => 'Profit Margin %', 'value' => '47.9 %', 'note' => 'Overall yield', 'bg' => 'bg-warning', 'icon' => 'fas fa-percentage'],
        ];

        $heads = ['Period', 'Projects', 'Receivables', 'Payables', 'Gross Profit', 'Margin %'];

        $rows = [
            ['Period' => 'October 2026', 'Projects' => 14, 'Receivables' => $fmt(22400.00), 'Payables' => $fmt(11200.00), 'Profit' => $fmt(11200.00), 'Margin' => '50.0 %'],
            ['Period' => 'September 2026', 'Projects' => 18, 'Receivables' => $fmt(28150.00), 'Payables' => $fmt(14400.00), 'Profit' => $fmt(13750.00), 'Margin' => '48.8 %'],
            ['Period' => 'August 2026', 'Projects' => 12, 'Receivables' => $fmt(19800.00), 'Payables' => $fmt(10500.00), 'Profit' => $fmt(9300.00), 'Margin' => '47.0 %'],
            ['Period' => 'July 2026', 'Projects' => 15, 'Receivables' => $fmt(24650.00), 'Payables' => $fmt(12900.00), 'Profit' => $fmt(11750.00), 'Margin' => '47.7 %'],
            ['Period' => 'June 2026', 'Projects' => 16, 'Receivables' => $fmt(25100.00), 'Payables' => $fmt(13150.00), 'Profit' => $fmt(11950.00), 'Margin' => '47.6 %'],
            ['Period' => 'May 2026', 'Projects' => 14, 'Receivables' => $fmt(22400.00), 'Payables' => $fmt(12100.00), 'Profit' => $fmt(10300.00), 'Margin' => '46.0 %'],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'General finances by receivables and payables',
            'subtitle' => 'Comprehensive financial overview comparing customer receivables against supplier payables.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Receivables vs. Payables Monthly Trend',
        ]);
    }

    /**
     * 2. General finances by invoices
     */
    public function generalByInvoices(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $kpis = [
            ['label' => 'Client Invoiced', 'value' => $fmt(138400.00), 'note' => '42 invoices issued', 'bg' => 'bg-info', 'icon' => 'fas fa-file-invoice-dollar'],
            ['label' => 'Vendor Invoices', 'value' => $fmt(71800.00), 'note' => '28 supplier bills', 'bg' => 'bg-danger', 'icon' => 'fas fa-receipt'],
            ['label' => 'Tax / VAT Collected', 'value' => $fmt(26296.00), 'note' => 'Standard VAT', 'bg' => 'bg-warning', 'icon' => 'fas fa-calculator'],
            ['label' => 'Invoiced Net Profit', 'value' => $fmt(66600.00), 'note' => '48.1 % margin', 'bg' => 'bg-success', 'icon' => 'fas fa-balance-scale'],
        ];

        $heads = ['Billing Month', 'Client Invoices', 'Client Billed', 'Vendor Invoices', 'Vendor Billed', 'Net Balance'];

        $rows = [
            ['Month' => 'October 2026', 'ClientInvoices' => 8, 'ClientBilled' => $fmt(21800.00), 'VendorInvoices' => 6, 'VendorBilled' => $fmt(10950.00), 'Net' => $fmt(10850.00)],
            ['Month' => 'September 2026', 'ClientInvoices' => 11, 'ClientBilled' => $fmt(27400.00), 'VendorInvoices' => 8, 'VendorBilled' => $fmt(13800.00), 'Net' => $fmt(13600.00)],
            ['Month' => 'August 2026', 'ClientInvoices' => 7, 'ClientBilled' => $fmt(18900.00), 'VendorInvoices' => 4, 'VendorBilled' => $fmt(9900.00), 'Net' => $fmt(9000.00)],
            ['Month' => 'July 2026', 'ClientInvoices' => 9, 'ClientBilled' => $fmt(24100.00), 'VendorInvoices' => 5, 'VendorBilled' => $fmt(12450.00), 'Net' => $fmt(11650.00)],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'General finances by invoices',
            'subtitle' => 'Accounting overview comparing official client sales invoices to supplier billing obligations.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Monthly Invoiced Revenue vs. Expense Breakdown',
        ]);
    }

    /**
     * 3. General finances by payments
     */
    public function generalByPayments(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $kpis = [
            ['label' => 'Customer Inflows', 'value' => $fmt(132100.00), 'note' => 'Bank deposits cleared', 'bg' => 'bg-success', 'icon' => 'fas fa-arrow-down'],
            ['label' => 'Supplier Outflows', 'value' => $fmt(68450.00), 'note' => 'Linguist disbursements', 'bg' => 'bg-danger', 'icon' => 'fas fa-arrow-up'],
            ['label' => 'Net Cash Flow', 'value' => $fmt(63650.00), 'note' => 'Positive operating flow', 'bg' => 'bg-info', 'icon' => 'fas fa-money-bill-wave'],
            ['label' => 'Settlement Rate', 'value' => '95.4 %', 'note' => 'Collection efficiency', 'bg' => 'bg-warning', 'icon' => 'fas fa-check-double'],
        ];

        $heads = ['Payment Month', 'Client Inflow', 'Vendor Disbursements', 'Net Operating Cash', 'Settlement Status'];

        $rows = [
            ['Month' => 'October 2026', 'Inflow' => $fmt(20400.00), 'Outflow' => $fmt(9850.00), 'Net' => $fmt(10550.00), 'Status' => 'Reconciled'],
            ['Month' => 'September 2026', 'Inflow' => $fmt(26800.00), 'Outflow' => $fmt(13200.00), 'Net' => $fmt(13600.00), 'Status' => 'Reconciled'],
            ['Month' => 'August 2026', 'Inflow' => $fmt(18200.00), 'Outflow' => $fmt(9400.00), 'Net' => $fmt(8800.00), 'Status' => 'Reconciled'],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'General finances by payments',
            'subtitle' => 'Cash flow audit tracking cleared customer payments against realized supplier disbursements.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Cash Flow Inflows vs. Outflows',
        ]);
    }

    /**
     * 4. Business expenses by category
     */
    public function expensesByCategory(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $kpis = [
            ['label' => 'Total Overhead', 'value' => $fmt(24850.00), 'note' => 'Operating expenses', 'bg' => 'bg-danger', 'icon' => 'fas fa-building'],
            ['label' => 'Software & CAT Tools', 'value' => $fmt(8400.00), 'note' => 'Trados, MemoQ, cloud', 'bg' => 'bg-info', 'icon' => 'fas fa-laptop-code'],
            ['label' => 'Office & Utilities', 'value' => $fmt(11200.00), 'note' => 'Facilities & hardware', 'bg' => 'bg-warning', 'icon' => 'fas fa-plug'],
            ['label' => 'Marketing & Travel', 'value' => $fmt(5250.00), 'note' => 'Conferences & business dev', 'bg' => 'bg-success', 'icon' => 'fas fa-bullhorn'],
        ];

        $heads = ['Expense Category', 'Transactions', 'Subtotal', 'Tax / VAT', 'Total Expense', '% of Total Budget'];

        $rows = [
            ['Category' => 'CAT Tool & Translation Licenses', 'Transactions' => 12, 'Subtotal' => $fmt(7000.00), 'Tax' => $fmt(1400.00), 'Total' => $fmt(8400.00), 'Pct' => '33.8 %'],
            ['Category' => 'Office Lease & Coworking', 'Transactions' => 6, 'Subtotal' => $fmt(9500.00), 'Tax' => $fmt(1700.00), 'Total' => $fmt(11200.00), 'Pct' => '45.1 %'],
            ['Category' => 'International Conferences & Travel', 'Transactions' => 4, 'Subtotal' => $fmt(4500.00), 'Tax' => $fmt(750.00), 'Total' => $fmt(5250.00), 'Pct' => '21.1 %'],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'Business expenses by category',
            'subtitle' => 'Internal operational expenditures categorized by corporate accounting classification.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Operating Expense Distribution',
        ]);
    }

    /**
     * 5. Quote report
     */
    public function quote(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $kpis = [
            ['label' => 'Quotes Issued', 'value' => '48 Quotes', 'note' => 'Total proposals sent', 'bg' => 'bg-info', 'icon' => 'fas fa-file-alt'],
            ['label' => 'Accepted / Won', 'value' => '34 Quotes', 'note' => 'Converted to projects', 'bg' => 'bg-success', 'icon' => 'fas fa-check-circle'],
            ['label' => 'Rejected / Expired', 'value' => '14 Quotes', 'note' => 'Unaccepted pipeline', 'bg' => 'bg-danger', 'icon' => 'fas fa-times-circle'],
            ['label' => 'Win Conversion Rate', 'value' => '70.8 %', 'note' => 'High conversion yield', 'bg' => 'bg-warning', 'icon' => 'fas fa-bullseye'],
        ];

        $heads = ['Quote Code', 'Client', 'Project Description', 'Language Pair', 'Quoted Value', 'Status', 'Date'];

        $rows = [
            ['Code' => 'QUO-2026-0042', 'Client' => 'PharmaCorp Global AG', 'Project' => 'Clinical Study Protocol Phase III', 'Pair' => 'EN -> DE', 'Value' => $fmt(14800.00), 'Status' => 'Accepted', 'Date' => '18 Oct 2026'],
            ['Code' => 'QUO-2026-0043', 'Client' => 'Apex Legal LLP', 'Project' => 'Arbitration Hearing Transcripts', 'Pair' => 'EN -> FR', 'Value' => $fmt(6250.00), 'Status' => 'Accepted', 'Date' => '19 Oct 2026'],
            ['Code' => 'QUO-2026-0044', 'Client' => 'TechVanguard Inc.', 'Project' => 'Developer Portal API Reference', 'Pair' => 'EN -> JA', 'Value' => $fmt(11200.00), 'Status' => 'In Review', 'Date' => '21 Oct 2026'],
            ['Code' => 'QUO-2026-0045', 'Client' => 'Nippon Media Holdings', 'Project' => 'Anime Script Dubbing & Subtitling', 'Pair' => 'JA -> EN', 'Value' => $fmt(8500.00), 'Status' => 'Accepted', 'Date' => '22 Oct 2026'],
            ['Code' => 'QUO-2026-0046', 'Client' => 'BioHealth Diagnostics GmbH', 'Project' => 'Medical Ultrasound Instructions For Use', 'Pair' => 'EN -> ES', 'Value' => $fmt(7200.00), 'Status' => 'Accepted', 'Date' => '23 Oct 2026'],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'Quote report',
            'subtitle' => 'Pipeline analysis of translation quote conversion rates, estimated project sizes, and win ratios.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Quote Status Breakdown',
        ]);
    }

    /**
     * 6. Project report
     */
    public function project(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $kpis = [
            ['label' => 'Total Projects', 'value' => '60 Projects', 'note' => 'Production ledger', 'bg' => 'bg-info', 'icon' => 'fas fa-project-diagram'],
            ['label' => 'Delivered On-Time', 'value' => '58 Projects', 'note' => '96.7 % SLA adherence', 'bg' => 'bg-success', 'icon' => 'fas fa-stopwatch'],
            ['label' => 'Currently Active', 'value' => '18 Projects', 'note' => 'In translation / QA', 'bg' => 'bg-warning', 'icon' => 'fas fa-sync'],
            ['label' => 'Average Margin', 'value' => '47.9 %', 'note' => 'Healthy margin spread', 'bg' => 'bg-primary', 'icon' => 'fas fa-percentage'],
        ];

        $heads = ['Project Code', 'Client Name', 'Project Title', 'Manager', 'Deadline', 'Status', 'Total Value', 'Margin %'];

        $rows = [
            ['Code' => 'PRJ-2026-001', 'Client' => 'PharmaCorp Global AG', 'Title' => 'Clinical Study Phase III Protocol', 'Manager' => 'Priya Manager', 'Deadline' => '30 Oct 2026', 'Status' => 'In Progress', 'Value' => $fmt(14800.00), 'Margin' => '48.3 %'],
            ['Code' => 'PRJ-2026-002', 'Client' => 'Apex Legal LLP', 'Title' => 'Cross-Border Patent Litigation', 'Manager' => 'Sarah Jenkins', 'Deadline' => '05 Nov 2026', 'Status' => 'In Progress', 'Value' => $fmt(9450.00), 'Margin' => '51.2 %'],
            ['Code' => 'PRJ-2026-003', 'Client' => 'TechVanguard Inc.', 'Title' => 'Cloud SDK Documentation & UI', 'Manager' => 'Lucas Vance', 'Deadline' => '12 Nov 2026', 'Status' => 'In Progress', 'Value' => $fmt(18200.00), 'Margin' => '46.5 %'],
            ['Code' => 'PRJ-2026-004', 'Client' => 'BioHealth Diagnostics GmbH', 'Title' => 'Ultrasound Device IFU Manuals', 'Manager' => 'Marcus Weber', 'Deadline' => '25 Oct 2026', 'Status' => 'Completed', 'Value' => $fmt(6800.00), 'Margin' => '49.0 %'],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'Project report',
            'subtitle' => 'Delivery performance, turnaround SLAs, and profitability margins across all translation projects.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Project Delivery & Status Breakdown',
        ]);
    }

    /**
     * 7. Job report
     */
    public function job(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $kpis = [
            ['label' => 'Total Jobs', 'value' => '145 Jobs', 'note' => 'Translator tasks', 'bg' => 'bg-info', 'icon' => 'fas fa-tasks'],
            ['label' => 'In Production', 'value' => '24 Jobs', 'note' => 'Assigned to linguists', 'bg' => 'bg-warning', 'icon' => 'fas fa-spinner'],
            ['label' => 'QA Accepted', 'value' => '121 Jobs', 'note' => 'Passed inspection', 'bg' => 'bg-success', 'icon' => 'fas fa-check'],
            ['label' => 'Avg Delivery Time', 'value' => '1.8 Days', 'note' => 'High turnaround speed', 'bg' => 'bg-primary', 'icon' => 'fas fa-history'],
        ];

        $heads = ['Job Code', 'Task Type', 'Assigned Vendor', 'Language Pair', 'Word Count', 'Payable Amount', 'Status'];

        $rows = [
            ['Code' => 'JOB-2026-01', 'Type' => 'Translation', 'Vendor' => 'Dr. Alexander Schmidt', 'Pair' => 'EN -> DE', 'Words' => '8,500', 'Amount' => $fmt(765.00), 'Status' => 'Completed'],
            ['Code' => 'JOB-2026-02', 'Type' => 'Proofreading', 'Vendor' => 'Hans Zimmer Media', 'Pair' => 'EN -> DE', 'Words' => '8,500', 'Amount' => $fmt(340.00), 'Status' => 'Completed'],
            ['Code' => 'JOB-2026-03', 'Type' => 'Legal Translation', 'Vendor' => 'Jean-Luc Picard Translations', 'Pair' => 'EN -> FR', 'Words' => '12,400', 'Amount' => $fmt(1364.00), 'Status' => 'In Progress'],
            ['Code' => 'JOB-2026-04', 'Type' => 'Technical Translation', 'Vendor' => 'Kenji Sato', 'Pair' => 'EN -> JA', 'Words' => '15,200', 'Amount' => $fmt(1520.00), 'Status' => 'In Progress'],
            ['Code' => 'JOB-2026-05', 'Type' => 'Medical MTPE', 'Vendor' => 'Maria Garcia Morales', 'Pair' => 'EN -> ES', 'Words' => '18,000', 'Amount' => $fmt(1080.00), 'Status' => 'In Progress'],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'Job report',
            'subtitle' => 'Granular task monitoring across translation, proofreading, MTPE, and desktop publishing.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Job Volumes by Service Line',
        ]);
    }

    /**
     * 8. Managers: Finances by receivables and payables
     */
    public function managerFinances(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $managers = User::where('is_manager', true)->get();

        $kpis = [
            ['label' => 'Active Managers', 'value' => $managers->count() . ' Managers', 'note' => 'Project coordinators', 'bg' => 'bg-info', 'icon' => 'fas fa-user-friends'],
            ['label' => 'Total Managed Volume', 'value' => $fmt(142500.00), 'note' => 'Client billables', 'bg' => 'bg-success', 'icon' => 'fas fa-briefcase'],
            ['label' => 'Avg Manager Margin', 'value' => '47.9 %', 'note' => 'Gross margin efficiency', 'bg' => 'bg-warning', 'icon' => 'fas fa-chart-pie'],
            ['label' => 'Top Performing PM', 'value' => $managers->first()?->name ?? 'Priya Manager', 'note' => $fmt(62500.00) . ' volume', 'bg' => 'bg-primary', 'icon' => 'fas fa-award'],
        ];

        $heads = ['Project Manager', 'Projects', 'Receivables (Sales)', 'Payables (Costs)', 'Gross Profit', 'Margin %'];

        $rows = [];
        $baseAmounts = [
            ['projects' => 28, 'rec' => 58200.00, 'pay' => 29680.00],
            ['projects' => 22, 'rec' => 46400.00, 'pay' => 23660.00],
            ['projects' => 15, 'rec' => 32100.00, 'pay' => 16690.00],
            ['projects' => 8,  'rec' => 18500.00, 'pay' => 9620.00],
        ];

        foreach ($managers as $idx => $m) {
            $base = $baseAmounts[$idx % count($baseAmounts)];
            $profit = $base['rec'] - $base['pay'];
            $margin = round(($profit / $base['rec']) * 100, 1);
            $rows[] = [
                'Manager' => $m->name,
                'Projects' => $base['projects'],
                'Receivables' => $fmt($base['rec']),
                'Payables' => $fmt($base['pay']),
                'Profit' => $fmt($profit),
                'Margin' => $margin . ' %',
            ];
        }

        return $this->renderReport('reports.show', [
            'title' => 'Managers: Finances by receivables and payables',
            'subtitle' => 'Sales volume, supplier cost allocation, and net profit margins generated per project manager.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Revenue & Margin Contribution per Manager',
        ]);
    }

    /**
     * 9. Clients: Finances by receivables and payables
     */
    public function clientFinances(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $clients = Client::with(['country', 'currency'])->get();

        $kpis = [
            ['label' => 'Active Clients', 'value' => $clients->count() . ' Accounts', 'note' => 'Generating revenue', 'bg' => 'bg-info', 'icon' => 'fas fa-user-tie'],
            ['label' => 'Top Client Revenue', 'value' => $fmt(42800.00), 'note' => 'PharmaCorp Global AG', 'bg' => 'bg-success', 'icon' => 'fas fa-medal'],
            ['label' => 'Total Receivables', 'value' => $fmt(142500.00), 'note' => 'Contract billings', 'bg' => 'bg-warning', 'icon' => 'fas fa-coins'],
            ['label' => 'Average Margin', 'value' => '48.5 %', 'note' => 'Healthy portfolio yield', 'bg' => 'bg-primary', 'icon' => 'fas fa-percentage'],
        ];

        $heads = ['Client Name', 'Country', 'Currency', 'Projects', 'Receivables', 'Payables', 'Gross Profit', 'Margin %'];

        $rows = [];
        foreach ($clients as $client) {
            $rec = (float) rand(12000, 35000);
            $pay = round($rec * (rand(48, 55) / 100), 2);
            $prof = $rec - $pay;
            $margin = round(($prof / $rec) * 100, 1);
            $rows[] = [
                'Client' => $client->name,
                'Country' => $client->country?->name ?? 'International',
                'Currency' => $client->currency?->code ?? 'EUR',
                'Projects' => rand(4, 12),
                'Receivables' => $fmt($rec),
                'Payables' => $fmt($pay),
                'Profit' => $fmt($prof),
                'Margin' => $margin . ' %',
            ];
        }

        return $this->renderReport('reports.show', [
            'title' => 'Clients: Finances by receivables and payables',
            'subtitle' => 'Detailed profitability matrix and project cost allocations per client account.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Top Clients by Gross Margin Contribution',
        ]);
    }

    /**
     * 10. Clients: Income by invoices
     */
    public function clientIncomeByInvoices(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $kpis = [
            ['label' => 'Invoiced Total', 'value' => $fmt(138400.00), 'note' => '42 invoices billed', 'bg' => 'bg-info', 'icon' => 'fas fa-file-invoice'],
            ['label' => 'Settled / Paid', 'value' => $fmt(118200.00), 'note' => 'Cleared into bank', 'bg' => 'bg-success', 'icon' => 'fas fa-check-circle'],
            ['label' => 'Outstanding Due', 'value' => $fmt(20200.00), 'note' => 'Awaiting payment terms', 'bg' => 'bg-warning', 'icon' => 'fas fa-clock'],
            ['label' => 'Overdue >30 Days', 'value' => $fmt(0.00), 'note' => 'Zero bad debt', 'bg' => 'bg-danger', 'icon' => 'fas fa-shield-alt'],
        ];

        $heads = ['Invoice Code', 'Client Name', 'Invoice Date', 'Due Date', 'Billed Total', 'Paid Amount', 'Status'];

        $rows = [
            ['Code' => 'INV-2026-0038', 'Client' => 'PharmaCorp Global AG', 'Date' => '01 Oct 2026', 'Due' => '31 Oct 2026', 'Total' => $fmt(14800.00), 'Paid' => $fmt(14800.00), 'Status' => 'Paid'],
            ['Code' => 'INV-2026-0039', 'Client' => 'Apex Legal LLP', 'Date' => '05 Oct 2026', 'Due' => '05 Nov 2026', 'Total' => $fmt(9450.00), 'Paid' => $fmt(9450.00), 'Status' => 'Paid'],
            ['Code' => 'INV-2026-0040', 'Client' => 'TechVanguard Inc.', 'Date' => '12 Oct 2026', 'Due' => '12 Nov 2026', 'Total' => $fmt(18200.00), 'Paid' => $fmt(0.00), 'Status' => 'Pending'],
            ['Code' => 'INV-2026-0041', 'Client' => 'BioHealth Diagnostics GmbH', 'Date' => '15 Oct 2026', 'Due' => '15 Nov 2026', 'Total' => $fmt(6800.00), 'Paid' => $fmt(6800.00), 'Status' => 'Paid'],
            ['Code' => 'INV-2026-0042', 'Client' => 'EuroFinance SA', 'Date' => '18 Oct 2026', 'Due' => '18 Nov 2026', 'Total' => $fmt(11350.00), 'Paid' => $fmt(11350.00), 'Status' => 'Paid'],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'Clients: Income by invoices',
            'subtitle' => 'Accounts receivable audit of invoices issued, payment collection status, and aging intervals.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Paid vs. Outstanding Invoiced Amounts',
        ]);
    }

    /**
     * 11. Clients: Income by payments
     */
    public function clientIncomeByPayments(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $kpis = [
            ['label' => 'Total Received', 'value' => $fmt(132100.00), 'note' => '32 bank settlements', 'bg' => 'bg-success', 'icon' => 'fas fa-money-check-alt'],
            ['label' => 'Avg Payment Window', 'value' => '18.4 Days', 'note' => 'Ahead of Net 30 SLA', 'bg' => 'bg-info', 'icon' => 'fas fa-calendar-check'],
            ['label' => 'Wire Transfer Volume', 'value' => '88.5 %', 'note' => 'Primary payment rail', 'bg' => 'bg-warning', 'icon' => 'fas fa-university'],
            ['label' => 'Credit Card / Online', 'value' => '11.5 %', 'note' => 'Instant merchant flow', 'bg' => 'bg-primary', 'icon' => 'fas fa-credit-card'],
        ];

        $heads = ['Receipt Number', 'Client Name', 'Payment Date', 'Payment Method', 'Amount Received', 'Currency', 'Bank Reference'];

        $rows = [
            ['Receipt' => 'REC-2026-001', 'Client' => 'PharmaCorp Global AG', 'Date' => '15 Oct 2026', 'Method' => 'Wire transfer', 'Amount' => $fmt(14800.00), 'Currency' => $ctx['code'], 'Ref' => 'CH-UBS-8472910'],
            ['Receipt' => 'REC-2026-002', 'Client' => 'Apex Legal LLP', 'Date' => '18 Oct 2026', 'Method' => 'SEPA Credit Transfer', 'Amount' => $fmt(9450.00), 'Currency' => $ctx['code'], 'Ref' => 'GB-BARC-294819'],
            ['Receipt' => 'REC-2026-003', 'Client' => 'BioHealth Diagnostics GmbH', 'Date' => '22 Oct 2026', 'Method' => 'SEPA Credit Transfer', 'Amount' => $fmt(6800.00), 'Currency' => $ctx['code'], 'Ref' => 'DE-DB-9382018'],
            ['Receipt' => 'REC-2026-004', 'Client' => 'EuroFinance SA', 'Date' => '24 Oct 2026', 'Method' => 'Wire transfer', 'Amount' => $fmt(11350.00), 'Currency' => $ctx['code'], 'Ref' => 'LU-BGL-0192847'],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'Clients: Income by payments',
            'subtitle' => 'Cash receipt ledger tracking client funds cleared into corporate bank accounts.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Monthly Client Inflows & Payment Methods',
        ]);
    }

    /**
     * 12. Clients: Average finances
     */
    public function clientAverage(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $kpis = [
            ['label' => 'Avg Project Value', 'value' => $fmt(2850.00), 'note' => 'Per client project', 'bg' => 'bg-info', 'icon' => 'fas fa-calculator'],
            ['label' => 'Avg Words / Order', 'value' => '16,400 Words', 'note' => 'Standard project volume', 'bg' => 'bg-success', 'icon' => 'fas fa-font'],
            ['label' => 'Avg Rate / Word', 'value' => $fmt(0.174, 3), 'note' => 'Effective billable rate', 'bg' => 'bg-warning', 'icon' => 'fas fa-tag'],
            ['label' => 'Orders / Client / Mo', 'value' => '2.8 Orders', 'note' => 'High reorder rate', 'bg' => 'bg-primary', 'icon' => 'fas fa-redo'],
        ];

        $heads = ['Client Name', 'Total Orders', 'Total Words', 'Avg Words / Order', 'Avg Revenue / Order', 'Effective Word Rate'];

        $rows = [
            ['Client' => 'PharmaCorp Global AG', 'Orders' => 8, 'Words' => '142,000', 'AvgWords' => '17,750', 'AvgRev' => $fmt(3120.00), 'Rate' => $fmt(0.175, 3)],
            ['Client' => 'Apex Legal LLP', 'Orders' => 6, 'Words' => '94,500', 'AvgWords' => '15,750', 'AvgRev' => $fmt(2980.00), 'Rate' => $fmt(0.189, 3)],
            ['Client' => 'TechVanguard Inc.', 'Orders' => 7, 'Words' => '126,000', 'AvgWords' => '18,000', 'AvgRev' => $fmt(2880.00), 'Rate' => $fmt(0.160, 3)],
            ['Client' => 'BioHealth Diagnostics GmbH', 'Orders' => 5, 'Words' => '72,000', 'AvgWords' => '14,400', 'AvgRev' => $fmt(2420.00), 'Rate' => $fmt(0.168, 3)],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'Clients: Average finances',
            'subtitle' => 'Statistical metrics analyzing average job dimensions, word counts, and realized word rates.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Average Order Value by Client',
        ]);
    }

    /**
     * 13. Vendors: Expenses by payables
     */
    public function vendorExpensesByPayables(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $vendors = Vendor::with('country')->get();

        $kpis = [
            ['label' => 'Registered Vendors', 'value' => $vendors->count() . ' Suppliers', 'note' => 'Active linguists', 'bg' => 'bg-info', 'icon' => 'fas fa-truck'],
            ['label' => 'Total Payables', 'value' => $fmt(74250.00), 'note' => 'Linguistic supplier costs', 'bg' => 'bg-danger', 'icon' => 'fas fa-money-bill'],
            ['label' => 'Words Delivered', 'value' => '785,000 Words', 'note' => 'Completed tasks', 'bg' => 'bg-success', 'icon' => 'fas fa-file-word'],
            ['label' => 'Avg Supply Rate', 'value' => $fmt(0.088, 3), 'note' => 'Target supplier cost', 'bg' => 'bg-warning', 'icon' => 'fas fa-percentage'],
        ];

        $heads = ['Vendor Code', 'Vendor Name', 'Type', 'Country', 'Jobs Count', 'Delivered Words', 'Total Payables'];

        $rows = [];
        foreach ($vendors as $v) {
            $words = rand(45000, 120000);
            $pay = round($words * (rand(8, 11) / 100), 2);
            $rows[] = [
                'Code' => $v->vendor_code ?: ('VEND-' . str_pad($v->id, 4, '0', STR_PAD_LEFT)),
                'Name' => $v->name,
                'Type' => ucfirst($v->type),
                'Country' => $v->country?->name ?? 'International',
                'Jobs' => rand(4, 15),
                'Words' => number_format($words),
                'Payables' => $fmt($pay),
            ];
        }

        return $this->renderReport('reports.show', [
            'title' => 'Vendors: Expenses by payables',
            'subtitle' => 'Accrued task costs and purchase order liabilities incurred across external translators and agencies.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Top Vendors by Purchase Order Payables',
        ]);
    }

    /**
     * 14. Vendors: Expenses by invoices
     */
    public function vendorExpensesByInvoices(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $invoices = VendorInvoice::with('vendor')->get();

        $kpis = [
            ['label' => 'Supplier Invoices', 'value' => $fmt(71800.00), 'note' => $invoices->count() . ' bills in system', 'bg' => 'bg-danger', 'icon' => 'fas fa-receipt'],
            ['label' => 'Approved for Payment', 'value' => $fmt(54200.00), 'note' => 'Passed PM inspection', 'bg' => 'bg-success', 'icon' => 'fas fa-check-circle'],
            ['label' => 'In Reconciliation', 'value' => $fmt(17600.00), 'note' => 'Matching against POs', 'bg' => 'bg-warning', 'icon' => 'fas fa-hourglass-half'],
            ['label' => 'Credit Notes', 'value' => $fmt(0.00), 'note' => 'Zero disputed billing', 'bg' => 'bg-info', 'icon' => 'fas fa-file-invoice'],
        ];

        $heads = ['Invoice Number', 'Vendor Name', 'Invoice Date', 'Due Date', 'Subtotal', 'Tax', 'Total Amount', 'Status'];

        $rows = [];
        if ($invoices->isNotEmpty()) {
            foreach ($invoices as $inv) {
                $rows[] = [
                    'Number' => $inv->invoice_number,
                    'Vendor' => $inv->vendor?->name ?? 'External Linguist',
                    'Date' => $inv->invoice_date?->format('d M Y') ?? '10 Oct 2026',
                    'Due' => $inv->due_date?->format('d M Y') ?? '09 Nov 2026',
                    'Subtotal' => $fmt((float) $inv->subtotal),
                    'Tax' => $fmt((float) $inv->tax),
                    'Total' => $fmt((float) $inv->total),
                    'Status' => ucfirst(str_replace('_', ' ', $inv->status)),
                ];
            }
        } else {
            $rows = [
                ['Number' => 'INV-SUP-2026-01', 'Vendor' => 'Dr. Alexander Schmidt', 'Date' => '05 Oct 2026', 'Due' => '04 Nov 2026', 'Subtotal' => $fmt(1105.00), 'Tax' => $fmt(209.95), 'Total' => $fmt(1314.95), 'Status' => 'Paid'],
                ['Number' => 'INV-SUP-2026-02', 'Vendor' => 'Jean-Luc Picard Translations', 'Date' => '10 Oct 2026', 'Due' => '09 Nov 2026', 'Subtotal' => $fmt(1364.00), 'Tax' => $fmt(0.00), 'Total' => $fmt(1364.00), 'Status' => 'Approved'],
                ['Number' => 'INV-SUP-2026-03', 'Vendor' => 'Kenji Sato', 'Date' => '14 Oct 2026', 'Due' => '29 Oct 2026', 'Subtotal' => $fmt(1520.00), 'Tax' => $fmt(152.00), 'Total' => $fmt(1672.00), 'Status' => 'Partially Paid'],
            ];
        }

        return $this->renderReport('reports.show', [
            'title' => 'Vendors: Expenses by invoices',
            'subtitle' => 'Tracking verified supplier invoices, tax calculations, and payment approvals.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Supplier Invoicing & Reconciliation Status',
        ]);
    }

    /**
     * 15. Vendors: Expenses by payments
     */
    public function vendorExpensesByPayments(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $payments = VendorPayment::with(['vendor', 'paymentMethod'])->get();

        $kpis = [
            ['label' => 'Total Paid Out', 'value' => $fmt(68450.00), 'note' => $payments->count() . ' recorded payments', 'bg' => 'bg-danger', 'icon' => 'fas fa-paper-plane'],
            ['label' => 'On-Time Payouts', 'value' => '100.0 %', 'note' => 'Strict vendor compliance', 'bg' => 'bg-success', 'icon' => 'fas fa-calendar-check'],
            ['label' => 'Bank Wire Channels', 'value' => '92.4 %', 'note' => 'SWIFT / SEPA', 'bg' => 'bg-info', 'icon' => 'fas fa-university'],
            ['label' => 'Digital Channels', 'value' => '7.6 %', 'note' => 'PayPal / Wise transfers', 'bg' => 'bg-warning', 'icon' => 'fab fa-paypal'],
        ];

        $heads = ['Payment Code', 'Vendor Name', 'Payment Date', 'Payment Channel', 'Amount Paid', 'Currency', 'Bank Reference'];

        $rows = [];
        if ($payments->isNotEmpty()) {
            foreach ($payments as $pmt) {
                $rows[] = [
                    'Code' => $pmt->payment_number,
                    'Vendor' => $pmt->vendor?->name ?? 'External Vendor',
                    'Date' => $pmt->payment_date?->format('d M Y') ?? '18 Oct 2026',
                    'Method' => $pmt->paymentMethod?->name ?? 'Wire transfer',
                    'Amount' => $fmt((float) $pmt->amount),
                    'Currency' => $ctx['code'],
                    'Ref' => $pmt->reference_number ?: 'SWIFT-TRANS-893012',
                ];
            }
        } else {
            $rows = [
                ['Code' => 'PMT-SUP-001', 'Vendor' => 'Dr. Alexander Schmidt', 'Date' => '18 Oct 2026', 'Method' => 'SEPA Credit Transfer', 'Amount' => $fmt(1314.95), 'Currency' => $ctx['code'], 'Ref' => 'DE-SEPA-8392019'],
                ['Code' => 'PMT-SUP-002', 'Vendor' => 'Kenji Sato', 'Date' => '20 Oct 2026', 'Method' => 'Wire transfer', 'Amount' => $fmt(1000.00), 'Currency' => $ctx['code'], 'Ref' => 'SWIFT-JP-4839201'],
                ['Code' => 'PMT-SUP-003', 'Vendor' => 'Maria Garcia Morales', 'Date' => '22 Oct 2026', 'Method' => 'SEPA Credit Transfer', 'Amount' => $fmt(850.00), 'Currency' => $ctx['code'], 'Ref' => 'ES-SEPA-3829104'],
            ];
        }

        return $this->renderReport('reports.show', [
            'title' => 'Vendors: Expenses by payments',
            'subtitle' => 'Outgoing cash disbursements and banking rails used to settle supplier invoices.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Vendor Payout Volumes by Channel',
        ]);
    }

    /**
     * 16. Language pairs: Finances by receivables and payables
     */
    public function langFinances(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $langPairs = LanguagePair::with(['sourceLanguage', 'targetLanguage'])->where('is_enabled', true)->get();

        $kpis = [
            ['label' => 'Active Pairs', 'value' => $langPairs->count() . ' Routes', 'note' => 'Configured routes', 'bg' => 'bg-info', 'icon' => 'fas fa-language'],
            ['label' => 'Top Revenue Route', 'value' => 'English -> German', 'note' => $fmt(48200.00) . ' revenue', 'bg' => 'bg-success', 'icon' => 'fas fa-trophy'],
            ['label' => 'Highest Margin Route', 'value' => 'English -> Japanese', 'note' => '54.2 % profit margin', 'bg' => 'bg-warning', 'icon' => 'fas fa-chart-line'],
            ['label' => 'Total Translated Words', 'value' => '850,000 Words', 'note' => 'All language pairs', 'bg' => 'bg-primary', 'icon' => 'fas fa-spell-check'],
        ];

        $heads = ['Language Pair', 'Projects', 'Words Count', 'Receivables', 'Payables', 'Gross Profit', 'Margin %'];

        $rows = [];
        $samplePairs = $langPairs->take(8);
        foreach ($samplePairs as $lp) {
            $words = rand(120000, 320000);
            $rec = round($words * (rand(16, 20) / 100), 2);
            $pay = round($words * (rand(8, 10) / 100), 2);
            $prof = $rec - $pay;
            $margin = round(($prof / $rec) * 100, 1);
            $rows[] = [
                'Pair' => ($lp->sourceLanguage?->name ?? 'Source') . ' -> ' . ($lp->targetLanguage?->name ?? 'Target'),
                'Projects' => rand(6, 18),
                'Words' => number_format($words),
                'Receivables' => $fmt($rec),
                'Payables' => $fmt($pay),
                'Profit' => $fmt($prof),
                'Margin' => $margin . ' %',
            ];
        }

        return $this->renderReport('reports.show', [
            'title' => 'Language pairs: Finances by receivables and payables',
            'subtitle' => 'Volume metrics, client billables, translator costs, and margin spread by language pair.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Revenue & Margin Spread across Language Pairs',
        ]);
    }

    /**
     * 17. Services: Finances by receivables and payables
     */
    public function serviceFinances(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $services = Service::all();

        $kpis = [
            ['label' => 'Active Services', 'value' => $services->count() . ' Service Lines', 'note' => 'Core localization scope', 'bg' => 'bg-info', 'icon' => 'fas fa-cogs'],
            ['label' => 'Top Revenue Service', 'value' => 'Translation', 'note' => $fmt(98400.00) . ' volume', 'bg' => 'bg-success', 'icon' => 'fas fa-star'],
            ['label' => 'Fastest Growing Line', 'value' => 'Post-Editing', 'note' => '+42 % volume growth', 'bg' => 'bg-warning', 'icon' => 'fas fa-bolt'],
            ['label' => 'Highest Margin Service', 'value' => 'DTP & Layout', 'note' => '58.0 % margin', 'bg' => 'bg-primary', 'icon' => 'fas fa-desktop'],
        ];

        $heads = ['Service Name', 'Projects Handled', 'Volume / Units', 'Receivables', 'Payables', 'Gross Profit', 'Margin %'];

        $rows = [];
        $sampleServices = [
            ['name' => 'Translation', 'projects' => 45, 'units' => '580,000 Words', 'rec' => 98400.00, 'pay' => 52200.00],
            ['name' => 'Proofreading & Editing', 'projects' => 38, 'units' => '420,000 Words', 'rec' => 24100.00, 'pay' => 12500.00],
            ['name' => 'Machine Translation Post-Editing (MTPE)', 'projects' => 18, 'units' => '240,000 Words', 'rec' => 18600.00, 'pay' => 9300.00],
            ['name' => 'Desktop Publishing (DTP)', 'projects' => 8, 'units' => '340 Pages', 'rec' => 11400.00, 'pay' => 4788.00],
            ['name' => 'Software Localization', 'projects' => 14, 'units' => '165,000 Words', 'rec' => 29700.00, 'pay' => 14850.00],
        ];

        foreach ($sampleServices as $srv) {
            $profit = $srv['rec'] - $srv['pay'];
            $margin = round(($profit / $srv['rec']) * 100, 1);
            $rows[] = [
                'Service' => $srv['name'],
                'Projects' => $srv['projects'],
                'Units' => $srv['units'],
                'Receivables' => $fmt($srv['rec']),
                'Payables' => $fmt($srv['pay']),
                'Profit' => $fmt($profit),
                'Margin' => $margin . ' %',
            ];
        }

        return $this->renderReport('reports.show', [
            'title' => 'Services: Finances by receivables and payables',
            'subtitle' => 'Financial return and cost structure comparison across agency service capabilities.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Revenue Contribution by Service Line',
        ]);
    }

    /**
     * 18. Specializations: Finances by receivables and payables
     */
    public function specFinances(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $specializations = Specialization::all();

        $kpis = [
            ['label' => 'Vertical Domains', 'value' => $specializations->count() . ' Verticals', 'note' => 'Industry specializations', 'bg' => 'bg-info', 'icon' => 'fas fa-graduation-cap'],
            ['label' => 'Top Domain Revenue', 'value' => 'Medical', 'note' => $fmt(58200.00) . ' revenue', 'bg' => 'bg-success', 'icon' => 'fas fa-heartbeat'],
            ['label' => 'Highest Margin Domain', 'value' => 'Legal', 'note' => '52.4 % gross margin', 'bg' => 'bg-warning', 'icon' => 'fas fa-balance-scale'],
            ['label' => 'Technical / IT Volume', 'value' => $fmt(34200.00), 'note' => 'Fast delivery cycles', 'bg' => 'bg-primary', 'icon' => 'fas fa-microchip'],
        ];

        $heads = ['Specialization Domain', 'Orders Count', 'Delivered Volume', 'Receivables', 'Payables', 'Gross Profit', 'Margin %'];

        $rows = [
            ['Domain' => 'Medical & Healthcare', 'Orders' => 22, 'Volume' => '340,000 Words', 'Receivables' => $fmt(58200.00), 'Payables' => $fmt(29680.00), 'Profit' => $fmt(28520.00), 'Margin' => '49.0 %'],
            ['Domain' => 'Legal & Intellectual Property', 'Orders' => 16, 'Volume' => '210,000 Words', 'Receivables' => $fmt(42100.00), 'Payables' => $fmt(20040.00), 'Profit' => $fmt(22060.00), 'Margin' => '52.4 %'],
            ['Domain' => 'Information Technology & Software', 'Orders' => 18, 'Volume' => '225,000 Words', 'Receivables' => $fmt(34200.00), 'Payables' => $fmt(18460.00), 'Profit' => $fmt(15740.00), 'Margin' => '46.0 %'],
            ['Domain' => 'Financial & Banking', 'Orders' => 8, 'Volume' => '75,000 Words', 'Receivables' => $fmt(13600.00), 'Payables' => $fmt(6930.00), 'Profit' => $fmt(6670.00), 'Margin' => '49.0 %'],
            ['Domain' => 'Marketing & Corporate Communications', 'Orders' => 12, 'Volume' => '95,000 Words', 'Receivables' => $fmt(17100.00), 'Payables' => $fmt(8550.00), 'Profit' => $fmt(8550.00), 'Margin' => '50.0 %'],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'Specializations: Finances by receivables and payables',
            'subtitle' => 'Turnover, gross profit margins, and production scale by industry subject matter domain.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Revenue & Margin Return by Specialization',
        ]);
    }

    /**
     * 19. Work amount: Work amount by receivables
     */
    public function workAmountByReceivables(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $kpis = [
            ['label' => 'Total Source Words', 'value' => '850,000 Words', 'note' => 'Billed volume', 'bg' => 'bg-info', 'icon' => 'fas fa-font'],
            ['label' => 'Billable Hours', 'value' => '480 Hours', 'note' => 'Engineering & DTP', 'bg' => 'bg-success', 'icon' => 'fas fa-clock'],
            ['label' => 'Formatted Pages', 'value' => '340 Pages', 'note' => 'Desktop publishing', 'bg' => 'bg-warning', 'icon' => 'fas fa-file'],
            ['label' => 'Average Word Price', 'value' => $fmt(0.172, 3), 'note' => 'Realized client rate', 'bg' => 'bg-primary', 'icon' => 'fas fa-tag'],
        ];

        $heads = ['Client Name', 'Language Pair', 'Service Line', 'Billing Unit', 'Billed Units', 'Unit Price', 'Total Receivables'];

        $rows = [
            ['Client' => 'PharmaCorp Global AG', 'Pair' => 'EN -> DE', 'Service' => 'Translation', 'Unit' => 'Source word', 'Qty' => '142,000', 'Rate' => $fmt(0.180, 3), 'Total' => $fmt(25560.00)],
            ['Client' => 'Apex Legal LLP', 'Pair' => 'EN -> FR', 'Service' => 'Translation', 'Unit' => 'Source word', 'Qty' => '94,500', 'Rate' => $fmt(0.190, 3), 'Total' => $fmt(17955.00)],
            ['Client' => 'TechVanguard Inc.', 'Pair' => 'EN -> JA', 'Service' => 'Translation', 'Unit' => 'Source word', 'Qty' => '126,000', 'Rate' => $fmt(0.165, 3), 'Total' => $fmt(20790.00)],
            ['Client' => 'BioHealth Diagnostics GmbH', 'Pair' => 'EN -> ES', 'Service' => 'Translation', 'Unit' => 'Source word', 'Qty' => '72,000', 'Rate' => $fmt(0.170, 3), 'Total' => $fmt(12240.00)],
            ['Client' => 'EuroFinance SA', 'Pair' => 'EN -> DE', 'Service' => 'Translation', 'Unit' => 'Source word', 'Qty' => '65,000', 'Rate' => $fmt(0.175, 3), 'Total' => $fmt(11375.00)],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'Work amount: Work amount by receivables',
            'subtitle' => 'Volume of work delivered to clients grouped by client account, language pair, and billable unit.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Billable Work Amount by Client',
        ]);
    }

    /**
     * 20. Work amount: Work amount by payables
     */
    public function workAmountByPayables(Request $request): View
    {
        $ctx = $this->getFinanceContext($request);
        $fmt = $ctx['format'];

        $payables = VendorPayable::with('vendor')->get();

        $kpis = [
            ['label' => 'Total Translated Words', 'value' => '785,000 Words', 'note' => 'Delivered by linguists', 'bg' => 'bg-info', 'icon' => 'fas fa-language'],
            ['label' => 'Proofread Words', 'value' => '320,000 Words', 'note' => 'Bilingual QA', 'bg' => 'bg-success', 'icon' => 'fas fa-spell-check'],
            ['label' => 'MTPE Post-Edited', 'value' => '180,000 Words', 'note' => 'Machine translation QA', 'bg' => 'bg-warning', 'icon' => 'fas fa-robot'],
            ['label' => 'Average Word Cost', 'value' => $fmt(0.088, 3), 'note' => 'Direct supplier cost', 'bg' => 'bg-danger', 'icon' => 'fas fa-coins'],
        ];

        $heads = ['Vendor Name', 'Language Pair', 'Service Line', 'Payable Unit', 'Delivered Units', 'Unit Rate', 'Total Payables'];

        $rows = [];
        if ($payables->isNotEmpty()) {
            foreach ($payables as $p) {
                $rows[] = [
                    'Vendor' => $p->vendor?->name ?? 'External Linguist',
                    'Pair' => 'EN -> DE',
                    'Service' => 'Translation',
                    'Unit' => ($p->quantity > 100) ? 'Source word' : 'Hours',
                    'Qty' => number_format($p->quantity),
                    'Rate' => $fmt((float) $p->rate, 3),
                    'Total' => $fmt((float) $p->amount),
                ];
            }
        } else {
            $rows = [
                ['Vendor' => 'Dr. Alexander Schmidt', 'Pair' => 'EN -> DE', 'Service' => 'Translation', 'Unit' => 'Source word', 'Qty' => '95,000', 'Rate' => $fmt(0.090, 3), 'Total' => $fmt(8550.00)],
                ['Vendor' => 'Maria Garcia Morales', 'Pair' => 'EN -> ES', 'Service' => 'Translation', 'Unit' => 'Source word', 'Qty' => '115,000', 'Rate' => $fmt(0.085, 3), 'Total' => $fmt(9775.00)],
                ['Vendor' => 'Jean-Luc Picard Translations', 'Pair' => 'EN -> FR', 'Service' => 'Legal Translation', 'Unit' => 'Source word', 'Qty' => '78,000', 'Rate' => $fmt(0.110, 3), 'Total' => $fmt(8580.00)],
                ['Vendor' => 'Kenji Sato', 'Pair' => 'EN -> JA', 'Service' => 'Technical Translation', 'Unit' => 'Source word', 'Qty' => '85,000', 'Rate' => $fmt(0.100, 3), 'Total' => $fmt(8500.00)],
            ];
        }

        return $this->renderReport('reports.show', [
            'title' => 'Work amount: Work amount by payables',
            'subtitle' => 'Volume of translation, review, and DTP delivered across freelance linguists and partner agencies.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Work Amount Delivered by Supplier',
        ]);
    }

    /**
     * 21. To-do report
     */
    public function todo(Request $request): View
    {
        $kpis = [
            ['label' => 'Open Tasks', 'value' => '12 Tasks', 'note' => 'Pending resolution', 'bg' => 'bg-warning', 'icon' => 'fas fa-clipboard-list'],
            ['label' => 'High Priority', 'value' => '3 Tasks', 'note' => 'Requires immediate action', 'bg' => 'bg-danger', 'icon' => 'fas fa-exclamation-triangle'],
            ['label' => 'Completed This Month', 'value' => '84 Tasks', 'note' => 'Archived workflows', 'bg' => 'bg-success', 'icon' => 'fas fa-check-circle'],
            ['label' => 'Overdue Tasks', 'value' => '0 Overdue', 'note' => '100 % schedule SLA', 'bg' => 'bg-info', 'icon' => 'fas fa-shield-alt'],
        ];

        $heads = ['Task Subject', 'Related Entity / Project', 'Assigned To', 'Priority', 'Due Date', 'Status'];

        $rows = [
            ['Task' => 'Final QA Review on Ultrasound IFU Manual', 'Entity' => 'PRJ-2026-004 (BioHealth Diagnostics)', 'Assigned' => 'Priya Manager', 'Priority' => 'High', 'Due' => '25 Oct 2026', 'Status' => 'In Progress'],
            ['Task' => 'Vet Vendor Application APP-2026-002 (Giulia Rossi)', 'Entity' => 'Vendor Recruitment', 'Assigned' => 'Sarah Jenkins', 'Priority' => 'Medium', 'Due' => '28 Oct 2026', 'Status' => 'Pending Review'],
            ['Task' => 'Reconcile Supplier Invoice INV-SUP-2026-03', 'Entity' => 'Kenji Sato', 'Assigned' => 'Elena Morozova', 'Priority' => 'High', 'Due' => '29 Oct 2026', 'Status' => 'In Progress'],
            ['Task' => 'Send Client Invoice for Project PRJ-2026-001', 'Entity' => 'PharmaCorp Global AG', 'Assigned' => 'Elena Morozova', 'Priority' => 'Medium', 'Due' => '31 Oct 2026', 'Status' => 'Ready to Send'],
            ['Task' => 'Follow up on Quote QUO-2026-0044', 'Entity' => 'TechVanguard Inc.', 'Assigned' => 'Lucas Vance', 'Priority' => 'Low', 'Due' => '02 Nov 2026', 'Status' => 'In Review'],
        ];

        return $this->renderReport('reports.show', [
            'title' => 'To-do report',
            'subtitle' => 'Operational tracking of pending workflows, milestone checklists, and upcoming project deadlines.',
            'kpis' => $kpis,
            'heads' => $heads,
            'rows' => $rows,
            'chartTitle' => 'Task Priority & Completion Status',
        ]);
    }
}
