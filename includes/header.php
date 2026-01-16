<?php
// Menentukan halaman aktif untuk styling
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'MTs Swasta Solear'; ?></title>
    <!-- For production, install Tailwind locally: https://tailwindcss.com/docs/installation -->
    <!-- <link href="path/to/tailwind.css" rel="stylesheet"> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html { scroll-behavior: smooth; }
        .active { color: #059669; font-weight: bold; } /* Warna hijau untuk link aktif */
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Header & Navigasi -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-1 flex justify-between items-center">
            <div class="flex items-center">
                <a href="/mtssolear/index.php" class="flex items-center">
                    <img src="admin/assets/logo-sekolah.png" alt="Logo Sekolah" class="h-20 w-auto mr-2" onerror="this.src='https://via.placeholder.com/100x40?text=Logo'">
                </a>
            </div>
            <div class="hidden md:flex items-center space-x-6">
                <a href="/mtssolear/" class="<?php echo ($current_page == 'index.php') ? 'active' : 'hover:text-green-700'; ?>">Beranda</a>
                <a href="/mtssolear/profil.php" class="<?php echo ($current_page == 'profil.php') ? 'active' : 'hover:text-green-700'; ?>">Profil</a>
                <a href="/mtssolear/berita.php" class="<?php echo ($current_page == 'berita.php') ? 'active' : 'hover:text-green-700'; ?>">Berita</a>
                <a href="/mtssolear/galeri.php" class="<?php echo ($current_page == 'galeri.php') ? 'active' : 'hover:text-green-700'; ?>">Galeri</a>
                <a href="/mtssolear/ppdb.php" class="<?php echo ($current_page == 'ppdb.php') ? 'active' : 'hover:text-green-700'; ?>">Pendaftaran PPDB</a>
                <a href="/mtssolear/lapor.php" class="<?php echo ($current_page == 'lapor.php') ? 'active' : 'hover:text-green-700'; ?>">Lapor Kasus</a>
                <a href="/mtssolear/kontak.php" class="<?php echo ($current_page == 'kontak.php') ? 'active' : 'hover:text-green-700'; ?>">Kontak</a>
                <a href="/mtssolear/ekstrakurikuler.php" class="<?php echo ($current_page == 'ekstrakurikuler.php') ? 'active' : 'hover:text-green-700'; ?>">Ekstrakurikuler</a>
            </div>
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </nav>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden px-6 pb-4 space-y-2">
            <a href="/mtssolear/" class="block <?php echo ($current_page == 'index.php') ? 'active' : 'hover:text-green-700'; ?>">Beranda</a>
            <a href="/mtssolear/profil.php" class="block <?php echo ($current_page == 'profil.php') ? 'active' : 'hover:text-green-700'; ?>">Profil</a>
            <a href="/mtssolear/berita.php" class="block <?php echo ($current_page == 'berita.php') ? 'active' : 'hover:text-green-700'; ?>">Berita</a>
            <a href="/mtssolear/galeri.php" class="block <?php echo ($current_page == 'galeri.php') ? 'active' : 'hover:text-green-700'; ?>">Galeri</a>
            <a href="/mtssolear/ppdb.php" class="block <?php echo ($current_page == 'ppdb.php') ? 'active' : 'hover:text-green-700'; ?>">Pendaftaran PPDB</a>
            <a href="/mtssolear/lapor.php" class="block <?php echo ($current_page == 'lapor.php') ? 'active' : 'hover:text-green-700'; ?>">Lapor Kasus</a>
            <a href="/mtssolear/kontak.php" class="block <?php echo ($current_page == 'kontak.php') ? 'active' : 'hover:text-green-700'; ?>">Kontak</a>
            <a href="/mtssolear/ekstrakurikuler.php" class="block <?php echo ($current_page == 'ekstrakurikuler.php') ? 'active' : 'hover:text-green-700'; ?>">Ekstrakurikuler</a>
        </div>
    </header>

    <main>
