-- Migration to support multi-panel comics
-- Run this after backing up your database

-- Remove image_path from comics table as images will be in panels
ALTER TABLE comics DROP COLUMN IF EXISTS image_path;

-- Add panel_count for quick reference  
ALTER TABLE comics ADD COLUMN IF NOT EXISTS panel_count INT DEFAULT 0;

-- Verify panels table structure (should already exist from db_schema.sql)
-- If you need to recreate it:
-- CREATE TABLE IF NOT EXISTS panels (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     comic_id INT,
--     image_path VARCHAR(255) NOT NULL,
--     dialogue TEXT,
--     panel_order INT,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (comic_id) REFERENCES comics(id) ON DELETE CASCADE
-- );
