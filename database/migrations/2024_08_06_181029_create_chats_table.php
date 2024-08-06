<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('amo_chat_id')->nullable();
            $table->string('whatsapp_chat_id')->nullable();
            $table->string('instance_id')->nullable();
            $table->foreign('instance_id')
                ->on('instances')
                ->references('id')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('connection_id')->nullable();
            $table->foreign('connection_id')
                ->on('amo_connections')
                ->references('account_id')
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
