<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // sample payload ----
        // ?category_id=3&document_number=0000003&status=draft&issued_at=2022-04-23&due_at=2022-05-22&account_id=1&currency_code=NZD&currency_rate=1&notes=This is note for invoice&contact_id=2&contact_name=Name&contact_email=mail@mail.com&contact_address=Client address&items[0][item_id]=1&items[0][name]=Service&items[0][quantity]=2&items[0][price]=1&items[0][total]=2&items[0][discount]=0&items[0][description]=This is custom item description&items[0][tax_ids][0]=1&items[0][tax_ids][1]=1&amount=2&type=invoice&search=type:invoice
        $validated = $request->validate([
            'document_number' => 'required|numeric',
            'status' => 'required|in:draft,sent,viewed,partial,overdue,paid,refunded,cancelled',
            'issued_at' => 'nullable|date',
            'due_at' => 'nullable|date',
            'currency_code' => 'nullable|in:INR,USD',
            'currency_rate' => 'nullable|numeric',
            'notes' => 'nullable',
            'contact_id' => 'required|numeric',
            'contact_name' => 'nullable',
            'contact_email' => 'nullable|email',
            'contact_address' => 'nullable',
            'items' => 'nullable|array',
            'items.*.item_id' => 'nullable|numeric',
            'items.*.name' => 'nullable',
            'items.*.quantity' => 'nullable|numeric',
            'items.*.price' => 'nullable|numeric',
            'items.*.total' => 'nullable|numeric',
            'items.*.discount' => 'nullable|numeric',
            'items.*.description' => 'nullable',
            'items.*.tax_ids' => 'nullable|array',
            'items.*.tax_ids.*' => 'nullable|numeric',
            'amount' => 'nullable|numeric'
        ]);

        $data = array_merge($validated, [
            'search' => 'type:invoice',
            'type' => 'invoice',
            'category_id' => 3, // 3 is for income category
            'account_id' => 1, // 1 is for cash account
            'issued_at' => $validated['issued_at'] ?? now()->format('Y-m-d'),
            'due_at' => $validated['due_at'] ?? now()->addDays(30)->format('Y-m-d'),
            'currency_code' => $validated['currency_code'] ?? 'INR',
            'currency_rate' => $validated['currency_rate'] ?? 1,
            'notes' => $validated['notes'] ?? '',
        ]);

        return $data;

        $users = app('akaunting')->post('transactions', $data);
        return response()->json($users);
    }

    public function index()
    {
        $users = app('akaunting')->get('documents', [
            'search' => 'type:invoice'
        ]);
        return response()->json($users);
    }
}
