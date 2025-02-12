<?php

use App\Enums\Alarms_type;
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
       
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained();
            $table->enum('type',array_column(Alarms_type::cases(),'name'));
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('weekday')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropColumn('zone_id');
            $table->dropForeign(['patient_id']);
            $table->dropColumn('patient_id');
        });
        Schema::dropIfExists('alerts');
    }
};
