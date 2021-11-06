<?php

namespace Tests\Unit;

use App\Rules\Currency;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class CurrencyRuleTest extends TestCase {
    private Currency $rule;

    protected function setUp(): void {
        parent::setUp();

        $this->rule = new Currency;
    }

    public function test_it_passes_nis() {
        $this->assertTrue($this->rule->passes('test','₪'));
    }

    public function test_it_passes_usd() {
        $this->assertTrue($this->rule->passes('test','$'));
    }

    public function test_it_passes_eur() {
        $this->assertTrue($this->rule->passes('test','€'));
    }


    public function test_it_fails_random_string() {
        $this->assertFalse($this->rule->passes('test',Str::random(1)));
    }
}
