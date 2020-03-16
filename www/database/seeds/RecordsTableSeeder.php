<?php

use App\Models\OrderPastel;
use Illuminate\Database\Seeder;

class RecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Calling just one seeder that trigger others
        factory(OrderPastel::class, 4)->create();
	}
}
