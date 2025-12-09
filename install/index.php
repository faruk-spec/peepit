<?php
session_start();

// Prevent access if already installed
if (file_exists(__DIR__ . '/../config/database.php')) {
    header('Location: ../public/index.php');
    exit;
}

$step = $_GET['step'] ?? 1;
$error = '';
$success = '';

// Step 1: Requirements Check
if ($step == 1) {
    $requirements = [
        'php_version' => version_compare(PHP_VERSION, '8.0.0', '>='),
        'pdo' => extension_loaded('pdo'),
        'pdo_mysql' => extension_loaded('pdo_mysql'),
        'mbstring' => extension_loaded('mbstring'),
        'openssl' => extension_loaded('openssl'),
        'curl' => extension_loaded('curl'),
        'gd' => extension_loaded('gd'),
        'fileinfo' => extension_loaded('fileinfo'),
        'config_writable' => is_writable(__DIR__ . '/../config'),
        'uploads_writable' => is_writable(__DIR__ . '/../public/uploads'),
    ];
}

// Step 2: Database Configuration
if ($step == 2 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['db_host'] ?? '';
    $dbname = $_POST['db_name'] ?? '';
    $username = $_POST['db_user'] ?? '';
    $password = $_POST['db_pass'] ?? '';

    try {
        $dsn = "mysql:host=$host;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Save credentials in session
        $_SESSION['installer'] = [
            'host' => $host,
            'dbname' => $dbname,
            'username' => $username,
            'password' => $password
        ];

        header('Location: index.php?step=3');
        exit;
    } catch (PDOException $e) {
        $error = 'Database connection failed: ' . $e->getMessage();
    }
}

// Step 3: Import Database Schema
if ($step == 3 && isset($_SESSION['installer'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $config = $_SESSION['installer'];
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Import SQL file
            $sql = file_get_contents(__DIR__ . '/database.sql');
            $pdo->exec($sql);

            header('Location: index.php?step=4');
            exit;
        } catch (Exception $e) {
            $error = 'Database import failed: ' . $e->getMessage();
        }
    }
}

