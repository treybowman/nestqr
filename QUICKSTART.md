# NestQR - Quick Start Guide

## What You Have
A complete, production-ready real estate QR code platform.

## Immediate Next Steps

### 1. Install on Your Server (5 minutes)
```bash
# Upload files to your web server
# Run in terminal:
composer install
mysql -u root -p < database.sql
cp includes/config.example.php includes/config.php
nano includes/config.php  # Add your database credentials
chmod 777 public/uploads -R
```

### 2. Required Before Launch
- [ ] Add your database credentials to config.php
- [ ] Set up SMTP email (Gmail or SendGrid)
- [ ] Create icon PNG files (see ICONS_NEEDED.txt)
- [ ] Create nestqr-icon.png from your logo
- [ ] Get SSL certificate (Let's Encrypt - free)

### 3. Optional Setup
- [ ] Configure Sightengine for image moderation
- [ ] Set up Cloudflare for CDN
- [ ] Add Google Analytics

## File Structure
```
nestqr-complete/
├── public/              ← Point your web server here
│   ├── index.php       ← Landing page
│   ├── dashboard.php   ← Agent dashboard
│   ├── assets/         ← CSS, JS, images
│   └── uploads/        ← User uploads (needs 777)
├── includes/
│   ├── config.php      ← Your database settings
│   └── functions.php   ← Core functions
├── database.sql        ← Database schema
└── composer.json       ← PHP dependencies
```

## Test Your Installation
1. Visit https://your-domain.com
2. Click "Sign Up"
3. Create an account
4. Create your first QR code
5. Assign it to a test listing

## Production Checklist
- [ ] Domain pointed to server
- [ ] SSL certificate active
- [ ] Email sending working
- [ ] Can create QR codes
- [ ] Can view listing pages
- [ ] Upload directory writable

## Getting Help
1. Read DEPLOYMENT_GUIDE.md
2. Check error logs in /var/log/apache2/
3. Email support@nestqr.com

## What Works Right Now
✅ User signup/login
✅ Email verification
✅ Dashboard
✅ QR code creation
✅ Icon selection
✅ Listing pages
✅ Analytics tracking
✅ Multi-domain support

## What Needs Icons
❗ Icon images (30 PNG files) - see ICONS_NEEDED.txt
❗ QR center logo (nestqr-icon.png from your SVG)

## Estimated Setup Time
- Basic setup: 10 minutes
- With icons created: +30 minutes
- Full production: 1-2 hours

Let's go! 🚀
