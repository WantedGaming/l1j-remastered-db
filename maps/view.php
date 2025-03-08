<?php
require_once '../includes/config.php';

// Initialize session
session_start();

// Check if map ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$mapId = (int)$_GET['id'];

// Get map details
$query = "SELECT mapid, locationname as name, startX, startY, endX, endY, teleportable, escapable, resurrection, take_pets, recall_pets, usable_item, usable_skill, underwater as is_pvp, markable as is_safe_zone, dungeon as is_instance FROM mapids WHERE mapid = $mapId";
$result = $conn->query($query);

if (!$result || $result->num_rows === 0) {
    header('Location: index.php?error=Map not found');
    exit;
}

$map = $result->fetch_assoc();

// Format flags for display
$flags = [
    'teleportable' => ['name' => 'Teleport', 'icon' => 'fa-magic'],
    'escapable' => ['name' => 'Escape', 'icon' => 'fa-person-running'],
    'resurrection' => ['name' => 'Resurrection', 'icon' => 'fa-heart-pulse'],
    'take_pets' => ['name' => 'Take Pets', 'icon' => 'fa-paw'],
    'recall_pets' => ['name' => 'Recall Pets', 'icon' => 'fa-whistle'],
    'usable_item' => ['name' => 'Use Items', 'icon' => 'fa-flask'],
    'usable_skill' => ['name' => 'Use Skills', 'icon' => 'fa-wand-sparkles']
];

// Add region and type based on mapid ranges (since they don't exist in the original table)
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

// Add description (not in original table)
$map['description'] = 'This is map #' . $map['mapid'] . ' (' . $map['name'] . ') located in the ' . $map['region'] . '. ' .
                     'It is a ' . $map['type'] . ' suitable for characters between levels ' . $map['min_level'] . ' and ' . $map['max_level'] . '. ' .
                     'The map coordinates range from X:' . $map['startX'] . '-' . $map['endX'] . ' to Y:' . $map['startY'] . '-' . $map['endY'] . '.';

// Use our HTML placeholder
$map['image_path'] = 'maps/images/map_placeholder.html?id=' . $map['mapid'];

// Get related maps (based on mapid proximity)
$relatedQuery = "SELECT mapid, locationname as name FROM mapids
                WHERE mapid != $mapId
                ORDER BY ABS(mapid - $mapId)
                LIMIT 4";
$relatedResult = $conn->query($relatedQuery);
$relatedMaps = [];

