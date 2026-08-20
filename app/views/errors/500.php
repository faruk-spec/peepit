<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | Peepit</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #991B1B 0%, #DC2626 50%, #EF4444 100%);
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
            animation: pulse 2s ease-in-out infinite;
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
            color: #DC2626;
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

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
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
        <div class="error-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="error-code">500</div>
        <h1 class="error-title">Internal Server Error</h1>
        <p class="error-message">
            Oops! Something went wrong on our end. Our team has been notified and we're working to fix it. 
            Please try again in a few moments.
        </p>
        <div class="error-actions">
            <a href="/" class="error-btn">
                <i class="fas fa-home"></i>
                Go to Homepage
            </a>
            <a href="javascript:location.reload()" class="error-btn secondary">
                <i class="fas fa-redo"></i>
                Try Again
            </a>
        </div>
    </div>
</body>
</html>
