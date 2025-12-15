<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #2c3e50;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 50px 40px;
            text-align: center;
            max-width: 600px;
            width: 100%;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 25px;
            background: #e74c3c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-icon svg {
            width: 50px;
            height: 50px;
            fill: white;
        }

        h1 {
            font-size: 64px;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 700;
        }

        h2 {
            font-size: 24px;
            color: #34495e;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .error-message {
            color: #7f8c8d;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .alert {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 25px;
            color: #856404;
            text-align: left;
        }

        .alert strong {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 28px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .info-box {
            margin-top: 25px;
            padding: 18px;
            background: #ecf0f1;
            border-radius: 4px;
            font-size: 13px;
            color: #555;
        }

        .info-box p {
            margin-bottom: 10px;
            font-weight: 600;
        }

        .info-box ul {
            text-align: left;
            margin-left: 30px;
            line-height: 1.8;
        }

        .info-box li {
            margin-bottom: 6px;
        }

        @media (max-width: 600px) {
            .container {
                padding: 40px 25px;
            }

            h1 {
                font-size: 52px;
            }

            h2 {
                font-size: 20px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
        </div>

        <h1>403</h1>
        <h2>Akses Ditolak</h2>

        @if(session('error'))
        <div class="alert">
            <strong>⚠️ Perhatian:</strong>
            {{ session('error') }}
        </div>
        @endif

        <p class="error-message">
            Maaf, Anda tidak memiliki hak akses untuk mengakses halaman ini.
            Halaman yang Anda tuju hanya dapat diakses oleh pengguna tertentu.
        </p>

        <div class="buttons">
            <a href="{{ url('/dashboard') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Kembali
            </a>
        </div>

        {{-- <div class="info-box">
            <p>💡 Informasi:</p>
            <ul>
                <li>Pastikan Anda sudah login dengan akun yang benar</li>
                <li>Hubungi administrator jika Anda merasa seharusnya memiliki akses</li>
                <li>Periksa kembali URL yang Anda tuju</li>
            </ul>
        </div> --}}
    </div>
</body>
</html>