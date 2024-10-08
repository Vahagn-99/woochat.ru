<?php

use App\Enums\InfoType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('infos', function (Blueprint $table) {
            $table->id();
            $table->string('infoable_type');
            $table->string('infoable_id');
            $table->string('type')->default(InfoType::AMOCRM->name);
            $table->json('data');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['infoable_id', 'infoable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infos');
    }
};
