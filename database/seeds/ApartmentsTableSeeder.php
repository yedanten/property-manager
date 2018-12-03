<?php

use Illuminate\Database\Seeder;
use App\Apartment;

class ApartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 5; $i++) { 
            for ($j = 1; $j < 6; $j++) { 
                for ($k = 1; $k < 19; $k++) {
                    for ($l = 1; $l < 6; $l++) { 
                        $aprtment = new Apartment;
                        $aprtment->name = $i.'区';
                        $aprtment->unit = $j;
                        $aprtment->doorplate = $k *  100 + $l;
                        $aprtment->save();
                    }
                }
            }
        }
    }
}
