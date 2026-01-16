<?php
require_once __DIR__ . '/config/database.php';

// Fungsi untuk mengambil konten dari database
function get_content($section_name) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM konten_web WHERE nama_section = ?");
    $stmt->execute([$section_name]);
    $content = $stmt->fetch();
    // Sediakan nilai default jika konten tidak ditemukan
    return $content ?: ['judul' => 'Judul Default', 'isi_konten' => 'Konten belum diatur.'];
}

$profil_content = get_content('profil');

$page_title = 'Profil Sekolah - MTs Solear';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container mx-auto px-6 py-20">
    <!-- Logo centered -->
    <div class="text-center mb-1">
        <img src="admin/assets/logo-sekolah.png" alt="Logo Sekolah" class="mx-auto" style="max-width: 200px;">
    </div>
    
    <!-- Profile content -->
    <div class="text-center mb-3">
        <h1 class="text-4xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($profil_content['judul']); ?></h1>
        <p class="max-w-3xl mx-auto text-lg text-gray-600 leading-relaxed">
            <?php echo nl2br(htmlspecialchars($profil_content['isi_konten'])); ?>
        </p>
    </div>
    
    <!-- Visi -->
    <div class="mb-3">
        <h2 class="text-2xl font-semibold text-center mb-4">VISI</h2>
        <p class="text-center text-lg text-gray-600 leading-relaxed">
            “Terwujudnya peserta didik yang unggul, berbudaya, berakhlakul karimah dan islami”
        </p>
    </div>
    
    <!-- Misi -->
    <div>
        <h2 class="text-2xl font-semibold text-center mb-4">MISI</h2>
        <ul class="list-disc list-inside text-lg text-gray-600 leading-relaxed max-w-3xl mx-auto">
            <li>Melaksanakan pendidikan secara disiplin, kerja keras yang ikhlas, jujur dan bertanggung jawab, transparansi, demokrasi, kekeluargaan dan peka terhadap pembaharuan.</li>
            <li>Menerapkan sistem pembelajaran aktif, kreatif dan bermakna melalui metodologi kasih sayang.</li>
            <li>Mengoptimalkan proses pembelajaran dan bimbingan.</li>
            <li>Meningkatkan profesionalisme guru dan bimbingan.</li>
            <li>Meningkatkan sarana dan prasarana belajar.</li>
            <li>Melaksanakan kegiatan ekstrakulikuler dan pembiasaan, dan</li>
            <li>Menjalinkan kerjasama yang harmonis antara warga madrasah dan lingkungan.</li>
        </ul>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
