<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectErrorsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('project_errors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned();
            $table->enum('type', ['serverSide', 'clientSide']);
            $table->string('url');
            $table->text('message');
            $table->json('exception');
            $table->json('user')->nullable();
            $table->json('extra_data');
            $table->timestamps();

            $table->foreign('project_id')->references('id')
                ->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('project_errors');
    }
}
