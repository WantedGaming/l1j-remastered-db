<?php
require_once '../includes/config.php';

// Initialize session
session_start();

// Get all maps from the database
$query = "SELECT mapid, locationname as name, startX, startY, endX, endY, teleportable, escapable, resurrection, take_pets, recall_pets, usable_item, usable_skill, underwater as is_pvp, markable as is_safe_zone, dungeon as is_instance FROM mapids ORDER BY locationname ASC";
$result = $conn->query($query);

$maps = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $maps[] = $row;
    }
}

// Since the existing table doesn't have region and type columns, we'll create some sample values
$regions = ['Aden Kingdom', 'Elmore Kingdom', 'Gludio Territory', 'Dion Territory', 'Giran Territory'];
$types = ['Field', 'Town', 'Dungeon', 'Castle', 'Village', 'Raid'];
sort($regions);
sort($types);

// Add region and type to each map based on mapid ranges
foreach ($maps as &$map) {
    // Assign region based on mapid range
    if ($map['mapid'] < 10) {
        $map['region'] = 'Aden Kingdom';
    } elseif ($map['mapid'] < 20) {
        $map['region'] = 'Elmore Kingdom';
    } elseif ($map['mapid'] < 30) {
        $map['region'] = 'Gludio Territory';
    } elseif ($map['mapid'] < 40) {
        $map['region'] = 'Dion Territory';
    } else {
        $map['region'] = 'Giran Territory';
    }
    
    // Assign type based on various properties
    if ($map['is_safe_zone']) {
        $map['type'] = 'Town';
    } elseif ($map['is_instance']) {
        $map['type'] = 'Raid';
    } elseif ($map['is_pvp']) {
        $map['type'] = 'PvP Zone';
    } else {
        $map['type'] = 'Field';
    }
    
    // Add level range (not in original table)
    $map['min_level'] = 1 + ($map['mapid'] * 2);
    $map['max_level'] = 20 + ($map['mapid'] * 3);
    
    // Use our HTML placeholder
    $map['image_path'] = 'maps/images/map_placeholder.html?id=' . $map['mapid'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maps - L1J Remastered Database</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
    .map-list {
        margin-top: 20px;
        width: 100%;
    }
    
    .map-list-item {
        display: flex;
        align-items: center;
        background: rgba(30, 30, 30, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        margin-bottom: 15px;
        padding: 15px;
        transition: all 0.3s ease;
    }
    
    .map-list-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .map-list-image {
        width: 80px;
        height: 80px;
        background-color: #333;
        border-radius: 6px;
        overflow: hidden;
        margin-right: 20px;
        flex-shrink: 0;
    }
    
    .map-list-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .map-list-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
        color: white;
        display: flex;
        align-items: center;
    }
    
    .map-list-info {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 5px;
    }
    
    .map-list-detail {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        color: #ddd;
    }
    
    .map-list-actions {
        margin-left: auto;
        display: flex;
        align-items: center;
    }
        
        .map-filters {
            background: rgba(30, 30, 30, 0.7);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: white;
        }
        
        .filter-group select, .filter-group input {
            width: 100%;
            padding: 8px 12px;
            border-radius: 4px;
            background: #333;
            border: 1px solid #444;
            color: white;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        
        .map-card a {
            text-decoration: none;
            color: inherit;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 5px;
        }
        
        .badge-safe {
            background-color: #28a745;
            color: white;
        }
        
        .badge-pvp {
            background-color: #dc3545;
            color: white;
        }
        
        .badge-instance {
            background-color: #6f42c1;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <a href="../index.php" class="logo">L1J Remastered DB</a>
            <nav>
                <ul>
                    <li><a href="../index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="../tables.php"><i class="fas fa-table"></i> Tables</a></li>
                    <li><a href="../search.php"><i class="fas fa-search"></i> Search</a></li>
                    <li><a href="../about.php"><i class="fas fa-info-circle"></i> About</a></li>
                    <li><a href="../player.php"><i class="fas fa-gamepad"></i> Player View</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="user-info">
                        <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_id']); ?><?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?> <span class="admin-badge">Admin</span><?php endif; ?></span>
                        <a href="../logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                    <?php else: ?>
                    <li><a href="../login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <h1 class="page-title">Maps</h1>
        
        <div class="map-filters">
            <div class="filter-group">
                <label for="search">Search</label>
                <input type="text" id="search" placeholder="Search by name...">
            </div>
            <div class="filter-group">
                <label for="region">Region</label>
                <select id="region">
                    <option value="">All Regions</option>
                    <?php foreach ($regions as $region): ?>
                    <option value="<?php echo htmlspecialchars($region); ?>"><?php echo htmlspecialchars($region); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label for="type">Type</label>
                <select id="type">
                    <option value="">All Types</option>
                    <?php foreach ($types as $type): ?>
                    <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-group">
                <label for="level">Level Range</label>
                <select id="level">
                    <option value="">Any Level</option>
                    <option value="1-20">1-20</option>
                    <option value="21-40">21-40</option>
                    <option value="41-60">41-60</option>
                    <option value="61-80">61-80</option>
                    <option value="81-99">81-99</option>
                </select>
            </div>
            <div class="filter-buttons">
                <button id="filter-button" class="btn">Apply Filters</button>
                <button id="reset-button" class="btn btn-secondary">Reset</button>
            </div>
        </div>
        
        <div class="map-list">
            <?php foreach ($maps as $map): ?>
            <a href="view.php?id=<?php echo $map['mapid']; ?>" class="map-list-item"
               data-name="<?php echo htmlspecialchars(strtolower($map['name'])); ?>"
               data-region="<?php echo htmlspecialchars($map['region']); ?>"
               data-type="<?php echo htmlspecialchars($map['type']); ?>"
               data-min-level="<?php echo (int)$map['min_level']; ?>"
               data-max-level="<?php echo (int)$map['max_level']; ?>">
                <div class="map-list-image">
                    <iframe src="../<?php echo $map['image_path']; ?>" frameborder="0" scrolling="no" width="100%" height="100%"></iframe>
                </div>
                <div class="map-list-content">
                    <div class="map-list-title">
                        <?php echo htmlspecialchars($map['name']); ?>
                        <?php if ($map['is_safe_zone']): ?>
                        <span class="badge badge-safe">Safe</span>
                        <?php endif; ?>
                        <?php if ($map['is_pvp']): ?>
                        <span class="badge badge-pvp">PvP</span>
                        <?php endif; ?>
                        <?php if ($map['is_instance']): ?>
                        <span class="badge badge-instance">Instance</span>
                        <?php endif; ?>
                    </div>
                    <div class="map-list-info">
                        <div class="map-list-detail">
                            <i class="fas fa-globe"></i> <?php echo htmlspecialchars($map['region']); ?>
                        </div>
                        <div class="map-list-detail">
                            <i class="fas fa-tag"></i> <?php echo htmlspecialchars($map['type']); ?>
                        </div>
                        <div class="map-list-detail">
                            <i class="fas fa-layer-group"></i> Level <?php echo $map['min_level']; ?>-<?php echo $map['max_level']; ?>
                        </div>
                    </div>
                </div>
                <div class="map-list-actions">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($maps)): ?>
        <div class="alert alert-warning">No maps found in the database.</div>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container footer-content">
            <p class="footer-text">&copy; <?php echo date('Y'); ?> L1J Remastered Database. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapItems = document.querySelectorAll('.map-list-item');
            const searchInput = document.getElementById('search');
            const regionSelect = document.getElementById('region');
            const typeSelect = document.getElementById('type');
            const levelSelect = document.getElementById('level');
            const filterButton = document.getElementById('filter-button');
            const resetButton = document.getElementById('reset-button');
            
            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();
                const regionFilter = regionSelect.value;
                const typeFilter = typeSelect.value;
                const levelFilter = levelSelect.value;
                
                let minLevel = 0;
                let maxLevel = 99;
                
                if (levelFilter) {
                    const levelRange = levelFilter.split('-');
                    minLevel = parseInt(levelRange[0]);
                    maxLevel = parseInt(levelRange[1]);
                }
                
                mapItems.forEach(item => {
                    const name = item.dataset.name;
                    const region = item.dataset.region;
                    const type = item.dataset.type;
                    const itemMinLevel = parseInt(item.dataset.minLevel);
                    const itemMaxLevel = parseInt(item.dataset.maxLevel);
                    
                    const nameMatch = name.includes(searchTerm);
                    const regionMatch = !regionFilter || region === regionFilter;
                    const typeMatch = !typeFilter || type === typeFilter;
                    const levelMatch = (itemMinLevel <= maxLevel && itemMaxLevel >= minLevel);
                    
                    if (nameMatch && regionMatch && typeMatch && levelMatch) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
            
            filterButton.addEventListener('click', applyFilters);
            
            resetButton.addEventListener('click', function() {
                searchInput.value = '';
                regionSelect.value = '';
                typeSelect.value = '';
                levelSelect.value = '';
                
                mapItems.forEach(item => {
                    item.style.display = '';
                });
            });
            
            // Enable search as you type
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                mapItems.forEach(item => {
                    const name = item.dataset.name;
                    
                    if (name.includes(searchTerm)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>