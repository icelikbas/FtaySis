CREATE TABLE IF NOT EXISTS `certificate_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Örnek veriler
INSERT INTO `certificate_types` (`name`, `description`) VALUES
('Forklift Operatörü', 'Forklift kullanım sertifikası'),
('Vinç Operatörü', 'Vinç kullanım sertifikası'),
('Ekskavatör Operatörü', 'Ekskavatör kullanım sertifikası'),
('Loder Operatörü', 'Loder kullanım sertifikası'),
('Greyder Operatörü', 'Greyder kullanım sertifikası'),
('Beko Loder Operatörü', 'Beko Loder kullanım sertifikası'),
('Dozer Operatörü', 'Dozer kullanım sertifikası'),
('Silindir Operatörü', 'Silindir kullanım sertifikası'),
('Kazıcı Yükleyici Operatörü', 'Kazıcı Yükleyici kullanım sertifikası'),
('Kaldırma Platformu Operatörü', 'Kaldırma Platformu kullanım sertifikası'); 