DROP TABLE IF EXISTS panels;
DROP TABLE IF EXISTS comics;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE comics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    prompt TEXT NOT NULL,
    style VARCHAR(50),
    layout VARCHAR(50),
    image_path VARCHAR(255),
    script_text TEXT,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'completed',
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE panels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comic_id INT,
    image_path VARCHAR(255) NOT NULL,
    dialogue TEXT,
    panel_order INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (comic_id) REFERENCES comics(id) ON DELETE CASCADE
);
