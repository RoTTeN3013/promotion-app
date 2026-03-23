<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Válasz az üzenetedre</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #222;">
    <h2>Válasz érkezett az üzenetedre</h2>

    <p><strong>Név:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
    <p><strong>E-mail:</strong> {{ $user->email }}</p>
    <p><strong>Telefonszám:</strong> {{ $user->phone_no }}</p>

    <hr>

    <p><strong>Üzeneted:</strong></p>
    <p>{!! nl2br(e($messageText ?? '')) !!}</p>

    @isset($answerText)
        <hr>
        <p><strong>Válasz:</strong></p>
        <p>{!! nl2br(e($answerText)) !!}</p>
    @endisset
</body>
</html>
