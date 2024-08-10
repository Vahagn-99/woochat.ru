<?php

use App\Enums\InstanceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('whatsapp_instances', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('token')->unique();
            $table->string('name')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->string('status')->default(InstanceStatus::STARTING);
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_instances');
    }
};
