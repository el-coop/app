<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionAttachmentsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('transaction_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id');
            $table->string('name');
            $table->string('path');
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')
                ->on('transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('transaction_attachments');
    }
}
