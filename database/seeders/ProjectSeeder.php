<?php

namespace Database\Seeders;

use App\Models\Entity;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder {
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run() {
        Project::factory()->count(10)->make()->each(function ($project) {
            Entity::inRandomOrder()->first()->projects()->save($project);
        });
    }
}
