-- Şirketler Tablosu
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(200) NOT NULL,
  `tax_office` varchar(100) DEFAULT NULL,
  `tax_number` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `status` enum('Aktif','Pasif') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `tax_number` (`tax_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sürücüler tablosuna şirket ID ekle
ALTER TABLE `drivers` ADD COLUMN IF NOT EXISTS `company_id` int(11) DEFAULT NULL AFTER `license_expiry_date`;
ALTER TABLE `drivers` ADD CONSTRAINT `fk_drivers_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

-- Araçlar tablosuna şirket ID ekle
ALTER TABLE `vehicles` ADD COLUMN IF NOT EXISTS `company_id` int(11) DEFAULT NULL AFTER `year`;
ALTER TABLE `vehicles` ADD CONSTRAINT `fk_vehicles_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

-- Örnek şirket verileri
INSERT INTO `companies` (`company_name`, `tax_office`, `tax_number`, `address`, `phone`, `email`, `status`) VALUES
('ABC Lojistik A.Ş.', 'İstanbul VD', '1234567890', 'Ataşehir, İstanbul', '(212) 555-1234', 'info@abclojistik.com', 'Aktif'),
('XYZ Taşımacılık Ltd. Şti.', 'Ankara VD', '9876543210', 'Çankaya, Ankara', '(312) 444-5678', 'iletisim@xyztasima.com', 'Aktif'),
('Delta Nakliyat', 'İzmir VD', '5678901234', 'Konak, İzmir', '(232) 333-9876', 'delta@deltanakliyat.com', 'Pasif');

-- İndeks ekleme
CREATE INDEX IF NOT EXISTS `idx_company_status` ON `companies` (`status`);
CREATE INDEX IF NOT EXISTS `idx_drivers_company` ON `drivers` (`company_id`);
CREATE INDEX IF NOT EXISTS `idx_vehicles_company` ON `vehicles` (`company_id`);