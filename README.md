# Peepit - Custom Water Bottle Ordering System

A production-ready PHP 8+ web application for custom water bottle ordering with a complete web installer, admin panel, and advanced features.

## Features

### Core Features
- ✅ Web-based installer with requirement checks
- ✅ Role-based admin panel (Superadmin, Manager, Sales, Webmail, User)
- ✅ User registration and authentication system
- ✅ Bottle models CRUD management
- ✅ Bottle sizes CRUD (250ml, 500ml, 1L, 2L, 5L)
- ✅ Color picker with presets and custom HEX/RGB
- ✅ Label designer with upload and templates
- ✅ Quantity-based dynamic pricing
- ✅ Complete order management system
- ✅ Email notifications (PHPMailer + SMTP)
- ✅ User analytics (IP, device, location tracking)
- ✅ Contact integration (WhatsApp, Email, Phone)
- ✅ Webmail URL access for staff

### Security Features
- ✅ CSRF protection on all forms
- ✅ Input sanitization and validation
- ✅ Password hashing (bcrypt)
- ✅ PDO prepared statements (SQL injection prevention)
- ✅ File upload validation
- ✅ Session security hardening
- ✅ HTTPS enforcement
- ✅ Directory access blocking

### Frontend Order Flow
1. **Step 1:** Select bottle model(s) - Multi-select support
2. **Step 2:** Choose size (250ml / 500ml / 1L / 2L / 5L)
3. **Step 3:** Select color (preset colors + custom color picker)
4. **Step 4:** Upload/Design label (Fabric.js integration)
5. **Step 5:** Choose quantity (dynamic pricing)
6. **Step 6:** Enter delivery details + ETA
7. **Step 7:** Review order summary
8. **Step 8:** Submit order (no payment gateway)
9. **Step 9:** Email notifications sent

## Requirements

### Server Requirements
- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache/Nginx with mod_rewrite
- Composer

### PHP Extensions
- PDO
- pdo_mysql
- mbstring
- openssl
- curl
- gd
- fileinfo

### Permissions
- `config/` directory must be writable
- `public/uploads/` directory must be writable

## Installation

### Step 1: Upload Files
Upload all files to your web server (cPanel or VPS).

### Step 2: Set Permissions
```bash
chmod -R 755 /path/to/peepit
chmod -R 777 /path/to/peepit/config
chmod -R 777 /path/to/peepit/public/uploads
```

### Step 3: Install Dependencies
```bash
cd /path/to/peepit
composer install
```

### Step 4: Run Web Installer
Navigate to: `https://yourdomain.com/install/`

The installer will guide you through:
1. System requirement checks
2. Database configuration
3. Database schema import
4. Admin user creation
5. Configuration file generation

> **⚠️ IMPORTANT:** After installation completes, follow the configuration steps shown on the completion page!
> See [POST-INSTALL.md](POST-INSTALL.md) for detailed post-installation configuration.

### Step 5: Configure Server (Required!)
Choose ONE method:
- **Method 1 (Recommended for aaPanel):** Keep DocumentRoot at root `/`
- **Method 2:** Set DocumentRoot to `/public` and configure open_basedir

**See [POST-INSTALL.md](POST-INSTALL.md) for complete instructions!**

### Step 6: Delete Installer
After successful installation AND configuration, delete the `/install` directory:
```bash
rm -rf /path/to/peepit/install
```

### Step 7: Configure SMTP (Optional)
Edit `/config/smtp.php` to configure email notifications:
```php
return [
    'host' => 'smtp.yourdomain.com',
    'port' => 587,
    'username' => 'no-reply@yourdomain.com',
    'password' => 'your-smtp-password',
    'from_email' => 'no-reply@yourdomain.com',
    'from_name' => 'Peepit',
    'encryption' => 'tls',
];
```

## Configuration

### Application Settings
Edit `/config/app.php`:
```php
return [
    'app_name' => 'Peepit',
    'app_url' => 'https://yourdomain.com',
    'timezone' => 'Asia/Kolkata',
    'currency' => '₹',
    'force_https' => true,
    'max_upload_size' => 5242880, // 5MB
    'whatsapp_number' => '+919876543210',
    'contact_email' => 'contact@yourdomain.com',
    'contact_phone' => '+919876543210',
    'webmail_url' => 'https://webmail.yourdomain.com',
];
```

### Database Configuration
Automatically generated during installation at `/config/database.php`.

## Usage

### Admin Panel Access
Navigate to: `https://yourdomain.com/admin/login`

### User Roles
- **Superadmin:** Full system access
- **Manager:** Product and order management
- **Sales:** Order management only
- **Webmail:** Webmail access only
- **User:** Frontend ordering only

### Pricing Configuration
Admin can configure quantity-based pricing slabs for each bottle size:
- Example: 1-20 pcs → ₹25/pc
- Example: 21-50 pcs → ₹22/pc
- Example: 51+ pcs → ₹20/pc

### Label Designer
Powered by Fabric.js for:
- Image uploads
- Pre-made templates
- Text addition
- Drag, resize, rotate functionality
- Live preview on bottle

## Project Structure

