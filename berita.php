<?php
require_once __DIR__ . '/config/database.php';

// Fetch all news
$berita = [];
if ($pdo) {
    $stmt = $pdo->query("SELECT * FROM berita ORDER BY tanggal DESC");
    $berita = $stmt->fetchAll();
}

$page_title = 'Berita - MTs Solear';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container mx-auto px-6 py-20">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Berita Sekolah</h1>
        <p class="max-w-3xl mx-auto text-lg text-gray-600 leading-relaxed">
            Berita terkini dari MTs Solear.
        </p>
    </div>

    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($berita as $item): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <?php if ($item['thumbnail_url']): ?>
                    <img src="<?php echo htmlspecialchars($item['thumbnail_url']); ?>" alt="Thumbnail Berita" class="w-full h-48 object-cover">
                <?php endif; ?>
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($item['judul']); ?></h2>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(substr($item['isi'], 0, 150)) . (strlen($item['isi']) > 150 ? '...' : ''); ?></p>
                    <a href="berita-detail.php?id=<?php echo $item['id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Detail</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
