<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('entity_id')->unsigned();
            $table->bigInteger('project_id')->unsigned()->nullable()->default(null);
            $table->date('date');
            $table->decimal('amount');
            $table->string('reason');
            $table->string('currency');
            $table->decimal('rate');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index('date');
            $table->foreign('entity_id')->references('id')->on('entities');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('transactions');
    }
}
