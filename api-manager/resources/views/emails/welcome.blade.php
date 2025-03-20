<!DOCTYPE html>
<html>
<head>
    <title>Bem-vindo Meu novo clinte</title>
</head>
<body>
    <h1>Olá, {{ $client->name }}!</h1>
    <p>Seja bem-vindo. Estamos felizes em tê-lo conosco.</p>
    <p>Seu email de cadastro é: {{ $client->email }}</p>
    <p>Atenciosamente,<br>Equipe Yuri Silva</p>
</body>
</html>