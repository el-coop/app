<?php

namespace Tests\Feature;

use App\Models\InvoiceSetting;
use App\Models\ScheduledAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class UserControllerTest extends TestCase {

    use CreatesUsers;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    protected $developer;


    protected function setUp():void  {
        parent::setUp();

        $this->developer = $this->getDeveloper();
    }

    public function test_guest_cant_get_user_data() {
        $this->get(action('UserController@show'))->assertRedirect('/');
    }

    public function test_developer_get_own_data() {
        $action = ScheduledAction::factory()->create([
            'user_id' => $this->developer->id
        ]);
        $invoiceSetting = InvoiceSetting::factory()->create([
            'user_id' => $this->developer->id
        ]);

        $this->actingAs($this->developer)->ajaxGet(action('UserController@show'))->assertJson([
            'scheduledActions' => [
                $action->action => $action->frequency
            ],
            'invoiceSettings' => [
                $invoiceSetting->key => $invoiceSetting->value
            ]
        ]);
    }

    public function test_guest_cant_update_scheduled_action() {
        $this->ajaxPatch(action('UserController@updateScheduledAction'))->assertRedirect('/');
    }

    public function test_developer_can_delete_schedule_action() {
        ScheduledAction::factory()->create([
            'user_id' => $this->developer->id
        ]);

        $this->actingAs($this->developer)->ajaxPatch(action('UserController@updateScheduledAction'),[
            'action' => 'backupDatabase'
        ])->assertSuccessful();

        $this->assertDatabaseMissing('scheduled_actions',[
            'user_id' => $this->developer->id,
            'action' => 'backupDatabase'
        ]);
    }

    public function test_developer_can_update_schedule_action() {
        ScheduledAction::factory()->create([
            'user_id' => $this->developer->id
        ]);

        $this->actingAs($this->developer)->ajaxPatch(action('UserController@updateScheduledAction'),[
            'action' => 'backupDatabase',
            'frequency' => '* 1 1 1 1'
        ])->assertSuccessful();

        $this->assertDatabaseHas('scheduled_actions',[
            'user_id' => $this->developer->id,
            'action' => 'backupDatabase',
            'frequency' => '* 1 1 1 1'
        ]);
    }

    public function test_developer_can_create_schedule_action() {

        $this->actingAs($this->developer)->ajaxPatch(action('UserController@updateScheduledAction'),[
            'action' => 'backupDatabase',
            'frequency' => '* 1 1 1 1'
        ])->assertSuccessful();

        $this->assertDatabaseHas('scheduled_actions',[
            'user_id' => $this->developer->id,
            'action' => 'backupDatabase',
            'frequency' => '* 1 1 1 1'
        ]);
    }

    public function test_update_scheduled_action_validation() {
        $this->actingAs($this->developer)->ajaxPatch(action('UserController@updateScheduledAction'),[
            'action' => '',
            'frequency' => 'asd'
        ])->assertSessionHasErrors(['action','frequency']);

    }

    public function test_developer_can_create_invoice_setting() {
        $this->actingAs($this->developer)->ajaxPatch(action('UserController@updateInvoiceSettings'),[
            'from' => 'test',
            'nextInvoice' => '2023-01'
        ])->assertSuccessful();

        $this->assertDatabaseHas('invoice_settings',[
            'user_id' => $this->developer->id,
            'key' => 'from',
            'value' => 'test'
        ]);

        $this->assertDatabaseHas('invoice_settings',[
            'user_id' => $this->developer->id,
            'key' => 'nextInvoice',
            'value' => '2023-01'
        ]);
    }

    public function test_developer_can_update_invoice_setting() {
        InvoiceSetting::factory()->create([
            'user_id' => $this->developer->id,
            'key' => 'from',
        ]);


        InvoiceSetting::factory()->create([
            'user_id' => $this->developer->id,
            'key' => 'nextInvoice',
        ]);

        $this->actingAs($this->developer)->ajaxPatch(action('UserController@updateInvoiceSettings'),[
            'from' => 'test',
            'nextInvoice' => '2023-01'
        ])->assertSuccessful();

        $this->assertDatabaseHas('invoice_settings',[
            'user_id' => $this->developer->id,
            'key' => 'from',
            'value' => 'test'
        ]);

        $this->assertDatabaseHas('invoice_settings',[
            'user_id' => $this->developer->id,
            'key' => 'nextInvoice',
            'value' => '2023-01'
        ]);
    }
}
