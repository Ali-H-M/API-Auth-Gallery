<?php
require '../config.php';

// Process the sign-up form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // user inputs
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $password = htmlspecialchars($_POST['password']);

    // Check if the username 
    $checkUsernameStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $checkUsernameStmt->execute(['username' => $username]);
    $usernameCount = $checkUsernameStmt->fetchColumn();

    // Check i the email is already taken
    $checkEmailStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $checkEmailStmt->execute(['email' => $email]);
    $emailCount = $checkEmailStmt->fetchColumn();

    if ($usernameCount > 0) {
        echo "<p class='error'>Error: Username already exists. Please choose a different one.</p>";
    } elseif ($emailCount > 0) {
        echo "<p class='error'>Error: Email already exists. Please use a different email.</p>";
    } else {
        // Generate a salt and hash the password
        $salt = bin2hex(random_bytes(8));
        $password_hash = md5($salt . $password);

        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, salt, email, phone_number) 
                               VALUES (:username, :password_hash, :salt, :email, :phone_number)");
        try {
            $stmt->execute([
                'username' => $username,
                'password_hash' => $password_hash,
                'salt' => $salt,
                'email' => $email,
                'phone_number' => $phone_number
            ]);
            echo "<p class='success'>Sign-up successful!</p>";
        } catch (PDOException $e) {
            echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
        }
    }
}
?>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>

    <body>
        <main class="main-content">
            <div class="signup-container">
                <h1>Fishing World</h1>
                <h2>Sign Up</h2>
                <form method="post" class="signup-form">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required placeholder="Enter your username">

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email address">

                    <label for="phone_number">Phone Number:</label>
                    <input type="tel" id="phone_number" name="phone_number" required
                        placeholder="Enter your phone number" pattern="[0-9]{8,11}" title="Enter a valid phone number">

                    <div class="button-row">
                        <button type="submit" class="signup-button">Sign Up</button>
                        <a href="signin.php" class="signin-link">
                            <button type="button" class="signin-button-up">Sign In</button>
                        </a>
                    </div>

                </form>
            </div>
        </main>

        <footer>
            <p>&copy; 2024 Fishing World. All Rights Reserved.</p>
        </footer>
    </body>
</body>

</html>