<!DOCTYPE html>
<html lang="en" data-accent-lock="page" data-accent="winter">

<!-- Mirrored from https://demo.gridgum.com/avavamotors/car-marketplace/index.html by HTTrack Website Copier/3.x [XR&CO], Tue, 06 Oct 2026 16:43:50 GMT -->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>
/* The accent travels with the visitor. A home page never takes a colour from
   anywhere — it wears its own season and publishes it, which is what the
   data-accent-lock attribute says. Every other page of the template omits
   that attribute and therefore inherits: first from the URL
   (?accent=winter|autumn, so a link can be shared), then from the home page
   the visitor came through. Runs before first paint, or the colour flashes. */
(function () {
  var root = document.documentElement;
  var accent = root.getAttribute('data-accent');

  if (!root.hasAttribute('data-accent-lock')) {
    try {
      var asked = new URLSearchParams(window.location.search).get('accent');
      var seen = window.localStorage.getItem('avava-accent');
      accent = (asked === 'winter' || asked === 'autumn') ? asked
             : (seen === 'winter' || seen === 'autumn') ? seen
             : accent;
    } catch (e) {}
  }

  try { window.localStorage.setItem('avava-accent', accent); } catch (e) {}
  root.setAttribute('data-accent', accent);
})();
</script>

<title>AL Jazeera AL Hamra UESD Cars Enam Ullah Ahmadi — Sharjah</title>
<meta name="description" content="AL Jazeera AL Hamra UESD Cars Enam Ullah Ahmadi. Al Jubail Street 72, Industrial Area-2, Sharjah, United Arab Emirates.">
<meta name="theme-color" content="#14161A">

<meta property="og:type" content="website">
<meta property="og:site_name" content="AL Jazeera AL Hamra UESD Cars Enam Ullah Ahmadi">
<meta property="og:title" content="AL Jazeera AL Hamra UESD Cars Enam Ullah Ahmadi — Sharjah">
<meta property="og:description" content="41 all-wheel-drive cars ready this week. 200-point report published before you ask for it. No buyer fees.">
<meta property="og:image" content="/frontend/assets/img/og-cover.jpg">
<meta name="twitter:card" content="summary_large_image">

<link rel="icon" href="/frontend/assets/img/favicon.svg" type="image/svg+xml">

