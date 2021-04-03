<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateInvoiceRequest;
use App\Http\Requests\GenerateSmartechInvoiceEmailRequest;

class InvoiceController extends Controller {
    public function generate(GenerateInvoiceRequest $request) {
        return response()->streamDownload(function() use ($request) {
            echo $request->commit();
        }, 'invoice.pdf', [
            'content-type' => 'application/pdf'
        ]);

    }

    public function smartechEmail(GenerateSmartechInvoiceEmailRequest $request) {
        $request->commit();

        return [
            'success' => true
        ];
    }
}
