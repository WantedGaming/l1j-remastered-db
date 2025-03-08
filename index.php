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
    <title>L1J Remastered Database - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <a href="index.php" class="logo">L1J Remastered DB - Admin</a>
            <nav>
                <ul>
                    <li><a href="index.php" class="active"><i class="fas fa-home"></i> Admin Home</a></li>
                    <li><a href="tables.php"><i class="fas fa-table"></i> Tables</a></li>
                    <li><a href="search.php"><i class="fas fa-search"></i> Search</a></li>
                    <li><a href="about.php"><i class="fas fa-info-circle"></i> About</a></li>
                    <li><a href="player.php" class="player-view"><i class="fas fa-users"></i> Player View</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="hero-section animate__animated animate__fadeIn">
            <h1 class="page-title">L1J Remastered Database Explorer</h1>
            <p class="hero-text">Access and manage game data with ease. Explore tables, search records, and view detailed information about the L1J Remastered database.</p>
            <div class="hero-actions">
                <a href="tables.php" class="btn"><i class="fas fa-table"></i> Browse Tables</a>
                <a href="search.php" class="btn btn-secondary"><i class="fas fa-search"></i> Search Data</a>
            </div>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success animate__animated animate__fadeIn">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        
        <div class="stats-section">
            <h2 class="section-title">Database Overview</h2>
            <div class="stats-cards">
                <div class="stat-card animate__animated animate__fadeInUp">
                    <div class="stat-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Database Name</h3>
                        <p class="stat-value"><?php echo DB_NAME; ?></p>
                    </div>
                </div>
                <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                    <div class="stat-icon">
                        <i class="fas fa-table"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Tables</h3>
                        <p class="stat-value"><?php echo $tableCount; ?></p>
                    </div>
                </div>
                <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Last Updated</h3>
                        <p class="stat-value"><?php echo date('M d, Y'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <h2 class="section-title">Database Tables</h2>
        <div class="table-filter">
            <input type="text" id="tableSearch" placeholder="Filter tables..." class="filter-input">
            <select id="tableSort" class="filter-select">
                <option value="name">Sort by Name</option>
                <option value="rows">Sort by Row Count</option>
            </select>
        </div>
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
                
                // Determine table category based on name (for demonstration)
                $category = '';
                if (strpos($table, 'character') !== false || strpos($table, 'player') !== false) {
                    $category = 'character';
                } elseif (strpos($table, 'item') !== false || strpos($table, 'weapon') !== false || strpos($table, 'armor') !== false) {
                    $category = 'item';
                } elseif (strpos($table, 'npc') !== false || strpos($table, 'monster') !== false) {
                    $category = 'npc';
                } elseif (strpos($table, 'skill') !== false || strpos($table, 'spell') !== false) {
                    $category = 'skill';
                } else {
                    $category = 'other';
                }
                ?>
                <div class="card animate__animated animate__fadeIn" data-name="<?php echo htmlspecialchars($table); ?>" data-rows="<?php echo $rowCount; ?>" data-category="<?php echo $category; ?>">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo htmlspecialchars($table); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="card-stats">
                            <div class="card-stat">
                                <i class="fas fa-database"></i>
                                <span><strong>Rows:</strong> <?php echo $rowCount; ?></span>
                            </div>
                            <div class="card-stat">
                                <i class="fas fa-tag"></i>
                                <span><strong>Category:</strong> <?php echo ucfirst($category); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="view_table.php?table=<?php echo urlencode($table); ?>" class="btn"><i class="fas fa-eye"></i> View Data</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <div class="container footer-content">
            <p class="footer-text">&copy; <?php echo date('Y'); ?> L1J Remastered Database. All rights reserved.</p>
            <div class="footer-links">
                <a href="https://github.com/l1j-en/classic" target="_blank"><i class="fab fa-github"></i> GitHub</a>
                <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Table search functionality
            const tableSearch = document.getElementById('tableSearch');
            const tableSort = document.getElementById('tableSort');
            const cards = document.querySelectorAll('.card');
            
            tableSearch.addEventListener('input', filterTables);
            tableSort.addEventListener('change', sortTables);
            
            function filterTables() {
                const searchTerm = tableSearch.value.toLowerCase();
                
                cards.forEach(card => {
                    const tableName = card.getAttribute('data-name').toLowerCase();
                    if (tableName.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
            
            function sortTables() {
                const sortBy = tableSort.value;
                const cardsArray = Array.from(cards);
                
                cardsArray.sort((a, b) => {
                    if (sortBy === 'name') {
                        return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                    } else if (sortBy === 'rows') {
                        return parseInt(b.getAttribute('data-rows')) - parseInt(a.getAttribute('data-rows'));
                    }
                });
                
                const cardGrid = document.querySelector('.card-grid');
                cardsArray.forEach(card => cardGrid.appendChild(card));
            }
            
            // Add hover effects to cards
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.classList.add('animate__pulse');
                });
                
                card.addEventListener('mouseleave', function() {
                    this.classList.remove('animate__pulse');
                });
            });
        });
    </script>
</body>
</html>