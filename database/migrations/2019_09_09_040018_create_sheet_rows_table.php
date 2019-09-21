<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSheetRowsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('sheet_rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('calculation_sheet_id')->unsigned();
            $table->string('label');
            $table->decimal('amount', 10, 2);
            $table->enum('action', ['+', '-', 'header', 'ignore']);
            $table->text('comment');
            $table->timestamps();
            
            $table->foreign('calculation_sheet_id')
                ->references('id')->on('calculation_sheets')
                ->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('sheet_rows');
    }
}
