<?php
// Target URL of the .wpress file
$url = "https://novela.xyz/wp-content/ai1wm-backups/yasinvlogs-com-20250202-030223-te2cape8pv6x-1717864095.wpress";

// Local path where file will be saved
$saveTo = __DIR__ . "/backup-novela.wpress";

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
        echo "✅ Download Complete";
    }

    curl_close($ch);
    fclose($fp);
} else {
    echo " Could not open remote or local file.";
}

// Example Download URL: https://sitename.com/wp-content/ai1wm-backups/download.php