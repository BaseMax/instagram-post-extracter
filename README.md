# Instagram Post Extractor

Instagram Post Extractor is a simple tool to collect and download images from Instagram posts using your browser and a Python script. Follow the steps below to extract and save images from any Instagram page.

## Installation

Clone the repository:

```bash
git clone https://github.com/BaseMax/instagram-post-extracter
cd instagram-post-extracter
```

## Step 0

Sign-in to your personal Instagram account.

## Step 1

Open your target Instagram page in your browser.

## Step 2

Run the following JavaScript code in your browser's developer console to collect image sources:

```javascript
const container = document.querySelector('div.x1n2onr6');
const imgElements = container.querySelectorAll('img');
const imgSrcs = Array.from(imgElements).map(img => img.src);
```

## Step 3

Run the following JavaScript code to collect unique image URLs as you scroll:

```javascript
const uniqueImgSrcs = new Set();
function collectImages() {
  const container = document.querySelector('div.x1n2onr6');
  if (!container) return;

  const imgElements = container.querySelectorAll('img');
  imgElements.forEach(img => {
    if (img.src && !uniqueImgSrcs.has(img.src)) {
      uniqueImgSrcs.add(img.src);
      console.log('New image:', img.src);
    }
  });
}
let scrollTimeout;
window.addEventListener('scroll', () => {
  if (scrollTimeout) return;
  scrollTimeout = setTimeout(() => {
    collectImages();
    scrollTimeout = null;
  }, 500);
});
collectImages();
```

## Step 4

Scroll through the page to load and display all posts. This will allow the tool to paginate and collect images from all posts on the page.

## Step 5

Run the following JavaScript code to convert the collected image URLs to a JSON format:

```javascript
const imgArray = Array.from(uniqueImgSrcs);
const json = JSON.stringify(imgArray, null, 2);
console.log(json);
```

## Step 6

To download the collected images as a JSON file, run the following JavaScript code:

```javascript
function downloadJSON(data, filename = 'images.json') {
  const blob = new Blob([data], { type: 'application/json' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = filename;
  a.click();
  URL.revokeObjectURL(url);
}

downloadJSON(json);
```

## Step 7

To automatically download all images from the generated JSON file, run the following Python script:

```bash
python dl.py
```

This will download all the images listed in the JSON file to your local system.

## License

MIT License

Copyright 2025, Max Base
