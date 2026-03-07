<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SettingApp;

class SettingAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        SettingApp::updateOrCreate([
            'id' => 1,
        ], [
            'brand' => 'Base App Template Admin',
            'thumbnail' => 'Base App',
            'logo' => null,
        ]);
    }
}
