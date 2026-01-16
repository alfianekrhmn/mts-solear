<?php
require_once __DIR__ . '/config/database.php';

// Fetch all extracurricular
$ekstrakurikuler = [];
if ($pdo) {
    $stmt = $pdo->query("SELECT * FROM ekstrakurikuler ORDER BY tanggal DESC");
    $ekstrakurikuler = $stmt->fetchAll();
}

$page_title = 'Ekstrakurikuler - MTs Solear';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container mx-auto px-6 py-20">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Ekstrakurikuler</h1>
        <p class="max-w-3xl mx-auto text-lg text-gray-600 leading-relaxed">
            Berbagai kegiatan ekstrakurikuler di MTs Solear.
        </p>
    </div>

    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($ekstrakurikuler as $item): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <?php if ($item['dokumentasi']): ?>
                    <?php if (in_array(strtolower(pathinfo($item['dokumentasi'], PATHINFO_EXTENSION)), ['mp4', 'avi'])): ?>
                        <video width="100%" height="200" controls>
                            <source src="<?php echo htmlspecialchars($item['dokumentasi']); ?>" type="video/mp4">
                        </video>
                    <?php else: ?>
                        <img src="<?php echo htmlspecialchars($item['dokumentasi']); ?>" alt="Dokumentasi" class="w-full h-48 object-cover">
                    <?php endif; ?>
                <?php endif; ?>
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($item['nama']); ?></h2>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($item['detail']); ?></p>
                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($item['tanggal']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
