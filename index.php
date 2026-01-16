<?php
require_once __DIR__ . '/config/database.php';

// Fungsi untuk mengambil konten dari database
function get_content($section_name) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM konten_web WHERE nama_section = ?");
    $stmt->execute([$section_name]);
    $content = $stmt->fetch();
    // Sediakan nilai default jika konten tidak ditemukan
    return $content ?: ['judul' => 'Judul Default', 'isi_konten' => 'Konten belum diatur.', 'gambar_url' => 'https://via.placeholder.com/1200x600'];
}

$hero_content = get_content('hero');

// Log visit
$ip = $_SERVER['REMOTE_ADDR'];
$stmt = $pdo->prepare("INSERT INTO visits (ip) VALUES (?)");
$stmt->execute([$ip]);

$page_title = 'Selamat Datang di MTs Swasta Solear';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<?php $hero_bg = !empty($hero_content['gambar_url']) ? htmlspecialchars($hero_content['gambar_url']) : '/mtssolear/uploads/mts-solear.jpg'; ?>
<section id="hero" class="relative text-white mb-5" style="background-image: url('<?php echo $hero_bg; ?>'); background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="container mx-auto px-6 py-32 relative z-10 text-center">
        <h1 class="text-4xl md:text-6xl font-bold leading-tight"><?php echo htmlspecialchars($hero_content['judul']); ?></h1>
        <p class="mt-4 text-lg md:text-xl"><?php echo htmlspecialchars($hero_content['isi_konten']); ?></p>
        <a href="/mtssolear/ppdb.php" class="mt-8 inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg">Daftar Sekarang</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
