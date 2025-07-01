<?php
// Target URL of the .wpress file
$url = "https://foodfight.news/wp-content/ai1wm-backups/foodfight-news-20250701-100248-g2t882utx5l1-1695683485.wpress";

// Local path where file will be saved
$saveTo = __DIR__ . "/foodfight-news.wpress";

// Start download
$ch = curl_init($url);
$fp = fopen($saveTo, 'w');

if ($ch && $fp) {
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300); // Optional: 5 min timeout
    curl_exec($ch);

    if (curl_errno($ch)) {
        echo "❌ cURL error: " . curl_error($ch);
    } else {
        echo "✅ Download complete: foodfight-news.wpress";
    }

    curl_close($ch);
    fclose($fp);
} else {
    echo " Could not open remote or local file.";
}

