-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 03 Nis 2025, 12:58:13
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
-- Veritabanı: `filo_takip`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `maintenance_records`
--

CREATE TABLE `maintenance_records` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `maintenance_type` enum('Periyodik Bakım','Arıza','Lastik Değişimi','Yağ Değişimi','Diğer') NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `km_reading` int(11) NOT NULL,
  `status` enum('Planlandı','Devam Ediyor','Tamamlandı','İptal') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `service_provider` varchar(100) DEFAULT NULL,
  `next_maintenance_date` date DEFAULT NULL,
  `next_maintenance_km` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `maintenance_records`
--

INSERT INTO `maintenance_records` (`id`, `vehicle_id`, `maintenance_type`, `description`, `start_date`, `end_date`, `cost`, `km_reading`, `status`, `notes`, `created_at`, `created_by`, `service_provider`, `next_maintenance_date`, `next_maintenance_km`, `updated_at`) VALUES
(1, 2, 'Periyodik Bakım', '10.000 km bakımı, filtre ve yağ değişimi', '2023-03-24', '2023-03-24', 1250.00, 10000, 'Tamamlandı', NULL, '2023-03-24 08:00:00', 1, 'ŞANTİYE SERVİSİ', NULL, 20200, '2025-04-03 10:57:36'),
(2, 3, 'Lastik Değişimi', '4 adet lastik değişimi', '2023-03-23', NULL, 3200.00, 45000, 'Devam Ediyor', 'Arka lastikler değiştirildi, ön lastikler bekleniyor', '2023-03-23 09:00:00', 1, NULL, NULL, NULL, '2025-04-03 10:56:58'),
(3, 4, 'Yağ Değişimi', 'Motor yağı ve filtre değişimi', '2023-03-22', '2023-03-22', 750.00, 8500, 'Tamamlandı', NULL, '2023-03-22 10:00:00', 1, NULL, NULL, NULL, '2025-04-03 10:56:58'),
(4, 6, 'Arıza', 'Fren sistemi arızası', '2023-03-20', '2023-03-21', 1800.00, 45000, 'Tamamlandı', 'Fren diskleri ve balataları değiştirildi', '2023-03-20 11:00:00', 1, NULL, NULL, NULL, '2025-04-03 10:56:58'),
(5, 8, 'Periyodik Bakım', '50.000 km bakımı', '2023-03-15', '2023-03-16', 2200.00, 50000, 'Tamamlandı', 'Tüm filtreler, yağlar ve kayışlar değiştirildi', '2023-03-15 12:00:00', 1, NULL, NULL, NULL, '2025-04-03 10:56:58');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `maintenance_records`
--
ALTER TABLE `maintenance_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `maintenance_records`
--
ALTER TABLE `maintenance_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `maintenance_records`
--
ALTER TABLE `maintenance_records`
  ADD CONSTRAINT `maintenance_records_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_records_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
