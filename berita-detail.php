<?php
require_once __DIR__ . '/config/database.php';

$berita = null;
if (isset($_GET['id']) && $pdo) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
    $stmt->execute([$id]);
    $berita = $stmt->fetch();
}

$page_title = $berita ? htmlspecialchars($berita['judul']) : 'Berita Tidak Ditemukan';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container mx-auto px-6 py-20">
    <?php if ($berita): ?>
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($berita['judul']); ?></h1>
            <?php if ($berita['thumbnail_url']): ?>
                <img src="<?php echo htmlspecialchars($berita['thumbnail_url']); ?>" alt="Gambar Berita" class="w-full h-64 object-cover rounded-lg mb-6">
            <?php endif; ?>
            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($berita['tanggal']); ?></p>
            <div class="text-lg text-gray-700 leading-relaxed">
                <?php echo nl2br(htmlspecialchars($berita['isi'])); ?>
            </div>
            <a href="berita.php" class="mt-8 inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Kembali ke Berita</a>
        </div>
    <?php else: ?>
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Berita Tidak Ditemukan</h1>
            <a href="berita.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Kembali ke Berita</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
