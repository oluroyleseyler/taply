-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3307
-- Üretim Zamanı: 27 Tem 2025, 08:03:58
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `taply_life`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `full_name`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin@taply.life', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Site Yöneticisi', NULL, '2025-07-27 04:56:53', '2025-07-27 04:56:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `url` varchar(500) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `click_count` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `links`
--

INSERT INTO `links` (`id`, `user_id`, `title`, `url`, `icon`, `display_order`, `click_count`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Instagram', 'https://instagram.com/testuser', NULL, 1, 0, 1, '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(2, 1, 'Twitter', 'https://twitter.com/testuser', NULL, 2, 0, 1, '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(3, 1, 'Kişisel Website', 'https://testuser.com', NULL, 3, 0, 1, '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(4, 1, 'YouTube Kanalı', 'https://youtube.com/testuser', NULL, 4, 0, 1, '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(5, 1, 'LinkedIn Profili', 'https://linkedin.com/in/testuser', NULL, 5, 0, 1, '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(7, 2, 'Instagram', 'https://instagram.com/ahmetbalciks_', NULL, 1, 0, 1, '2025-07-27 05:32:08', '2025-07-27 05:32:08');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `link_clicks`
--

CREATE TABLE `link_clicks` (
  `id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `referer` varchar(500) DEFAULT NULL,
  `clicked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `profile_visits`
--

CREATE TABLE `profile_visits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `visitor_ip` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `referer` varchar(500) DEFAULT NULL,
  `visited_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('string','number','boolean','json') DEFAULT 'string',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Taply.life', 'string', 'Site adı', '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(2, 'site_description', 'Kişiye özel link profili platformu', 'string', 'Site açıklaması', '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(3, 'max_links_per_user', '50', 'number', 'Kullanıcı başına maksimum link sayısı', '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(4, 'allow_registrations', '1', 'boolean', 'Yeni kayıtlara izin ver', '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(5, 'require_email_verification', '0', 'boolean', 'E-mail doğrulaması gerekli', '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(6, 'default_theme', 'default', 'string', 'Varsayılan tema', '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(7, 'upload_max_size', '2048', 'number', 'Maksimum dosya yükleme boyutu (KB)', '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(8, 'analytics_enabled', '1', 'boolean', 'İstatistik takibi aktif', '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(9, 'maintenance_mode', '0', 'boolean', 'Bakım modu', '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(10, 'site_url', 'https://taply.life', 'string', 'Site ana URL', '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(11, 'contact_email', 'contact@taply.life', 'string', 'İletişim e-mail adresi', '2025-07-27 04:56:53', '2025-07-27 04:56:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `themes`
--

CREATE TABLE `themes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `css_file` varchar(255) NOT NULL,
  `preview_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `themes`
--

INSERT INTO `themes` (`id`, `name`, `display_name`, `css_file`, `preview_image`, `is_active`, `created_at`, `updated_at`) VALUES
(7, 'default', 'Varsayılan', 'default/style.css', 'default/preview.png', 1, '2025-07-27 05:43:29', '2025-07-27 05:43:29'),
(8, 'sunset', 'Gün Batımı', 'sunset/style.css', 'sunset/preview.png', 1, '2025-07-27 05:43:29', '2025-07-27 05:43:29'),
(9, 'ocean', 'Okyanus', 'ocean/style.css', 'ocean/preview.png', 1, '2025-07-27 05:43:29', '2025-07-27 05:43:29'),
(10, 'forest', 'Orman', 'forest/style.css', 'forest/preview.png', 1, '2025-07-27 05:43:29', '2025-07-27 05:43:29'),
(11, 'night', 'Gece', 'night/style.css', 'night/preview.png', 1, '2025-07-27 05:43:29', '2025-07-27 05:43:29'),
(12, 'rose', 'Gül', 'rose/style.css', 'rose/preview.png', 1, '2025-07-27 05:43:29', '2025-07-27 05:43:29'),
(13, 'cosmic', 'Kozmik', 'cosmic/style.css', 'cosmic/preview.png', 1, '2025-07-27 05:43:29', '2025-07-27 05:43:29'),
(14, 'minimal', 'Minimal', 'minimal/style.css', 'minimal/preview.png', 1, '2025-07-27 05:43:29', '2025-07-27 05:43:29');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `selected_theme` varchar(50) DEFAULT 'default',
  `status` tinyint(1) DEFAULT 1 COMMENT '1: aktif, 0: pasif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `bio`, `profile_picture`, `selected_theme`, `status`, `created_at`, `updated_at`) VALUES
(1, 'testuser', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bu bir test kullanıcısıdır. Gerçek kullanım için silebilirsiniz.', NULL, 'default', 1, '2025-07-27 04:56:53', '2025-07-27 04:56:53'),
(2, 'ahmetbalcik', 'abalcik.ahmet@gmail.com', '$2y$10$xuCEu2TAmfQQSneO7AM5.u6a9QcreKF5tjRsb0E3rnwI4oDukyjye', NULL, NULL, 'default', 1, '2025-07-27 05:19:49', '2025-07-27 05:19:49');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Tablo için indeksler `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_display_order` (`display_order`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_user_order` (`user_id`,`display_order`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Tablo için indeksler `link_clicks`
--
ALTER TABLE `link_clicks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_link_id` (`link_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_clicked_at` (`clicked_at`),
  ADD KEY `idx_ip_address` (`ip_address`),
  ADD KEY `idx_link_date` (`link_id`,`clicked_at`),
  ADD KEY `idx_user_date` (`user_id`,`clicked_at`);

--
-- Tablo için indeksler `profile_visits`
--
ALTER TABLE `profile_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_visited_at` (`visited_at`),
  ADD KEY `idx_visitor_ip` (`visitor_ip`),
  ADD KEY `idx_user_date` (`user_id`,`visited_at`);

--
-- Tablo için indeksler `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_setting_key` (`setting_key`),
  ADD KEY `idx_setting_type` (`setting_type`);

--
-- Tablo için indeksler `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name` (`name`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `link_clicks`
--
ALTER TABLE `link_clicks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `profile_visits`
--
ALTER TABLE `profile_visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `themes`
--
ALTER TABLE `themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `fk_links_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `link_clicks`
--
ALTER TABLE `link_clicks`
  ADD CONSTRAINT `fk_clicks_link_id` FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_clicks_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `profile_visits`
--
ALTER TABLE `profile_visits`
  ADD CONSTRAINT `fk_visits_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
