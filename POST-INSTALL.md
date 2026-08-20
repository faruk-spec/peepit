# Post-Installation Configuration

## 🎉 Installation Complete!

Your Peepit installation is complete, but you need to configure your server properly before accessing the site.

## ⚠️ Choose ONE Configuration Method

### Method 1: Root DocumentRoot (RECOMMENDED for aaPanel)

**This is the simplest method and avoids open_basedir issues.**

#### Steps:
1. Log into aaPanel
2. Go to **Website** → Click on your site (e.g., `peepit.mymultibranch.com`)
3. Click **Site Directory** tab
4. Set **Running Directory** to: `/` (just a forward slash)
5. Click **Save**
6. Click **Service** → **Restart** Apache/Nginx

#### Verify:
```bash
# Your DocumentRoot should be:
/www/wwwroot/peepit.mymultibranch.com
```

✅ **Advantages:**
- No open_basedir configuration needed
- Fewer steps
- Works immediately
- Still secure (root .htaccess blocks sensitive directories)

---

### Method 2: /public DocumentRoot (More Secure, Requires Extra Step)

**This method hides all backend files outside web root but requires open_basedir configuration.**

#### Steps:
1. Log into aaPanel
2. Go to **Website** → Click on your site
3. Click **Site Directory** tab
4. Set **Running Directory** to: `/public`
5. Click **Save**
6. Scroll down to **Security** section (or **防跨站设置** in Chinese)
7. Find **Open Basedir** field
8. It will show: `/www/wwwroot/peepit.mymultibranch.com/public/:/tmp/`
9. Change it to: `/www/wwwroot/peepit.mymultibranch.com/:/tmp/` 
   - **IMPORTANT:** Remove the `/public` part, keep just the domain root
10. Click **Save**
11. Click **Service** → **Restart PHP-FPM**

#### Verify:
```bash
# Your DocumentRoot should be:
/www/wwwroot/peepit.mymultibranch.com/public

# Your open_basedir should be:
/www/wwwroot/peepit.mymultibranch.com/:/tmp/
```

✅ **Advantages:**
- Backend files completely outside web root
- Slightly more secure
- Industry best practice

---

## 🔒 Security: Delete Installation Directory

After configuring the above, **immediately delete** the `/install` directory:

```bash
# SSH into your server
ssh user@your-server

# Navigate to your site directory
cd /www/wwwroot/peepit.mymultibranch.com

# Delete the installer
rm -rf install/

# Verify it's gone
ls -la | grep install  # Should show nothing
```

**OR** via aaPanel File Manager:
1. Go to **Files**
2. Navigate to `/www/wwwroot/peepit.mymultibranch.com/`
3. Right-click on `install` folder
4. Click **Delete**

---

## 🧪 Testing Your Installation

### Test 1: Homepage
Visit: `https://yourdomain.com/`
- Should show: Peepit home page
- Should NOT show: Any PHP errors

### Test 2: Admin Login
Visit: `https://yourdomain.com/admin/login`
- Should show: Admin login form
- Login with credentials created during installation

### Test 3: Installer Blocked
Visit: `https://yourdomain.com/install/`
- Should show: 404 Not Found (after deletion)
- OR redirect to homepage (if config exists)

---

## ❌ Common Errors & Solutions

### Error: "open_basedir restriction in effect"

**Full Error:**
```
Warning: require_once(): open_basedir restriction in effect. 
File(/www/wwwroot/peepit.mymultibranch.com/vendor/autoload.php) 
is not within the allowed path(s)
```

**Causes:** 
- **Cause A:** You set DocumentRoot to `/public` but didn't update open_basedir setting
- **Cause B:** You're accessing `/public/` directly in the URL with Method 1

**Solutions:**

**If using Method 1 (Root DocumentRoot):**
- **Don't access** `https://yourdomain.com/public/` directly
- **Always use** `https://yourdomain.com/` (root URL)
- The `.htaccess` automatically redirects `/public/` URLs to root
- Clear your browser cache if needed

