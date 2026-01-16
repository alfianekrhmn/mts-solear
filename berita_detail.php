<?php
require_once __DIR__ . '/config/database.php';

if (!isset($_GET['id'])) {
    header("Location: berita.php");
    exit();
}

$stmt = $pdo->prepare("SELECT berita.*, users.username FROM berita LEFT JOIN users ON berita.penulis_id = users.id WHERE berita.id = ?");
$stmt->execute([$_GET['id']]);
$berita = $stmt->fetch();

if (!$berita) {
    // Jika berita tidak ditemukan, alihkan ke halaman daftar berita
    header("Location: berita.php");
    exit();
}

$page_title = htmlspecialchars($berita['judul']) . ' - MTs Solear';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container mx-auto px-6 py-12">
    <article class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($berita['judul']); ?></h1>
        <div class="text-gray-500 mb-6">
            <span>Ditulis oleh <?php echo htmlspecialchars($berita['username'] ?? 'Admin'); ?></span> |
            <span><?php echo date('d F Y', strtotime($berita['tanggal_publikasi'])); ?></span>
        </div>
        <?php if ($berita['gambar_url']): ?>
            <img src="<?php echo htmlspecialchars($berita['gambar_url']); ?>" alt="<?php echo htmlspecialchars($berita['judul']); ?>" class="w-full h-auto max-h-96 object-cover rounded-lg mb-8">
        <?php endif; ?>
        <div class="prose lg:prose-xl max-w-none text-gray-800">
            <?php echo $berita['isi']; // Menampilkan konten HTML langsung dari editor ?>
        </div>
        <div class="mt-12">
            <a href="berita.php" class="text-green-600 hover:text-green-800 font-semibold">&larr; Kembali ke Daftar Berita</a>
        </div>
    </article>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
