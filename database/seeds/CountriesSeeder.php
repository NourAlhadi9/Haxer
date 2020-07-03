<?php

use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'countries.sql';
        $sql = file_get_contents($path);
        $sql = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($sql as $query) {
            DB::statement($query);
        }
    }
}
