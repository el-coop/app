<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class AjaxOrSpaMiddlewareTest extends TestCase {

    use CreatesUsers;


    public function test_returns_spa_vue_when_not_ajax() {

        $this->withoutExceptionHandling();
        $developer = $this->getDeveloper();

        $this->actingAs($developer)->get('/')
            ->assertStatus(200)
            ->assertSee('<div id="app" :class="`theme-${theme}`">', false);
    }

    public function test_returns_answer_when_ajax_call() {

        $developer = $this->getDeveloper();

        $this->actingAs($developer)->ajaxGet(action('EntityController@index'))
            ->assertStatus(200)
            ->assertJson([
                'entities' => []
            ]);
    }
}
