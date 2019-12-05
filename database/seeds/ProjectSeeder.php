<?php

use App\Models\Entity;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        factory(Project::class, 10)->make()->each(function ($project) {
            Entity::inRandomOrder()->first()->projects()->save($project);
        });
    }
}
