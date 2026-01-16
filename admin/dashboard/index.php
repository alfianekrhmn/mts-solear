<?php
$page_title = 'Dashboard';
require_once __DIR__ . '/../includes/layout_header.php';
require_once __DIR__ . '/../../config/database.php';

// Ambil data ringkasan
$count_ppdb = $pdo->query("SELECT COUNT(*) FROM ppdb")->fetchColumn();
$count_laporan = $pdo->query("SELECT COUNT(*) FROM laporan WHERE status = 'Baru' OR status IS NULL")->fetchColumn();
$count_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

require_once __DIR__ . '/../includes/sidebar.php';
?>

<!-- Main Content -->
<div class="flex-1 flex flex-col">
    <header class="bg-white shadow-md p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Dashboard</h1>
        <div class="flex items-center">
            <span class="text-gray-600 mr-4 hidden sm:inline">Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span>
            <a href="/mtssolear/app/controllers/AdminController.php?action=logout" class="px-4 py-2 font-medium text-white bg-red-600 rounded-md hover:bg-red-700 hidden md:inline-flex">Logout</a>
            <button id="mobile-menu-button" class="p-2 text-gray-500 rounded-md md:hidden focus:outline-none focus:bg-gray-100 ml-2">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </header>
    <main class="flex-grow p-4 md:p-8">
        <h2 class="text-2xl font-semibold text-gray-700">Ringkasan</h2>
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Pendaftar Baru (PPDB)</h3>
                <p class="text-3xl font-bold mt-2"><?php echo $count_ppdb; ?></p>
                <a href="../pages/reports.php" class="text-blue-600 mt-2 inline-block">Lihat Laporan</a>
            </div>
            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Laporan Masuk (Baru)</h3>
                <p class="text-3xl font-bold mt-2"><?php echo $count_laporan; ?></p>
                <a href="../pages/reports.php" class="text-blue-600 mt-2 inline-block">Kelola Laporan</a>
            </div>
            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Pengguna Admin</h3>
                <p class="text-3xl font-bold mt-2"><?php echo $count_users; ?></p>
            </div>
        </div>
    </main>
</div>

<?php
require_once __DIR__ . '/../includes/layout_footer.php';
?>