**If using Method 2 (Public DocumentRoot):**
Follow **Method 2** above completely, especially steps 6-11 for open_basedir configuration.

**Quick Fix for Method 2:**
```bash
# In aaPanel → Website → Your Site → Site Directory → Security
# Change open_basedir from:
/www/wwwroot/peepit.mymultibranch.com/public/:/tmp/

# To (remove /public):
/www/wwwroot/peepit.mymultibranch.com/:/tmp/
```

---

### Error: "Failed to open stream: No such file or directory"

**Full Error:**
```
Warning: require(/www/wwwroot/peepit.mymultibranch.com/app/helpers/../config/app.php): 
Failed to open stream: No such file or directory
```

**Cause:**
- Configuration files missing (installer not completed or failed)
- Installation completed before latest fix was applied

**Solution:**
1. Verify config files exist:
   ```bash
   ls -la /www/wwwroot/peepit.mymultibranch.com/config/
   ```
   Should show: `database.php`, `app.php`, `smtp.php`
   
2. If `app.php` is missing but `database.php` exists:
   ```bash
   cd /www/wwwroot/peepit.mymultibranch.com
   git pull origin copilot/add-web-installer-setup
   # Then delete install/ and re-run installer
   rm -rf install/
   git checkout origin/copilot/add-web-installer-setup -- install/
   # Visit /install/ in browser and complete steps 1-6
   ```

3. If all config files are missing, re-run installer at `/install/`

4. After reinstallation, ensure DocumentRoot is set correctly (use Method 1 or Method 2 above)

---

### Error: "Internal Server Error" (500)

**Cause:**
- `.htaccess` syntax error
- OR wrong Apache version

**Solution:**
1. Check Apache error log:
   ```bash
   tail -f /www/wwwlogs/peepit.mymultibranch.com-error_log
   ```

2. Ensure you have latest code:
   ```bash
   cd /www/wwwroot/peepit.mymultibranch.com
   git pull origin copilot/add-web-installer-setup
   ```

3. Restart Apache:
   - aaPanel → Service → Apache → Restart

---

## 📞 Need Help?

### Recommended Configuration for aaPanel:
- **Method:** Method 1 (Root DocumentRoot)
- **DocumentRoot:** `/www/wwwroot/yourdomain.com`
- **Running Directory:** `/`
- **No open_basedir changes needed**

### Check Current Configuration:
```bash
# SSH into server
cd /www/wwwroot/peepit.mymultibranch.com

# Check if root index.php exists (for Method 1)
ls -la index.php

# Check if files are present
ls -la app/ config/ vendor/ public/

# Check permissions
ls -la config/ public/uploads/
```

### Required Permissions:
```bash
# Set correct permissions
chmod -R 755 /www/wwwroot/peepit.mymultibranch.com
chmod -R 777 /www/wwwroot/peepit.mymultibranch.com/config
chmod -R 777 /www/wwwroot/peepit.mymultibranch.com/public/uploads
chown -R www:www /www/wwwroot/peepit.mymultibranch.com
```

---

## 🚀 Next Steps

After successful configuration:

1. ✅ Log into Admin Panel: `/admin/login`
2. ✅ Configure SMTP: Admin → Settings → SMTP
3. ✅ Add Bottle Models: Admin → Bottles → Add New
4. ✅ Add Bottle Sizes: Admin → Sizes → Add New
5. ✅ Add Color Presets: Admin → Colors → Add New
6. ✅ Customize Settings: Admin → Settings → General

---

## 📚 Documentation

- **Main Guide:** `README.md`
- **aaPanel Specific:** `AAPANEL-GUIDE.md`
- **Deployment:** `DEPLOYMENT.md`
- **Security:** `SECURITY.md`
- **API Reference:** `API.md`

---

**Happy Ordering! 🚰**
