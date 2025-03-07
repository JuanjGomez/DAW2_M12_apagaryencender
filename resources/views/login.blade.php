<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email">
            <span id="email-error" class="error"></span>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <span id="password-error" class="error"></span>
        </div>
        <div>
            <button type="submit" id="btnSesion" disabled>Entrar</button>
        </div>
        @if ($errors->any())
            <div>
                <strong>{{ $errors->first() }}</strong>
            </div>
        @endif
    </form>
    <script src="{{ asset('js/validacionLogin.js') }}"></script>
</body>
</html>
