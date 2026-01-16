<?php
session_start();
require_once __DIR__ . '/config/database.php';

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pelapor = trim($_POST['nama_pelapor'] ?? '');
    $jenis_kasus = trim($_POST['jenis_kasus'] ?? '');
    $tanggal_kejadian = trim($_POST['tanggal_kejadian'] ?? '');
    $lokasi_kejadian = trim($_POST['lokasi_kejadian'] ?? '');
    $nama_korban = trim($_POST['nama_korban'] ?? '');
    $nama_pelaku = trim($_POST['nama_pelaku'] ?? '');
    $kontak_pelapor = trim($_POST['kontak_pelapor'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    // Handle file uploads
    $bukti = [];
    $target_dir = __DIR__ . "/uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    if (!empty($_FILES['bukti']['name'][0])) {
        foreach ($_FILES['bukti']['name'] as $key => $name) {
            $target_file = $target_dir . basename($name);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif', 'mp4', 'avi'])) {
                if (move_uploaded_file($_FILES['bukti']['tmp_name'][$key], $target_file)) {
                    $bukti[] = $target_file;
                } else {
                    $message = 'Gagal upload bukti.';
                }
            } else {
                $message = 'Format file bukti tidak valid.';
            }
        }
    }
    $bukti_str = implode(',', $bukti);

    if (empty($deskripsi)) {
        $message = 'Deskripsi wajib diisi.';
    } elseif (empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO laporan (nama_pelapor, jenis_kasus, tanggal_kejadian, lokasi_kejadian, nama_korban, nama_pelaku, kontak_pelapor, deskripsi, bukti) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nama_pelapor, $jenis_kasus, $tanggal_kejadian, $lokasi_kejadian, $nama_korban, $nama_pelaku, $kontak_pelapor, $deskripsi, $bukti_str])) {
            $message = 'Laporan berhasil dikirim!';
        } else {
            $message = 'Terjadi kesalahan, coba lagi.';
        }
    }
}

$page_title = 'Lapor Kasus - MTs Solear';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container mx-auto px-6 py-12">
    <div class="w-full max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-center mb-2">Formulir Laporan Kasus Bullying</h1>
        <p class="text-center text-gray-600 mb-6">Identitas Anda akan kami jaga kerahasiaannya. Anda juga bisa melapor secara anonim.</p>

        <?php if ($message): ?>
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_pelapor">Nama Pelapor </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nama_pelapor" name="nama_pelapor" type="text" placeholder="Boleh dikosongkan untuk anonim">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="jenis_kasus">Jenis Kasus</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="jenis_kasus" name="jenis_kasus" required>
                    <option value="">Pilih Jenis Kasus</option>
                    <option value="Bullying Fisik">Bullying Fisik</option>
                    <option value="Bullying Verbal">Bullying Verbal</option>
                    <option value="Bullying Sosial">Bullying Sosial</option>
                    <option value="Cyberbullying">Cyberbullying</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="tanggal_kejadian">Tanggal Kejadian </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="tanggal_kejadian" name="tanggal_kejadian" type="date">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="lokasi_kejadian">Lokasi Kejadian </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="lokasi_kejadian" name="lokasi_kejadian" type="text" placeholder="Contoh: Kelas X-A, Lapangan Sekolah">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_korban">Nama Korban </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nama_korban" name="nama_korban" type="text" placeholder="Boleh dikosongkan untuk anonim">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_pelaku">Nama Pelaku </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="nama_pelaku" name="nama_pelaku" type="text" placeholder="Boleh dikosongkan untuk anonim">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="kontak_pelapor">Kontak Pelapor </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="kontak_pelapor" name="kontak_pelapor" type="text" placeholder="Email atau nomor telepon, boleh dikosongkan untuk anonim">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="deskripsi">Deskripsi Lengkap Kejadian</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="deskripsi" name="deskripsi" rows="5" placeholder="Jelaskan kejadian, waktu, lokasi, dan pihak yang terlibat." required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="bukti">Bukti Kejadian </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="bukti" name="bukti[]" type="file" accept="image/*,video/*" multiple>
                <p class="text-sm text-gray-500 mt-1">Upload foto atau video sebagai bukti .</p>
            </div>
            <div class="flex items-center justify-center">
                <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" type="submit">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
