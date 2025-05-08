import json
import os
import requests
import time
import hashlib

FILE = 'posts.json'
OUTPUT_DIR = 'images'
DELAY_SECONDS = 2

os.makedirs(OUTPUT_DIR, exist_ok=True)

with open(FILE, 'r', encoding='utf-8') as f:
    data = json.load(f)

print(f"Found {len(data)} image URLs.")

HEADERS = {
    'accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'accept-language': 'en-US,en;q=0.9,fa;q=0.8,it;q=0.7',
    'cache-control': 'max-age=0',
    'priority': 'u=0, i',
    'sec-ch-ua': '"Google Chrome";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
    'sec-ch-ua-mobile': '?0',
    'sec-ch-ua-platform': '"Windows"',
    'sec-fetch-dest': 'document',
    'sec-fetch-mode': 'navigate',
    'sec-fetch-site': 'none',
    'sec-fetch-user': '?1',
    'upgrade-insecure-requests': '1',
    'user-agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'
}

for index, url in enumerate(data, start=1):
    try:
        print(f"Downloading {index}: {url}")
        response = requests.get(url, headers=HEADERS, timeout=10)
        response.raise_for_status()

        file_hash = str(index) + "_" + hashlib.md5(url.encode('utf-8')).hexdigest()
        ext = url.split('.')[-1].split('?')[0]
        if not ext:
            ext = 'jpg'

        filename = os.path.join(OUTPUT_DIR, f"{file_hash[:10]}.{ext}")
        with open(filename, 'wb') as img_file:
            img_file.write(response.content)

        time.sleep(DELAY_SECONDS)
    except Exception as e:
        print(f"Failed to download {url}: {e}")
