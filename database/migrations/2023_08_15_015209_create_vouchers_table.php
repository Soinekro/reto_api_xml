<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('invoice_code', 2)->comment('01: Factura, 03: Boleta, 07: Nota de crédito, 08: Nota de débito');
            $table->char('invoice_serie', 4)->comment('serie del comprobante');
            $table->unsignedInteger('invoice_correlative')->comment('correlativo del comprobante');
            $table->char('invoice_type_currency', 3)->default('PEN')->comment('Tipo de moneda');
            $table->string('issuer_name')->comment('Nombre del emisor');
            $table->string('issuer_document_type')->comment('Tipo de documento del emisor');
            $table->string('issuer_document_number')->comment('Número de documento del emisor');
            $table->string('receiver_name')->comment('Nombre del receptor');
            $table->string('receiver_document_type')->comment('Tipo de documento del receptor');
            $table->string('receiver_document_number')->comment('Número de documento del receptor');
            $table->decimal('total_amount', 8, 2)->comment('Monto total de la factura');
            $table->longText('xml_content')->comment('Contenido del XML');
            $table->uuid('user_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
