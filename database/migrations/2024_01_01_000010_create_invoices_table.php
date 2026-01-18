<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->timestamp('issued_at')->nullable();
            $table->enum('status', ['draft', 'issued', 'paid', 'overdue'])->default('draft');
            $table->timestamps();
            $table->index('reservation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
