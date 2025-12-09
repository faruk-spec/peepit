<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Peepit</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0369A1 0%, #0EA5E9 50%, #06B6D4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            text-align: center;
            color: white;
        }

        .error-code {
            font-size: 150px;
            font-weight: bold;
            line-height: 1;
            margin-bottom: 20px;
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: bounce 2s ease-in-out infinite;
        }

        .error-icon {
            font-size: 100px;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .error-title {
            font-size: 48px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .error-message {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .error-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: white;
            color: #0EA5E9;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .error-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .error-btn.secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @media (max-width: 768px) {
            .error-code { font-size: 100px; }
            .error-title { font-size: 32px; }
            .error-message { font-size: 16px; }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon"><i class="fas fa-search"></i></div>
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-message">
            Oops! The page you're looking for seems to have gone on vacation. 
            It might have been moved, deleted, or never existed in the first place.
        </p>
        <div class="error-actions">
            <a href="/" class="error-btn">
                <i class="fas fa-home"></i>
                Go to Homepage
            </a>
            <a href="javascript:history.back()" class="error-btn secondary">
                <i class="fas fa-arrow-left"></i>
                Go Back
            </a>
        </div>
    </div>
</body>
</html>
