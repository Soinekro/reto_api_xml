<!DOCTYPE html>
<html>

<head>
    <title>Comprobantes Subidos</title>
</head>

<body>
    <h1>Estimado {{ $user->name }},</h1>
    <p>Hemos recibido tus comprobantes con los siguientes detalles:</p>
    <h3>comprobantes aceptados</h3>
    @foreach ($comprobantes as $comprobante)
        <ul>
            <li>Nombre del Emisor: {{ $comprobante->issuer_name }}</li>
            <li>Tipo de Documento del Emisor: {{ $comprobante->issuer_document_type }}</li>
            <li>Número de Documento del Emisor: {{ $comprobante->issuer_document_number }}</li>
            <li>Nombre del Receptor: {{ $comprobante->receiver_name }}</li>
            <li>Tipo de Documento del Receptor: {{ $comprobante->receiver_document_type }}</li>
            <li>Número de Documento del Receptor: {{ $comprobante->receiver_document_number }}</li>
            <li>Monto Total: {{ $comprobante->total_amount }}</li>
        </ul>
    @endforeach
    <h3>Los siguientes fueron rechazados</h3>
    @foreach ($vouchers_error as $comprobante_reject)
        <ul>
            @foreach ($data['errors'] as $error)
                <li>Error en el XML: {{ $error['error'] }}</li>
                <pre>{{ $error['xmlContent'] }}</pre>
            @endforeach
        </ul>
    @endforeach
    <p>¡Gracias por usar nuestro servicio!</p>
</body>

</html>
