<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FlightSchedule;

class FlightScheduleFactory extends Factory
{
    protected $model = FlightSchedule::class;

    public function definition(): array
    {
        $airports = [
            'DPS' => 'Ngurah Rai Airport',
            'LOP' => 'Lombok International Airport',
            'JOG' => 'Adisucipto Airport',
            'PDG' => 'Minangkabau Airport',
            'CGK' => 'Soekarno-Hatta Airport',
            'KDI' => 'Haluoleo Airport',
            'DJJ' => 'Sentani Airport',
            'AMQ' => 'Pattimura Airport',
            'BPN' => 'Sepinggan Airport',
            'KOE' => 'El Tari Airport',
        ];

        $departure = $this->faker->randomElement(array_keys($airports));
        $arrival = $this->faker->randomElement(array_diff(array_keys($airports), [$departure]));
        $etdTime = $this->faker->time('H:i:00', '09:00:00');
        $etaTime = $this->faker->time('H:i:00', '16:00:00');

        return [
            'trip_id' => null, // Will be set by relationship
            'route' => "$departure - $arrival",
            'eta_time' => $etaTime,
            'eta_text' => "Arrive at $arrival at $etaTime",
            'etd_time' => $etdTime,
            'etd_text' => "Depart from $departure at $etdTime",
        ];
    }
}
