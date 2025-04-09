-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 03 Nis 2025, 11:40:53
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
-- Tablo için tablo yapısı `certificate_types`
--

CREATE TABLE `certificate_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `certificate_types`
--

INSERT INTO `certificate_types` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Forklift Operatörü', 'Forklift kullanım sertifikası', '2025-04-02 13:13:45', '2025-04-02 13:13:45'),
(2, 'Vinç Operatörü', 'Vinç kullanım sertifikası', '2025-04-02 13:13:45', '2025-04-02 13:13:45'),
(3, 'İş Makinesi Operatörü', 'İş makinesi kullanım sertifikası', '2025-04-02 13:13:45', '2025-04-02 13:13:45'),
(4, 'Yüksek İş Platformu Operatörü', 'Yüksek iş platformu kullanım sertifikası', '2025-04-02 13:13:45', '2025-04-02 13:13:45'),
(5, 'Kaldırma ve Taşıma Operatörü', 'Kaldırma ve taşıma ekipmanları kullanım sertifikası', '2025-04-02 13:13:45', '2025-04-02 13:13:45');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `tax_office` varchar(100) DEFAULT NULL,
  `tax_number` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `status` enum('Aktif','Pasif') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `tax_office`, `tax_number`, `address`, `phone`, `email`, `logo_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ABC Lojistik A.Ş.', 'İstanbul VD', '1234567890', 'Ataşehir, İstanbul', '(212) 555-1234', 'info@abclojistik.com', NULL, 'Aktif', '2025-04-03 07:50:21', '2025-04-03 07:50:21'),
(3, 'Delta Nakliyat', 'İzmir VD', '5678901234', 'Konak, İzmir', '(232) 333-9876', 'delta@deltanakliyat.com', NULL, 'Aktif', '2025-04-03 07:50:21', '2025-04-03 08:54:44'),
(4, 'Duygu İnşaat', 'Ankara', '1225221111', 'Ankara', '(312) 222-1212', 'duygu@duygugrup.com', 'uploads/company_logos/67ee4d78e2c0c_duygu.jpg', 'Aktif', '2025-04-03 08:57:28', '2025-04-03 08:57:28');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `identity_number` varchar(20) NOT NULL,
  `license_number` varchar(20) NOT NULL,
  `primary_license_type` varchar(5) DEFAULT NULL,
  `license_issue_date` date DEFAULT NULL,
  `license_expiry_date` date DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Aktif','Pasif','İzinli') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `drivers`
--

