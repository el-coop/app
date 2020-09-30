<?php

namespace Tests\Feature;

use App\Models\Entity;
use App\Models\Project;
use App\Models\ProjectError;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class ProjectErrorsCrudTest extends TestCase {
    use CreatesUsers;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    protected $developer;
    protected $entity;
    protected $project;
    protected $projectError;

    protected function setUp(): void {
        parent::setUp();
        $this->developer = $this->getDeveloper();
        $this->entity = Entity::factory()->create();
        $this->project = Project::factory()->make();
        $this->entity->projects()->save($this->project);
        $this->projectError = ProjectError::factory()->create([
            'project_id' => $this->project->id
        ]);
    }

    public function test_guest_cant_see_errors() {
        $this->ajaxGet(action('ProjectErrorController@index', $this->project))->assertRedirect('/');
    }

    public function test_developer_can_see_errors() {

        $errors = ProjectError::factory()->count(8)->create([
            'project_id' => $this->project->id
        ]);

        $response = $this->actingAs($this->developer)->ajaxGet(action('ProjectErrorController@index', $this->project))->assertSuccessful();

        $errors->each(fn($error) => $response->assertJsonFragment([
            'id' => $error->id,
            'type' => $error->type == 'serverSide' ? 'Server Side' : 'Client Side'
        ]));
    }

    public function test_guest_cant_delete_error() {
        $this->ajaxDelete(action('ProjectErrorController@destroy', [
            'project' => $this->project,
            'projectError' => $this->projectError
        ]))->assertRedirect('/');
    }

    public function test_developer_can_delete_error() {


        $this->actingAs($this->developer)->ajaxDelete(action('ProjectErrorController@destroy', [
            'project' => $this->project,
            'projectError' => $this->projectError
        ]))->assertSuccessful();

        $this->assertDatabaseMissing('project_errors',[
            'id' => $this->projectError->id
        ]);
    }

}
