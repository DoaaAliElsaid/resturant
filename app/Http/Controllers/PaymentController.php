<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PaymentController extends Controller
{
    //
    public function pay(Request $request, Order $order)
    {
        // if (!auth()->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $validated = $request->validate([
            'payment_method' => 'required|in:OPTION_1,OPTION_2',
        ]);

        $total = $order->total;

        switch ($validated['payment_method']) {
            case 'OPTION_1':
                $total += $total * 0.14 + $total * 0.20;
                break;
            case 'OPTION_2':
                $total += $total * 0.15;
                break;
        }

        $order->update(['paid' => true, 'total' => $total]);

        return response()->json([
            'invoice_id' => $order->id,
            'total_paid' => $total,
        ]);
    }

}
