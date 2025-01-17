<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation; // Correctly imported

class ReservationController extends Controller
{
    //
    public function store(Request $request)
    {
        // if (!auth()->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $validatedData = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_id' => 'required|exists:customers,id',
            'from_time' => 'required|date',
            'to_time' => 'required|date|after:from_time',
        ]);
    
        // Check for overlapping reservations
        $conflict = Reservation::where('table_id', $validatedData['table_id'])
                                ->where(function ($query) use ($validatedData) {
                                    $query->whereBetween('from_time', [$validatedData['from_time'], $validatedData['to_time']])
                                          ->orWhereBetween('to_time', [$validatedData['from_time'], $validatedData['to_time']])
                                          ->orWhere(function ($query) use ($validatedData) {
                                              $query->where('from_time', '<=', $validatedData['from_time'])
                                                    ->where('to_time', '>=', $validatedData['to_time']);
                                          });
                                })->exists();
    
        if ($conflict) {
            return response()->json(['error' => 'Table is already reserved during this time.'], 422);
        }
    
        // Create the reservation
        $reservation = Reservation::create($validatedData);
    
        return response()->json(['reservation_id' => $reservation->id], 201);
    }    

}
