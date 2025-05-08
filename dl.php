<?php
$file = 'posts.json';
$outputDir = 'images';
$delaySeconds = 1;

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$data = json_decode(file_get_contents($file), true);

echo "Found " . count($data) . " image URLs.\n";

foreach ($data as $index => $url) {
    try {
        $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (empty($ext)) {
            $ext = 'jpg';
        }

        $filename = $outputDir . DIRECTORY_SEPARATOR . ($index + 1) . '.' . $ext;

        if (file_exists($filename) && filesize($filename) > 0) {
            echo "Skipping " . ($index + 1) . ": $filename already exists and is not empty.\n";
            continue;
        }

        echo "Downloading " . ($index + 1) . ": $url\n";

        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate, br');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36');
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'accept-language: en-US,en;q=0.9,fa;q=0.8,it;q=0.7',
            'cache-control: max-age=0',
            'priority: u=0, i',
            'sec-ch-ua: "Google Chrome";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'sec-fetch-dest: document',
            'sec-fetch-mode: navigate',
            'sec-fetch-site: none',
            'sec-fetch-user: ?1',
            'upgrade-insecure-requests: 1'
        ]);

        $imageData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($imageData === false || strlen($imageData) === 0 || $httpCode !== 200) {
            var_dump($imageData);
            var_dump($httpCode);
            throw new Exception("cURL error or empty response (HTTP $httpCode): " . curl_error($ch));
        }
        
        file_put_contents($filename, $imageData);
        
        curl_close($ch);

        echo "Downloaded $url to $filename\n";

        sleep($delaySeconds);
    } catch (Exception $e) {
        echo "Failed to download $url: " . $e->getMessage() . "\n";
    }
}
