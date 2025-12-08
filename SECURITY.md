# Security Policy

## Supported Versions

We release patches for security vulnerabilities in the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |

## Reporting a Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

If you discover a security vulnerability in Peepit, please send an email to:

**security@peepit.com**

Include the following information:
- Type of issue (e.g., SQL injection, XSS, CSRF)
- Full paths of source file(s) related to the vulnerability
- Location of the affected source code (tag/branch/commit)
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the issue

### Response Timeline

- **Initial Response:** Within 48 hours
- **Assessment:** Within 1 week
- **Fix Development:** Depends on severity (critical: 24-48 hours, high: 1 week, medium: 2 weeks)
- **Public Disclosure:** After patch is released

## Security Best Practices

### For Administrators

1. **Keep Software Updated**
   ```bash
   git pull origin main
   composer update
   ```

2. **Use Strong Passwords**
   - Minimum 12 characters
   - Mix of uppercase, lowercase, numbers, symbols
   - Use password manager

3. **Enable HTTPS**
   - Use Let's Encrypt or commercial SSL
   - Force HTTPS in config: `force_https => true`

4. **Secure Database**
   ```sql
   -- Use strong password
   ALTER USER 'peepit_user'@'localhost' IDENTIFIED BY 'VeryStr0ng!P@ssw0rd';
   
   -- Limit permissions
   GRANT SELECT, INSERT, UPDATE, DELETE ON peepit.* TO 'peepit_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

5. **File Permissions**
   ```bash
   # Restrictive permissions
   chmod 755 /var/www/peepit
   chmod 644 /var/www/peepit/config/*.php
   chmod 755 /var/www/peepit/public/uploads
   
   # Only web server can write
   chown -R www-data:www-data /var/www/peepit
   ```

6. **Disable Directory Listing**
   - Already configured in `.htaccess`
   - Verify: `Options -Indexes`

7. **Hide Server Information**
   ```apache
   # In Apache config
   ServerTokens Prod
   ServerSignature Off
   ```

8. **Implement Rate Limiting**
   ```apache
   # In .htaccess or virtual host
   <IfModule mod_evasive.c>
       DOSHashTableSize 3097
       DOSPageCount 10
       DOSSiteCount 50
       DOSPageInterval 1
       DOSSiteInterval 1
       DOSBlockingPeriod 10
   </IfModule>
   ```

9. **Regular Backups**
   - Automated daily backups
   - Store offsite
   - Test restoration procedure

10. **Monitor Logs**
    ```bash
    # Check for suspicious activity
    tail -f /var/log/apache2/access.log
    tail -f /var/log/apache2/error.log
    
    # Check failed login attempts
    grep "Invalid credentials" /var/log/apache2/error.log
    ```

### For Developers

1. **Input Validation**
   ```php
   // Always sanitize input
   $input = sanitize($_POST['field']);
   
   // Validate data types
   $id = intval($_POST['id']);
   
   // Validate email
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       throw new Exception('Invalid email');
   }
   ```

2. **Output Escaping**
   ```php
   // Always escape output
   echo escape($userInput);
   
   // In views
   <?= escape($variable) ?>
   ```

3. **SQL Injection Prevention**
   ```php
   // Always use prepared statements
   $stmt = $db->query(
       "SELECT * FROM users WHERE email = ?",
       [$email]
   );
   
   // Never concatenate SQL
   // BAD: "SELECT * FROM users WHERE email = '$email'"
   ```

4. **CSRF Protection**
   ```php
   // Generate token
   $token = $this->generateCSRF();
   
   // In forms
   <?= csrf_field() ?>
   
   // Validate on submit
   $this->validateCSRF();
   ```

5. **Password Security**
   ```php
   // Hash passwords
   $hash = password_hash($password, PASSWORD_DEFAULT);
   
   // Verify passwords
   if (password_verify($input, $hash)) {
       // Correct
   }
   
   // Never store plain text passwords!
   ```

6. **Session Security**
   ```php
   // Configure session settings
   ini_set('session.cookie_httponly', 1);
   ini_set('session.cookie_secure', 1);
   ini_set('session.use_strict_mode', 1);
   
   // Regenerate session ID on login
   session_regenerate_id(true);
   ```

7. **File Upload Security**
   ```php
   // Validate file type
   $allowedTypes = ['image/jpeg', 'image/png'];
   $finfo = new finfo(FILEINFO_MIME_TYPE);
   $mimeType = $finfo->file($_FILES['file']['tmp_name']);
   
   if (!in_array($mimeType, $allowedTypes)) {
       throw new Exception('Invalid file type');
   }
   
   // Validate file size
   if ($_FILES['file']['size'] > 5242880) { // 5MB
       throw new Exception('File too large');
   }
   
   // Use random filename
   $filename = uniqid() . '_' . time() . '.jpg';
   ```

8. **Error Handling**
   ```php
   // Never expose sensitive information
   try {
       // Code
   } catch (Exception $e) {
       error_log($e->getMessage()); // Log full error
       throw new Exception('An error occurred'); // Generic user message
   }
   
   // Disable error display in production
   ini_set('display_errors', 0);
   error_reporting(0);
   ```

9. **API Security**
   ```php
   // Use authentication tokens
   // Implement rate limiting
   // Validate content type
   
   if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
       http_response_code(415);
       exit;
   }
   ```

10. **Dependencies**
    ```bash
    # Keep dependencies updated
    composer update
    
    # Check for vulnerabilities
    composer audit
    ```

## Security Features in Peepit

### Built-in Protection

1. **CSRF Protection**
   - All forms include CSRF tokens
   - Validated on submission
   - Token regeneration on login

2. **XSS Prevention**
   - Output escaping with `htmlspecialchars()`
   - Helper functions: `escape()`, `sanitize()`
   - Content Security Policy headers

3. **SQL Injection Prevention**
   - PDO prepared statements
   - No raw SQL concatenation
   - Input validation

4. **Password Security**
   - bcrypt hashing
   - Minimum length requirements
   - No plain text storage

5. **Session Security**
   - HTTP-only cookies
   - Secure flag for HTTPS
   - Session regeneration
   - Timeout after inactivity

6. **File Upload Security**
   - MIME type validation
   - File size limits
   - Random filename generation
   - Separate upload directory

7. **Access Control**
   - Role-based permissions
   - Authentication required for sensitive operations
   - Authorization checks on all admin routes

8. **Headers Security**
   ```apache
   # Configured in .htaccess
   Header set X-Content-Type-Options "nosniff"
   Header set X-Frame-Options "SAMEORIGIN"
   Header set X-XSS-Protection "1; mode=block"
   Header set Referrer-Policy "strict-origin-when-cross-origin"
   ```

9. **Directory Protection**
   - `.htaccess` files block direct access
   - Options -Indexes
   - Sensitive files outside web root

10. **HTTPS Enforcement**
    - Automatic redirect to HTTPS
    - Configurable in `config/app.php`

## Common Vulnerabilities & Mitigations

### SQL Injection
- **Risk:** Attackers can execute malicious SQL
- **Mitigation:** PDO prepared statements, input validation
- **Status:** ✅ Protected

### Cross-Site Scripting (XSS)
- **Risk:** Malicious scripts in user input
- **Mitigation:** Output escaping, CSP headers
- **Status:** ✅ Protected

### Cross-Site Request Forgery (CSRF)
- **Risk:** Unauthorized actions on behalf of user
- **Mitigation:** CSRF tokens on all forms
- **Status:** ✅ Protected

### Session Hijacking
- **Risk:** Attacker steals session cookie
- **Mitigation:** Secure cookies, HTTPS, session regeneration
- **Status:** ✅ Protected

### File Upload Attacks
- **Risk:** Malicious file upload
- **Mitigation:** MIME validation, size limits, random names
- **Status:** ✅ Protected

### Brute Force Attacks
- **Risk:** Password guessing attacks
- **Mitigation:** Rate limiting (recommended), strong passwords
- **Status:** ⚠️ Rate limiting should be implemented

### Directory Traversal
- **Risk:** Access to unauthorized files
- **Mitigation:** Input validation, path sanitization
- **Status:** ✅ Protected

### Remote Code Execution
- **Risk:** Attacker executes code on server
- **Mitigation:** No eval(), proper file validation
- **Status:** ✅ Protected

## Security Checklist

### Before Going Live

- [ ] Change all default passwords
- [ ] Enable HTTPS
- [ ] Delete install directory
- [ ] Set restrictive file permissions
- [ ] Configure firewall
- [ ] Set up automated backups
- [ ] Configure SMTP securely
- [ ] Review all user roles
- [ ] Test all security features
- [ ] Enable error logging (disable display)
- [ ] Configure rate limiting
- [ ] Set up monitoring/alerts
- [ ] Review database permissions
- [ ] Scan for vulnerabilities
- [ ] Document security procedures

### Regular Maintenance

- [ ] Review access logs weekly
- [ ] Update dependencies monthly
- [ ] Rotate database passwords quarterly
- [ ] Audit user accounts monthly
- [ ] Test backups monthly
- [ ] Review security patches
- [ ] Update SSL certificates before expiry
- [ ] Review and update firewall rules

## Incident Response

If a security breach occurs:

1. **Immediate Actions**
   - Take affected systems offline
   - Change all passwords
   - Review access logs
   - Identify attack vector

2. **Assessment**
   - Determine scope of breach
   - Identify compromised data
   - Document everything

3. **Containment**
   - Patch vulnerabilities
   - Remove malicious code
   - Update security measures

4. **Recovery**
   - Restore from clean backups
   - Verify system integrity
   - Bring systems back online

5. **Post-Incident**
   - Notify affected users
   - Review and improve security
   - Update documentation
   - File necessary reports

## Contact

For security concerns:
- Email: security@peepit.com
- PGP Key: [Available on request]

For general questions:
- GitHub Issues: https://github.com/faruk-spec/peepit/issues
- Email: support@peepit.com

## Acknowledgments

We appreciate responsible disclosure and will credit security researchers who help improve Peepit's security.
