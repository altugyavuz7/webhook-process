<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebhookProcessErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhook_process_errors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('process_id')->index();
            $table->string('line', 50)->nullable();
            $table->text('file')->nullable();
            $table->longText('message');
            $table->longText('code')->nullable();
            $table->timestamps();

            $table->foreign('process_id')
                ->references('id')->on('webhook_processes')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webhook_process_errors');
    }
}
