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

// Check if table parameter is provided
if (!isset($_GET['table']) || empty($_GET['table'])) {
    header('Location: index.php');
    exit;
}

$tableName = sanitize($_GET['table']);
$format = isset($_GET['format']) ? sanitize($_GET['format']) : 'csv';

// Check if table exists
$tableExistsQuery = "SHOW TABLES LIKE '$tableName'";
$tableExistsResult = $conn->query($tableExistsQuery);

if ($tableExistsResult->num_rows === 0) {
    header('Location: index.php?error=Table not found');
    exit;
}

// Get table structure
$tableStructure = getTableStructure($tableName);
$columns = array_column($tableStructure, 'Field');

// Get all records
$query = "SELECT * FROM `$tableName`";
$result = $conn->query($query);

// Handle different export formats
switch ($format) {
    case 'csv':
        exportCSV($tableName, $columns, $result);
        break;
    case 'json':
        exportJSON($tableName, $result);
        break;
    case 'sql':
        exportSQL($tableName, $columns, $result, $tableStructure);
        break;
    default:
        header('Location: view_table.php?table=' . urlencode($tableName) . '&error=Invalid export format');
        exit;
}

/**
 * Export data as CSV
 */
function exportCSV($tableName, $columns, $result) {
    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $tableName . '_export_' . date('Y-m-d') . '.csv');
    
    // Create output stream
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for Excel compatibility
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Add header row
    fputcsv($output, $columns);
    
    // Add data rows
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
    }
    
    fclose($output);
    exit;
}

/**
 * Export data as JSON
 */
function exportJSON($tableName, $result) {
    // Set headers for JSON download
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $tableName . '_export_' . date('Y-m-d') . '.json');
    
    $data = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Export data as SQL INSERT statements
 */
function exportSQL($tableName, $columns, $result, $tableStructure) {
    // Set headers for SQL download
    header('Content-Type: text/plain; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $tableName . '_export_' . date('Y-m-d') . '.sql');
    
    // Start output with comments
    echo "-- L1J Remastered Database Export\n";
    echo "-- Table: $tableName\n";
    echo "-- Date: " . date('Y-m-d H:i:s') . "\n\n";
    
    // Add table structure
    echo "-- Table structure\n";
    echo "CREATE TABLE IF NOT EXISTS `$tableName` (\n";
    
    $columnDefs = [];
    $primaryKeys = [];
    
    foreach ($tableStructure as $column) {
        $columnDef = "  `{$column['Field']}` {$column['Type']}";
        
        if ($column['Null'] === 'NO') {
            $columnDef .= " NOT NULL";
        }
        
        if ($column['Default'] !== NULL) {
            $columnDef .= " DEFAULT '{$column['Default']}'";
        } elseif ($column['Null'] === 'YES') {
            $columnDef .= " DEFAULT NULL";
        }
        
        if ($column['Extra'] === 'auto_increment') {
            $columnDef .= " AUTO_INCREMENT";
        }
        
        $columnDefs[] = $columnDef;
        
        if ($column['Key'] === 'PRI') {
            $primaryKeys[] = $column['Field'];
        }
    }
    
    echo implode(",\n", $columnDefs);
    
    if (!empty($primaryKeys)) {
        echo ",\n  PRIMARY KEY (`" . implode("`, `", $primaryKeys) . "`)";
    }
    
    echo "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
    
    // Add data
    echo "-- Data\n";
    
    if ($result && $result->num_rows > 0) {
        // Reset result pointer
        $result->data_seek(0);
        
        while ($row = $result->fetch_assoc()) {
            $values = [];
            
            foreach ($columns as $column) {
                if ($row[$column] === NULL) {
                    $values[] = "NULL";
                } else {
                    $values[] = "'" . addslashes($row[$column]) . "'";
                }
            }
            
            echo "INSERT INTO `$tableName` (`" . implode("`, `", $columns) . "`) VALUES (" . implode(", ", $values) . ");\n";
        }
    }
    
    exit;
}
?>