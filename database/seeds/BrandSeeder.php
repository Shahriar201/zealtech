<?php

use App\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 10; $i++) {
            $name =Str::random(5);
            Brand::create([
                'name' => $name,
                'slug' => $name
            ]);
        }
    }
}
