# aaPanel / BT Panel Deployment Guide for Peepit

This guide specifically addresses deployment on aaPanel (BaoTa Panel) servers.

> **📢 AFTER INSTALLATION:** See [POST-INSTALL.md](POST-INSTALL.md) for required configuration steps!

## Quick Fix for Common Errors

### Recommended Approach: Use Method B (Root DocumentRoot)

**This is the SIMPLEST solution that avoids open_basedir issues entirely:**

1. **Keep DocumentRoot at root level:** `/www/wwwroot/yourdomain.com`
2. **Ensure the root `.htaccess` is configured correctly** (pull latest changes)
3. **Ensure `index.php` exists in the root directory** (pull latest changes)
4. **No need to modify open_basedir settings**
5. The root `index.php` handles all routing automatically
6. **⚠️ IMPORTANT: Access site at `https://yourdomain.com/` NOT `https://yourdomain.com/public/`**

**To verify your setup:**
```bash
cd /www/wwwroot/yourdomain.com
ls -la index.php  # Should exist in root
ls -la .htaccess  # Should exist in root
```

**Common Mistake:**
- ❌ **DON'T access:** `https://yourdomain.com/public/` 
- ✅ **DO access:** `https://yourdomain.com/`
- The `.htaccess` automatically redirects `/public/` URLs to root

### Alternative: Method A (More Secure but Requires open_basedir Configuration)

If you prefer to set DocumentRoot to `/public`:

**Step 1:** Set DocumentRoot to `/public`
1. aaPanel → **Website** → Your site → **Site Directory**
2. Change **Running Directory** from `/` to `/public`
3. Click **Save**

**Step 2:** Configure open_basedir (CRITICAL)
1. Same page, scroll to **Security** section
2. Find **Open Basedir** setting
3. Change from: `/www/wwwroot/yourdomain.com/public/:/tmp/`
4. Change to: `/www/wwwroot/yourdomain.com/:/tmp/`
   - **Remove `/public` from the path**
5. Click **Save**
6. Restart PHP-FPM

**Note:** Method B (root DocumentRoot) is recommended for aaPanel deployments as it avoids the open_basedir configuration step entirely.

## Complete aaPanel Setup Guide

### Step 1: Upload Files

Upload via FTP/SFTP to: `/www/wwwroot/yourdomain.com/`

Ensure these folders exist:
```
/www/wwwroot/yourdomain.com/
├── app/
├── config/
├── install/
├── public/
├── vendor/
├── index.php (fallback file)
└── composer.json
```

### Step 2: Choose DocumentRoot Configuration Method

Choose ONE of the following methods:

#### Method A: DocumentRoot to /public (More Secure - Requires open_basedir Config)

This method is more secure but requires additional configuration of `open_basedir`.

**For Apache:**
1. Website → Your Site → **Site Directory**
2. Set **Running Directory**: `/public`
3. Save

**For Nginx:**
1. Website → Your Site → **Site Directory**
2. Set **Running Directory**: `/public`
3. Check **Rewrite** tab, ensure it has:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

**Then proceed to Step 3 to configure open_basedir (REQUIRED for Method A).**

#### Method B: DocumentRoot to Root Directory (RECOMMENDED for aaPanel - Simpler)

This is the **recommended method for aaPanel** deployments as it avoids open_basedir configuration issues.

**For Apache:**
1. Website → Your Site → **Config File** (or keep default settings)
2. Ensure `DocumentRoot` points to root: `"/www/wwwroot/yourdomain.com"`
3. Verify the `<Directory>` section has:
```apache
<Directory "/www/wwwroot/yourdomain.com">
    SetOutputFilter DEFLATE
    Options FollowSymLinks
    AllowOverride All
    Require all granted
    DirectoryIndex index.php index.html index.htm default.php default.html default.htm
</Directory>
```
4. Save and restart Apache

