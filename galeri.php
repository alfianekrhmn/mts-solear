<?php
require_once __DIR__ . '/config/database.php';

// Fetch all gallery items
$galeri = [];
if ($pdo) {
    $stmt = $pdo->query("SELECT * FROM galeri ORDER BY tanggal DESC");
    $galeri = $stmt->fetchAll();
}

$page_title = 'Galeri Sekolah - MTs Solear';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container mx-auto px-6 py-20">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Galeri Sekolah</h1>
        <p class="max-w-3xl mx-auto text-lg text-gray-600 leading-relaxed">
            Kumpulan gambar kegiatan di MTs Solear.
        </p>
    </div>

    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($galeri as $item): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <?php if ($item['gambar_url']): ?>
                    <img src="<?php echo htmlspecialchars($item['gambar_url']); ?>" alt="Gambar Galeri" class="w-full h-48 object-cover">
                <?php endif; ?>
                <div class="p-6">
                    <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($item['deskripsi']); ?></h3>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
