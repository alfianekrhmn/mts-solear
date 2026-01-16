<?php
session_start();
require_once __DIR__ . '/config/database.php';

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $nisn = trim($_POST['nisn'] ?? '');
    $nik = trim($_POST['nik'] ?? '');
    $tempat_lahir = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
    $jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
    $agama = trim($_POST['agama'] ?? '');
    $no_hp_siswa = trim($_POST['no_hp_siswa'] ?? '');
    $alamat_lengkap = trim($_POST['alamat_lengkap'] ?? '');
    $nama_ayah = trim($_POST['nama_ayah'] ?? '');
    $nama_ibu = trim($_POST['nama_ibu'] ?? '');
    $pekerjaan_ayah = trim($_POST['pekerjaan_ayah'] ?? '');
    $pekerjaan_ibu = trim($_POST['pekerjaan_ibu'] ?? '');
    $kontak_wali = trim($_POST['kontak_wali'] ?? '');
    $no_kk = trim($_POST['no_kk'] ?? '');
    $asal_sekolah = trim($_POST['asal_sekolah'] ?? '');
    $alamat_sekolah_asal = trim($_POST['alamat_sekolah_asal'] ?? '');

    // Handle file uploads
    $pas_foto = '';
    $scan_ijazah = '';
    $scan_akta_lahir = '';
    $scan_kk = '';
    $target_dir = __DIR__ . "/uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $files = ['pas_foto', 'scan_ijazah', 'scan_akta_lahir', 'scan_kk'];
    foreach ($files as $file) {
        if (!empty($_FILES[$file]['name'])) {
            $target_file = $target_dir . basename($_FILES[$file]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif', 'pdf'])) {
                if (move_uploaded_file($_FILES[$file]["tmp_name"], $target_file)) {
                    ${$file} = $target_file;
                } else {
                    $message = 'Gagal upload ' . $file . '.';
                }
            } else {
                $message = 'Format file ' . $file . ' tidak valid.';
            }
        }
    }

    if (empty($nama_lengkap) || empty($nisn) || empty($nik)) {
        $message = 'Nama lengkap, NISN, dan NIK wajib diisi.';
    } elseif (empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO ppdb (nama_lengkap, nisn, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, no_hp_siswa, alamat_lengkap, nama_ayah, nama_ibu, pekerjaan_ayah, pekerjaan_ibu, kontak_wali, no_kk, asal_sekolah, alamat_sekolah_asal, pas_foto, scan_ijazah, scan_akta_lahir, scan_kk) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nama_lengkap, $nisn, $nik, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $agama, $no_hp_siswa, $alamat_lengkap, $nama_ayah, $nama_ibu, $pekerjaan_ayah, $pekerjaan_ibu, $kontak_wali, $no_kk, $asal_sekolah, $alamat_sekolah_asal, $pas_foto, $scan_ijazah, $scan_akta_lahir, $scan_kk])) {
            $message = 'Pendaftaran berhasil dikirim!';
        } else {
            $message = 'Terjadi kesalahan pada database. Silakan coba lagi.';
        }
    }
}

$page_title = 'Pendaftaran PPDB - MTs Solear';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container mx-auto px-6 py-12">
    <div class="w-full max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-center mb-6">Formulir Pendaftaran PPDB</h1>

        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>

        <?php if ($message): ?>
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="/mtssolear/app/controllers/PublicController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="submit_ppdb">

            <!-- Data Pribadi Siswa -->
            <fieldset class="border p-4 rounded-md mb-6">
                <legend class="text-xl font-semibold px-2">Data Pribadi Siswa</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_lengkap">Nama Lengkap</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nama_lengkap" name="nama_lengkap" type="text" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nisn">NISN</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nisn" name="nisn" type="text" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nik">NIK (16 digit)</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nik" name="nik" type="text" pattern="\d{16}" title="NIK harus 16 digit angka" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tempat_lahir">Tempat Lahir</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="tempat_lahir" name="tempat_lahir" type="text" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tanggal_lahir">Tanggal Lahir</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="tanggal_lahir" name="tanggal_lahir" type="date" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="agama">Agama</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="agama" name="agama" type="text" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="no_hp_siswa">Nomor Telepon/HP Siswa</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="no_hp_siswa" name="no_hp_siswa" type="text">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="alamat_lengkap">Alamat Lengkap</label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="alamat_lengkap" name="alamat_lengkap" rows="3" required></textarea>
                </div>
            </fieldset>

            <!-- Data Orang Tua/Wali -->
            <fieldset class="border p-4 rounded-md mb-6">
                <legend class="text-xl font-semibold px-2">Data Orang Tua/Wali</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_ayah">Nama Ayah</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nama_ayah" name="nama_ayah" type="text" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_ibu">Nama Ibu</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nama_ibu" name="nama_ibu" type="text" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="pekerjaan_ayah">Pekerjaan Ayah</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="pekerjaan_ayah" name="pekerjaan_ayah" type="text">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="pekerjaan_ibu">Pekerjaan Ibu</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="pekerjaan_ibu" name="pekerjaan_ibu" type="text">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="kontak_wali">Nomor Kontak (WA) Orang Tua/Wali</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="kontak_wali" name="kontak_wali" type="text" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="no_kk">Nomor Kartu Keluarga (KK)</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="no_kk" name="no_kk" type="text">
                    </div>
                </div>
            </fieldset>

            <!-- Data Sekolah Asal -->
            <fieldset class="border p-4 rounded-md mb-6">
                <legend class="text-xl font-semibold px-2">Data Sekolah Asal</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="asal_sekolah">Nama Sekolah Asal (SD/MI)</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="asal_sekolah" name="asal_sekolah" type="text" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="alamat_sekolah_asal">Alamat Sekolah Asal</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="alamat_sekolah_asal" name="alamat_sekolah_asal" type="text">
                    </div>
                </div>
            </fieldset>

            <!-- Unggah Dokumen -->
            <fieldset class="border p-4 rounded-md mb-6">
                <legend class="text-xl font-semibold px-2">Unggah Dokumen</legend>
                <p class="text-sm text-gray-600 mb-4">Format file: JPG, PNG, PDF. Ukuran maks: 2MB.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="pas_foto">Pas Foto</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="pas_foto" name="pas_foto" type="file" accept="image/*,application/pdf">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="scan_ijazah">Scan Ijazah SD/MI</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="scan_ijazah" name="scan_ijazah" type="file" accept="image/*,application/pdf">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="scan_akta_lahir">Scan Akta Lahir</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="scan_akta_lahir" name="scan_akta_lahir" type="file" accept="image/*,application/pdf">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="scan_kk">Scan Kartu Keluarga</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="scan_kk" name="scan_kk" type="file" accept="image/*,application/pdf">
                    </div>
                </div>
            </fieldset>

            <div class="flex items-center justify-center mt-6">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline" type="submit">
                    Kirim Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
