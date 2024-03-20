<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beer Site - News</title>
  <link rel="stylesheet" href="styles/news.css">
  <link rel="stylesheet" href="styles/home.css">
</head>
<body>
<img src="img/beer-background.png" alt="" class="bc-background">
<div class="container">
  <div class="blog-card spring-fever">
    <div class="title-content">
      <h3>The Oldest Recipe in History</h3>
      <hr />
    </div><!-- /.title-content -->
    <div class="card-image">
      <img src="img/beer.png" alt="Beer">
    </div><!-- /.card-image -->
    <div class="card-info">
      Beer has been enjoyed by humanity for millennia, with the oldest known recipe dating back over 5,000 years. Found inscribed on a Sumerian clay tablet, the recipe outlines the brewing process for a beer-like beverage made from barley.
    </div><!-- /.card-info -->
    <div class="utility-info">
      <ul class="utility-list">
        <li class="date">03.8.2024</li>
      </ul>
    </div>
    <div class="gradient-overlay"></div>
    <div class="color-overlay"></div>
  </div>

  <div class="blog-card spring-fever">
    <div class="title-content">
      <h3>The Strongest Beer in the World</h3>
      <hr />
    </div><!-- /.title-content -->
    <div class="card-image">
      <img src="img/beer.png" alt="Beer">
    </div><!-- /.card-image -->
    <div class="card-info">
      For those who like their brews with a bit more kick, there's a beer out there that holds the title for the strongest beer in the world. Brewed by Scottish brewery Brewmeister, "Snake Venom" boasts an astonishing 67.5% alcohol by volume (ABV), making it stronger than many spirits.
    </div><!-- /.card-info -->
    <div class="utility-info">
      <ul class="utility-list">
        <li class="date">03.12.2024</li>
      </ul>
    </div>
    <div class="gradient-overlay"></div>
    <div class="color-overlay"></div>
  </div>

  <div class="blog-card spring-fever">
    <div class="title-content">
      <h3>A Beer Wave</h3>
      <hr />
    </div><!-- /.title-content -->
    <div class="card-image">
      <img src="img/beer.png" alt="Beer">
    </div><!-- /.card-image -->
    <div class="card-info">
      In 1814, London experienced a bizarre and tragic event known as the "London Beer Flood." When a massive vat containing over 135,000 gallons of beer ruptured at the Meux and Company Brewery, it sent a tidal wave of beer rushing through the streets, causing widespread damage and claiming the lives of eight people.
    </div><!-- /.card-info -->
    <div class="utility-info">
      <ul class="utility-list">
        <li class="date">03.20.2024</li>
      </ul>
    </div>
    <div class="gradient-overlay"></div>
    <div class="color-overlay"></div>
  </div>
</div>
</body>
</html>

<style>
  @import url(https://fonts.googleapis.com/css?family=Roboto:400,500,700);
  @import url(https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic);

  /* variables */
  .container {
    display: flex;
    justify-content: space-around;
    align-items: center;
    flex-wrap: wrap;
    margin-top: 50px;
  }

  .card-width {
    width: 350px;
  }

  .card-height {
    height: 500px;
  }

  .h-color {
    color: #9CC9E3;
  }

  .yellow {
    color: #D0BB57;
  }

  .txt-color {
    color: #DCE3E7;
  }

  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  .blog-card {
    width: 350px;
    height: 500px;
    position: relative; /* Changed position to relative */
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 3px 3px 20px rgba(0, 0, 0, 0.5);
    text-align: center;
    margin-bottom: 20px; /* Added margin for spacing */
  }

  .blog-card .card-image {
  width: 100%;
  height: 100%;
  background-image: url('img/beer-background.png'); /* Background image */
  background-size: cover; /* Cover the entire container */
  background-position: center center; /* Center the image */
  position: absolute;
  z-index: -1; /* Set z-index to be behind other elements */
}



  .blog-card .color-overlay {
    background: rgba(84, 104, 110, 0.4);
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 10;
    top: 0;
    left: 0;
    transition: background 0.3s cubic-bezier(0.33, 0.66, 0.66, 1);
  }

  .blog-card .gradient-overlay {
    background-image: -webkit-linear-gradient(rgba(0, 0, 0, 0.00) 0%, rgba(0, 0, 0, 0.6) 21%);
    background-image: -moz-linear-gradient(rgba(0, 0, 0, 0.00) 0%, rgba(0, 0, 0, 0.6) 21%);
    background-image: -o-linear-gradient(rgba(0, 0, 0, 0.00) 0%, rgba(0, 0, 0, 0.6) 21%);
    background-image: linear-gradient(rgba(0, 0, 0, 0.00) 0%, rgba(0, 0, 0, 0.6) 21%);
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 15;
  }

  .blog-card:hover .card-info {
    opacity: 1;
    bottom: 120px;
  }

  .blog-card:hover .color-overlay {
    background: rgba(84, 104, 110, 0.8);
  }

  .title-content {
    text-align: center;
    margin: 70px 0 0 0;
    position: absolute;
    z-index: 20;
    width: 100%;
    top : 0;
    left: 0;
  }

  h3 {
    font-size: 20px;
    font-weight: 500;
    letter-spacing: 2px;
    color: #9CC9E3;
    font-family: 'Roboto', sans-serif;
    margin-bottom: 0;
  }

  hr {
    width: 50px;
    height: 3px;
    margin: 20px auto;
    border: 0;
    background: #D0BB57;
  }

  .card-info {
    width: 100%;
    position: absolute;
    bottom: 100px;
    left: 0;
    margin: 0 auto;
    padding: 0 50px;
    color: #DCE3E7;
    font-family: 'Droid Serif', serif;
    font-style: 16px;
    line-height: 24px;
    z-index: 20;
    opacity: 0;
    transition: bottom 0.3s, opacity 0.3s cubic-bezier(0.33, 0.66, 0.66, 1);
  }

  .utility-info {
    position: absolute;
    bottom: 0px;
    left: 0;
    z-index: 20;
  }

  .utility-list {
    list-style-type: none;
    margin: 0 0 30px 20px;
    padding: 0;
    width: 100%;
  }

  .utility-list li {
    margin: 0 15px 0 0;
    padding: 0 0 0 22px;
    display: inline-block;
    color: #DCE3E7;
    font-family: 'Roboto', sans-serif;
  }

  .utility-list li.date {
    background: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/1765/icon-calendar.svg) no-repeat 0 0.1em;
  }
</style>
