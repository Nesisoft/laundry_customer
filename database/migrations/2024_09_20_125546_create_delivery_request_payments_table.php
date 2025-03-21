<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_request_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('request_id');
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['Cash', 'MoMo', 'Card']);
            $table->enum('status', ['paid', 'unpaid']);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('request_id')->references('id')->on('delivery_requests')->onDelete('cascade')->onUpdate('cascade');

            // Indexes
            $table->index('amount', 'delivery_request_payments_amount_idx1');
            $table->index('method', 'delivery_request_payments_method_idx1');
            $table->index('status', 'delivery_request_payments_status_idx1');
            $table->index('created_at', 'delivery_request_payments_created_at_idx1');
            $table->index('updated_at', 'delivery_request_payments_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_request_payments');
    }
};
