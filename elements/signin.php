<?php
require '../config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // user inputs
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $stmt = $pdo->prepare("SELECT password_hash, salt FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        // Verify the password using the stored salt and hash
        $password_hash = md5($user['salt'] . $password);
        if ($password_hash === $user['password_hash']) {
            $_SESSION['username'] = $username;
            header("Location: ../home.php");
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Page</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>
    <main class="main-content">

        <div class="signup-container signin-container"> <!-- Use shared and specific classes -->
            <h1>Fishing World</h1>
            <h2>Sign In</h2>
            <form method="post" class="signup-form"> <!-- Reuse signup-form class -->
                <?php if (isset($error_message)): ?>
                    <p class="error"><?php echo $error_message; ?></p>
                <?php endif; ?>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required placeholder="Enter your username">

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">

                <button type="submit" class="signin-button">Sign In</button>
            </form>
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </main>
    
    <footer>
        <p>&copy; 2024 Fishing World. All Rights Reserved.</p>
    </footer>
</body>

</html>