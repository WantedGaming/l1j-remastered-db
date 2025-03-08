<?php
require_once 'includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - L1J Remastered Database</title>
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
                    <li><a href="about.php" class="active"><i class="fas fa-info-circle"></i> About</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <h1 class="page-title">About L1J Remastered Database</h1>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Project Overview</h2>
            </div>
            <div class="card-body">
                <p>The L1J Remastered Database is a web-based tool designed to provide easy access and management capabilities for the L1J Remastered game database. This tool allows users to browse, search, edit, and export data from the game's database tables.</p>
                
                <h3>Features</h3>
                <ul>
                    <li>Browse all database tables with a modern card-based interface</li>
                    <li>View detailed table structure and data</li>
                    <li>Add, edit, and delete records</li>
                    <li>Search across tables for specific information</li>
                    <li>Export data in multiple formats (CSV, JSON, SQL)</li>
                    <li>Responsive design that works on desktop and mobile devices</li>
                </ul>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">About L1J Remastered</h2>
            </div>
            <div class="card-body">
                <p>L1J Remastered is a community-driven project that aims to revive and enhance the classic Lineage 1 MMORPG experience. The project focuses on maintaining the core gameplay elements that made the original game popular while introducing quality-of-life improvements and new content.</p>
                
                <p>This database tool is designed to help server administrators and developers manage game data efficiently, making it easier to maintain and update the game world.</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Technical Information</h2>
            </div>
            <div class="card-body">
                <h3>Database Structure</h3>
                <p>The L1J Remastered database contains numerous tables that store various game data, including:</p>
                <ul>
                    <li>Character information</li>
                    <li>Item data</li>
                    <li>Monster statistics</li>
                    <li>Quest information</li>
                    <li>World map data</li>
                    <li>Server configuration</li>
                </ul>
                
                <h3>Technology Stack</h3>
                <ul>
                    <li><strong>Frontend:</strong> HTML5, CSS3, JavaScript</li>
                    <li><strong>Backend:</strong> PHP</li>
                    <li><strong>Database:</strong> MySQL</li>
                    <li><strong>UI Framework:</strong> Custom CSS with responsive design</li>
                    <li><strong>Icons:</strong> Font Awesome</li>
                </ul>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Usage Guide</h2>
            </div>
            <div class="card-body">
                <h3>Browsing Tables</h3>
                <p>From the home page, you can see all available database tables displayed as cards. Click on "View Data" to explore the contents of a specific table.</p>
                
                <h3>Viewing and Editing Records</h3>
                <p>When viewing a table, you can see its structure and data. Use the edit and delete icons to modify records. Click "Add New Record" to create new entries.</p>
                
                <h3>Searching</h3>
                <p>Use the Search page to find specific information across tables. Select a table and enter your search query to find matching records.</p>
                
                <h3>Exporting Data</h3>
                <p>When viewing a table, click the "Export Data" button to download the table data in CSV, JSON, or SQL format.</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Contact & Support</h2>
            </div>
            <div class="card-body">
                <p>For questions, feedback, or support regarding the L1J Remastered Database tool, please contact the development team through the official L1J Remastered community channels.</p>
                
                <div class="contact-links">
                    <a href="https://github.com/l1j-remastered" target="_blank" class="btn btn-secondary">
                        <i class="fab fa-github"></i> GitHub
                    </a>
                    <a href="https://discord.gg/l1j-remastered" target="_blank" class="btn btn-secondary">
                        <i class="fab fa-discord"></i> Discord
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container footer-content">
            <p class="footer-text">&copy; <?php echo date('Y'); ?> L1J Remastered Database. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('About page loaded');
        });
    </script>
</body>
</html>