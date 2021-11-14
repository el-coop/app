<?php

namespace Tests\Feature;

use App\Models\Debt;
use App\Models\Entity;
use App\Models\Project;
use App\Models\Transaction;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class EntitiesCrudTest extends TestCase {

    use CreatesUsers;

    protected $developer;
    protected $entity;

    protected function setUp(): void {
        parent::setUp();
        $this->developer = $this->getDeveloper();
        $this->entity = Entity::factory()->create();
    }

    public function test_guest_cant_see_entity_index() {
        $this->ajaxGet(action('EntityController@index'))->assertRedirect('/');
    }

    public function test_developer_can_see_entity_index() {
        $entities = Entity::factory()->count(10)->create();

        $response = $this->actingAs($this->developer)->ajaxGet(action('EntityController@index'))->assertSuccessful();

        $entities->each(fn($entity) => $response->assertJsonFragment([
            'id' => $entity->id,
            'name' => $entity->name
        ]));
    }

    public function test_guest_cant_create_entity() {
        $this->ajaxPost(action('EntityController@store'))->assertRedirect('/');
    }

    public function test_developer_can_create_entity() {
        $this->actingAs($this->developer)->ajaxPost(action('EntityController@store'), [
            'name' => 'test'
        ])->assertCreated();

        $this->assertDatabaseHas('entities', [
            'name' => 'test'
        ]);
    }

    public function test_create_entity_validation() {
        $this->actingAs($this->developer)->ajaxPost(action('EntityController@store'))
            ->assertRedirect()
            ->assertSessionHasErrors(['name']);
    }


    public function test_guest_cant_edit_entity() {
        $this->ajaxPatch(action('EntityController@update', $this->entity))->assertRedirect('/');
    }

    public function test_developer_can_edit_entity() {
        $this->actingAs($this->developer)->ajaxPatch(action('EntityController@update', $this->entity), [
            'name' => 'test'
        ])->assertSuccessful();

        $this->assertDatabaseHas('entities', [
            'id' => $this->entity->id,
            'name' => 'test'
        ]);
    }

    public function test_edit_entity_validation() {
        $this->actingAs($this->developer)->ajaxPatch(action('EntityController@update', $this->entity))
            ->assertRedirect()
            ->assertSessionHasErrors(['name']);
    }


    public function test_guest_cant_delete_entity() {
        $this->ajaxDelete(action('EntityController@destroy', $this->entity))->assertRedirect('/');
    }

    public function test_developer_can_delete_entity() {
        $project = Project::factory()->create([
            'entity_id' => $this->entity->id
        ]);
        $debt = Debt::factory()->create([
            'entity_id' => $this->entity->id
        ]);

        $transaction = Transaction::factory()->create([
            'entity_id' => $this->entity->id
        ]);

        $this->actingAs($this->developer)
            ->ajaxDelete(action('EntityController@destroy', $this->entity))
            ->assertSuccessful();

        $this->assertDatabaseMissing('entities', [
            'id' => $this->entity->id
        ]);

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id
        ]);

        $this->assertDatabaseMissing('debts', [
            'id' => $debt->id
        ]);


        $this->assertDatabaseMissing('transactions', [
            'id' => $transaction->id
        ]);
    }

}
