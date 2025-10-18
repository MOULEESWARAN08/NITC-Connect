-- Create database
CREATE DATABASE IF NOT EXISTS nitcconnect;
USE nitcconnect;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    roll_number VARCHAR(50) UNIQUE,
    branch VARCHAR(50),
    year INT,
    profile_picture VARCHAR(255),
    resume_path VARCHAR(255),
    total_points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Clubs table
CREATE TABLE IF NOT EXISTS clubs (
    club_id INT AUTO_INCREMENT PRIMARY KEY,
    club_name VARCHAR(100) NOT NULL,
    description TEXT,
    category VARCHAR(50),
    image_url VARCHAR(255),
    members_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Club members table
CREATE TABLE IF NOT EXISTS club_members (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    club_id INT,
    user_id INT,
    join_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (club_id) REFERENCES clubs(club_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Events table
CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(200) NOT NULL,
    description TEXT,
    event_date DATETIME NOT NULL,
    location VARCHAR(200),
    organizer VARCHAR(100),
    image_url VARCHAR(255),
    max_participants INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Event registrations table
CREATE TABLE IF NOT EXISTS event_registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    user_id INT,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(event_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Hostel tickets table
CREATE TABLE IF NOT EXISTS hostel_tickets (
    ticket_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    subject VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Study materials table
CREATE TABLE IF NOT EXISTS study_materials (
    material_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    subject VARCHAR(100),
    file_path VARCHAR(255),
    uploaded_by INT,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    downloads INT DEFAULT 0,
    FOREIGN KEY (uploaded_by) REFERENCES users(user_id)
);

-- Leaderboard table
CREATE TABLE IF NOT EXISTS leaderboard (
    entry_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    activity_type VARCHAR(100),
    points INT DEFAULT 0,
    activity_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- AI Recommendations table
CREATE TABLE IF NOT EXISTS ai_recommendations (
    recommendation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    recommendation_type VARCHAR(50),
    recommendation_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Insert sample data
INSERT INTO clubs (club_name, description, category, members_count) VALUES
('Robotics Club', 'Build and program robots', 'Technical', 145),
('CP Hub', 'Competitive Programming Community', 'Technical', 230),
('Music Club', 'Learn and perform music', 'Cultural', 98),
('Photography Club', 'Capture moments', 'Cultural', 76);

INSERT INTO events (event_name, description, event_date, location, organizer) VALUES
('CONCEPTUALIZE 2025', 'Ideathon on Smart Power Systems', '2025-11-07 09:00:00', 'Main Auditorium', 'Innovation Cell'),
('Lifelong Learning Conference', 'International Conference', '2025-11-10 10:00:00', 'Conference Hall', 'Academic Affairs'),
('Women\'s Empowerment', 'Workshop and Discussion', '2025-11-20 14:00:00', 'Seminar Hall', 'Women\'s Cell');