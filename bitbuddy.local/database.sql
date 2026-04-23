-- BitBuddy Database Schema
-- MySQL 8.4+

CREATE DATABASE IF NOT EXISTS `bitbuddy_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bitbuddy_db`;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `avatar_url` VARCHAR(500) DEFAULT NULL,
    `role` ENUM('user','admin') NOT NULL DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_email` (`email`),
    INDEX `idx_username` (`username`)
) ENGINE=InnoDB;

-- Categories table
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `icon` VARCHAR(50) DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB;

-- Services table
CREATE TABLE IF NOT EXISTS `services` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) DEFAULT NULL,
    `description` TEXT NOT NULL,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `delivery_days` INT DEFAULT 14,
    `includes_audit` TINYINT(1) DEFAULT 1,
    `support_days` INT DEFAULT 30,
    `is_popular` TINYINT(1) DEFAULT 0,
    `image_url` VARCHAR(500) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
    INDEX `idx_category` (`category_id`),
    INDEX `idx_popular` (`is_popular`),
    INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB;

-- Orders table
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `service_id` INT NOT NULL,
    `order_code` VARCHAR(20) NOT NULL UNIQUE,
    `status` ENUM('pending','active','completed','cancelled') NOT NULL DEFAULT 'pending',
    `price` DECIMAL(10,2) NOT NULL,
    `notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE,
    INDEX `idx_user` (`user_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_code` (`order_code`)
) ENGINE=InnoDB;

-- Contact messages table
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_read` (`is_read`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB;

-- Insert default categories
INSERT INTO `categories` (`name`, `slug`, `description`, `icon`, `sort_order`) VALUES
('Веб-разработка', 'development', 'Высокопроизводительные приложения на современных стеках', 'code_blocks', 1),
('UI/UX Дизайн', 'design', 'Интерфейсы, которые продают. Глубокая аналитика и премиальный визуальный стиль', 'design_services', 2),
('IT-поддержка', 'it-support', 'Техническая поддержка и обслуживание инфраструктуры', 'support_agent', 3),
('Видео-продакшн', 'video', 'Профессиональный видеомонтаж и моушн-дизайн', 'movie_creation', 4);

-- Insert default services
INSERT INTO `services` (`category_id`, `title`, `description`, `price`, `delivery_days`, `is_popular`, `slug`) VALUES
(1, 'Веб-разработка', 'Высокопроизводительные приложения на современных стеках. От архитектуры до деплоя.', 100000.00, 21, 1, 'web-development'),
(2, 'UI/UX Дизайн', 'Интерфейсы, которые продают. Глубокая аналитика и премиальный визуальный стиль.', 80000.00, 14, 1, 'ui-ux-design'),
(1, 'Кибербезопасность', 'Аудит и защита вашей инфраструктуры по высочайшим стандартам безопасности.', 200000.00, 30, 1, 'cybersecurity'),
(1, 'Фронтенд-разработка', 'Создание отзывчивых и быстрых интерфейсов с использованием React, Vue, Svelte.', 60000.00, 14, 0, 'frontend-development'),
(1, 'Бэкенд-разработка', 'Надёжные серверные решения на PHP, Node.js, Python с масштабируемой архитектурой.', 120000.00, 21, 0, 'backend-development'),
(2, 'Брендинг и айдентика', 'Полный цикл создания визуальной идентичности: логотип, гайдлайны, носители.', 50000.00, 10, 0, 'branding'),
(2, 'Моушн-дизайн', 'Анимации и интерактивные элементы, которые оживляют ваш продукт.', 70000.00, 14, 0, 'motion-design'),
(3, 'DevOps и CI/CD', 'Настройка автоматизации деплоя, мониторинга и масштабирования.', 150000.00, 21, 0, 'devops'),
(3, 'Техническая поддержка 24/7', 'Круглосуточный мониторинг и оперативное решение инцидентов.', 30000.00, 1, 0, 'support-24-7'),
(4, 'Промо-видео', 'Создание рекламных и презентационных видеороликов для вашего продукта.', 90000.00, 14, 0, 'promo-video'),
(4, 'Моушн-графика', 'Сложные анимированные графики и визуализации для контента.', 60000.00, 10, 0, 'motion-graphics'),
(1, 'Мобильная разработка', 'Нативные и кросс-платформенные мобильные приложения.', 180000.00, 30, 1, 'mobile-development');

-- Admin user is created via setup_admin.php script
-- Run setup_admin.php in browser after importing this schema
