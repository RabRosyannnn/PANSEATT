<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class UpdateBookingStatus extends Command
{
    protected $signature = 'bookings:update-status';
    protected $description = 'Update booking confirmation status for past reservations';

    public function handle()
    {
        $this->info('Starting to update booking statuses...');

        // Get all reservations where:
        // 1. End time has passed
        // 2. Current booking_confirmation is 'confirmed'
        // 3. Not already marked as complete
        $reservations = Reservation::whereRaw("CONCAT(date, ' ', end_time) < ?", [now()])
            ->where('booking_confirmation', '=', 'confirmed')
            ->get();

        $updatedCount = 0;
        foreach ($reservations as $reservation) {
            $reservation->update([
                'booking_confirmation' => 'complete'
            ]);

            $updatedCount++;
            $this->info("Updated reservation #{$reservation->id} for {$reservation->customer_name}");
        }

        $this->info("Finished updating booking statuses. Updated {$updatedCount} reservations.");
    }
}