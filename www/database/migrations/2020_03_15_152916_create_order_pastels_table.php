<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPastelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_pastels', function (Blueprint $table) {
            $table->increments('id');

            $table->foreignId('order_id')
                ->constrained()
                ->onDelete('cascade')
            ;

            $table->foreignId('pastel_id')
                ->constrained()
                ->onDelete('cascade')
            ;

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_pastels');
    }
}
