<?php


namespace App\Services;


class CurrencyConverter {
    private $url = 'https://api.exchangerate-api.com/v4/latest/ILS';
    private $rates;
    
    public function getRate($currency) {
        switch ($currency) {
            case '€':
                $currency = 'EUR';
                break;
            default:
                $currency = 'USD';
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
