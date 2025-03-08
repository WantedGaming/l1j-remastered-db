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

// Initialize variables
$searchQuery = '';
$searchTable = '';
$searchResults = [];
$totalResults = 0;
$searchPerformed = false;

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 20;
$offset = ($page - 1) * $recordsPerPage;

// Process search form
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchQuery = isset($_GET['query']) ? sanitize($_GET['query']) : '';
    $searchTable = isset($_GET['table']) ? sanitize($_GET['table']) : '';
    $searchPerformed = true;
    
    if (!empty($searchQuery) && !empty($searchTable)) {
        // Get table structure to determine searchable columns
        $tableStructure = getTableStructure($searchTable);
        $searchableColumns = [];
        
        foreach ($tableStructure as $column) {
            // Only search in string-like columns
            if (strpos($column['Type'], 'char') !== false || 
                strpos($column['Type'], 'text') !== false || 
                strpos($column['Type'], 'enum') !== false || 
                strpos($column['Type'], 'set') !== false) {
                $searchableColumns[] = $column['Field'];
            }
        }
        
        // Determine primary key column(s)
        $primaryKeys = [];
        foreach ($tableStructure as $column) {
            if ($column['Key'] === 'PRI') {
                $primaryKeys[] = $column['Field'];
            }
        }
        
        // If no primary key, use the first column
        if (empty($primaryKeys) && !empty($tableStructure)) {
            $primaryKeys[] = $tableStructure[0]['Field'];
        }
        
        // Build search query
        if (!empty($searchableColumns)) {
            $conditions = [];
            foreach ($searchableColumns as $column) {
                $conditions[] = "`$column` LIKE '%$searchQuery%'";
            }
            
            $whereClause = implode(' OR ', $conditions);
            
            // Count total results
            $countQuery = "SELECT COUNT(*) as total FROM `$searchTable` WHERE $whereClause";
            $countResult = $conn->query($countQuery);
            $totalResults = $countResult->fetch_assoc()['total'];
            $totalPages = ceil($totalResults / $recordsPerPage);
            
            // Get paginated results
            $query = "SELECT * FROM `$searchTable` WHERE $whereClause LIMIT $offset, $recordsPerPage";
            $result = $conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $searchResults[] = $row;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Database - L1J Remastered Database</title>
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
                    <li><a href="search.php" class="active"><i class="fas fa-search"></i> Search</a></li>
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
        <h1 class="page-title">Search Database</h1>
        
        <div class="form-container">
            <form method="get" action="search.php">
                <div class="form-group">
                    <label for="table">Select Table</label>
                    <select id="table" name="table" required>
                        <option value="">Select a table</option>
                        <?php foreach ($tables as $table): ?>
                            <option value="<?php echo htmlspecialchars($table); ?>" <?php echo $searchTable === $table ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($table); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="query">Search Query</label>
                    <input type="text" id="query" name="query" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Enter search terms..." required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="search" value="1" class="btn">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
        
        <?php if ($searchPerformed): ?>
            <h2 class="section-title">Search Results</h2>
            
            <?php if ($totalResults > 0): ?>
                <div class="search-summary">
                    <p>Found <?php echo $totalResults; ?> result<?php echo $totalResults !== 1 ? 's' : ''; ?> for "<?php echo htmlspecialchars($searchQuery); ?>" in table "<?php echo htmlspecialchars($searchTable); ?>"</p>
                </div>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <?php 
                                $tableStructure = getTableStructure($searchTable);
                                foreach ($tableStructure as $column): 
                                ?>
                                    <th><?php echo htmlspecialchars($column['Field']); ?></th>
                                <?php endforeach; ?>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($searchResults as $row): ?>
                                <tr>
                                    <?php 
                                    foreach ($tableStructure as $column): 
                                        $field = $column['Field'];
                                        $value = $row[$field];
                                    ?>
                                        <td>
                                            <?php 
                                            // Highlight search term in results
                                            if (is_string($value) && strpos(strtolower($value), strtolower($searchQuery)) !== false) {
                                                $pattern = '/(' . preg_quote($searchQuery, '/') . ')/i';
                                                $value = preg_replace($pattern, '<span class="highlight">$1</span>', htmlspecialchars($value));
                                                echo $value;
                                            } else {
                                                // Truncate long values
                                                if (is_string($value) && strlen($value) > 100) {
                                                    echo htmlspecialchars(substr($value, 0, 100)) . '...';
                                                } else {
                                                    echo htmlspecialchars($value ?? 'NULL');
                                                }
                                            }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="actions">
                                        <?php 
                                        // Determine primary key for actions
                                        $primaryKeys = [];
                                        foreach ($tableStructure as $column) {
                                            if ($column['Key'] === 'PRI') {
                                                $primaryKeys[] = $column['Field'];
                                            }
                                        }
                                        
                                        if (!empty($primaryKeys)) {
                                            $idParam = $row[$primaryKeys[0]];
                                        ?>
                                            <a href="view_table.php?table=<?php echo urlencode($searchTable); ?>&highlight=<?php echo urlencode($idParam); ?>" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="edit_record.php?table=<?php echo urlencode($searchTable); ?>&id=<?php echo urlencode($idParam); ?>&action=edit" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <?php if (isset($totalPages) && $totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="search.php?query=<?php echo urlencode($searchQuery); ?>&table=<?php echo urlencode($searchTable); ?>&search=1&page=<?php echo $page - 1; ?>">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            <?php endif; ?>
                            
                            <?php
                            $startPage = max(1, $page - 2);
                            $endPage = min($totalPages, $page + 2);
                            
                            for ($i = $startPage; $i <= $endPage; $i++):
                            ?>
                                <a href="search.php?query=<?php echo urlencode($searchQuery); ?>&table=<?php echo urlencode($searchTable); ?>&search=1&page=<?php echo $i; ?>" <?php echo $i === $page ? 'class="active"' : ''; ?>>
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                                <a href="search.php?query=<?php echo urlencode($searchQuery); ?>&table=<?php echo urlencode($searchTable); ?>&search=1&page=<?php echo $page + 1; ?>">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    No results found for "<?php echo htmlspecialchars($searchQuery); ?>" in table "<?php echo htmlspecialchars($searchTable); ?>".
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container footer-content">
            <p class="footer-text">&copy; <?php echo date('Y'); ?> L1J Remastered Database. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Search page loaded');
        });
    </script>
</body>
</html>