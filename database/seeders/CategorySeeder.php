<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = array('Appetizers', 'Asians', 'Pasta', 'Entrees', 'Sides', 'Cold Beverages', 'Hot Beverages', 'Fresh Juice', 'Milkshake');
        for($i = 0; $i < sizeof($categories); $i++)
        {
            if($i < 5){
                $category = Category::create([
                    'name' => $categories[$i],
                    'category' => 0,
                ]);
            }
            else{
                $category = Category::create([
                    'name' => $categories[$i],
                    'category' => 1,
                ]);
            }
        }
    }
}
