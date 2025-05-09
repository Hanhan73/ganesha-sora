<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Ganesha Sora</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }

        .auth-box {
            max-width: 450px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .auth-box h2 {
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="auth-box">
        @yield('content')
    </div>
</body>
</html>
