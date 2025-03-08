<?php
// Database connection configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'l1j_remastered');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

// Function to sanitize input data
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}

// Function to get table structure
function getTableStructure($tableName) {
    global $conn;
    $structure = [];
    
    $sql = "DESCRIBE $tableName";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $structure[] = $row;
        }
    }
    
    return $structure;
}

// Function to get all tables in the database
function getAllTables() {
    global $conn;
    $tables = [];
    
    $sql = "SHOW TABLES";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
    }
    
    return $tables;
}
?>