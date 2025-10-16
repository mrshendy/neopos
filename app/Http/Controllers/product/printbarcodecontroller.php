<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class printbarcodecontroller extends Controller
{
    /**
     * GET: يستقبل payload (Base64 JSON) من الـ URL
     */
    public function show(?string $payload = null)
    {
        $items = [];

        if ($payload) {
            try {
                $decoded = base64_decode($payload, true);
                if ($decoded !== false) {
                    $data = json_decode($decoded, true, 512, JSON_THROW_ON_ERROR);
                    $items = is_array($data['items'] ?? null) ? $data['items'] : [];
                }
            } catch (\Throwable $e) {
                $items = [];
            }
        }

        if (empty($items)) {
            return redirect()->route('product.index')->with('success', __('pos.no_data') ?: 'لا توجد بيانات للطباعة');
        }

        return view('product.barcodes.print', compact('items'));
    }

    /**
     * POST: يستقبل JSON مباشر (بديل لو عنوان GET طويل)
     */
    public function showPost(Request $request)
    {
        $items = $request->input('items', []);
        if (!is_array($items) || empty($items)) {
            return redirect()->route('product.index')->with('success', __('pos.no_data') ?: 'لا توجد بيانات للطباعة');
        }
        return view('product.barcodes.print', compact('items'));
    }
}
