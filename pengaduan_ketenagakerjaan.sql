/*
SQLyog Community v13.3.0 (64 bit)
MySQL - 10.4.32-MariaDB : Database - pengaduan_ketenagakerjaan
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`pengaduan_ketenagakerjaan` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `pengaduan_ketenagakerjaan`;

/*Table structure for table `admin` */

DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `admin` */

insert  into `admin`(`id`,`username`,`password`,`nama`,`created_at`) values 
(1,'admin','$2y$10$LyGMbzID9/PSqi31MLB5i.k8QHQr8vmAguMrnE9jCAZS55eC3kiTO','Super Admin','2026-09-09 07:11:16');

/*Table structure for table `kategori_pengaduan` */

DROP TABLE IF EXISTS `kategori_pengaduan`;

CREATE TABLE `kategori_pengaduan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `kategori_pengaduan` */

insert  into `kategori_pengaduan`(`id`,`nama_kategori`,`is_active`,`created_at`,`updated_at`) values 
(1,'Norma/Permasalahan Ketenagakerjaan',1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(2,'Kepesertaan Jaminan Sosial Ketenagakerjaan',1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(3,'Pelatihan & Sertifikasi Kerja',1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(4,'Penempatan Tenaga Kerja',1,'2026-09-09 07:11:16','2026-09-09 07:11:16');

/*Table structure for table `masyarakat` */

DROP TABLE IF EXISTS `masyarakat`;

CREATE TABLE `masyarakat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) NOT NULL,
  `nisn` varchar(30) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `masyarakat_no_hp_unique` (`no_hp`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nisn` (`nisn`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `masyarakat` */

insert  into `masyarakat`(`id`,`nama`,`nisn`,`email`,`password`,`no_hp`,`is_active`,`created_at`,`updated_at`) values 
(1,'Budi Santoso','0051234567','budi.santoso@example.com','$2y$10$pN3.h0jNnD/bAsjB2d5MEet1rGN.nLpIQESpOCQtActHTrqBZfZbK','081234567890',1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(2,'Siti Rahma','0051234568','siti.rahma@example.com','$2y$10$VVbil/lD2Na5IwPzDGwBgerrSL0pOdlYmeZd2GAfXMpmY4IfCbFcy','081298765432',1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(3,'Andi Wijaya','0051234569',NULL,'$2y$10$hVoywqK5npdZEzPr89U6dOcG99X8rR7EMFUxI.zlysxCTTtEevxua','081211122233',0,'2026-09-09 07:11:16','2026-09-09 07:11:16');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values 
(14,'2026-08-30-100001','App\\Database\\Migrations\\CreateAdmin','default','App',1788937872,1),
(15,'2026-08-30-100002','App\\Database\\Migrations\\CreateWilayah','default','App',1788937872,1),
(16,'2026-08-30-100003','App\\Database\\Migrations\\CreateKategoriPengaduan','default','App',1788937872,1),
(17,'2026-08-30-100004','App\\Database\\Migrations\\CreatePic','default','App',1788937872,1),
(18,'2026-08-30-100005','App\\Database\\Migrations\\CreateMasyarakat','default','App',1788937872,1),
(19,'2026-08-30-100006','App\\Database\\Migrations\\CreateTopikPengaduan','default','App',1788937872,1),
(20,'2026-08-30-100007','App\\Database\\Migrations\\CreateSlaDefault','default','App',1788937872,1),
(21,'2026-08-30-100008','App\\Database\\Migrations\\CreatePengaduan','default','App',1788937872,1),
(22,'2026-08-30-100009','App\\Database\\Migrations\\CreatePengaduanTahapan','default','App',1788937872,1),
(23,'2026-08-30-100010','App\\Database\\Migrations\\CreatePengaduanLampiran','default','App',1788937872,1),
(24,'2026-08-30-100011','App\\Database\\Migrations\\CreatePengaduanAuditTrail','default','App',1788937872,1),
(25,'2026-09-01-090001','App\\Database\\Migrations\\UpdateMasyarakatLoginByPhone','default','App',1788937872,1),
(26,'2026-09-01-090002','App\\Database\\Migrations\\MakeEmailPelaporNullable','default','App',1788937872,1),
(27,'2026-09-08-090001','App\\Database\\Migrations\\AddNipToPic','default','App',1788938544,2),
(28,'2026-09-16-090001','App\\Database\\Migrations\\ExtendPengaduanAuditTrail','default','App',1789547457,3),
(29,'2026-09-16-090002','App\\Database\\Migrations\\AddPenangananFieldsToPengaduan','default','App',1789547457,3),
(30,'2026-09-16-090003','App\\Database\\Migrations\\CreatePengaduanTanggapan','default','App',1789547457,3),
(31,'2026-09-16-090004','App\\Database\\Migrations\\AddJenisToPengaduanLampiran','default','App',1789547457,3),
(32,'2026-09-16-090005','App\\Database\\Migrations\\DropAssignedPicFromPengaduan','default','App',1789555188,4),
(33,'2026-09-16-090006','App\\Database\\Migrations\\AlignTahapanWithDirectDisposisi','default','App',1789720941,5);

/*Table structure for table `pengaduan` */

DROP TABLE IF EXISTS `pengaduan`;

CREATE TABLE `pengaduan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nomor_tiket` varchar(30) NOT NULL,
  `topik_id` int(10) unsigned NOT NULL,
  `wilayah_id` int(10) unsigned NOT NULL,
  `masyarakat_id` int(10) unsigned DEFAULT NULL,
  `sla_hari` int(10) unsigned DEFAULT NULL,
  `tanggal_target_selesai` date DEFAULT NULL,
  `status_validasi` enum('Belum Diverifikasi','Valid','Tidak Valid','Bukan Kewenangan') DEFAULT 'Belum Diverifikasi',
  `catatan_validasi` text DEFAULT NULL,
  `lokasi_kejadian` varchar(150) DEFAULT NULL,
  `judul_pengaduan` varchar(150) NOT NULL,
  `tanggal_pengaduan` date NOT NULL,
  `tanggal_kejadian` date NOT NULL,
  `pihak_dilaporkan` varchar(150) NOT NULL,
  `kronologi` text NOT NULL,
  `nama_pelapor` varchar(150) NOT NULL,
  `email_pelapor` varchar(150) DEFAULT NULL,
  `no_hp_pelapor` varchar(20) NOT NULL,
  `rahasiakan_identitas` tinyint(1) NOT NULL DEFAULT 0,
  `kode_akses` varchar(20) NOT NULL,
  `status_akhir` enum('Didisposisikan ke PIC','Menunggu Verifikasi','Dalam Penanganan','Selesai','Ditolak') NOT NULL DEFAULT 'Didisposisikan ke PIC',
  `alasan_penolakan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_tiket` (`nomor_tiket`),
  KEY `pengaduan_topik_id_foreign` (`topik_id`),
  KEY `pengaduan_wilayah_id_foreign` (`wilayah_id`),
  KEY `pengaduan_masyarakat_id_foreign` (`masyarakat_id`),
  CONSTRAINT `pengaduan_masyarakat_id_foreign` FOREIGN KEY (`masyarakat_id`) REFERENCES `masyarakat` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `pengaduan_topik_id_foreign` FOREIGN KEY (`topik_id`) REFERENCES `topik_pengaduan` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pengaduan_wilayah_id_foreign` FOREIGN KEY (`wilayah_id`) REFERENCES `wilayah` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pengaduan` */

insert  into `pengaduan`(`id`,`nomor_tiket`,`topik_id`,`wilayah_id`,`masyarakat_id`,`sla_hari`,`tanggal_target_selesai`,`status_validasi`,`catatan_validasi`,`lokasi_kejadian`,`judul_pengaduan`,`tanggal_pengaduan`,`tanggal_kejadian`,`pihak_dilaporkan`,`kronologi`,`nama_pelapor`,`email_pelapor`,`no_hp_pelapor`,`rahasiakan_identitas`,`kode_akses`,`status_akhir`,`alasan_penolakan`,`created_at`,`updated_at`) values 
(1,'JATIM-PHK-2026-00841',1,1,1,NULL,NULL,'Belum Diverifikasi',NULL,NULL,'PHK sepihak tanpa pesangon','2026-08-03','2026-08-01','PT Maju Jaya Sentosa','Pada tanggal 1 Agustus 2026 saya menerima surat PHK tanpa pemberitahuan sebelumnya.\nPerusahaan tidak memberikan pesangon sesuai ketentuan yang berlaku.','Budi Santoso','budi.santoso@example.com','081234567890',0,'25C90516','Dalam Penanganan',NULL,'2026-08-03 09:00:00','2026-09-09 07:11:16'),
(2,'JATIM-UPH-2026-00842',2,2,2,NULL,NULL,'Belum Diverifikasi',NULL,NULL,'Upah lembur tidak dibayarkan','2026-08-02','2026-07-15','CV Sumber Rejeki','Selama 3 bulan terakhir upah lembur saya tidak dibayarkan sesuai kesepakatan awal.','Siti Rahma','siti.rahma@example.com','081298765432',0,'CE1A27F2','Didisposisikan ke PIC',NULL,'2026-08-02 09:00:00','2026-09-09 07:11:16'),
(3,'JATIM-K3-2026-00843',4,3,3,NULL,NULL,'Belum Diverifikasi',NULL,NULL,'Kondisi kerja tidak memenuhi standar K3','2026-07-30','2026-07-20','PT Industri Logam Nusantara','Tidak tersedia alat pelindung diri (APD) yang memadai di area produksi.','Andi Wijaya','andi.wijaya@example.com','081211122233',0,'8C10807A','Selesai',NULL,'2026-07-30 09:00:00','2026-09-09 07:11:16'),
(4,'JATIM-DIS-2026-00844',5,4,NULL,NULL,NULL,'Belum Diverifikasi',NULL,NULL,'Diskriminasi terhadap karyawan perempuan','2026-07-28','2026-07-10','PT Retail Sejahtera','Karyawan perempuan tidak diberikan kesempatan promosi yang setara.','Rina Melati','rina.melati@example.com','081355566677',0,'36251E33','Ditolak',NULL,'2026-07-28 09:00:00','2026-09-09 07:11:16'),
(5,'JATIM-PHK-2026-70640',5,2,2,10,'2026-09-26','Bukan Kewenangan',NULL,NULL,'saya sering didiskriminasi ','2026-09-16','2026-09-02','PT. unknown','saya sering dilambatkan saat mengurus administrasi,mungkin karena saya dari kalangan bawah sedangkan ada yang baru datang langsung didahului','Siti Rahma','siti.rahma@example.com','081298765432',0,'47EF2797','Ditolak','Bukan Kewenangan','2026-09-16 10:45:28','2026-09-16 10:47:58'),
(6,'JATIM-PHK-2026-74058',5,2,2,6,'2026-09-24','Valid',NULL,NULL,'saya sering didiskriminasi ','2026-09-18','2026-09-02','PT. unknown','saya didiskriminasi','Siti Rahma','siti.rahma@example.com','081298765432',0,'CCC5FA4C','Selesai',NULL,'2026-09-18 08:45:06','2026-09-20 15:22:09');

/*Table structure for table `pengaduan_audit_trail` */

DROP TABLE IF EXISTS `pengaduan_audit_trail`;

CREATE TABLE `pengaduan_audit_trail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pengaduan_id` int(10) unsigned NOT NULL,
  `admin_id` int(10) unsigned DEFAULT NULL,
  `pic_id` int(10) unsigned DEFAULT NULL,
  `aktor_tipe` enum('admin','pic','sistem') DEFAULT 'admin',
  `aktor_nama` varchar(150) DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `is_publik` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengaduan_audit_trail_pengaduan_id_foreign` (`pengaduan_id`),
  KEY `pengaduan_audit_trail_admin_id_foreign` (`admin_id`),
  KEY `fk_audit_pic` (`pic_id`),
  CONSTRAINT `fk_audit_pic` FOREIGN KEY (`pic_id`) REFERENCES `pic` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `pengaduan_audit_trail_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `pengaduan_audit_trail_pengaduan_id_foreign` FOREIGN KEY (`pengaduan_id`) REFERENCES `pengaduan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pengaduan_audit_trail` */

insert  into `pengaduan_audit_trail`(`id`,`pengaduan_id`,`admin_id`,`pic_id`,`aktor_tipe`,`aktor_nama`,`aktivitas`,`is_publik`,`created_at`) values 
(1,5,NULL,NULL,'sistem','Sistem','Pengaduan diterima dan diteruskan ke PIC penanggung jawab topik.',1,NULL),
(2,5,NULL,2,'pic','Dewi Anggraini','Hasil verifikasi validitas: Valid',1,NULL),
(3,5,NULL,2,'pic','Dewi Anggraini','Hasil verifikasi validitas: Valid',1,NULL),
(4,5,NULL,2,'pic','Dewi Anggraini','Hasil verifikasi validitas: Tidak Valid',1,NULL),
(5,5,NULL,2,'pic','Dewi Anggraini','Hasil verifikasi validitas: Bukan Kewenangan',1,NULL),
(6,5,NULL,2,'pic','Dewi Anggraini','Menetapkan SLA penanganan 10 hari kerja (target selesai: 26 September 2026).',1,NULL),
(7,6,NULL,2,'sistem','Sistem','Pengaduan diterima dan langsung didisposisikan ke PIC Dewi Anggraini.',1,NULL),
(8,6,NULL,2,'pic','Dewi Anggraini','Hasil verifikasi validitas: Valid',1,NULL),
(9,6,NULL,2,'pic','Dewi Anggraini','Menetapkan SLA penanganan 6 hari kerja (target selesai: 24 September 2026).',1,NULL),
(10,6,NULL,2,'pic','Dewi Anggraini','Mengirim balasan kepada pelapor.',1,NULL),
(11,6,NULL,2,'pic','Dewi Anggraini','Menambahkan catatan internal.',0,NULL),
(12,6,NULL,2,'pic','Dewi Anggraini','Mengunggah 1 dokumen bukti penyelesaian dan menutup tiket.',1,NULL);

/*Table structure for table `pengaduan_lampiran` */

DROP TABLE IF EXISTS `pengaduan_lampiran`;

CREATE TABLE `pengaduan_lampiran` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pengaduan_id` int(10) unsigned NOT NULL,
  `jenis` enum('pelapor','bukti_penyelesaian') DEFAULT 'pelapor',
  `uploaded_by_pic_id` int(10) unsigned DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `path_file` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `ukuran_kb` int(10) unsigned DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengaduan_lampiran_pengaduan_id_foreign` (`pengaduan_id`),
  KEY `fk_lampiran_pic` (`uploaded_by_pic_id`),
  CONSTRAINT `fk_lampiran_pic` FOREIGN KEY (`uploaded_by_pic_id`) REFERENCES `pic` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `pengaduan_lampiran_pengaduan_id_foreign` FOREIGN KEY (`pengaduan_id`) REFERENCES `pengaduan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pengaduan_lampiran` */

insert  into `pengaduan_lampiran`(`id`,`pengaduan_id`,`jenis`,`uploaded_by_pic_id`,`nama_file`,`path_file`,`mime_type`,`ukuran_kb`,`keterangan`,`created_at`) values 
(1,5,'pelapor',NULL,'7.jpg','writable/uploads/pengaduan/1789555528_1dae32891fa03eec1bfb.jpg','image/jpeg',112,NULL,NULL),
(2,6,'pelapor',NULL,'7.jpg','writable/uploads/pengaduan/1789721107_530accc68689fc95d1ca.jpg','image/jpeg',112,NULL,NULL),
(3,6,'bukti_penyelesaian',2,'7.jpg','writable/uploads/penyelesaian/1789917729_51571f365f3b17580fa6.jpg','image/jpeg',112,NULL,NULL);

/*Table structure for table `pengaduan_tahapan` */

DROP TABLE IF EXISTS `pengaduan_tahapan`;

CREATE TABLE `pengaduan_tahapan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pengaduan_id` int(10) unsigned NOT NULL,
  `tahap` enum('Pengaduan Diterima','Disposisi ke PIC','Tindak Lanjut','Selesai') NOT NULL,
  `urutan` tinyint(3) unsigned NOT NULL,
  `status` enum('Menunggu','Dalam Proses','Selesai') NOT NULL DEFAULT 'Menunggu',
  `sla_hari` int(10) unsigned DEFAULT NULL,
  `tanggal_mulai` datetime DEFAULT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengaduan_tahapan_pengaduan_id_foreign` (`pengaduan_id`),
  CONSTRAINT `pengaduan_tahapan_pengaduan_id_foreign` FOREIGN KEY (`pengaduan_id`) REFERENCES `pengaduan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pengaduan_tahapan` */

insert  into `pengaduan_tahapan`(`id`,`pengaduan_id`,`tahap`,`urutan`,`status`,`sla_hari`,`tanggal_mulai`,`tanggal_selesai`,`keterangan`) values 
(21,1,'Pengaduan Diterima',1,'Selesai',NULL,'2026-08-03 09:00:00','2026-08-03 09:00:00',NULL),
(22,1,'Disposisi ke PIC',2,'Selesai',NULL,'2026-09-09 07:11:16','2026-09-09 07:11:16',NULL),
(23,1,'Tindak Lanjut',3,'Dalam Proses',NULL,'2026-09-09 07:11:16',NULL,NULL),
(24,1,'Selesai',4,'Menunggu',NULL,NULL,NULL,NULL),
(25,2,'Pengaduan Diterima',1,'Selesai',NULL,'2026-08-02 09:00:00','2026-08-02 09:00:00',NULL),
(26,2,'Disposisi ke PIC',2,'Dalam Proses',NULL,'2026-09-09 07:11:16',NULL,NULL),
(27,2,'Tindak Lanjut',3,'Menunggu',NULL,NULL,NULL,NULL),
(28,2,'Selesai',4,'Menunggu',NULL,NULL,NULL,NULL),
(29,3,'Pengaduan Diterima',1,'Selesai',NULL,'2026-07-30 09:00:00','2026-07-30 09:00:00',NULL),
(30,3,'Disposisi ke PIC',2,'Selesai',NULL,'2026-09-09 07:11:16','2026-09-09 07:11:16',NULL),
(31,3,'Tindak Lanjut',3,'Selesai',NULL,'2026-09-09 07:11:16','2026-09-09 07:11:16',NULL),
(32,3,'Selesai',4,'Selesai',NULL,'2026-09-09 07:11:16','2026-09-09 07:11:16',NULL),
(33,4,'Pengaduan Diterima',1,'Selesai',NULL,'2026-07-28 09:00:00','2026-07-28 09:00:00',NULL),
(34,4,'Disposisi ke PIC',2,'Dalam Proses',NULL,'2026-09-09 07:11:16',NULL,NULL),
(35,4,'Tindak Lanjut',3,'Menunggu',NULL,NULL,NULL,NULL),
(36,4,'Selesai',4,'Menunggu',NULL,NULL,NULL,NULL),
(37,5,'Pengaduan Diterima',1,'Selesai',NULL,'2026-09-16 10:45:28','2026-09-16 10:45:28',NULL),
(38,5,'Disposisi ke PIC',2,'Dalam Proses',NULL,'2026-09-16 10:47:58',NULL,NULL),
(39,5,'Tindak Lanjut',3,'Menunggu',NULL,NULL,NULL,NULL),
(40,5,'Selesai',4,'Menunggu',NULL,NULL,NULL,NULL),
(41,6,'Pengaduan Diterima',1,'Selesai',0,'2026-09-18 08:45:06','2026-09-18 08:45:06',NULL),
(42,6,'Disposisi ke PIC',2,'Selesai',2,'2026-09-18 08:45:06','2026-09-18 08:45:52','Diteruskan otomatis ke PIC penanggung jawab topik.'),
(43,6,'Tindak Lanjut',3,'Selesai',5,'2026-09-18 08:45:52','2026-09-20 15:22:09',NULL),
(44,6,'Selesai',4,'Selesai',1,'2026-09-20 15:22:09','2026-09-20 15:22:09',NULL);

/*Table structure for table `pengaduan_tanggapan` */

DROP TABLE IF EXISTS `pengaduan_tanggapan`;

CREATE TABLE `pengaduan_tanggapan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pengaduan_id` int(10) unsigned NOT NULL,
  `pic_id` int(10) unsigned DEFAULT NULL,
  `admin_id` int(10) unsigned DEFAULT NULL,
  `pengirim_tipe` enum('pic','admin') NOT NULL DEFAULT 'pic',
  `pengirim_nama` varchar(150) DEFAULT NULL,
  `isi` text NOT NULL,
  `is_publik` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengaduan_tanggapan_pengaduan_id_foreign` (`pengaduan_id`),
  KEY `pengaduan_tanggapan_pic_id_foreign` (`pic_id`),
  KEY `pengaduan_tanggapan_admin_id_foreign` (`admin_id`),
  CONSTRAINT `pengaduan_tanggapan_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `pengaduan_tanggapan_pengaduan_id_foreign` FOREIGN KEY (`pengaduan_id`) REFERENCES `pengaduan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pengaduan_tanggapan_pic_id_foreign` FOREIGN KEY (`pic_id`) REFERENCES `pic` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pengaduan_tanggapan` */

insert  into `pengaduan_tanggapan`(`id`,`pengaduan_id`,`pic_id`,`admin_id`,`pengirim_tipe`,`pengirim_nama`,`isi`,`is_publik`,`created_at`) values 
(1,6,2,NULL,'pic','Dewi Anggraini','pengaduan anda akan kami segera kami tindak lanjuti',1,NULL),
(2,6,2,NULL,'pic','Dewi Anggraini','tes kepada internal',0,NULL);

/*Table structure for table `pic` */

DROP TABLE IF EXISTS `pic`;

CREATE TABLE `pic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_pic` varchar(150) NOT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `jabatan` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `pic_nip_unique` (`nip`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pic` */

insert  into `pic`(`id`,`nama_pic`,`nip`,`jabatan`,`email`,`password`,`no_hp`,`is_active`,`created_at`,`updated_at`) values 
(1,'Hendra Kurniawan','198501012010011001','Bidang Pengawasan Ketenagakerjaan','hendra.k@disnaker.jatimprov.go.id','$2y$10$2o8wO9hnbCGgYkavOq/cOeY/7zs43D4gKNqTADzanmgu5scROgSmu','081511122233',1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(2,'Dewi Anggraini','198702022011012002','Bidang Hubungan Industrial','dewi.a@disnaker.jatimprov.go.id','$2y$10$QWi0T66d/tzZrH6Sb2j7A.pl8CsBfCXWSqEJ/MmESieOMldZHUfGe','081522233344',1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(3,'Fajar Nugroho','199003032012011003','Bidang Penempatan Tenaga Kerja','fajar.n@disnaker.jatimprov.go.id','$2y$10$lQE9R2l.98wmji/GOcfk.e4Ii/R.DUajNe9cED1dQKD6Q5YaLSfpO','081533344455',1,'2026-09-09 07:11:16','2026-09-09 07:11:16');

/*Table structure for table `sla_default` */

DROP TABLE IF EXISTS `sla_default`;

CREATE TABLE `sla_default` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topik_id` int(10) unsigned NOT NULL,
  `tahap` enum('Pengaduan Diterima','Verifikasi Admin','Didisposisikan ke PIC','Tindak Lanjut','Selesai') NOT NULL,
  `sla_hari` int(10) unsigned NOT NULL,
  `urutan` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sla_default_topik_id_foreign` (`topik_id`),
  CONSTRAINT `sla_default_topik_id_foreign` FOREIGN KEY (`topik_id`) REFERENCES `topik_pengaduan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `sla_default` */

insert  into `sla_default`(`id`,`topik_id`,`tahap`,`sla_hari`,`urutan`) values 
(1,1,'Pengaduan Diterima',0,1),
(2,1,'',2,2),
(3,1,'Tindak Lanjut',5,3),
(4,1,'Selesai',1,4),
(5,2,'Pengaduan Diterima',0,1),
(6,2,'',2,2),
(7,2,'Tindak Lanjut',5,3),
(8,2,'Selesai',1,4),
(9,3,'Pengaduan Diterima',0,1),
(10,3,'',2,2),
(11,3,'Tindak Lanjut',5,3),
(12,3,'Selesai',1,4),
(13,4,'Pengaduan Diterima',0,1),
(14,4,'',2,2),
(15,4,'Tindak Lanjut',5,3),
(16,4,'Selesai',1,4),
(17,5,'Pengaduan Diterima',0,1),
(18,5,'',2,2),
(19,5,'Tindak Lanjut',5,3),
(20,5,'Selesai',1,4);

/*Table structure for table `topik_pengaduan` */

DROP TABLE IF EXISTS `topik_pengaduan`;

CREATE TABLE `topik_pengaduan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kategori_id` int(10) unsigned NOT NULL,
  `pic_id` int(10) unsigned DEFAULT NULL,
  `nama_topik` varchar(150) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `topik_pengaduan_kategori_id_foreign` (`kategori_id`),
  KEY `topik_pengaduan_pic_id_foreign` (`pic_id`),
  CONSTRAINT `topik_pengaduan_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_pengaduan` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `topik_pengaduan_pic_id_foreign` FOREIGN KEY (`pic_id`) REFERENCES `pic` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `topik_pengaduan` */

insert  into `topik_pengaduan`(`id`,`kategori_id`,`pic_id`,`nama_topik`,`deskripsi`,`is_active`,`created_at`,`updated_at`) values 
(1,1,2,'Pemutusan Hubungan Kerja (PHK)',NULL,1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(2,1,1,'Upah Tidak Dibayar / Terlambat',NULL,1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(3,1,1,'Jam Kerja & Lembur',NULL,1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(4,1,1,'Keselamatan & Kesehatan Kerja (K3)',NULL,1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(5,1,2,'Diskriminasi di Tempat Kerja',NULL,1,'2026-09-09 07:11:16','2026-09-09 07:11:16');

/*Table structure for table `wilayah` */

DROP TABLE IF EXISTS `wilayah`;

CREATE TABLE `wilayah` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_wilayah` varchar(150) NOT NULL,
  `kode_wilayah` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `wilayah` */

insert  into `wilayah`(`id`,`nama_wilayah`,`kode_wilayah`,`is_active`,`created_at`,`updated_at`) values 
(1,'Kota Surabaya','JATIM-SBY',1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(2,'Kabupaten Sidoarjo','JATIM-SDA',1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(3,'Kabupaten Gresik','JATIM-GRS',1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(4,'Kota Malang','JATIM-MLG',1,'2026-09-09 07:11:16','2026-09-09 07:11:16'),
(5,'Kabupaten Mojokerto','JATIM-MJK',1,'2026-09-09 07:11:16','2026-09-09 07:11:16');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
