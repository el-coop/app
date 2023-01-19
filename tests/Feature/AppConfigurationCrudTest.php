<?php

namespace Tests\Feature;

use App\Models\AppConfiguration;
use App\Models\Entity;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class AppConfigurationCrudTest extends TestCase {

    use RefreshDatabase;
    use CreatesUsers;

    protected $developer;
    protected $appConfigurations;

    protected function setUp(): void {
        parent::setUp();
        $this->developer = $this->getDeveloper();
        $this->appConfigurations = AppConfiguration::factory()->count(10)->create();
    }

    public function test_guest_cant_see_configuration_index() {
        $this->ajaxGet(action('AppConfigurationController@index'))->assertRedirect('/');
    }


    public function test_developer_can_see_configuration_index() {
        $response = $this->actingAs($this->developer)
            ->ajaxGet(action('AppConfigurationController@index'))
            ->assertSuccessful();

        $this->appConfigurations->each(fn($appConfiguration) => $response->assertJsonFragment([
            'id' => $appConfiguration->id,
            'key' => $appConfiguration->key,
            'value' => $appConfiguration->value,
        ]));
        $this->assertCount($this->appConfigurations->count(), $response->json('configurations'));
    }

    public function test_guest_cant_see_individual_config() {
        $this->ajaxGet(action('AppConfigurationController@get', 'key'))
            ->assertRedirect('/');
    }

    public function test_developer_can_see_individual_configuration() {
        $config = $this->appConfigurations->first();
        $response = $this->actingAs($this->developer)
            ->ajaxGet(action('AppConfigurationController@get', $config->key))
            ->assertSuccessful();

        $response->assertJsonFragment([
            'id' => $config->id,
            'key' => $config->key,
            'value' => $config->value,
        ]);
    }

    public function test_guest_cant_create_configuration() {
        $this->ajaxPost(action('AppConfigurationController@store'))->assertRedirect('/');
    }


    public function test_developer_can_create_configuration() {
        $this->actingAs($this->developer)->ajaxPost(action('AppConfigurationController@store'), [
            'key' => 'key',
            'value' => 'value',
        ])->assertCreated();

        $this->assertDatabaseHas('app_configurations', [
            'key' => 'key',
            'value' => 'value',
        ]);

    }

    public function test_create_configuration_validation() {
        $this->actingAs($this->developer)->ajaxPost(action('AppConfigurationController@store'))
            ->assertRedirect()
            ->assertSessionHasErrors(['key', 'value']);
    }

    public function test_guest_cant_edit_configuration() {
        $config = $this->appConfigurations->first();
        $this->ajaxPatch(action('AppConfigurationController@update', $config))
            ->assertRedirect('/');
    }

    public function test_developer_can_edit_configuration() {
        $config = $this->appConfigurations->first();
        $this->actingAs($this->developer)->ajaxPatch(action('AppConfigurationController@update', $config), [
            'value' => 'value',
        ])->assertSuccessful();

        $this->assertDatabaseHas('app_configurations', [
            'id' => $config->id,
            'key' => $config->key,
            'value' => 'value',
        ]);
    }

    public function test_edit_configuration_validation() {
        $config = $this->appConfigurations->first();
        $this->actingAs($this->developer)->ajaxPatch(action('AppConfigurationController@update',$config))
            ->assertRedirect()
            ->assertSessionDoesntHaveErrors('key')
            ->assertSessionHasErrors(['value']);
    }

    public function test_guest_cant_delete_configuration() {
        $config = $this->appConfigurations->first();
        $this->ajaxDelete(action('AppConfigurationController@destroy', $config))
            ->assertRedirect('/');
    }

    public function test_developer_can_delete_configuration() {
        $config = $this->appConfigurations->first();
        $this->actingAs($this->developer)->ajaxDelete(action('AppConfigurationController@destroy', $config))
            ->assertSuccessful();

        $this->assertDatabaseMissing('app_configurations', [
            'id' => $config->id,
            'key' => $config->key,
        ]);

    }

}
