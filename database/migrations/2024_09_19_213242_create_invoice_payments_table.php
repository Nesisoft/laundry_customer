<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['Cash', 'MoMo', 'Card']);
            $table->enum('status', ['fully paid', 'partly paid']);
            $table->timestamps();

            // Indexes
            $table->index('amount', 'invoice_payments_amount_idx1');
            $table->index('method', 'invoice_payments_method_idx1');
            $table->index('status', 'invoice_payments_status_idx1');
            $table->index('created_at', 'invoice_payments_created_at_idx1');
            $table->index('updated_at', 'invoice_payments_updated_at_idx1');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_payments');
    }
};
