<?php


namespace App\Services;


use Http;

class CurrencyConverter {
    private $url;
    private $rates;

    public function __construct() {
        $this->url = config('services.currencyConverter.url','http://example.dev');
    }

    public function getRate($currency) {
        switch ($currency) {
            case '₪':
                return 1;
            case '€':
                $currency = 'EUR';
                break;
            default:
                $currency = 'USD';
                break;
        }

        if (!isset($this->rates->{$currency})) {
            $this->getRates();
        }

        return round(1 / $this->rates->{$currency}, 2);
    }

    private function getRates() {
        $this->rates = \Cache::remember('currency_rates', 60 * 60, function () {
            $response = Http::get($this->url);

            if ($response->failed()) {
                throw new \Exception('Conversion error');
            }

            return $response->object()->rates;
        });
    }
}
