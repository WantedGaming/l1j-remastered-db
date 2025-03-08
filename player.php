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
    <title>Lineage-R Database</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/player-style.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo-container">
                <a href="player.php" class="logo">
                    <i class="fas fa-database"></i> L1J-R DB
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="player.php" class="active">Home</a></li>
                    <li><a href="#">Items</a></li>
                    <li><a href="#">NPCs</a></li>
                    <li><a href="#">Quests</a></li>
                    <li><a href="#">Maps</a></li>
                    <li><a href="#">Tools</a></li>
                    <li><a href="index.php" class="admin-link">Admin</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="hero-section">
                <h1>Lineage-R Database</h1>
                <p>A comprehensive database for Lineage Remastered game.</p>
                <p class="hero-stats">Browse information about items, NPCs, quests, and more.</p>
                <div class="hero-buttons">
                    <a href="#" class="btn btn-primary">Browse Items</a>
                    <a href="#" class="btn btn-secondary">Browse NPCs</a>
                    <a href="#" class="btn btn-secondary">Browse Data</a>
                </div>
            </div>

            <div class="search-section">
                <h2>Quick Search</h2>
                <div class="search-container">
                    <input type="text" placeholder="Search for items, NPCs, quests, etc." class="search-input">
                    <select class="search-select">
                        <option value="all">All</option>
                        <option value="items">Items</option>
                        <option value="npcs">NPCs</option>
                        <option value="quests">Quests</option>
                    </select>
                    <button class="search-button">Search</button>
                </div>
            </div>

            <div class="content-grid">
                <div class="grid-section boss-timers">
                    <h2 class="section-title">Boss Timers</h2>
                    <div class="boss-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Boss</th>
                                    <th>Level</th>
                                    <th>Location</th>
                                    <th>Spawn Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Antharas</td>
                                    <td>85</td>
                                    <td>Dragon Valley</td>
                                    <td>12h</td>
                                </tr>
                                <tr>
                                    <td>Valakas</td>
                                    <td>90</td>
                                    <td>Forge of Gods</td>
                                    <td>24h</td>
                                </tr>
                                <tr>
                                    <td>Queen Ant</td>
                                    <td>60</td>
                                    <td>Ant Nest</td>
                                    <td>8h</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid-section db-stats">
                    <h2 class="section-title">Database Statistics</h2>
                    <div class="stats-grid">
                        <div class="stat-box">
                            <h3>Weapons</h3>
                            <div class="stat-value">578</div>
                        </div>
                        <div class="stat-box">
                            <h3>Armors</h3>
                            <div class="stat-value">1,346</div>
                        </div>
                        <div class="stat-box">
                            <h3>NPCs</h3>
                            <div class="stat-value">4,258</div>
                        </div>
                        <div class="stat-box">
                            <h3>Total Items</h3>
                            <div class="stat-value">5,972</div>
                        </div>
                        <div class="stat-box">
                            <h3>Total Maps</h3>
                            <div class="stat-value">86</div>
                        </div>
                        <div class="stat-box">
                            <h3>Quests</h3>
                            <div class="stat-value">3,567</div>
                        </div>
                    </div>
                    <div class="view-more">
                        <a href="#" class="btn btn-small">View More Statistics</a>
                    </div>
                </div>
            </div>

            <div class="content-grid three-columns">
                <div class="grid-section featured-items">
                    <h2 class="section-title">Featured Items</h2>
                    <div class="featured-list">
                        <div class="featured-item">
                            <div class="item-icon"><i class="fas fa-sword"></i></div>
                            <div class="item-details">
                                <h3>Dragon Slayer</h3>
                                <p>Legendary Two-Handed Sword</p>
                            </div>
                        </div>
                        <div class="featured-item">
                            <div class="item-icon"><i class="fas fa-shield"></i></div>
                            <div class="item-details">
                                <h3>Composite Plate Armor</h3>
                                <div class="item-description">Legendary Full Plate</div>
                            </div>
                        </div>
                        <div class="featured-item">
                            <div class="item-icon"><i class="fas fa-ring"></i></div>
                            <div class="item-details">
                                <h3>Necklace of Wisdom</h3>
                                <div class="item-description">Rare Neck Item</div>
                            </div>
                        </div>
                    </div>
                    <div class="view-all">
                        <a href="#" class="btn btn-small">View All Items</a>
                    </div>
                </div>

                <div class="grid-section featured-npcs">
                    <h2 class="section-title">Featured NPCs</h2>
                    <div class="featured-list">
                        <div class="featured-item">
                            <div class="item-icon"><i class="fas fa-dragon"></i></div>
                            <div class="item-details">
                                <h3>Ancient Dragon Baium</h3>
                                <div class="item-description">Level 80+ Raid Boss</div>
                            </div>
                        </div>
                        <div class="featured-item">
                            <div class="item-icon"><i class="fas fa-crown"></i></div>
                            <div class="item-details">
                                <h3>King Cadmus</h3>
                                <div class="item-description">Royal NPC, Quest Giver</div>
                            </div>
                        </div>
                        <div class="featured-item">
                            <div class="item-icon"><i class="fas fa-ghost"></i></div>
                            <div class="item-details">
                                <h3>Wraith Overlord</h3>
                                <div class="item-description">Elite Monster, Level 45</div>
                            </div>
                        </div>
                    </div>
                    <div class="view-all">
                        <a href="#" class="btn btn-small">View All NPCs</a>
                    </div>
                </div>

                <div class="grid-section quick-tools">
                    <h2 class="section-title">Quick Tools</h2>
                    <div class="tools-list">
                        <div class="tool-item">
                            <div class="tool-icon"><i class="fas fa-calculator"></i></div>
                            <div class="tool-name">Damage Calculator</div>
                        </div>
                        <div class="tool-item">
                            <div class="tool-icon"><i class="fas fa-chart-line"></i></div>
                            <div class="tool-name">XP Calculator</div>
                        </div>
                        <div class="tool-item">
                            <div class="tool-icon"><i class="fas fa-map-marked"></i></div>
                            <div class="tool-name">Interactive Map</div>
                        </div>
                        <div class="tool-item">
                            <div class="tool-icon"><i class="fas fa-clock"></i></div>
                            <div class="tool-name">Boss Timer</div>
                        </div>
                        <div class="tool-item">
                            <div class="tool-icon"><i class="fas fa-coins"></i></div>
                            <div class="tool-name">Wealth Tracker</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Lineage-R Remastered Database</h3>
                    <p>A comprehensive database for Lineage Remastered game.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Items</a></li>
                        <li><a href="#">NPCs</a></li>
                        <li><a href="#">Quests</a></li>
                        <li><a href="#">Maps</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Resources</h3>
                    <ul>
                        <li><a href="#">User Guide</a></li>
                        <li><a href="#">API</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">GitHub</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Lineage Remastered Database. All rights reserved and trademarks are property of their respective owners.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add any JavaScript functionality here
            
            // Example: Add hover effects to featured items
            const featuredItems = document.querySelectorAll('.featured-item');
            featuredItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.classList.add('hover');
                });
                
                item.addEventListener('mouseleave', function() {
                    this.classList.remove('hover');
                });
            });
        });
    </script>
</body>
</html>