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
$action = isset($_GET['action']) ? sanitize($_GET['action']) : '';
$id = isset($_GET['id']) ? sanitize($_GET['id']) : '';

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

// Initialize variables
$formData = [];
$errors = [];
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data
    foreach ($tableStructure as $column) {
        $field = $column['Field'];
        $formData[$field] = isset($_POST[$field]) ? $_POST[$field] : '';
        
        // Validate required fields (not NULL and no default)
        if ($column['Null'] === 'NO' && $column['Default'] === NULL && $column['Extra'] !== 'auto_increment') {
            if (empty($formData[$field])) {
                $errors[$field] = "The {$field} field is required.";
            }
        }
    }
    
    // If no errors, proceed with database operation
    if (empty($errors)) {
        if ($action === 'add') {
            // Build INSERT query
            $fields = [];
            $values = [];
            
            foreach ($formData as $field => $value) {
                // Skip auto-increment fields for insert
                $isAutoIncrement = false;
                foreach ($tableStructure as $column) {
                    if ($column['Field'] === $field && $column['Extra'] === 'auto_increment') {
                        $isAutoIncrement = true;
                        break;
                    }
                }
                
                if (!$isAutoIncrement || !empty($value)) {
                    $fields[] = "`$field`";
                    $values[] = "'" . sanitize($value) . "'";
                }
            }
            
            $fieldsStr = implode(', ', $fields);
            $valuesStr = implode(', ', $values);
            
            $query = "INSERT INTO `$tableName` ($fieldsStr) VALUES ($valuesStr)";
            
            if ($conn->query($query)) {
                header("Location: view_table.php?table=$tableName&success=Record added successfully");
                exit;
            } else {
                $errors['db'] = "Error adding record: " . $conn->error;
            }
        } elseif ($action === 'edit' && !empty($id)) {
            // Build UPDATE query
            $updates = [];
            
            foreach ($formData as $field => $value) {
                $updates[] = "`$field` = '" . sanitize($value) . "'";
            }
            
            $updatesStr = implode(', ', $updates);
            $idField = $primaryKeys[0]; // Use the first primary key for simplicity
            
            $query = "UPDATE `$tableName` SET $updatesStr WHERE `$idField` = '$id'";
            
            if ($conn->query($query)) {
                header("Location: view_table.php?table=$tableName&success=Record updated successfully");
                exit;
            } else {
                $errors['db'] = "Error updating record: " . $conn->error;
            }
        }
    }
} else {
    // If editing, fetch the current record data
    if ($action === 'edit' && !empty($id) && !empty($primaryKeys)) {
        $idField = $primaryKeys[0]; // Use the first primary key for simplicity
        $query = "SELECT * FROM `$tableName` WHERE `$idField` = '$id'";
        $result = $conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            $formData = $result->fetch_assoc();
        } else {
            header("Location: view_table.php?table=$tableName&error=Record not found");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $action === 'add' ? 'Add New' : 'Edit'; ?> Record - <?php echo htmlspecialchars($tableName); ?> - L1J Remastered Database</title>
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
                    <li class="user-info">
                        <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?><?php if ($isAdmin): ?> <span class="admin-badge">Admin</span><?php endif; ?></span>
                        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add New' : 'Edit'; ?> Record - <?php echo htmlspecialchars($tableName); ?></h1>
        
        <?php if (!empty($errors['db'])): ?>
            <div class="alert alert-danger">
                <?php echo $errors['db']; ?>
            </div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="post" action="">
                <?php foreach ($tableStructure as $column): ?>
                    <div class="form-group">
                        <label for="<?php echo $column['Field']; ?>">
                            <?php echo htmlspecialchars($column['Field']); ?>
                            <?php if ($column['Null'] === 'NO' && $column['Default'] === NULL && $column['Extra'] !== 'auto_increment'): ?>
                                <span class="required">*</span>
                            <?php endif; ?>
                        </label>
                        
                        <?php
                        $value = isset($formData[$column['Field']]) ? $formData[$column['Field']] : '';
                        $isAutoIncrement = $column['Extra'] === 'auto_increment';
                        $isReadOnly = $isAutoIncrement && $action === 'edit';
                        $isDisabled = $isAutoIncrement && $action === 'add';
                        
                        // Determine input type based on column type
                        $inputType = 'text';
                        $inputAttributes = '';
                        
                        if (strpos($column['Type'], 'int') !== false) {
                            $inputType = 'number';
                        } elseif (strpos($column['Type'], 'text') !== false || strpos($column['Type'], 'longtext') !== false) {
                            $inputType = 'textarea';
                        } elseif (strpos($column['Type'], 'date') !== false) {
                            $inputType = 'date';
                        } elseif (strpos($column['Type'], 'time') !== false) {
                            $inputType = 'time';
                        } elseif (strpos($column['Type'], 'datetime') !== false) {
                            $inputType = 'datetime-local';
                        } elseif (strpos($column['Type'], 'enum') !== false || strpos($column['Type'], 'set') !== false) {
                            $inputType = 'select';
                            // Extract options from enum/set type
                            preg_match("/enum\('(.*)'\)/", $column['Type'], $matches);
                            if (empty($matches)) {
                                preg_match("/set\('(.*)'\)/", $column['Type'], $matches);
                            }
                            if (!empty($matches)) {
                                $options = explode("','", $matches[1]);
                            }
                        }
                        ?>
                        
                        <?php if ($inputType === 'textarea'): ?>
                            <textarea 
                                id="<?php echo $column['Field']; ?>" 
                                name="<?php echo $column['Field']; ?>" 
                                <?php echo $isReadOnly ? 'readonly' : ''; ?> 
                                <?php echo $isDisabled ? 'disabled' : ''; ?>
                                rows="5"
                            ><?php echo htmlspecialchars($value); ?></textarea>
                        <?php elseif ($inputType === 'select'): ?>
                            <select 
                                id="<?php echo $column['Field']; ?>" 
                                name="<?php echo $column['Field']; ?>" 
                                <?php echo $isReadOnly ? 'readonly' : ''; ?> 
                                <?php echo $isDisabled ? 'disabled' : ''; ?>
                            >
                                <option value="">Select an option</option>
                                <?php foreach ($options as $option): ?>
                                    <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $value === $option ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($option); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <input 
                                type="<?php echo $inputType; ?>" 
                                id="<?php echo $column['Field']; ?>" 
                                name="<?php echo $column['Field']; ?>" 
                                value="<?php echo htmlspecialchars($value); ?>" 
                                <?php echo $isReadOnly ? 'readonly' : ''; ?> 
                                <?php echo $isDisabled ? 'disabled' : ''; ?>
                                <?php echo $inputAttributes; ?>
                            >
                        <?php endif; ?>
                        
                        <?php if (isset($errors[$column['Field']])): ?>
                            <div class="error-message"><?php echo $errors[$column['Field']]; ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                
                <div class="form-actions">
                    <button type="submit" class="btn">
                        <?php echo $action === 'add' ? 'Add Record' : 'Update Record'; ?>
                    </button>
                    <a href="view_table.php?table=<?php echo urlencode($tableName); ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
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
            console.log('Form loaded and parsed');
        });
    </script>
</body>
</html>