<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectError;
use Illuminate\Database\Seeder;

class ProjectErrorSeeder extends Seeder {
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run() {
        ProjectError::factory()->count(20)->make()->each(function ($projectError) {
            Project::inRandomOrder()->first()->errors()->save($projectError);
        });
    }
}
