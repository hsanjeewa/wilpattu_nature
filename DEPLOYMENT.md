# Wilpattu Nature - Deployment Checklist

## Pre-Deployment Checklist

### Server Requirements
- [ ] PHP 8.0 or higher installed
- [ ] SQLite3 extension enabled
- [ ] Apache with mod_rewrite OR Nginx
- [ ] Write permissions for database directory

### Files to Upload
Upload all files maintaining the directory structure:

```
wilpattu-nature/
├── index.php
├── config.php
├── .htaccess
├── database/
│   └── (empty - will be auto-created)
├── includes/
│   ├── db.php
│   ├── functions.php
│   ├── header.php
│   └── footer.php
├── pages/
│   ├── home.php
│   ├── gallery.php
│   └── partials/
│       ├── hero.php
│       ├── why-choose-us.php
│       ├── packages.php
│       ├── safari-ops.php
│       ├── wildlife-wonders.php
│       └── contact.php
├── api/
│   └── booking.php
├── assets/
│   └── images/
│       ├── logo.png
│       ├── hero/
│       │   └── hero-elephant.jpg
│       ├── packages/
│       │   ├── package-half-day.jpg
│       │   ├── package-full-day.jpg
│       │   ├── package-extended.jpg
│       │   ├── package-night.jpg
│       │   └── package-ruins.jpg
│       └── gallery/
│           ├── gallery-1.jpg through gallery-12.jpg
└── tests/
    ├── index.php
    └── validate.php
```

## Deployment Steps

### Step 1: Upload Files

**Via FTP/SFTP:**
1. Connect to your server
2. Upload all files to your web root (e.g., `/public_html/`)
3. Maintain the directory structure

**Via cPanel File Manager:**
1. Zip the `wilpattu-nature` folder
2. Upload via cPanel File Manager
3. Extract the zip file
4. Move contents to desired location

**Via SSH:**
```bash
# Upload zip file
scp wilpattu-nature.zip user@server:/path/to/webroot/

# Extract on server
ssh user@server
cd /path/to/webroot
unzip wilpattu-nature.zip
```

### Step 2: Set Permissions

```bash
# Make database directory writable
chmod 755 database/

# If using command line:
chmod -R 755 assets/images/
chmod 644 .htaccess
```

**Via cPanel:**
1. Go to File Manager
2. Right-click on `database` folder
3. Select "Change Permissions"
4. Set to 755

### Step 3: Configure Server

**For Apache:**
- Ensure `.htaccess` is uploaded
- Enable mod_rewrite:
```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

**For Nginx:**
Add to your server block:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
}

# Protect database file
location ~ /database/.*\.db$ {
    deny all;
}
```

### Step 4: Run Validation Tests

**Browser Method:**
Visit: `https://yourdomain.com/tests/`

**Command Line Method:**
```bash
cd /path/to/wilpattu-nature
php tests/validate.php
```

Expected output: All tests should pass ✓

### Step 5: Verify Website

**Homepage:**
- [ ] Hero section displays correctly
- [ ] Navigation works
- [ ] All images load
- [ ] Stats are visible (90%+, 7+, 5.0)
- [ ] CTA buttons work

**Packages Section:**
- [ ] All 5 packages display
- [ ] Prices are correct
- [ ] "Most Popular" badge shows on Full Day Safari
- [ ] Images load

**Gallery Section:**
- [ ] Masonry grid displays
- [ ] Images load
- [ ] Hover effects work
- [ ] "View All Photos" button works

**Contact Form:**
- [ ] Form displays
- [ ] Contact info is correct
- [ ] Form validation works
- [ ] Submit button works

**Gallery Page:**
- [ ] Gallery page loads (`?page=gallery`)
- [ ] Lightbox works
- [ ] Navigation works

**Footer:**
- [ ] Social icons display
- [ ] Links work
- [ ] WhatsApp button appears

### Step 6: Customize (Optional)

**Update Contact Information:**
Edit `config.php`:
```php
define('PHONE_PRIMARY', '+94 XX XXX XXXX');
define('EMAIL_PRIMARY', 'your@email.com');
define('WHATSAPP_NUMBER', '+94XXXXXXXXX');
```

**Update Social Links:**
Edit `config.php`:
```php
define('FACEBOOK_URL', 'https://facebook.com/yourpage');
define('INSTAGRAM_URL', 'https://instagram.com/yourpage');
```

**Update Packages:**
Use an SQLite browser or edit `database/schema.sql` and re-import.

## Post-Deployment Checklist

### Security
- [ ] Delete `tests/` folder (optional, for production)
- [ ] Ensure database file is not accessible via web
- [ ] Check `.htaccess` is working
- [ ] Verify PHP errors are hidden (display_errors = Off)

### Performance
- [ ] Enable gzip compression (via .htaccess or server config)
- [ ] Verify image caching headers
- [ ] Test page load speed

### SEO
- [ ] Update meta description in `includes/header.php`
- [ ] Add Google Analytics (optional)
- [ ] Submit sitemap to Google

### Monitoring
- [ ] Set up error logging
- [ ] Test booking form submission
- [ ] Verify email notifications (if configured)

## Troubleshooting

### 404 Errors
```bash
# Check if mod_rewrite is enabled
apachectl -M | grep rewrite

# If not enabled:
a2enmod rewrite
service apache2 restart
```

### Database Errors
```bash
# Check permissions
ls -la database/

# Fix permissions
chmod 755 database/
chmod 666 database/wilpattu.db
```

### Images Not Loading
```bash
# Check file permissions
chmod -R 755 assets/images/

# Verify paths are correct
ls assets/images/hero/
ls assets/images/packages/
ls assets/images/gallery/
```

### PHP Errors
Check PHP error log:
```bash
tail -f /var/log/apache2/error.log
```

Or enable display_errors temporarily in `config.php`:
```php
ini_set('display_errors', 1);
```

## Rollback Plan

If deployment fails:
1. Keep backup of previous site
2. Rename `wilpattu-nature` folder
3. Restore backup
4. Check error logs
5. Fix issues and retry

## Support

For issues:
1. Run validation tests: `/tests/`
2. Check error logs
3. Verify file permissions
4. Confirm PHP version

## Success Criteria

✅ Website is live and accessible
✅ All pages load correctly
✅ Images display properly
✅ Booking form works
✅ Gallery lightbox functions
✅ Mobile responsive
✅ All tests pass
