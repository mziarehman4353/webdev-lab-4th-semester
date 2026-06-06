-- ============================================
-- BlossomMart General Store Database Schema
-- ============================================

CREATE DATABASE IF NOT EXISTS blossommart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blossommart;

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) DEFAULT 'ti-tag',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    original_price DECIMAL(10,2) DEFAULT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(300) DEFAULT NULL,
    featured TINYINT(1) DEFAULT 0,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Users / Customers Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    role ENUM('customer','admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(20) DEFAULT NULL,
    shipping_address TEXT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    shipping_fee DECIMAL(10,2) DEFAULT 15000,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT DEFAULT NULL,
    product_name VARCHAR(200) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- ============================================
-- Seed Data
-- ============================================

INSERT INTO categories (name, slug, icon) VALUES
('Fresh Produce', 'fresh-produce', 'ti-leaf'),
('Snacks & Beverages', 'snacks-beverages', 'ti-coffee'),
('Dairy & Eggs', 'dairy-eggs', 'ti-droplet'),
('Household', 'household', 'ti-home'),
('Personal Care', 'personal-care', 'ti-heart'),
('Bakery', 'bakery', 'ti-bread');

INSERT INTO products (category_id, name, slug, description, price, original_price, stock, featured) VALUES
(1, 'Fresh Organic Apples (1kg)', 'fresh-organic-apples', 'Crispy and sweet organic apples sourced from local farms. Perfect for snacking or baking.', 35000, 42000, 150, 1),
(1, 'Ripe Avocado Pack (3pcs)', 'ripe-avocado-pack', 'Creamy, ready-to-eat avocados. Rich in healthy fats and vitamins.', 28000, NULL, 80, 1),
(1, 'Baby Spinach (250g)', 'baby-spinach', 'Tender young spinach leaves, washed and ready to use. Packed with iron and antioxidants.', 18000, NULL, 60, 0),
(1, 'Cherry Tomatoes (500g)', 'cherry-tomatoes', 'Sweet and juicy cherry tomatoes, perfect for salads and cooking.', 22000, 28000, 90, 0),
(2, 'Mineral Water 6-Pack', 'mineral-water-6pack', 'Pure mountain mineral water in convenient 600ml bottles. Stay hydrated throughout the day.', 24000, NULL, 200, 1),
(2, 'Premium Green Tea (20 bags)', 'premium-green-tea', 'Aromatic Japanese green tea with delicate flavor. Rich in antioxidants and polyphenols.', 45000, 55000, 75, 0),
(2, 'Potato Chips Assorted', 'potato-chips-assorted', 'Crunchy and delicious potato chips in 3 flavors. Perfect for movie nights.', 32000, NULL, 120, 0),
(2, 'Fresh Orange Juice (1L)', 'fresh-orange-juice', 'Cold-pressed, no-sugar-added fresh orange juice. Full of vitamin C goodness.', 38000, 45000, 55, 1),
(3, 'Full Cream Milk (1L)', 'full-cream-milk', 'Rich and creamy full cream milk from happy free-range cows. High in calcium and protein.', 22000, NULL, 100, 0),
(3, 'Greek Yogurt Plain (500g)', 'greek-yogurt-plain', 'Thick, creamy Greek yogurt with live cultures. Great for breakfast or cooking.', 35000, 40000, 65, 1),
(3, 'Free-Range Eggs (12pcs)', 'free-range-eggs', 'Fresh eggs from free-range chickens. Superior taste and nutrition.', 28000, NULL, 80, 0),
(3, 'Cheddar Cheese (250g)', 'cheddar-cheese', 'Aged cheddar cheese with bold, sharp flavor. Perfect for sandwiches and cooking.', 42000, 50000, 45, 0),
(4, 'Laundry Detergent Powder', 'laundry-detergent-powder', 'Powerful cleaning formula that tackles tough stains while being gentle on fabrics.', 55000, 65000, 90, 0),
(4, 'Dish Soap Lemon Scent', 'dish-soap-lemon', 'Grease-cutting formula with refreshing lemon fragrance. Gentle on hands.', 18000, NULL, 110, 0),
(4, 'All-Purpose Cleaner (500ml)', 'all-purpose-cleaner', 'Versatile household cleaner effective on multiple surfaces. Leaves a fresh scent.', 25000, NULL, 70, 0),
(5, 'Moisturizing Hand Cream', 'moisturizing-hand-cream', 'Deeply hydrating hand cream with shea butter and vitamin E. Non-greasy formula.', 48000, 60000, 55, 1),
(5, 'Gentle Face Wash (200ml)', 'gentle-face-wash', 'Mild, pH-balanced face wash suitable for all skin types. Removes impurities gently.', 65000, NULL, 40, 0),
(5, 'Bamboo Toothbrush Set', 'bamboo-toothbrush-set', 'Eco-friendly bamboo toothbrushes with soft bristles. Pack of 4 in assorted colors.', 38000, 45000, 85, 0),
(6, 'Artisan Sourdough Bread', 'artisan-sourdough-bread', 'Handcrafted sourdough made with long fermentation process. Tangy flavor and chewy crust.', 45000, NULL, 30, 1),
(6, 'Croissants (4pcs)', 'croissants-4pcs', 'Buttery, flaky croissants baked fresh every morning. Perfect with coffee or jam.', 32000, 38000, 25, 0),
(6, 'Whole Grain Muffins (6pcs)', 'whole-grain-muffins', 'Wholesome muffins made with whole grain flour. Available in blueberry and banana.', 38000, NULL, 40, 0);

-- Admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@blossommart.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
