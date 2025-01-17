<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\Customer;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a reservation can be created successfully.
     *
     * @return void
     */
    public function test_reservation_can_be_created()
    {
        // Create a table and customer
        $table = Table::factory()->create(['capacity' => 4]);
        $customer = Customer::factory()->create();
    
        // Mock request data
        $data = [
            'table_id' => $table->id,
            'customer_id' => $customer->id,
            'from_time' => now()->addHour()->toDateTimeString(),
            'to_time' => now()->addHours(2)->toDateTimeString(),
        ];
    
        // Send POST request to create a reservation
        $response = $this->postJson('/api/reservations', $data);
       //dd($response->json());

       // Print response for debugging
   // dd($response->json());

    // Adjust assertion based on actual response structure
    $response->assertStatus(201)
             ->assertJson([
                 'reservation_id' => $response->json('reservation_id'), // Adjust if necessary
             ]);
        // Decode response to check reservation_id
        $responseData = $response->json();
        $reservationId = $responseData['reservation_id'];
    
        // Additionally, assert that the reservation exists in the database
        $this->assertDatabaseHas('reservations', [
            'id' => $reservationId,
            'table_id' => $table->id,
            'customer_id' => $customer->id,
            'from_time' => $data['from_time'],
            'to_time' => $data['to_time'],
        ]);
    }
    




    /**
     * Test that a reservation cannot be created with invalid data.
     *
     * @return void
     */
    public function test_reservation_cannot_be_created_with_invalid_data()
    {
        // Mock invalid request data
        $data = [
            'table_id' => null, // Invalid
            'customer_id' => null, // Invalid
            'from_time' => 'invalid-date',
            'to_time' => '2024-08-10 20:00:00',
        ];

        // Send POST request to create a reservation
        $response = $this->postJson('/api/reservations', $data);

        // Assert that the reservation creation failed
        $response->assertStatus(422);
        $this->assertDatabaseMissing('reservations', $data);
    }

    /**
     * Test that a reservation cannot be created if the table is already reserved.
     *
     * @return void
     */
    public function test_reservation_cannot_be_created_if_table_is_reserved()
    {
        // Create a table, customer, and a reservation
        $table = Table::factory()->create(['capacity' => 4]);
        $customer = Customer::factory()->create();

        Reservation::factory()->create([
            'table_id' => $table->id,
            'customer_id' => $customer->id,
            'from_time' => '2024-08-10 18:00:00',
            'to_time' => '2024-08-10 20:00:00',
        ]);

        // Mock request data for a conflicting reservation
        $data = [
            'table_id' => $table->id,
            'customer_id' => $customer->id,
            'from_time' => '2024-08-10 19:00:00', // Overlaps with existing reservation
            'to_time' => '2024-08-10 21:00:00',
        ];

        // Send POST request to create a reservation
        $response = $this->postJson('/api/reservations', $data);

        // Assert that the reservation creation failed
        $response->assertStatus(422);
        $this->assertDatabaseMissing('reservations', $data);
    }

}
