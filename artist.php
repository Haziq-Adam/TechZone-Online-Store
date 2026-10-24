<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become Artist - NFT Distro</title>
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
            <a href="discover.php">Trending🔥</a>
            <a href="artist.php" class="active">Become Artist</a>
        </nav>
        
        <div style="display: flex; align-items: center; gap: 15px;">
            <span style="color: var(--text-muted);">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php" class="btn-secondary" style="padding: 8px 16px; border-radius: 20px;">Logout</a>
        </div>
    </header>

    <main class="section">
        <div class="artist-layout">
            <div class="artist-content">
                <span class="tag">NFT CREATOR</span>
                <h1 style="font-size: 3.5rem; margin-bottom: 20px;">Become an Artist</h1>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 40px; max-width: 500px;">
                    Welcome to the world of NFTs, Creator! We're excited to see your unique digital assets come to life. Create, share, and let your imagination run wild.
                </p>
                
                <h3 style="margin-bottom: 10px; font-size: 1.5rem;">Create your NFT</h3>
                <p style="color: var(--text-muted); margin-bottom: 30px; max-width: 400px; font-size: 0.95rem;">
                    Use a program such as Adobe Photoshop, Illustrator, or Procreate to create a digital asset. Make sure the image is in a supported file format (e.g. JPEG, PNG, GIF).
                </p>
                
                <button class="btn-primary" style="padding: 12px 30px; font-size: 1.1rem;">Explore Now</button>
            </div>
            
            <div class="artist-upload">
                <div class="upload-box" onclick="alert('File selector would open here')">
                    <div class="upload-icon">🖌️</div>
                    <h3 style="margin-bottom: 10px;">Drag and Drop your file</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">PNG, GIF, WEBP, MP4 or MP3. Max 1Gb.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Print Button (Exam Requirement d) -->
    <button class="print-btn" id="printBtn" title="Print Display">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
    </button>

    <script src="main.js"></script>
</body>
</html>