INSERT INTO `drivers` (`id`, `name`, `surname`, `identity_number`, `license_number`, `primary_license_type`, `license_issue_date`, `license_expiry_date`, `company_id`, `phone`, `email`, `address`, `status`, `created_at`) VALUES
(1, 'Ahmet', 'Yılmaz', '12345678910', 'B123456', 'B', '2015-05-15', '2025-05-15', 4, '5551234567', 'ahmet@example.com', 'Beşiktaş, İstanbul', 'Aktif', '2023-03-22 08:00:00'),
(2, 'Mehmet', 'Demir', '12345678911', 'E234567', 'CE', '2017-08-22', '2027-08-22', NULL, '5552345678', 'mehmet@example.com', 'Kadıköy, İstanbul', 'Aktif', '2023-03-22 08:30:00'),
(3, 'Ayşe', 'Kaya', '12345678912', 'B345678', 'B', '2017-01-05', '2027-01-05', NULL, '5553456789', 'ayse@example.com', 'Şişli, İstanbul', 'İzinli', '2023-03-22 09:00:00'),
(4, 'Fatma', 'Çelik', '12345678913', 'B456789', 'B', NULL, NULL, NULL, '5554567890', 'fatma@example.com', 'Üsküdar, İstanbul', 'İzinli', '2023-03-22 09:30:00'),
(5, 'Ali', 'Yıldız', '12345678914', 'E567890', 'D', '2015-09-18', '2025-09-18', NULL, '5555678901', 'ali@example.com', 'Beyoğlu, İstanbul', 'Aktif', '2023-03-22 10:00:00'),
(6, 'Mustafa', 'Şahin', '12345678915', 'C678901', 'C', '2018-04-30', '2028-04-30', NULL, '5556789012', 'mustafa@example.com', 'Bakırköy, İstanbul', 'Aktif', '2023-03-22 10:30:00'),
(7, 'Zeynep', 'Öztürk', '12345678916', 'B789012', 'B', '2016-12-01', '2026-12-01', NULL, '5557890123', 'zeynep@example.com', 'Ataşehir, İstanbul', 'Aktif', '2023-03-22 11:00:00'),
(8, 'Hüseyin', 'Aydın', '12345678917', 'E890123', 'CE', '2016-02-20', '2026-02-20', NULL, '5558901234', 'huseyin@example.com', 'Maltepe, İstanbul', 'Aktif', '2023-03-22 11:30:00'),
(9, 'ismail', 'çelikbaş', '40574073114', 'DE258556', 'A', '0000-00-00', '0000-00-00', NULL, '5552552525', 'ismailcelikbas66@gmail.com', 'Yozgat Merkez', 'Aktif', '2025-04-02 11:09:00'),
(10, 'SEHER', 'EROL', '58584445666', 'DE885554', 'B', '0000-00-00', '0000-00-00', NULL, '255551223', 'seher@bb.com', 'yeni şöför', 'Aktif', '2025-04-02 11:21:47'),
(12, 'abc', 'abc', '45555555555', 'EDA455454', 'M', '0000-00-00', '0000-00-00', NULL, '4455552211', 'aaaa@bb.com', 'aaaa', 'Aktif', '2025-04-02 11:52:11'),
(13, 'deneme2', 'deneme3', '11111111111', 'DE55444545', 'B', '0000-00-00', '0000-00-00', NULL, '2553691212', 'aba@bb.com', '', 'Aktif', '2025-04-02 12:05:05'),
(14, 'sevval', 'deneme', '55555555555', 'DE112444', 'A', NULL, NULL, NULL, '3232225512', 'abx@bb.com', 'Yozgat Merkez', 'Aktif', '2025-04-02 12:12:14');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `driver_certificates`
--

CREATE TABLE `driver_certificates` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `certificate_type_id` int(11) NOT NULL,
  `certificate_number` varchar(50) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `issuing_authority` varchar(100) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `driver_certificates`
--

INSERT INTO `driver_certificates` (`id`, `driver_id`, `certificate_type_id`, `certificate_number`, `issue_date`, `expiry_date`, `issuing_authority`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '145255', '2002-05-09', '2025-04-09', 'MİLLİ EĞİTİM', '', '2025-04-02 13:16:32', '2025-04-03 07:00:06'),
(2, 12, 1, '1145224', '2020-05-09', '2025-05-09', 'MİLLİ EĞİTİM', '', '2025-04-02 13:18:27', '2025-04-02 13:18:27'),
(3, 1, 5, '154555', '2021-01-02', '2025-06-02', 'MİLLİ EĞİTİM', '', '2025-04-02 13:33:44', '2025-04-02 13:33:44'),
(4, 14, 5, '145555', '2020-01-02', '2025-10-01', 'ÜNİVERSİTE', '', '2025-04-02 14:11:00', '2025-04-02 14:11:00'),
(5, 4, 2, '5252411', '2020-12-01', '2025-02-11', 'milli eğitim', '', '2025-04-03 07:01:14', '2025-04-03 07:01:14'),
(6, 10, 3, '452255', '2021-01-03', '2025-04-30', 'üniversite', '', '2025-04-03 07:19:22', '2025-04-03 07:19:22');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `driver_licenses`
--

CREATE TABLE `driver_licenses` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `license_type_id` int(11) NOT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `driver_licenses`
--

