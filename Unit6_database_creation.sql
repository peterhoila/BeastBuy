use hoila;

DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS product;

CREATE TABLE customer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    email VARCHAR(255)
);

CREATE TABLE product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255),
    image_name VARCHAR(255),
    price DECIMAL(6, 2),
    in_stock INT,
    inactive TINYINT
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    customer_id INT,
    quantity INT,
    price DECIMAL(6,2),
    tax DECIMAL(6, 2),
    donation DECIMAL(4, 2),
    timestamp BIGINT,
    FOREIGN KEY (product_id) REFERENCES product(id),
    FOREIGN KEY (customer_id) REFERENCES customer(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    password VARCHAR(255),
    email VARCHAR(100),
    role INT
);

INSERT INTO users (first_name, last_name, password, email, role)
VALUES 
    ('Frodo', 'Baggins', 'fb', 'fb@mines.edu', 1),
    ('Harry', 'Potter', 'hp', 'hp@mines.edu', 2);
    
INSERT INTO customer (first_name, last_name, email)
VALUES
    ('Mickey', 'Mouse', 'mmouse@mines.edu'),
    ('Adam', 'Johnson', 'aj73@gmail.com');

INSERT INTO product (product_name, image_name, price, in_stock, inactive)
VALUES
    ('Box TV', 'box.jpg', 20, 0, 0),
    ('LCD TV', 'lcd.jpg', 100, 3, 0),
    ('OLED TV', 'oled.jpg', 1500, 10, 0);

