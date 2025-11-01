<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('plan')->index();                                // monthly | annual
            $table->string('status')->default('pending')->index();          // pending|active|canceled|past_due|incomplete

            $table->string('paddle_customer_id')->nullable()->index();
            $table->string('paddle_subscription_id')->nullable()->unique();

            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('canceled_at')->nullable();

            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'plan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
