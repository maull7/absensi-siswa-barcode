-- Update tabel absensi untuk menyimpan koordinat lokasi absensi siswa
ALTER TABLE `masuk`
    ADD COLUMN `latitude` DECIMAL(10,8) NULL DEFAULT NULL AFTER `status`,
    ADD COLUMN `longitude` DECIMAL(11,8) NULL DEFAULT NULL AFTER `latitude`;

ALTER TABLE `pulang`
    ADD COLUMN `latitude` DECIMAL(10,8) NULL DEFAULT NULL AFTER `status`,
    ADD COLUMN `longitude` DECIMAL(11,8) NULL DEFAULT NULL AFTER `latitude`;
