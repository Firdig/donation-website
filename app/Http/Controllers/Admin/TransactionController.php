<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationTransaction;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = DonationTransaction::with(['user', 'donation']);

        // Filter by donation
        if ($request->donation_id) {
            $query->where('donation_id', $request->donation_id);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search by donor name or email
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('donor_name', 'like', '%' . $request->search . '%')
                  ->orWhere('donor_email', 'like', '%' . $request->search . '%');
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);
        $donations = Donation::orderBy('title')->get();

        return view('admin.transactions.index', compact('transactions', 'donations'));
    }

    public function show(DonationTransaction $transaction)
    {
        $transaction->load(['user', 'donation']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, DonationTransaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed',
        ]);

        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        DB::beginTransaction();
        try {
            // Update transaction status
            $transaction->update(['status' => $newStatus]);

            // Update donation amount if status changes
            if ($oldStatus !== $newStatus) {
                $donation = $transaction->donation;

                if ($oldStatus === 'completed' && $newStatus !== 'completed') {
                    // Remove from donation amount
                    $donation->decrement('current_amount', $transaction->amount);
                } elseif ($oldStatus !== 'completed' && $newStatus === 'completed') {
                    // Add to donation amount
                    $donation->increment('current_amount', $transaction->amount);
                }

                // Check if target reached or not
                if ($donation->current_amount >= $donation->target_amount && $donation->status !== 'completed') {
                    $donation->update(['status' => 'completed']);
                } elseif ($donation->current_amount < $donation->target_amount && $donation->status === 'completed') {
                    $donation->update(['status' => 'active']);
                }
            }

            DB::commit();

            return redirect()->route('admin.transactions.index')
                ->with('success', 'Status transaksi berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat mengupdate status.');
        }
    }

    public function destroy(DonationTransaction $transaction)
    {
        DB::beginTransaction();
        try {
            // Update donation amount if transaction was completed
            if ($transaction->status === 'completed') {
                $donation = $transaction->donation;
                $donation->decrement('current_amount', $transaction->amount);

                // Update donation status if needed
                if ($donation->current_amount < $donation->target_amount && $donation->status === 'completed') {
                    $donation->update(['status' => 'active']);
                }
            }

            $transaction->delete();

            DB::commit();

            return redirect()->route('admin.transactions.index')
                ->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus transaksi.');
        }
    }
}