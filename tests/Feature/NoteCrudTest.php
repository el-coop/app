<?php

namespace Tests\Feature;

use App\Models\Entity;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class NoteCrudTest extends TestCase {

    use CreatesUsers;

    protected $developer;
    protected $entity;
    protected $note;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected function setUp(): void {
        parent::setUp();
        $this->developer = $this->getDeveloper();
        $this->entity = Entity::factory()->create();
        $this->note = Note::factory()->make();
        $this->entity->notes()->save($this->note);
    }

    public function test_guest_cant_create_note() {
        $this->ajaxPost(action('NoteController@store', $this->entity))->assertRedirect('/');
    }

    public function test_developer_can_create_note() {
        $this->actingAs($this->developer)->ajaxPost(action('NoteController@store', $this->entity), [
            'title' => 'test',
            'content' => 'test content',
            'x' => 10,
            'y' => 20,
        ])->assertCreated();

        $this->assertDatabaseHas('notes', [
            'title' => 'test',
            'content' => 'test content',
            'x' => 10,
            'y' => 20,
            'entity_id' => $this->entity->id
        ]);
    }

    public function test_create_note_validation() {
        $this->actingAs($this->developer)->ajaxPost(action('NoteController@store', $this->entity), [])
            ->assertRedirect()
            ->assertSessionHasErrors(['title', 'content', 'x', 'y']);
    }

    public function test_guest_cant_edit_note() {
        $this->ajaxPatch(action('NoteController@update', [
            'entity' => $this->entity,
            'note' => $this->note,
        ]))->assertRedirect('/');
    }

    public function test_developer_can_edit_note() {
        $this->actingAs($this->developer)->ajaxPatch(action('NoteController@update', [
            'entity' => $this->entity,
            'note' => $this->note,
            'x' => $this->note->x,
            'y' => $this->note->y,
        ]), [
            'title' => 'crest',
            'content' => 'bontent'
        ])->assertSuccessful();

        $this->assertDatabaseHas('notes', [
            'title' => 'crest',
            'content' => 'bontent',
            'entity_id' => $this->entity->id,
            'id' => $this->note->id,
            'x' => $this->note->x,
            'y' => $this->note->y,
        ]);
    }

    public function test_edit_note_validation() {
        $this->actingAs($this->developer)->ajaxPatch(action('NoteController@update', [
            'entity' => $this->entity,
            'note' => $this->note,
        ]),[
        ])->assertRedirect()->assertSessionHasErrors(['title', 'content', 'x', 'y']);
    }

    public function test_guest_cant_delete_note() {
        $this->ajaxDelete(action('NoteController@destroy', [
            'entity' => $this->entity,
            'note' => $this->note,
        ]))->assertRedirect('/');
    }

    public function test_developer_can_delete_note() {
        $this->actingAs($this->developer)->ajaxDelete(action('NoteController@destroy', [
            'entity' => $this->entity,
            'note' => $this->note,
        ]))->assertSuccessful();

        $this->assertDatabaseMissing('notes',[
            'id' => $this->note->id
        ]);
    }
}
