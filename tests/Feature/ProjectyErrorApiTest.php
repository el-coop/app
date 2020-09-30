<?php

namespace Tests\Feature;

use App\Models\Entity;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectyErrorApiTest extends TestCase {
    use RefreshDatabase;

    public function test_cant_save_errors_with_wrong_key() {
        $this->post(action('Api\ProjectErrorController@store', 'asd'))->assertNotFound();
    }

    public function test_cant_save_errors_with_no_key() {
        $this->post('/api/errors')->assertStatus(405);
    }

    public function test_can_save_error_with_correct_credentials() {
        $project = Project::factory()->create([
            'entity_id' => Entity::factory()->create()->id
        ]);

        $this->post(action('Api\ProjectErrorController@store', $project->token), [
            'type' => 'serverSide',
            'url' => 'https://test.test',
            'message' => 'message',
            'exception' => ["exception" => "bla"],
            'user' => null,
            'extra' => null,
        ])->assertSuccessful();

        $this->assertDatabaseHas('project_errors', [
            'project_id' => $project->id,
            'type' => 'serverSide',
            'url' => 'https://test.test',
            'message' => 'message',
            'user' => null,
            'extra_data' => null,
        ]);
    }
}
