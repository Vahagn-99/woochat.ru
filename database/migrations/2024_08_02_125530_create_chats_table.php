<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('amo_chat_id');
            $table->string('whatsapp_chat_id');
            $table->string('instance_id');
            $table->foreign('instance_id')
                ->on('instances')
                ->references('id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['amo_chat_id', 'whatsapp_chat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