// Step 4: Create Admin User
if ($step == 4 && isset($_SESSION['installer'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['admin_name'] ?? '';
        $email = $_POST['admin_email'] ?? '';
        $password = $_POST['admin_password'] ?? '';
        $confirm = $_POST['admin_password_confirm'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $error = 'All fields are required';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters';
        } else {
            try {
                $config = $_SESSION['installer'];
                $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
                $pdo = new PDO($dsn, $config['username'], $config['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'superadmin')");
                $stmt->execute([$name, $email, $hashedPassword]);

                header('Location: index.php?step=5');
                exit;
            } catch (Exception $e) {
                $error = 'Failed to create admin user: ' . $e->getMessage();
            }
        }
    }
}

// Step 5: Finalize Installation
if ($step == 5 && isset($_SESSION['installer'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $config = $_SESSION['installer'];
            
            // Write config file
            $configContent = "<?php\nreturn [\n";
            $configContent .= "    'host' => " . var_export($config['host'], true) . ",\n";
            $configContent .= "    'dbname' => " . var_export($config['dbname'], true) . ",\n";
            $configContent .= "    'username' => " . var_export($config['username'], true) . ",\n";
            $configContent .= "    'password' => " . var_export($config['password'], true) . ",\n";
            $configContent .= "    'charset' => 'utf8mb4',\n";
            $configContent .= "    'options' => [\n";
            $configContent .= "        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,\n";
            $configContent .= "        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,\n";
            $configContent .= "        PDO::ATTR_EMULATE_PREPARES => false,\n";
            $configContent .= "    ]\n";
            $configContent .= "];\n";

            file_put_contents(__DIR__ . '/../config/database.php', $configContent);

            // Create app.php config if it doesn't exist
            if (!file_exists(__DIR__ . '/../config/app.php')) {
                $appUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
                $appConfig = "<?php\nreturn [\n";
                $appConfig .= "    'app_name' => 'Peepit',\n";
                $appConfig .= "    'app_url' => " . var_export($appUrl, true) . ",\n";
                $appConfig .= "    'timezone' => 'Asia/Kolkata',\n";
                $appConfig .= "    'currency' => '₹',\n";
                $appConfig .= "    'force_https' => " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'true' : 'false') . ",\n";
                $appConfig .= "    'session_lifetime' => 7200, // 2 hours\n";
                $appConfig .= "    'max_upload_size' => 5242880, // 5MB in bytes\n";
                $appConfig .= "    'allowed_image_types' => ['image/jpeg', 'image/png', 'image/jpg'],\n";
                $appConfig .= "    'webmail_url' => 'https://webmail.yourdomain.com',\n";
                $appConfig .= "    'whatsapp_number' => '+919876543210',\n";
                $appConfig .= "    'contact_email' => 'contact@yourdomain.com',\n";
                $appConfig .= "    'contact_phone' => '+919876543210',\n";
                $appConfig .= "];\n";
                file_put_contents(__DIR__ . '/../config/app.php', $appConfig);
            }

            // Copy SMTP config example
            if (!file_exists(__DIR__ . '/../config/smtp.php')) {
                copy(__DIR__ . '/../config/smtp.example.php', __DIR__ . '/../config/smtp.php');
            }

            // Clear session
            unset($_SESSION['installer']);

            header('Location: index.php?step=6');
            exit;
        } catch (Exception $e) {
            $error = 'Failed to write configuration: ' . $e->getMessage();
        }
    }
}

// Step 6: Installation Complete
if ($step == 6) {
    // Show completion message
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peepit Installer - Step <?= $step ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .installer {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 50px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
        }
        .header {
            background: #667eea;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { font-size: 32px; margin-bottom: 10px; }
        .header p { opacity: 0.9; }
        .progress {
            display: flex;
            padding: 20px 30px;
            background: #f7fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .progress-step {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            color: #718096;
        }
        .progress-step.active {
            background: #667eea;
            color: white;
        }
        .progress-step.completed {
            background: #48bb78;
            color: white;
        }
        .content {
            padding: 30px;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border: 1px solid #fc8181;
        }
        .alert-success {
            background: #c6f6d5;
            color: #2f855a;
            border: 1px solid #9ae6b4;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2d3748;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e0;
            border-radius: 5px;
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5568d3;
        }
        .btn-success {
            background: #48bb78;
        }
        .btn-success:hover {
            background: #38a169;
        }
        .requirements {
            list-style: none;
        }
        .requirements li {
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        .requirements li.pass {
            background: #c6f6d5;
            color: #2f855a;
        }
        .requirements li.fail {
            background: #fed7d7;
            color: #c53030;
        }
        .requirements li::before {
            content: '✓';
            margin-right: 10px;
            font-weight: bold;
        }
        .requirements li.fail::before {
            content: '✗';
        }
        .text-center {
            text-align: center;
        }
        .mt-20 {
            margin-top: 20px;
        }
        .success-icon {
            font-size: 80px;
            color: #48bb78;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="installer">
        <div class="header">
            <h1>🚰 Peepit Installer</h1>
            <p>Custom Water Bottle Ordering System</p>
        </div>

        <div class="progress">
            <div class="progress-step <?= $step >= 1 ? ($step > 1 ? 'completed' : 'active') : '' ?>">Requirements</div>
            <div class="progress-step <?= $step >= 2 ? ($step > 2 ? 'completed' : 'active') : '' ?>">Database</div>
            <div class="progress-step <?= $step >= 3 ? ($step > 3 ? 'completed' : 'active') : '' ?>">Import</div>
            <div class="progress-step <?= $step >= 4 ? ($step > 4 ? 'completed' : 'active') : '' ?>">Admin</div>
            <div class="progress-step <?= $step >= 5 ? ($step > 5 ? 'completed' : 'active') : '' ?>">Finalize</div>
            <div class="progress-step <?= $step >= 6 ? 'active' : '' ?>">Complete</div>
        </div>

        <div class="content">
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if ($step == 1): ?>
                <h2>Step 1: System Requirements</h2>
                <p>Please ensure your server meets the following requirements:</p>
                <ul class="requirements mt-20">
                    <li class="<?= $requirements['php_version'] ? 'pass' : 'fail' ?>">
                        PHP 8.0 or higher (Current: <?= PHP_VERSION ?>)
                    </li>
                    <li class="<?= $requirements['pdo'] ? 'pass' : 'fail' ?>">PDO Extension</li>
                    <li class="<?= $requirements['pdo_mysql'] ? 'pass' : 'fail' ?>">PDO MySQL Extension</li>
                    <li class="<?= $requirements['mbstring'] ? 'pass' : 'fail' ?>">Mbstring Extension</li>
                    <li class="<?= $requirements['openssl'] ? 'pass' : 'fail' ?>">OpenSSL Extension</li>
                    <li class="<?= $requirements['curl'] ? 'pass' : 'fail' ?>">cURL Extension</li>
                    <li class="<?= $requirements['gd'] ? 'pass' : 'fail' ?>">GD Extension</li>
                    <li class="<?= $requirements['fileinfo'] ? 'pass' : 'fail' ?>">Fileinfo Extension</li>
                    <li class="<?= $requirements['config_writable'] ? 'pass' : 'fail' ?>">Config Directory Writable</li>
                    <li class="<?= $requirements['uploads_writable'] ? 'pass' : 'fail' ?>">Uploads Directory Writable</li>
                </ul>
                <?php if (array_filter($requirements) === $requirements): ?>
                    <div class="text-center mt-20">
                        <a href="?step=2" class="btn">Continue to Database Setup</a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-error mt-20">
                        Please fix the failed requirements before continuing.
                    </div>
                <?php endif; ?>

            <?php elseif ($step == 2): ?>
                <h2>Step 2: Database Configuration</h2>
                <p>Enter your database credentials:</p>
                <form method="POST" class="mt-20">
                    <div class="form-group">
                        <label>Database Host:</label>
                        <input type="text" name="db_host" value="localhost" required>
                    </div>
                    <div class="form-group">
                        <label>Database Name:</label>
                        <input type="text" name="db_name" value="peepit" required>
                    </div>
                    <div class="form-group">
                        <label>Database Username:</label>
                        <input type="text" name="db_user" value="root" required>
                    </div>
                    <div class="form-group">
                        <label>Database Password:</label>
                        <input type="password" name="db_pass">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn">Test Connection & Continue</button>
                    </div>
                </form>

            <?php elseif ($step == 3): ?>
                <h2>Step 3: Import Database Schema</h2>
                <p>Click the button below to import the database schema:</p>
                <form method="POST" class="mt-20">
                    <div class="text-center">
                        <button type="submit" class="btn">Import Database</button>
                    </div>
                </form>

            <?php elseif ($step == 4): ?>
                <h2>Step 4: Create Admin User</h2>
                <p>Create your superadmin account:</p>
                <form method="POST" class="mt-20">
                    <div class="form-group">
                        <label>Full Name:</label>
                        <input type="text" name="admin_name" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address:</label>
                        <input type="email" name="admin_email" required>
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" name="admin_password" required minlength="8">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password:</label>
                        <input type="password" name="admin_password_confirm" required minlength="8">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn">Create Admin Account</button>
                    </div>
                </form>

            <?php elseif ($step == 5): ?>
                <h2>Step 5: Finalize Installation</h2>
                <p>Click the button below to complete the installation:</p>
                <form method="POST" class="mt-20">
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Complete Installation</button>
                    </div>
                </form>

            <?php elseif ($step == 6): ?>
                <div class="text-center">
                    <div class="success-icon">✓</div>
                    <h2>Installation Complete!</h2>
                    <p class="mt-20">Peepit has been successfully installed.</p>
                </div>
                
                <div class="alert alert-warning mt-20">
                    <strong>⚠️ Important: Configuration Required</strong>
                    <p style="margin-top: 10px;">Before accessing your site, please complete ONE of these options:</p>
                </div>

                <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 15px; text-align: left;">
                    <h3 style="color: #667eea; margin-bottom: 10px;">Option 1: Keep DocumentRoot at Root (RECOMMENDED for aaPanel)</h3>
                    <p style="margin-bottom: 10px;">Your DocumentRoot should point to: <code>/www/wwwroot/yourdomain.com</code></p>
                    <ol style="margin-left: 20px; line-height: 1.8;">
                        <li>In aaPanel: Website → Your Site → <strong>Site Directory</strong></li>
                        <li>Set <strong>Running Directory</strong> to: <code>/</code> (root)</li>
                        <li>Click <strong>Save</strong></li>
                        <li>Restart Apache/Nginx</li>
                    </ol>
                    <p style="margin-top: 10px;"><strong>✅ Advantage:</strong> No open_basedir changes needed!</p>
                </div>

                <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 15px; text-align: left;">
                    <h3 style="color: #667eea; margin-bottom: 10px;">Option 2: Set DocumentRoot to /public (More Secure)</h3>
                    <p style="margin-bottom: 10px;">Your DocumentRoot should point to: <code>/www/wwwroot/yourdomain.com/public</code></p>
                    <ol style="margin-left: 20px; line-height: 1.8;">
                        <li>In aaPanel: Website → Your Site → <strong>Site Directory</strong></li>
                        <li>Set <strong>Running Directory</strong> to: <code>/public</code></li>
                        <li>Scroll to <strong>Security</strong> section</li>
                        <li>Find <strong>Open Basedir</strong> setting</li>
                        <li>Change from: <code>/www/wwwroot/yourdomain.com/public/:/tmp/</code></li>
                        <li>Change to: <code>/www/wwwroot/yourdomain.com/:/tmp/</code> (remove /public)</li>
                        <li>Click <strong>Save</strong> and restart PHP-FPM</li>
                    </ol>
                </div>

                <div class="alert alert-error mt-20">
                    <strong>🔒 Security:</strong> After configuration, delete the <code>/install</code> directory:
                    <pre style="background: #fff; padding: 10px; margin-top: 10px; border-radius: 3px;">rm -rf /www/wwwroot/yourdomain.com/install</pre>
                </div>

                <div class="alert alert-info mt-20">
                    <strong>📝 Important Notes:</strong>
                    <ul style="margin-left: 20px; margin-top: 10px; text-align: left; line-height: 1.8;">
                        <li>After configuration, access your site at: <code>https://yourdomain.com/</code></li>
                        <li><strong>Do NOT</strong> access <code>https://yourdomain.com/public/</code> directly</li>
                        <li>Clear your browser cache if you see errors</li>
                        <li>Restart Apache after configuration changes</li>
                    </ul>
                </div>

                <div class="text-center mt-20">
                    <p style="margin-bottom: 10px;">After completing the configuration above:</p>
                    <a href="/" class="btn btn-success">Go to Home Page</a>
                    <a href="/admin/login" class="btn" style="margin-left: 10px;">Admin Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
