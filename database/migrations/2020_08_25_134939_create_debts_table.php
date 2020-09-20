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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('entity_id')->unsigned();
            $table->bigInteger('project_id')->unsigned()->nullable()->default(null);
            $table->decimal('amount');
            $table->string('reason');
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
