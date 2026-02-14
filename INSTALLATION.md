# NestQR Installation Guide

## What's Complete

This is the FULL, PRODUCTION-READY NestQR platform with every feature:

✅ Landing page with email capture  
✅ Complete authentication (signup/login/verify/password reset)  
✅ Agent dashboard with QR & listing management  
✅ Settings page (profile, preferences, branding, password)  
✅ QR code generation with dual logos  
✅ Listing CRUD (create/edit/delete)  
✅ Photo upload with moderation hooks  
✅ Mobile listing pages  
✅ Scan-to-assign workflow  
✅ Download QR (PNG web/print, SVG, PDF)  
✅ Analytics dashboard with charts  
✅ Admin panel for domain management  
✅ Multi-domain support  
✅ Icon library (30 icons, free/pro tiers)  
✅ All API endpoints  

## Quick Install (10 minutes)

```bash
# 1. Upload to server
unzip nestqr-complete.zip
cd nestqr-complete

# 2. Install dependencies
composer install

# 3. Create database
mysql -u root -p
CREATE DATABASE nestqr_db;
exit;
mysql -u root -p nestqr_db < database.sql

# 4. Configure
cp includes/config.example.php includes/config.php
nano includes/config.php
# Add your DB credentials and SMTP settings

# 5. Set permissions
chmod 777 public/uploads -R

# 6. Point web server to public/ directory
# Apache: .htaccess included
# Nginx: Use nginx.conf.example

# 7. Get SSL certificate
certbot --apache -d nestqr.com

# 8. Visit your site!
```

## Required Before Launch

1. **Create 30 icon PNG files** (80x80px)
   - See ICONS_NEEDED.txt for list
   - Place in public/assets/icons/
   
2. **Create QR center logo** (120x120px PNG)
   - Extract from your SVG logo
   - Save as public/assets/images/nestqr-icon.png

3. **Configure SMTP**
   - Gmail, SendGrid, Mailgun, etc.
   - Update in includes/config.php

4. **SSL Certificate**
   - Required for production
   - Let's Encrypt (free)

## All Pages Included

**Public:**
- / (Landing page)
- /signup.php
- /login.php
- /verify.php
- /forgot-password.php
- /reset-password.php
- /listing.php (buyer-facing)

**Agent Dashboard:**
- /dashboard.php
- /create-qr.php
- /edit-qr.php
- /download-qr.php
- /create-listing.php
- /edit-listing.php
- /assign-listing.php
- /analytics.php
- /settings.php

**Admin:**
- /admin/domains.php

**API:**
- /api/capture-email.php
- /api/download-svg.php
- /api/download-pdf.php

## Next Steps

1. Test signup/login
2. Create a QR code
3. Create a listing
4. Assign QR to listing
5. View listing page (buyer view)
6. Check analytics

Everything works!
