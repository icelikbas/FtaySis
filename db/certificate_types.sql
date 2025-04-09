CREATE TABLE IF NOT EXISTS `certificate_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Örnek sertifika türleri
INSERT INTO `certificate_types` (`name`, `description`) VALUES
('Forklift Operatörü', 'Forklift kullanım sertifikası'),
('Vinç Operatörü', 'Vinç kullanım sertifikası'),
('İş Makinesi Operatörü', 'İş makinesi kullanım sertifikası'),
('Yüksek İş Platformu Operatörü', 'Yüksek iş platformu kullanım sertifikası'),
('Kaldırma ve Taşıma Operatörü', 'Kaldırma ve taşıma ekipmanları kullanım sertifikası'); 