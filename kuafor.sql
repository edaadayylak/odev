-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 28 May 2025, 08:03:56
-- Sunucu sürümü: 9.1.0
-- PHP Sürümü: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `odev`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', '2025-05-27 14:25:59');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategoriler`
--

DROP TABLE IF EXISTS `kategoriler`;
CREATE TABLE IF NOT EXISTS `kategoriler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kategori_adi` varchar(100) NOT NULL,
  `fiyat` decimal(10,2) NOT NULL,
  `sure` int NOT NULL COMMENT 'Dakika cinsinden süre',
  `aktif` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `kategoriler`
--

INSERT INTO `kategoriler` (`id`, `kategori_adi`, `fiyat`, `sure`, `aktif`, `created_at`) VALUES
(1, 'Saç Kesimi', 150.00, 30, 1, '2025-05-27 14:43:51'),
(2, 'Saç Boyama', 400.00, 120, 1, '2025-05-27 14:43:51'),
(3, 'Fön', 100.00, 30, 1, '2025-05-27 14:43:51'),
(4, 'Manikür', 120.00, 45, 1, '2025-05-27 14:43:51'),
(5, 'Pedikür', 150.00, 45, 1, '2025-05-27 14:43:51'),
(6, 'Saç Bakımı', 250.00, 60, 1, '2025-05-27 14:43:51'),
(7, 'Cilt Bakımı', 300.00, 60, 1, '2025-05-27 14:43:51');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `randevular`
--

DROP TABLE IF EXISTS `randevular`;
CREATE TABLE IF NOT EXISTS `randevular` (
  `id` int NOT NULL AUTO_INCREMENT,
  `adsoyad` varchar(100) NOT NULL,
  `telefon` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cinsiyet` enum('Kadın','Erkek') NOT NULL,
  `tarih` date NOT NULL,
  `saat` time NOT NULL,
  `islem` varchar(50) NOT NULL,
  `notlar` text,
  `durum` enum('Bekliyor','Onaylandı','Tamamlandı','İptal','Gelmedi') DEFAULT 'Bekliyor',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `islem_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `randevular`
--

INSERT INTO `randevular` (`id`, `adsoyad`, `telefon`, `email`, `cinsiyet`, `tarih`, `saat`, `islem`, `notlar`, `durum`, `created_at`, `islem_id`) VALUES
(1, 'Ayşe Yılmaz', '5551234567', 'ayse@email.com', 'Kadın', '2025-05-27', '10:00:00', 'Saç Kesimi', 'Kısa kesim tercih ediyor', 'Onaylandı', '2025-05-27 14:25:59', ''),
(2, 'Mehmet Demir', '5559876543', 'mehmet@email.com', 'Erkek', '2025-05-28', '14:00:00', 'Saç Kesimi', 'Sakal tıraşı da istiyor', 'Onaylandı', '2025-05-27 14:25:59', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