**For Nginx:**
1. Keep root directive pointing to project root: `/www/wwwroot/yourdomain.com`
2. Ensure proper rewrite rules in site config:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

**What happens:**
- The root `index.php` file acts as a router
- It automatically serves files from `/public` directory
- It handles installer access at `/install/`
- No need to modify `open_basedir` settings

**Advantages of Method B:**
- ✅ No need to modify `open_basedir` settings
- ✅ Simpler configuration
- ✅ Fewer permission issues
- ✅ The root `index.php` file handles all routing automatically
- ✅ Works out of the box with aaPanel defaults

**Disadvantages of Method B:**
- ❌ Slightly less secure (project files accessible if .htaccess fails)
- ❌ Not the standard MVC pattern deployment

**Security Note:** The root `.htaccess` file blocks direct access to sensitive directories (app/, config/, vendor/) even with Method B.

**Skip to Step 4 (Install PHP Extensions) if you chose Method B.**

### Step 3: Configure PHP Security Settings (Only for Method A)

**Skip this step if you chose Method B above.**

**IMPORTANT:** After setting DocumentRoot to `/public`, you must adjust the `open_basedir` restriction:

1. Website → Your Site → **Site Directory**
2. Find the **Security** section (may need to scroll down)
3. Look for **Open Basedir** or **防跨站设置** (Anti-cross-site setting)
4. Change from:
   ```
   /www/wwwroot/yourdomain.com/public/:/tmp/
   ```
   To:
   ```
   /www/wwwroot/yourdomain.com/:/tmp/
   ```
   (Remove `/public` from the path - allow access to entire project directory)
5. Click **Save**

**Why this is needed:** PHP needs to access files in `vendor/`, `app/`, `config/`, and `install/` folders which are outside the `/public` directory. The `open_basedir` restriction prevents this access for security, but our application architecture requires parent directory access.

### Step 4: Install PHP Extensions

Go to **Software Store** → **PHP 8.0** (or higher) → **Settings** → **Install Extensions**

Required extensions:
- [x] mysqli / pdo_mysql
- [x] gd
- [x] mbstring
- [x] curl
- [x] openssl
- [x] fileinfo
- [x] zip

### Step 5: Set File Permissions

In Terminal or SSH:
```bash
cd /www/wwwroot/yourdomain.com
chmod -R 755 .
chmod -R 777 config
chmod -R 777 public/uploads
chown -R www:www .
```

Or in aaPanel File Manager:
- Right-click on `config` folder → Permissions → Set to `777`
- Right-click on `public/uploads` folder → Permissions → Set to `777`

### Step 6: Create Database

1. **Database** → **Add Database**
   - Database Name: `peepit_db` (or your choice)
   - Username: `peepit_user`
   - Password: (create strong password)

2. **Grant Privileges:**
   - Select database → **Privileges** → Grant **ALL** to your user

3. **Note down:**
   - Database host: `localhost`
   - Database name: `peepit_db`
   - Database user: `peepit_user`
   - Database password: (your password)

### Step 7: Configure SSL (Recommended)

1. **Website** → Your Site → **SSL**
2. Choose **Let's Encrypt** (free)
3. Apply for certificate
4. Enable **Force HTTPS**

### Step 8: Install Composer Dependencies

**Option A: Via SSH**
```bash
cd /www/wwwroot/yourdomain.com
composer install --no-dev --optimize-autoloader
```

**Option B: Upload with Dependencies**
- Install locally: `composer install --no-dev`
- Upload entire project including `vendor/` folder

### Step 9: Run Web Installer

1. Visit: `https://yourdomain.com/install/`

2. **Step 1 - Requirements:**
   - All items should show green checkmarks
   - If any fail, install missing PHP extensions

3. **Step 2 - Database:**
   - Host: `localhost`
   - Database: `peepit_db`
   - Username: `peepit_user`
   - Password: (your database password)
   - Click **Test Connection & Continue**

