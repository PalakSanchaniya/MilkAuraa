-- 1. USERS (Admin + Customers Login)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    mobile VARCHAR(15) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. CUSTOMERS (Extra details)
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    address TEXT,
    custom_rate DECIMAL(5,2), -- per liter rate
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 3. RATE SETTINGS (Fat-based pricing)
CREATE TABLE rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fat DECIMAL(4,2),
    rate_per_liter DECIMAL(6,2)
);

-- 4. MILK ENTRIES (Morning/Evening)
CREATE TABLE milk_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    date DATE,
    shift ENUM('morning','evening'),
    liters DECIMAL(5,2),
    fat DECIMAL(4,2),
    rate DECIMAL(6,2),
    total_amount DECIMAL(8,2),
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

-- 5. BILLS (Bulk billing)
CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    start_date DATE,
    end_date DATE,
    total_liters DECIMAL(8,2),
    total_amount DECIMAL(10,2),
    status ENUM('paid','unpaid') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

-- 6. PAYMENTS
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    bill_id INT,
    amount_paid DECIMAL(10,2),
    payment_date DATE,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (bill_id) REFERENCES bills(id)
);

-- 7. STOCK (Ghee, Powder, Feed etc.)
CREATE TABLE stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100),
    quantity DECIMAL(8,2),
    price DECIMAL(8,2),
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 8. EXTRA MILK REQUESTS (Customer → Admin)
CREATE TABLE milk_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    request_date DATE,
    extra_liters DECIMAL(5,2),
    status ENUM('pending','accepted','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

-- 9. ANNOUNCEMENTS (Admin notices)
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);