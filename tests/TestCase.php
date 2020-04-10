<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
    use CreatesApplication;

    public function ajaxGet(string $uri, array $headers = []) {
        return $this->get($uri, array_merge($headers,
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        ));
    }

    protected function ajaxPost(string $uri, array $data = [], array $headers = []) {
        return $this->post($uri, $data, array_merge($headers,
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        ));
    }

    protected function ajaxPatch(string $uri, array $data = [], array $headers = []) {
        return $this->patch($uri, $data, array_merge($headers,
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        ));
    }

    protected function ajaxDelete(string $uri, array $data = [], array $headers = []) {
        return $this->delete($uri, $data, array_merge($headers,
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        ));
    }

}
