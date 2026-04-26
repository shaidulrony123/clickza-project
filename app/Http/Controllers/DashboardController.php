<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Invoice;

class DashboardController extends Controller
{
    //show dashboard page
    // public function Dashboard()
    // {
    //     return view('backend.pages.dashboard');
    // }
    public function index()
    {
        $totalProjects = Project::count();
        $totalContacts = Contact::count();
        $totalProducts = Product::count();
        $paidInvoiceRevenue = (float) Invoice::where('status', 'paid')->sum('total');
        $paidInvoiceCost = (float) Invoice::where('status', 'paid')->sum('internal_cost');
        $paidInvoiceProfit = $paidInvoiceRevenue - $paidInvoiceCost;
        $paidInvoiceMargin = $paidInvoiceRevenue > 0
            ? round(($paidInvoiceProfit / $paidInvoiceRevenue) * 100, 1)
            : 0;

        return view('backend.pages.dashboard', compact(
            'totalProjects',
            'totalContacts',
            'totalProducts',
            'paidInvoiceRevenue',
            'paidInvoiceCost',
            'paidInvoiceProfit',
            'paidInvoiceMargin'
        ));
    }
}
