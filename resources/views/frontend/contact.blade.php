<!DOCTYPE html>
<html lang="en" data-accent="winter">

<!-- Mirrored from https://demo.gridgum.com/avavamotors/car-marketplace/contact.html by HTTrack Website Copier/3.x [XR&CO], Tue, 06 Oct 2026 16:46:18 GMT -->
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

<title>AVAVA — Contact us</title>
<meta name="description" content="Write once and a specialist answers in writing within four working hours. Seven Alpine showrooms with direct numbers, opening hours and live stock — and no call centre.">
<meta name="theme-color" content="#14161A">

<meta property="og:type" content="website">
<meta property="og:site_name" content="AVAVA">
<meta property="og:title" content="AVAVA — Contact us">
<meta property="og:description" content="41 all-wheel-drive cars ready this week. 200-point report published before you ask for it. No buyer fees.">
<meta property="og:image" content="/frontend/assets/img/og-cover.jpg">
<meta name="twitter:card" content="summary_large_image">

<link rel="icon" href="/frontend/assets/img/favicon.svg" type="image/svg+xml">

<link rel="preconnect" href="https://fonts.googleapis.com/">
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800;900&amp;family=JetBrains+Mono:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link rel="stylesheet" href="/frontend/assets/css/style1ef4.css?v=5d78136b">
<link rel="stylesheet" href="/frontend/assets/vendor/leaflet/leaflet.css">
<link rel="stylesheet" href="/frontend/assets/css/contact1ef4.css?v=5d78136b">
<style>.brandlogo{display:flex;align-items:center;gap:12px;color:inherit;text-decoration:none}.brandlogo__mark{display:grid;place-items:center;width:48px;height:48px;flex:none;border-radius:14px;background:#c6a15b;color:#14161a;font-weight:800;font-size:16px}.brandlogo__line{display:block;font-weight:800;letter-spacing:.04em;text-transform:uppercase;line-height:1.15;font-size:12px}.brandlogo__sub{display:block;margin-top:3px;font-size:11px;opacity:.78}.brandlogo--foot .brandlogo__mark{width:72px;height:72px;border-radius:18px;font-size:24px}.brandlogo--foot .brandlogo__line{font-size:18px;color:#fff}.brandlogo--foot .brandlogo__sub{margin-top:6px;font-size:14px;color:rgba(255,255,255,.72)}.nav__link.is-on{box-shadow:inset 0 -2px 0 #c6a15b}.footbrand__text a{color:#fff}</style>
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
    <svg class="preloader__logo" viewBox="0 0 203 51" width="203" height="51" focusable="false">
      <defs>
        <g id="pl-mark">
          <path d="M1.91199 40.24C1.91199 39.5573 2.02932 38.88 2.26399 38.208C2.50932 37.5253 2.86132 36.9173 3.31999 36.384C3.77866 35.8507 4.34399 35.4187 5.01599 35.088C5.68799 34.7467 6.45066 34.576 7.30399 34.576C8.31732 34.576 9.18132 34.8053 9.89599 35.264C10.6213 35.7227 11.1547 36.32 11.496 37.056L10.616 37.6C10.4347 37.2267 10.2107 36.912 9.94399 36.656C9.68799 36.4 9.41066 36.192 9.11199 36.032C8.81332 35.872 8.50399 35.76 8.18399 35.696C7.86399 35.6213 7.54932 35.584 7.23999 35.584C6.55732 35.584 5.95466 35.7227 5.43199 36C4.90932 36.2773 4.46666 36.64 4.10399 37.088C3.75199 37.536 3.48532 38.0427 3.30399 38.608C3.12266 39.1627 3.03199 39.728 3.03199 40.304C3.03199 40.9333 3.13866 41.536 3.35199 42.112C3.57599 42.688 3.87466 43.2 4.24799 43.648C4.63199 44.0853 5.07999 44.4373 5.59199 44.704C6.11466 44.96 6.67999 45.088 7.28799 45.088C7.60799 45.088 7.93332 45.0507 8.26399 44.976C8.60532 44.8907 8.93066 44.7627 9.23999 44.592C9.55999 44.4107 9.85332 44.192 10.12 43.936C10.3867 43.6693 10.6107 43.3493 10.792 42.976L11.72 43.456C11.5387 43.8827 11.2827 44.2613 10.952 44.592C10.6213 44.912 10.2533 45.184 9.84799 45.408C9.44266 45.632 9.01066 45.8027 8.55199 45.92C8.10399 46.0373 7.66132 46.096 7.22399 46.096C6.44532 46.096 5.73066 45.9253 5.07999 45.584C4.42932 45.2427 3.86932 44.8 3.39999 44.256C2.93066 43.7013 2.56266 43.0773 2.29599 42.384C2.03999 41.68 1.91199 40.9653 1.91199 40.24ZM20.3565 34.64H21.3005L26.0205 46H24.8365L23.3645 42.448H18.2605L16.8045 46H15.6045L20.3565 34.64ZM23.1085 41.568L20.8205 35.952L18.5005 41.568H23.1085ZM31.009 46V34.64H35.809C36.2997 34.64 36.7477 34.7467 37.153 34.96C37.569 35.1627 37.9263 35.4347 38.225 35.776C38.5237 36.1067 38.753 36.4853 38.913 36.912C39.0837 37.3387 39.169 37.7707 39.169 38.208C39.169 38.624 39.105 39.024 38.977 39.408C38.8597 39.7813 38.689 40.1227 38.465 40.432C38.2517 40.7307 37.9903 40.9867 37.681 41.2C37.3717 41.4027 37.025 41.5413 36.641 41.616L39.425 46H38.161L35.489 41.808H32.129V46H31.009ZM32.129 40.816H35.841C36.1717 40.816 36.4703 40.7467 36.737 40.608C37.0143 40.4587 37.249 40.2613 37.441 40.016C37.633 39.7707 37.7823 39.4933 37.889 39.184C37.9957 38.8747 38.049 38.5493 38.049 38.208C38.049 37.8667 37.985 37.5413 37.857 37.232C37.7397 36.912 37.5743 36.6347 37.361 36.4C37.1583 36.1653 36.913 35.9787 36.625 35.84C36.3477 35.7013 36.0543 35.632 35.745 35.632H32.129V40.816ZM62.3242 46V36.672L58.1802 43.888H57.5242L53.3802 36.672V46H52.2602V34.64H53.4122L57.8442 42.416L62.3082 34.64H63.4442V46H62.3242ZM73.1715 34.64H74.1155L78.8355 46H77.6515L76.1795 42.448H71.0755L69.6195 46H68.4195L73.1715 34.64ZM75.9235 41.568L73.6355 35.952L71.3155 41.568H75.9235ZM83.824 46V34.64H88.624C89.1147 34.64 89.5627 34.7467 89.968 34.96C90.384 35.1627 90.7413 35.4347 91.04 35.776C91.3387 36.1067 91.568 36.4853 91.728 36.912C91.8987 37.3387 91.984 37.7707 91.984 38.208C91.984 38.624 91.92 39.024 91.792 39.408C91.6747 39.7813 91.504 40.1227 91.28 40.432C91.0667 40.7307 90.8053 40.9867 90.496 41.2C90.1867 41.4027 89.84 41.5413 89.456 41.616L92.24 46H90.976L88.304 41.808H84.944V46H83.824ZM84.944 40.816H88.656C88.9867 40.816 89.2853 40.7467 89.552 40.608C89.8293 40.4587 90.064 40.2613 90.256 40.016C90.448 39.7707 90.5973 39.4933 90.704 39.184C90.8107 38.8747 90.864 38.5493 90.864 38.208C90.864 37.8667 90.8 37.5413 90.672 37.232C90.5547 36.912 90.3893 36.6347 90.176 36.4C89.9733 36.1653 89.728 35.9787 89.44 35.84C89.1627 35.7013 88.8693 35.632 88.56 35.632H84.944V40.816ZM97.6371 46V34.656H98.7571V41.248L105.013 34.64H106.245L101.605 39.632L106.485 46H105.253L100.901 40.288L98.7571 42.512V46H97.6371ZM118.91 45.008V46H111.278V34.64H118.766V35.632H112.398V39.728H117.95V40.672H112.398V45.008H118.91ZM132.452 35.632H128.404V46H127.284V35.632H123.236V34.64H132.452V35.632ZM137.514 46V34.64H142.218C142.709 34.64 143.157 34.7467 143.562 34.96C143.978 35.1627 144.335 35.4347 144.634 35.776C144.933 36.1067 145.167 36.4853 145.338 36.912C145.509 37.3387 145.594 37.7707 145.594 38.208C145.594 38.6773 145.514 39.1307 145.354 39.568C145.194 39.9947 144.97 40.3733 144.682 40.704C144.394 41.0347 144.047 41.3013 143.642 41.504C143.247 41.7067 142.805 41.808 142.314 41.808H138.634V46H137.514ZM138.634 40.816H142.266C142.597 40.816 142.895 40.7467 143.162 40.608C143.439 40.4587 143.674 40.2667 143.866 40.032C144.058 39.7867 144.207 39.5093 144.314 39.2C144.421 38.88 144.474 38.5493 144.474 38.208C144.474 37.856 144.41 37.5253 144.282 37.216C144.165 36.896 143.999 36.6187 143.786 36.384C143.583 36.1493 143.338 35.968 143.05 35.84C142.773 35.7013 142.479 35.632 142.17 35.632H138.634V40.816ZM150.827 46V34.64H151.947V45.008H158.507V46H150.827ZM167.035 34.64H167.979L172.699 46H171.515L170.043 42.448H164.939L163.483 46H162.283L167.035 34.64ZM169.787 41.568L167.499 35.952L165.179 41.568H169.787ZM176.514 40.24C176.514 39.5573 176.631 38.88 176.866 38.208C177.111 37.5253 177.463 36.9173 177.922 36.384C178.381 35.8507 178.946 35.4187 179.618 35.088C180.29 34.7467 181.053 34.576 181.906 34.576C182.919 34.576 183.783 34.8053 184.498 35.264C185.223 35.7227 185.757 36.32 186.098 37.056L185.218 37.6C185.037 37.2267 184.813 36.912 184.546 36.656C184.29 36.4 184.013 36.192 183.714 36.032C183.415 35.872 183.106 35.76 182.786 35.696C182.466 35.6213 182.151 35.584 181.842 35.584C181.159 35.584 180.557 35.7227 180.034 36C179.511 36.2773 179.069 36.64 178.706 37.088C178.354 37.536 178.087 38.0427 177.906 38.608C177.725 39.1627 177.634 39.728 177.634 40.304C177.634 40.9333 177.741 41.536 177.954 42.112C178.178 42.688 178.477 43.2 178.85 43.648C179.234 44.0853 179.682 44.4373 180.194 44.704C180.717 44.96 181.282 45.088 181.89 45.088C182.21 45.088 182.535 45.0507 182.866 44.976C183.207 44.8907 183.533 44.7627 183.842 44.592C184.162 44.4107 184.455 44.192 184.722 43.936C184.989 43.6693 185.213 43.3493 185.394 42.976L186.322 43.456C186.141 43.8827 185.885 44.2613 185.554 44.592C185.223 44.912 184.855 45.184 184.45 45.408C184.045 45.632 183.613 45.8027 183.154 45.92C182.706 46.0373 182.263 46.096 181.826 46.096C181.047 46.096 180.333 45.9253 179.682 45.584C179.031 45.2427 178.471 44.8 178.002 44.256C177.533 43.7013 177.165 43.0773 176.898 42.384C176.642 41.68 176.514 40.9653 176.514 40.24ZM199.227 45.008V46H191.595V34.64H199.083V35.632H192.715V39.728H198.267V40.672H192.715V45.008H199.227Z"></path>
          <path d="M-9.76771e-06 29L17.48 0.999998H32.16L49.6 29H36.28L33.88 24.64H15.84L13.32 29H-9.76771e-06ZM21.16 15.36L19.8 17.76H30.08L28.76 15.36L25.44 8.56H24.64L21.16 15.36ZM55.0687 29L37.6287 0.999998H51.5087L62.2287 19.96L73.3487 0.999998H87.2287L69.7487 29H55.0687ZM75.2594 29L92.7394 0.999998H107.419L124.859 29H111.539L109.139 24.64H91.0994L88.5794 29H75.2594ZM96.4194 15.36L95.0594 17.76H105.339L104.019 15.36L100.699 8.56H99.8994L96.4194 15.36ZM130.328 29L112.888 0.999998H126.768L137.488 19.96L148.608 0.999998H162.488L145.008 29H130.328ZM150.519 29L167.999 0.999998H182.679L200.119 29H186.799L184.399 24.64H166.359L163.839 29H150.519ZM171.679 15.36L170.319 17.76H180.599L179.279 15.36L175.959 8.56H175.159L171.679 15.36Z"></path>
        </g>
        <clipPath id="pl-clip">
          <rect id="pl-rect" x="0" y="51" width="203" height="51"></rect>
        </clipPath>
      </defs>
      <use class="preloader__outline" href="#pl-mark"></use>
      <g clip-path="url(#pl-clip)"><use class="preloader__fill" href="#pl-mark"></use></g>
    </svg>
    <p class="preloader__pct"><span id="preloader-pct">0</span>%</p>
  </div>
</div>

<a class="skip-link" href="#main">Skip to content</a>

<header class="hdr hdr--light">
  <div class="hdr__utility">
    <ul class="util">
      <li class="util__item">
        <svg class="ico" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11z"></path><circle cx="12" cy="10" r="2.4"></circle></svg>
        Al Jubail Street 72, Industrial Area-2, Sharjah, UAE
      </li>
      <li class="util__sep" aria-hidden="true"></li>
      <li class="util__item">
        <svg class="ico" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4.5 5.5h4l1.6 4-2 1.4a12 12 0 0 0 5 5l1.4-2 4 1.6v4a1.5 1.5 0 0 1-1.6 1.5C9.4 20.4 3.6 14.6 3 5.1a1.5 1.5 0 0 1 1.5-1.6z"></path></svg>
        <a class="util__link" href="tel:+971780272673">078&nbsp;027&nbsp;2673</a>
      </li>
      <li class="util__sep" aria-hidden="true"></li>
      <li class="util__item"><span class="dot dot--accent" aria-hidden="true"></span>MON&ndash;SAT 09:00&ndash;19:00</li>
    </ul>
    <div class="hdr__utility-right">
      <span class="cur" role="group" aria-label="Currency"><button class="cur__btn cur__btn--on" type="button" data-cur="AED" aria-pressed="true">AED</button><span class="cur__sep" aria-hidden="true">&middot;</span><button class="cur__btn" type="button" data-cur="USD" aria-pressed="false">US$</button></span>
    </div>
  </div>
  <div class="hdr__bar">
    <a class="hdr__logo brandlogo" href="/"><span class="brandlogo__mark" aria-hidden="true">AJ</span><span class="brandlogo__text"><span class="brandlogo__line">AL Jazeera AL Hamra</span><span class="brandlogo__line">UESD Cars</span><span class="brandlogo__sub">Enam Ullah Ahmadi</span></span></a>
    <nav class="nav" id="nav" aria-label="Main">
      <ul class="nav__list">
        <li class="nav__item"><a class="nav__link" href="/">Home</a></li>
        <li class="nav__item"><a class="nav__link" href="/about">About Us</a></li>
        <li class="nav__item"><a class="nav__link" href="/service">Service</a></li>
        <li class="nav__item"><a class="nav__link" href="/pricing">Pricing</a></li>
        <li class="nav__item"><a class="nav__link is-on" href="/contact">Contact Us</a></li>
      </ul>
    </nav>
  </div>
</header>
<div class="hdr__rule"></div>

<main id="main">

  <!-- ==================================================== 1. Page header -->
  <section class="pagehead">
    <span class="pagehead__wordmark" aria-hidden="true">AVAVA</span>

    <nav class="crumbs" aria-label="Breadcrumb">
      <ol class="crumbs__list">
        <li><a href="/">HOME</a></li>
        <li aria-current="page">CONTACT US</li>
      </ol>
      <p class="crumbs__live">
        <span class="crumbs__ready"><span class="dot dot--accent" aria-hidden="true"></span>41 CARS READY THIS WEEK</span>
        <span class="crumbs__sep" aria-hidden="true"></span>
        <span>7 SHOWROOMS</span>
        <span class="crumbs__sep" aria-hidden="true"></span>
        <span>UPDATED 12 MIN AGO</span>
      </p>
    </nav>

    <div class="pagehead__row">
      <div>
        <p class="eyebrow"><span class="dot dot--accent dot--8" aria-hidden="true"></span>CONTACT US</p>
        <h1 class="pagehead__title">A specialist answers, not a <span class="outlineword">call</span> <span class="accentword">centre</span>.</h1>
      </div>
      <p class="pagehead__lede">Four working hours to a written reply, seven showrooms open six days a week, and one number that reaches a technician.</p>
    </div>
  </section>

  <hr class="rule rule--head">

  <!-- ================================================ 2. The contact panel -->
  <section class="sec" aria-labelledby="write-title">
    <form class="talk" id="talk" novalidate>
      <span class="talk__wordmark" aria-hidden="true">TALK</span>

      <div class="talk__head">
        <div class="talk__lead">
          <p class="talk__live"><span class="dot dot--accent dot--8 pulse" aria-hidden="true"></span>DESK OPEN NOW</p>
          <h2 class="talk__h" id="write-title">Write once. Get a real answer.</h2>
          <p class="talk__sub">The person who replies is the person who can answer &mdash; and they answer in writing, in four working hours.</p>
        </div>

        <div class="talk__figs">
          <div class="askfig"><p class="askfig__num askfig__num--accent">4h</p><p class="askfig__lbl">AVERAGE REPLY</p></div>
          <span class="askfig__sep" aria-hidden="true"></span>
          <div class="askfig"><p class="askfig__num">0</p><p class="askfig__lbl">CALL CENTRES</p></div>
        </div>
      </div>

      <div class="talk__topics" role="group" aria-labelledby="topic-lbl">
        <p class="talk__lbl" id="topic-lbl">WHAT IS IT ABOUT?</p>
        <div class="ctopics">
          <button class="ctopic ctopic--on" type="button" aria-pressed="true" data-topic="Buying a car">Buying a car</button>
          <button class="ctopic" type="button" aria-pressed="false" data-topic="Service booking">Service booking</button>
          <button class="ctopic" type="button" aria-pressed="false" data-topic="Trade-in valuation">Trade-in valuation</button>
          <button class="ctopic" type="button" aria-pressed="false" data-topic="Finance question">Finance question</button>
          <button class="ctopic" type="button" aria-pressed="false" data-topic="Something went wrong">Something went wrong</button>
        </div>
      </div>

      <div class="talk__grid talk__grid--3">
        <label class="tfield">
          <span class="tfield__lbl">FULL NAME</span>
          <input class="tfield__in" type="text" name="name" id="ct-name" autocomplete="name" required>
        </label>
        <label class="tfield">
          <span class="tfield__lbl">EMAIL</span>
          <input class="tfield__in" type="email" name="email" id="ct-email" placeholder="you@example.com" autocomplete="email" required>
        </label>
        <label class="tfield">
          <span class="tfield__lbl">PHONE</span>
          <input class="tfield__in" type="tel" name="phone" id="ct-phone" placeholder="078 027 2673" autocomplete="tel">
        </label>
      </div>

      <div class="talk__grid talk__grid--2">
        <label class="tfield">
          <span class="tfield__lbl">NEAREST SHOWROOM</span>
          <span class="tfield__pick">
            <select class="tfield__in tfield__in--select" name="showroom" id="ct-shop">
              <option>Munich</option>
              <option>Z&uuml;rich</option>
              <option>Vienna</option>
              <option>Geneva</option>
              <option>Milan</option>
              <option>Salzburg</option>
              <option>Innsbruck</option>
            </select>
            <span class="tfield__chev" aria-hidden="true"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 9l7 7 7-7"></path></svg></span>
          </span>
        </label>
        <label class="tfield">
          <span class="tfield__lbl">THE CAR, IF THERE IS ONE</span>
          <input class="tfield__in" type="text" name="car" id="ct-car" placeholder="Range Rover SV, or a listing number">
        </label>
      </div>

      <label class="tfield talk__msg">
        <span class="tfield__lbl">YOUR MESSAGE</span>
        <textarea class="tfield__in tfield__in--area" name="message" id="ct-msg" rows="4" placeholder="Tell us what you need. The more specific, the faster the answer." required></textarea>
      </label>

      <p class="talk__error" id="talk-error" role="alert" hidden>Add your name, a valid email address and a message, and we will take it from there.</p>

      <div class="talk__send">
        <ul class="cpledges">
          <li class="cpledge"><span class="cpledge__tick" aria-hidden="true"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12.5l4.5 4.5L19 7"></path></svg></span>Read by a specialist</li>
          <li class="cpledge"><span class="cpledge__tick" aria-hidden="true"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12.5l4.5 4.5L19 7"></path></svg></span>Answered in writing</li>
          <li class="cpledge"><span class="cpledge__tick" aria-hidden="true"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12.5l4.5 4.5L19 7"></path></svg></span>No follow-up chasing</li>
        </ul>
        <button class="talk__cta" type="submit">Send the message<span class="talk__arrow" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></button>
      </div>

      <div class="dlines">
        <div class="dline">
          <span class="dline__body">
            <span class="dline__lbl" id="dl-0">CALL THE DESK</span>
            <a class="dline__val" href="tel:+971780272673" aria-describedby="dl-0">078&nbsp;027&nbsp;2673</a>
            <span class="dline__note">MON&ndash;SAT 09:00&ndash;19:00</span>
          </span>
          <span class="dline__go" aria-hidden="true"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span>
        </div>
        <div class="dline">
          <span class="dline__body">
            <span class="dline__lbl" id="dl-1">SERVICE BOOKINGS</span>
            <a class="dline__val" href="mailto:service@avava.eu" aria-describedby="dl-1">service@avava.eu</a>
            <span class="dline__note">FOUR WORKING HOURS</span>
          </span>
          <span class="dline__go" aria-hidden="true"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span>
        </div>
        <div class="dline">
          <span class="dline__body">
            <span class="dline__lbl" id="dl-2">SOMETHING WRONG</span>
            <a class="dline__val" href="mailto:resolve@avava.eu" aria-describedby="dl-2">resolve@avava.eu</a>
            <span class="dline__note">STRAIGHT TO A DIRECTOR</span>
          </span>
          <span class="dline__go" aria-hidden="true"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span>
        </div>
      </div>
    </form>
  </section>

  <hr class="rule">

  <!-- ==================================================== 3. Showrooms -->
  <section class="sec" aria-labelledby="doors-title">
    <div class="sechead sechead--split sechead--doors">
      <div>
        <p class="eyebrow"><span class="dot dot--accent dot--8" aria-hidden="true"></span>SHOWROOMS</p>
        <h2 class="h2 h2--doors" id="doors-title">Seven doors, <span class="accentword">open now</span>.</h2>
        <p class="sechead__lead sechead__lead--doors">Walk in without an appointment, or ring the floor directly &mdash; the phone is answered by someone standing next to the cars.</p>
      </div>
      <div class="pagefacts">
        <div class="pagefact"><div class="pagefact__num pagefact__num--accent">4</div><div class="pagefact__lbl">OPEN RIGHT NOW</div></div>
        <span class="pagefact__sep" aria-hidden="true"></span>
        <div class="pagefact"><div class="pagefact__num">142</div><div class="pagefact__lbl">CARS ON THE FLOOR</div></div>
      </div>
    </div>

    <div class="doors">
      <div class="doors__map">
        <div class="doors__canvas" id="doors-map" data-showrooms='[{"city": "Munich", "address": "Landsberger Str. 214", "opens": "09:00", "closes": "19:00", "days": "Mon–Sat", "phone": "078 027 2673", "tel": "tel:+971780272673", "open": true, "stock": 42, "distance": "12 km", "lat": 48.1372, "lng": 11.5756}, {"city": "Zürich", "address": "Seestrasse 41", "opens": "09:00", "closes": "18:30", "days": "Mon–Fri", "phone": "078 027 2673", "tel": "tel:+971780272673", "open": true, "stock": 28, "distance": "241 km", "lat": 47.3769, "lng": 8.5417}, {"city": "Vienna", "address": "Triester Str. 96", "opens": "09:00", "closes": "18:00", "days": "Mon–Sat", "phone": "078 027 2673", "tel": "tel:+971780272673", "open": true, "stock": 22, "distance": "356 km", "lat": 48.2082, "lng": 16.3738}, {"city": "Geneva", "address": "Route de Meyrin 122", "opens": "09:00", "closes": "18:00", "days": "Mon–Fri", "phone": "078 027 2673", "tel": "tel:+971780272673", "open": false, "stock": 19, "distance": "512 km", "lat": 46.2044, "lng": 6.1432}, {"city": "Milan", "address": "Via Mecenate 78", "opens": "09:30", "closes": "18:00", "days": "Mon–Fri", "phone": "078 027 2673", "tel": "tel:+971780272673", "open": true, "stock": 17, "distance": "488 km", "lat": 45.4642, "lng": 9.19}, {"city": "Salzburg", "address": "Alpenstrasse 60", "opens": "09:00", "closes": "17:30", "days": "Mon–Fri", "phone": "078 027 2673", "tel": "tel:+971780272673", "open": false, "stock": 8, "distance": "142 km", "lat": 47.8095, "lng": 13.055}, {"city": "Innsbruck", "address": "Grünes Feld 9", "opens": "09:00", "closes": "17:30", "days": "Mon–Fri", "phone": "078 027 2673", "tel": "tel:+971780272673", "open": false, "stock": 6, "distance": "162 km", "lat": 47.2692, "lng": 11.4041}]'></div>

        <p class="doors__legend">
          <span class="doors__key"><span class="dot dot--accent" aria-hidden="true"></span>OPEN</span>
          <span class="doors__keysep" aria-hidden="true"></span>
          <span class="doors__key doors__key--off"><span class="dot dot--off" aria-hidden="true"></span>CLOSED TODAY</span>
        </p>

        <div class="nearby">
          <div class="nearby__row">
            <div>
              <p class="nearby__lbl" id="nearby-lbl">NEAREST TO YOU &middot; 12 km</p>
              <p class="nearby__title" id="nearby-title">Munich &mdash; 42 cars on the floor</p>
              <p class="nearby__note" id="nearby-note">Landsberger Str. 214 &middot; open until 19:00</p>
            </div>
            <div class="nearby__acts">
              <a class="nearby__call" id="nearby-call" href="tel:+971780272673">078&nbsp;027&nbsp;2673<span class="nearby__ico" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6.5 4h3l1.5 4-2 1.5a12 12 0 006 6L17 13l4 1.5v3a2 2 0 01-2.2 2A16 16 0 014.5 6.2 2 2 0 016.5 4z"></path></svg></span></a>
              <a class="nearby__dir" href="#">Get directions</a>
            </div>
          </div>

          <p class="nearby__bar">
            <span class="nearby__time" id="nearby-open">TODAY 09:00</span>
            <span class="nearby__track"><span class="nearby__fill" id="nearby-fill"></span></span>
            <span class="nearby__time" id="nearby-close">19:00</span>
          </p>
        </div>
      </div>

      <div class="doors__list">
        <div class="shrows">
          <button class="shrow shrow--on" type="button" data-city="0" aria-pressed="true">
            <span class="shrow__shot" aria-hidden="true"><span class="shrow__mono">M</span></span>
            <span class="shrow__main">
              <span class="shrow__top">
                <span class="dot dot--accent" aria-hidden="true"></span>
                <span class="shrow__city">Munich</span>
                <span class="shrow__badge">HEAD SHOWROOM</span><span class="shrow__state">TO 19:00</span>
              </span>
              <span class="shrow__addr">Landsberger Str. 214</span>
              <span class="shrow__meta"><span>42 CARS</span><span class="shrow__dot" aria-hidden="true"></span><span>12 km</span></span>
            </span>
            <span class="shrow__call">
              <span class="shrow__tel">078&nbsp;027&nbsp;2673</span>
              <span class="shrow__ico shrow__ico--on" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6.5 4h3l1.5 4-2 1.5a12 12 0 006 6L17 13l4 1.5v3a2 2 0 01-2.2 2A16 16 0 014.5 6.2 2 2 0 016.5 4z"></path></svg></span>
            </span>
          </button>
          <button class="shrow" type="button" data-city="1" aria-pressed="false">
            <span class="shrow__shot" aria-hidden="true"><span class="shrow__mono">Z</span></span>
            <span class="shrow__main">
              <span class="shrow__top">
                <span class="dot dot--accent" aria-hidden="true"></span>
                <span class="shrow__city">Z&uuml;rich</span>
                <span class="shrow__state">TO 18:30</span>
              </span>
              <span class="shrow__addr">Seestrasse 41</span>
              <span class="shrow__meta"><span>28 CARS</span><span class="shrow__dot" aria-hidden="true"></span><span>241 km</span></span>
            </span>
            <span class="shrow__call">
              <span class="shrow__tel">078&nbsp;027&nbsp;2673</span>
              <span class="shrow__ico" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6.5 4h3l1.5 4-2 1.5a12 12 0 006 6L17 13l4 1.5v3a2 2 0 01-2.2 2A16 16 0 014.5 6.2 2 2 0 016.5 4z"></path></svg></span>
            </span>
          </button>
          <button class="shrow" type="button" data-city="2" aria-pressed="false">
            <span class="shrow__shot" aria-hidden="true"><span class="shrow__mono">V</span></span>
            <span class="shrow__main">
              <span class="shrow__top">
                <span class="dot dot--accent" aria-hidden="true"></span>
                <span class="shrow__city">Vienna</span>
                <span class="shrow__state">TO 18:00</span>
              </span>
              <span class="shrow__addr">Triester Str. 96</span>
              <span class="shrow__meta"><span>22 CARS</span><span class="shrow__dot" aria-hidden="true"></span><span>356 km</span></span>
            </span>
            <span class="shrow__call">
              <span class="shrow__tel">078&nbsp;027&nbsp;2673</span>
              <span class="shrow__ico" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6.5 4h3l1.5 4-2 1.5a12 12 0 006 6L17 13l4 1.5v3a2 2 0 01-2.2 2A16 16 0 014.5 6.2 2 2 0 016.5 4z"></path></svg></span>
            </span>
          </button>
          <button class="shrow" type="button" data-city="3" aria-pressed="false">
            <span class="shrow__shot" aria-hidden="true"><span class="shrow__mono">G</span></span>
            <span class="shrow__main">
              <span class="shrow__top">
                <span class="dot dot--off" aria-hidden="true"></span>
                <span class="shrow__city">Geneva</span>
                <span class="shrow__state shrow__state--off">OPENS 09:00</span>
              </span>
              <span class="shrow__addr">Route de Meyrin 122</span>
              <span class="shrow__meta"><span>19 CARS</span><span class="shrow__dot" aria-hidden="true"></span><span>512 km</span></span>
            </span>
            <span class="shrow__call">
              <span class="shrow__tel">078&nbsp;027&nbsp;2673</span>
              <span class="shrow__ico" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6.5 4h3l1.5 4-2 1.5a12 12 0 006 6L17 13l4 1.5v3a2 2 0 01-2.2 2A16 16 0 014.5 6.2 2 2 0 016.5 4z"></path></svg></span>
            </span>
          </button>
          <button class="shrow" type="button" data-city="4" aria-pressed="false">
            <span class="shrow__shot" aria-hidden="true"><span class="shrow__mono">M</span></span>
            <span class="shrow__main">
              <span class="shrow__top">
                <span class="dot dot--accent" aria-hidden="true"></span>
                <span class="shrow__city">Milan</span>
                <span class="shrow__state">TO 18:00</span>
              </span>
              <span class="shrow__addr">Via Mecenate 78</span>
              <span class="shrow__meta"><span>17 CARS</span><span class="shrow__dot" aria-hidden="true"></span><span>488 km</span></span>
            </span>
            <span class="shrow__call">
              <span class="shrow__tel">078&nbsp;027&nbsp;2673</span>
              <span class="shrow__ico" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6.5 4h3l1.5 4-2 1.5a12 12 0 006 6L17 13l4 1.5v3a2 2 0 01-2.2 2A16 16 0 014.5 6.2 2 2 0 016.5 4z"></path></svg></span>
            </span>
          </button>
          <button class="shrow" type="button" data-city="5" aria-pressed="false">
            <span class="shrow__shot" aria-hidden="true"><span class="shrow__mono">S</span></span>
            <span class="shrow__main">
              <span class="shrow__top">
                <span class="dot dot--off" aria-hidden="true"></span>
                <span class="shrow__city">Salzburg</span>
                <span class="shrow__state shrow__state--off">OPENS 09:00</span>
              </span>
              <span class="shrow__addr">Alpenstrasse 60</span>
              <span class="shrow__meta"><span>8 CARS</span><span class="shrow__dot" aria-hidden="true"></span><span>142 km</span></span>
            </span>
            <span class="shrow__call">
              <span class="shrow__tel">078&nbsp;027&nbsp;2673</span>
              <span class="shrow__ico" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6.5 4h3l1.5 4-2 1.5a12 12 0 006 6L17 13l4 1.5v3a2 2 0 01-2.2 2A16 16 0 014.5 6.2 2 2 0 016.5 4z"></path></svg></span>
            </span>
          </button>
          <button class="shrow" type="button" data-city="6" aria-pressed="false">
            <span class="shrow__shot" aria-hidden="true"><span class="shrow__mono">I</span></span>
            <span class="shrow__main">
              <span class="shrow__top">
                <span class="dot dot--off" aria-hidden="true"></span>
                <span class="shrow__city">Innsbruck</span>
                <span class="shrow__state shrow__state--off">OPENS 09:00</span>
              </span>
              <span class="shrow__addr">Gr&uuml;nes Feld 9</span>
              <span class="shrow__meta"><span>6 CARS</span><span class="shrow__dot" aria-hidden="true"></span><span>162 km</span></span>
            </span>
            <span class="shrow__call">
              <span class="shrow__tel">078&nbsp;027&nbsp;2673</span>
              <span class="shrow__ico" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6.5 4h3l1.5 4-2 1.5a12 12 0 006 6L17 13l4 1.5v3a2 2 0 01-2.2 2A16 16 0 014.5 6.2 2 2 0 016.5 4z"></path></svg></span>
            </span>
          </button>
        </div>

        <div class="deliver">
          <p class="deliver__text">Cannot get to any of them? We deliver anywhere in Europe.</p>
          <a class="deliver__go" href="#">ARRANGE DELIVERY</a>
        </div>
      </div>
    </div>
  </section>

</main>

<!-- ============================================================ 11. Footer -->
<footer class="foot">

  <div class="ticker">
    <span class="ticker__open"><span class="dot dot--ink dot--8 pulse" aria-hidden="true"></span>OPEN NOW</span>
    <span class="ticker__shop"><span class="dot dot--ink" aria-hidden="true"></span><span class="ticker__city">Sharjah</span><span class="ticker__time">AL JUBAIL STREET 72</span></span>
    <a class="ticker__phone" href="tel:+971780272673">078&nbsp;027&nbsp;2673<span class="ticker__arrow" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></a>
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
        <p class="footbrand__text">Al Jubail Street 72, Industrial Area-2, Sharjah, United Arab Emirates.<br><a href="tel:+971780272673">078 027 2673</a></p>
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

  

  <div class="footbottom">
    <div class="footbottom__inner">
      <span>&copy; 2026 AL Jazeera AL Hamra UESD Cars Enam Ullah Ahmadi</span>
      <span>Create By Alawoddin Khedmat</span>
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
        <li><span class="auth__tick" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Saved cars across all seven showrooms</li>
        <li><span class="auth__tick" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Test drives you can move or cancel yourself</li>
        <li><span class="auth__tick" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Trade-in offers held for fourteen days</li>
      </ul>
      <div class="auth__proof">
        <div><div class="auth__proof-num">41</div><div class="auth__proof-lbl">CARS THIS WEEK</div></div>
        <span class="auth__proof-sep" aria-hidden="true"></span>
        <div><div class="auth__proof-num">4.9</div><div class="auth__proof-lbl">OWNER RATING</div></div>
      </div>
      <span class="auth__wordmark" aria-hidden="true">AVAVA</span>
    </div>

    <form class="auth__form" id="login-form" action="#" method="post" novalidate>
      <button class="auth__close" type="button" data-auth-close aria-label="Close"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"></path></svg></button>
      <p class="auth__kicker">WELCOME BACK</p>
      <h2 class="auth__title" id="auth-login-title">Sign in to AVAVA</h2>
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

      <p class="auth__swap">New to AVAVA? <button class="auth__swap-btn" type="button" data-auth-to="auth-register">Create an account</button></p>
    </form>
  </div>
</dialog>

<dialog class="modal modal--auth" id="auth-register" aria-labelledby="auth-register-title">
  <div class="auth">
    <div class="auth__aside">
      <p class="auth__eyebrow"><span class="dot dot--accent dot--8" aria-hidden="true"></span>MEMBER ACCESS</p>
      <p class="auth__pitch">Every car you save, every viewing you book and every trade-in offer &mdash; kept in one place.</p>
      <ul class="auth__list">
        <li><span class="auth__tick" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Saved cars across all seven showrooms</li>
        <li><span class="auth__tick" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Test drives you can move or cancel yourself</li>
        <li><span class="auth__tick" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-11"></path></svg></span>Trade-in offers held for fourteen days</li>
      </ul>
      <div class="auth__proof">
        <div><div class="auth__proof-num">41</div><div class="auth__proof-lbl">CARS THIS WEEK</div></div>
        <span class="auth__proof-sep" aria-hidden="true"></span>
        <div><div class="auth__proof-num">4.9</div><div class="auth__proof-lbl">OWNER RATING</div></div>
      </div>
      <span class="auth__wordmark" aria-hidden="true">AVAVA</span>
    </div>

    <form class="auth__form" id="register-form" action="#" method="post" novalidate>
      <button class="auth__close" type="button" data-auth-close aria-label="Close"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"></path></svg></button>
      <p class="auth__kicker">CREATE YOUR ACCOUNT</p>
      <h2 class="auth__title" id="auth-register-title">Join AVAVA</h2>
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
    <h2 class="modal__title" id="book-confirm-title">Book the car for <span id="confirm-time">16:30</span> in <span id="confirm-city">Munich</span>?</h2>
    <p class="modal__text">Forty minutes on your own roads. We hold the car until you arrive &mdash; free, with no obligation, and no sales calls afterwards.</p>
    <dl class="modal__facts">
      <div class="modal__fact"><dt>SHOWROOM</dt><dd id="confirm-city-2">Munich</dd></div>
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
    <p class="modal__text">We are holding the car for <strong id="success-slot">16:30 in Munich</strong>. The confirmation is on its way to your inbox with the address, the route and what to bring with you.</p>
    <dl class="modal__facts">
      <div class="modal__fact"><dt>REFERENCE</dt><dd id="success-ref">AV-000000</dd></div>
      <div class="modal__fact"><dt>ON ARRIVAL</dt><dd>Ask for the AVAVA desk</dd></div>
      <div class="modal__fact"><dt>BRING</dt><dd>Your driving licence</dd></div>
    </dl>
    <div class="modal__actions modal__actions--single">
      <button class="modal__btn modal__btn--ok" type="submit" value="done">Done</button>
    </div>
  </form>
</dialog>

<script src="/frontend/assets/vendor/leaflet/leaflet.js"></script>
<script src="/frontend/assets/js/main1ef4.js?v=5d78136b"></script>
<dialog class="modal" id="ct-done" aria-labelledby="ct-done-title">
  <form class="modal__box modal__box--success" method="dialog">
    <span class="modal__tick" aria-hidden="true"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12l4 4 10-11"></path></svg></span>
    <h2 class="modal__title" id="ct-done-title">Your message is with a specialist</h2>
    <p class="modal__text" id="ct-done-text">Not a queue and not a bot &mdash; the person who can answer it has it now. You will have a written reply within four working hours.</p>
    <dl class="modal__facts">
      <div class="modal__fact"><dt>REFERENCE</dt><dd id="ct-d-ref">CT-000000</dd></div>
      <div class="modal__fact"><dt>ABOUT</dt><dd id="ct-d-topic">Buying a car</dd></div>
      <div class="modal__fact"><dt>DESK</dt><dd id="ct-d-shop">Munich</dd></div>
      <div class="modal__fact"><dt>REPLY TO</dt><dd id="ct-d-email">&mdash;</dd></div>
    </dl>
    <div class="modal__actions modal__actions--single">
      <button class="modal__btn modal__btn--ok" type="submit" value="done">Done</button>
    </div>
  </form>
</dialog>
</body>

<!-- Mirrored from https://demo.gridgum.com/avavamotors/car-marketplace/contact.html by HTTrack Website Copier/3.x [XR&CO], Tue, 06 Oct 2026 16:46:20 GMT -->
</html>
