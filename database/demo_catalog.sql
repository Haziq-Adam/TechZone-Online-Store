-- Optional sample data for a NEW installation of TechZone.
-- Import database/schema.sql FIRST, then import this file if you want a populated catalog.
-- Contains no users, e-mail addresses, passwords, store profiles or orders.
-- Do not import repeatedly because it adds duplicate products.
SET NAMES utf8mb4;

INSERT INTO `categories` (`name`, `icon`) VALUES
  ('Smartphones', '📱'),
  ('Laptops', '💻'),
  ('Audio', '🎧'),
  ('Cameras', '📸'),
  ('Gaming', '🎮'),
  ('Wearables', '⌚');

INSERT INTO `products` (`name`, `category_id`, `price`, `original_price`, `description`, `specs`, `rating`, `reviews_count`, `stock`, `badge`, `hue`, `image_url`) VALUES
  ('Sample Phone', (SELECT id FROM categories WHERE name='Smartphones' LIMIT 1), 1299.00, NULL, 'Sample catalog listing for local testing only.', '128GB | Demo listing', 4.5, 0, 10, NULL, 230, 'uploads/products/iphone_15_pro_max_promo_1777348499319.png'),
  ('Sample Laptop', (SELECT id FROM categories WHERE name='Laptops' LIMIT 1), 2499.00, NULL, 'Sample catalog listing for local testing only.', '16GB RAM | Demo listing', 4.5, 0, 8, NULL, 260, 'uploads/products/macbook_pro_m3_promo_1777348673703.png'),
  ('Sample Headphones', (SELECT id FROM categories WHERE name='Audio' LIMIT 1), 249.00, NULL, 'Sample catalog listing for local testing only.', 'Wireless | Demo listing', 4.5, 0, 12, NULL, 200, 'uploads/products/sony_wh1000xm5_promo_1777348944531.png'),
  ('Sample Camera', (SELECT id FROM categories WHERE name='Cameras' LIMIT 1), 1799.00, NULL, 'Sample catalog listing for local testing only.', 'Digital camera | Demo listing', 4.5, 0, 5, NULL, 170, 'uploads/products/sony_a7rv_promo_1777349220099.png');
