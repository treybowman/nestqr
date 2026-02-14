-- NestQR Database Schema
-- MySQL/MariaDB

CREATE DATABASE IF NOT EXISTS nestqr_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nestqr_db;

-- Users (Agents) Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    photo_url VARCHAR(255),
    bio TEXT,
    company_id INT NULL,
    plan_tier ENUM('free', 'pro', 'unlimited', 'company') DEFAULT 'free',
    preferred_domain VARCHAR(100) DEFAULT 'nestqr',
    custom_brand_logo VARCHAR(255),
    custom_brand_color VARCHAR(7),
    auth_preference ENUM('pin', 'login', 'magic_link') DEFAULT 'login',
    theme_preference ENUM('light', 'dark') DEFAULT 'light',
    is_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(64),
    reset_token VARCHAR(64),
    reset_token_expires DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_company (company_id),
    INDEX idx_plan (plan_tier)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Companies (for team plans) Table
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    logo_url VARCHAR(255),
    brand_color VARCHAR(7),
    admin_user_id INT NOT NULL,
    plan_start_date DATE,
    billing_email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Active Domains Table
CREATE TABLE active_domains (
    id INT AUTO_INCREMENT PRIMARY KEY,
    domain VARCHAR(100) NOT NULL UNIQUE,
    market_name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    launched_at DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_domain (domain),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default domains
INSERT INTO active_domains (domain, market_name, is_active, launched_at) VALUES
('nestqr', 'National', TRUE, CURDATE()),
('nestatl', 'Atlanta', TRUE, CURDATE());

-- Icon Library Table
CREATE TABLE icon_library (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(50) NOT NULL,
    emoji VARCHAR(10) NOT NULL,
    svg_filename VARCHAR(100) NOT NULL,
    tier ENUM('free', 'pro') DEFAULT 'free',
    category VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    INDEX idx_tier (tier),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert icon library
INSERT INTO icon_library (slug, name, emoji, svg_filename, tier, category, sort_order) VALUES
-- Free tier icons
('house', 'House', '🏠', 'house.svg', 'free', 'standard', 1),
('tree', 'Tree', '🌳', 'tree.svg', 'free', 'nature', 2),
('key', 'Key', '🔑', 'key.svg', 'free', 'standard', 3),
('building', 'Building', '🏢', 'building.svg', 'free', 'commercial', 4),
('pin', 'Pin', '📍', 'pin.svg', 'free', 'standard', 5),
('door', 'Door', '🚪', 'door.svg', 'free', 'standard', 6),
('star', 'Star', '⭐', 'star.svg', 'free', 'premium', 7),
('sunset', 'Sunset', '🌅', 'sunset.svg', 'free', 'lifestyle', 8),
('cottage', 'Cottage', '🏡', 'cottage.svg', 'free', 'standard', 9),
('diamond', 'Diamond', '💎', 'diamond.svg', 'free', 'luxury', 10),
-- Pro tier icons
('window', 'Window', '🪟', 'window.svg', 'pro', 'standard', 11),
('furniture', 'Furniture', '🛋️', 'furniture.svg', 'pro', 'staging', 12),
('beach', 'Beach', '🏖️', 'beach.svg', 'pro', 'lifestyle', 13),
('mountain', 'Mountain', '🏔️', 'mountain.svg', 'pro', 'lifestyle', 14),
('pool', 'Pool', '🏊', 'pool.svg', 'pro', 'luxury', 15),
('target', 'Target', '🎯', 'target.svg', 'pro', 'hot', 16),
('bell', 'Bell', '🔔', 'bell.svg', 'pro', 'event', 17),
('flower', 'Flower', '🌺', 'flower.svg', 'pro', 'curb_appeal', 18),
('castle', 'Castle', '🏰', 'castle.svg', 'pro', 'historic', 19),
('warehouse', 'Warehouse', '🏭', 'warehouse.svg', 'pro', 'commercial', 20),
('cityscape', 'Cityscape', '🌃', 'cityscape.svg', 'pro', 'urban', 21),
('pine', 'Pine Tree', '🌲', 'pine.svg', 'pro', 'nature', 22),
('palm', 'Palm Tree', '🌴', 'palm.svg', 'pro', 'tropical', 23),
('construction', 'Construction', '🏗️', 'construction.svg', 'pro', 'new_build', 24),
('paintbrush', 'Paint Brush', '🎨', 'paintbrush.svg', 'pro', 'renovated', 25),
('fire', 'Fire', '🔥', 'fire.svg', 'pro', 'hot', 26),
('snowflake', 'Snowflake', '❄️', 'snowflake.svg', 'pro', 'seasonal', 27),
('sun', 'Sun', '☀️', 'sun.svg', 'pro', 'bright', 28),
('moon', 'Moon', '🌙', 'moon.svg', 'pro', 'evening', 29),
('tent', 'Tent', '🎪', 'tent.svg', 'pro', 'vacation', 30);

-- QR Slots (Reusable) Table
CREATE TABLE qr_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    icon_id INT NOT NULL,
    qr_code_filename VARCHAR(100) NOT NULL,
    short_code VARCHAR(20) NOT NULL UNIQUE,
    current_listing_id INT NULL,
    total_scans INT DEFAULT 0,
    icon_locked_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (icon_id) REFERENCES icon_library(id),
    INDEX idx_user (user_id),
    INDEX idx_short_code (short_code),
    INDEX idx_listing (current_listing_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listings Table
CREATE TABLE listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    qr_slot_id INT,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(100),
    state VARCHAR(50),
    zip VARCHAR(20),
    price DECIMAL(12, 2),
    beds INT,
    baths DECIMAL(3, 1),
    sqft INT,
    description TEXT,
    status ENUM('active', 'sold', 'pending', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (qr_slot_id) REFERENCES qr_slots(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_qr_slot (qr_slot_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listing Photos Table
CREATE TABLE listing_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    photo_url VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    is_moderated BOOLEAN DEFAULT FALSE,
    moderation_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    INDEX idx_listing (listing_id),
    INDEX idx_moderation (moderation_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Scan Analytics Table
CREATE TABLE scan_analytics (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    qr_slot_id INT NOT NULL,
    listing_id INT,
    scanned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    referrer VARCHAR(255),
    FOREIGN KEY (qr_slot_id) REFERENCES qr_slots(id) ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE SET NULL,
    INDEX idx_qr_slot (qr_slot_id),
    INDEX idx_listing (listing_id),
    INDEX idx_scanned_at (scanned_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email Capture (Beta Signups) Table
CREATE TABLE email_capture (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    captured_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sessions Table (for authentication)
CREATE TABLE sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
