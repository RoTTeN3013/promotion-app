<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feltöltés státusz változás</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #222;">
    <h2>Feltöltés státusz változás</h2>

    <p>A feltöltésed státusza megváltozott.</p>

    <p><strong>Feltöltés azonosító:</strong> #{{ $submission->id }}</p>
    <p><strong>Új státusz:</strong> {{ $statusLabel }}</p>

    <p>Köszönjük, hogy a Promotion Appot használod!</p>
</body>
</html>