INSERT INTO `driver_licenses` (`id`, `driver_id`, `license_type_id`, `issue_date`, `expiry_date`, `notes`, `created_at`) VALUES
(2, 1, 10, '2002-07-20', '2028-07-20', 'Ağır vasıta ehliyeti', '2025-04-02 10:51:59'),
(3, 2, 15, '2016-03-10', '2026-03-10', NULL, '2025-04-02 10:51:59'),
(5, 2, 11, '2017-08-22', '2027-08-22', 'Tır kullanım izni', '2025-04-02 10:51:59'),
(7, 4, 6, '2019-11-12', '2029-11-12', NULL, '2025-04-02 10:51:59'),
(8, 5, 14, '2015-09-18', '2025-09-18', 'Otobüs kullanım belgesi', '2025-04-02 10:51:59'),
(9, 5, 15, '2015-09-18', '2025-09-18', NULL, '2025-04-02 10:51:59'),
(11, 7, 6, '2016-12-01', '2026-12-01', NULL, '2025-04-02 10:51:59'),
(12, 8, 15, '2014-08-15', '2024-08-15', NULL, '2025-04-02 10:51:59'),
(13, 8, 10, '2014-08-15', '2024-08-15', NULL, '2025-04-02 10:51:59'),
(14, 8, 11, '2016-02-20', '2026-02-20', 'Ek yetki', '2025-04-02 10:51:59'),
(16, 9, 6, '2002-12-20', '2025-12-20', 'B OTOMOBİL', '2025-04-02 11:10:19'),
(17, 10, 6, '2002-02-09', '2029-02-09', 'Birincil ehliyet', '2025-04-02 11:21:47'),
(22, 12, 6, '2002-12-09', '2025-12-09', '', '2025-04-02 12:02:09'),
(23, 13, 6, '2022-02-12', '2025-06-27', 'Birincil ehliyet', '2025-04-02 12:05:05'),
(24, 14, 4, '2002-05-06', '2030-03-03', 'Birincil ehliyet', '2025-04-02 12:12:14'),
(25, 14, 3, '2002-10-15', '2029-12-02', '', '2025-04-02 12:13:34'),
(26, 1, 6, '2015-02-12', '2026-02-19', '', '2025-04-02 12:24:16'),
(27, 3, 8, '2002-02-19', '2025-05-17', '', '2025-04-02 14:15:49'),
(28, 10, 12, NULL, NULL, '', '2025-04-03 07:18:41');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `fuel_purchases`
--

CREATE TABLE `fuel_purchases` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(200) NOT NULL,
  `fuel_type` enum('Benzin','Dizel','LPG','Elektrik') NOT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'Litre',
  `cost` decimal(10,2) NOT NULL COMMENT 'TL',
  `unit_price` decimal(10,2) NOT NULL COMMENT 'TL/Litre',
  `tank_id` int(11) NOT NULL,
  `invoice_number` varchar(100) DEFAULT NULL,
  `date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `fuel_purchases`
--

INSERT INTO `fuel_purchases` (`id`, `supplier_name`, `fuel_type`, `amount`, `cost`, `unit_price`, `tank_id`, `invoice_number`, `date`, `notes`, `created_at`, `created_by`) VALUES
(1, 'PETROL OFİSİ', 'Dizel', 3000.00, 100000.00, 33.33, 1, 'FA54255522', '2025-04-02', 'İRSALİYESİ BUY', '2025-04-02 09:02:54', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `fuel_records`
--

CREATE TABLE `fuel_records` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `tank_id` int(11) NOT NULL,
  `fuel_type` enum('Benzin','Dizel','LPG','Elektrik') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'Litre',
  `cost` decimal(10,2) DEFAULT NULL COMMENT 'TL',
  `km_reading` int(11) DEFAULT NULL COMMENT 'Kilometre',
  `hour_reading` decimal(10,2) DEFAULT NULL COMMENT 'Çalışma Saati',
  `date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `fuel_records`
--

