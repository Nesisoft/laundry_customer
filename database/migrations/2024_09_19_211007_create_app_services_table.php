<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('name', 'app_services_name_idx1');
            $table->index('created_at', 'app_services_created_at_idx1');
            $table->index('updated_at', 'app_services_updated_at_idx1');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_services');
    }
};
