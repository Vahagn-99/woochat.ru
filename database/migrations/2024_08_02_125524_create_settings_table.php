<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('instance_id');
            $table->foreign('instance_id')
                ->on('instances')
                ->references('id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('pipeline_id');
            $table->unsignedBigInteger('status_id');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
