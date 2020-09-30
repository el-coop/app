<?php

namespace Tests\Feature;

use App\Models\Entity;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class ProjectsCrudTest extends TestCase {

    use CreatesUsers;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    protected $developer;
    protected $entity;
    protected $project;

    protected function setUp(): void {
        parent::setUp();
        $this->developer = $this->getDeveloper();
        $this->entity = Entity::factory()->create();
        $this->project = Project::factory()->make();
        $this->entity->projects()->save($this->project);
    }

    public function test_guest_cant_create_project() {
        $this->ajaxPost(action('EntityProjectController@store', $this->entity))->assertRedirect('/');
    }

    public function test_developer_can_create_project() {
        $this->actingAs($this->developer)->ajaxPost(action('EntityProjectController@store', $this->entity),[
            'name' => 'test'
        ])->assertCreated();

        $this->assertDatabaseHas('projects',[
            'name' => 'test',
            'entity_id' => $this->entity->id
        ]);
    }

    public function test_create_project_validation() {
        $this->actingAs($this->developer)->ajaxPost(action('EntityProjectController@store', $this->entity),[
            'name' => ''
        ])->assertRedirect()->assertSessionHasErrors(['name']);
    }

    public function test_guest_cant_edit_project() {
        $this->ajaxPatch(action('EntityProjectController@update', [
            'entity' => $this->entity,
            'project' => $this->project,
        ]))->assertRedirect('/');
    }

    public function test_developer_can_edit_project() {
        $this->actingAs($this->developer)->ajaxPatch(action('EntityProjectController@update', [
            'entity' => $this->entity,
            'project' => $this->project,
        ]),[
            'name' => 'test'
        ])->assertSuccessful();

        $this->assertDatabaseHas('projects',[
            'name' => 'test',
            'entity_id' => $this->entity->id,
            'id' => $this->project->id
        ]);
    }

    public function test_edit_project_validation() {
        $this->actingAs($this->developer)->ajaxPatch(action('EntityProjectController@update', [
            'entity' => $this->entity,
            'project' => $this->project,
        ]),[
            'name' => ''
        ])->assertRedirect()->assertSessionHasErrors(['name']);
    }

    public function test_guest_cant_delete_project() {
        $this->ajaxDelete(action('EntityProjectController@destroy', [
            'entity' => $this->entity,
            'project' => $this->project,
        ]))->assertRedirect('/');
    }

    public function test_developer_can_delete_project() {
        $this->actingAs($this->developer)->ajaxDelete(action('EntityProjectController@update', [
            'entity' => $this->entity,
            'project' => $this->project,
        ]))->assertSuccessful();

        $this->assertDatabaseMissing('projects',[
            'id' => $this->project->id
        ]);
    }
}
