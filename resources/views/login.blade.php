<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GOR Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card { width: 100%; max-width: 400px; border: none; }
    </style>
</head>
<body>

<div class="card shadow-lg">
    <div class="card-body text-center p-5">
        <h3 class="fw-bold mb-4">Silakan Login</h3>
        <p class="text-muted mb-4">Pilih akun untuk masuk:</p>

        <div class="d-grid gap-3">
            <a href="/test-login-staff" class="btn btn-dark btn-lg">
                👮 Login sebagai Staff
            </a>
            
            <div class="text-muted fs-6">- ATAU -</div>

            <a href="/test-login-user" class="btn btn-primary btn-lg">
                🏸 Login sebagai User
            </a>
        </div>
        
        <div class="mt-4">
            <a href="/" class="text-decoration-none text-muted">← Kembali ke Beranda</a>
        </div>
    </div>
</div>

</body>
</html>