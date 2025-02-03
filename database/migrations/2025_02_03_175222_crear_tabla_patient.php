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
        Schema::create('patients', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('name');
            $table->string('last_name');
            $table->string('birth_date');
            $table->string('address');
            $table->string('city');
            $table->integer('postal_code');
            $table->string('dni');
            $table->integer('health_card_number')->unique();
            $table->string('phone');
            $table->string('email');
            $table->integer('zone_id');
            $table->string('personal_situation');
            $table->string('health_situation');
            $table->string('housing_situation');
            $table->string('personal_autonomy');
            $table->string('economic_situation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
