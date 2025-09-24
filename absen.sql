
-- Tabel absensi menyimpan data jam masuk dan pulang dalam satu tabel
CREATE TABLE IF NOT EXISTS `absensi` (
  `nis` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`nis`, `tanggal`),
  KEY `idx_absensi_tanggal` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

