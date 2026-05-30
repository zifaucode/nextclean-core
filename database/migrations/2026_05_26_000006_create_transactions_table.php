<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('outlet_id')->index();
            $table->bigInteger('customer_id')->index();
            $table->bigInteger('user_id')->index();
            $table->string('invoice_code')->unique();
            $table->dateTime('transaction_date');
            $table->dateTime('estimate_date')->nullable();
            $table->dateTime('pickup_date')->nullable();
            $table->decimal('total_price', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('additional_fee', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->enum('status', ['Diterima', 'Dicuci', 'Disetrika', 'Selesai', 'Diambil'])->default('Diterima');
            $table->enum('payment_status', ['Belum Bayar', 'Dibayar'])->default('Belum Bayar');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->index();
            $table->string('item_name');
            $table->string('type');
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('transactions');
    }
};
