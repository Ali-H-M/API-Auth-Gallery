<?php
require 'config.php';
$photos = [];

$api_url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=919dd6ca4e101d12167c0cbb7d22b1d0&tags=fishing&format=json&nojsoncallback=1';
$photos_per_page = isset($_COOKIE['photos_per_page']) ? $_COOKIE['photos_per_page'] : 20;  // Default to 20 photos
$response = file_get_contents($api_url);
if ($response) {
    $data = json_decode($response, true);
    if (isset($data['photos']['photo'])) {
        foreach ($data['photos']['photo'] as $photo) {
            $photo_url = "https://farm" . $photo['farm'] . ".staticflickr.com/" . $photo['server'] . "/" . $photo['id'] . "_" . $photo['secret'] . ".jpg";
            $photos[] = [
                'id' => $photo['id'],
                'url' => $photo_url
            ];
        }
    }
}

$exclude_ids = ['54678087948', '54678086829'];  // IDs to skip 

// If the user selects a new number of photos, update the cookie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['photos_per_page'])) {
    $photos_per_page = $_POST['photos_per_page'];
    setcookie('photos_per_page', $photos_per_page, time() + (86400 * 30), "/");  // Store for 30 days
}
?>

<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: elements/signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="styles/home.css">
</head>

<body>
    <header class="header">
        <div class="container">
            <h1>Fishing World</h1>
            <a href="out.php" class="signout-button">Sign Out</a>
        </div>
    </header>

    <main class="main-content">
        <section class="welcome-section">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Discover stunning fishing photos from around the world.</p>
        </section>

        <!-- Photo preference form -->
        <form method="POST" class="photo-preference-form">
            <label for="photos_per_page">Choose how many photos to display:</label>
            <select name="photos_per_page" id="photos_per_page">
                <option value="20" <?php echo $photos_per_page == 20 ? 'selected' : ''; ?>>20</option>
                <option value="30" <?php echo $photos_per_page == 30 ? 'selected' : ''; ?>>30</option>
                <option value="40" <?php echo $photos_per_page == 40 ? 'selected' : ''; ?>>40</option>
            </select>
            <button type="submit">Save Preference</button>
        </form>

        <!-- Photo gallery -->
        <section class="photo-gallery-section">
            <h3>Latest Fishing Photos</h3>
            <div class="photo-gallery">
                <?php
                $count = 0;
                foreach ($photos as $photo) {
                    if ($count >= $photos_per_page)
                        break;
                    if (in_array($photo['id'], $exclude_ids))
                        continue;
                    ?>
                    <div class="photo-item">
                        <img src="<?php echo htmlspecialchars($photo['url']); ?>" alt="Fishing Photo" class="flickr-photo">
                    </div>
                    <?php $count++;
                } ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Fishing World. All Rights Reserved.</p>
    </footer>
</body>


</html>