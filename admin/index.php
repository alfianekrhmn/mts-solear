<?php
session_start();

// Database connection (same as landing page)
$host = '127.0.0.1';
$db = 'sekolah_db'; // Changed to match config/database.php
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Handle error, e.g., display message
    $pdo = null;
}

// Get period from GET, default to 'daily'
$period = isset($_GET['period']) ? $_GET['period'] : 'daily';

// Query traffic stats for info boxes (unchanged)
$daily = 0;
$weekly = 0;
$monthly = 0;
if ($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM visits WHERE visit_time >= DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $daily = $stmt->fetch()['count'];

    $stmt = $pdo->query("SELECT COUNT(*) as count FROM visits WHERE visit_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $weekly = $stmt->fetch()['count'];

    $stmt = $pdo->query("SELECT COUNT(*) as count FROM visits WHERE visit_time >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
    $monthly = $stmt->fetch()['count'];
}

// Query data for bar chart based on period
$chartData = [];
if ($pdo) {
    if ($period === 'daily') {
        // Visits per hour for today
        $stmt = $pdo->query("SELECT HOUR(visit_time) as hour, COUNT(*) as count FROM visits WHERE DATE(visit_time) = CURDATE() GROUP BY HOUR(visit_time)");
        while ($row = $stmt->fetch()) {
            $chartData[] = ['hour' => $row['hour'], 'count' => $row['count']];
        }
    } elseif ($period === 'weekly') {
        // Visits per day for last 7 days
        $stmt = $pdo->query("SELECT DATE(visit_time) as date, COUNT(*) as count FROM visits WHERE visit_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(visit_time)");
        while ($row = $stmt->fetch()) {
            $chartData[] = ['day' => date('D', strtotime($row['date'])), 'count' => $row['count']];
        }
    } elseif ($period === 'monthly') {
        // Visits per week for last 30 days
        $stmt = $pdo->query("SELECT WEEK(visit_time) as week, COUNT(*) as count FROM visits WHERE visit_time >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY WEEK(visit_time)");
        while ($row = $stmt->fetch()) {
            $chartData[] = ['week' => 'Minggu ' . $row['week'], 'count' => $row['count']];
        }
    }
}

// Add dummy data if no real data
if (empty($chartData)) {
    if ($period === 'daily') {
        $chartData = [
            ['hour' => 8, 'count' => 5],
            ['hour' => 12, 'count' => 10],
            ['hour' => 16, 'count' => 8],
            ['hour' => 20, 'count' => 3],
        ];
    } elseif ($period === 'weekly') {
        $chartData = [
            ['day' => 'Mon', 'count' => 15],
            ['day' => 'Tue', 'count' => 20],
            ['day' => 'Wed', 'count' => 18],
            ['day' => 'Thu', 'count' => 25],
            ['day' => 'Fri', 'count' => 30],
            ['day' => 'Sat', 'count' => 12],
            ['day' => 'Sun', 'count' => 8],
        ];
    } elseif ($period === 'monthly') {
        $chartData = [
            ['week' => 'Minggu 1', 'count' => 50],
            ['week' => 'Minggu 2', 'count' => 60],
            ['week' => 'Minggu 3', 'count' => 45],
            ['week' => 'Minggu 4', 'count' => 70],
        ];
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>MTS Solear | Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Morris chart -->
        <link href="css/morris/morris.css" rel="stylesheet" type="text/css" />
        <!-- jvectormap -->
        <link href="css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- Date Picker -->
        <link href="css/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">

        <a href="index.php" class="logo">Admin</a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
</div>
</nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">
    
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="assets/logo-sekolah.png" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p>Admin</p>

                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- search form -->
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search..."/>
                    <span class="input-group-btn">
                        <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="active">
                    <a href="index.php">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>

                <!-- Removed Profil li -->
                
                <li>
                    <a href="pages/news.php">
                        <i class="fa fa-newspaper-o"></i> <span>Berita</span>
                    </a>
                </li>

                <li>
                    <a href="pages/gallery.php">
                        <i class="fa fa-picture-o"></i> <span>Galeri</span>
                    </a>
                </li>

                <li>
                    <a href="pages/ppdb.php">
                        <i class="fa fa-user-plus"></i> <span>Pendaftaran PPDB</span>
                    </a>
                </li>

                <li>
                    <a href="pages/reports.php">
                        <i class="fa fa-exclamation-triangle"></i> <span>Lapor Kasus</span>
                    </a>
                </li>

                <li>
                    <a href="pages/kontak.php">
                        <i class="fa fa-phone"></i> <span>Kontak</span>
                    </a>
                </li>

                <li>
                    <a href="pages/ekstrakurikuler.php">
                        <i class="fa fa-futbol-o"></i> <span>Ekstrakurikuler</span>
                    </a>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
                <small>Control panel</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-calendar-day"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Kunjungan Harian</span>
                            <span class="info-box-number"><?php echo $daily ?: 28; // Dummy if 0 ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-calendar-week"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Kunjungan Mingguan</span>
                            <span class="info-box-number"><?php echo $weekly ?: 150; // Dummy if 0 ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-calendar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Kunjungan Bulanan</span>
                            <span class="info-box-number"><?php echo $monthly ?: 600; // Dummy if 0 ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Statistik Kunjungan</h3>
                            <form method="get" style="display: inline;">
                                <select name="period" onchange="this.form.submit()">
                                    <option value="daily" <?php echo $period === 'daily' ? 'selected' : ''; ?>>Harian</option>
                                    <option value="weekly" <?php echo $period === 'weekly' ? 'selected' : ''; ?>>Mingguan</option>
                                    <option value="monthly" <?php echo $period === 'monthly' ? 'selected' : ''; ?>>Bulanan</option>
                                </select>
                            </form>
                        </div>
                        <div class="box-body">
                            <div id="traffic-chart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

<!-- add new calendar event modal -->


<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js" type="text/javascript"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
<!-- Morris.js charts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="js/plugins/morris/morris.min.js" type="text/javascript"></script>
<!-- Sparkline -->
<script src="js/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- jvectormap -->
<script src="js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
<script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
<!-- jQuery Knob Chart -->
<script src="js/plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script>
<!-- daterangepicker -->
<script src="js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- datepicker -->
<script src="js/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<!-- iCheck -->
<script src="js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>

<!-- AdminLTE App -->
<script src="js/AdminLTE/app.js" type="text/javascript"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="js/AdminLTE/dashboard.js" type="text/javascript"></script>

<!-- AdminLTE for demo purposes -->
<script src="js/AdminLTE/demo.js" type="text/javascript"></script>

<script>
$(function() {
    var chartData = <?php echo json_encode($chartData); ?>;
    var xkey, ykeys, labels;
    if ('<?php echo $period; ?>' === 'daily') {
        xkey = 'hour';
        ykeys = ['count'];
        labels = ['Kunjungan'];
    } else if ('<?php echo $period; ?>' === 'weekly') {
        xkey = 'day';
        ykeys = ['count'];
        labels = ['Kunjungan'];
    } else {
        xkey = 'week';
        ykeys = ['count'];
        labels = ['Kunjungan'];
    }
    Morris.Bar({
        element: 'traffic-chart',
        data: chartData,
        xkey: xkey,
        ykeys: ykeys,
        labels: labels,
        barColors: ['#3498db']
    });
});
</script>

</body>
</html>
<?php
// End of PHP
?>
