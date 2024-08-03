<?php

use App\Models\Chat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('amo_message_id');
            $table->string('whatsapp_message_id');
            $table->text('message')->nullable();
            $table->string('source_path')->nullable();
            $table->foreignIdFor(Chat::class,'chat_id')
                ->constrained()
                ->references('id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['amo_message_id', 'whatsapp_message_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
