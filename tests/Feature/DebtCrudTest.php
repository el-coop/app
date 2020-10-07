<?php

namespace Tests\Feature;

use App\Models\Debt;
use App\Models\Entity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class DebtCrudTest extends TestCase {

    use CreatesUsers;

    protected $developer;
    protected $entity;
    protected $debt;

    protected function setUp(): void {
        parent::setUp();
        $this->developer = $this->getDeveloper();
        $this->entity = Entity::factory()->create();
        $this->debt = Debt::factory()->create();
    }


    public function test_guest_cant_see_debt_index() {
        $this->ajaxGet(action('DebtController@index'))->assertRedirect('/');
    }

    public function test_developer_can_see_debt_index() {
        $debts = Debt::factory()->count(10)->create();

        $response = $this->actingAs($this->developer)->ajaxGet(action('DebtController@index'))->assertSuccessful();

        $debts->each(fn($debt) => $response->assertJsonFragment([
            'id' => $debt->id,
            'amount' => number_format($debt->amount, 2, '.', '')
        ]));
    }

    public function test_guest_cant_create_debt() {
        $this->ajaxPost(action('DebtController@store'))->assertRedirect('/');
    }

    public function test_developer_can_create_debt() {
        $this->actingAs($this->developer)->ajaxPost(action('DebtController@store'), [
            'entity' => $this->entity->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'currency' => '$',
            'type' => 'fixed',
            'amount' => 1000,
        ])->assertCreated();

        $this->assertDatabaseHas('debts', [
            'entity_id' => $this->entity->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'currency' => '$',
            'type' => 'fixed',
            'amount' => 1000,
        ]);
    }

    public function test_create_entity_validation() {
        $this->actingAs($this->developer)->ajaxPost(action('DebtController@store'), [
            'invoiced' => 'haba'
        ])->assertRedirect()
            ->assertSessionHasErrors(['entity', 'date', 'currency', 'type', 'amount', 'invoiced']);
    }

    public function test_guest_cant_edit_debt() {
        $this->ajaxPatch(action('DebtController@update', $this->debt))->assertRedirect('/');
    }

    public function test_developer_can_edit_debt() {
        $this->actingAs($this->developer)->ajaxPatch(action('DebtController@update', $this->debt), [
            'entity' => $this->entity->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'currency' => '$',
            'type' => 'fixed',
            'amount' => 1000,
        ])->assertSuccessful();

        $this->assertDatabaseHas('debts', [
            'id' => $this->debt->id,
            'entity_id' => $this->entity->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'currency' => '$',
            'type' => 'fixed',
            'amount' => 1000,
        ]);
    }

    public function test_edit_debt_validation() {
        $this->actingAs($this->developer)->ajaxPatch(action('DebtController@update', $this->debt), [
            'invoiced' => 'haba'
        ])->assertRedirect()
            ->assertSessionHasErrors(['entity', 'date', 'currency', 'type', 'amount', 'invoiced']);
    }

    public function test_guest_cant_delete_debt() {
        $this->ajaxDelete(action('DebtController@destroy', $this->debt))->assertRedirect('/');
    }

    public function test_developer_can_delete_debt() {
        $this->actingAs($this->developer)->ajaxDelete(action('DebtController@update', $this->debt))->assertSuccessful();

        $this->assertDatabaseMissing('debts',[
            'id' => $this->debt->id
        ]);
    }
}
