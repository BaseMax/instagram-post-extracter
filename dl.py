import json
import os
import requests

FILE = 'posts.json'
OUTPUT_DIR = 'images'

os.makedirs(OUTPUT_DIR, exist_ok=True)

with open(FILE, 'r', encoding='utf-8') as f:
    data = json.load(f)

print(f"Found {len(data)} image URLs.")

for index, url in enumerate(data, start=1):
    try:
        print(f"Downloading {index}: {url}")
        response = requests.get(url, timeout=10)
        response.raise_for_status()
        
        ext = url.split('.')[-1].split('?')[0]
        filename = os.path.join(OUTPUT_DIR, f"{index:03d}.{ext}")
        with open(filename, 'wb') as img_file:
            img_file.write(response.content)
    except Exception as e:
        print(f"Failed to download {url}: {e}")
