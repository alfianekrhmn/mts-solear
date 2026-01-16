-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2025 at 03:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sekolah_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `gambar_url` varchar(255) DEFAULT NULL,
  `penulis_id` int(11) DEFAULT NULL,
  `tanggal_publikasi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `isi`, `gambar_url`, `penulis_id`, `tanggal_publikasi`) VALUES
(1, 'Peringatan Hari Kemerdekaan ke-79', 'Siswa-siswi MTs Solear mengikuti upacara bendera dengan khidmat dalam rangka memperingati HUT RI ke-79. Acara dilanjutkan dengan berbagai perlombaan antar kelas.', 'https://images.unsplash.com/photo-1566149941334-98c222472339?q=80&w=870', 1, '2025-12-05 01:58:59'),
(2, 'Kegiatan Class Meeting Semester Ganjil', 'Setelah ujian akhir semester, OSIS MTs Solear mengadakan kegiatan class meeting yang diisi dengan berbagai lomba olahraga dan seni untuk menyegarkan pikiran siswa.', 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?q=80&w=774', 1, '2025-12-05 01:58:59');

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` int(11) NOT NULL,
  `url_gambar` varchar(255) NOT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_unggah` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galeri`
--

INSERT INTO `galeri` (`id`, `url_gambar`, `judul`, `deskripsi`, `tanggal_unggah`) VALUES
(1, 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=870', 'Upacara Bendera', 'Kegiatan rutin setiap hari Senin untuk menumbuhkan jiwa nasionalisme.', '2025-12-05 01:58:59'),
(2, 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=870', 'Kegiatan Belajar Mengajar', 'Suasana belajar di dalam kelas yang interaktif dan menyenangkan.', '2025-12-05 01:58:59'),
(3, 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=870', 'Kelulusan Siswa', 'Momen bahagia para siswa di hari kelulusan.', '2025-12-05 01:58:59'),
(4, 'https://images.unsplash.com/photo-1531482615713-2c657f6b0c69?q=80&w=870', 'Diskusi Kelompok', 'Siswa aktif berdiskusi untuk memecahkan masalah.', '2025-12-05 01:58:59');

-- --------------------------------------------------------

--
-- Table structure for table `konten_web`
--

CREATE TABLE `konten_web` (
  `id` int(11) NOT NULL,
  `nama_section` varchar(50) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `isi_konten` text DEFAULT NULL,
  `gambar_url` varchar(255) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konten_web`
--

INSERT INTO `konten_web` (`id`, `nama_section`, `judul`, `isi_konten`, `gambar_url`, `last_updated`, `created_at`) VALUES
(1, 'hero', 'Selamat Datang di MTs Swasta Solear', 'Mencetak Generasi Unggul, Berakhlak Mulia, dan Berprestasi.', 'https://images.unsplash.com/photo-1580582932707-520aed93a94d?q=80&w=1932', '2025-12-05 01:58:59', '2025-12-05 01:59:13'),
(2, 'profil', 'Profil Sekolah', 'MTs Swasta Solear adalah lembaga pendidikan yang berkomitmen untuk memberikan pendidikan berkualitas dengan landasan nilai-nilai Islam. Kami berfokus pada pengembangan akademik, karakter, dan potensi setiap siswa untuk menghadapi tantangan masa depan.', NULL, '2025-12-05 01:58:59', '2025-12-05 01:59:13'),
(4, 'kontak', 'Hubungi Kami', 'Jl. Raya Pendidikan No. 123, Solear, Tangerang\nEmail: info@mtssolear.sch.id\nTelepon: (021) 555-1234', NULL, '2025-12-05 01:58:59', '2025-12-05 01:59:13');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `nama_pelapor` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_kasus`
--

CREATE TABLE `laporan_kasus` (
  `id` int(11) NOT NULL,
  `nama_pelapor` varchar(100) DEFAULT 'Anonim',
  `jenis_kasus` varchar(50) NOT NULL,
  `deskripsi` text NOT NULL,
  `tanggal_laporan` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Baru','Diproses','Selesai') DEFAULT 'Baru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ppdb`
--

CREATE TABLE `ppdb` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `agama` varchar(50) NOT NULL,
  `alamat_lengkap` text NOT NULL,
  `no_hp_siswa` varchar(20) DEFAULT NULL,
  `nama_ayah` varchar(100) NOT NULL,
  `pendidikan_ayah` varchar(50) DEFAULT NULL,
  `pekerjaan_ayah` varchar(50) DEFAULT NULL,
  `nama_ibu` varchar(100) NOT NULL,
  `pendidikan_ibu` varchar(50) DEFAULT NULL,
  `pekerjaan_ibu` varchar(50) DEFAULT NULL,
  `nama_wali` varchar(100) DEFAULT NULL,
  `kontak_wali` varchar(20) NOT NULL,
  `no_kk` varchar(20) DEFAULT NULL,
  `asal_sekolah` varchar(100) NOT NULL,
  `alamat_sekolah_asal` text DEFAULT NULL,
  `nilai_rapor` text DEFAULT NULL,
  `jalur_pendaftaran` varchar(50) DEFAULT NULL,
  `prestasi` text DEFAULT NULL,
  `riwayat_kesehatan` text DEFAULT NULL,
  `no_kip` varchar(20) DEFAULT NULL,
  `no_pkh` varchar(20) DEFAULT NULL,
  `no_kks` varchar(20) DEFAULT NULL,
  `pas_foto_url` varchar(255) DEFAULT NULL,
  `scan_ijazah_url` varchar(255) DEFAULT NULL,
  `scan_akta_lahir_url` varchar(255) DEFAULT NULL,
  `scan_kk_url` varchar(255) DEFAULT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profil`
--

CREATE TABLE `profil` (
  `id` int(11) NOT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil`
--

INSERT INTO `profil` (`id`, `content`) VALUES
(1, 'Default school profile content.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$Y5v33o5BiorqN7oG1bVpL.j2jL4U.V0x3O.C.jB4.Iu0bL8A3Q9uU', '2025-12-05 01:58:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penulis_id` (`penulis_id`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `konten_web`
--
ALTER TABLE `konten_web`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_section` (`nama_section`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan_kasus`
--
ALTER TABLE `laporan_kasus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppdb`
--
ALTER TABLE `ppdb`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nisn` (`nisn`);

--
-- Indexes for table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `konten_web`
--
ALTER TABLE `konten_web`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_kasus`
--
ALTER TABLE `laporan_kasus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppdb`
--
ALTER TABLE `ppdb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `berita_ibfk_1` FOREIGN KEY (`penulis_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Create 'profil' table if it doesn't exist (for admin/pages/profil.php)
CREATE TABLE IF NOT EXISTS profil (
    id INT PRIMARY KEY AUTO_INCREMENT,
    content TEXT
);

-- Insert default content if table is empty
INSERT INTO profil (id, content) VALUES (1, 'Default school profile content.') ON DUPLICATE KEY UPDATE content = content;

-- Create 'laporan' table if it doesn't exist (for admin/pages/reports.php)
CREATE TABLE IF NOT EXISTS laporan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_pelapor VARCHAR(255),
    email VARCHAR(255),
    deskripsi TEXT,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add 'created_at' column to 'konten_web' if it doesn't exist (for kontak.php and ekstrakurikuler.php)
ALTER TABLE konten_web ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
