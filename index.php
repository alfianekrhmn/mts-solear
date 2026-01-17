<?php
// ==================================================
// ERROR REPORTING (MATIKAN SAAT PRODUKSI PENUH)
// ==================================================
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ==================================================
// BASE URL DINAMIS (AMAN LOKAL & HOSTING)
// ==================================================
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

define('BASE_URL', $protocol . '://' . $host . $scriptDir);

// ==================================================
// LOAD DATABASE CONFIG
// ==================================================
$dbPath = __DIR__ . '/config/database.php';
if (!file_exists($dbPath)) {
    die('Database config tidak ditemukan');
}
require_once $dbPath;

if (!isset($pdo)) {
    die('Koneksi database gagal');
}

// ==================================================
// FUNCTION: GET CONTENT
// ==================================================
function get_content($section_name)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare(
            "SELECT judul, isi_konten, gambar_url
             FROM konten_web
             WHERE nama_section = ?
             LIMIT 1"
        );
        $stmt->execute([$section_name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ?: [
            'judul' => 'Judul Default',
            'isi_konten' => 'Konten belum diatur.',
            'gambar_url' => 'https://via.placeholder.com/1200x600'
        ];
    } catch (Throwable $e) {
        return [
            'judul' => 'Judul Error',
            'isi_konten' => 'Gagal memuat konten.',
            'gambar_url' => 'https://via.placeholder.com/1200x600'
        ];
    }
}

// ==================================================
// FETCH DATA
// ==================================================
$hero_content = get_content('hero');

// ==================================================
// LOG VISITOR (NON-BLOCKING)
// ==================================================
if (!empty($_SERVER['REMOTE_ADDR'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO visits (ip) VALUES (?)");
        $stmt->execute([$_SERVER['REMOTE_ADDR']]);
    } catch (Throwable $e) {
        // abaikan error
    }
}

// ==================================================
// LOAD HEADER
// ==================================================
$page_title = 'Selamat Datang di MTs Swasta Solear';

$headerPath = __DIR__ . '/includes/header.php';
if (file_exists($headerPath)) {
    require_once $headerPath;
} else {
    echo "<!DOCTYPE html><html><head><title>$page_title</title></head><body>";
}
?>

<!-- ==============================
     HERO SECTION
================================ -->
<?php
$hero_bg = !empty($hero_content['gambar_url'])
    ? htmlspecialchars($hero_content['gambar_url'])
    : 'https://via.placeholder.com/1200x600';
?>

<section id="hero"
    class="relative text-white mb-5"
    style="background-image:url('<?= $hero_bg ?>');
           background-size:cover;
           background-position:center;">

    <div class="absolute inset-0 bg-black opacity-50"></div>

    <div class="container mx-auto px-6 py-32 relative z-10 text-center">
        <h1 class="text-4xl md:text-6xl font-bold leading-tight">
            <?= htmlspecialchars($hero_content['judul']) ?>
        </h1>

        <p class="mt-4 text-lg md:text-xl">
            <?= htmlspecialchars($hero_content['isi_konten']) ?>
        </p>

        <a href="<?= BASE_URL ?>/ppdb.php"
           class="mt-8 inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg">
            Daftar Sekarang
        </a>
    </div>
</section>

<?php
// ==================================================
// LOAD FOOTER
// ==================================================
$footerPath = __DIR__ . '/includes/footer.php';
if (file_exists($footerPath)) {
    require_once $footerPath;
} else {
    echo "</body></html>";
}
?>
