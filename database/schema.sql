-- Wilpattu Nature Database Schema

-- Packages table
CREATE TABLE IF NOT EXISTS packages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    subtitle TEXT,
    duration TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    features TEXT NOT NULL, -- JSON array of features
    image TEXT NOT NULL,
    is_popular BOOLEAN DEFAULT 0,
    sort_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Gallery images table
CREATE TABLE IF NOT EXISTS gallery_images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
    filename TEXT NOT NULL,
    category TEXT,
    sort_order INTEGER DEFAULT 0,
    is_featured BOOLEAN DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    full_name TEXT NOT NULL,
    email TEXT NOT NULL,
    preferred_date DATE,
    num_guests INTEGER DEFAULT 1,
    message TEXT,
    package_id INTEGER,
    status TEXT DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (package_id) REFERENCES packages(id)
);

-- Site settings table
CREATE TABLE IF NOT EXISTS settings (
    key TEXT PRIMARY KEY,
    value TEXT
);

-- Insert default packages
INSERT INTO packages (name, subtitle, duration, price, features, image, is_popular, sort_order) VALUES
('Half Day Safari', 'Morning Wildlife Experience', '6:00 AM - 10:30 AM', 55, '["Hotel pickup & drop-off", "Entrance fees included", "Light refreshments", "English-speaking driver", "Perfect for short visits"]', 'package-half-day.jpg', 0, 1),
('Full Day Safari', 'Wilpattu National Park', '6:00 AM - 5:00 PM', 86, '["Hotel pickup & drop-off", "Entrance fees included", "Lunch & refreshments", "English-speaking driver", "Photography tips"]', 'package-full-day.jpg', 1, 2),
('Extended Safari', 'In-Depth Wildlife Exploration', '6:00 AM - 2:00 PM', 75, '["Hotel pickup & drop-off", "Entrance fees included", "Lunch included", "Expert naturalist guide", "Extended wildlife viewing"]', 'package-extended.jpg', 0, 3),
('Night Safari', 'Buffer Zone Adventure', '8:30 PM - 11:00 PM', 65, '["Hotel pickup & drop-off", "Outside park in buffer zone", "Nocturnal wildlife spotting", "Expert night guide", "Unique night experience"]', 'package-night.jpg', 0, 4),
('Exploring Ruins', 'Historical & Wildlife Tour', 'Full Day (6 AM - 5 PM)', 95, '["Hotel pickup & drop-off", "Entrance fees included", "Lunch & refreshments", "Historical site visit", "Expert guide"]', 'package-ruins.jpg', 0, 5);

-- Insert gallery images
INSERT INTO gallery_images (title, filename, category, sort_order, is_featured) VALUES
('Majestic Leopard', 'leapords/leapord_01.jpg', 'leopard', 1, 1),
('Elephant Family', 'elephants/elephant_01.jpg', 'wildlife', 2, 1),
('Eagle in Flight', 'birds/bird_01.jpg', 'birds', 3, 1),
('Leopard in Tree', 'leapords/leapord_05.jpg', 'leopard', 4, 1),
('Forest Fox', 'foxes/fox_01.jpg', 'wildlife', 5, 1),
('Colorful Bird', 'birds/bird_02.jpg', 'birds', 6, 1),
('Resting Leopard', 'leapords/leapord_10.jpg', 'leopard', 7, 1),
('Elephant Portrait', 'elephants/elephant_02.jpg', 'wildlife', 8, 0),
('Spotted Deer', 'deer/deer_01.jpg', 'wildlife', 9, 0),
('Tropical Bird', 'birds/bird_03.jpg', 'birds', 10, 0),
('Curious Monkey', 'monkey_01.jpeg', 'wildlife', 11, 0),
('Leopard Gaze', 'leapords/leapord_15.jpg', 'leopard', 12, 0);

-- Insert site settings
INSERT INTO settings (key, value) VALUES
('site_name', 'Wilpattu Nature'),
('site_tagline', 'Sri Lanka''s Premier Safari Experience'),
('phone_primary', '+94 77 207 5924'),
('phone_secondary', '+94 77 207 5924'),
('email_primary', 'info@wilsafari.com'),
('email_secondary', 'bookings@wilsafari.com'),
('address', 'Wilpattu National Park, North Western Province, Sri Lanka'),
('whatsapp_number', '+94772075924'),
('facebook_url', 'https://facebook.com/wilpattunature'),
('instagram_url', 'https://instagram.com/wilpattunature'),
('twitter_url', 'https://twitter.com/wilpattunature'),
('youtube_url', 'https://youtube.com/wilpattunature');
