<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateInvoiceRequest;

class InvoiceController extends Controller {
    public function generate(GenerateInvoiceRequest $request) {
        return $request->commit();
    }
}
