<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class Blood_groupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blood_group')->insert([
            [
		'name' => 'O',
		],
		
		[
		'name' => 'O-',
		],
		[
		'name' => 'A',
		],
		[
		'name' => 'A-',
		],
		[
		'name' => 'B',
		],
		[
		'name' => 'B-',
		],
		[
		'name' => 'AB',
		],
		[
		'name' => 'AB-',
		]
		]);
		

    }
}
