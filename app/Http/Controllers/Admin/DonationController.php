<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::query();

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search by title or description
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $donations = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.donations.index', compact('donations'));
    }

    public function create()
    {
        return view('admin.donations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:100000',
            'end_date' => 'nullable|date|after:today',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('donations', 'public');
        }

        Donation::create($data);

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donasi berhasil dibuat.');
    }

    public function show(Donation $donation)
    {
        $transactions = $donation->transactions()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.donations.show', compact('donation', 'transactions'));
    }

    public function edit(Donation $donation)
    {
        return view('admin.donations.edit', compact('donation'));
    }

    public function update(Request $request, Donation $donation)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:100000',
            'end_date' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive,completed',
        ]);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            if ($donation->image) {
                Storage::disk('public')->delete($donation->image);
            }
            $data['image'] = $request->file('image')->store('donations', 'public');
        }

        $donation->update($data);

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donasi berhasil diupdate.');
    }

    public function destroy(Donation $donation)
    {
        if ($donation->image) {
            Storage::disk('public')->delete($donation->image);
        }

        $donation->delete();

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donasi berhasil dihapus.');
    }
}