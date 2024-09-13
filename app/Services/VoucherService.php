<?php

namespace App\Services;

use App\Events\Vouchers\VouchersCreated;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherLine;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class VoucherService
{
    /**
     * @param int $page
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function getVouchers(): LengthAwarePaginator
    {
        return Voucher::included()
            ->code()
            ->betweenDates()
            ->sort()
            ->perPaginate();
    }

    /**
     * @param string[] $xmlContents
     * @param User $user
     * @return Voucher[]
     */
    public function storeVouchersFromXmlContents(array $xmlContents, User $user): void
    {
        $vouchers = [];
        $vouchers_error = [];
        foreach ($xmlContents as $xmlContent) {
            $response = $this->storeVoucherFromXmlContent($xmlContent['content'], $user);
            if ($response['aceptado']) {
                $vouchers[] = [
                    'xml_content' => $xmlContent['name'],
                ];
            } else {
                $vouchers_error[] = [
                    'xml_content' => $xmlContent['name'],
                    'error' => $response['error'] ? $response['error'] : ['Error al crear el comprobante'],
                ];
            }
        }

        VouchersCreated::dispatch($vouchers, $user, $vouchers_error);
    }

    public function storeVoucherFromXmlContent(string $xmlContent, User $user): array
    {
        $xml = new SimpleXMLElement($xmlContent);
        $errores = [];
        $issuerName = Voucher::validatePathXML($xml, 'cac:AccountingSupplierParty/cac:Party/cac:PartyName/cbc:Name');
        if ($issuerName == null) {
            $errores[] = 'No se encontró el campo de nombre del emisor';
        }
        $invoiceCode = Voucher::validatePathXML($xml, 'cbc:InvoiceTypeCode');
        if ($invoiceCode == null) {
            $errores[] = 'No se encontró el campo de tipo de comprobante';
        }
        // Verificar que el contenido no esté vacío
        $invoiceSerieComplete = Voucher::validatePathXML($xml, 'cbc:ID');
        if ($invoiceSerieComplete == null) {
            $errores[] = 'No se encontró el campo de serie y correlativo';
        }
        $invoiceSerie = substr($invoiceSerieComplete, 0, 4);
        $invoiceCorrelative = substr($invoiceSerieComplete, 5);
        $invoice_type_currency = Voucher::validatePathXML($xml, 'cbc:DocumentCurrencyCode');
        if ($invoice_type_currency == null) {
            $errores[] = 'No se encontró el campo de tipo de moneda';
        }
        $issuerDocumentType = Voucher::validatePathXML($xml, 'cac:AccountingSupplierParty/cac:Party/cac:PartyIdentification/cbc:ID/@schemeID');
        if ($issuerDocumentType == null) {
            $errores[] = 'No se encontró el campo de tipo de documento del emisor';
        }
        $issuerDocumentNumber = Voucher::validatePathXML($xml, 'cac:AccountingSupplierParty/cac:Party/cac:PartyIdentification/cbc:ID');
        if ($issuerDocumentNumber == null) {
            $errores[] = 'No se encontró el campo de número de documento del emisor';
        }
        $receiverName = Voucher::validatePathXML($xml, 'cac:AccountingCustomerParty/cac:Party/cac:PartyLegalEntity/cbc:RegistrationName');
        if ($receiverName == null) {
            $errores[] = 'No se encontró el campo de nombre del receptor';
        }
        $receiverDocumentType = Voucher::validatePathXML($xml, 'cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID/@schemeID');
        if ($receiverDocumentType == null) {
            $errores[] = 'No se encontró el campo de tipo de documento del receptor';
        }
        $receiverDocumentNumber = Voucher::validatePathXML($xml, 'cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID');
        if ($receiverDocumentNumber == null) {
            $errores[] = 'No se encontró el campo de número de documento del receptor';
        }
        $totalAmount = Voucher::validatePathXML($xml, 'cac:LegalMonetaryTotal/cbc:PayableAmount');
        if ($totalAmount == null) {
            $errores[] = 'No se encontró el campo de monto total';
        }

        if (!empty($errores)) {
            return [
                'aceptado' => false,
                'error' => $errores,
            ];
        } else {
            $voucher = new Voucher([
                'invoice_code' => $invoiceCode,
                'invoice_serie' => $invoiceSerie,
                'invoice_correlative' => $invoiceCorrelative,
                'invoice_type_currency' => $invoice_type_currency,
                'issuer_name' => $issuerName,
                'issuer_document_type' => $issuerDocumentType,
                'issuer_document_number' => $issuerDocumentNumber,
                'receiver_name' => $receiverName,
                'receiver_document_type' => $receiverDocumentType,
                'receiver_document_number' => $receiverDocumentNumber,
                'total_amount' => $totalAmount,
                'xml_content' => $xmlContent,
                'user_id' => $user->id,
            ]);
            $voucher->save();
        }

        $items = Voucher::validatePathXML($xml, 'cac:InvoiceLine');
        if ($items == null) {
            $errores[] = 'No se encontraron items en el comprobante';
        }
        $n = 1;
        foreach ($xml->xpath('//cac:InvoiceLine') as $invoiceLine) {
            $name = Voucher::validatePathXML($xml, 'cac:InvoiceLine/cac:Item/cbc:Description');
            if ($name == null) {
                $errores[] = 'No se encontró el campo de nombre del producto item ' . $n;
            }
            $quantity = Voucher::validatePathXML($xml, 'cac:InvoiceLine/cbc:InvoicedQuantity');
            if ($quantity == null) {
                $errores[] = 'No se encontró el campo de cantidad item ' . $n;
            }
            $unitPrice = Voucher::validatePathXML($xml, 'cac:InvoiceLine/cac:Price/cbc:PriceAmount');
            if ($unitPrice == null) {
                $errores[] = 'No se encontró el campo de precio unitario  item ' . $n;
            }
            if (!empty($errores)) {
                return [
                    'aceptado' => false,
                    'error' => $errores,
                ];
            } else {
                $voucherLine = new VoucherLine([
                    'name' => $name,
                    'quantity' => (double)$quantity,
                    'unit_price' => (double)$unitPrice,
                    'voucher_id' => $voucher->id,
                ]);
                $voucherLine->save();
            }
            $n++;
        }

        return [
            'aceptado' => empty($errores),
            'error' => $errores,
        ];
    }
}