if ($relatedResult && $relatedResult->num_rows > 0) {
    while ($relatedMap = $relatedResult->fetch_assoc()) {
        // Add type based on mapid
        if ($relatedMap['mapid'] % 4 == 0) {
            $relatedMap['type'] = 'Town';
        } elseif ($relatedMap['mapid'] % 4 == 1) {
            $relatedMap['type'] = 'Field';
        } elseif ($relatedMap['mapid'] % 4 == 2) {
            $relatedMap['type'] = 'Dungeon';
        } else {
            $relatedMap['type'] = 'Raid';
        }
        
        // Use our HTML placeholder
        $relatedMap['image_path'] = 'maps/images/map_placeholder.html?id=' . $relatedMap['mapid'];
        
        $relatedMaps[] = $relatedMap;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($map['name']); ?> - L1J Remastered Database</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .map-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            margin-top: 20px;
        }
        
        @media (min-width: 992px) {
            .map-container {
                grid-template-columns: 2fr 1fr;
            }
        }
        
        .map-details {
            background: rgba(30, 30, 30, 0.7);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .map-image {
            height: 300px;
            background-color: #333;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .map-image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            font-size: 96px;
            color: rgba(255, 255, 255, 0.2);
        }
        
        .map-content {
            padding: 25px;
        }
        
        .map-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }
        
        .map-subtitle {
            font-size: 18px;
            color: #aaa;
            margin-bottom: 20px;
        }
        
        .map-description {
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .map-properties {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .map-property {
            background: rgba(0, 0, 0, 0.3);
            padding: 12px 15px;
            border-radius: 6px;
            display: flex;
            align-items: center;
        }
        
        .map-property i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .map-flags {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 20px;
        }
        
        .map-flag {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.2);
        }
        
        .map-flag i {
            margin-right: 10px;
            width: 16px;
            text-align: center;
        }
        
        .map-flag.enabled {
            color: #4caf50;
        }
        
        .map-flag.disabled {
            color: #f44336;
            text-decoration: line-through;
            opacity: 0.7;
        }
        
        .map-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .sidebar-section {
            background: rgba(30, 30, 30, 0.7);
            border-radius: 8px;
            padding: 20px;
        }
        
        .sidebar-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .related-maps {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .related-map {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 6px;
            overflow: hidden;
            transition: all 0.2s ease;
        }
        
        .related-map:hover {
            transform: translateY(-2px);
            background: rgba(0, 0, 0, 0.4);
        }
        
        .related-map-image {
            width: 60px;
            height: 60px;
            background-color: #333;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .related-map-image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            font-size: 24px;
            color: rgba(255, 255, 255, 0.2);
        }
        
        .related-map-content {
            padding: 10px 15px;
            flex-grow: 1;
        }
        
        .related-map-name {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .related-map-type {
            font-size: 12px;
            color: #aaa;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
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
        
        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
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
        <div class="breadcrumb">
            <a href="../index.php">Home</a> &gt;
            <a href="index.php">Maps</a> &gt;
            <span><?php echo htmlspecialchars($map['name']); ?></span>
        </div>
        
        <div class="map-container">
            <div class="map-details">
                <div class="map-image">
                    <iframe src="../<?php echo $map['image_path']; ?>" frameborder="0" scrolling="no" width="100%" height="100%"></iframe>
                </div>
                <div class="map-content">
                    <div class="map-title">
                        <?php echo htmlspecialchars($map['name']); ?>
                        <?php if ($map['is_safe_zone']): ?>
                        <span class="badge badge-safe">Safe Zone</span>
                        <?php endif; ?>
                        <?php if ($map['is_pvp']): ?>
                        <span class="badge badge-pvp">PvP Enabled</span>
                        <?php endif; ?>
                        <?php if ($map['is_instance']): ?>
                        <span class="badge badge-instance">Instance</span>
                        <?php endif; ?>
                    </div>
                    <div class="map-subtitle">
                        <?php echo htmlspecialchars($map['region']); ?> &bull; <?php echo htmlspecialchars($map['type']); ?>
                    </div>
                    
                    <div class="map-description">
                        <?php echo htmlspecialchars($map['description'] ?? 'No description available.'); ?>
                    </div>
                    
                    <h3>Map Properties</h3>
                    <div class="map-properties">
                        <div class="map-property">
                            <i class="fas fa-layer-group"></i>
                            <div>
                                <strong>Level Range</strong><br>
                                <?php echo $map['min_level']; ?> - <?php echo $map['max_level']; ?>
                            </div>
                        </div>
                        <div class="map-property">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Map ID</strong><br>
                                <?php echo $map['mapid']; ?>
                            </div>
                        </div>
                        <div class="map-property">
                            <i class="fas fa-globe"></i>
                            <div>
                                <strong>Region</strong><br>
                                <?php echo htmlspecialchars($map['region']); ?>
                            </div>
                        </div>
                        <div class="map-property">
                            <i class="fas fa-tag"></i>
                            <div>
                                <strong>Type</strong><br>
                                <?php echo htmlspecialchars($map['type']); ?>
                            </div>
                        </div>
                    </div>
                    
                    <h3>Map Flags</h3>
                    <div class="map-flags">
                        <?php foreach ($flags as $flag => $info): ?>
                        <div class="map-flag <?php echo $map[$flag] ? 'enabled' : 'disabled'; ?>">
                            <i class="fas <?php echo $info['icon']; ?>"></i>
                            <?php echo $info['name']; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <div class="actions">
                        <a href="../edit_record.php?table=mapids&id=<?php echo $map['mapid']; ?>&action=edit" class="btn">
                            <i class="fas fa-edit"></i> Edit Map
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="map-sidebar">
                <?php if (!empty($relatedMaps)): ?>
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Related Maps</h3>
                    <div class="related-maps">
                        <?php foreach ($relatedMaps as $relatedMap): ?>
                        <a href="view.php?id=<?php echo $relatedMap['mapid']; ?>" class="related-map">
                            <div class="related-map-image">
                                <iframe src="../<?php echo $relatedMap['image_path']; ?>" frameborder="0" scrolling="no" width="100%" height="100%"></iframe>
                            </div>
                            <div class="related-map-content">
                                <div class="related-map-name"><?php echo htmlspecialchars($relatedMap['name']); ?></div>
                                <div class="related-map-type"><?php echo htmlspecialchars($relatedMap['type']); ?></div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Back to Maps List</a></li>
                        <li><a href="../player.php">Player View</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="../view_table.php?table=mapids">View Maps Table</a></li>
                        <?php endif; ?>
                    </ul>
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
            console.log('Map detail view loaded');
        });
    </script>
</body>
</html>