```
peepit/
├── app/
│   ├── Core/           # Framework core classes
│   ├── controllers/    # Application controllers
│   ├── models/         # Database models
│   ├── views/          # View templates
│   ├── middleware/     # Middleware classes
│   └── helpers/        # Helper functions
├── config/             # Configuration files
├── install/            # Web installer (delete after install)
├── public/             # Web root
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   ├── images/        # Static images
│   └── uploads/       # User uploads
├── vendor/            # Composer dependencies
├── .htaccess          # Apache configuration
├── composer.json      # PHP dependencies
└── README.md          # This file
```

## API Documentation

### Helper Functions

#### Security
- `sanitize($input)` - Sanitize user input
- `escape($string)` - HTML escape output
- `csrf_field()` - Generate CSRF hidden field
- `validate_csrf()` - Validate CSRF token

#### Authentication
- `is_logged_in()` - Check if user is logged in
- `current_user()` - Get current user data
- `user_role()` - Get current user role
- `has_role($role)` - Check if user has role
- `require_login()` - Require authentication
- `require_role($role)` - Require specific role

#### URL & Routing
- `url($path)` - Generate full URL
- `redirect($url)` - Redirect to URL
- `back()` - Redirect back

#### Session
- `session($key)` - Get session value
- `flash($key, $message)` - Flash message
- `old($key)` - Get old input value

#### Utility
- `config($key)` - Get config value
- `currency_format($amount)` - Format currency
- `get_client_ip()` - Get client IP
- `get_user_agent()` - Get user agent
- `get_device_type()` - Get device type

## Database Schema

### Main Tables
- `users` - User accounts
- `bottle_models` - Bottle model definitions
- `bottle_sizes` - Available bottle sizes
- `color_presets` - Preset colors
- `label_templates` - Label templates
- `pricing` - Quantity-based pricing
- `orders` - Customer orders
- `order_items` - Order line items
- `email_logs` - Email activity logs
- `user_analytics` - User tracking data
- `settings` - Application settings

## Email System

### PHPMailer Integration
Configured via `/config/smtp.php` for:
- Order confirmation to customers
- New order notification to admin
- Label approval requests
- Custom email notifications

### Email Triggers
- User registration
- Order placement
- Order status updates
- Admin notifications

## User Analytics

Tracks:
- IP addresses
- Device type (Mobile/Tablet/Desktop)
- User agent
- Geographic location (optional)
- Page visits
- User actions

## Security Best Practices

1. **Always use HTTPS in production**
2. **Delete /install directory after installation**
3. **Use strong database passwords**
4. **Configure SMTP credentials securely**
5. **Regular database backups**
6. **Keep PHP and dependencies updated**
7. **Monitor logs for suspicious activity**
8. **Implement rate limiting for API endpoints**

## Troubleshooting

### Issue: "This page isn't working" or "No matching DirectoryIndex" (aaPanel/BT Panel)

**Recommended Solution (Method B - Simpler):**
1. Keep DocumentRoot at root level: `/www/wwwroot/yourdomain.com`
2. Ensure `index.php` and `.htaccess` exist in root directory (pull latest changes)
3. No need to modify open_basedir settings
4. Restart Apache/Nginx

**Alternative Solution (Method A - More Secure):**
1. In aaPanel, go to Website > Your Site > Site Directory
2. Set "Running Directory" to `/public`
3. Configure open_basedir: Change `/www/wwwroot/yourdomain.com/public/:/tmp/` to `/www/wwwroot/yourdomain.com/:/tmp/`
4. Save and restart PHP-FPM

**See AAPANEL-GUIDE.md for detailed instructions.**

### Issue: White screen after installation
**Solution:** Check PHP error logs, ensure all permissions are correct

### Issue: Database connection failed
**Solution:** Verify database credentials in `/config/database.php`

### Issue: Upload errors
**Solution:** Check permissions on `/public/uploads` (777 or 755 with www-data owner)

### Issue: SMTP errors
**Solution:** Verify SMTP credentials and test with telnet

### Issue: .htaccess not working
**Solution:** Enable mod_rewrite in Apache: `a2enmod rewrite`

## Development

### Adding New Routes
Edit `/public/index.php`:
```php
$router->get('/your-route', function() {
    // Your logic
});
```

### Creating Controllers
Extend `App\Core\Controller`:
```php
namespace App\Controllers;
use App\Core\Controller;

class YourController extends Controller {
    public function index() {
        $this->view('your-view', ['data' => 'value']);
    }
}
```

### Creating Models
Extend `App\Core\Model`:
```php
namespace App\Models;
use App\Core\Model;

class YourModel extends Model {
    protected $table = 'your_table';
}
```

## Support

For issues, feature requests, or contributions:
- GitHub: https://github.com/faruk-spec/peepit
- Email: support@peepit.com

## License

MIT License - See LICENSE file for details

## Credits

- Framework: Custom MVC PHP 8+
- Email: PHPMailer
- Label Designer: Fabric.js
- Icons: Font Awesome (optional)

## Changelog

### Version 1.0.0 (Initial Release)
- Complete web installer
- Role-based admin panel
- Frontend order system
- Email notifications
- User analytics
- Security features
- Production-ready architecture

---

**Made with ❤️ for the bottle customization industry**
