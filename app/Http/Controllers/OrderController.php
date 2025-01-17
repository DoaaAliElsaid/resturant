<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Meal;
use App\Models\OrderDetail;
use Exception;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // if (!auth()->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        try {
            $validated = $request->validate([
                'reservation_id' => 'required|exists:reservations,id',
                'table_id' => 'required|exists:tables,id',
                'customer_id' => 'required|exists:customers,id',
                'user_id' => 'required|exists:users,id',
                'order_details' => 'required|array',
                'order_details.*.meal_id' => 'required|exists:meals,id',
                'order_details.*.quantity' => 'required|integer|min:1',
            ]);

            $order = Order::create([
                'reservation_id' => $validated['reservation_id'],
                'table_id' => $validated['table_id'],
                'customer_id' => $validated['customer_id'],
                'user_id' => $validated['user_id'], // Use the authenticated user's ID
                'date' => now(),
                'total' => 0,
                'paid' => false,
            ]);

            $total = 0;

            foreach ($validated['order_details'] as $detail) {
                $meal = Meal::find($detail['meal_id']);
                $amountToPay = $meal->price * (1 - $meal->discount / 100) * $detail['quantity'];

                OrderDetail::create([
                    'order_id' => $order->id,
                    'meal_id' => $meal->id,
                    'amount_to_pay' => $amountToPay,
                ]);

                $total += $amountToPay;
            }

            $order->update(['total' => $total]);

            return response()->json([
                'order_id' => $order->id,
                'total' => $total,
            ], 201);
        }catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to place order',
                'message' => $e->getMessage(),
            ], 500);
        }
    }  
}
