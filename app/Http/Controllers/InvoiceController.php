<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    public function InvoiceSection()
    {
        return view('backend.pages.invoice');
    }

    public function InvoiceList()
    {
        $rows = Invoice::latest()->get();

        return response()->json([
            'status' => 'success',
            'rows' => $rows,
        ]);
    }

    public function InvoiceCreate(Request $request)
    {
        $data = $this->validatedInvoiceData($request);

        Invoice::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice created successfully',
        ]);
    }

    public function InvoiceById(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:invoices,id',
        ]);

        $row = Invoice::findOrFail($request->id);

        return response()->json([
            'status' => 'success',
            'row' => $row,
        ]);
    }

    public function InvoiceUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:invoices,id',
        ]);

        $invoice = Invoice::findOrFail($request->id);
        $data = $this->validatedInvoiceData($request, $invoice->id);

        $invoice->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice updated successfully',
        ]);
    }

    public function InvoiceDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:invoices,id',
        ]);

        $invoice = Invoice::findOrFail($request->id);
        $invoice->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice deleted successfully',
        ]);
    }

    public function InvoiceDownload($id)
    {
        $invoice = Invoice::findOrFail($id);
        $settings = Settings::first();

        $pdf = Pdf::loadView('backend.components.invoice.pdf', [
            'invoice' => $invoice,
            'settings' => $settings,
        ])->setPaper('a4', 'portrait');

        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    public function InvoicePreview($id)
    {
        $invoice = Invoice::findOrFail($id);
        $settings = Settings::first();

        return view('backend.components.invoice.pdf', [
            'invoice' => $invoice,
            'settings' => $settings,
            'isPreview' => true,
        ]);
    }

    protected function validatedInvoiceData(Request $request, ?int $invoiceId = null): array
    {
        $validated = $request->validate([
            'invoice_number' => 'nullable|string|max:255|unique:invoices,invoice_number,' . $invoiceId,
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:255',
            'client_address' => 'nullable|string',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'required|string|max:255',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.description' => 'nullable|string|max:255',
            'items.*.qty' => 'nullable|numeric|min:0',
            'items.*.rate' => 'nullable|numeric|min:0',
        ]);

        $items = collect($validated['items'] ?? [])
            ->map(function ($item) {
                $description = trim((string) ($item['description'] ?? ''));
                $qty = (float) ($item['qty'] ?? 0);
                $rate = (float) ($item['rate'] ?? 0);

                if ($description === '' || $qty <= 0) {
                    return null;
                }

                return [
                    'description' => $description,
                    'qty' => $qty,
                    'rate' => $rate,
                    'amount' => round($qty * $rate, 2),
                ];
            })
            ->filter()
            ->values()
            ->all();

        if (count($items) === 0) {
            throw ValidationException::withMessages([
                'items' => 'At least one invoice item is required.',
            ]);
        }

        $subtotal = round(collect($items)->sum('amount'), 2);
        $discount = round((float) ($validated['discount'] ?? 0), 2);
        $total = round(max($subtotal - $discount, 0), 2);

        return [
            'invoice_number' => $validated['invoice_number'] ?? $this->generateInvoiceNumber(),
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'] ?? null,
            'client_phone' => $validated['client_phone'] ?? null,
            'client_address' => $validated['client_address'] ?? null,
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'] ?? null,
            'status' => $validated['status'],
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'notes' => $validated['notes'] ?? null,
        ];
    }

    protected function generateInvoiceNumber(): string
    {
        do {
            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . random_int(1000, 9999);
        } while (Invoice::where('invoice_number', $invoiceNumber)->exists());

        return $invoiceNumber;
    }
}
