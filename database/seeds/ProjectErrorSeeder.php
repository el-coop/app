<?php

use App\Models\Project;
use App\Models\ProjectError;
use Illuminate\Database\Seeder;

class ProjectErrorSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        factory(ProjectError::class, 20)->make()->each(function ($projectError) {
            Project::inRandomOrder()->first()->errors()->save($projectError);
        });
    }
}
