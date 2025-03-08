<?php
require_once 'includes/config.php';

// Initialize session
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to index page
    header("Location: index.php");
    exit;
}

// Initialize variables
$username = '';
$password = '';
$error = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? sanitize($_POST['username']) : '';
    $password = isset($_POST['password']) ? sanitize($_POST['password']) : '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Check if accounts table exists
        $tableExistsQuery = "SHOW TABLES LIKE 'accounts'";
        $tableExistsResult = $conn->query($tableExistsQuery);
        
        if ($tableExistsResult->num_rows === 0) {
            // Create accounts table if it doesn't exist
            $createTableQuery = "CREATE TABLE IF NOT EXISTS `accounts` (
                `login` VARCHAR(50) NOT NULL,
                `password` VARCHAR(50) NOT NULL,
                `access_level` INT NOT NULL DEFAULT 0,
                PRIMARY KEY (`login`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            
            if (!$conn->query($createTableQuery)) {
                $error = "Error creating accounts table: " . $conn->error;
            } else {
                // Insert sample accounts
                $insertAccountsQuery = "INSERT INTO `accounts` (`login`, `password`, `access_level`) VALUES
                    ('admin', 'admin123', 1),
                    ('user1', 'password1', 0),
                    ('user2', 'password2', 0);";
                
                if (!$conn->query($insertAccountsQuery)) {
                    $error = "Error inserting sample accounts: " . $conn->error;
                }
            }
        }
        
        if (empty($error)) {
            // Query for user
            $query = "SELECT * FROM `accounts` WHERE `login` = '$username' AND `password` = '$password'";
            $result = $conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                // Set session variables
                $_SESSION['user_id'] = $user['login'];
                $_SESSION['is_admin'] = ($user['access_level'] == 1);
                
                // Redirect to index page
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid username or password.";
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
    <title>Login - L1J Remastered Database</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 80px auto;
            background: var(--gradient-dark);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-logo i {
            font-size: 48px;
            color: var(--red-1);
            margin-bottom: 15px;
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 30px;
            color: var(--white);
            font-size: 24px;
            font-weight: 600;
        }
        
        .login-form .form-group {
            margin-bottom: 20px;
        }
        
        .login-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .login-form input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--black-3);
            border-radius: 4px;
            background-color: var(--black-3);
            color: var(--white);
            font-size: 16px;
        }
        
        .login-form input:focus {
            outline: none;
            border-color: var(--red-1);
        }
        
        .login-form .form-actions {
            margin-top: 30px;
        }
        
        .login-form .btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            text-align: center;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: var(--gray);
        }
        
        .login-footer a {
            color: var(--red-1);
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-logo">
                <i class="fas fa-database"></i>
                <h1 class="login-title">L1J Remastered Database</h1>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form class="login-form" method="post" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </div>
            </form>
            
            <div class="login-footer">
                <p>Don't have an account? Contact the administrator.</p>
                <p><a href="player.php">Return to Player View</a></p>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Login page loaded');
        });
    </script>
</body>
</html>