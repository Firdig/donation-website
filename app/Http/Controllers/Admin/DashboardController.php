<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationTransaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_donations' => Donation::count(),
            'active_donations' => Donation::where('status', 'active')->count(),
            'completed_donations' => Donation::where('status', 'completed')->count(),
            'total_raised' => DonationTransaction::where('status', 'completed')->sum('amount'),
            'total_transactions' => DonationTransaction::where('status', 'completed')->count(),
            'total_donors' => User::where('role_id', 2)->count(),
        ];

        $recentDonations = Donation::latest()->take(5)->get();
        $recentTransactions = DonationTransaction::with(['user', 'donation'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentDonations', 'recentTransactions'));
    }
}