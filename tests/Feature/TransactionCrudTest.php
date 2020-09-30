<?php

namespace Tests\Feature;

use App\Models\Entity;
use App\Models\Transaction;
use App\Models\TransactionAttachment;
use App\Services\CurrencyConverter;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Storage;
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
        $this->entity = Entity::factory()->create();
        $this->transaction = Transaction::factory()->create([
            'entity_id' => $this->entity
        ]);
    }

    public function test_guest_cant_create_transaction() {
        $this->ajaxPost(action('TransactionController@store'))->assertRedirect('/');
    }

    public function test_developer_can_create_transaction() {
        $this->withoutExceptionHandling();
        Storage::fake();
        $files = [
            UploadedFile::fake()->image('file.doc'),
            UploadedFile::fake()->image('file1.pdf')
        ];

        $this->actingAs($this->developer)->ajaxPost(action('TransactionController@store'), [
            'entity' => $this->entity->id,
            'reason' => 'reason',
            'date' => Carbon::now()->toDateString(),
            'amount' => 10,
            'currency' => '€',
            'rate' => '3.7',
            'comment' => 'payment',
            'files' => $files
        ])->assertCreated();

        $this->assertDatabaseHas('transactions', [
            'entity_id' => $this->entity->id,
            'reason' => 'reason',
            'amount' => 10,
            'currency' => '€',
            'rate' => '3.7',
            'comment' => 'payment',
        ]);

        foreach ($files as $file) {
            Storage::assertExists("transactionAttachments/{$file->hashName()}");

            $this->assertDatabaseHas('transaction_attachments', [
                'name' => $file->name,
                'path' => $file->hashName()
            ]);
        }

    }

    public function test_automatically_calculates_rate_on_create_if_not_given() {
        $this->mock(CurrencyConverter::class, function($mock) {
            $mock->shouldReceive('getRate')->once()->with('€')->andReturn(3.6);
        });

        $this->actingAs($this->developer)->ajaxPost(action('TransactionController@store'), [
            'entity' => $this->entity->id,
            'reason' => 'reason',
            'date' => Carbon::now()->toDateString(),
            'amount' => 10,
            'currency' => '€',
            'comment' => 'payment',
            'files' => []
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
        Storage::fake();
        Storage::put('transactionAttachments/file1', ' ');
        Storage::put('transactionAttachments/file2', ' ');
        Storage::put('transactionAttachments/file3', ' ');
        $file = UploadedFile::fake()->image('file.doc');

        $attachment1 = TransactionAttachment::factory()->create([
            'transaction_id' => $this->transaction->id,
            'path' => 'file1'
        ]);
        $attachment2 = TransactionAttachment::factory()->create([
            'transaction_id' => $this->transaction->id,
            'path' => 'file2'
        ]);


        $attachment3 = TransactionAttachment::factory()->create([
            'path' => 'file2'
        ]);

        $this->actingAs($this->developer)->ajaxPatch(action('TransactionController@update', $this->transaction), [
            'entity' => $this->entity->id,
            'reason' => 'reason',
            'date' => Carbon::now()->toDateString(),
            'amount' => 10,
            'currency' => '€',
            'rate' => '3.7',
            'comment' => 'payment',
            'attachments' => [[
                'id' => $attachment1->id,
                'checked' => "true"
            ], [
                'id' => $attachment2->id,
                'checked' => "false"
            ], [
                'id' => $attachment3->id,
                'checked' => "false"
            ]],
            'files' => [
                $file
            ]

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

        $this->assertDatabaseHas('transaction_attachments', [
            'transaction_id' => $this->transaction->id,
            'name' => $file->getClientOriginalName()
        ]);

        $this->assertDatabaseHas('transaction_attachments', [
            'id' => $attachment1->id,
        ]);

        $this->assertDatabaseHas('transaction_attachments', [
            'id' => $attachment3->id,
        ]);


        $this->assertDatabaseMissing('transaction_attachments', [
            'transaction_id' => $this->transaction->id,
            'id' => $attachment2
        ]);

        Storage::assertExists("transactionAttachments/{$file->hashName()}");
        Storage::assertExists("transactionAttachments/file3");
        Storage::assertMissing('transactionAttachments/file2');
    }

    public function test_automatically_calculates_rate_on_edit_if_not_given() {
        $this->mock(CurrencyConverter::class, function($mock) {
            $mock->shouldReceive('getRate')->once()->with('€')->andReturn(3.6);
        });

        $this->actingAs($this->developer)->ajaxPatch(action('TransactionController@update', $this->transaction), [
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
        $this->actingAs($this->developer)->ajaxPatch(action('TransactionController@update', $this->transaction), [
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
        Storage::fake();
        Storage::put('transactionAttachments/file1', ' ');
        Storage::put('transactionAttachments/file2', ' ');

        $attachment1 = TransactionAttachment::factory()->create([
            'transaction_id' => $this->transaction->id,
            'path' => 'file1'
        ]);
        $attachment2 = TransactionAttachment::factory()->create([
            'transaction_id' => $this->transaction->id,
            'path' => 'file2'
        ]);

        $this->actingAs($this->developer)->ajaxDelete(action('TransactionController@destroy', $this->transaction))->assertSuccessful();

        $this->assertDatabaseMissing('transactions', [
            'id' => $this->transaction->id
        ]);
        $this->assertDatabaseMissing('transaction_attachments', [
            'id' => $attachment1->id
        ]);
        $this->assertDatabaseMissing('transaction_attachments', [
            'id' => $attachment2->id
        ]);

        Storage::assertMissing('transactionAttachments/file1');
        Storage::assertMissing('transactionAttachments/file2');
    }

}
