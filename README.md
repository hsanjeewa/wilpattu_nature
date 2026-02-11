# Wilpattu Nature - PHP Website

A complete PHP replication of the Wilpattu Nature safari booking website.

## Features

- **Responsive Design** - Works on all devices
- **Booking System** - Contact form with database storage
- **Gallery** - Masonry layout with lightbox
- **SEO Optimized** - Meta tags and structured data
- **Fast Loading** - CDN resources, optimized images

## Tech Stack

- **PHP 8.0+** - Server-side logic
- **SQLite** - Lightweight database
- **TailwindCSS (CDN)** - Utility-first CSS
- **AlpineJS (CDN)** - Lightweight JavaScript framework
- **Lucide Icons** - Modern icon set

## Installation

### Requirements

- PHP 8.0 or higher
- SQLite3 extension enabled
- Apache with mod_rewrite (or Nginx)

### Step 1: Upload Files

Upload all files to your web server:

```
/public_html/
  ├── index.php
  ├── config.php
  ├── .htaccess
  ├── database/
  ├── includes/
  ├── pages/
  ├── api/
  ├── assets/
  └── tests/
```

### Step 2: Set Permissions

Make the database directory writable:

```bash
chmod 755 database/
chmod 644 database/wilpattu.db
```

### Step 3: Verify Installation

Run the validation test:

```bash
php tests/validate.php
```

Or visit in browser:
```
https://yourdomain.com/tests/validate.php
```

### Step 4: Customize (Optional)

Edit `config.php` to update:
- Site name and tagline
- Contact information
- Social media links

## Database

The SQLite database is automatically created on first run. It contains:

- **packages** - Safari packages and pricing
- **gallery_images** - Gallery image data
- **bookings** - Booking form submissions
- **settings** - Site configuration

### Default Packages

1. Half Day Safari - $55/person
2. Full Day Safari - $86/person (Most Popular)
3. Extended Safari - $75/person
4. Night Safari - $65/person
5. Exploring Ruins - $95/person

## File Structure

```
wilpattu-nature/
├── index.php              # Main entry point / router
├── config.php             # Configuration
├── .htaccess              # Apache rules
├── database/
│   ├── schema.sql         # Database schema
│   └── wilpattu.db        # SQLite database (auto-created)
├── includes/
│   ├── db.php             # Database class
│   ├── functions.php      # Helper functions
│   ├── header.php         # Site header
│   └── footer.php         # Site footer
├── pages/
│   ├── home.php           # Homepage
│   ├── gallery.php        # Gallery page
│   └── partials/          # Section components
│       ├── hero.php
│       ├── why-choose-us.php
│       ├── packages.php
│       ├── safari-ops.php
│       ├── wildlife-wonders.php
│       └── contact.php
├── api/
│   └── booking.php        # Booking form handler
├── assets/
│   ├── images/
│   │   ├── logo.png
│   │   ├── hero/
│   │   ├── packages/
│   │   └── gallery/
│   ├── css/
│   └── js/
└── tests/
    └── validate.php       # Validation tests
```

## Pages

### Homepage (`/` or `?page=home`)

Sections:
1. Hero - Full-screen background with CTAs
2. Why Choose Us - 6 feature cards
3. Safari Packages - 5 pricing cards
4. Safari Operations - Info and inclusions
5. Wildlife Wonders - Featured gallery
6. Contact/Booking - Form and contact info

### Gallery Page (`?page=gallery`)

- Full masonry gallery grid
- Lightbox with keyboard navigation
- Double-click to expand

## Customization

### Change Colors

Edit the Tailwind config in `includes/header.php`:

```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: '#E5A935',      // Main yellow
                'dark-green': '#1E3A2F',  // Dark green
                'dark-brown': '#2D2419',  // Footer bg
                cream: '#F5F0E8',         // Section bg
            }
        }
    }
}
```

### Update Packages

Edit `database/schema.sql` and re-import, or use SQLite browser to modify the `packages` table.

### Add Gallery Images

1. Upload images to `assets/images/gallery/`
2. Add entries to `gallery_images` table

## API Endpoints

### POST /api/booking.php

Submit a booking request:

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "date": "2025-03-15",
    "guests": 2,
    "package_id": 1,
    "message": "Optional message",
    "csrf_token": "..."
}
```

Response:

```json
{
    "success": true,
    "message": "Booking request submitted successfully"
}
```

## Security

- CSRF protection on all forms
- XSS prevention with output escaping
- SQL injection prevention with prepared statements
- Database file protected by .htaccess

## Performance

- CDN resources (Tailwind, AlpineJS, Fonts)
- Lazy loading for gallery images
- Gzip compression enabled
- Static asset caching (1 year for images)

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Troubleshooting

### Database not writable

```bash
chmod 755 database/
chmod 666 database/wilpattu.db
```

### 404 errors on pages

Enable mod_rewrite:
```bash
a2enmod rewrite
service apache2 restart
```

### Images not loading

Check file permissions:
```bash
chmod -R 755 assets/images/
```

## License

This is a replication project for educational purposes.

## Credits

- Original design: https://wilpattu-nature.lovable.app/
- Fonts: Google Fonts (Playfair Display, Inter)
- Icons: Lucide Icons
- CSS Framework: TailwindCSS

## Support

For issues or questions, please contact the developer.
# wilpattu_nature
# wilpattu_nature
