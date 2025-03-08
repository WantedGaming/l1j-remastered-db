<?php
require_once 'includes/config.php';

// Initialize session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header("Location: login.php");
    exit;
}

// Get user information
$isAdmin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;
$username = $_SESSION['user_id'];

// Get all tables in the database
$tables = getAllTables();

// Count tables
$tableCount = count($tables);

// Get additional information for each table
$tableInfo = [];
foreach ($tables as $table) {
    // Get row count
    $countQuery = "SELECT COUNT(*) as count FROM `$table`";
    $countResult = $conn->query($countQuery);
    $rowCount = 0;
    if ($countResult && $countResult->num_rows > 0) {
        $rowCount = $countResult->fetch_assoc()['count'];
    }
    
    // Get table structure
    $structure = getTableStructure($table);
    $columnCount = count($structure);
    
    // Get creation time if available
    $creationTime = '';
    $statusQuery = "SHOW TABLE STATUS LIKE '$table'";
    $statusResult = $conn->query($statusQuery);
    if ($statusResult && $statusResult->num_rows > 0) {
        $status = $statusResult->fetch_assoc();
        $creationTime = $status['Create_time'] ?? '';
    }
    
    $tableInfo[$table] = [
        'rows' => $rowCount,
        'columns' => $columnCount,
        'created' => $creationTime
    ];
}

// Sort tables alphabetically
sort($tables);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tables - L1J Remastered Database</title>
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
                    <li><a href="tables.php" class="active"><i class="fas fa-table"></i> Tables</a></li>
                    <li><a href="search.php"><i class="fas fa-search"></i> Search</a></li>
                    <li><a href="about.php"><i class="fas fa-info-circle"></i> About</a></li>
                    <li class="user-info">
                        <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?><?php if ($isAdmin): ?> <span class="admin-badge">Admin</span><?php endif; ?></span>
                        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <h1 class="page-title">Database Tables</h1>
        
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
        
        <div class="table-actions">
            <div class="search-filter">
                <input type="text" id="tableFilter" placeholder="Filter tables..." class="filter-input">
                <button id="clearFilter" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>
        
        <div class="table-container">
            <table id="tablesTable">
                <thead>
                    <tr>
                        <th>Table Name</th>
                        <th>Rows</th>
                        <th>Columns</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tables as $table): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($table); ?></td>
                            <td><?php echo number_format($tableInfo[$table]['rows']); ?></td>
                            <td><?php echo $tableInfo[$table]['columns']; ?></td>
                            <td><?php echo $tableInfo[$table]['created'] ? date('Y-m-d', strtotime($tableInfo[$table]['created'])) : 'N/A'; ?></td>
                            <td class="actions">
                                <a href="view_table.php?table=<?php echo urlencode($table); ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="export_table.php?table=<?php echo urlencode($table); ?>&format=csv" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-download"></i> Export
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <div class="container footer-content">
            <p class="footer-text">&copy; <?php echo date('Y'); ?> L1J Remastered Database. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Table filtering functionality
            const tableFilter = document.getElementById('tableFilter');
            const clearFilter = document.getElementById('clearFilter');
            const tablesTable = document.getElementById('tablesTable');
            const tableRows = tablesTable.querySelectorAll('tbody tr');
            
            tableFilter.addEventListener('input', function() {
                const filterValue = this.value.toLowerCase();
                
                tableRows.forEach(row => {
                    const tableName = row.cells[0].textContent.toLowerCase();
                    if (tableName.includes(filterValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            
            clearFilter.addEventListener('click', function() {
                tableFilter.value = '';
                tableRows.forEach(row => {
                    row.style.display = '';
                });
            });
        });
    </script>
</body>
</html>