<?php

namespace Tests\Feature;

use App\Models\Entity;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class TransactionViewTest extends TestCase {
    use CreatesUsers;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    protected $developer;
    protected $entity;
    protected $transactions;

    protected function setUp(): void {
        parent::setUp();
        $this->developer = $this->getDeveloper();
        $this->entity = Entity::factory()->create();
        $this->transactions = Transaction::factory( )->count(10)->create([
            'entity_id' => $this->entity->id
        ]);
    }

    public function test_guest_cant_see_transaction_index() {
        $this->ajaxGet(action('TransactionController@index'))->assertRedirect('/');
    }

    public function test_developer_can_see_transaction_index_for_default_time_period() {
        // Default period is one month
        $transactions = $this->transactions->where('date', '>=', Carbon::parse('- 1 month')->startOfDay());

        $response = $this->actingAs($this->developer)->ajaxGet(action('TransactionController@index'))->assertSuccessful();

        $transactions->each(fn($transaction) => $response->assertJsonFragment([
            'id' => $transaction->id,
            'entity' => $this->entity->id
        ]));
        $this->assertCount($transactions->count(), $response->json('transactions'));
    }

    public function test_developer_can_see_transaction_index_for_arbitrary_time_period() {
        $startDate = Carbon::parse('- 50 days');
        $endDate = Carbon::parse('- 10 days');
        $transactions = $this->transactions->where('date', '>=', $startDate->startOfDay())
            ->where('date', '<', $endDate->endOfDay());

        $response = $this->actingAs($this->developer)->ajaxGet(action('TransactionController@index', [
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]))->assertSuccessful();

        $transactions->each(fn($transaction) => $response->assertJsonFragment([
            'id' => $transaction->id,
            'entity' => $this->entity->id
        ]));

        $this->assertCount($transactions->count(), $response->json('transactions'));
    }

    public function test_guest_cant_see_transaction_total() {
        $this->ajaxGet(action('TransactionController@total'))->assertRedirect('/');
    }

    public function test_developer_can_see_transaction_total() {
        $response = $this->actingAs($this->developer)->ajaxGet(action('TransactionController@total'))->assertSuccessful();

        $this->assertEquals(
            number_format($this->transactions->sum(fn($transaction) => $transaction->rate * $transaction->amount), 2),
            number_format($response->json('total'), 2)
        );
    }

}
