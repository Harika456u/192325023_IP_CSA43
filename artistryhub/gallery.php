<link rel="stylesheet" href="style.css">

<header><h1>Media Gallery</h1></header>

<div class="gallery">
    <img src="images/sunset.jpg" onclick="showImage(this.src)">
    <img src="images/ocean.jpg" onclick="showImage(this.src)">
    <img src="images/nature.jpg" onclick="showImage(this.src)">
    <img src="images/artist.jpg" onclick="showImage(this.src)">
</div>

<div id="preview" onclick="this.style.display='none'">
    <img id="largeImage">
</div>

<h2>Art Videos</h2>

<a href="https://www.youtube.com/results?search_query=art+painting"
   target="_blank" rel="noopener">
    Watch Painting Videos
</a>

<p><a href="index.php">Back to Home</a></p>

<script src="script.js"></script>