4. **Step 3 - Import Schema:**
   - Click **Import Database**
   - Wait for success message

5. **Step 4 - Admin Account:**
   - Full Name: Your name
   - Email: your@email.com
   - Password: (strong password, min 8 chars)
   - Confirm Password: (same password)
   - Click **Create Admin Account**

6. **Step 5 - Finalize:**
   - Click **Complete Installation**

7. **Step 6 - Cleanup:**
   - **IMPORTANT:** Delete install folder
   ```bash
   rm -rf /www/wwwroot/yourdomain.com/install
   ```

### Step 10: Access Your Site

- **Frontend:** `https://yourdomain.com`
- **Admin Panel:** `https://yourdomain.com/admin/login`

## Common aaPanel Issues & Solutions

### Issue 1: "Cannot serve directory" Error

**Error in logs:**
```
AH01276: Cannot serve directory: No matching DirectoryIndex found
```

**Solution:**
```bash
# Check current document root
Website → Your Site → Site Directory

# Should show: /www/wwwroot/yourdomain.com/public
# If it shows: /www/wwwroot/yourdomain.com

# Change Running Directory to: /public
```

### Issue 2: open_basedir Restriction Error (CRITICAL)

**Error:**
```
Warning: is_dir(): open_basedir restriction in effect. File(...) is not within the allowed path(s)
Warning: require_once(): open_basedir restriction in effect. File(/www/wwwroot/.../vendor/autoload.php) is not within the allowed path(s)
Fatal error: Failed opening required '.../vendor/autoload.php'
```

**Solution:**
This happens after setting DocumentRoot to `/public`. PHP's `open_basedir` security setting prevents access to parent directories.

1. Website → Your Site → **Site Directory**
2. Scroll down to **Security** section
3. Find **Open Basedir** (or **防跨站设置** in Chinese)
4. Current value will be: `/www/wwwroot/yourdomain.com/public/:/tmp/`
5. Change to: `/www/wwwroot/yourdomain.com/:/tmp/`
   - Remove the `/public` part to allow access to parent directory
6. Click **Save**
7. Restart PHP-FPM or Apache

**Why this happens:** The MVC architecture requires access to `vendor/`, `app/`, `config/` folders which are outside the `/public` directory. The `open_basedir` restriction must include the entire project directory.

### Issue 3: SSL Certificate Mismatch

**Error:**
```
SSL certificate does NOT include an ID which matches the server name
```

**Solution:**
1. Website → Your Site → SSL
2. Delete existing certificate
3. Re-apply Let's Encrypt certificate
4. Wait 2-3 minutes for DNS propagation
5. Ensure domain points to correct IP

### Issue 4: 500 Internal Server Error

**Solutions:**
1. Check file permissions:
   ```bash
   chmod -R 755 /www/wwwroot/yourdomain.com
   chmod -R 777 /www/wwwroot/yourdomain.com/config
   chmod -R 777 /www/wwwroot/yourdomain.com/public/uploads
   ```

2. Check PHP error logs:
   - Website → Your Site → **Log**
   - Look for PHP errors

3. Enable error display (temporarily):
   - Software Store → PHP → Settings → **php.ini**
   - Find: `display_errors = Off`
   - Change to: `display_errors = On`
   - Save and restart PHP

### Issue 5: Database Connection Failed

**Solution:**
1. Verify database credentials:
   ```bash
   # Check if database exists
   Database → List → Find your database
   
   # Test connection
   mysql -u peepit_user -p peepit_db
   ```

2. Check database permissions:
   - Database → Your Database → **Privileges**
   - User should have ALL privileges

3. Update config if needed:
   - Edit `/www/wwwroot/yourdomain.com/config/database.php`

### Issue 6: File Upload Not Working

**Solution:**
1. Check permissions:
   ```bash
   ls -la /www/wwwroot/yourdomain.com/public/uploads
   # Should show: drwxrwxrwx (777)
   ```

