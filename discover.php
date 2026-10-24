<?php
session_start();
require 'db.php';

$sql = "SELECT * FROM nfts ORDER BY id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover - NFT Distro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <div class="dots">
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
            NFT Distro
        </div>
        
        <nav class="nav-links">
            <a href="index.php">Explore</a>
            <a href="discover.php" class="active">Trending🔥</a>
            <a href="artist.php">Become Artist</a>
        </nav>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <div style="display: flex; align-items: center; gap: 15px;">
                <span style="color: var(--text-muted);">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="logout.php" class="btn-secondary" style="padding: 8px 16px; border-radius: 20px;">Logout</a>
            </div>
        <?php else: ?>
            <a href="login.php" class="btn-primary">Login / Register</a>
        <?php endif; ?>
    </header>

    <main class="section">
        <div class="section-header" style="flex-direction: column; align-items: center; text-align: center; margin-bottom: 60px;">
            <h1 style="font-size: 3.5rem; margin-bottom: 20px;">Discover Amazing NFT's</h1>
            
            <div class="search-bar" style="width: 100%; max-width: 600px; padding: 15px 25px; border-radius: 30px;">
                <svg width="20" height="20" fill="none" stroke="var(--text-muted)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search your favorite art..." style="width: 100%; font-size: 1.1rem;">
            </div>
        </div>

        <div class="categories">
            <button class="cat-btn active">All Items</button>
            <button class="cat-btn">Collections</button>
            <button class="cat-btn">Gaming</button>
            <button class="cat-btn">Photography</button>
            <button class="cat-btn">Music</button>
            <button class="cat-btn">Art</button>
        </div>
        
        <div class="nft-grid">
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $hue = htmlspecialchars($row['hue_rotation']);
                    $title = htmlspecialchars($row['title']);
                    $creator = htmlspecialchars($row['creator_name']);
                    $price = number_format($row['price'], 3);
                    $hours = htmlspecialchars($row['ends_in_hours']);
                    
                    echo "
                    <div class='nft-card'>
                        <img src='hero.png' alt='NFT' class='nft-image' style='filter: hue-rotate({$hue}deg);'>
                        <div class='nft-info'>
                            <h3>{$title}</h3>
                            <div class='nft-creator'>
                                <img src='hero.png' alt='Creator' style='filter: hue-rotate({$hue}deg);'>
                                <span>{$creator}</span>
                            </div>
                        </div>
                        <div class='nft-footer'>
                            <div class='price'>
                                <span>Current Bid</span>
                                <strong>♦ {$price}</strong>
                            </div>
                            <div class='time'>
                                <span>Ends in</span>
                                <strong>{$hours}h 00m 00s</strong>
                            </div>
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "<p>No NFTs found.</p>";
            }
            ?>
        </div>
    </main>

    <!-- Print Button (Exam Requirement d) -->
    <button class="print-btn" id="printBtn" title="Print Display">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
    </button>

    <script src="main.js"></script>
</body>
</html>
