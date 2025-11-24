<?php
$page_title = "LET Lab - Admin Login";
include_once 'includes/header.php';

// Check if user is already logged in
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
    if($_SESSION['role'] === 'admin'){
        header("location: admin_dashboard.php");
    } else {
        header("location: dashboard.php");
    }
    exit;
}

// Include database and user model
include_once 'config/database.php';
include_once 'models/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $user->username = trim($_POST["username"]);
    $user->password = trim($_POST["password"]);
    
    if($user->login()){
        session_start();
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = $user->id;
        $_SESSION["username"] = $user->username;
        $_SESSION["role"] = $user->role;
        
        // Redirect based on role
        if($user->role === 'admin'){
            header("location: admin_dashboard.php");
        } else {
            header("location: dashboard.php");
        }
    } else {
        $login_err = "Username atau password salah.";
    }
}
?>

<div class="login-wrapper">
    <div class="login-container">
        <!-- Header dengan Logo dan Judul -->
        <div class="login-header text-center mb-5">
            <div class="login-logo mb-4">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="login-title">
                <h1 class="main-title">INFORMATION AND LEARNING</h1>
                <h2 class="sub-title">ENGINEERING TECHNOLOGY</h2>
                
            </div>
        </div>
        
        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>
        
        <!-- Form Login -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-4">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>
            
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
            
            <button type="submit" class="btn btn-login w-100 mb-3">Login</button>
            
            <div class="text-center">
                <a href="index.php" class="back-link">Kembali ke Halaman Utama</a>
            </div>
        </form>
    </div>
</div>

