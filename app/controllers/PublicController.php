<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

class PublicController {
    private function upload_document($file, $prefix) {
        if (isset($file) && $file['error'] == 0) {
            $target_dir = __DIR__ . "/../../uploads/ppdb/";
            if (!is_dir($target_dir)) @mkdir($target_dir, 0777, true);

            $file_extension = pathinfo($file["name"], PATHINFO_EXTENSION);
            $safe_filename = preg_replace('/[^A-Za-z0-9\-_\.]/', '', basename($file["name"]));
            $target_file = $target_dir . $prefix . '_' . time() . '_' . $safe_filename;
            $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];
            $max_size = 2 * 1024 * 1024; // 2MB

            if (!in_array(strtolower($file_extension), $allowed_types)) {
                return ['error' => "Hanya file JPG, PNG, & PDF yang diizinkan untuk " . str_replace('_', ' ', $prefix) . "."];
            }

            if ($file['size'] > $max_size) {
                return ['error' => "Ukuran file " . str_replace('_', ' ', $prefix) . " tidak boleh lebih dari 2MB."];
            }

            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return ['url' => '/mtssolear/uploads/ppdb/' . basename($target_file)];
            } else {
                return ['error' => "Gagal mengunggah file " . str_replace('_', ' ', $prefix) . "."];
            }
        }
        return ['url' => null];
    }

    public function submit_ppdb($data, $files) {
        global $pdo;

        // Proses upload dokumen
        $uploads = [];
        $file_fields = ['pas_foto', 'scan_ijazah', 'scan_akta_lahir', 'scan_kk'];
        foreach ($file_fields as $field) {
            $upload_result = $this->upload_document($files[$field], $field);
            if (isset($upload_result['error'])) {
                $_SESSION['error_message'] = $upload_result['error'];
                header("Location: /mtssolear/ppdb.php");
                exit();
            }
            $uploads[$field . '_url'] = $upload_result['url'];
        }

        try {
            $sql = "INSERT INTO ppdb (
                        nama_lengkap, nisn, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat_lengkap, no_hp_siswa,
                        nama_ayah, pekerjaan_ayah, nama_ibu, pekerjaan_ibu, kontak_wali, no_kk,
                        asal_sekolah, alamat_sekolah_asal,
                        pas_foto_url, scan_ijazah_url, scan_akta_lahir_url, scan_kk_url
                    ) VALUES (
                        :nama_lengkap, :nisn, :nik, :tempat_lahir, :tanggal_lahir, :jenis_kelamin, :agama, :alamat_lengkap, :no_hp_siswa,
                        :nama_ayah, :pekerjaan_ayah, :nama_ibu, :pekerjaan_ibu, :kontak_wali, :no_kk,
                        :asal_sekolah, :alamat_sekolah_asal,
                        :pas_foto_url, :scan_ijazah_url, :scan_akta_lahir_url, :scan_kk_url
                    )";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nama_lengkap' => $data['nama_lengkap'],
                'nisn' => $data['nisn'],
                'nik' => $data['nik'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'agama' => $data['agama'],
                'alamat_lengkap' => $data['alamat_lengkap'],
                'no_hp_siswa' => $data['no_hp_siswa'] ?? null,
                'nama_ayah' => $data['nama_ayah'],
                'pekerjaan_ayah' => $data['pekerjaan_ayah'] ?? null,
                'nama_ibu' => $data['nama_ibu'],
                'pekerjaan_ibu' => $data['pekerjaan_ibu'] ?? null,
                'kontak_wali' => $data['kontak_wali'],
                'no_kk' => $data['no_kk'] ?? null,
                'asal_sekolah' => $data['asal_sekolah'],
                'alamat_sekolah_asal' => $data['alamat_sekolah_asal'] ?? null,
                'pas_foto_url' => $uploads['pas_foto_url'],
                'scan_ijazah_url' => $uploads['scan_ijazah_url'],
                'scan_akta_lahir_url' => $uploads['scan_akta_lahir_url'],
                'scan_kk_url' => $uploads['scan_kk_url']
            ]);
            $_SESSION['success_message'] = "Pendaftaran berhasil dikirim. Terima kasih!";
        } catch (PDOException $e) {
            // Cek jika error karena duplikat NISN
            if ($e->errorInfo[1] == 1062) {
                $_SESSION['error_message'] = "Gagal: NISN yang Anda masukkan sudah terdaftar.";
            } else {
                $_SESSION['error_message'] = "Terjadi kesalahan pada database. Silakan coba lagi.";
            }
        }
        header("Location: /mtssolear/ppdb.php");
        exit();
    }

    public function submit_laporan($data, $files) {
        global $pdo;
        try {
            $nama_pelapor = htmlspecialchars($data['nama_pelapor'] ?? '');
            $jenis_kasus = htmlspecialchars($data['jenis_kasus']);
            $tanggal_kejadian = htmlspecialchars($data['tanggal_kejadian'] ?? '');
            $lokasi_kejadian = htmlspecialchars($data['lokasi_kejadian'] ?? '');
            $nama_korban = htmlspecialchars($data['nama_korban'] ?? '');
            $nama_pelaku = htmlspecialchars($data['nama_pelaku'] ?? '');
            $kontak_pelapor = htmlspecialchars($data['kontak_pelapor'] ?? '');
            $deskripsi = htmlspecialchars($data['deskripsi']);

            // Handle file uploads
            $bukti_paths = [];
            if (!empty($files['bukti']['name'][0])) {
                $upload_dir = __DIR__ . '/../../uploads/bukti/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                foreach ($files['bukti']['tmp_name'] as $key => $tmp_name) {
                    $file_name = basename($files['bukti']['name'][$key]);
                    $file_path = $upload_dir . time() . '_' . $file_name;
                    $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                    if (in_array($file_type, ['jpg', 'jpeg', 'png', 'mp4', 'avi']) && $files['bukti']['size'][$key] < 10 * 1024 * 1024) { // Max 10MB
                        if (move_uploaded_file($tmp_name, $file_path)) {
                            $bukti_paths[] = str_replace(__DIR__ . '/../../', '', $file_path); // Relative path
                        }
                    }
                }
            }
            $bukti_json = json_encode($bukti_paths);

            $stmt = $pdo->prepare("INSERT INTO laporan (nama_pelapor, jenis_kasus, tanggal_kejadian, lokasi_kejadian, nama_korban, nama_pelaku, kontak_pelapor, deskripsi, bukti, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $nama_pelapor,
                $jenis_kasus,
                $tanggal_kejadian,
                $lokasi_kejadian,
                $nama_korban,
                $nama_pelaku,
                $kontak_pelapor,
                $deskripsi,
                $bukti_json
            ]);
            $_SESSION['success_message'] = "Laporan berhasil dikirim. Terima kasih atas kepedulian Anda.";
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Terjadi kesalahan pada database. Silakan coba lagi.";
        }
        header("Location: /mtssolear/lapor.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $controller = new PublicController();
    if ($_POST['action'] == 'submit_ppdb') {
        $controller->submit_ppdb($_POST, $_FILES);
    } elseif ($_POST['action'] == 'submit_laporan') {
        $controller->submit_laporan($_POST, $_FILES);
    }
} else {
    // Jika diakses langsung, alihkan ke halaman utama
    header("Location: /mtssolear/");
    exit();
}
?>