<link rel="preconnect" href="https://fonts.googleapis.com/">
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800;900&amp;family=JetBrains+Mono:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link rel="preload" href="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=2400&q=80" as="image">
<link rel="stylesheet" href="/frontend/assets/vendor/leaflet/leaflet.css">
<link rel="stylesheet" href="/frontend/assets/css/style1ef4.css?v=5d78136b">
<style>
  .stock { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
  .stock__card { position: relative; display: block; height: 220px; border-radius: 20px; overflow: hidden; background: #14161a; color: #fff; }
  .stock__card img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
  .stock__card:hover img { transform: scale(1.04); }
  .stock__name { position: absolute; left: 0; right: 0; bottom: 0; margin: 0; padding: 36px 14px 14px; background: linear-gradient(transparent, rgba(10, 12, 16, .78)); font-size: 15px; font-weight: 700; }
  .brandlogo { display: flex; align-items: center; gap: 12px; color: inherit; text-decoration: none; }
  .brandlogo__mark { display: grid; place-items: center; width: 48px; height: 48px; flex: none; border-radius: 14px; background: #c6a15b; color: #14161a; font-weight: 800; letter-spacing: -.04em; font-size: 16px; }
  .brandlogo__line { display: block; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; line-height: 1.15; font-size: 12px; }
  .brandlogo__sub { display: block; margin-top: 3px; font-size: 11px; letter-spacing: .02em; opacity: .78; }
  .brandlogo--foot .brandlogo__mark { width: 72px; height: 72px; border-radius: 18px; font-size: 24px; }
  .brandlogo--foot .brandlogo__line { font-size: 18px; color: #fff; }
  .brandlogo--foot .brandlogo__sub { margin-top: 6px; font-size: 14px; color: rgba(255,255,255,.72); }
  .hero__photo { object-position: 72% center; }
  .preloader__brand { margin: 0 auto; max-width: 420px; color: #fff; font-size: 22px; font-weight: 800; line-height: 1.3; }
  .preloader__brand span { display: block; margin-top: 8px; font-size: 15px; font-weight: 600; opacity: .72; }
  .wordmark, .ctapanel__wordmark { font-size: clamp(42px, 7vw, 110px) !important; }
  @media (max-width: 980px) { .stock { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
  @media (max-width: 560px) { .stock { grid-template-columns: 1fr; } .stock__card { height: 240px; } }
</style>
<noscript><style>.preloader { display: none; }</style></noscript>
</head>
<body>

<!-- ============================================================ Preloader -->
<!-- The wordmark is drawn as an outline and fills with the page's accent as
     the assets arrive: lime on the winter pages, larch on the autumn ones,
     because the fill takes the accent custom property. Removed on load, and
     hidden entirely when JavaScript is off (see the noscript rule above). -->
<div class="preloader" id="preloader" aria-hidden="true">
  <div class="preloader__inner">
    <p class="preloader__brand">AL Jazeera AL Hamra UESD Cars<span>Enam Ullah Ahmadi</span></p>
    <svg width="0" height="0" aria-hidden="true" focusable="false"><rect id="pl-rect" x="0" y="51" width="1" height="1"></rect></svg>
    <p class="preloader__pct"><span id="preloader-pct">0</span>%</p>
  </div>
</div>

<a class="skip-link" href="#main">Skip to content</a>

<header class="hdr">

  <div class="hdr__utility">
    <ul class="util">
      <li class="util__item">
        <svg class="ico" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11z"></path><circle cx="12" cy="10" r="2.4"></circle></svg>
        Al Jubail Street 72, Industrial Area-2, Sharjah, UAE
      </li>
      <li class="util__sep" aria-hidden="true"></li>
      <li class="util__item"><span class="dot dot--accent" aria-hidden="true"></span>MON&ndash;SAT 09:00&ndash;19:00</li>
    </ul>

    <div class="hdr__utility-right">
      <ul class="social social--bare">
        <li><a href="#" aria-label="Showroom on Instagram"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" focusable="false"><rect x="3.5" y="3.5" width="17" height="17" rx="5"></rect><circle cx="12" cy="12" r="3.8"></circle><circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none"></circle></svg></a></li>
        <li><a href="#" aria-label="Showroom on Facebook"><svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M13 10h3l.5-3H13V5.5c0-.8.3-1.5 1.5-1.5H17V1.4C16.5 1.3 15.4 1 14.2 1 11.7 1 10 2.5 10 5v2H7v3h3v9h3z"></path></svg></a></li>
        <li><a href="#" aria-label="Showroom on YouTube"><svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8zM10 15V9l5.2 3z"></path></svg></a></li>
        <li><a href="#" aria-label="Showroom on X"><svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M22 5.9c-.7.3-1.5.5-2.3.6.8-.5 1.4-1.3 1.7-2.2-.8.4-1.6.8-2.5 1a4 4 0 0 0-6.8 3.6A11.3 11.3 0 0 1 3.9 4.6a4 4 0 0 0 1.2 5.3c-.6 0-1.2-.2-1.7-.5v.1a4 4 0 0 0 3.2 3.9c-.6.1-1.1.2-1.7.1a4 4 0 0 0 3.7 2.8A8 8 0 0 1 2 18.6a11.3 11.3 0 0 0 6.1 1.8c7.4 0 11.4-6.1 11.4-11.4v-.5c.8-.6 1.5-1.3 2-2.1z"></path></svg></a></li>
      </ul>
      <span class="util__sep" aria-hidden="true"></span>
      <span class="cur" role="group" aria-label="Currency"><button class="cur__btn cur__btn--on" type="button" data-cur="AED" aria-pressed="true">AED</button><span class="cur__sep" aria-hidden="true">&middot;</span><button class="cur__btn" type="button" data-cur="USD" aria-pressed="false">US$</button></span>
    </div>
  </div>

  <div class="hdr__bar">
    <a class="hdr__logo brandlogo" href="/"><span class="brandlogo__mark" aria-hidden="true">AJ</span><span class="brandlogo__text"><span class="brandlogo__line">AL Jazeera AL Hamra</span><span class="brandlogo__line">UESD Cars</span><span class="brandlogo__sub">Enam Ullah Ahmadi</span></span></a>

    <nav class="nav" id="nav" aria-label="Main">
      <ul class="nav__list">
        <li class="nav__item">
          <a class="nav__link" href="/">Home <svg class="nav__chev" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 9l7 7 7-7"></path></svg></a>
          <button class="nav__toggle" type="button" aria-expanded="false" aria-label="Show Home submenu"></button>
          <ul class="dd">
            <li><a class="drow" href="/">Home <span class="drow__tag">CURRENT</span></a></li>
            <li><a class="drow" href="home-v2.html">Home v2</a></li>
            <li><a class="drow" href="home-v3.html">Home v3</a></li>
            <li><a class="drow" href="home-v4.html">Home v4</a></li>
          </ul>
        </li>

        <li class="nav__item">
          <a class="nav__link" href="#">Pages <svg class="nav__chev" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 9l7 7 7-7"></path></svg></a>
          <button class="nav__toggle" type="button" aria-expanded="false" aria-label="Show Pages submenu"></button>
          <ul class="dd">
            <li><a class="drow" href="about.html">About Us</a></li>
            <li><a class="drow" href="service.html">Service</a></li>
            <li><a class="drow" href="pricing.html">Pricing</a></li>
            <li><a class="drow" href="compare.html">Compare</a></li>
            <li><a class="drow" href="faq.html">FAQ</a></li>
            <li><a class="drow" href="contact.html">Contact Us</a></li>
            <li><a class="drow" href="terms.html">Terms &amp; Conditions</a></li>
            <li><a class="drow" href="404.html">Error 404</a></li>
          </ul>
        </li>

        <li class="nav__item">
          <a class="nav__link" href="#">Listings <svg class="nav__chev" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 9l7 7 7-7"></path></svg></a>
          <button class="nav__toggle" type="button" aria-expanded="false" aria-label="Show Listings submenu"></button>
          <ul class="dd dd--wide">
            <li class="sub">
              <a class="drow" href="#">Listing Styles <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 6l6 6-6 6"></path></svg></a>
              <button class="nav__toggle nav__toggle--sub" type="button" aria-expanded="false" aria-label="Show Listing Styles submenu"></button>
              <ul class="flyout">
                <li><a class="drow" href="listing-v1.html">Listing v1</a></li>
                <li><a class="drow" href="listing-v2.html">Listing v2</a></li>
                <li><a class="drow" href="listing-v3.html">Listing v3</a></li>
                <li><a class="drow" href="listing-v4.html">Listing v4</a></li>
                <li><a class="drow" href="listing-v5.html">Listing v5</a></li>
                <li><a class="drow" href="listing-v6.html">Listing v6</a></li>
              </ul>
            </li>
            <li class="sub">
              <a class="drow" href="#">Listing Map <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 6l6 6-6 6"></path></svg></a>
              <button class="nav__toggle nav__toggle--sub" type="button" aria-expanded="false" aria-label="Show Listing Map submenu"></button>
              <ul class="flyout">
                <li><a class="drow" href="listing-map-v1.html">Listing Map v1</a></li>
                <li><a class="drow" href="listing-map-v2.html">Listing Map v2</a></li>
                <li><a class="drow" href="listing-map-v3.html">Listing Map v3</a></li>
                <li><a class="drow" href="listing-map-v4.html">Listing Map v4</a></li>
              </ul>
            </li>
            <li class="sub">
              <a class="drow" href="#">Listing Single <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 6l6 6-6 6"></path></svg></a>
              <button class="nav__toggle nav__toggle--sub" type="button" aria-expanded="false" aria-label="Show Listing Single submenu"></button>
              <ul class="flyout">
                <li><a class="drow" href="listing-single-v1.html">Listing Single v1</a></li>
                <li><a class="drow" href="listing-single-v2.html">Listing Single v2</a></li>
                <li><a class="drow" href="listing-single-v3.html">Listing Single v3</a></li>
                <li><a class="drow" href="listing-single-v4.html">Listing Single v4</a></li>
                <li><a class="drow" href="listing-single-v5.html">Listing Single v5</a></li>
                <li><a class="drow" href="#">Listing Single v6</a></li>
              </ul>
            </li>
          </ul>
        </li>

        <li class="nav__item">
          <a class="nav__link" href="#">Shop <svg class="nav__chev" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 9l7 7 7-7"></path></svg></a>
          <button class="nav__toggle" type="button" aria-expanded="false" aria-label="Show Shop submenu"></button>
          <ul class="dd">
            <li><a class="drow" href="shop.html">Shop</a></li>
            <li><a class="drow" href="shop-v2.html">Shop v2</a></li>
            <li><a class="drow" href="shop-v3.html">Shop v3</a></li>
            <li><a class="drow" href="shop-single-v1.html">Shop single v1</a></li><li><a class="drow" href="shop-single-v2.html">Shop single v2</a></li><li><a class="drow" href="shop-single-v3.html">Shop single v3</a></li>
            <li><a class="drow" href="cart.html">Cart</a></li>
            <li><a class="drow" href="checkout.html">Checkout</a></li>
          </ul>
        </li>

        <li class="nav__item">
          <a class="nav__link" href="#">Blog <svg class="nav__chev" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 9l7 7 7-7"></path></svg></a>
          <button class="nav__toggle" type="button" aria-expanded="false" aria-label="Show Blog submenu"></button>
          <ul class="dd">
            <li><a class="drow" href="blog.html">Blog list</a></li>
            <li><a class="drow" href="blog-single.html">Blog single</a></li>
          </ul>
        </li>

        <li class="nav__item">
          <a class="nav__link" href="#">Explore <svg class="nav__chev" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 9l7 7 7-7"></path></svg></a>
          <button class="nav__toggle" type="button" aria-expanded="false" aria-label="Show Explore submenu"></button>
          <ul class="dd dd--wide">
            <li class="sub">
              <a class="drow" href="#">User Dashboard <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 6l6 6-6 6"></path></svg></a>
              <button class="nav__toggle nav__toggle--sub" type="button" aria-expanded="false" aria-label="Show User Dashboard submenu"></button>
              <ul class="flyout">
                <li><a class="drow" href="profile.html">Profile</a></li>
                <li><a class="drow" href="saved-cars.html">Saved cars</a></li>
                <li><a class="drow" href="bookings.html">Bookings</a></li>
                <li><a class="drow" href="orders.html">Orders</a></li>
                <li><a class="drow" href="my-listings.html">My listings</a></li>
                <li><a class="drow" href="sell-my-car.html">Sell my car</a></li>
                <li><a class="drow" href="messages.html">Messages</a></li>
                <li><a class="drow" href="settings.html">Settings</a></li>
              </ul>
            </li>
            <li class="sub">
              <a class="drow" href="#">Dealer Dashboard <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 6l6 6-6 6"></path></svg></a>
              <button class="nav__toggle nav__toggle--sub" type="button" aria-expanded="false" aria-label="Show Dealer Dashboard submenu"></button>
              <ul class="flyout">
                <li><a class="drow" href="dealer-dashboard.html">Dashboard</a></li>
                <li><a class="drow" href="dealer-listings.html">My Listings</a></li>
                <li><a class="drow" href="dealer-add-listing.html">Add Listing</a></li>
                <li><a class="drow" href="dealer-enquiries.html">Enquiries</a></li>
                <li><a class="drow" href="dealer-bookings.html">Bookings</a></li>
                <li><a class="drow" href="dealer-orders.html">Orders</a></li>
                <li><a class="drow" href="dealer-analytics.html">Analytics</a></li>
                <li><a class="drow" href="dealer-reviews.html">Reviews</a></li>
                <li><a class="drow" href="dealer-team.html">Team</a></li>
                <li><a class="drow" href="dealer-payouts.html">Payouts</a></li>
                <li><a class="drow" href="dealer-invoices.html">Invoices</a></li>
                <li><a class="drow" href="dealer-profile.html">Dealer Profile</a></li>
                <li><a class="drow" href="dealer-settings.html">Settings</a></li>
              </ul>
            </li>
          </ul>
        </li>
      </ul>
    </nav>

    <div class="hdr__actions">
      <button class="iconbtn" type="button" aria-label="Search the inventory"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="6.5"></circle><path d="m20 20-4.6-4.6"></path></svg></button>
      <button class="iconbtn" type="button" id="account-btn" aria-label="Sign in to your account"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="8" r="3.6"></circle><path d="M5 20c1.1-3.2 3.8-4.8 7-4.8s5.9 1.6 7 4.8"></path></svg></button>
      <a class="btn btn--add" href="sell-my-car.html" aria-label="Add Listing"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M12 5v14M5 12h14"></path></svg><span class="btn__label">Add Listing</span></a>
      <a class="cartbtn" id="cart-btn" href="cart.html" hidden aria-label="Cart"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6 8h12l-1 12H7z"></path><path d="M9.5 8V6a2.5 2.5 0 015 0v2"></path></svg><span class="cartbtn__n" id="cart-n">0</span></a>
      <button class="burger" id="burger" type="button" aria-expanded="false" aria-controls="nav" aria-label="Open the menu"><span class="burger__bar"></span><span class="burger__bar"></span><span class="burger__bar"></span></button>
    </div>
  </div>
</header>

<main id="main">

  <!-- ============================================================ 1. Hero -->
  <section class="hero" id="hero" aria-labelledby="hero-title">
    <img class="hero__photo" src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=2400&q=80" width="2400" height="1600" alt="Sports car on an open road" fetchpriority="high">
    <div class="hero__scrim" aria-hidden="true"></div>

    <div class="hero__inner">
      <div class="hero__body">

        <div class="hero__copy">
          <div class="hero__chips">
            <span class="chip chip--accent">SHOWROOM 2026</span>
            <span class="chip chip--glass"><span class="dot dot--accent" aria-hidden="true"></span>17 CARS ON THE FLOOR</span>
          </div>

          <h1 class="hero__title" id="hero-title">The next drive<br>is already<br><span class="hero__title-out">in the showroom.</span></h1>

          <p class="hero__lead">These are the cars on the floor now, photographed<br>and ready to view before you book a drive.</p>

          <div class="hero__stats">
            <div class="hstat"><div class="hstat__num">17</div><div class="hstat__lbl">CARS ON THE FLOOR</div></div>
            <span class="hstat__sep" aria-hidden="true"></span>
            <div class="hstat"><div class="hstat__num">48h</div><div class="hstat__lbl">TO YOUR DOOR</div></div>
            <span class="hstat__sep" aria-hidden="true"></span>
            <div class="hstat"><div class="hstat__num hstat__num--glow">0%</div><div class="hstat__lbl">BUYER FEES</div></div>
          </div>

          <div class="hero__cta">
            <a class="btn btn--white btn--hero" href="#showroom">See the showroom<span class="btn__arrow" aria-hidden="true"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></a>
            <a class="btn btn--glass btn--hero" href="#showroom">Book a test drive</a>
          </div>
        </div>

        <aside class="livecard" aria-label="Live inventory">
          <div class="livecard__head">
            <span class="livecard__ghost" aria-hidden="true">17</span>
            <p class="livecard__live"><span class="dot dot--accent dot--8" aria-hidden="true"></span>SHOWROOM &middot; LIVE</p>
            <p class="livecard__count"><span class="livecard__num">17</span><span class="livecard__sub">cars photographed<br>and on the floor</span></p>
          </div>

          <div class="livecard__body">
            <div class="livecard__row">
              <span class="livecard__ico" aria-hidden="true"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.4"></circle><path d="M12 3.6v16.8M3.6 12h16.8M6 6l12 12M18 6L6 18"></path></svg></span>
              <div><div class="livecard__title">Winter tyres in the price</div><div class="livecard__note">Measured and dated &mdash; never an upsell at handover.</div></div>
            </div>
            <div class="livecard__row livecard__row--last">
              <span class="livecard__ico" aria-hidden="true"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>
              <div><div class="livecard__title">200-point report published</div><div class="livecard__note">Read every finding before you travel to see it.</div></div>
            </div>

            <div class="livecard__foot">
              <div>
                <div class="livecard__from">FROM</div>
                <div class="livecard__price">AED 385,600</div>
              </div>
              <a class="btn btn--ink btn--browse" href="#showroom">Browse 17<span class="btn__arrow" aria-hidden="true"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></a>
            </div>
          </div>
        </aside>
      </div>

      <div class="hero__searchwrap">
        <form class="search" action="#" method="get">
          <div class="search__quick">
            <span class="search__quicklbl">QUICK PICKS</span>
            <button class="qp qp--on" type="button" aria-pressed="true">In stock</button>
            <button class="qp" type="button" aria-pressed="false">Under AED 600k</button>
            <button class="qp" type="button" aria-pressed="false">Electric</button>
            <button class="qp" type="button" aria-pressed="false">Delivered in 48h</button>
            <span class="search__fresh"><span class="dot dot--accent" aria-hidden="true"></span>UPDATED 12 MIN AGO</span>
          </div>

          <div class="search__row">
            <p class="bsel">
              <label class="bsel__lbl" for="f-make">MAKE</label>
              <select class="bsel__sel" id="f-make" name="make"><option>All makes</option><option>Lexus</option><option>Toyota</option><option>Nissan</option><option>Mercedes-Benz</option><option>BMW</option><option>Land Rover</option><option>Kia</option><option>Hyundai</option></select>
              <svg class="bsel__chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 9l7 7 7-7"></path></svg>
            </p>
            <p class="bsel">
              <label class="bsel__lbl" for="f-body">BODY STYLE</label>
              <select class="bsel__sel" id="f-body" name="body"><option>All styles</option><option>Sport SUV</option><option>Coupe</option><option>Cabriolet</option><option>Estate</option></select>
              <svg class="bsel__chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 9l7 7 7-7"></path></svg>
            </p>
            <p class="bsel">
              <label class="bsel__lbl" for="f-drive">DRIVETRAIN</label>
              <select class="bsel__sel" id="f-drive" name="drivetrain"><option>All-wheel drive</option><option>Any drivetrain</option><option>Rear-wheel drive</option></select>
              <svg class="bsel__chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 9l7 7 7-7"></path></svg>
            </p>
            <p class="bsel">
              <label class="bsel__lbl" for="f-price">PRICE UP TO</label>
              <select class="bsel__sel" id="f-price" name="price"><option>Any price</option><option>AED 600,000</option><option>AED 800,000</option><option>AED 1,200,000</option></select>
              <svg class="bsel__chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 9l7 7 7-7"></path></svg>
            </p>
            <p class="bsel">
              <label class="bsel__lbl" for="f-loc">LOCATION</label>
              <select class="bsel__sel" id="f-loc" name="location"><option>Sharjah</option></select>
              <svg class="bsel__chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 9l7 7 7-7"></path></svg>
            </p>
            <div class="search__go">
              <button class="btn btn--ink btn--show" type="submit">Show 41 cars<span class="btn__arrow btn__arrow--36" aria-hidden="true"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- ======================================================= 2. Why AVAVA -->
  <section class="sec sec--why" aria-labelledby="why-title">
    <div class="sechead sechead--center">
      <p class="eyebrow"><span class="dot dot--accent dot--8" aria-hidden="true"></span>WHY THIS SHOWROOM</p>
      <h2 class="h2 h2--60" id="why-title">Built around the driver</h2>
      <p class="sechead__sub">AL Jazeera AL Hamra UESD Cars Enam Ullah Ahmadi, on Al Jubail Street in Sharjah.</p>
    </div>

    <div class="why__grid">
      <article class="wcard">
        <span class="wcard__badge wcard__badge--circle" aria-hidden="true"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="#14161A" stroke-width="1.8" stroke-linejoin="round"><path d="M12 3l7 9-7 9-7-9z"></path></svg></span>
        <h3 class="wcard__title">Curated collection</h3>
        <p class="wcard__text">Only the top 3% of the vehicles we inspect ever reach our showroom floor &mdash; no filler, no compromises.</p>
      </article>

      <article class="wcard wcard--ink">
        <span class="wcard__badge wcard__badge--diamond" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#14161A" stroke-width="1.8" stroke-linejoin="round"><path d="M4 4h8l8 8-8 8-8-8z"></path><circle cx="8" cy="8" r="1.6" fill="#14161A" stroke="none"></circle></svg></span>
        <h3 class="wcard__title">Transparent pricing</h3>
        <p class="wcard__text">One honest price with the full service history and finance terms shown up front. What you see is what you pay.</p>
      </article>

      <article class="wcard">
        <span class="wcard__badge wcard__badge--ring" aria-hidden="true"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="#14161A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v11M7 10l5 5 5-5M5 20h14"></path></svg></span>
        <h3 class="wcard__title">White-glove delivery</h3>
        <p class="wcard__text">Collected from Al Jubail Street 72, Industrial Area-2, Sharjah, or delivered to your door.</p>
      </article>

      <article class="wcard">
        <span class="wcard__badge wcard__badge--square" aria-hidden="true"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12l5 5L20 6"></path></svg></span>
        <h3 class="wcard__title">Certified inspection</h3>
        <p class="wcard__text">Every car passes a documented 200-point check, backed by a 12-month warranty.</p>
      </article>
    </div>

    <div class="numbers">
      <div class="numbers__cell"><div class="numbers__num">17</div><div class="numbers__lbl">VEHICLES IN STOCK</div></div>
      <div class="numbers__cell"><div class="numbers__num">1</div><div class="numbers__lbl">SHARJAH SHOWROOM</div></div>
      <div class="numbers__cell"><div class="numbers__num">4.9<span class="numbers__accent">/5</span></div><div class="numbers__lbl">2,400+ REVIEWS</div></div>
      <div class="numbers__cell"><div class="numbers__num">15</div><div class="numbers__lbl">YEARS OF TRUST</div></div>
    </div>
  </section>

  <hr class="rule">

  <!-- ================================================ 3. Featured listings -->
  <section class="sec" aria-labelledby="feat-title">
    <div class="sechead sechead--split">
      <div>
        <p class="eyebrow eyebrow--soft"><span class="dot dot--accent dot--8" aria-hidden="true"></span>FEATURED LISTINGS</p>
        <h2 class="h2 h2--fluid h2--nowrap" id="feat-title">Featured Listings</h2>
      </div>
      <a class="btn btn--ink btn--pill" href="#showroom">View all 17<span class="btn__arrow btn__arrow--34" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></a>
    </div>

    <div class="feat" id="featured">
      <a class="fcard fcard--tall" href="#showroom">
        <img class="fcard__img" src="/frontend/assets/img/showroom/mercedes-g-wagon.jpg" width="1200" height="800" alt="Mercedes-AMG G 63">
        <span class="fcard__flag">PICK OF THE WEEK</span>
        <span class="fcard__meta"><span class="fcard__name">Mercedes-AMG G 63</span><span class="fcard__spec">On the floor</span></span>
      </a>
      <button class="fcard" type="button">
        <img class="fcard__img" src="/frontend/assets/img/showroom/lexus-lx-green.jpg" width="1200" height="800" alt="Lexus LX">
        <span class="fcard__meta"><span class="fcard__name">Lexus LX</span><span class="fcard__spec">On the floor</span></span>
      </button>
      <button class="fcard" type="button">
        <img class="fcard__img" src="/frontend/assets/img/showroom/range-rover-gold.jpg" width="1200" height="800" alt="Range Rover">
        <span class="fcard__meta"><span class="fcard__name">Range Rover</span><span class="fcard__spec">On the floor</span></span>
      </button>
      <button class="fcard" type="button">
        <img class="fcard__img" src="/frontend/assets/img/showroom/bmw-7-series.jpg" width="1200" height="800" alt="BMW 7 Series">
        <span class="fcard__meta"><span class="fcard__name">BMW 7 Series</span><span class="fcard__spec">On the floor</span></span>
      </button>
      <button class="fcard" type="button">
        <img class="fcard__img" src="/frontend/assets/img/showroom/nissan-patrol-white.jpg" width="1200" height="800" alt="Nissan Patrol">
        <span class="fcard__meta"><span class="fcard__name">Nissan Patrol</span><span class="fcard__spec">On the floor</span></span>
      </button>
    </div>
  </section>

  <hr class="rule">

  <section class="sec" id="showroom" aria-labelledby="showroom-title">
    <div class="sechead">
      <p class="eyebrow eyebrow--soft"><span class="dot dot--accent dot--8" aria-hidden="true"></span>SHOWROOM</p>
      <h2 class="h2 h2--fluid" id="showroom-title">Cars on the floor</h2>
    </div>
    <div class="stock">
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/lexus-rx-white.jpg" alt="Lexus RX"><span class="stock__name">Lexus RX</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/lexus-lx-green.jpg" alt="Lexus LX"><span class="stock__name">Lexus LX</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/lexus-gx-beige.jpg" alt="Lexus GX"><span class="stock__name">Lexus GX</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/lexus-nx-copper.jpg" alt="Lexus NX"><span class="stock__name">Lexus NX</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/lexus-tx-silver.jpg" alt="Lexus TX"><span class="stock__name">Lexus TX</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/lexus-sedan-copper.jpg" alt="Lexus sedan"><span class="stock__name">Lexus Sedan</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/lexus-front-silver.jpg" alt="Lexus"><span class="stock__name">Lexus</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/toyota-land-cruiser.jpg" alt="Toyota Land Cruiser"><span class="stock__name">Toyota Land Cruiser</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/toyota-camry-white.jpg" alt="Toyota Camry"><span class="stock__name">Toyota Camry</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/toyota-corolla-cross.jpg" alt="Toyota Corolla Cross"><span class="stock__name">Toyota Corolla Cross</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/nissan-patrol-white.jpg" alt="Nissan Patrol"><span class="stock__name">Nissan Patrol</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/nissan-rogue-grey.jpg" alt="Nissan Rogue"><span class="stock__name">Nissan Rogue</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/mercedes-g-wagon.jpg" alt="Mercedes-AMG G 63"><span class="stock__name">Mercedes-AMG G 63</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/bmw-7-series.jpg" alt="BMW 7 Series"><span class="stock__name">BMW 7 Series</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/range-rover-gold.jpg" alt="Range Rover"><span class="stock__name">Range Rover</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/kia-k5-white.jpg" alt="Kia K5"><span class="stock__name">Kia K5</span></a>
      <a class="stock__card" href="#showroom"><img src="/frontend/assets/img/showroom/hyundai-palisade-black.jpg" alt="Hyundai Palisade"><span class="stock__name">Hyundai Palisade</span></a>
    </div>
  </section>

  <hr class="rule">

  <!-- ==================================================== 4. How it works -->
  <section class="sec" aria-labelledby="how-title">
    <div class="sechead sechead--split sechead--wide">
      <div>
        <p class="eyebrow"><span class="dot dot--accent dot--8" aria-hidden="true"></span>HOW IT WORKS</p>
        <h2 class="h2 h2--fluid h2--nowrap" id="how-title">Twelve days, stop at any of them.</h2>
      </div>
      <div class="minifacts">
        <div class="minifact"><div class="minifact__num">12</div><div class="minifact__lbl">DAYS, TYPICAL</div></div>
        <span class="minifact__sep" aria-hidden="true"></span>
        <div class="minifact"><div class="minifact__num">0</div><div class="minifact__lbl">CALLS REQUIRED</div></div>
        <span class="minifact__sep" aria-hidden="true"></span>
        <div class="minifact"><div class="minifact__num minifact__num--accent">7</div><div class="minifact__lbl">DAYS TO RETURN</div></div>
      </div>
    </div>

    <div class="steps" id="steps">
      <span class="steps__track" aria-hidden="true"></span>
      <span class="steps__fill" aria-hidden="true"></span>

      <ol class="steps__grid">
        <li class="step step--on">
          <button class="step__day" type="button" aria-pressed="true">DAY 1<span class="visually-hidden"> — Find it</span></button>
          <span class="step__node" aria-hidden="true"></span>
          <div class="step__body">
            <p class="step__kicker"><span>FILTER</span><span class="step__rule"></span></p>
            <h3 class="step__title">Find it</h3>
            <p class="step__text">Filter 1,840 cars from 380 verified dealers. Every listing shows the real price &mdash; no enquiry required to see it.</p>
            <span class="step__tag"><span class="dot dot--accent dot--6" aria-hidden="true"></span>2 MIN</span>
          </div>
        </li>
        <li class="step">
          <button class="step__day" type="button" aria-pressed="false">DAY 2<span class="visually-hidden"> — Read the report</span></button>
          <span class="step__node" aria-hidden="true"></span>
          <div class="step__body">
            <p class="step__kicker"><span>INSPECT</span><span class="step__rule"></span></p>
            <h3 class="step__title">Read the report</h3>
            <p class="step__text">The full 200-point inspection lands in your inbox before a salesperson calls, including what we chose to leave alone.</p>
            <span class="step__tag"><span class="dot dot--grey dot--6" aria-hidden="true"></span>SAME DAY</span>
          </div>
        </li>
        <li class="step">
          <button class="step__day" type="button" aria-pressed="false">DAY 5<span class="visually-hidden"> — Take it out</span></button>
          <span class="step__node" aria-hidden="true"></span>
          <div class="step__body">
            <p class="step__kicker"><span>DRIVE</span><span class="step__rule"></span></p>
            <h3 class="step__title">Take it out</h3>
            <p class="step__text">Book a viewing at Al Jubail Street 72, Industrial Area-2, Sharjah, or have the car brought to your door for the drive.</p>
            <span class="step__tag"><span class="dot dot--grey dot--6" aria-hidden="true"></span>48 H</span>
          </div>
        </li>
        <li class="step">
          <button class="step__day" type="button" aria-pressed="false">DAY 12<span class="visually-hidden"> — Drive it home</span></button>
          <span class="step__node" aria-hidden="true"></span>
          <div class="step__body">
            <p class="step__kicker"><span>KEYS</span><span class="step__rule"></span></p>
            <h3 class="step__title">Drive it home</h3>
            <p class="step__text">Seven days or 300 km to change your mind. Trade-in settled the same day, paperwork handled by us.</p>
            <span class="step__tag"><span class="dot dot--grey dot--6" aria-hidden="true"></span>7-DAY RETURN</span>
          </div>
        </li>
      </ol>
    </div>

    <div class="secfoot">
      <p class="secfoot__text">Nothing here is binding until the twelfth day &mdash; and after it, you still have a week to bring the car back.</p>
      <a class="btn btn--ink btn--pill" href="#">Start at day one<span class="btn__arrow btn__arrow--34" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></a>
    </div>
  </section>

  <hr class="rule">

  <!-- ========================================================== 5. Finance -->
  <section class="sec" id="finance" aria-labelledby="fin-title">
    <div class="sechead sechead--split sechead--fin">
      <div>
        <p class="eyebrow"><span class="dot dot--accent dot--8" aria-hidden="true"></span>FINANCE</p>
        <h2 class="h2 h2--fin h2--nowrap" id="fin-title">Three ways to pay for it.</h2>
        <p class="sechead__lead">An AED 659,600 car. Same rate on all three &mdash; the only thing that changes is how long you carry it, and what that costs you.</p>
      </div>
      <div class="minifacts">
        <div class="minifact"><div class="minifact__num minifact__num--30" id="apr-fact">3.9%</div><div class="minifact__lbl">APR, FIXED</div></div>
        <span class="minifact__sep minifact__sep--38" aria-hidden="true"></span>
        <div class="minifact"><div class="minifact__num minifact__num--30">&euro;0</div><div class="minifact__lbl">ARRANGEMENT FEE</div></div>
        <span class="minifact__sep minifact__sep--38" aria-hidden="true"></span>
        <div class="minifact"><div class="minifact__num minifact__num--30 minifact__num--accent">0%</div><div class="minifact__lbl">EARLY SETTLEMENT</div></div>
      </div>
    </div>

    <div class="depbar">
      <div class="depbar__row">
        <div class="depbar__value">
          <div class="depbar__lbl">DEPOSIT</div>
          <div class="depbar__amount" id="dep-out">&euro;30,000</div>
        </div>
        <div class="depbar__slider">
          <label class="visually-hidden" for="dep">Deposit</label>
          <input class="range" id="dep" type="range" min="0" max="80000" step="1000" value="30000">
          <div class="depbar__scale"><span>&euro;0</span><span>&euro;80,000</span></div>
        </div>
        <div class="depbar__shortcuts">
          <span class="depbar__or">OR USE</span>
          <button class="dep-btn dep-btn--trade" id="dep-trade" type="button" aria-pressed="false">My trade-in &middot; &euro;61,400</button>
          <button class="dep-btn" id="dep-zero" type="button" aria-pressed="false">Nothing down</button>
        </div>
      </div>

      <div class="depbar__row">
        <div class="depbar__value">
          <div class="depbar__lbl">INTEREST RATE</div>
          <div class="depbar__amount" id="apr-out">3.9%</div>
        </div>
        <div class="depbar__slider">
          <label class="visually-hidden" for="apr">Annual interest rate</label>
          <input class="range" id="apr" type="range" min="0.9" max="9.9" step="0.1" value="3.9">
          <div class="depbar__scale"><span>0.9%</span><span>9.9%</span></div>
        </div>
        <div class="depbar__shortcuts">
          <span class="depbar__or">OR PICK</span>
          <button class="dep-btn dep-btn--trade" id="apr-standard" type="button" aria-pressed="true" data-apr="3.9">Standard &middot; 3.9%</button>
          <button class="dep-btn" id="apr-partner" type="button" aria-pressed="false" data-apr="2.4">Partner &middot; 2.4%</button>
        </div>
      </div>
    </div>

    <div class="plans" id="plans">
      <div class="plan" data-term="36">
        <span class="plan__flag">MOST CHOSEN</span>
        <div class="plan__head"><span class="plan__term">36 MONTHS</span><span class="plan__who">PAY IT OFF FAST</span></div>
        <p class="plan__price"><span class="plan__monthly">&euro;0</span><span class="plan__per">/ mo</span></p>
        <p class="plan__tradeoff">&nbsp;</p>
        <div class="plan__interest">
          <div class="plan__interest-head"><span class="plan__interest-lbl">INTEREST YOU PAY</span><span class="plan__interest-val">&euro;0</span></div>
          <div class="plan__bar"><span class="plan__barfill"></span></div>
        </div>
        <div class="plan__foot">
          <div class="plan__total"><span>Total paid</span><span class="plan__total-val">&euro;0</span></div>
          <button class="plan__cta" type="button" aria-pressed="false" data-pick="36">Choose 36 months</button>
        </div>
      </div>

      <div class="plan plan--popular plan--picked" data-term="60">
        <span class="plan__flag">MOST CHOSEN</span>
        <div class="plan__head"><span class="plan__term">60 MONTHS</span><span class="plan__who">THE BALANCE</span></div>
        <p class="plan__price"><span class="plan__monthly">&euro;0</span><span class="plan__per">/ mo</span></p>
        <p class="plan__tradeoff">&nbsp;</p>
        <div class="plan__interest">
          <div class="plan__interest-head"><span class="plan__interest-lbl">INTEREST YOU PAY</span><span class="plan__interest-val">&euro;0</span></div>
          <div class="plan__bar"><span class="plan__barfill"></span></div>
        </div>
        <div class="plan__foot">
          <div class="plan__total"><span>Total paid</span><span class="plan__total-val">&euro;0</span></div>
          <button class="plan__cta" type="button" aria-pressed="true" data-pick="60">Choose 60 months</button>
        </div>
      </div>

      <div class="plan" data-term="72">
        <span class="plan__flag">MOST CHOSEN</span>
        <div class="plan__head"><span class="plan__term">72 MONTHS</span><span class="plan__who">LOWEST MONTHLY</span></div>
        <p class="plan__price"><span class="plan__monthly">&euro;0</span><span class="plan__per">/ mo</span></p>
        <p class="plan__tradeoff">&nbsp;</p>
        <div class="plan__interest">
          <div class="plan__interest-head"><span class="plan__interest-lbl">INTEREST YOU PAY</span><span class="plan__interest-val">&euro;0</span></div>
          <div class="plan__bar"><span class="plan__barfill"></span></div>
        </div>
        <div class="plan__foot">
          <div class="plan__total"><span>Total paid</span><span class="plan__total-val">&euro;0</span></div>
          <button class="plan__cta" type="button" aria-pressed="false" data-pick="72">Choose 72 months</button>
        </div>
      </div>
    </div>

    <div class="secfoot secfoot--fin">
      <div class="finnotes">
        <span class="finnote"><span class="finnote__tick" aria-hidden="true"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Settle early and the interest simply stops</span>
        <span class="finnote"><span class="finnote__tick" aria-hidden="true"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>A soft check that leaves no mark</span>
      </div>
      <a class="textlink" href="#">See the full agreement<span class="textlink__arrow" aria-hidden="true">&rarr;</span></a>
    </div>
  </section>

  <hr class="rule">

  <!-- ========================================================== 6. Trade in -->
  <section class="sec" id="trade-in" aria-labelledby="trade-title">
    <div class="sechead sechead--trade">
      <p class="eyebrow"><span class="dot dot--accent dot--8" aria-hidden="true"></span>TRADE-IN &middot; INSTANT OFFER</p>
      <h2 class="h2 h2--60 h2--tight" id="trade-title">Your car is worth more than you think</h2>
    </div>

    <div class="trade">
      <div class="trade__panel">
        <div class="trade__quote">
          <p class="trade__lbl">ESTIMATED OFFER RANGE</p>
          <p class="trade__range"><span id="trade-low">&euro;0</span><span class="trade__dash"> &ndash; </span><span id="trade-high">&euro;0</span></p>
          <p class="trade__desc" id="trade-desc">Kia K5 &middot; on the floor</p>
        </div>
        <img class="trade__car" src="/frontend/assets/img/showroom/kia-k5-white.jpg" width="1200" height="700" alt="Kia K5">
        <a class="btn btn--ink btn--accept" href="#">Accept offer<span class="btn__arrow btn__arrow--32" aria-hidden="true"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></a>
      </div>

      <div class="trade__form">
        <div class="trade__fields">
          <p class="field"><label class="field__lbl" for="t-make">MAKE</label><input class="field__in" id="t-make" type="text" value="Kia"></p>
          <p class="field"><label class="field__lbl" for="t-model">MODEL</label><input class="field__in" id="t-model" type="text" value="K5"></p>
          <p class="field"><label class="field__lbl" for="t-year">YEAR</label><input class="field__in" id="t-year" type="text" inputmode="numeric" value="2019"></p>
          <p class="field"><label class="field__lbl" for="t-mileage">MILEAGE</label><input class="field__in" id="t-mileage" type="text" inputmode="numeric" value="48,000 mi"></p>
        </div>

        <div class="gaugebox">
          <div class="gauge" id="gauge">
            <div class="gauge__hole"><div class="gauge__num" id="cond-num">2/4</div><div class="gauge__lbl">CONDITION</div></div>
          </div>
          <div class="gaugebox__control">
            <div class="gaugebox__title"><span id="cond-label">Good</span> condition</div>
            <label class="visually-hidden" for="t-cond">Condition</label>
            <input class="range range--cond" id="t-cond" type="range" min="1" max="4" step="1" value="2">
            <div class="gaugebox__scale"><span>Fair</span><span>Good</span><span>Great</span><span>Excel.</span></div>
          </div>
        </div>

        <div class="drivers">
          <p class="drivers__lbl">VALUE DRIVERS</p>
          <div class="driver">
            <div class="driver__head"><span>Model demand</span><span class="driver__val">High</span></div>
            <div class="driver__bar"><span class="driver__fill driver__fill--accent driver__fill--86"></span></div>
          </div>
          <div class="driver">
            <div class="driver__head"><span>Mileage</span><span class="driver__val" id="mileage-band">Average</span></div>
            <div class="driver__bar"><span class="driver__fill" id="mileage-bar"></span></div>
          </div>
          <div class="driver driver--last">
            <div class="driver__head"><span>Condition</span><span class="driver__val" id="cond-word">Good</span></div>
            <div class="driver__bar"><span class="driver__fill" id="cond-bar"></span></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <hr class="rule">

  <!-- ===================================================== 7. Testimonials -->
  <section class="sec sec--flush" aria-labelledby="rev-title">
    <div class="sechead sechead--center sechead--pad">
      <p class="eyebrow"><span class="dot dot--accent dot--8" aria-hidden="true"></span>TESTIMONIALS</p>
      <h2 class="h2 h2--60" id="rev-title">Rated 4.9 by real owners</h2>
      <p class="stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</p>
      <p class="revcount">2,431 VERIFIED REVIEWS</p>
    </div>

    <div class="marquee" id="reviews">
      <div class="marquee__track">
        <figure class="rev">
          <div class="rev__stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
          <blockquote class="rev__quote"><p>&ldquo;On my driveway in four days, spotless and charged. Unreal service.&rdquo;</p></blockquote>
          <figcaption class="rev__by"><img class="rev__av" src="/frontend/assets/img/avatar-daniel.webp" width="144" height="144" alt="" loading="lazy"><span><span class="rev__name">Daniel R.</span><span class="rev__car">Cayenne Coupe &middot; Sharjah</span></span></figcaption>
        </figure>
        <figure class="rev rev--ink">
          <div class="rev__stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
          <blockquote class="rev__quote"><p>&ldquo;The most transparent purchase I&rsquo;ve ever made. No surprises at all.&rdquo;</p></blockquote>
          <figcaption class="rev__by"><img class="rev__av" src="/frontend/assets/img/avatar-sofia.webp" width="144" height="144" alt="" loading="lazy"><span><span class="rev__name">Sofia M.</span><span class="rev__car">Taycan Turbo GT &middot; Sharjah</span></span></figcaption>
        </figure>
        <figure class="rev">
          <div class="rev__stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
          <blockquote class="rev__quote"><p>&ldquo;Trade-in beat every other offer I got. Finance took ten minutes.&rdquo;</p></blockquote>
          <figcaption class="rev__by"><img class="rev__av" src="/frontend/assets/img/avatar-aisha.webp" width="144" height="144" alt="" loading="lazy"><span><span class="rev__name">Aisha K.</span><span class="rev__car">Macan Electric &middot; Sharjah</span></span></figcaption>
        </figure>
        <figure class="rev">
          <div class="rev__stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
          <blockquote class="rev__quote"><p>&ldquo;They walked me through every feature. Customer for life.&rdquo;</p></blockquote>
          <figcaption class="rev__by"><img class="rev__av" src="/frontend/assets/img/avatar-marco.webp" width="144" height="144" alt="" loading="lazy"><span><span class="rev__name">Marco L.</span><span class="rev__car">Panamera GTS &middot; Sharjah</span></span></figcaption>
        </figure>
        <figure class="rev rev--ink">
          <div class="rev__stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
          <blockquote class="rev__quote"><p>&ldquo;White-glove delivery is exactly that. Handled all the paperwork.&rdquo;</p></blockquote>
          <figcaption class="rev__by"><img class="rev__av" src="/frontend/assets/img/avatar-james.webp" width="144" height="144" alt="" loading="lazy"><span><span class="rev__name">James P.</span><span class="rev__car">911 Carrera &middot; Sharjah</span></span></figcaption>
        </figure>
        <figure class="rev">
          <div class="rev__stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
          <blockquote class="rev__quote"><p>&ldquo;No pressure at the showroom &mdash; just great people who love cars.&rdquo;</p></blockquote>
          <figcaption class="rev__by"><img class="rev__av" src="/frontend/assets/img/avatar-nina.webp" width="144" height="144" alt="" loading="lazy"><span><span class="rev__name">Nina H.</span><span class="rev__car">718 Cayman &middot; Sharjah</span></span></figcaption>
        </figure>
      </div>
    </div>
  </section>

  <hr class="rule">

  <!-- ======================================================== 8. Locations -->
  <section class="sec" aria-labelledby="loc-title">
    <div class="sechead sechead--split sechead--loc">
      <div>
        <p class="eyebrow eyebrow--soft"><span class="dot dot--accent dot--8" aria-hidden="true"></span>SHOWROOMS</p>
        <h2 class="h2 h2--fluid" id="loc-title">The Sharjah showroom.</h2>
      </div>
      <p class="sechead__aside">AL Jazeera AL Hamra UESD Cars Enam Ullah Ahmadi. Al Jubail Street 72, Industrial Area-2, Sharjah, United Arab Emirates.</p>
    </div>

    <div class="mapbox">
      <!-- Leaflet + OpenStreetMap: no API key, no billing account. The pins
           are read from data-showrooms below and drawn in CSS. The tile
           attribution is required by the OpenStreetMap licence — keep it. -->
      <div class="mapbox__canvas" id="showroom-map" data-showrooms='[
        {"city":"Sharjah","country":"United Arab Emirates","lat":25.3215,"lng":55.4128,"address":"Al Jubail Street 72, Industrial Area-2","label":"Showroom"}
      ]'></div>
      <span class="mapbox__flag"><span class="dot dot--accent dot--8" aria-hidden="true"></span>SHARJAH &middot; UNITED ARAB EMIRATES</span>
    </div>

    <div class="shops" role="group" aria-label="Showroom address">
      <button class="shop shop--ink" type="button" aria-pressed="true" data-showroom="0">
        <span class="shop__top"><span class="shop__city">Sharjah</span><span class="shop__flag">SHOWROOM</span></span>
        <span class="shop__addr">Al Jubail Street 72<br>Industrial Area-2, Sharjah<br>United Arab Emirates</span>
        <span class="shop__foot"><span>AL Jazeera AL Hamra UESD Cars</span><span>Enam Ullah Ahmadi</span></span>
      </button>
    </div>
  </section>

  <hr class="rule">

  <!-- ============================================================== 9. CTA -->
  <section class="sec" aria-labelledby="cta-title">
    <div class="ctapanel">
      <span class="ctapanel__wordmark" aria-hidden="true">SHARJAH</span>
      <img class="ctapanel__car" src="/frontend/assets/img/showroom/lexus-sedan-copper.jpg" width="1200" height="700" alt="Lexus sedan">
      <span class="ctapanel__scrim" aria-hidden="true"></span>

      <div class="ctapanel__inner">
        <div class="ctapanel__chips">
          <span class="chip chip--outline"><span class="dot dot--accent pulse" aria-hidden="true"></span>4 SLOTS LEFT TODAY</span>
          <span class="ctapanel__free">FREE &middot; NO OBLIGATION</span>
        </div>

        <h2 class="h2 h2--cta h2--nowrap" id="cta-title">Drive it before you decide</h2>
        <p class="ctapanel__lead">Forty minutes on your own roads. We hold the car and confirm in minutes.</p>

        <div class="slots" id="slots">
          <button class="slot slot--on" type="button" aria-pressed="true"><span class="slot__city">Sharjah</span><span class="slot__time">16:30</span></button>
          <button class="slot" type="button" aria-pressed="false"><span class="slot__city">Sharjah</span><span class="slot__time">17:15</span></button>
          <button class="slot" type="button" aria-pressed="false"><span class="slot__city">Sharjah</span><span class="slot__time">18:00</span></button>
          <button class="slot" type="button" aria-pressed="false"><span class="slot__city">Sharjah</span><span class="slot__time">Tmw 09:30</span></button>
        </div>

        <div class="ctapanel__actions">
          <button class="btn btn--accent btn--book" type="button" id="book-cta">Book 16:30 in Sharjah<span class="btn__arrow btn__arrow--34 btn__arrow--ink" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></button>
          <a class="ctapanel__alt" href="#">Or have it brought to you</a>
        </div>

        <div class="ctapanel__stats">
          <div class="cstat"><div class="cstat__num">1,240</div><div class="cstat__lbl">DRIVES THIS MONTH</div></div>
          <span class="cstat__sep" aria-hidden="true"></span>
          <div class="cstat"><div class="cstat__num">40 min</div><div class="cstat__lbl">TYPICAL ROUTE</div></div>
          <span class="cstat__sep" aria-hidden="true"></span>
          <div class="cstat"><div class="cstat__num cstat__num--accent">0</div><div class="cstat__lbl">SALES CALLS AFTER</div></div>
        </div>
      </div>
    </div>
  </section>

  <hr class="rule">

  <!-- ======================================================= 10. Our brands -->
  <section class="sec sec--brands" aria-labelledby="brands-title">
    <div class="sechead sechead--center sechead--brands">
      <p class="eyebrow eyebrow--soft"><span class="dot dot--accent dot--8" aria-hidden="true"></span>OUR BRANDS</p>
      <h2 class="h2 h2--brands" id="brands-title">Multi-brand, by design</h2>
      <p class="sechead__sub">We are tied to no manufacturer, which is why we can tell you when the other one is the better car.</p>
    </div>

    <ul class="brands">
      <li class="brand"><img class="brand__mark" src="/frontend/assets/img/brands/alpine-motors.svg" width="512" height="512" alt="" loading="lazy"><span class="brand__name">Alpine Motors</span></li>
      <li class="brand"><img class="brand__mark" src="/frontend/assets/img/brands/baumann-sportwagen.svg" width="512" height="512" alt="" loading="lazy"><span class="brand__name">Baumann Sportwagen</span></li>
      <li class="brand"><img class="brand__mark" src="/frontend/assets/img/brands/elbe-prestige.svg" width="512" height="512" alt="" loading="lazy"><span class="brand__name">Elbe Prestige</span></li>
      <li class="brand"><img class="brand__mark" src="/frontend/assets/img/brands/nord-prestige.svg" width="512" height="512" alt="" loading="lazy"><span class="brand__name">Nord Prestige</span></li>
      <li class="brand"><img class="brand__mark" src="/frontend/assets/img/brands/rheinland-motors.svg" width="512" height="512" alt="" loading="lazy"><span class="brand__name">Rheinland Motors</span></li>
      <li class="brand"><img class="brand__mark" src="/frontend/assets/img/brands/sterling-automobile.svg" width="512" height="512" alt="" loading="lazy"><span class="brand__name">Sterling Automobile</span></li>
      <li class="brand"><img class="brand__mark" src="/frontend/assets/img/brands/volt-trading.svg" width="512" height="512" alt="" loading="lazy"><span class="brand__name">Volt Trading</span></li>
    </ul>
  </section>

</main>

<!-- ============================================================ 11. Footer -->
<footer class="foot">

  <div class="ticker">
    <span class="ticker__open"><span class="dot dot--ink dot--8 pulse" aria-hidden="true"></span>OPEN NOW</span>
    <span class="ticker__shop"><span class="dot dot--ink" aria-hidden="true"></span><span class="ticker__city">Sharjah</span><span class="ticker__time">AL JUBAIL STREET 72</span></span>
  </div>

  <div class="footstats">
    <div class="footstat"><div class="footstat__num">1,840</div><div class="footstat__lbl">CARS LISTED TODAY</div></div>
    <div class="footstat"><div class="footstat__num">380</div><div class="footstat__lbl">VERIFIED DEALERS</div></div>
    <div class="footstat"><div class="footstat__num">4h 12m</div><div class="footstat__lbl">AVERAGE REPLY</div></div>
    <div class="footstat"><div class="footstat__num footstat__num--accent">0%</div><div class="footstat__lbl">COMMISSION ON THE SALE</div></div>
  </div>

  <div class="footmain">
    <div class="footgrid">
      <div class="footbrand">
        <a class="brandlogo brandlogo--foot" href="/"><span class="brandlogo__mark" aria-hidden="true">AJ</span><span class="brandlogo__text"><span class="brandlogo__line">AL Jazeera AL Hamra</span><span class="brandlogo__line">UESD Cars</span><span class="brandlogo__sub">Enam Ullah Ahmadi</span></span></a>
        <p class="footbrand__text">Al Jubail Street 72, Industrial Area-2, Sharjah, United Arab Emirates.</p>
        <form class="subscribe" action="#" method="post">
          <label class="visually-hidden" for="sub-email">Your email</label>
          <input class="subscribe__in" id="sub-email" type="email" name="email" placeholder="Your email" required>
          <button class="subscribe__btn" type="submit">Subscribe</button>
        </form>
        <ul class="social">
          <li><a href="#" aria-label="Showroom on Instagram"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" focusable="false"><rect x="3.5" y="3.5" width="17" height="17" rx="5"></rect><circle cx="12" cy="12" r="3.8"></circle><circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none"></circle></svg></a></li>
          <li><a href="#" aria-label="Showroom on Facebook"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M15 3h-2a4 4 0 0 0-4 4v3H7v3h2v8h3v-8h3l1-3h-4V7a1 1 0 0 1 1-1h2z"></path></svg></a></li>
          <li><a href="#" aria-label="Showroom on X"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M4 4l7.5 9L4.5 20h2l6-6.6 5 6.6H21l-8-10 7-8h-2l-6 6.4L7.5 4z"></path></svg></a></li>
        </ul>
      </div>

      <nav class="footcol" aria-label="Marketplace">
        <h2 class="footcol__title">MARKETPLACE</h2>
        <ul class="footcol__list">
          <li><a class="footlink" href="#">All listings</a></li>
          <li><a class="footlink" href="#">Map search</a></li>
          <li><a class="footlink" href="#">Compare</a></li>
          <li><a class="footlink" href="#">Sell your car</a></li>
          <li><a class="footlink" href="#finance">Finance</a></li>
        </ul>
      </nav>

      <nav class="footcol" aria-label="Company">
        <h2 class="footcol__title">COMPANY</h2>
        <ul class="footcol__list">
          <li><a class="footlink" href="#">About</a></li>
          <li><a class="footlink" href="#">Showrooms</a></li>
          <li><a class="footlink" href="#">Careers</a></li>
          <li><a class="footlink" href="#">Press</a></li>
          <li><a class="footlink" href="#">Dealer plans</a></li>
        </ul>
      </nav>

      <nav class="footcol" aria-label="Support">
        <h2 class="footcol__title">SUPPORT</h2>
        <ul class="footcol__list">
          <li><a class="footlink" href="#">Contact</a></li>
          <li><a class="footlink" href="#">FAQ</a></li>
          <li><a class="footlink" href="#">Service booking</a></li>
          <li><a class="footlink" href="#">Terms</a></li>
          <li><a class="footlink" href="#">Privacy</a></li>
        </ul>
      </nav>
    </div>
  </div>

  <div class="wordmarkbox">
    <span class="wordmark" aria-hidden="true">SHARJAH</span>
  </div>

  <div class="footbottom">
    <div class="footbottom__inner">
      <span>&copy; 2026 AL Jazeera AL Hamra UESD Cars Enam Ullah Ahmadi</span>
      <span>EVERY CAR INSPECTED BEFORE IT IS LISTED</span>
    </div>
  </div>
</footer>



<!-- ========================================================== Auth dialogs -->
<!-- The provider buttons deliberately carry no Google / Facebook / Apple
     logo — only a neutral monogram. Reproduced third-party marks in a sign-in
     form are exactly what the AVAVA Motors trademark rejection was about. -->
<dialog class="modal modal--auth" id="auth-login" aria-labelledby="auth-login-title">
  <div class="auth">
    <div class="auth__aside">
      <p class="auth__eyebrow"><span class="dot dot--accent dot--8" aria-hidden="true"></span>MEMBER ACCESS</p>
      <p class="auth__pitch">Every car you save, every viewing you book and every trade-in offer &mdash; kept in one place.</p>
      <ul class="auth__list">
        <li><span class="auth__tick" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Saved cars at the Sharjah showroom</li>
        <li><span class="auth__tick" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Test drives you can move or cancel yourself</li>
        <li><span class="auth__tick" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Trade-in offers held for fourteen days</li>
      </ul>
      <div class="auth__proof">
        <div><div class="auth__proof-num">41</div><div class="auth__proof-lbl">CARS THIS WEEK</div></div>
        <span class="auth__proof-sep" aria-hidden="true"></span>
        <div><div class="auth__proof-num">4.9</div><div class="auth__proof-lbl">OWNER RATING</div></div>
      </div>
      <span class="auth__wordmark" aria-hidden="true">SHARJAH</span>
    </div>

    <form class="auth__form" id="login-form" action="#" method="post" novalidate>
      <button class="auth__close" type="button" data-auth-close aria-label="Close"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"></path></svg></button>
      <p class="auth__kicker">WELCOME BACK</p>
      <h2 class="auth__title" id="auth-login-title">Sign in</h2>
      <p class="auth__sub">Carry on with a provider, or use the address you booked with.</p>

      <div class="auth__social">
        <button class="provider" type="button" data-provider="Google"><span class="provider__mark" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path></svg></span><span class="provider__name">Google</span></button>
        <button class="provider" type="button" data-provider="Facebook"><span class="provider__mark" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="#1877F2" d="M24 12a12 12 0 1 0-13.88 11.85v-8.38H7.08V12h3.04V9.36c0-3 1.79-4.67 4.53-4.67 1.31 0 2.68.24 2.68.24v2.95h-1.51c-1.49 0-1.95.92-1.95 1.87V12h3.32l-.53 3.47h-2.79v8.38A12 12 0 0 0 24 12z"></path><path fill="#FFFFFF" d="M16.67 15.47 17.2 12h-3.32V9.75c0-.95.46-1.87 1.95-1.87h1.51V4.93s-1.37-.24-2.68-.24c-2.74 0-4.53 1.67-4.53 4.67V12H7.08v3.47h3.04v8.38a12.1 12.1 0 0 0 3.76 0v-8.38z"></path></svg></span><span class="provider__name">Facebook</span></button>
        <button class="provider" type="button" data-provider="Apple"><span class="provider__mark" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="#14161A" aria-hidden="true" focusable="false"><path d="M17.05 12.54c-.03-2.68 2.19-3.97 2.29-4.03-1.25-1.83-3.19-2.08-3.88-2.11-1.65-.17-3.22.97-4.06.97-.84 0-2.13-.95-3.5-.92-1.8.03-3.46 1.05-4.39 2.66-1.87 3.25-.48 8.06 1.34 10.7.89 1.29 1.95 2.74 3.35 2.69 1.34-.05 1.85-.87 3.47-.87 1.62 0 2.08.87 3.5.84 1.45-.02 2.36-1.31 3.24-2.61 1.02-1.5 1.44-2.95 1.47-3.02-.03-.01-2.82-1.08-2.85-4.3z"></path><path d="M14.5 4.6c.74-.9 1.24-2.15 1.1-3.4-1.07.04-2.36.71-3.12 1.61-.68.79-1.28 2.06-1.12 3.28 1.19.09 2.41-.6 3.14-1.49z"></path></svg></span><span class="provider__name">Apple</span></button>
      </div>

      <p class="auth__or"><span>OR WITH EMAIL</span></p>

      <p class="field">
        <label class="field__lbl" for="login-email">EMAIL</label>
        <input class="field__in" id="login-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required>
      </p>

      <p class="field field--pw">
        <label class="field__lbl" for="login-pw">PASSWORD</label>
        <input class="field__in" id="login-pw" name="password" type="password" autocomplete="current-password" placeholder="At least 8 characters" minlength="8" required>
        <button class="field__peek" type="button" data-peek="login-pw" aria-label="Show password" aria-pressed="false">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M2.5 12S6 5.8 12 5.8 21.5 12 21.5 12 18 18.2 12 18.2 2.5 12 2.5 12z"></path><circle cx="12" cy="12" r="3.1"></circle></svg>
        </button>
      </p>

      <div class="auth__row">
        <label class="check"><input class="check__box" type="checkbox" name="remember" checked><span class="check__mark" aria-hidden="true"></span>Keep me signed in</label>
        <a class="auth__link" href="#">Forgot password?</a>
      </div>

      <p class="auth__error" id="login-error" role="alert" hidden>Check the address and the password, then try again.</p>

      <button class="auth__submit" type="submit">Sign in<span class="btn__arrow btn__arrow--34 btn__arrow--ink" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></button>

      <p class="auth__swap">New here? <button class="auth__swap-btn" type="button" data-auth-to="auth-register">Create an account</button></p>
    </form>
  </div>
</dialog>

<dialog class="modal modal--auth" id="auth-register" aria-labelledby="auth-register-title">
  <div class="auth">
    <div class="auth__aside">
      <p class="auth__eyebrow"><span class="dot dot--accent dot--8" aria-hidden="true"></span>MEMBER ACCESS</p>
      <p class="auth__pitch">Every car you save, every viewing you book and every trade-in offer &mdash; kept in one place.</p>
      <ul class="auth__list">
        <li><span class="auth__tick" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Saved cars at the Sharjah showroom</li>
        <li><span class="auth__tick" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Test drives you can move or cancel yourself</li>
        <li><span class="auth__tick" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Trade-in offers held for fourteen days</li>
      </ul>
      <div class="auth__proof">
        <div><div class="auth__proof-num">41</div><div class="auth__proof-lbl">CARS THIS WEEK</div></div>
        <span class="auth__proof-sep" aria-hidden="true"></span>
        <div><div class="auth__proof-num">4.9</div><div class="auth__proof-lbl">OWNER RATING</div></div>
      </div>
      <span class="auth__wordmark" aria-hidden="true">SHARJAH</span>
    </div>

    <form class="auth__form" id="register-form" action="#" method="post" novalidate>
      <button class="auth__close" type="button" data-auth-close aria-label="Close"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"></path></svg></button>
      <p class="auth__kicker">CREATE YOUR ACCOUNT</p>
      <h2 class="auth__title" id="auth-register-title">Create an account</h2>
      <p class="auth__sub">Two minutes, and the collection remembers you on every device.</p>

      <div class="auth__social">
        <button class="provider" type="button" data-provider="Google"><span class="provider__mark" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path></svg></span><span class="provider__name">Google</span></button>
        <button class="provider" type="button" data-provider="Facebook"><span class="provider__mark" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="#1877F2" d="M24 12a12 12 0 1 0-13.88 11.85v-8.38H7.08V12h3.04V9.36c0-3 1.79-4.67 4.53-4.67 1.31 0 2.68.24 2.68.24v2.95h-1.51c-1.49 0-1.95.92-1.95 1.87V12h3.32l-.53 3.47h-2.79v8.38A12 12 0 0 0 24 12z"></path><path fill="#FFFFFF" d="M16.67 15.47 17.2 12h-3.32V9.75c0-.95.46-1.87 1.95-1.87h1.51V4.93s-1.37-.24-2.68-.24c-2.74 0-4.53 1.67-4.53 4.67V12H7.08v3.47h3.04v8.38a12.1 12.1 0 0 0 3.76 0v-8.38z"></path></svg></span><span class="provider__name">Facebook</span></button>
        <button class="provider" type="button" data-provider="Apple"><span class="provider__mark" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="#14161A" aria-hidden="true" focusable="false"><path d="M17.05 12.54c-.03-2.68 2.19-3.97 2.29-4.03-1.25-1.83-3.19-2.08-3.88-2.11-1.65-.17-3.22.97-4.06.97-.84 0-2.13-.95-3.5-.92-1.8.03-3.46 1.05-4.39 2.66-1.87 3.25-.48 8.06 1.34 10.7.89 1.29 1.95 2.74 3.35 2.69 1.34-.05 1.85-.87 3.47-.87 1.62 0 2.08.87 3.5.84 1.45-.02 2.36-1.31 3.24-2.61 1.02-1.5 1.44-2.95 1.47-3.02-.03-.01-2.82-1.08-2.85-4.3z"></path><path d="M14.5 4.6c.74-.9 1.24-2.15 1.1-3.4-1.07.04-2.36.71-3.12 1.61-.68.79-1.28 2.06-1.12 3.28 1.19.09 2.41-.6 3.14-1.49z"></path></svg></span><span class="provider__name">Apple</span></button>
      </div>

      <p class="auth__or"><span>OR WITH EMAIL</span></p>

      <div class="auth__pair">
        <p class="field">
          <label class="field__lbl" for="reg-first">FIRST NAME</label>
          <input class="field__in" id="reg-first" name="first" type="text" autocomplete="given-name" placeholder="Lena" required>
        </p>
        <p class="field">
          <label class="field__lbl" for="reg-last">LAST NAME</label>
          <input class="field__in" id="reg-last" name="last" type="text" autocomplete="family-name" placeholder="Brandt" required>
        </p>
      </div>

      <p class="field">
        <label class="field__lbl" for="reg-email">EMAIL</label>
        <input class="field__in" id="reg-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required>
      </p>

      <p class="field field--pw">
        <label class="field__lbl" for="reg-pw">PASSWORD</label>
        <input class="field__in" id="reg-pw" name="password" type="password" autocomplete="new-password" placeholder="At least 8 characters" minlength="8" required>
        <button class="field__peek" type="button" data-peek="reg-pw" aria-label="Show password" aria-pressed="false">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M2.5 12S6 5.8 12 5.8 21.5 12 21.5 12 18 18.2 12 18.2 2.5 12 2.5 12z"></path><circle cx="12" cy="12" r="3.1"></circle></svg>
        </button>
        <span class="meter" aria-hidden="true"><span class="meter__fill" id="pw-meter"></span></span>
        <span class="meter__lbl" id="pw-label">Use eight characters or more</span>
      </p>

      <div class="auth__row auth__row--terms">
        <label class="check"><input class="check__box" type="checkbox" name="terms" required><span class="check__mark" aria-hidden="true"></span>I agree to the <a class="auth__link" href="#">Terms</a> and the <a class="auth__link" href="#">Privacy Policy</a></label>
      </div>

      <p class="auth__error" id="register-error" role="alert" hidden>Fill in every field and accept the terms to continue.</p>

      <button class="auth__submit" type="submit">Create account<span class="btn__arrow btn__arrow--34 btn__arrow--ink" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></button>

      <p class="auth__swap">Already have an account? <button class="auth__swap-btn" type="button" data-auth-to="auth-login">Sign in</button></p>
    </form>
  </div>
</dialog>

<dialog class="modal" id="auth-done" aria-labelledby="auth-done-title">
  <form class="modal__box modal__box--success" method="dialog">
    <span class="modal__tick" aria-hidden="true"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>
    <h2 class="modal__title" id="auth-done-title">You are signed in</h2>
    <p class="modal__text" id="auth-done-text">Welcome back. Your saved cars, bookings and trade-in offers are waiting in your profile.</p>
    <div class="modal__actions modal__actions--single">
      <button class="modal__btn modal__btn--ok" type="submit" value="done">Continue</button>
    </div>
  </form>
</dialog>

<!-- ======================================================= Booking dialogs -->
<dialog class="modal" id="book-confirm" aria-labelledby="book-confirm-title">
  <form class="modal__box" method="dialog">
    <p class="modal__eyebrow"><span class="dot dot--accent dot--8" aria-hidden="true"></span>CONFIRM YOUR TEST DRIVE</p>
    <h2 class="modal__title" id="book-confirm-title">Book the car for <span id="confirm-time">16:30</span> in <span id="confirm-city">Sharjah</span>?</h2>
    <p class="modal__text">Forty minutes on your own roads. We hold the car until you arrive &mdash; free, with no obligation, and no sales calls afterwards.</p>
    <dl class="modal__facts">
      <div class="modal__fact"><dt>SHOWROOM</dt><dd id="confirm-city-2">Sharjah</dd></div>
      <div class="modal__fact"><dt>SLOT</dt><dd id="confirm-time-2">16:30</dd></div>
      <div class="modal__fact"><dt>DURATION</dt><dd>40 min</dd></div>
    </dl>
    <div class="modal__actions">
      <button class="modal__btn modal__btn--ghost" type="submit" value="cancel">Cancel</button>
      <button class="modal__btn modal__btn--ok" type="submit" value="confirm">OK, book it</button>
    </div>
  </form>
</dialog>

<dialog class="modal" id="book-success" aria-labelledby="book-success-title">
  <form class="modal__box modal__box--success" method="dialog">
    <span class="modal__tick" aria-hidden="true"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>
    <h2 class="modal__title" id="book-success-title">Your test drive is booked</h2>
    <p class="modal__text">We are holding the car for <strong id="success-slot">16:30 in Sharjah</strong>. The showroom is at Al Jubail Street 72, Industrial Area-2, Sharjah, United Arab Emirates.</p>
    <dl class="modal__facts">
      <div class="modal__fact"><dt>REFERENCE</dt><dd id="success-ref">AV-000000</dd></div>
      <div class="modal__fact"><dt>ON ARRIVAL</dt><dd>Ask for Enam Ullah Ahmadi</dd></div>
      <div class="modal__fact"><dt>BRING</dt><dd>Your driving licence</dd></div>
    </dl>
    <div class="modal__actions modal__actions--single">
      <button class="modal__btn modal__btn--ok" type="submit" value="done">Done</button>
    </div>
  </form>
</dialog>

<script src="/frontend/assets/vendor/leaflet/leaflet.js"></script>
<script src="/frontend/assets/js/main1ef4.js?v=5d78136b"></script>
</body>

<!-- Mirrored from https://demo.gridgum.com/avavamotors/car-marketplace/index.html by HTTrack Website Copier/3.x [XR&CO], Tue, 06 Oct 2026 16:45:40 GMT -->
</html>
