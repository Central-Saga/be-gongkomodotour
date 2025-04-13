<?php
// filepath: /c:/laragon/www/be-gongkomodotour/database/factories/TestimonialFactory.php

namespace Database\Factories;

use App\Models\Customers;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Trips;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
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

        $tripName = $this->faker->randomElement($tripNames);

        $reviews = [
            "Perjalanan yang luar biasa! {$tripName} memberikan pengalaman yang tak terlupakan. Pemandu wisata sangat informatif dan ramah.",
            "{$tripName} adalah perjalanan terbaik yang pernah saya lakukan. Pemandangan alamnya menakjubkan dan akomodasinya nyaman.",
            "Saya sangat merekomendasikan {$tripName}. Makanannya enak, transportasinya nyaman, dan jadwal perjalanannya sangat terorganisir.",
            "Pengalaman yang luar biasa di {$tripName}. Tim tour guide sangat profesional dan membantu dalam setiap situasi.",
            "{$tripName} memberikan pengalaman budaya yang mendalam. Saya belajar banyak tentang sejarah dan tradisi lokal.",
            "Perjalanan yang sempurna! {$tripName} memiliki kombinasi yang tepat antara petualangan dan relaksasi.",
            "{$tripName} adalah perjalanan impian saya. Setiap momennya berharga dan tak terlupakan.",
            "Saya terkesan dengan profesionalisme tim {$tripName}. Semuanya berjalan sesuai rencana dan sangat menyenangkan.",
            "{$tripName} memberikan pengalaman yang autentik. Saya merasa benar-benar terhubung dengan budaya lokal.",
            "Perjalanan yang sangat direkomendasikan! {$tripName} memiliki nilai yang sangat baik untuk uang yang dikeluarkan."
        ];

        return [
            'customer_id'  => Customers::inRandomOrder()->value('id') ?? Customers::factory(),
            'trip_id'      => Trips::inRandomOrder()->value('id') ?? Trips::factory(),
            'rating'       => $this->faker->numberBetween(4, 5), // Biasakan rating tinggi untuk testimonial yang ditampilkan
            'review'       => $this->faker->randomElement($reviews),
            'is_approved'  => true, // Semua testimonial disetujui
            'is_highlight' => $this->faker->boolean(20), // 20% chance untuk menjadi highlight
        ];
    }
}
