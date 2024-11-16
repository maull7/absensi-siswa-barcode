-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2024 at 03:08 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absen`
--

-- --------------------------------------------------------

--
-- Table structure for table `absen`
--

CREATE TABLE `absen` (
  `id` int(11) NOT NULL,
  `nis` int(11) NOT NULL,
  `tanggal` varchar(50) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `data_siswa`
--

CREATE TABLE `data_siswa` (
  `nis` int(6) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `tempat_l` varchar(50) NOT NULL,
  `tanggal_l` varchar(50) NOT NULL,
  `jenis_kelamin` varchar(50) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `img` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_siswa`
--

INSERT INTO `data_siswa` (`nis`, `nama`, `kelas`, `jurusan`, `tempat_l`, `tanggal_l`, `jenis_kelamin`, `alamat`, `img`) VALUES
(1234567, 'UBAID BAIHAQI', 'X PPLG 1', 'Pengembangan Perangkat Lunak', 'DEPOK', '2024-08-03', 'Laki-Laki', '.', '847-UBAID.png'),
(7654321, 'MOHAMAD FAHRI AMSYAH', 'X PPLG 1', 'Pengembangan Perangkat Lunak', 'DEPOK', '2024-08-03', 'Laki-Laki', '.', '165-FAHRI.png'),
(24251111, 'NATASYA PUSPITA SARI N', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '.', '2024-08-05', 'Perempuan', '.', '88-natasya.png'),
(24252222, 'FATHIR SYAKIEB A', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '.', '2024-08-05', 'Laki-Laki', '.', '5-syakieb.png'),
(232410108, 'Dava Nanda Febrianto', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim', '', '', '', ' ', ''),
(232410123, 'Muhammad kholid Umar', 'XI PPLG 2', 'Pengembangan Perangkat Lunak', '.', '2024-08-01', 'Laki-Laki', '.', '502-umar.png'),
(232410173, 'Abednego Mangantua H.A', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim', '', '', '', '', ''),
(232410174, 'Adhitya Bagus Pratama', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410175, 'Alvian Nabil Aulia', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410176, 'Anam Putra Adillah P', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim', '', '', '', '', ''),
(232410177, 'Arjuna Dwi Anggara', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410178, 'Arkhan Maulana', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410179, 'Baldy Chausar', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410180, 'Bilal Al Fardhan', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410181, 'Dzaky Alfiansyah', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410182, 'Fachri Maulana Putra', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410184, 'Fatimah Dwi Rizkiya', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410185, 'Favian Daffa Mahardika', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410186, 'Gilang Rasya Bintang', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410187, 'Hamka Nurhikmat', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410188, 'Handika Maulana', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410189, 'Kafka Fahri Hanafi', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410190, 'Luthfi Juniear Siddiq', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410191, 'M.Gildo Giri Awang', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim', '', '', '', '', ''),
(232410192, 'Muhamad Firdzy Fahrezi', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410193, 'Muhamad Galang Azzahran', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410194, 'Muhamad Saviq F.Y', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim', '', '', '', '', ''),
(232410195, 'Muhammad Alfiansyah', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410196, 'Muhammad Fahry', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410197, 'Muhammad Fajri', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410198, 'Muhammad Fikri', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410199, 'Muhammad Raihan Alfarizi', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410200, 'Muhammad Rifat Aditya', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410202, 'Naufal Imtiyaz', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410203, 'Novan Zunanda Putra', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410204, 'Rayfansa', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410205, 'Rehan Maulana', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410206, 'Reza Dwi Andhika', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410207, 'Rifky Wahyu Pratama', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410208, 'Rizky Maulana Julianto', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410209, 'Salma Nur Aulia M', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim', '', '', '', '', ''),
(232410210, 'Sutisna Maulana', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410211, 'Tedi Setiawan', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410212, 'Zahra Grecia Sebastian', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410213, 'Ahdan Prakoso', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410214, 'Alif Rasya Pradita', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410215, 'Aliyudha Frandito', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410216, 'Arifa Restu Hidayah', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410217, 'Atthaillah Ramadhan A', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim', '', '', '', '', ''),
(232410218, 'Azis Tegar Maulana', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410219, 'Bima Aditiya Wiryatama', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410220, 'Daffa Yazid Fadillah', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410221, 'David Raiska M.S', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim', '', '', '', '', ''),
(232410222, 'Dirly Kurniawan P.S', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim', '', '', '', '', ''),
(232410224, 'Fachry Akbar', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410225, 'Fahri Firmansah', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410226, 'Fakhri Fadil Aziz', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410227, 'Galang Saputra', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410228, 'Galih Wicaksono', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410229, 'Gilang Alfahrezi Dwi Putra', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410230, 'Haris Fadillah Syafei', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410231, 'Iyan Sulistianto', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410232, 'Kaka Septa Ramadani', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410233, 'Muhamad Ardean Kamil', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410234, 'Muhammad Alika Saputra', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410235, 'Muhammad Bayu A.F', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim', '', '', '', '', ''),
(232410236, 'Muhammad Dyo R', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim', '', '', '', '', ''),
(232410237, 'Muhammad Fadhil Arifin', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410238, 'Muhammad Fauzi Fadilah', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410239, 'Muhammad Risqi Mustofa', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410241, 'Nasya Alica Fajar Sari', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410242, 'Rafiy Ahmad Suharto', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410243, 'Raindra Ramadhan', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410244, 'Reza Dwi Saputra', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410245, 'Rifaldi Rahman', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410246, 'Riki Apriyanto', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410247, 'Sultan Zadit Ramadhan', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410248, 'Syakila Nurul Azizah', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(232410250, 'Yuna Caya Dewi', 'XI PPLG 2', 'Perkembangan Perangkat Lunak Dan Gim\r', '', '', '', '', ''),
(242510202, 'ADIT DZAKHWAN', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '155-ADIT DZAKHWAN.png'),
(242510203, 'AFDAL MAHESA KHADAVI', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '156-AFDAL MAHESA KHADAVI.png'),
(242510204, 'AHMAD KHIDIR', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '397-AHMAD KHIDIR.png'),
(242510205, 'ALFIN MUBAROK', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '978-ALFIN MUBAROK.png'),
(242510206, 'ANGGITA ZAHRA SYAHPUTRI', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '652-ANGGITA ZAHRA SYAHPUTRI.png'),
(242510207, 'ANNISA SALSABILA', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '761-ANNISA SALSABILA.png'),
(242510208, 'AURA UTSWAN ZANIA', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '636-AURA UTSWAN ZANIA.png'),
(242510209, 'BAYU FERDIANSYAH', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '375-BAYU FERDIANSYAH.png'),
(242510210, 'BUNGA ANISSA', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '666-BUNGA ANISSA.png'),
(242510211, 'DALIYAH MUMTAZAH', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '782-DALIYAH MUMTAZAH.png'),
(242510212, 'ERLANGGA ADITYA', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '56-ERLANGGA ADITYA.png'),
(242510213, 'FAHRI ISLAM MUBARAK', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '142-FAHRI ISLAM MUBARAK.png'),
(242510214, 'IDHAM FARID', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '952-IDHAM FARID.png'),
(242510215, 'ILHAM ARIS RIYADI', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '130-ILHAM ARIS RIYADI.png'),
(242510216, 'M. ALBANI NURKHOLIS', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '841-M. ALBANI NURKHOLIS.png'),
(242510217, 'MARCHELLO NOVRIZA ROHAN', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '388-MARCHELLO NOVRIZA ROHAN.png'),
(242510218, 'MARSHA SAPUTRA', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '953-MARSHA SAPUTRA.png'),
(242510219, 'MARVEL OKI SAPUTRA', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '452-MARVEL OKI SAPUTRA.png'),
(242510220, 'MUHAMAD ARIJAL', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '414-MUHAMAD ARIJAL.png'),
(242510221, 'MUHAMAD FAJRI RAMADHAN', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '408-MUHAMAD FAJRI RAMADHAN.png'),
(242510222, 'MUHAMMAD FADHIL', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '270-MUHAMMAD FADHIL.png'),
(242510223, 'MUHAMMAD IRFAN ALI', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '985-MUHAMMAD IRFAN ALI.png'),
(242510224, 'NAUFAL PUTRA PRATAMA', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '810-NAUFAL PUTRA PRATAMA.png'),
(242510225, 'PUTRI APRILIANI SOLEHA', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '279-PUTRI APRILIANI SOLEHA.png'),
(242510226, 'RAKA ALFARESHKY', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '290-RAKA ALFARESHKY.png'),
(242510227, 'RIZKY KUSUMO BAGYO', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '793-RIZKY KUSUMO BAGYO.png'),
(242510228, 'SHEZA WIBISONO', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '164-SHEZA WIBISONO.png'),
(242510229, 'SOPHIA JULYANA JOYAN', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '232-SOPHIA JULYANA JOYAN.png'),
(242510230, 'SYAFA JULIA SARI ', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '421-SYAFA JULIA SARI.png'),
(242510231, 'TIO ALVIANSYAH', 'X PPLG 1', 'Pengembangan Perangkat Lunak', '', '', '', '.', '980-TIO ALVIANSYAH.png'),
(242510232, 'AGUSTIN EDISA HANA WINANTI', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '790-AGUSTIN EDISA HANA WINANTI.png'),
(242510233, 'AKHTAR RADITAMA', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '956-AKHTAR RADITAMA.png'),
(242510234, 'ALFADILAH BAHTIAR', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '562-ALFADILAH BAHTIAR.png'),
(242510235, 'ALFARIZI', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '644-ALFARIZI.png'),
(242510236, 'ALYCIA TRIHAPSARI', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '878-ALYCIA TRIHAPSARI.png'),
(242510237, 'ANUGRAH BAGAS LUTFIYANTO', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '828-ANUGRAH BAGAS LUTFIYANTO.png'),
(242510238, 'AZ ZAHRA SYAHRANI SALSABILLAH', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '873-AZ ZAHRA SYAHRANI SALSABILLAH.png'),
(242510240, 'CAESAREA GILANG TRAVAIL', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '883-CAESAREA GILANG TRAVAIL.png'),
(242510241, 'DIKA AGUSTIN', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '786-DIKA AGUSTIN.png'),
(242510242, 'DINDA FATIMAH MAHARANI', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '819-DINDA FATIMAH MAHARANI.png'),
(242510243, 'ERLAN HAFIZH', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '383-ERLAN HAFIZH.png'),
(242510244, 'FADILL MUHAMMAD FEBRIANSYAH', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '964-FADILL MUHAMMAD FEBRIANSYAH.png'),
(242510245, 'FAHRUROZI NUR HIDAYAH', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '544-FAHRUROZI NUR HIDAYAH.png'),
(242510246, 'FAIZ KAMIL MUTHI', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '900-FAIZ KAMIL MUTHI.png'),
(242510247, 'INFAN SYAH ABI ABDILAH', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '256-INFAN SYAH ABI ABDILAH.png'),
(242510248, 'MAULANA PRADITIYA ', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '333-MAULANA PRADITIYA.png'),
(242510249, 'MUHAMAD RIZAL', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '758-MUHAMAD RIZAL.png'),
(242510250, 'MUHAMMAD ALRIZKIYA PUTRA', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '799-MUHAMMAD ALRIZKIYA PUTRA.png'),
(242510251, 'MUHAMMAD MARUF FAUZI', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '515-MUHAMMAD MARUF FAUZI.png'),
(242510252, 'NAVISYA ASHILA PUTRI', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '42-NAVISYA ASHILA PUTRI.png'),
(242510253, 'RAFFI HIBRIZI', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '866-RAFFI HIBRIZI.png'),
(242510254, 'RATU KHENZA MAHARANI', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '561-RATU KHENZA MAHARANI.png'),
(242510255, 'REIHAN AL QABIDH', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '216-REIHAN AL QABIDH.png'),
(242510256, 'RIFQY BAIHAQY SAPUTRA', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '316-RIFQY BAIHAQY SAPUTRA.png'),
(242510257, 'RIZKI SAPUTRA', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '426-RIZKI SAPUTRA.png'),
(242510258, 'TIKO YULIANTO', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '847-TIKO YULIANTO.png'),
(242510259, 'ZAHRATUN NURUL AINI', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '382-ZAHRATUN NURUL AINI.png'),
(242510316, 'RIKO FAEEZAH FARZANA', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '991-RIKO FAEEZAH FARZANA.png'),
(242510317, 'RAIHAN SYAHREZA', 'X PPLG 2', 'Pengembangan Perangkat Lunak', '', '', '', '.', '615-RAIHAN SYAHREZA.png'),
(242511001, 'RIVAL RAMADHANI', 'XI PPLG 1', 'Perkembangan Perangkat Lunak Dan Gim', '', '', '', '.', '741-rival.png');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `sebagai` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `password`, `nama`, `sebagai`) VALUES
(1, 'adhi', '123', 'adhi', 'admin'),
(9, 'rpl', 'rpl', 'RPL', 'admin'),
(0, 'user', 'user', 'user', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `login_siswa`
--

CREATE TABLE `login_siswa` (
  `id` int(11) NOT NULL,
  `nis` int(6) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `masuk`
--

CREATE TABLE `masuk` (
  `id` int(5) NOT NULL,
  `nis` int(5) NOT NULL,
  `jam_masuk` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(231) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `masuk`
--

INSERT INTO `masuk` (`id`, `nis`, `jam_masuk`, `tanggal`, `status`) VALUES
(1722, 232410212, '06.33.35', '2024-08-12', ''),
(1723, 232410220, '06.33.41', '2024-08-12', ''),
(1724, 232410231, '06.33.55', '2024-08-12', ''),
(1725, 232410224, '06.36.43', '2024-08-12', ''),
(1726, 242510259, '06.39.08', '2024-08-12', ''),
(1727, 242510252, '06.39.11', '2024-08-12', ''),
(1728, 242510233, '06.39.23', '2024-08-12', ''),
(1729, 242510249, '06.39.27', '2024-08-12', ''),
(1730, 242510247, '06.39.30', '2024-08-12', ''),
(1731, 242510245, '06.39.33', '2024-08-12', ''),
(1732, 242510203, '06.39.36', '2024-08-12', ''),
(1733, 242510211, '06.40.02', '2024-08-12', ''),
(1734, 232410191, '06.40.31', '2024-08-12', ''),
(1735, 232410217, '06.42.07', '2024-08-12', ''),
(1736, 242510242, '06.42.15', '2024-08-12', ''),
(1737, 242510229, '06.42.20', '2024-08-12', ''),
(1738, 242510206, '06.42.24', '2024-08-12', ''),
(1739, 242510221, '06.42.28', '2024-08-12', ''),
(1740, 242510210, '06.42.31', '2024-08-12', ''),
(1741, 232410195, '06.42.34', '2024-08-12', ''),
(1742, 232410177, '06.42.38', '2024-08-12', ''),
(1743, 232410206, '06.42.42', '2024-08-12', ''),
(1744, 242510225, '06.42.45', '2024-08-12', ''),
(1745, 232410180, '06.42.59', '2024-08-12', ''),
(1746, 242510223, '06.43.02', '2024-08-12', ''),
(1747, 242510212, '06.43.08', '2024-08-12', ''),
(1748, 242510202, '06.43.05', '2024-08-12', ''),
(1749, 242510219, '06.43.09', '2024-08-12', ''),
(1750, 242510317, '06.43.14', '2024-08-12', ''),
(1751, 242510254, '06.43.12', '2024-08-12', ''),
(1752, 242510238, '06.43.15', '2024-08-12', ''),
(1753, 232410174, '06.43.21', '2024-08-12', ''),
(1754, 242510235, '06.43.25', '2024-08-12', ''),
(1755, 242510234, '06.43.31', '2024-08-12', ''),
(1756, 232410214, '06.43.51', '2024-08-12', ''),
(1757, 232410235, '06.43.54', '2024-08-12', ''),
(1758, 232410232, '06.44.00', '2024-08-12', ''),
(1759, 242510246, '06.44.27', '2024-08-12', ''),
(1760, 242510244, '06.44.33', '2024-08-12', ''),
(1761, 232410184, '06.44.39', '2024-08-12', ''),
(1762, 232410209, '06.44.36', '2024-08-12', ''),
(1763, 242510316, '06.44.50', '2024-08-12', ''),
(1764, 242510255, '06.44.48', '2024-08-12', ''),
(1765, 242510207, '06.45.09', '2024-08-12', ''),
(1766, 242510208, '06.45.12', '2024-08-12', ''),
(1767, 232410202, '06.45.37', '2024-08-12', ''),
(1768, 232410225, '06.45.52', '2024-08-12', ''),
(1769, 24252222, '06.45.59', '2024-08-12', ''),
(1770, 242510253, '06.46.17', '2024-08-12', ''),
(1771, 242510236, '06:46:27', '2024-08-12', ''),
(1772, 242510241, '06.46.26', '2024-08-12', ''),
(1773, 242510218, '06.47.31', '2024-08-12', ''),
(1774, 242510215, '06.48.04', '2024-08-12', ''),
(1775, 242510256, '06.48.10', '2024-08-12', ''),
(1776, 242510227, '06.48.23', '2024-08-12', ''),
(1777, 232410237, '06.48.25', '2024-08-12', ''),
(1778, 232410178, '06.49.22', '2024-08-12', ''),
(1779, 232410215, '06.50.17', '2024-08-12', ''),
(1780, 242510258, '06.50.48', '2024-08-12', ''),
(1781, 232410194, '06.50.59', '2024-08-12', ''),
(1782, 232410221, '06.51.09', '2024-08-12', ''),
(1783, 232410246, '06.51.12', '2024-08-12', ''),
(1784, 232410200, '06.51.16', '2024-08-12', ''),
(1785, 232410227, '06.51.26', '2024-08-12', ''),
(1786, 242510232, '06.51.30', '2024-08-12', ''),
(1787, 24251111, '06.51.34', '2024-08-12', ''),
(1788, 242510240, '06.51.37', '2024-08-12', ''),
(1789, 242510209, '06.51.40', '2024-08-12', ''),
(1790, 242510216, '06.51.45', '2024-08-12', ''),
(1791, 232410213, '06.51.43', '2024-08-12', ''),
(1792, 232410228, '06.51.46', '2024-08-12', ''),
(1793, 242511001, '06.51.50', '2024-08-12', ''),
(1794, 232410196, '06.51.58', '2024-08-12', ''),
(1795, 232410198, '06.51.58', '2024-08-12', ''),
(1796, 242510237, '06.52.03', '2024-08-12', ''),
(1797, 232410245, '06.52.19', '2024-08-12', ''),
(1798, 242510243, '06.52.24', '2024-08-12', ''),
(1799, 242510228, '06.52.33', '2024-08-12', ''),
(1800, 242510231, '06.52.34', '2024-08-12', ''),
(1801, 232410187, '06.53.11', '2024-08-12', ''),
(1802, 232410211, '06.53.14', '2024-08-12', ''),
(1803, 232410199, '06.53.20', '2024-08-12', ''),
(1804, 242510250, '06.53.47', '2024-08-12', ''),
(1805, 242510257, '06.54.05', '2024-08-12', ''),
(1806, 242510204, '06.54.07', '2024-08-12', ''),
(1807, 232410207, '06.54.16', '2024-08-12', ''),
(1808, 232410189, '06.54.22', '2024-08-12', ''),
(1809, 232410192, '06.54.27', '2024-08-12', ''),
(1810, 232410188, '06.54.36', '2024-08-12', ''),
(1811, 242510214, '06.54.47', '2024-08-12', ''),
(1812, 232410242, '06.55.31', '2024-08-12', ''),
(1813, 242510222, '06.55.42', '2024-08-12', ''),
(1814, 232410182, '06.55.50', '2024-08-12', ''),
(1815, 232410230, '06.56.23', '2024-08-12', ''),
(1816, 232410248, '06.56.38', '2024-08-12', ''),
(1817, 232410250, '06.56.55', '2024-08-12', ''),
(1818, 232410181, '06.57.00', '2024-08-12', ''),
(1819, 232410210, '06.57.05', '2024-08-12', ''),
(1820, 232410247, '06.57.11', '2024-08-12', ''),
(1821, 232410243, '06.57.16', '2024-08-12', ''),
(1822, 232410205, '06.57.21', '2024-08-12', ''),
(1823, 232410219, '06.57.29', '2024-08-12', ''),
(1824, 232410226, '06.57.35', '2024-08-12', ''),
(1825, 232410193, '06.57.42', '2024-08-12', ''),
(1826, 232410173, '06.57.47', '2024-08-12', ''),
(1827, 232410186, '06.57.53', '2024-08-12', ''),
(1828, 232410190, '06.57.58', '2024-08-12', ''),
(1829, 232410176, '06.58.15', '2024-08-12', ''),
(1830, 232410203, '06.58.15', '2024-08-12', ''),
(1831, 232410185, '06.58.21', '2024-08-12', ''),
(1832, 232410175, '06.58.21', '2024-08-12', ''),
(1833, 232410238, '06.58.29', '2024-08-12', ''),
(1834, 232410229, '06.58.36', '2024-08-12', ''),
(1835, 232410197, '06.58.34', '2024-08-12', ''),
(1836, 7654321, '06.58.37', '2024-08-12', ''),
(1837, 1234567, '06.58.40', '2024-08-12', ''),
(1838, 242510220, '06.59.06', '2024-08-12', ''),
(1839, 232410233, '06.59.06', '2024-08-12', ''),
(1840, 232410239, '06.59.14', '2024-08-12', ''),
(1841, 242510251, '07:00:21', '2024-08-12', 'Telat'),
(1842, 232410216, '07.01.18', '2024-08-12', 'Telat'),
(1843, 242510230, '07.01.21', '2024-08-12', 'Telat'),
(1844, 242510248, '07.01.24', '2024-08-12', 'Telat'),
(1845, 242510226, '07.01.57', '2024-08-12', 'Telat'),
(1846, 242510213, '07.01.55', '2024-08-12', 'Telat'),
(1847, 232410208, '07.02.15', '2024-08-12', 'Telat'),
(1848, 232410123, '07.02.49', '2024-08-12', 'Telat'),
(1849, 232410179, '07.46.32', '2024-08-12', 'Telat'),
(1850, 232410234, '07.51.18', '2024-08-12', 'Telat'),
(1851, 232410108, '07.51.48', '2024-08-12', 'Telat'),
(1852, 232410241, '07.54.55', '2024-08-12', 'Telat');

-- --------------------------------------------------------

--
-- Table structure for table `pulang`
--

CREATE TABLE `pulang` (
  `id` int(5) NOT NULL,
  `nis` int(5) NOT NULL,
  `jam_pulang` varchar(50) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nis` (`nis`);

--
-- Indexes for table `data_siswa`
--
ALTER TABLE `data_siswa`
  ADD PRIMARY KEY (`nis`);

--
-- Indexes for table `login_siswa`
--
ALTER TABLE `login_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nis` (`nis`);

--
-- Indexes for table `masuk`
--
ALTER TABLE `masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nis` (`nis`);

--
-- Indexes for table `pulang`
--
ALTER TABLE `pulang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nis` (`nis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absen`
--
ALTER TABLE `absen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `data_siswa`
--
ALTER TABLE `data_siswa`
  MODIFY `nis` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- AUTO_INCREMENT for table `login_siswa`
--
ALTER TABLE `login_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `masuk`
--
ALTER TABLE `masuk`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1853;

--
-- AUTO_INCREMENT for table `pulang`
--
ALTER TABLE `pulang`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=661;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absen`
--
ALTER TABLE `absen`
  ADD CONSTRAINT `absen_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `data_siswa` (`nis`);

--
-- Constraints for table `login_siswa`
--
ALTER TABLE `login_siswa`
  ADD CONSTRAINT `login_siswa_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `data_siswa` (`nis`);

--
-- Constraints for table `masuk`
--
ALTER TABLE `masuk`
  ADD CONSTRAINT `masuk_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `data_siswa` (`nis`);

--
-- Constraints for table `pulang`
--
ALTER TABLE `pulang`
  ADD CONSTRAINT `pulang_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `data_siswa` (`nis`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
