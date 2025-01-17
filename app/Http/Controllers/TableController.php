<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table; // Import the Table model
use App\Models\Reservation;

class TableController extends Controller
{
    //
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'from_time' => 'required|date',
            'to_time' => 'required|date',
            'guests' => 'required|integer',
        ]);

        $availableTable = Table::where('capacity', '>=', $validated['guests'])
            ->whereDoesntHave('reservations', function ($query) use ($validated) {
                $query->where('from_time', '<=', $validated['to_time'])
                    ->where('to_time', '>=', $validated['from_time']);
            })->first();

        return response()->json([
            'available' => $availableTable !== null,
            'table_id' => $availableTable ? $availableTable->id : null,
        ]);
    }

}
