<?php
require_once 'includes/config.php';

// Get all tables in the database
$tables = getAllTables();

// Count tables
$tableCount = count($tables);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L1J Remastered Database</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <a href="index.php" class="logo">L1J Remastered DB</a>
            <nav>
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="tables.php"><i class="fas fa-table"></i> Tables</a></li>
                    <li><a href="search.php"><i class="fas fa-search"></i> Search</a></li>
                    <li><a href="about.php"><i class="fas fa-info-circle"></i> About</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <h1 class="page-title">Database Explorer</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-stats">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Database Statistics</h2>
                </div>
                <div class="card-body">
                    <p><strong>Total Tables:</strong> <?php echo $tableCount; ?></p>
                    <p><strong>Database Name:</strong> <?php echo DB_NAME; ?></p>
                </div>
            </div>
        </div>
        
        <h2 class="section-title">Database Tables</h2>
        <div class="card-grid">
            <?php foreach ($tables as $table): ?>
                <?php 
                // Get row count for this table
                $countQuery = "SELECT COUNT(*) as count FROM `$table`";
                $countResult = $conn->query($countQuery);
                $rowCount = 0;
                if ($countResult && $countResult->num_rows > 0) {
                    $rowCount = $countResult->fetch_assoc()['count'];
                }
                ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo htmlspecialchars($table); ?></h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Rows:</strong> <?php echo $rowCount; ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="view_table.php?table=<?php echo urlencode($table); ?>" class="btn">View Data</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <div class="container footer-content">
            <p class="footer-text">&copy; <?php echo date('Y'); ?> L1J Remastered Database. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Add any JavaScript functionality here
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded and parsed');
        });
    </script>
</body>
</html>