-- create users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    age INT,
    address TEXT
);

-- add columns to parcels table
ALTER TABLE parcels 
ADD COLUMN added_by INT,
ADD COLUMN last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD FOREIGN KEY (added_by) REFERENCES users(user_id);

-- add columns to warehouse table
ALTER TABLE warehouse 
ADD COLUMN added_by INT,
ADD COLUMN last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD FOREIGN KEY (added_by) REFERENCES users(user_id);