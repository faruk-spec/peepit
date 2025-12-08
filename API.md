# API Documentation

This document provides API endpoints and helper functions for extending Peepit.

## Table of Contents
1. [Helper Functions](#helper-functions)
2. [Database API](#database-api)
3. [Models](#models)
4. [Controllers](#controllers)
5. [Services](#services)
6. [Creating Extensions](#creating-extensions)

## Helper Functions

All helper functions are available globally via `/app/helpers/functions.php`.

### Security Functions

#### `sanitize($input)`
Sanitizes user input by removing HTML and trimming whitespace.

```php
$clean = sanitize($_POST['name']);
// Arrays are automatically handled
$cleanArray = sanitize($_POST);
```

#### `escape($string)`
Escapes HTML for safe output.

```php
echo escape($userInput);
```

#### `csrf_field()`
Generates a hidden CSRF token field for forms.

```php
<form method="POST">
    <?= csrf_field() ?>
    <!-- form fields -->
</form>
```

#### `csrf_token()`
Returns the current CSRF token value.

```php
$token = csrf_token();
```

#### `validate_csrf()`
Validates CSRF token from POST request.

```php
validate_csrf(); // Throws error if invalid
```

### URL Functions

#### `url($path = '')`
Generates a full URL from a relative path.

```php
echo url('admin/dashboard');
// Output: https://yourdomain.com/admin/dashboard
```

#### `redirect($url)`
Redirects to a URL and exits.

```php
redirect(url('login'));
```

#### `back()`
Redirects to the previous page.

```php
back();
```

### Session Functions

#### `session($key, $default = null)`
Gets a session value.

```php
$userId = session('user_id');
$name = session('name', 'Guest');
```

#### `set_session($key, $value)`
Sets a session value.

```php
set_session('cart_total', 100.00);
```

#### `flash($key, $message = null)`
Sets or gets a flash message (one-time session variable).

```php
// Set flash message
flash('success', 'Order placed successfully!');

// Get and remove flash message
$message = flash('success');
```

#### `old($key, $default = '')`
Gets old input value (useful for form repopulation).

```php
<input name="email" value="<?= old('email') ?>">
```

#### `set_old($data)`
Saves form data for repopulation.

```php
set_old($_POST);
redirect(url('form'));
```

#### `clear_old()`
Clears old input data.

```php
clear_old();
```

### Authentication Functions

#### `is_logged_in()`
Checks if user is authenticated.

```php
if (is_logged_in()) {
    // User is logged in
}
```

#### `current_user()`
Gets current user data.

```php
$user = current_user();
echo $user['name'];
echo $user['email'];
```

#### `user_id()`
Gets current user ID.

```php
$id = user_id();
```

#### `user_role()`
Gets current user role.

```php
$role = user_role(); // 'superadmin', 'manager', 'sales', 'webmail', 'user'
```

#### `has_role($role)`
Checks if user has specific role or higher.

```php
if (has_role('manager')) {
    // User is manager, superadmin, or higher
}
```

#### `require_login()`
Requires authentication, redirects to login if not authenticated.

```php
require_login();
// Code only executes if logged in
```

#### `require_role($role)`
Requires specific role, shows 403 if not authorized.

```php
require_role('superadmin');
// Only superadmin can access
```

### File Upload Functions

#### `validate_upload($file, $maxSize, $allowedTypes)`
Validates an uploaded file.

```php
$result = validate_upload(
    $_FILES['image'],
    5242880, // 5MB
    ['image/jpeg', 'image/png']
);

if ($result === true) {
    // Valid
} else {
    // $result contains error message
}
```

#### `upload_file($file, $destination)`
Uploads and validates a file.

```php
$result = upload_file(
    $_FILES['photo'],
    __DIR__ . '/public/uploads/photos'
);

if (isset($result['success'])) {
    $filename = $result['filename'];
    $path = $result['path'];
} else {
    $error = $result['error'];
}
```

### Utility Functions

#### `config($key, $default = null)`
Gets configuration value.

```php
$appName = config('app_name');
$currency = config('currency', '$');
```

#### `format_date($date, $format = 'Y-m-d H:i:s')`
Formats a date string.

```php
echo format_date('2025-01-15', 'd M Y');
// Output: 15 Jan 2025
```

#### `currency_format($amount)`
Formats amount with currency symbol.

```php
echo currency_format(1500);
// Output: ₹1,500.00
```

#### `get_client_ip()`
Gets client IP address.

```php
$ip = get_client_ip();
```

#### `get_user_agent()`
Gets client user agent string.

```php
$userAgent = get_user_agent();
```

#### `get_device_type()`
Detects device type (Mobile/Tablet/Desktop).

```php
$device = get_device_type();
```

## Database API

### Getting Database Instance

```php
use App\Core\Database;

$db = Database::getInstance();
```

### Query Methods

#### `query($sql, $params = [])`
Executes a query with prepared statement.

```php
$db->query(
    "INSERT INTO users (name, email) VALUES (?, ?)",
    [$name, $email]
);
```

#### `fetchAll($sql, $params = [])`
Fetches all rows.

```php
$users = $db->fetchAll(
    "SELECT * FROM users WHERE role = ?",
    ['user']
);

foreach ($users as $user) {
    echo $user['name'];
}
```

#### `fetch($sql, $params = [])`
Fetches single row.

```php
$user = $db->fetch(
    "SELECT * FROM users WHERE id = ?",
    [$id]
);

echo $user['email'];
```

#### `lastInsertId()`
Gets last inserted ID.

```php
$db->query("INSERT INTO ...");
$newId = $db->lastInsertId();
```

## Models

### Base Model Methods

All models extend `App\Core\Model` and have these methods:

#### `all()`
Gets all records.

```php
use App\Models\User;

$userModel = new User();
$users = $userModel->all();
```

#### `find($id)`
Finds record by ID.

```php
$user = $userModel->find(1);
```

#### `create($data)`
Creates new record.

```php
$userId = $userModel->create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => password_hash('secret', PASSWORD_DEFAULT)
]);
```

#### `update($id, $data)`
Updates record.

```php
$userModel->update(1, [
    'name' => 'Jane Doe',
    'email' => 'jane@example.com'
]);
```

#### `delete($id)`
Deletes record.

```php
$userModel->delete(1);
```

#### `where($column, $value)`
Finds records by column value.

```php
$admins = $userModel->where('role', 'superadmin');
```

#### `first($column, $value)`
Finds first record by column value.

```php
$user = $userModel->first('email', 'john@example.com');
```

### Available Models

#### User Model

```php
use App\Models\User;

$userModel = new User();

// Find by email
$user = $userModel->findByEmail('email@example.com');

// Get user orders
$orders = $userModel->getOrders($userId);

// Get user analytics
$analytics = $userModel->getUserAnalytics($userId, 50);
```

#### BottleModel

```php
use App\Models\BottleModel;

$bottleModel = new BottleModel();

// Get active bottles
$bottles = $bottleModel->getActive();
```

#### BottleSize

```php
use App\Models\BottleSize;

$sizeModel = new BottleSize();

// Get active sizes
$sizes = $sizeModel->getActive();

// Get pricing for quantity
$price = $sizeModel->getPricing($sizeId, $quantity);
```

#### Order

```php
use App\Models\Order;

$orderModel = new Order();

// Generate order number
$orderNumber = $orderModel->generateOrderNumber();

// Get user orders
$orders = $orderModel->getUserOrders($userId);

// Get order details
$order = $orderModel->getOrderDetails($orderId);

// Get order items
$items = $orderModel->getOrderItems($orderId);
```

### Creating Custom Models

```php
<?php

namespace App\Models;

use App\Core\Model;

class CustomModel extends Model
{
    protected $table = 'custom_table';

    public function customMethod()
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE custom_field = ?",
            ['value']
        );
    }
}
```

## Controllers

### Base Controller Methods

All controllers extend `App\Core\Controller`:

#### `view($view, $data = [])`
Renders a view.

```php
$this->view('admin/dashboard', [
    'stats' => $stats,
    'orders' => $orders
]);
```

#### `json($data, $statusCode = 200)`
Returns JSON response.

```php
$this->json([
    'success' => true,
    'message' => 'Order created',
    'order_id' => $orderId
], 201);
```

#### `redirect($url)`
Redirects to URL.

```php
$this->redirect(url('admin/orders'));
```

#### `validateCSRF()`
Validates CSRF token.

```php
$this->validateCSRF();
```

#### `generateCSRF()`
Generates CSRF token.

```php
$token = $this->generateCSRF();
```

### Creating Custom Controllers

```php
<?php

namespace App\Controllers;

use App\Core\Controller;

class CustomController extends Controller
{
    public function index()
    {
        require_login();
        
        $data = $this->db->fetchAll("SELECT * FROM custom_table");
        
        $this->view('custom/index', [
            'csrf_token' => $this->generateCSRF(),
            'data' => $data
        ]);
    }

    public function store()
    {
        require_login();
        $this->validateCSRF();

        $name = sanitize($_POST['name']);
        
        try {
            $this->db->query(
                "INSERT INTO custom_table (name) VALUES (?)",
                [$name]
            );
            
            flash('success', 'Created successfully');
            $this->redirect(url('custom'));
        } catch (\Exception $e) {
            flash('error', 'Failed to create');
            $this->redirect(url('custom/create'));
        }
    }
}
```

## Services

### Email Service

```php
use App\Services\EmailService;

$emailService = new EmailService();

// Send order confirmation
$emailService->sendOrderConfirmation(
    $orderId,
    'customer@email.com',
    'Customer Name',
    'ORD-123456'
);

// Send admin notification
$emailService->sendAdminOrderNotification($orderId, 'ORD-123456');

// Send label approval request
$emailService->sendLabelApprovalRequest($orderId, 'admin@email.com');
```

### Creating Custom Services

```php
<?php

namespace App\Services;

use App\Core\Database;

class CustomService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function processData($data)
    {
        // Custom logic
        return $result;
    }
}
```

## Creating Extensions

### Adding New Routes

Edit `/public/index.php`:

```php
$router->get('/custom/route', function() {
    require_once __DIR__ . '/../app/controllers/CustomController.php';
    $controller = new \App\Controllers\CustomController();
    $controller->index();
});

$router->post('/custom/route', function() {
    require_once __DIR__ . '/../app/controllers/CustomController.php';
    $controller = new \App\Controllers\CustomController();
    $controller->store();
});
```

### Creating Middleware

Create `/app/middleware/CustomMiddleware.php`:

```php
<?php

namespace App\Middleware;

class CustomMiddleware
{
    public static function handle()
    {
        // Custom logic
        if (!someCondition()) {
            http_response_code(403);
            die('Access denied');
        }
    }
}
```

Use in routes:

```php
$router->get('/protected', function() {
    \App\Middleware\CustomMiddleware::handle();
    // Route logic
});
```

### Adding Database Tables

Create migration file:

```sql
-- migrations/001_add_custom_table.sql

CREATE TABLE `custom_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Run manually or via installer.

### Creating Admin Pages

1. Create controller in `/app/controllers/admin/`
2. Create views in `/app/views/admin/`
3. Add routes in `/public/index.php`
4. Add navigation link in admin sidebar

## REST API Endpoints

### Creating API Endpoints

```php
// In public/index.php
$router->get('/api/products', function() {
    require_login();
    
    $db = \App\Core\Database::getInstance();
    $products = $db->fetchAll("SELECT * FROM products WHERE status = 'active'");
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'data' => $products
    ]);
});

$router->post('/api/products', function() {
    require_role('manager');
    validate_csrf();
    
    $data = json_decode(file_get_contents('php://input'), true);
    $name = sanitize($data['name']);
    
    $db = \App\Core\Database::getInstance();
    $db->query(
        "INSERT INTO products (name) VALUES (?)",
        [$name]
    );
    
    header('Content-Type: application/json');
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'id' => $db->lastInsertId()
    ]);
});
```

### API Authentication

For API endpoints, implement token-based authentication:

```php
function validateApiToken()
{
    $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    
    if (empty($token)) {
        http_response_code(401);
        die(json_encode(['error' => 'Unauthorized']));
    }
    
    $db = \App\Core\Database::getInstance();
    $user = $db->fetch(
        "SELECT * FROM users WHERE api_token = ?",
        [str_replace('Bearer ', '', $token)]
    );
    
    if (!$user) {
        http_response_code(401);
        die(json_encode(['error' => 'Invalid token']));
    }
    
    return $user;
}
```

## Examples

### Creating a Custom Report

```php
<?php
// app/controllers/admin/ReportController.php

namespace App\Controllers\Admin;

use App\Core\Controller;

class ReportController extends Controller
{
    public function sales()
    {
        require_role('manager');
        
        $startDate = sanitize($_GET['start'] ?? date('Y-m-01'));
        $endDate = sanitize($_GET['end'] ?? date('Y-m-d'));
        
        $report = $this->db->fetchAll(
            "SELECT 
                DATE(created_at) as date,
                COUNT(*) as orders,
                SUM(total_amount) as revenue
            FROM orders
            WHERE created_at BETWEEN ? AND ?
            GROUP BY DATE(created_at)
            ORDER BY date DESC",
            [$startDate, $endDate]
        );
        
        $this->view('admin/reports/sales', [
            'csrf_token' => $this->generateCSRF(),
            'report' => $report,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }
}
```

### Adding Custom Pricing Logic

```php
<?php
// app/services/PricingService.php

namespace App\Services;

use App\Core\Database;

class PricingService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function calculatePrice($sizeId, $quantity, $customOptions = [])
    {
        // Get base price
        $pricing = $this->db->fetch(
            "SELECT price_per_unit FROM pricing 
            WHERE bottle_size_id = ? 
            AND min_quantity <= ? 
            AND (max_quantity IS NULL OR max_quantity >= ?)
            ORDER BY min_quantity DESC LIMIT 1",
            [$sizeId, $quantity, $quantity]
        );
        
        $basePrice = $pricing['price_per_unit'] ?? 0;
        
        // Apply custom options
        if (!empty($customOptions['rush'])) {
            $basePrice *= 1.5; // 50% rush fee
        }
        
        if (!empty($customOptions['premium_label'])) {
            $basePrice += 10; // ₹10 premium label fee
        }
        
        return [
            'unit_price' => $basePrice,
            'total_price' => $basePrice * $quantity,
            'quantity' => $quantity
        ];
    }
}
```

## Support

For API questions or feature requests:
- GitHub Issues: https://github.com/faruk-spec/peepit/issues
- Email: dev@peepit.com