INSERT INTO `fuel_records` (`id`, `vehicle_id`, `driver_id`, `tank_id`, `fuel_type`, `amount`, `cost`, `km_reading`, `hour_reading`, `date`, `notes`, `created_at`, `created_by`) VALUES
(1, 1, 1, 2, '', 450.00, 12500.00, 20230325, NULL, '0000-00-00', '2023-03-25 13:00:00', '0000-00-00 00:00:00', 1),
(2, 2, 2, 2, '', 550.00, 28900.00, 20230324, NULL, '0000-00-00', '2023-03-24 17:00:00', '0000-00-00 00:00:00', 1),
(5, 1, 1, 2, '', 500.00, 13100.00, 20230321, NULL, '0000-00-00', '2023-03-21 12:00:00', '0000-00-00 00:00:00', 1),
(6, 6, 5, 2, '', 1800.00, 45700.00, 20230320, NULL, '0000-00-00', '2023-03-20 19:00:00', '0000-00-00 00:00:00', 1),
(7, 8, 6, 2, '', 1620.00, 67800.00, 20230319, NULL, '0000-00-00', '2023-03-19 13:00:00', '0000-00-00 00:00:00', 1),
(8, 1, 1, 2, '', 4250.00, 148500.00, 20250402, NULL, '0000-00-00', '2025-04-02 11:11:53', '0000-00-00 00:00:00', 1),
(10, 11, 7, 1, 'Dizel', 200.00, 0.00, 145555, 13350.00, '2025-04-02', '', '2025-04-02 10:21:05', 1),
(11, 2, 2, 1, 'Dizel', 200.00, 0.00, 145550, NULL, '2025-04-02', '', '2025-04-02 10:25:31', 1),
(12, 10, 8, 1, 'Dizel', 222.00, 0.00, 145780, 12552.00, '2025-04-01', 'km-118', '2025-04-02 10:26:32', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `fuel_tanks`
--

CREATE TABLE `fuel_tanks` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('Sabit','Mobil') NOT NULL,
  `capacity` decimal(10,2) NOT NULL COMMENT 'Litre',
  `current_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Litre',
  `location` varchar(200) DEFAULT NULL,
  `status` enum('Aktif','Pasif','Bakımda') NOT NULL DEFAULT 'Aktif',
  `fuel_type` enum('Benzin','Dizel','LPG','Elektrik') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `fuel_tanks`
--

INSERT INTO `fuel_tanks` (`id`, `name`, `type`, `capacity`, `current_amount`, `location`, `status`, `fuel_type`, `created_at`) VALUES
(1, 'Ana Şantiye Tankı', 'Sabit', 10000.00, 8228.00, 'Ana Şantiye', 'Aktif', 'Dizel', '2025-04-02 08:28:16'),
(2, 'Mobil Tank 1', 'Mobil', 1000.00, 950.00, 'Saha 1', 'Aktif', 'Dizel', '2025-04-02 08:28:16'),
(3, 'Mobil Tank 2', 'Mobil', 1000.00, 500.00, 'Saha 2', 'Aktif', 'Dizel', '2025-04-02 08:28:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `fuel_transfers`
--

CREATE TABLE `fuel_transfers` (
  `id` int(11) NOT NULL,
  `source_tank_id` int(11) NOT NULL,
  `destination_tank_id` int(11) NOT NULL,
  `fuel_type` enum('Benzin','Dizel','LPG','Elektrik') NOT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'Litre',
  `date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `license_types`
--

CREATE TABLE `license_types` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `license_types`
--

INSERT INTO `license_types` (`id`, `code`, `name`, `description`) VALUES
(1, 'M', 'M Sınıfı', 'Motorlu bisiklet (Moped) kullanımı için'),
(2, 'A1', 'A1 Sınıfı', 'Silindir hacmi 125 cc\'ye kadar, gücü 11 kilovatı geçmeyen sepetsiz iki tekerlekli motosikletler'),
(3, 'A2', 'A2 Sınıfı', 'Gücü 35 kilovatı geçmeyen, gücü/ağırlığı 0,2 kilovatı/kiloğramı geçmeyen iki tekerlekli motosikletler'),
(4, 'A', 'A Sınıfı', 'Gücü 35 kilovatı veya gücü/ağırlığı 0,2 kilovatı/kiloğramı geçen iki tekerlekli motosikletler'),
(5, 'B1', 'B1 Sınıfı', 'Net motor gücü 15 kilovatı ve net ağırlığı 400 kilogram geçmeyen dört tekerlekli motosikletler'),
(6, 'B', 'B Sınıfı', 'Otomobil ve kamyonet (3500 kg\'a kadar)'),
(7, 'BE', 'BE Sınıfı', 'B sınıfı sürücü belgesi ile sürülebilen otomobil veya kamyonetin römork takılmış hali'),
(8, 'C1', 'C1 Sınıfı', 'Azami yüklü ağırlığı 3.500 kg\'ın üzerinde olan ve 7.500 kg\'ı geçmeyen kamyon ve çekiciler'),
(9, 'C1E', 'C1E Sınıfı', 'C1 sınıfı sürücü belgesi ile sürülebilen araçlara takılan ve azami yüklü ağırlığı 750 kg\'ı geçen römorklu kamyonlar'),
(10, 'C', 'C Sınıfı', 'Kamyon ve Çekici (Tır)'),
(11, 'CE', 'CE Sınıfı', 'C sınıfı sürücü belgesi ile sürülebilen araçlarla römork takılan hali'),
(12, 'D1', 'D1 Sınıfı', 'Minibüs'),
(13, 'D1E', 'D1E Sınıfı', 'D1 sınıfı sürücü belgesi ile sürülebilen araçlara takılan ve azami yüklü ağırlığı 750 kg\'ı geçen römorklu halı'),
(14, 'D', 'D Sınıfı', 'Otobüs'),
(15, 'DE', 'DE Sınıfı', 'D sınıfı sürücü belgesi ile sürülebilen araçlara römork takılan hali'),
(16, 'F', 'F Sınıfı', 'Traktör kullanımı için'),
(17, 'G', 'G Sınıfı', 'İş makinası türündeki motorlu araçları kullanabilme');

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
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `maintenance_records`
--

INSERT INTO `maintenance_records` (`id`, `vehicle_id`, `maintenance_type`, `description`, `start_date`, `end_date`, `cost`, `km_reading`, `status`, `notes`, `created_at`, `created_by`) VALUES
(1, 2, 'Periyodik Bakım', '10.000 km bakımı, filtre ve yağ değişimi', '2023-03-24', '2023-03-24', 1250.00, 10000, 'Tamamlandı', NULL, '2023-03-24 08:00:00', 1),
(2, 3, 'Lastik Değişimi', '4 adet lastik değişimi', '2023-03-23', NULL, 3200.00, 45000, 'Devam Ediyor', 'Arka lastikler değiştirildi, ön lastikler bekleniyor', '2023-03-23 09:00:00', 1),
(3, 4, 'Yağ Değişimi', 'Motor yağı ve filtre değişimi', '2023-03-22', '2023-03-22', 750.00, 8500, 'Tamamlandı', NULL, '2023-03-22 10:00:00', 1),
(4, 6, 'Arıza', 'Fren sistemi arızası', '2023-03-20', '2023-03-21', 1800.00, 45000, 'Tamamlandı', 'Fren diskleri ve balataları değiştirildi', '2023-03-20 11:00:00', 1),
(5, 8, 'Periyodik Bakım', '50.000 km bakımı', '2023-03-15', '2023-03-16', 2200.00, 50000, 'Tamamlandı', 'Tüm filtreler, yağlar ve kayışlar değiştirildi', '2023-03-15 12:00:00', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `system_logs`
--

INSERT INTO `system_logs` (`id`, `user_id`, `action`, `type`, `ip_address`, `details`, `created_at`) VALUES
(14, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:16:38'),
(15, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:16:45'),
(16, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:16:49'),
(17, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:17:42'),
(18, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:17:47'),
(19, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:18:31'),
(20, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:18:39'),
(21, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:19:37'),
(22, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:19:37'),
(23, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:19:38'),
(24, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:19:38'),
(25, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:19:42'),
(26, 1, 'Oturum açıldı', 'login', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-02 07:20:15'),
(27, 1, 'Oturum kapatıldı', 'logout', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-02 07:20:42'),
(28, NULL, 'Hata oluştu', 'error', '::1', 'Hata: Başarısız giriş denemesi, Modül: users, E-posta: admin@filotak.ip', '2025-04-02 07:20:54'),
(30, 1, 'Oturum açıldı', 'login', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-02 07:21:04'),
(31, 1, 'Eski loglar temizlendi', 'maintenance', '::1', '30 günden eski loglar silindi', '2025-04-02 07:23:41'),
(33, 1, 'Log silindi', 'delete', '::1', 'ID: 32', '2025-04-02 07:24:27'),
(34, 1, 'Eski loglar temizlendi', 'maintenance', '::1', '7 günden eski loglar silindi', '2025-04-02 07:24:37'),
(35, 1, 'Oturum kapatıldı', 'logout', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-02 15:09:06'),
(36, 1, 'Oturum açıldı', 'login', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-02 15:09:27'),
(37, 1, 'Oturum açıldı', 'login', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-03 06:39:29'),
(38, 1, 'Oturum kapatıldı', 'logout', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-03 06:41:22'),
(39, 1, 'Oturum açıldı', 'login', '::1', 'Kullanıcı adı: admin@filotak.ip', '2025-04-03 06:48:14'),
(40, 1, 'Eski loglar temizlendi', 'maintenance', '::1', '30 günden eski loglar silindi', '2025-04-03 09:35:52');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin Kullanıcı', 'admin@filotak.ip', '$2y$10$nOu8a/7Pt8vi4rQaOadGmOwxmhb2449Nw4CiW8fvoi0k/4QeYk8gm', 'admin', '2023-03-28 12:00:00'),
(2, 'Veri Girişi Kullanıcısı', 'user@filotak.ip', '$2y$10$JL4Mmf9CzJaWRdtbuCmk2.Bqt1eGLGovnTJUFXnLf9jyAd31Hts32', 'user', '2023-03-28 12:00:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `vehicle_type` enum('Otomobil','Kamyonet','Kamyon','Otobüs') NOT NULL,
  `status` enum('Aktif','Pasif','Bakımda') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `vehicles`
--

INSERT INTO `vehicles` (`id`, `plate_number`, `brand`, `model`, `year`, `company_id`, `vehicle_type`, `status`, `created_at`) VALUES
(1, '34 ABC 123', 'Mercedes', 'Sprinter', 2020, 1, 'Otomobil', 'Aktif', '2023-03-20 13:00:00'),
(2, '34 DEF 456', 'Ford', 'Transit', 2019, 4, 'Otomobil', 'Aktif', '2023-03-20 13:30:00'),
(3, '34 GHI 789', 'Volvo', 'FH16', 2021, NULL, 'Kamyon', 'Pasif', '2023-03-20 14:00:00'),
(4, '34 JKL 012', 'Renault', 'Clio', 2022, NULL, 'Otomobil', 'Aktif', '2023-03-21 09:00:00'),
(5, '34 MNO 345', 'Toyota', 'Corolla', 2022, NULL, 'Otomobil', 'Aktif', '2023-03-21 10:00:00'),
(6, '34 PQR 678', 'Mercedes', 'Travego', 2018, NULL, 'Otobüs', 'Bakımda', '2023-03-21 11:00:00'),
(7, '34 STU 901', 'Volvo', '9700', 2019, NULL, 'Otobüs', 'Aktif', '2023-03-21 12:00:00'),
(8, '34 VWX 234', 'Scania', 'R450', 2020, NULL, 'Kamyon', 'Aktif', '2023-03-21 13:00:00'),
(9, '34 YZA 567', 'MAN', 'TGX', 2021, NULL, 'Kamyon', 'Pasif', '2023-03-21 14:00:00'),
(10, '34 BCD 890', 'Ford', 'Courier', 2022, NULL, 'Kamyonet', 'Aktif', '2023-03-21 15:00:00'),
(11, 'EX-32', 'CATEPİLLER', '336D', 2020, NULL, 'Kamyonet', 'Aktif', '2025-04-02 07:56:54'),
(12, '66 DE 953', 'FORD', 'CONNET', 2025, 4, 'Otomobil', 'Aktif', '2025-04-03 09:13:42');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vehicle_assignments`
--

CREATE TABLE `vehicle_assignments` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Aktif','Tamamlandı','İptal') NOT NULL DEFAULT 'Aktif',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `vehicle_assignments`
--

INSERT INTO `vehicle_assignments` (`id`, `vehicle_id`, `driver_id`, `start_date`, `end_date`, `status`, `notes`, `created_at`) VALUES
(1, 1, 1, '2023-03-22', NULL, 'Aktif', 'Düzenli şehir içi teslimat', '2023-03-22 12:00:00'),
(2, 2, 2, '2023-03-22', NULL, 'Aktif', 'Uzun yol teslimatları', '2023-03-22 12:30:00'),
(3, 4, 3, '2023-03-22', '2023-04-05', 'Tamamlandı', 'Yönetici aracı', '2023-03-22 13:00:00'),
(4, 5, 4, '2023-03-23', NULL, 'Aktif', 'Şehir içi kısa mesafe', '2023-03-23 08:00:00'),
(5, 6, 5, '2023-03-23', NULL, 'Aktif', 'Şehirlerarası personel taşıma', '2023-03-23 09:00:00'),
(6, 8, 6, '2023-03-23', NULL, 'Aktif', 'Ağır yük taşıma', '2023-03-23 10:00:00'),
(7, 10, 8, '2023-03-24', NULL, 'Aktif', 'Hızlı teslimat', '2023-03-24 08:00:00'),
(8, 11, 7, '2025-04-02', NULL, 'Aktif', 'KULLAN BAKALM', '2025-04-02 08:01:42');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `certificate_types`
--
ALTER TABLE `certificate_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Tablo için indeksler `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tax_number` (`tax_number`),
  ADD KEY `idx_company_status` (`status`);

--
-- Tablo için indeksler `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identity_number` (`identity_number`),
  ADD UNIQUE KEY `license_number` (`license_number`),
  ADD KEY `idx_drivers_company` (`company_id`);

--
-- Tablo için indeksler `driver_certificates`
--
ALTER TABLE `driver_certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `certificate_type_id` (`certificate_type_id`);

--
-- Tablo için indeksler `driver_licenses`
--
ALTER TABLE `driver_licenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `license_type_id` (`license_type_id`);

--
-- Tablo için indeksler `fuel_purchases`
--
ALTER TABLE `fuel_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tank_id` (`tank_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Tablo için indeksler `fuel_records`
--
ALTER TABLE `fuel_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tank_id` (`tank_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Tablo için indeksler `fuel_tanks`
--
ALTER TABLE `fuel_tanks`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `fuel_transfers`
--
ALTER TABLE `fuel_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `source_tank_id` (`source_tank_id`),
  ADD KEY `destination_tank_id` (`destination_tank_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Tablo için indeksler `license_types`
--
ALTER TABLE `license_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Tablo için indeksler `maintenance_records`
--
ALTER TABLE `maintenance_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Tablo için indeksler `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type` (`type`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate_number` (`plate_number`),
  ADD KEY `idx_vehicles_company` (`company_id`);

--
-- Tablo için indeksler `vehicle_assignments`
--
ALTER TABLE `vehicle_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `certificate_types`
--
ALTER TABLE `certificate_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `driver_certificates`
--
ALTER TABLE `driver_certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `driver_licenses`
--
ALTER TABLE `driver_licenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Tablo için AUTO_INCREMENT değeri `fuel_purchases`
--
ALTER TABLE `fuel_purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `fuel_records`
--
ALTER TABLE `fuel_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `fuel_tanks`
--
ALTER TABLE `fuel_tanks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `fuel_transfers`
--
ALTER TABLE `fuel_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `license_types`
--
ALTER TABLE `license_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Tablo için AUTO_INCREMENT değeri `maintenance_records`
--
ALTER TABLE `maintenance_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `vehicle_assignments`
--
ALTER TABLE `vehicle_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `fk_drivers_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `driver_certificates`
--
ALTER TABLE `driver_certificates`
  ADD CONSTRAINT `driver_certificates_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `driver_certificates_ibfk_2` FOREIGN KEY (`certificate_type_id`) REFERENCES `certificate_types` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `driver_licenses`
--
ALTER TABLE `driver_licenses`
  ADD CONSTRAINT `driver_licenses_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `driver_licenses_ibfk_2` FOREIGN KEY (`license_type_id`) REFERENCES `license_types` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `fuel_purchases`
--
ALTER TABLE `fuel_purchases`
  ADD CONSTRAINT `fuel_purchases_ibfk_1` FOREIGN KEY (`tank_id`) REFERENCES `fuel_tanks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fuel_purchases_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `fuel_records`
--
ALTER TABLE `fuel_records`
  ADD CONSTRAINT `fuel_records_ibfk_1` FOREIGN KEY (`tank_id`) REFERENCES `fuel_tanks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fuel_records_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fuel_records_ibfk_3` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fuel_records_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `fuel_transfers`
--
ALTER TABLE `fuel_transfers`
  ADD CONSTRAINT `fuel_transfers_ibfk_1` FOREIGN KEY (`source_tank_id`) REFERENCES `fuel_tanks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fuel_transfers_ibfk_2` FOREIGN KEY (`destination_tank_id`) REFERENCES `fuel_tanks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fuel_transfers_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `maintenance_records`
--
ALTER TABLE `maintenance_records`
  ADD CONSTRAINT `maintenance_records_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_records_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `fk_vehicles_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `vehicle_assignments`
--
ALTER TABLE `vehicle_assignments`
  ADD CONSTRAINT `vehicle_assignments_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_assignments_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
