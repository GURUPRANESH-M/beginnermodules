CREATE DATABASE local_login_db;

USE local_login_db;

-- Table for user credentials
CREATE TABLE user_credentials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Table for storing user details
CREATE TABLE user_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100),
    address VARCHAR(255),
    mobile VARCHAR(15),
    age INT
);
