CREATE DATABASE IF NOT EXISTS taskflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE taskflow;
DROP TABLE IF EXISTS notifications; DROP TABLE IF EXISTS tasks; DROP TABLE IF EXISTS users;
CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, role ENUM('manager','istehsalat_ustasi','cilalama_ustasi','boyalama_ustasi','anbar_ustasi') NOT NULL UNIQUE, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB;
CREATE TABLE tasks (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255) NOT NULL, description TEXT, image_path VARCHAR(255), current_stage ENUM('sifarisler','istehsalat','cilalama','boyalama','anbar') NOT NULL DEFAULT 'sifarisler', target_stage ENUM('sifarisler','istehsalat','cilalama','boyalama','anbar'), approval_status ENUM('active','pending_approval','rejected') NOT NULL DEFAULT 'active', priority ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium', created_by INT UNSIGNED NOT NULL, assigned_to INT UNSIGNED NULL, rejection_reason TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY(created_by) REFERENCES users(id), FOREIGN KEY(assigned_to) REFERENCES users(id) ON DELETE SET NULL, INDEX idx_tasks_updated(updated_at), INDEX idx_tasks_stage(current_stage,approval_status), INDEX idx_tasks_priority(priority)) ENGINE=InnoDB;
CREATE TABLE notifications (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, task_id INT UNSIGNED NOT NULL, target_role VARCHAR(50) NOT NULL, claimed_by_user_id INT UNSIGNED NULL, message TEXT NOT NULL, type ENUM('approval_request','claimed','rejected') NOT NULL, is_read BOOLEAN DEFAULT FALSE, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE, FOREIGN KEY(claimed_by_user_id) REFERENCES users(id) ON DELETE SET NULL, INDEX idx_notifications_created(created_at)) ENGINE=InnoDB;

-- Fixed local login accounts. Every account password is: 010203
INSERT INTO users (name, email, password, role) VALUES
('Manager', 'manager@taskflow', '$2y$10$Cl9hbSsG8kSYa12z4Un59.hv4MehEWd.44A97yvNZbh9rSWVhts2y', 'manager'),
('İstehsalat ustası', 'istehsalat@taskflow', '$2y$10$Cl9hbSsG8kSYa12z4Un59.hv4MehEWd.44A97yvNZbh9rSWVhts2y', 'istehsalat_ustasi'),
('Cilalama ustası', 'cilalama@taskflow', '$2y$10$Cl9hbSsG8kSYa12z4Un59.hv4MehEWd.44A97yvNZbh9rSWVhts2y', 'cilalama_ustasi'),
('Boyalama ustası', 'boyalama@taskflow', '$2y$10$Cl9hbSsG8kSYa12z4Un59.hv4MehEWd.44A97yvNZbh9rSWVhts2y', 'boyalama_ustasi'),
('Anbar ustası', 'anbar@taskflow', '$2y$10$Cl9hbSsG8kSYa12z4Un59.hv4MehEWd.44A97yvNZbh9rSWVhts2y', 'anbar_ustasi');
