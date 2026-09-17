-- Novel Reading — Database Schema
CREATE DATABASE IF NOT EXISTS novel_reading;
USE novel_reading;

CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  profile_pic VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE novels (
  novel_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  author VARCHAR(100) NOT NULL,
  description TEXT,
  cover_image VARCHAR(255) DEFAULT 'assets/img/default-cover.png',
  price DECIMAL(8,2) DEFAULT 0.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE chapters (
  chapter_id INT AUTO_INCREMENT PRIMARY KEY,
  novel_id INT NOT NULL,
  chapter_number INT NOT NULL,
  chapter_title VARCHAR(150),
  content LONGTEXT NOT NULL,
  FOREIGN KEY (novel_id) REFERENCES novels(novel_id) ON DELETE CASCADE,
  UNIQUE KEY unique_chapter (novel_id, chapter_number)
);

CREATE TABLE purchases (
  purchase_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  novel_id INT NOT NULL,
  purchased_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (novel_id) REFERENCES novels(novel_id) ON DELETE CASCADE,
  UNIQUE KEY unique_purchase (user_id, novel_id)
);

CREATE TABLE wishlist (
  wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  novel_id INT NOT NULL,
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (novel_id) REFERENCES novels(novel_id) ON DELETE CASCADE,
  UNIQUE KEY unique_wish (user_id, novel_id)
);

CREATE TABLE reading_history (
  history_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  novel_id INT NOT NULL,
  chapter_id INT NOT NULL,
  last_read_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (novel_id) REFERENCES novels(novel_id) ON DELETE CASCADE,
  FOREIGN KEY (chapter_id) REFERENCES chapters(chapter_id) ON DELETE CASCADE,
  UNIQUE KEY unique_progress (user_id, novel_id)
);

-- Sample data so you have something to test with
INSERT INTO novels (title, author, description, price) VALUES
('The Silent Orchard', 'Maya Lindqvist', 'A quiet mystery set in an old orchard town where nothing is as peaceful as it seems.', 0.00),
('Iron & Ashes', 'Devon Kade', 'A gritty fantasy epic about a fallen kingdom and the blacksmith who must reforge it.', 4.99),
('Letters to No One', 'Priya Nair', 'A slow-burn romance told entirely through unsent letters.', 2.99);

INSERT INTO chapters (novel_id, chapter_number, chapter_title, content) VALUES
(1, 1, 'The Orchard Gate', 'The gate had not opened in eleven years, and yet this morning it stood ajar...'),
(1, 2, 'Old Roots', 'Beneath the oldest tree, Mira found something that should not have been there...'),
(2, 1, 'The Last Forge', 'Smoke rose from a forge that had no right to still be burning...'),
(3, 1, 'Unsent', 'Dear no one, I am writing this letter knowing you will never read it...');
