<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SmartechInvoiceEmail extends Mailable {
    use Queueable, SerializesModels;

    protected $invoice;
    protected string $signature;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice, $name) {
        //
        $this->invoice = $invoice;
        $this->signature = Str::before($name, ' ');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('email.smartechInvoice')->with([
            'invoice' => $this->invoice,
            'signature' => $this->signature
        ]);
    }
}
