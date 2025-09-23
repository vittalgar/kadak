<!DOCTYPE html>
<html>

<head>
    <title>QR Codes - {{ $batchName }}</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
        }

        .qr-item {
            display: inline-block;
            width: 24%;
            padding: 10px;
            box-sizing: border-box;
            text-align: center;
            page-break-inside: avoid;
        }

        .qr-code {
            margin-bottom: 5px;
            margin-top: 5px;
        }

        .token {
            font-size: 10px;
            word-break: break-all;
        }
    </style>
</head>

<body>
    {{-- <h1>Batch: {{ $batchName }}</h1> --}}
    <div class="container">
        @foreach ($tokens as $token)
            <div class="qr-item">
                <div class="qr-code">
                    <img src="data:image/png;base64, {!! base64_encode(
                        QrCode::format('png')->size(150)->generate(env('QR_CODE_BASE_URL') . '/spin/' . $token->token),
                    ) !!} ">
                </div>
                {{-- <div class="token">{{ $token->token }}</div> --}}
            </div>
        @endforeach
    </div>
</body>

</html>
