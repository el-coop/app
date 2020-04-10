<?php

namespace Tests\Feature;

use App\Models\Entity;
use App\Models\Transaction;
use App\Services\CurrencyConverter;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class TransactionCrudTest extends TestCase {

    use CreatesUsers;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    protected $developer;
    protected $entity;
    protected $transaction;

    protected function setUp(): void {
        parent::setUp();
        $this->developer = $this->getDeveloper();
        $this->entity = factory(Entity::class)->create();
        $this->transaction = factory(Transaction::class)->create([
            'entity_id' => $this->entity
        ]);
    }

    public function test_guest_cant_create_transaction() {
        $this->ajaxPost(action('TransactionController@store'))->assertRedirect('/');
    }

    public function test_developer_can_create_transaction() {
        $this->actingAs($this->developer)->ajaxPost(action('TransactionController@store'), [
            'entity' => $this->entity->id,
            'reason' => 'reason',
            'date' => Carbon::now()->toDateString(),
            'amount' => 10,
            'currency' => '€',
            'rate' => '3.7',
            'comment' => 'payment',
        ])->assertCreated();

        $this->assertDatabaseHas('transactions', [
            'entity_id' => $this->entity->id,
            'reason' => 'reason',
            'amount' => 10,
            'currency' => '€',
            'rate' => '3.7',
            'comment' => 'payment',
        ]);
    }

    public function test_automatically_calculates_rate_on_create_if_not_given() {
        $this->mock(CurrencyConverter::class, function ($mock) {
            $mock->shouldReceive('getRate')->once()->with('€')->andReturn(3.6);
        });

        $this->actingAs($this->developer)->ajaxPost(action('TransactionController@store'), [
            'entity' => $this->entity->id,
            'reason' => 'reason',
            'date' => Carbon::now()->toDateString(),
            'amount' => 10,
            'currency' => '€',
            'comment' => 'payment',
        ])->assertCreated();

        $this->assertDatabaseHas('transactions', [
            'entity_id' => $this->entity->id,
            'reason' => 'reason',
            'amount' => 10,
            'currency' => '€',
            'rate' => '3.6',
            'comment' => 'payment',
        ]);
    }

    public function test_create_transaction_validation() {
        $this->actingAs($this->developer)->ajaxPost(action('TransactionController@store'), [
            'entity' => '',
            'reason' => '',
            'date' => '',
            'amount' => '',
            'currency' => '',
        ])->assertRedirect()->assertSessionHasErrors([
            'entity',
            'reason',
            'date',
            'amount',
            'currency',
        ]);
    }

    public function test_guest_cant_edit_transaction() {
        $this->ajaxPatch(action('TransactionController@update', $this->transaction))->assertRedirect('/');
    }

    public function test_developer_can_edit_transaction() {
        $this->actingAs($this->developer)->ajaxPatch(action('TransactionController@update', $this->transaction), [
            'entity' => $this->entity->id,
            'reason' => 'reason',
            'date' => Carbon::now()->toDateString(),
            'amount' => 10,
            'currency' => '€',
            'rate' => '3.7',
            'comment' => 'payment',
        ])->assertSuccessful();

        $this->assertDatabaseHas('transactions', [
            'id' => $this->transaction->id,
            'entity_id' => $this->entity->id,
            'reason' => 'reason',
            'amount' => 10,
            'currency' => '€',
            'rate' => '3.7',
            'comment' => 'payment',
        ]);
    }

    public function test_automatically_calculates_rate_on_edit_if_not_given() {
        $this->mock(CurrencyConverter::class, function ($mock) {
            $mock->shouldReceive('getRate')->once()->with('€')->andReturn(3.6);
        });

        $this->actingAs($this->developer)->ajaxPatch(action('TransactionController@update',$this->transaction), [
            'entity' => $this->entity->id,
            'reason' => 'reason',
            'date' => Carbon::now()->toDateString(),
            'amount' => 10,
            'currency' => '€',
            'comment' => 'payment',
        ])->assertSuccessful();

        $this->assertDatabaseHas('transactions', [
            'id' => $this->transaction->id,
            'entity_id' => $this->entity->id,
            'reason' => 'reason',
            'amount' => 10,
            'currency' => '€',
            'rate' => '3.6',
            'comment' => 'payment',
        ]);
    }

    public function test_edit_transaction_validation() {
        $this->actingAs($this->developer)->ajaxPatch(action('TransactionController@update',$this->transaction), [
            'entity' => '',
            'reason' => '',
            'date' => '',
            'amount' => '',
            'currency' => '',
        ])->assertRedirect()->assertSessionHasErrors([
            'entity',
            'reason',
            'date',
            'amount',
            'currency',
        ]);
    }


    public function test_guest_cant_delete_transaction() {
        $this->ajaxDelete(action('TransactionController@destroy', $this->transaction))->assertRedirect('/');
    }

    public function test_developer_can_delete_transaction() {
        $this->actingAs($this->developer)->ajaxDelete(action('TransactionController@destroy', $this->transaction))->assertSuccessful();

        $this->assertDatabaseMissing('transactions',[
           'id' => $this->transaction->id
        ]);
    }

}
