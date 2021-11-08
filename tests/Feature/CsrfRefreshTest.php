<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CsrfRefreshTest extends TestCase {
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_get_an_issued_csrf_token() {
        $this->ajaxGet(action('HomeController@csrf'))->assertSuccessful()->assertJsonStructure(['csrfToken']);
    }
}
