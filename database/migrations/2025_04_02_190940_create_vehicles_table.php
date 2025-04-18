<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('number', 255)->unique();
            $table->enum('type', ['car', 'motorcycle', 'bicycle'])->default('car');
            $table->string('model', 255)->nullable();
            $table->string('year', 255)->nullable();
            $table->boolean('archived')->default(false);
            $table->timestamps();

            // Define indexes
            $table->index('number', 'vehicles_number_idx1');
            $table->index('type', 'vehicles_type_idx1');
            $table->index('model', 'vehicles_model_idx1');
            $table->index('year', 'vehicles_year_idx1');
            $table->index('archived', 'vehicles_archived_idx1');
            $table->index('created_at', 'vehicles_created_at_idx1');
            $table->index('updated_at', 'vehicles_updated_at_idx1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
