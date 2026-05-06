<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #F5F5F5;
            color: #333;
            min-height: 90vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            width: 100%;
            text-align: center;
        }

        .error-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            margin-bottom: 30px;
            border-left: 6px solid #7367f0;
            transition: transform 0.3s ease;
        }

        .error-code {
            font-size: 4.5rem;
            font-weight: 800;
            color: #7367f0;
            margin-bottom: 10px;
            line-height: 1;
        }

        .error-title {
            font-size: 1.8rem;
            color: #7367f0;
            margin-bottom: 15px;
        }

        .error-description {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 25px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .error-solution {
            background: #e9e8f5;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: left;
            border-left: 4px solid #7367f0;
        }

        .error-solution h3 {
            color: #7367f0;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .error-solution ul {
            padding-left: 20px;
        }

        .error-solution li {
            margin-bottom: 8px;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn {
            padding: 8px 25px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
        }

        .btn-primary {
            background: #7367f0;
            color: white;
        }

        .btn-secondary {
            color: #7367f0;
            border: 1px solid;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            background: #7367f0;
        }

        .btn-secondary:hover {}

        .error-selector {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .error-btn {
            padding: 10px 20px;
            background: white;
            border: 1px solid #c8e6c9;
            border-radius: 6px;
            color: #7367f0;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .error-btn.active {
            background: #7367f0;
            color: white;
        }

        .error-btn:hover {
            background: #e8f5e9;
        }

        .error-btn.active:hover {
            background: #7367f0;
        }

        .icon {
            margin-right: 8px;
            font-size: 1.2rem;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 4rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-card {
                padding: 25px 20px;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div id="error-500" class="error-card">
            <div class="error-code">500</div>
            <h2 class="error-title">Kesalahan Server</h2>
            <p class="error-description">
                Terjadi kesalahan internal server. Tim teknis telah diberitahu dan sedang mengatasi masalah ini.
            </p>

            <div class="error-solution">
                <h3>Solusi yang mungkin:</h3>
                <ul>
                    <li>Coba lagi dalam beberapa menit</li>
                    <li>Periksa koneksi internet Anda</li>
                    <li>Hubungi administrator sistem</li>
                    <li>Laporkan masalah ini kepada tim pengembang</li>
                </ul>
            </div>

            <div class="action-buttons">
                <button class="btn btn-primary" onclick="window.location.reload()">
                    Coba Lagi
                </button>
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>

</html>
