# instagram-post-extracter

## Step 0

Sign-in to your personal Instagram account.

## Step 1

Open your target Instagram page in your browser.

## Step 2

Run the following JS code:

```javascript
const container = document.querySelector('div.x1n2onr6');
const imgElements = container.querySelectorAll('img');
const imgSrcs = Array.from(imgElements).map(img => img.src);
```

## Step 3

Run the following JS code:

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

Start scrolling from up to end of the page and saw all posts one by one to paginate all pages.

## Step 5

Run the following JS code to convert to JSON:

```javascript
const imgArray = Array.from(uniqueImgSrcs);
const json = JSON.stringify(imgArray, null, 2);
console.log(json);
```

Copyright 2025, Max Base
