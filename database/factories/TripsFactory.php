<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Trips;

class TripsFactory extends Factory
{
    protected $model = Trips::class;

    public function definition(): array
    {
        $tripNames = [
            'Bali Cultural Journey',
            'Lombok Coastal Adventure',
            'Yogyakarta Heritage Tour',
            'Sumatra Jungle Trek',
            'Java Volcano Expedition',
            'Sulawesi Diving Experience',
            'Papua Tribal Exploration',
            'Maluku Spice Island Tour',
            'Borneo Orangutan Safari',
            'Timor Historical Trail',
            'Raja Ampat Snorkeling Escape',
            'Flores Mountain Retreat'
        ];

        $meetingPoints = [
            'Ngurah Rai Airport (DPS)',
            'Lombok International Airport (LOP)',
            'Adisucipto Airport (JOG)',
            'Minangkabau Airport (PDG)',
            'Soekarno-Hatta Airport (CGK)',
            'Haluoleo Airport (KDI)',
            'Sentani Airport (DJJ)',
            'Pattimura Airport (AMQ)',
            'Sepinggan Airport (BPN)',
            'El Tari Airport (KOE)',
            'Juanda Airport (SUB)',
            'Frans Kaisiepo Airport (BIK)'
        ];

        $includes = [
            'Transportation, Local Guide, Entrance Fee, Hotel Accommodation, Meals, Insurance',
            'Flight Ticket, Snorkeling Equipment, Mineral Water, Local Guide, Transportation',
            'Hotel Accommodation, Meals, Entrance Fee, Cultural Workshop, Insurance',
            'Trekking Gear, Local Guide, Transportation, Camping Equipment, Meals'
        ];

        $excludes = [
            'Personal Expenses, Tipping, Soft Drinks & Alcohol',
            'Documentation Fee, Personal Insurance, Extra Activities',
            'Souvenirs, Additional Meals, Travel Insurance',
            'Tipping, Personal Expenses, Optional Tours'
        ];

        return [
            'name' => $this->faker->randomElement($tripNames),
            'include' => $this->faker->randomElement($includes),
            'exclude' => $this->faker->randomElement($excludes),
            'note' => 'Valid for Low Season; additional charges apply during High/Peak Seasons.',
            'region' => $this->faker->randomElement(['Domestic', 'Overseas', 'Domestic & Overseas']),
            'start_time' => $this->faker->randomElement(['07:00:00', '08:00:00', '09:00:00']),
            'end_time' => $this->faker->randomElement(['17:00:00', '18:00:00', '19:00:00']),
            'meeting_point' => $this->faker->randomElement($meetingPoints),
            'type' => $this->faker->randomElement(['Open Trip', 'Private Trip']),
            'status' => 'Aktif',
            'is_highlight' => 'No', // Default to 'No'
            'has_boat' => $this->faker->boolean(30), // 30% chance of having boat
            'has_hotel' => $this->faker->boolean(30), // 30% chance of having hotel
            'destination_count' => $this->faker->numberBetween(1, 5), // Random number between 1-5 destinations
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function highlighted()
    {
        return $this->state(['is_highlight' => 'Yes']);
    }

    public function withBoat()
    {
        return $this->state(['has_boat' => true]);
    }

    public function withoutBoat()
    {
        return $this->state(['has_boat' => false]);
    }

    public function withHotel()
    {
        return $this->state(['has_hotel' => true]);
    }

    public function withoutHotel()
    {
        return $this->state(['has_hotel' => false]);
    }

    public function withDestinationCount($count)
    {
        return $this->state(['destination_count' => $count]);
    }
}
