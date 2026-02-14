# NestQR - Real Estate QR Code Platform

## Quick Start

### 1. Install Dependencies
```bash
composer install
```

### 2. Database Setup
```bash
mysql -u root -p < database.sql
```

### 3. Configuration
```bash
cp includes/config.example.php includes/config.php
# Edit includes/config.php with your database credentials
```

### 4. File Permissions
```bash
chmod 777 public/uploads/
chmod 777 public/uploads/logos
chmod 777 public/uploads/photos
chmod 777 public/uploads/qr-codes
```

### 5. Web Server
Point your web server to the `public/` directory.

**Apache:** .htaccess included
**Nginx:** See nginx.conf.example

### 6. SSL/HTTPS
Required for production. Use Let's Encrypt:
```bash
certbot --nginx -d nestqr.com -d www.nestqr.com
```

## Features
- ✅ Agent signup/login with email verification
- ✅ QR code generation with custom icons
- ✅ Mobile-optimized listing pages  
- ✅ Reusable QR slot system
- ✅ Scan analytics
- ✅ Multi-domain support
- ✅ Custom branding (Pro plans)

## Adding New Market Domains

1. Add domain to Cloudflare DNS
2. Configure SSL
3. Log into admin panel: /admin/domains.php
4. Follow setup instructions

## Support
Email: support@nestqr.com
