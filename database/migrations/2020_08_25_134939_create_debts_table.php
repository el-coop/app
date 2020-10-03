<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('debts', function(Blueprint $table) {
            $table->id();
            $table->bigInteger('entity_id')->unsigned();
            $table->bigInteger('project_id')->unsigned()->nullable()->default(null);
            $table->date('date')->nullable();
            $table->string('currency');
            $table->enum('type', ['fixed', 'hourly']);
            $table->decimal('amount');
            $table->boolean('invoiced')->default(false);
            $table->string('comment')->nullable();
            $table->timestamps();


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
        Schema::dropIfExists('debts');
    }
}
