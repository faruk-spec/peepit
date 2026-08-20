# Peepit - Deployment Guide

This guide will help you deploy Peepit to various hosting environments.

## Table of Contents
1. [Pre-Deployment Checklist](#pre-deployment-checklist)
2. [cPanel Hosting](#cpanel-hosting)
3. [VPS/Dedicated Server](#vps-dedicated-server)
4. [Docker Deployment](#docker-deployment)
5. [SSL Certificate Setup](#ssl-certificate-setup)
6. [Post-Deployment Steps](#post-deployment-steps)
7. [Troubleshooting](#troubleshooting)

## Pre-Deployment Checklist

Before deploying, ensure you have:

- [ ] PHP 8.0 or higher
- [ ] MySQL 5.7+ or MariaDB 10.3+
- [ ] Composer installed (locally or on server)
- [ ] Domain name configured
- [ ] SSL certificate (recommended)
- [ ] SMTP credentials for email
- [ ] Server with at least 1GB RAM
- [ ] 1GB+ disk space

## cPanel Hosting

### Step 1: Upload Files

1. **Download the repository:**
   ```bash
   git clone https://github.com/faruk-spec/peepit.git
   cd peepit
   ```

2. **Install dependencies locally:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Create a ZIP file:**
   ```bash
   zip -r peepit.zip . -x "*.git*" -x "*node_modules*"
   ```

4. **Upload to cPanel:**
   - Login to cPanel
   - Go to File Manager
   - Navigate to `public_html` (or subdirectory)
   - Upload `peepit.zip`
   - Extract the archive

### Step 2: Configure Public Directory

1. **If your domain points to public_html:**
   - Move all files from `peepit/public/*` to `public_html/`
   - Move all other folders (`app`, `config`, `install`, `vendor`) outside of `public_html` (one level up)
   - Update paths in `index.php` accordingly

2. **Or use subdomain/addon domain:**
   - Point the domain document root to `peepit/public/`

### Step 3: Set Permissions

In cPanel File Manager:
```
chmod 755 /home/username/peepit
chmod 777 /home/username/peepit/config
chmod 777 /home/username/peepit/public/uploads
```

Or via SSH:
```bash
cd ~/peepit
chmod -R 755 .
chmod -R 777 config
chmod -R 777 public/uploads
```

### Step 4: Create Database

1. Go to **MySQL Databases** in cPanel
2. Create a new database (e.g., `username_peepit`)
3. Create a database user
4. Add user to database with all privileges
5. Note down the database name, username, and password

### Step 5: Run Installer

1. Navigate to `https://yourdomain.com/install/`
2. Follow the installation wizard:
   - Check system requirements
   - Enter database credentials
   - Import database schema
   - Create admin account
   - Complete installation

3. **Important:** Delete the `install` folder after installation:
   ```bash
   rm -rf install
   ```

### Step 6: Configure SMTP

1. Login to admin panel
2. Go to Settings > SMTP Configuration
3. Enter your SMTP details:
   - **Host:** mail.yourdomain.com (or smtp.gmail.com for Gmail)
   - **Port:** 587 (TLS) or 465 (SSL)
   - **Username:** Your email address
   - **Password:** Email password or app password
   - **From Email:** no-reply@yourdomain.com
   - **From Name:** Peepit

4. Test email configuration

## aaPanel / BT Panel Hosting

aaPanel (BaoTa Panel) is a popular server management panel. Here's how to deploy Peepit on aaPanel:

### Step 1: Upload Files via FTP/SFTP

1. **Connect to your server:**
   - Use FileZilla or any FTP/SFTP client
   - Connect to your server using credentials from aaPanel

2. **Upload project files:**
   - Upload all files to `/www/wwwroot/yourdomain.com/`
   - Ensure all folders are uploaded (app, config, install, public, vendor)

### Step 2: Configure Site Settings in aaPanel

1. **Login to aaPanel**
2. **Go to Website > Your Site > Site Directory**
3. **IMPORTANT: Set the Document Root (Running Directory) to `/www/wwwroot/yourdomain.com/public`**
   - This is crucial! The DocumentRoot must point to the `public/` subdirectory
   - Click "Site Directory" button
   - Set "Running Directory" to `/public`
   - Save changes

**Alternative if you cannot change DocumentRoot:**
If your panel doesn't allow changing the document root, the fallback `index.php` in the root directory will handle redirects automatically. However, for best performance and security, always configure the DocumentRoot to point to the `public/` folder.

### Step 3: Configure PHP Settings

1. In aaPanel, go to **Website > Your Site > PHP Version**
2. Select **PHP 8.0** or higher
3. Click **PHP Extensions** and ensure these are installed:
   - mysqli / pdo_mysql
   - gd
   - mbstring
   - curl
   - openssl
   - fileinfo
   - zip

### Step 4: Set Permissions

1. In aaPanel File Manager or via SSH:
   ```bash
   cd /www/wwwroot/yourdomain.com
   chmod -R 755 .
   chmod -R 777 config
   chmod -R 777 public/uploads
   chown -R www:www .
   ```

### Step 5: Create Database

1. In aaPanel, go to **Database > Add Database**
2. Create database name (e.g., `peepit_db`)
3. Create database user with password
4. Grant all privileges to the user
5. Note down: database name, username, and password

### Step 6: Configure SSL (Optional but Recommended)

1. In aaPanel, go to **Website > Your Site > SSL**
2. Choose one of:
   - **Let's Encrypt** (Free): Click "Let's Encrypt" tab and apply
   - **Other Certificate**: Upload your SSL certificate files
3. Enable **Force HTTPS** option

### Step 7: Run Installer

1. Navigate to `https://yourdomain.com/install/` (or `http://` if no SSL)
2. Follow the installation wizard:
   - Check system requirements (should all pass if PHP extensions are installed)
   - Enter database credentials from Step 5
   - Import database schema
   - Create admin account
   - Complete installation

3. **Important:** Delete the `install` folder after installation:
   ```bash
   rm -rf /www/wwwroot/yourdomain.com/install
   ```

### Step 8: Configure Rewrite Rules (If needed)

If the site still shows errors after setting DocumentRoot to `/public`:

1. In aaPanel, go to **Website > Your Site > Rewrite**
2. Select **Other** from the dropdown
3. Add this rewrite rule:
   ```apache
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   ```
4. Save and restart the web server

### Troubleshooting aaPanel Deployment

**Error: "This page isn't working" or "No matching DirectoryIndex"**
- **Solution:** Make sure DocumentRoot is set to `/www/wwwroot/yourdomain.com/public` in aaPanel Site Directory settings
- Alternative: Ensure the fallback `index.php` file exists in the root directory

**Error: "500 Internal Server Error"**
- **Solution:** Check file permissions, especially `config/` and `public/uploads/` folders (should be 777)
- Check PHP error logs in aaPanel > Website > Your Site > Log

**Error: SSL Certificate Warning**
- **Solution:** In aaPanel SSL settings, ensure your certificate matches your domain name
- For Let's Encrypt, make sure your domain DNS is properly pointing to the server

**Error: Database Connection Failed**
- **Solution:** Verify database credentials in the installer
- Make sure the database user has all privileges on the database

### aaPanel Performance Optimization

After installation:

1. **Enable OPcache:**
   - Go to Software Store > PHP > Settings > Configuration
   - Find `opcache.enable` and set to `1`
   - Set `opcache.memory_consumption=128`

2. **Enable GZIP Compression:**
   - Already configured in `.htaccess` for Apache
   - For Nginx, it's usually enabled by default in aaPanel

3. **Configure PHP-FPM:**
   - Go to Software Store > PHP > Settings > FPM
   - Adjust `pm.max_children` based on your server RAM (default: 20 for 1GB RAM)

## VPS/Dedicated Server

### Option 1: Ubuntu/Debian with Apache

#### 1. Install Dependencies

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache
sudo apt install apache2 -y

# Install PHP 8.0+
sudo apt install php8.1 php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml -y

# Install MySQL
sudo apt install mysql-server -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Enable Apache modules
sudo a2enmod rewrite
sudo a2enmod headers
sudo systemctl restart apache2
```

#### 2. Clone and Setup

```bash
# Clone repository
cd /var/www/
sudo git clone https://github.com/faruk-spec/peepit.git
cd peepit

# Install dependencies
sudo composer install --no-dev --optimize-autoloader

# Set permissions
sudo chown -R www-data:www-data /var/www/peepit
sudo chmod -R 755 /var/www/peepit
sudo chmod -R 777 /var/www/peepit/config
sudo chmod -R 777 /var/www/peepit/public/uploads
```

#### 3. Configure Apache Virtual Host

```bash
sudo nano /etc/apache2/sites-available/peepit.conf
```

Add the following configuration:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/peepit/public

    <Directory /var/www/peepit/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/peepit-error.log
    CustomLog ${APACHE_LOG_DIR}/peepit-access.log combined
</VirtualHost>
```

Enable the site:

```bash
sudo a2ensite peepit.conf
sudo systemctl reload apache2
```

#### 4. Setup MySQL Database

```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE peepit CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'peepit_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON peepit.* TO 'peepit_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### 5. Run Installer

Navigate to `http://yourdomain.com/install/` and complete the installation.

### Option 2: Nginx Setup

#### 1. Install Nginx and PHP-FPM

```bash
sudo apt install nginx php8.1-fpm -y
```

#### 2. Configure Nginx

```bash
sudo nano /etc/nginx/sites-available/peepit
```

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/peepit/public;
    
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 365d;
    }
}
```

Enable and restart:

```bash
sudo ln -s /etc/nginx/sites-available/peepit /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

## Docker Deployment

### Dockerfile

Create `Dockerfile` in the project root:

```dockerfile
FROM php:8.1-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip

# Enable Apache modules
RUN a2enmod rewrite headers

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/config \
    && chmod -R 777 /var/www/html/public/uploads

# Configure Apache DocumentRoot
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80
```

### docker-compose.yml

```yaml
version: '3.8'

services:
  web:
    build: .
    ports:
      - "80:80"
    volumes:
      - ./:/var/www/html
    depends_on:
      - db
    environment:
      - DB_HOST=db
      - DB_NAME=peepit
      - DB_USER=peepit
      - DB_PASS=peepit_password

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: peepit
      MYSQL_USER: peepit
      MYSQL_PASSWORD: peepit_password
      MYSQL_ROOT_PASSWORD: root_password
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"

volumes:
  db_data:
```

### Deploy with Docker

```bash
# Build and start containers
docker-compose up -d

# Check status
docker-compose ps

# View logs
docker-compose logs -f web
```

Access the installer at `http://localhost/install/`

## SSL Certificate Setup

### Option 1: Let's Encrypt (Free)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# For Apache
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# For Nginx
sudo apt install python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo certbot renew --dry-run
```

### Option 2: Commercial SSL

1. Purchase SSL certificate
2. Upload certificate files to server
3. Configure in Apache/Nginx virtual host
4. Restart web server

## Post-Deployment Steps

### 1. Security Hardening

```bash
# Remove install directory
rm -rf install/

# Set restrictive permissions
chmod 644 config/*.php
chmod 755 public/uploads

# Disable directory listing
# Already configured in .htaccess
```

### 2. Performance Optimization

#### Enable OPcache

```bash
sudo nano /etc/php/8.1/apache2/php.ini
```

Add:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

#### Enable Gzip Compression

Apache:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>
```

### 3. Monitoring Setup

Install monitoring tools:

```bash
# Install fail2ban for security
sudo apt install fail2ban -y

# Setup log rotation
sudo nano /etc/logrotate.d/peepit
```

```
/var/www/peepit/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0640 www-data www-data
}
```

### 4. Backup Configuration

Create backup script:

```bash
sudo nano /root/backup-peepit.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/peepit"
mkdir -p $BACKUP_DIR

# Backup files
tar -czf $BACKUP_DIR/peepit_files_$DATE.tar.gz /var/www/peepit

# Backup database
mysqldump -u peepit_user -p'password' peepit | gzip > $BACKUP_DIR/peepit_db_$DATE.sql.gz

# Keep only last 30 days
find $BACKUP_DIR -mtime +30 -delete
```

```bash
chmod +x /root/backup-peepit.sh

# Add to crontab (daily at 2 AM)
(crontab -l ; echo "0 2 * * * /root/backup-peepit.sh") | crontab -
```

### 5. Configure Firewall

```bash
# UFW (Ubuntu)
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable

# Or iptables
sudo iptables -A INPUT -p tcp --dport 22 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 80 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 443 -j ACCEPT
```

## Troubleshooting

### Issue: White Screen / 500 Error

**Solution:**
```bash
# Check PHP error log
sudo tail -f /var/log/apache2/error.log

# Enable error display (temporarily)
sudo nano /etc/php/8.1/apache2/php.ini
# Set: display_errors = On
```

### Issue: Permission Denied

**Solution:**
```bash
sudo chown -R www-data:www-data /var/www/peepit
sudo chmod -R 755 /var/www/peepit
sudo chmod -R 777 /var/www/peepit/config
sudo chmod -R 777 /var/www/peepit/public/uploads
```

### Issue: Database Connection Failed

**Solution:**
1. Check database credentials in `/config/database.php`
2. Verify MySQL is running: `sudo systemctl status mysql`
3. Test connection: `mysql -u username -p database_name`

### Issue: .htaccess Not Working

**Solution:**
```bash
# Enable mod_rewrite
sudo a2enmod rewrite

# Check AllowOverride in virtual host
# Should be: AllowOverride All

sudo systemctl restart apache2
```

### Issue: Upload Errors

**Solution:**
```bash
# Check PHP upload settings
sudo nano /etc/php/8.1/apache2/php.ini

# Increase limits:
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300

sudo systemctl restart apache2
```

### Issue: Email Not Sending

**Solution:**
1. Verify SMTP credentials
2. Check firewall allows outbound SMTP: `telnet smtp.server.com 587`
3. Check email logs in admin panel
4. Test with simple PHP mail script

## Performance Tuning

### MySQL Optimization

```sql
-- Add indexes for better performance
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_order_items_order_id ON order_items(order_id);
CREATE INDEX idx_user_analytics_user_id ON user_analytics(user_id);
```

### Apache Optimization

```apache
# Enable KeepAlive
KeepAlive On
MaxKeepAliveRequests 100
KeepAliveTimeout 5

# Set appropriate limits
<IfModule mpm_prefork_module>
    StartServers 5
    MinSpareServers 5
    MaxSpareServers 10
    MaxRequestWorkers 150
    MaxConnectionsPerChild 3000
</IfModule>
```

## Maintenance

### Regular Tasks

- **Daily:** Check error logs
- **Weekly:** Review order reports, backup verification
- **Monthly:** Update dependencies, security patches
- **Quarterly:** Performance review, database optimization

### Update Procedure

```bash
# Backup first!
/root/backup-peepit.sh

# Pull latest changes
cd /var/www/peepit
git pull origin main

# Update dependencies
composer install --no-dev

# Clear cache if any
rm -rf cache/*

# Restart services
sudo systemctl restart apache2
```

## Support

For issues or questions:
- GitHub Issues: https://github.com/faruk-spec/peepit/issues
- Email: support@peepit.com

## License

MIT License - See LICENSE file for details
