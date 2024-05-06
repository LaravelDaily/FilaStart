<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\User;
use App\Services\ModuleService;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@admin.com',
        ]);

        $modulesList = (new ModuleService())->listModules();

        foreach ($modulesList as $slug => $title) {

            Module::create([
                'title' => $title,
                'slug' => $slug,
            ]);

        }
    }
}
