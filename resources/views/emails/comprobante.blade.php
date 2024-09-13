<!DOCTYPE html>
<html>

<head>
    <title>Comprobantes Subidos</title>
</head>

<body>
    <h1>Estimado {{ $user->name }},</h1>
    <p>Hemos recibido tus comprobantes con los siguientes detalles:</p>
    @if (count($vouchers_error) > 0)
        <h3>Los siguientes comprobantes fueron rechazados</h3>
        @foreach ($vouchers_error as $comprobante_reject)
            <ul>
                <li>archivo: {{ $comprobante_reject['xml_content'] }}</li>
                @foreach ($comprobante_reject['error'] as $error)
                    <ul>
                        <li>{{ $error }}</li>
                    </ul>
                @endforeach
            </ul>
        @endforeach
    @endif
    @if (count($comprobantes) > 0)
        <h3>Los siguientes comprobantes fueron aceptados</h3>
        @foreach ($comprobantes as $comprobante_accept)
            <ul>
                <li>archivo: {{ $comprobante_accept['xml_content'] }}</li>
            </ul>
        @endforeach
        <p>¡Gracias por usar nuestro servicio!</p>
        <p>Saludos cordiales,</p>
        <p>El equipo de {{ config('app.name') }}</p>
    @endif
</body>

</html>