2. Check PHP upload settings:
   - Software Store → PHP → Settings → **php.ini**
   - Find and set:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   max_execution_time = 300
   ```

3. Restart PHP-FPM:
   - Software Store → PHP → **Settings** → **Service** → Restart

### Issue 7: .htaccess Not Working (Apache)

**Solution:**
1. Enable mod_rewrite:
   - Software Store → Apache → Settings → **Modules**
   - Find `rewrite_module` and enable it

2. Check AllowOverride:
   - Website → Your Site → **Config File**
   - Find `<Directory>` section
   - Ensure: `AllowOverride All`

3. Restart Apache

### Issue 8: Nginx 404 Errors

**Solution:**
1. Website → Your Site → **Rewrite**
2. Select **Other** from dropdown
3. Add:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/tmp/php-cgi-83.sock;
    fastcgi_index index.php;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
}
```
4. Save and restart Nginx

## Performance Optimization for aaPanel

### 1. Enable OPcache
```bash
# Software Store → PHP → Settings → php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### 2. Enable Redis (Optional)
```bash
# Software Store → Find Redis → Install
# Then in PHP → Install redis extension
```

### 3. Configure PHP-FPM
```bash
# Software Store → PHP → Settings → FPM Configuration

# For 1GB RAM server:
pm = dynamic
pm.max_children = 20
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 10

# For 2GB RAM server:
pm.max_children = 40
```

### 4. Enable GZIP Compression

**Apache:** Already configured in `.htaccess`

**Nginx:**
```nginx
# Website → Your Site → Config File
gzip on;
gzip_vary on;
gzip_min_length 1024;
gzip_types text/plain text/css application/json application/javascript text/xml application/xml;
```

## Security Hardening in aaPanel

### 1. Configure Firewall
```bash
# Security → System Firewall
# Allow only: 22 (SSH), 80 (HTTP), 443 (HTTPS)
```

### 2. Enable fail2ban
```bash
# Software Store → Find fail2ban → Install
# Protects against brute force attacks
```

### 3. Regular Backups
```bash
# Website → Your Site → Backup
# Set: Daily automatic backup
# Keep: Last 7 days
# Include: Database + Website files
```

### 4. Update Regularly
```bash
# Check for updates:
Software Store → Check for PHP/Apache/Nginx updates
```

## Monitoring & Logs

### Access Error Logs
```bash
# Website → Your Site → Log
# View:
# - Access log: /www/wwwlogs/yourdomain.com-access_log
# - Error log: /www/wwwlogs/yourdomain.com-error_log
```

### Monitor Server Resources
```bash
# Dashboard → System Status
# Monitor: CPU, RAM, Disk usage
```

### Check PHP Errors
```bash
# View PHP error log
tail -f /www/server/php/83/var/log/php-fpm.log
```

## Getting Help

If issues persist:

1. **Check aaPanel Forums:** https://forum.aapanel.com
2. **Check Peepit Documentation:** README.md, DEPLOYMENT.md
3. **Enable Debug Mode:** Set `display_errors = On` in PHP settings
4. **Review Logs:** Check both Apache/Nginx and PHP error logs
5. **GitHub Issues:** https://github.com/faruk-spec/peepit/issues

## Quick Command Reference

```bash
# Navigate to site
cd /www/wwwroot/yourdomain.com

# Check permissions
ls -la

# Set permissions
chmod -R 755 .
chmod -R 777 config public/uploads
chown -R www:www .

# Delete install folder
rm -rf install

# View error logs
tail -f /www/wwwlogs/yourdomain.com-error_log

# Restart services
/etc/init.d/apache2 restart  # For Apache
/etc/init.d/nginx restart    # For Nginx
/etc/init.d/php-fpm-83 restart  # For PHP-FPM
```

---

**Still having issues?** Check the main DEPLOYMENT.md for more troubleshooting options.
