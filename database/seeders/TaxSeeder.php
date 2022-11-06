<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taxes = array('GST', 'SST');
        for($i = 0; $i < 2; $i++)
        {
            $tax = Tax::create([
                'name' => $taxes[$i],
                'percentage' => 5,
            ]);
        }
    }
}
