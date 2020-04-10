<?php

namespace Tests\Unit;

use App\Services\CurrencyConverter;
use Illuminate\Support\Facades\Cache;
use Tests\CreatesApplication;
use Tests\TestCase;

class CurrencyConverterTest extends TestCase {

    public function test_gets_rates_from_cache() {

        Cache::shouldReceive('remember')->once()->andReturn((object)[
            'EUR' => '3.6'
        ]);

        $converter = new CurrencyConverter;
        $this->assertEquals(round(1 / 3.6, 2), $converter->getRate('€'));
    }

    public function test_gets_rates_from_memory_when_already_found_once() {

        Cache::shouldReceive('remember')->once()->andReturn((object)[
            'EUR' => '3.6'
        ]);

        $converter = new CurrencyConverter;
        $this->assertEquals(round(1 / 3.6, 2), $converter->getRate('€'));
        $this->assertEquals(round(1 / 3.6, 2), $converter->getRate('€'));
    }
}
