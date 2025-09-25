<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('donations.index', compact('donations'));
    }

    public function show(Donation $donation)
    {
        $donation->load('transactions');
        $recentTransactions = $donation->transactions()
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get();

        return view('donations.show', compact('donation', 'recentTransactions'));
    }
    public function debugDates(Donation $donation)
{
    $now = now();
    $endDate = $donation->end_date;
    
    $debug = [
        'now' => $now->format('Y-m-d H:i:s'),
        'now_start_of_day' => $now->startOfDay()->format('Y-m-d H:i:s'),
        'end_date' => $endDate ? $endDate->format('Y-m-d H:i:s') : 'null',
        'end_date_start_of_day' => $endDate ? $endDate->startOfDay()->format('Y-m-d H:i:s') : 'null',
        'is_past' => $endDate ? $endDate->isPast() : 'null',
        'diff_in_days' => $endDate ? $now->startOfDay()->diffInDays($endDate->startOfDay()) : 'null',
        'remaining_days' => $donation->remaining_days,
        'days_passed' => $donation->days_passed_since_end,
    ];
    
    dd($debug);
}

    public function donate(Request $request, Donation $donation)
    {
        // Validate donation status and end date
        if ($donation->status !== 'active') {
            return back()->with('error', 'Donasi ini sudah tidak aktif.');
        }

        if ($donation->end_date && $donation->end_date->isPast()) {
            return back()->with('error', 'Donasi ini sudah berakhir.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1000', // Changed from 10000 to 1000 to match form
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Get donor information
            $user = auth()->user();
            $isAnonymous = $request->boolean('is_anonymous');
            
            // Create transaction
            DonationTransaction::create([
                'user_id' => $user->id,
                'donation_id' => $donation->id,
                'amount' => $request->amount,
                'donor_name' => $isAnonymous ? 'Anonim' : $user->name,
                'donor_email' => $user->email,
                'message' => $request->message,
                'status' => 'completed', // In real app, this would be 'pending' until payment confirmed
                'is_anonymous' => $isAnonymous,
            ]);

            // Update donation current amount
            $donation->increment('current_amount', $request->amount);

            // Check if target reached
            if ($donation->current_amount >= $donation->target_amount) {
                $donation->update(['status' => 'completed']);
            }

            DB::commit();

            return redirect()->route('donations.show', $donation)
                ->with('success', 'Terima kasih! Donasi Anda berhasil.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi: ' . $e->getMessage());
        }
    }
}