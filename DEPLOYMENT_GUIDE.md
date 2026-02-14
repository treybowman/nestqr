# NestQR Deployment Guide

## Prerequisites
- PHP 8.0+
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Web server (Apache/Nginx)
- SSL certificate (Let's Encrypt recommended)

## Step-by-Step Deployment

### 1. Upload Files
Upload all files to your web host. Most hosts use `/var/www/html` or `/home/username/public_html`.

### 2. Install PHP Dependencies
```bash
cd /path/to/nestqr
composer install
```

This will install:
- PHPMailer (email sending)
- Endroid QR Code (QR generation)

### 3. Create Database
```bash
mysql -u your_username -p
```

Then in MySQL:
```sql
CREATE DATABASE nestqr_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

Import the schema:
```bash
mysql -u your_username -p nestqr_db < database.sql
```

### 4. Configure Application
```bash
cp includes/config.example.php includes/config.php
nano includes/config.php
```

Update these values:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'nestqr_db');

define('SITE_URL', 'https://nestqr.com');
define('SITE_EMAIL', 'support@nestqr.com');

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
```

### 5. Set File Permissions
```bash
chmod 755 public
chmod 777 public/uploads
chmod 777 public/uploads/logos
chmod 777 public/uploads/photos
chmod 777 public/uploads/qr-codes
```

### 6. Configure Web Server

**Apache** (most common - .htaccess already included):
Make sure mod_rewrite is enabled:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

**Nginx** (if using Nginx):
Use the provided `nginx.conf.example` as a template.

### 7. Setup SSL (Required)
```bash
# Using Let's Encrypt (free)
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d nestqr.com -d www.nestqr.com
```

For additional domains (nestatl.com, etc):
```bash
sudo certbot --apache -d nestatl.com -d *.nestatl.com
```

### 8. Test Installation
Visit https://nestqr.com

You should see the landing page. Try:
1. Signing up for an account
2. Creating a QR code
3. Viewing a listing page

### 9. Production Checklist
- [ ] Change error_reporting to 0 in config.php
- [ ] Set strong database password
- [ ] Configure backup system
- [ ] Set up monitoring (UptimeRobot, etc)
- [ ] Configure SMTP for transactional emails
- [ ] Test email delivery
- [ ] Create admin account

## Adding New Market Domains

### 1. Purchase Domain
Buy domain (e.g., nestdfw.com) from registrar

### 2. Point to Cloudflare
Add domain to Cloudflare and update nameservers

### 3. DNS Configuration
In Cloudflare DNS:
```
Type: A
Name: @
IPv4: your_server_ip
Proxy: ON (orange cloud)

Type: A  
Name: *
IPv4: your_server_ip
Proxy: ON (orange cloud)
```

### 4. SSL/TLS Settings
Cloudflare → SSL/TLS → Full (strict)
Enable "Always Use HTTPS"

### 5. Server Configuration
Add to your web server config:
```
server_name nestqr.com nestatl.com nestdfw.com *.nestqr.com *.nestatl.com *.nestdfw.com;
```

### 6. Database Update
```sql
INSERT INTO active_domains (domain, market_name, is_active, launched_at) 
VALUES ('nestdfw', 'Dallas', TRUE, CURDATE());
```

### 7. Update Config
In `includes/config.php`, add to $ACTIVE_DOMAINS array:
```php
'nestdfw' => [
    'name' => 'Dallas',
    'url' => 'https://nestdfw.com',
    'is_primary' => false
]
```

## Troubleshooting

### "Database connection failed"
- Check config.php credentials
- Verify MySQL is running: `sudo systemctl status mysql`
- Test connection: `mysql -u username -p`

### "Page not found" or routing issues
- Check .htaccess is present in public/ directory
- Verify mod_rewrite is enabled (Apache)
- Check web server error logs

### Emails not sending
- Verify SMTP credentials in config.php
- For Gmail: Use App Password, not regular password
- Check spam folder
- Review PHP error logs

### QR codes not generating
- Run `composer install` to install dependencies
- Check GD extension: `php -m | grep gd`
- Verify uploads/ folder is writable

### Images not uploading
- Check file permissions on public/uploads/
- Verify max upload size in php.ini:
  ```ini
  upload_max_filesize = 10M
  post_max_size = 10M
  ```

## Security Best Practices

1. **Keep software updated**
   ```bash
   composer update
   ```

2. **Use strong passwords**
   - Database: 20+ random characters
   - Admin accounts: unique, strong passwords

3. **Regular backups**
   ```bash
   # Database
   mysqldump -u user -p nestqr_db > backup.sql
   
   # Files
   tar -czf uploads-backup.tar.gz public/uploads/
   ```

4. **Monitor logs**
   - Check Apache/Nginx error logs daily
   - Review database slow queries
   - Monitor failed login attempts

5. **Disable directory listing**
   Already configured in .htaccess

## Performance Optimization

1. **Enable PHP OpCache**
   Edit php.ini:
   ```ini
   opcache.enable=1
   opcache.memory_consumption=128
   opcache.max_accelerated_files=10000
   ```

2. **Use Cloudflare CDN**
   Already set up if using Cloudflare DNS

3. **Database Indexing**
   Already optimized in schema

4. **Image Optimization**
   Consider adding image compression on upload

## Support

For issues:
1. Check error logs
2. Review this guide
3. Email: support@nestqr.com

## License
Proprietary - All Rights Reserved
