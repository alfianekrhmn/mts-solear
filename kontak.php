<?php
require_once __DIR__ . '/config/database.php';

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    if (empty($nama) || empty($email) || empty($pesan)) {
        $message = 'Nama, Email, dan Pesan wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Email tidak valid.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO kontak_pesan (nama, email, url, pesan) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nama, $email, $url, $pesan])) {
            $message = 'Pesan berhasil dikirim!';
        } else {
            $message = 'Terjadi kesalahan, coba lagi.';
        }
    }
}

$page_title = 'Kontak Kami - MTs Solear';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container mx-auto px-6 py-20">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Kontak Kami</h1>
        <p class="max-w-3xl mx-auto text-lg text-gray-600 leading-relaxed">
            Hubungi kami untuk informasi lebih lanjut tentang MTs Solear.
        </p>
    </div>

    <div class="max-w-2xl mx-auto mt-12">
        <?php if ($message): ?>
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-6">
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                <input type="text" id="nama" name="nama" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                <input type="email" id="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                <input type="url" id="url" name="url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="pesan" class="block text-sm font-medium text-gray-700">Pesan *</label>
                <textarea id="pesan" name="pesan" rows="5" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Kirim Pesan</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
