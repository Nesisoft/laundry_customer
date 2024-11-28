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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');  // Foreign key column
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('name');
            $table->index('created_at');
            $table->index('updated_at');

            // Define foreign key
        });

        // Insert initial data
        DB::table('services')->insert([
            ['name' => 'Washing only'],
            ['name' => 'Ironing only'],
            ['name' => 'Washing and Ironing']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
