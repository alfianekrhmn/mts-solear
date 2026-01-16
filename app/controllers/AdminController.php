<?php
// Set cookie agar hanya berlaku selama sesi browser
session_set_cookie_params(0);
session_start();

// Logika untuk logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: /mtssolear/admin/login.php");
    exit();
}

// Logika untuk menangani login admin
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kredensial admin yang benar
    $admin_username = 'admin';
    $admin_password = 'admin123';

    if ($username === $admin_username && $password === $admin_password) {
        // Jika kredensial benar, buat session dan alihkan ke dasbor
        $_SESSION['admin_id'] = 1; // Contoh ID admin
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_logged_in'] = true;
        header("Location: /mtssolear/admin/dashboard/");
        exit();
    } else {
        // Jika kredensial salah, set pesan error dan alihkan kembali ke login
        $_SESSION['error'] = 'Username atau password salah.';
        header("Location: /mtssolear/admin/login.php");
        exit();
    }
}

// Tambahkan logika untuk aksi admin lainnya
if (isset($_POST['action'])) {
    if (!isset($_SESSION['admin_logged_in'])) {
        header('Location: /mtssolear/admin/login.php');
        exit;
    }
    require_once __DIR__ . '/../../config/database.php';
    switch ($_POST['action']) {
        case 'delete_news':
            if (isset($_POST['news_id'])) {
                $stmt = $pdo->prepare("DELETE FROM berita WHERE id = ?");
                $stmt->execute([$_POST['news_id']]);
                $_SESSION['success_message'] = "Berita berhasil dihapus.";
            }
            header("Location: /mtssolear/admin/pages/news.php");
            exit();
        case 'add_gallery':
        case 'update_gallery':
            $is_edit = $_POST['action'] === 'update_gallery';
            $gallery_id = $is_edit ? $_POST['gallery_id'] : null;
            $judul = $_POST['judul'];
            $deskripsi = $_POST['deskripsi'] ?? '';

            // Upload gambar jika ada
            $url_gambar = $is_edit ? null : '';
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
                $target_dir = __DIR__ . "/../../uploads/gallery/";
                if (!is_dir($target_dir)) @mkdir($target_dir, 0777, true);
                $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
                $safe_filename = preg_replace('/[^A-Za-z0-9\-_\.]/', '', basename($_FILES['gambar']['name']));
                $target_file = $target_dir . time() . '_' . $safe_filename;
                $allowed_types = ['jpg', 'jpeg', 'png'];
                if (in_array(strtolower($file_extension), $allowed_types) && $_FILES['gambar']['size'] <= 2 * 1024 * 1024) {
                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                        $url_gambar = '/mtssolear/uploads/gallery/' . basename($target_file);
                    }
                }
            }

            if ($is_edit) {
                $stmt = $pdo->prepare("UPDATE galeri SET judul = ?, deskripsi = ?" . ($url_gambar ? ", url_gambar = ?" : "") . " WHERE id = ?");
                $params = [$judul, $deskripsi];
                if ($url_gambar) $params[] = $url_gambar;
                $params[] = $gallery_id;
                $stmt->execute($params);
            } else {
                $stmt = $pdo->prepare("INSERT INTO galeri (url_gambar, judul, deskripsi) VALUES (?, ?, ?)");
                $stmt->execute([$url_gambar, $judul, $deskripsi]);
            }
            $_SESSION['success_message'] = "Foto galeri berhasil " . ($is_edit ? "diupdate" : "ditambahkan") . ".";
            header("Location: /mtssolear/admin/pages/gallery.php");
            exit();
        case 'delete_gallery':
            if (isset($_POST['gallery_id'])) {
                $stmt = $pdo->prepare("DELETE FROM galeri WHERE id = ?");
                $stmt->execute([$_POST['gallery_id']]);
                $_SESSION['success_message'] = "Foto galeri berhasil dihapus.";
            }
            header("Location: /mtssolear/admin/pages/gallery.php");
            exit();
        case 'add_news':
        case 'update_news':
            $is_edit = $_POST['action'] === 'update_news';
            $news_id = $is_edit ? $_POST['news_id'] : null;
            $judul = $_POST['judul'];
            $isi = $_POST['isi'];

            // Upload gambar jika ada
            $gambar_url = $is_edit ? null : '';
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
                $target_dir = __DIR__ . "/../../uploads/news/";
                if (!is_dir($target_dir)) @mkdir($target_dir, 0777, true);
                $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
                $safe_filename = preg_replace('/[^A-Za-z0-9\-_\.]/', '', basename($_FILES['gambar']['name']));
                $target_file = $target_dir . time() . '_' . $safe_filename;
                $allowed_types = ['jpg', 'jpeg', 'png'];
                if (in_array(strtolower($file_extension), $allowed_types) && $_FILES['gambar']['size'] <= 2 * 1024 * 1024) {
                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                        $gambar_url = '/mtssolear/uploads/news/' . basename($target_file);
                    }
                }
            }

            if ($is_edit) {
                $stmt = $pdo->prepare("UPDATE berita SET judul = ?, isi = ?" . ($gambar_url ? ", gambar_url = ?" : "") . " WHERE id = ?");
                $params = [$judul, $isi];
                if ($gambar_url) $params[] = $gambar_url;
                $params[] = $news_id;
                $stmt->execute($params);
            } else {
                $stmt = $pdo->prepare("INSERT INTO berita (judul, isi, gambar_url, penulis_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$judul, $isi, $gambar_url, 1]); // Asumsi penulis_id = 1 (admin)
            }
            $_SESSION['success_message'] = "Berita berhasil " . ($is_edit ? "diupdate" : "ditambahkan") . ".";
            header("Location: /mtssolear/admin/pages/news.php");
            exit();
        case 'add_laporan':
            $nama_pelapor = htmlspecialchars($_POST['nama_pelapor'] ?? '');
            $jenis_kasus = htmlspecialchars($_POST['jenis_kasus']);
            $tanggal_kejadian = htmlspecialchars($_POST['tanggal_kejadian'] ?? '');
            $lokasi_kejadian = htmlspecialchars($_POST['lokasi_kejadian'] ?? '');
            $nama_korban = htmlspecialchars($_POST['nama_korban'] ?? '');
            $nama_pelaku = htmlspecialchars($_POST['nama_pelaku'] ?? '');
            $kontak_pelapor = htmlspecialchars($_POST['kontak_pelapor'] ?? '');
            $deskripsi = htmlspecialchars($_POST['deskripsi']);
            $stmt = $pdo->prepare("INSERT INTO laporan (nama_pelapor, jenis_kasus, tanggal_kejadian, lokasi_kejadian, nama_korban, nama_pelaku, kontak_pelapor, deskripsi, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$nama_pelapor, $jenis_kasus, $tanggal_kejadian, $lokasi_kejadian, $nama_korban, $nama_pelaku, $kontak_pelapor, $deskripsi]);
            $_SESSION['success_message'] = 'Laporan berhasil ditambahkan.';
            header('Location: /mtssolear/admin/pages/reports.php');
            exit();
        case 'update_laporan':
            $id = $_POST['id'];
            $nama_pelapor = htmlspecialchars($_POST['nama_pelapor'] ?? '');
            $jenis_kasus = htmlspecialchars($_POST['jenis_kasus']);
            $tanggal_kejadian = htmlspecialchars($_POST['tanggal_kejadian'] ?? '');
            $lokasi_kejadian = htmlspecialchars($_POST['lokasi_kejadian'] ?? '');
            $nama_korban = htmlspecialchars($_POST['nama_korban'] ?? '');
            $nama_pelaku = htmlspecialchars($_POST['nama_pelaku'] ?? '');
            $kontak_pelapor = htmlspecialchars($_POST['kontak_pelapor'] ?? '');
            $deskripsi = htmlspecialchars($_POST['deskripsi']);
            $stmt = $pdo->prepare("UPDATE laporan SET nama_pelapor=?, jenis_kasus=?, tanggal_kejadian=?, lokasi_kejadian=?, nama_korban=?, nama_pelaku=?, kontak_pelapor=?, deskripsi=? WHERE id=?");
            $stmt->execute([$nama_pelapor, $jenis_kasus, $tanggal_kejadian, $lokasi_kejadian, $nama_korban, $nama_pelaku, $kontak_pelapor, $deskripsi, $id]);
            $_SESSION['success_message'] = 'Laporan berhasil diupdate.';
            header('Location: /mtssolear/admin/pages/reports.php');
            exit();
        case 'add_kontak':
            $judul = htmlspecialchars($_POST['judul']);
            $isi_konten = htmlspecialchars($_POST['isi_konten']);
            $nama_section = htmlspecialchars($_POST['nama_section']);
            $stmt = $pdo->prepare("INSERT INTO konten_web (nama_section, judul, isi_konten, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$nama_section, $judul, $isi_konten]);
            $_SESSION['success_message'] = 'Postingan kontak berhasil ditambahkan.';
            header('Location: /mtssolear/admin/pages/kontak.php');
            exit();
        case 'update_kontak':
            $id = $_POST['id'];
            $judul = htmlspecialchars($_POST['judul']);
            $isi_konten = htmlspecialchars($_POST['isi_konten']);
            $stmt = $pdo->prepare("UPDATE konten_web SET judul=?, isi_konten=? WHERE id=? AND nama_section='kontak'");
            $stmt->execute([$judul, $isi_konten, $id]);
            $_SESSION['success_message'] = 'Postingan kontak berhasil diupdate.';
            header('Location: /mtssolear/admin/pages/kontak.php');
            exit();
        case 'delete_kontak':
            $id = $_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM konten_web WHERE id = ? AND nama_section = 'kontak'");
            $stmt->execute([$id]);
            $_SESSION['success_message'] = 'Postingan kontak berhasil dihapus.';
            header('Location: /mtssolear/admin/pages/kontak.php');
            exit();
        case 'add_ekskul':
            $judul = htmlspecialchars($_POST['judul']);
            $isi_konten = htmlspecialchars($_POST['isi_konten']);
            $nama_section = htmlspecialchars($_POST['nama_section']);
            $stmt = $pdo->prepare("INSERT INTO konten_web (nama_section, judul, isi_konten, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$nama_section, $judul, $isi_konten]);
            $_SESSION['success_message'] = 'Postingan ekstrakurikuler berhasil ditambahkan.';
            header('Location: /mtssolear/admin/pages/ekstrakurikuler.php');
            exit();
        case 'update_ekskul':
            $id = $_POST['id'];
            $judul = htmlspecialchars($_POST['judul']);
            $isi_konten = htmlspecialchars($_POST['isi_konten']);
            $stmt = $pdo->prepare("UPDATE konten_web SET judul=?, isi_konten=? WHERE id=? AND nama_section='ekstrakurikuler'");
            $stmt->execute([$judul, $isi_konten, $id]);
            $_SESSION['success_message'] = 'Postingan ekstrakurikuler berhasil diupdate.';
            header('Location: /mtssolear/admin/pages/ekstrakurikuler.php');
            exit();
        case 'delete_ekskul':
            $id = $_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM konten_web WHERE id = ? AND nama_section = 'ekstrakurikuler'");
            $stmt->execute([$id]);
            $_SESSION['success_message'] = 'Postingan ekstrakurikuler berhasil dihapus.';
            header('Location: /mtssolear/admin/pages/ekstrakurikuler.php');
            exit();
    }
} else {
    // Jika halaman diakses langsung tanpa POST, alihkan ke login
    header("Location: /mtssolear/admin/login.php");
    exit();
}
?>
