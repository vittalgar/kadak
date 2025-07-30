<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>QR Codes for Batch: {{ $batchName }}</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: sans-serif;
        }

        .qr-container {
            text-align: center;
            display: inline-block;
            width: 18%;
            /* 5 columns */
            padding: 1%;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .qr-code img {
            max-width: 100%;
            height: auto;
        }

        .token-text {
            font-size: 8px;
            word-break: break-all;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    @foreach ($tokens->chunk(50) as $page)
        <div style="page-break-after: always;">
            @foreach ($page as $token)
                <div class="qr-container">
                    <div class="qr-code">
                        <!-- THE FIX: Generate a Base64 PNG image for reliable PDF rendering -->
                        @php
                            $qrCodeImage = base64_encode(
                                QrCode::format('png')
                                    ->size(100)
                                    ->generate(route('spin', ['token' => $token->token])),
                            );
                        @endphp
                        <img src="data:image/png;base64,{{ $qrCodeImage }}">
                    </div>
                    <div class="token-text">{{ $token->token }}</div>
                </div>
            @endforeach
        </div>
    @endforeach
</body>

</html>
