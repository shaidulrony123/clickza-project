<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Contact;
use App\Models\Product;

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

        return view('backend.pages.dashboard', compact(
            'totalProjects',
            'totalContacts',
            'totalProducts'
        ));
    }
}
