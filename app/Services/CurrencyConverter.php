<?php


namespace App\Services;


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
            $response = file_get_contents($this->url);
            if (!$response) {
                throw new \Exception('Conversion error');
            }
            return json_decode($response)->rates;
        });
    }
}
