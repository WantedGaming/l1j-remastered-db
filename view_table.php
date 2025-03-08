<?php
require_once 'includes/config.php';

// Check if table parameter is provided
if (!isset($_GET['table']) || empty($_GET['table'])) {
    header('Location: index.php');
    exit;
}

$tableName = sanitize($_GET['table']);

// Check if table exists
$tableExistsQuery = "SHOW TABLES LIKE '$tableName'";
$tableExistsResult = $conn->query($tableExistsQuery);

if ($tableExistsResult->num_rows === 0) {
    header('Location: index.php?error=Table not found');
    exit;
}

// Get table structure
$tableStructure = getTableStructure($tableName);

// Determine primary key column(s)
$primaryKeys = [];
foreach ($tableStructure as $column) {
    if ($column['Key'] === 'PRI') {
        $primaryKeys[] = $column['Field'];
    }
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 20;
$offset = ($page - 1) * $recordsPerPage;

// Get total records
$countQuery = "SELECT COUNT(*) as total FROM `$tableName`";
$countResult = $conn->query($countQuery);
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Get records for current page
$query = "SELECT * FROM `$tableName` LIMIT $offset, $recordsPerPage";
$result = $conn->query($query);

// Handle record deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && !empty($_GET['id'])) {
    $id = sanitize($_GET['id']);
    $idColumn = $primaryKeys[0]; // Use the first primary key for simplicity
    
    $deleteQuery = "DELETE FROM `$tableName` WHERE `$idColumn` = '$id'";
    if ($conn->query($deleteQuery)) {
        header("Location: view_table.php?table=$tableName&success=Record deleted successfully");
        exit;
    } else {
        $error = "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tableName); ?> - L1J Remastered Database</title>
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
        <h1 class="page-title"><?php echo htmlspecialchars($tableName); ?></h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div class="table-actions">
            <a href="edit_record.php?table=<?php echo urlencode($tableName); ?>&action=add" class="btn">
                <i class="fas fa-plus"></i> Add New Record
            </a>
            <a href="export_table.php?table=<?php echo urlencode($tableName); ?>" class="btn btn-secondary">
                <i class="fas fa-download"></i> Export Data
            </a>
        </div>
        
        <h2 class="section-title">Table Structure</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Column</th>
                        <th>Type</th>
                        <th>Null</th>
                        <th>Key</th>
                        <th>Default</th>
                        <th>Extra</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tableStructure as $column): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($column['Field']); ?></td>
                            <td><?php echo htmlspecialchars($column['Type']); ?></td>
                            <td><?php echo htmlspecialchars($column['Null']); ?></td>
                            <td><?php echo htmlspecialchars($column['Key']); ?></td>
                            <td><?php echo htmlspecialchars($column['Default'] ?? 'NULL'); ?></td>
                            <td><?php echo htmlspecialchars($column['Extra']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <h2 class="section-title">Table Data</h2>
        <div class="table-container">
            <?php if ($result && $result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <?php foreach ($tableStructure as $column): ?>
                                <th><?php echo htmlspecialchars($column['Field']); ?></th>
                            <?php endforeach; ?>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <?php foreach ($tableStructure as $column): ?>
                                    <td>
                                        <?php 
                                        $value = $row[$column['Field']];
                                        // Truncate long values
                                        if (is_string($value) && strlen($value) > 100) {
                                            echo htmlspecialchars(substr($value, 0, 100)) . '...';
                                        } else {
                                            echo htmlspecialchars($value ?? 'NULL');
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                                <td class="actions">
                                    <?php if (!empty($primaryKeys)): ?>
                                        <?php 
                                        // Create ID parameter for edit/delete links
                                        $idParam = $row[$primaryKeys[0]];
                                        ?>
                                        <a href="edit_record.php?table=<?php echo urlencode($tableName); ?>&id=<?php echo urlencode($idParam); ?>&action=edit" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="view_table.php?table=<?php echo urlencode($tableName); ?>&id=<?php echo urlencode($idParam); ?>&action=delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="view_table.php?table=<?php echo urlencode($tableName); ?>&page=<?php echo $page - 1; ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);
                        
                        for ($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <a href="view_table.php?table=<?php echo urlencode($tableName); ?>&page=<?php echo $i; ?>" <?php echo $i === $page ? 'class="active"' : ''; ?>>
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="view_table.php?table=<?php echo urlencode($tableName); ?>&page=<?php echo $page + 1; ?>">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="alert alert-warning">No records found in this table.</div>
            <?php endif; ?>
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