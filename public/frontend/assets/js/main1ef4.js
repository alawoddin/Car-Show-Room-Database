/* ==========================================================================
   AVAVA — Alpine car marketplace
   Home page behaviour

   CONTENTS
   -----------------------------------------------------------------------
   01. Hero units (--u / --uh / --hdr-h)
   02. Mobile navigation
   03. Quick picks & test-drive slots
   04. Finance calculator
   05. Trade-in estimator
   06. Testimonial marquee
   07. Showroom map (Leaflet + OpenStreetMap)
   08. Test-drive booking dialogs
   09. Sign in / sign up dialogs
   10. How it works — the twelve-day rail
   11. Home v2 — hero search panel
   12. Featured listings — the big card follows the pick
   13. Preloader
   14. Service page — workshop map, slots and needs
   15. Service page — booking dialogs
   ========================================================================== */

(function () {
  'use strict';

  var root = document.documentElement;

  /* ------------------------------------------------------------------ 01 */
  /* The hero is laid out in hundredths of its own box, the way the design
     uses container-query units. 1% of the hero width is not the same as 1vw
     (vw counts the scrollbar), so the exact value is measured here and the
     stylesheet keeps 1vw only as the no-JS fallback. */

  var header = document.querySelector('.hdr');

  function syncUnits() {
    var w = root.clientWidth;
    if (!w) { return; }

    root.style.setProperty('--u', (w / 100) + 'px');

    if (header) {
      root.style.setProperty('--hdr-h', header.offsetHeight + 'px');
    }
  }

  /* ------------------------------------------------------------------ 02 */

  var burger = document.getElementById('burger');
  var nav = document.getElementById('nav');

  function closeNav() {
    if (!nav || !burger) { return; }
    nav.classList.remove('is-open');
    burger.setAttribute('aria-expanded', 'false');
    burger.setAttribute('aria-label', 'Open the menu');
  }

  function initNav() {
    if (!burger || !nav) { return; }

    burger.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      burger.setAttribute('aria-label', open ? 'Close the menu' : 'Open the menu');
    });

    /* Chevron buttons open the panels on touch, where :hover never fires. */
    Array.prototype.forEach.call(nav.querySelectorAll('.nav__toggle'), function (btn) {
      btn.addEventListener('click', function () {
        var open = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', open ? 'false' : 'true');
      });
    });

    document.addEventListener('click', function (e) {
      if (!nav.classList.contains('is-open')) { return; }
      if (nav.contains(e.target) || burger.contains(e.target)) { return; }
      closeNav();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { closeNav(); }
    });
  }

  /* ------------------------------------------------------------------ 03 */
  /* Single-choice button groups: quick picks in the v1 search bar, the
     test-drive slots in the CTA panel, the body-style pills in the v2 hero.
     The chosen class is the selector plus --on, so a group only has to keep
     that naming to work here. */

  function initToggleGroup(container, selector, onPick) {
    if (!container) { return; }

    var buttons = container.querySelectorAll(selector);
    var onClass = selector.replace('.', '') + '--on';

    Array.prototype.forEach.call(buttons, function (btn) {
      btn.addEventListener('click', function () {
        Array.prototype.forEach.call(buttons, function (other) {
          other.setAttribute('aria-pressed', 'false');
          other.classList.remove(onClass);
        });
        btn.setAttribute('aria-pressed', 'true');
        btn.classList.add(onClass);
        if (onPick) { onPick(btn); }
      });
    });
  }

  /* ------------------------------------------------------------------ 04 */
  /* Finance. A $164,900 car over 36 / 60 / 72 months. The deposit and the
     rate are both the visitor's to move; the 60-month plan stays the
     reference the other two are compared against, because that is what the
     copy on the cards says. */

  var PRICE = 164900;
  var TRADE_IN_DEPOSIT = 61400;
  var REFERENCE_TERM = 60;

  var fin = { dep: 30000, apr: 3.9, term: REFERENCE_TERM };

  var depInput = document.getElementById('dep');
  var depOut = document.getElementById('dep-out');
  var depTrade = document.getElementById('dep-trade');
  var depZero = document.getElementById('dep-zero');
  var aprInput = document.getElementById('apr');
  var aprOut = document.getElementById('apr-out');
  var aprFact = document.getElementById('apr-fact');
  var aprPresets = document.querySelectorAll('.dep-btn[data-apr]');
  var plansBox = document.getElementById('plans');

  /* Both the finance table and the trade-in band are money — the switch in
     the utility strip decides how they read. */
  function money(v) {
    return formatMoney(v);
  }

  function monthly(principal, months, rate) {
    var r = rate / 12;
    return principal * r / (1 - Math.pow(1 + r, -months));
  }

  function press(el, on) {
    if (el) { el.setAttribute('aria-pressed', on ? 'true' : 'false'); }
  }

  function renderPlans() {
    if (!plansBox) { return; }

    var principal = Math.max(0, PRICE - fin.dep);
    var rate = fin.apr / 100;

    var rows = [36, 60, 72].map(function (n) {
      var m = monthly(principal, n, rate);
      return { n: n, m: m, total: m * n, interest: m * n - principal };
    });

    var maxInterest = Math.max(rows[0].interest, rows[1].interest, rows[2].interest) || 1;
    var base = rows[1];

    rows.forEach(function (row) {
      var card = plansBox.querySelector('.plan[data-term="' + row.n + '"]');
      if (!card) { return; }

      var diff = row.m - base.m;
      var tradeoff;

      if (row.n === REFERENCE_TERM) {
        tradeoff = 'The one four in five people take';
      } else if (diff > 0) {
        tradeoff = money(diff) + ' more a month than 60';
      } else {
        tradeoff = money(-diff) + ' less a month, ' +
          money(row.interest - base.interest) + ' more interest';
      }

      card.querySelector('.plan__monthly').textContent = money(row.m);
      card.querySelector('.plan__tradeoff').textContent = tradeoff;
      card.querySelector('.plan__interest-val').textContent = money(row.interest);
      card.querySelector('.plan__total-val').textContent = money(row.total + fin.dep);
      card.querySelector('.plan__barfill').style.width =
        Math.round((row.interest / maxInterest) * 100) + '%';

      var picked = row.n === fin.term;
      card.classList.toggle('plan--picked', picked);
      press(card.querySelector('.plan__cta'), picked);
    });
  }

  function renderFinance() {
    if (depOut) { depOut.textContent = money(fin.dep); }
    if (aprOut) { aprOut.textContent = fin.apr.toFixed(1) + '%'; }
    if (aprFact) { aprFact.textContent = fin.apr.toFixed(1) + '%'; }

    press(depTrade, fin.dep === TRADE_IN_DEPOSIT);
    press(depZero, fin.dep === 0);

    Array.prototype.forEach.call(aprPresets, function (btn) {
      press(btn, Math.abs(Number(btn.getAttribute('data-apr')) - fin.apr) < 0.05);
    });

    renderPlans();
  }

  function setDeposit(value) {
    fin.dep = value;
    if (depInput) { depInput.value = String(value); }
    renderFinance();
  }

  function setApr(value) {
    fin.apr = value;
    if (aprInput) { aprInput.value = String(value); }
    renderFinance();
  }

  function initFinance() {
    if (!depInput || !aprInput) { return; }

    depInput.addEventListener('input', function () {
      fin.dep = Number(depInput.value);
      renderFinance();
    });

    aprInput.addEventListener('input', function () {
      fin.apr = Number(aprInput.value);
      renderFinance();
    });

    if (depTrade) {
      depTrade.addEventListener('click', function () { setDeposit(TRADE_IN_DEPOSIT); });
    }

    if (depZero) {
      depZero.addEventListener('click', function () { setDeposit(0); });
    }

    Array.prototype.forEach.call(aprPresets, function (btn) {
      btn.addEventListener('click', function () {
        setApr(Number(btn.getAttribute('data-apr')));
      });
    });

    /* Choosing a term moves the highlight; MOST CHOSEN stays a label on the
       60-month card, it is not the selection. */
    Array.prototype.forEach.call(document.querySelectorAll('.plan__cta[data-pick]'), function (btn) {
      btn.addEventListener('click', function () {
        fin.term = Number(btn.getAttribute('data-pick'));
        renderFinance();
      });
    });

    fin.dep = Number(depInput.value);
    fin.apr = Number(aprInput.value);
    renderFinance();

    onCurrencyChange(renderFinance);
  }

  /* ------------------------------------------------------------------ 05 */
  /* Trade-in. Indicative valuation only — the coefficients belong in
     configuration once this is wired to a real pricing service. */

  var TRADE_BASE = 92000;
  var THIS_YEAR = 2026;
  var CONDITIONS = { 1: 'Fair', 2: 'Good', 3: 'Great', 4: 'Excellent' };

  var tMake = document.getElementById('t-make');
  var tModel = document.getElementById('t-model');
  var tYear = document.getElementById('t-year');
  var tMileage = document.getElementById('t-mileage');
  var tCond = document.getElementById('t-cond');

  function digits(value) {
    var n = parseFloat(String(value).replace(/[^0-9.]/g, ''));
    return isNaN(n) ? 0 : n;
  }

  function renderTrade() {
    if (!tCond) { return; }

    var year = digits(tYear.value);
    var mileage = digits(tMileage.value);
    var cond = Number(tCond.value);

    var ageAdj = Math.max(0.5, 1 - (THIS_YEAR - year) * 0.06);
    var mileAdj = Math.max(0.45, 1 - (mileage / 1000) * 0.006);
    var condAdj = 0.82 + cond * 0.05;
    var mid = TRADE_BASE * ageAdj * mileAdj * condAdj;

    var band = mileage < 20000 ? 'Low'
      : mileage < 50000 ? 'Average'
        : mileage < 80000 ? 'High' : 'Very high';

    document.getElementById('trade-low').textContent = money(mid * 0.93);
    document.getElementById('trade-high').textContent = money(mid * 1.07);
    document.getElementById('trade-desc').textContent =
      year + ' ' + tMake.value + ' ' + tModel.value + ' · ' +
      mileage.toLocaleString('en-US') + ' mi';

    document.getElementById('cond-num').textContent = cond + '/4';
    document.getElementById('cond-label').textContent = CONDITIONS[cond];
    document.getElementById('cond-word').textContent = CONDITIONS[cond];
    document.getElementById('mileage-band').textContent = band;

    document.getElementById('mileage-bar').style.width =
      Math.max(15, Math.min(95, 100 - (mileage / 1000) * 0.9)) + '%';
    document.getElementById('cond-bar').style.width = (cond / 4 * 100) + '%';

    document.getElementById('gauge').style.background =
      'conic-gradient(var(--accent) 0deg ' + (cond / 4) * 360 + 'deg, ' +
      'rgba(255,255,255,.18) ' + (cond / 4) * 360 + 'deg 360deg)';
  }

  function initTrade() {
    if (!tCond) { return; }

    [tMake, tModel, tYear, tMileage, tCond].forEach(function (el) {
      el.addEventListener('input', renderTrade);
    });

    /* Reformatting mid-keystroke fights the caret, so the mileage field is
       tidied up once the field is left. */
    tMileage.addEventListener('blur', function () {
      tMileage.value = digits(tMileage.value).toLocaleString('en-US') + ' mi';
    });

    renderTrade();

    onCurrencyChange(renderTrade);
  }

  /* ------------------------------------------------------------------ 06 */
  /* The marquee scrolls by half its own width, so the cards are duplicated
     once to make the loop seamless. */

  function initMarquee() {
    var track = document.querySelector('.marquee__track');
    if (!track) { return; }

    var cards = Array.prototype.slice.call(track.children);
    cards.forEach(function (card) {
      var copy = card.cloneNode(true);
      copy.setAttribute('aria-hidden', 'true');
      track.appendChild(copy);
    });
  }

  /* ------------------------------------------------------------------ 07 */
  /* Showroom map. Leaflet with OpenStreetMap tiles — no API key and no
     billing account. The attribution the tiles carry is required by the
     OpenStreetMap licence and must stay visible. */

  function initMap() {
    var el = document.getElementById('showroom-map');
    if (!el || typeof L === 'undefined') { return; }

    var showrooms;
    try {
      showrooms = JSON.parse(el.getAttribute('data-showrooms'));
    } catch (e) {
      return;
    }

    var map = L.map(el, {
      scrollWheelZoom: false,   /* let the page scroll past the map */
      zoomControl: false,       /* re-added on the right, clear of the chip */
      attributionControl: true
    });

    L.control.zoom({ position: 'topright' }).addTo(map);

    L.tileLayer('../../../tile.openstreetmap.org/%7bz%7d/%7bx%7d/%7by%7d.png', {
      maxZoom: 18,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    /* Pins are drawn in CSS so they carry the page's own accent. The active
       one is a teardrop, so its anchor sits below the box centre. */
    function pinIcon(active) {
      return L.divIcon({
        className: 'lpin-wrap',
        html: '<span class="lpin' + (active ? ' lpin--active' : '') + '"></span>',
        iconSize: active ? [22, 22] : [16, 16],
        iconAnchor: active ? [11, 31] : [8, 8],
        popupAnchor: active ? [0, -28] : [0, -10]
      });
    }

    var bounds = [];
    var markers = showrooms.map(function (s, i) {
      var marker = L.marker([s.lat, s.lng], { icon: pinIcon(i === 0), title: s.city })
        .addTo(map)
        .bindPopup('<strong>' + s.city + '</strong><br>' + s.address + '<br>' + s.label)
        .on('click', function () { select(i); });
      bounds.push([s.lat, s.lng]);
      return marker;
    });

    /* The cards under the map are the map's controls: data-showroom holds
       the index of the showroom each card stands for. */
    var cards = Array.prototype.slice.call(document.querySelectorAll('.shop[data-showroom]'));
    var current = 0;

    function select(index, pan) {
      if (!showrooms[index]) { return; }
      current = index;

      markers.forEach(function (mk, i) { mk.setIcon(pinIcon(i === index)); });

      cards.forEach(function (card) {
        var on = Number(card.getAttribute('data-showroom')) === index;
        card.setAttribute('aria-pressed', on ? 'true' : 'false');
        card.classList.toggle('shop--ink', on);
      });

      /* Skipped on the first call so the initial fitBounds framing — all
         seven pins in view — is what the visitor sees first. */
      if (pan !== false) {
        map.panTo([showrooms[index].lat, showrooms[index].lng], { animate: true, duration: 0.6 });
      }
    }

    cards.forEach(function (card) {
      card.addEventListener('click', function () {
        select(Number(card.getAttribute('data-showroom')));
      });
    });

    map.fitBounds(bounds, { paddingTopLeft: [40, 70], paddingBottomRight: [40, 40] });
    select(0, false);

    window.addEventListener('resize', function () {
      map.invalidateSize();
      map.panTo([showrooms[current].lat, showrooms[current].lng], { animate: false });
    });
  }

  /* ------------------------------------------------------------------ 08 */
  /* Booking. Two native <dialog> steps: confirm what was picked, then the
     success notice. Esc, the backdrop and focus handling come from the
     browser; the only work here is filling in the slot and chaining them. */

  var bookBtn = document.getElementById('book-cta');
  var confirmDlg = document.getElementById('book-confirm');
  var successDlg = document.getElementById('book-success');

  /* The slot the visitor picked, kept in one place for both dialogs. */
  var booking = { city: 'Munich', time: '16:30' };

  function openDialog(dlg) {
    if (typeof dlg.showModal === 'function') {
      dlg.showModal();
    } else {
      dlg.setAttribute('open', '');   /* very old browsers: inline panel */
    }
  }

  function setText(id, value) {
    var el = document.getElementById(id);
    if (el) { el.textContent = value; }
  }

  function initBooking() {
    if (!bookBtn || !confirmDlg || !successDlg) { return; }

    bookBtn.addEventListener('click', function () {
      setText('confirm-city', booking.city);
      setText('confirm-city-2', booking.city);
      setText('confirm-time', booking.time);
      setText('confirm-time-2', booking.time);
      openDialog(confirmDlg);
    });

    /* A click on the backdrop lands on the dialog itself, never on the box. */
    [confirmDlg, successDlg].forEach(function (dlg) {
      dlg.addEventListener('click', function (e) {
        if (e.target === dlg) { dlg.close('cancel'); }
      });
    });

    /* Bound to the button, not to the dialog's close event: the button is what
       was pressed, and it behaves the same in every engine. */
    var confirmOk = confirmDlg.querySelector('[value="confirm"]');
    if (confirmOk) {
      confirmOk.addEventListener('click', function () {
        setText('success-slot', booking.time + ' in ' + booking.city);
        setText('success-ref', 'AV-' + String(Date.now()).slice(-6));

        window.setTimeout(function () {
          if (confirmDlg.open) { confirmDlg.close('confirm'); }
          openDialog(successDlg);
        }, 0);
      });
    }
  }

  /* ------------------------------------------------------------------ 09 */
  /* Sign in and sign up. Same native <dialog> pattern as the booking step:
     the avatar in the top bar opens the sign-in panel, the two panels swap
     through each other, and a shared confirmation closes the flow. There is
     no backend here — the forms validate and stop. */

  var loginDlg = document.getElementById('auth-login');
  var registerDlg = document.getElementById('auth-register');
  var authDoneDlg = document.getElementById('auth-done');
  var accountBtn = document.getElementById('account-btn');

  function authDone(title, text) {
    var t = document.getElementById('auth-done-title');
    var p = document.getElementById('auth-done-text');
    if (t) { t.textContent = title; }
    if (p) { p.textContent = text; }
    openDialog(authDoneDlg);
  }

  /* Native validity, but reported through the panel's own message so the
     browser bubble does not cover the field on a small screen. */
  function validate(form, errorId) {
    var box = document.getElementById(errorId);
    var ok = form.checkValidity();

    if (box) { box.hidden = ok; }

    if (!ok) {
      var first = form.querySelector(':invalid');
      if (first && typeof first.focus === 'function') { first.focus(); }
    }

    return ok;
  }

  function scorePassword(value) {
    var score = 0;
    if (value.length >= 8) { score += 1; }
    if (value.length >= 12) { score += 1; }
    if (/[A-Z]/.test(value) && /[a-z]/.test(value)) { score += 1; }
    if (/[0-9]/.test(value)) { score += 1; }
    if (/[^A-Za-z0-9]/.test(value)) { score += 1; }
    return Math.min(score, 4);
  }

  function initAuth() {
    if (!loginDlg || !registerDlg) { return; }

    if (accountBtn) {
      accountBtn.addEventListener('click', function () { openDialog(loginDlg); });
    }

    [loginDlg, registerDlg, authDoneDlg].forEach(function (dlg) {
      if (!dlg) { return; }
      dlg.addEventListener('click', function (e) {
        if (e.target === dlg) { dlg.close('cancel'); }
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll('[data-auth-close]'), function (btn) {
      btn.addEventListener('click', function () {
        var dlg = btn.closest('dialog');
        if (dlg) { dlg.close('cancel'); }
      });
    });

    /* Swapping panels: close this one, then open the other on the next tick
       so the browser finishes the first close animation and focus move. */
    Array.prototype.forEach.call(document.querySelectorAll('[data-auth-to]'), function (btn) {
      btn.addEventListener('click', function () {
        var target = document.getElementById(btn.getAttribute('data-auth-to'));
        var current = btn.closest('dialog');
        if (current) { current.close('swap'); }
        if (target) { window.setTimeout(function () { openDialog(target); }, 0); }
      });
    });

    /* Show / hide the password. */
    Array.prototype.forEach.call(document.querySelectorAll('.field__peek'), function (btn) {
      btn.addEventListener('click', function () {
        var input = document.getElementById(btn.getAttribute('data-peek'));
        if (!input) { return; }
        var shown = input.type === 'text';
        input.type = shown ? 'password' : 'text';
        btn.setAttribute('aria-pressed', shown ? 'false' : 'true');
        btn.setAttribute('aria-label', shown ? 'Show password' : 'Hide password');
      });
    });

    /* Strength meter on the sign-up password. */
    var regPw = document.getElementById('reg-pw');
    var meter = document.getElementById('pw-meter');
    var meterLbl = document.getElementById('pw-label');

    if (regPw && meter) {
      var WORDS = ['Use eight characters or more', 'Weak', 'Fair', 'Good', 'Strong'];
      regPw.addEventListener('input', function () {
        var score = regPw.value ? scorePassword(regPw.value) : 0;
        meter.style.width = (score * 25) + '%';
        meter.className = 'meter__fill' +
          (score <= 1 ? ' meter__fill--weak' : score === 2 ? ' meter__fill--fair' : '');
        if (meterLbl) { meterLbl.textContent = WORDS[score]; }
      });
    }

    /* Providers stand in for a real OAuth round trip. */
    Array.prototype.forEach.call(document.querySelectorAll('.provider'), function (btn) {
      btn.addEventListener('click', function () {
        var provider = btn.getAttribute('data-provider');
        var dlg = btn.closest('dialog');
        if (dlg) { dlg.close('provider'); }
        window.setTimeout(function () {
          authDone('You are signed in',
            'Signed in with ' + provider + '. Your saved cars, bookings and trade-in offers are waiting in your profile.');
        }, 0);
      });
    });

    var loginForm = document.getElementById('login-form');
    if (loginForm) {
      loginForm.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!validate(loginForm, 'login-error')) { return; }
        loginDlg.close('done');
        window.setTimeout(function () {
          authDone('You are signed in',
            'Welcome back. Your saved cars, bookings and trade-in offers are waiting in your profile.');
        }, 0);
      });
    }


    var registerForm = document.getElementById('register-form');
    if (registerForm) {
      registerForm.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!validate(registerForm, 'register-error')) { return; }
        var name = document.getElementById('reg-first');
        registerDlg.close('done');
        window.setTimeout(function () {
          authDone('Your account is ready',
            'Welcome to AVAVA' + (name && name.value ? ', ' + name.value : '') +
            '. We have sent a confirmation to your inbox — open it to start saving cars.');
        }, 0);
      });
    }
  }

  /* ------------------------------------------------------------------ 10 */
  /* The twelve-day rail. It walks itself through the four days the first
     time the section comes into view, and after that the day pills drive it
     — by click or with the arrow keys. */

  function initSteps() {
    var box = document.getElementById('steps');
    if (!box) { return; }

    var fill = box.querySelector('.steps__fill');
    var steps = Array.prototype.slice.call(box.querySelectorAll('.step'));
    var pills = steps.map(function (step) { return step.querySelector('.step__day'); });
    if (!fill || !steps.length) { return; }

    var timer = null;

    function show(index) {
      steps.forEach(function (step, i) {
        step.classList.toggle('step--on', i === index);
        if (pills[i]) { pills[i].setAttribute('aria-pressed', i === index ? 'true' : 'false'); }
      });
      /* The rail runs from the first node and stops half a column past the
         chosen one, which is where the design leaves it on day one. */
      fill.style.width = (index * 25 + 12.5) + '%';
    }

    function stopRun() {
      if (timer) { window.clearInterval(timer); timer = null; }
    }

    pills.forEach(function (pill, i) {
      if (!pill) { return; }

      pill.addEventListener('click', function () {
        stopRun();
        show(i);
      });

      pill.addEventListener('keydown', function (e) {
        var next = e.key === 'ArrowRight' ? i + 1 : e.key === 'ArrowLeft' ? i - 1 : -1;
        if (next < 0 || next >= pills.length) { return; }
        e.preventDefault();
        stopRun();
        show(next);
        pills[next].focus();
      });
    });

    /* Walk through the days once, the first time the rail is on screen. */
    var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reduced || typeof IntersectionObserver !== 'function') { return; }

    var seen = false;
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting || seen) { return; }
        seen = true;
        observer.disconnect();

        var i = 0;
        timer = window.setInterval(function () {
          i += 1;
          if (i >= steps.length) { stopRun(); return; }
          show(i);
        }, 900);
      });
    }, { threshold: 0.35 });

    observer.observe(box);
  }

  /* ------------------------------------------------------------------ 11 */
  /* Home v2 only: the search panel that sits in the hero. The controls are
     presentational — nothing is queried — but the price slider and the body
     pills have to behave, or the panel reads as a picture of a form. */

  function initFindPanel() {
    var price = document.getElementById('find-price');
    if (!price) { return; }

    var out = document.getElementById('find-price-out');

    function paint() {
      var min = Number(price.min);
      var max = Number(price.max);
      var share = ((Number(price.value) - min) / (max - min)) * 100;

      /* --p fills the track up to the thumb; the rule lives in home-v2.css. */
      price.style.setProperty('--p', share.toFixed(1) + '%');
      if (out) { out.textContent = money(Number(price.value)); }
    }

    price.addEventListener('input', paint);
    paint();

    initToggleGroup(document.querySelector('.bodypills'), '.bodypill');
  }

  /* ------------------------------------------------------------------ 12 */
  /* Featured listings. The four cards beside the big one are picks: choosing
     one puts that car in the big slot and sends the car that was there into
     the slot just vacated. Nothing is duplicated and nothing disappears, so
     the five cars on show stay the same five. */

  function initFeatured() {
    var grid = document.getElementById('featured');
    if (!grid) { return; }

    var big = grid.querySelector('.fcard--tall');
    var picks = Array.prototype.slice.call(grid.querySelectorAll('.fcard:not(.fcard--tall)'));
    if (!big || !picks.length) { return; }

    picks.forEach(function (pick) {
      pick.addEventListener('click', function () {
        var incoming = pick.innerHTML;
        pick.innerHTML = big.innerHTML;
        big.innerHTML = incoming;

        /* Restart the fade: removing the class and reading offsetWidth makes
           the browser drop the finished animation before it is added again. */
        big.classList.remove('fcard--swapped');
        pick.classList.remove('fcard--swapped');
        void big.offsetWidth;
        big.classList.add('fcard--swapped');
        pick.classList.add('fcard--swapped');
      });
    });
  }

  /* ------------------------------------------------------------------ 13 */
  /* Preloader. The outlined wordmark fills with the accent as the images
     arrive. Real progress, not a fake timer — but with a floor so it always
     moves, and a hard stop so a single slow asset can never hold the page. */

  var PRELOAD_LIMIT = 6000;   /* ms — after this the page opens regardless */
  var PRELOAD_MIN = 700;      /* ms — below this the fill is a flicker, not a fill */
  var MARK_HEIGHT = 51;       /* the logo viewBox, which the rect rides */

  function initPreloader() {
    var box = document.getElementById('preloader');
    if (!box) { return; }

    var rect = document.getElementById('pl-rect');
    var pct = document.getElementById('preloader-pct');
    var body = document.body;
    var shown = 0;
    var timer = null;
    var done = false;
    var started = new Date().getTime();

    body.classList.add('is-loading');

    function draw(value) {
      shown = Math.max(shown, Math.min(100, value));   /* never runs backwards */
      if (rect) { rect.setAttribute('y', (MARK_HEIGHT * (1 - shown / 100)).toFixed(2)); }
      if (pct) { pct.textContent = String(Math.round(shown)); }
    }

    function loaded() {
      var images = document.images;
      if (!images.length) { return 100; }

      var ready = 0;
      Array.prototype.forEach.call(images, function (img) {
        if (img.complete) { ready += 1; }
      });
      return (ready / images.length) * 100;
    }

    function finish() {
      if (done) { return; }

      /* On a fast connection the load event fires before the fill has visibly
         moved; hold the floor so it reads as a fill rather than a blink. */
      var waited = new Date().getTime() - started;
      if (waited < PRELOAD_MIN) {
        window.setTimeout(finish, PRELOAD_MIN - waited);
        return;
      }

      done = true;
      window.clearInterval(timer);
      draw(100);

      window.setTimeout(function () {
        box.classList.add('preloader--done');
        body.classList.remove('is-loading');
        window.setTimeout(function () {
          if (box.parentNode) { box.parentNode.removeChild(box); }
        }, 500);
      }, 240);
    }

    /* Real progress, capped at 96 so the last step belongs to the load event
       — otherwise the bar sits full while the page is still assembling.

       Elapsed time caps it as well, and that is not decoration: on a second
       visit every image is already in cache, loaded() returns 100 on the first
       tick, and the mark is full before it has been looked at. A bar that
       starts full is not a preloader — the map pages read as having none at
       all. Real progress still holds it back; the clock only stops it from
       teleporting. */
    timer = window.setInterval(function () {
      var waited = new Date().getTime() - started;
      draw(Math.min(96, loaded(), (waited / PRELOAD_MIN) * 100));
    }, 90);

    draw(8);

    if (document.readyState === 'complete') {
      finish();
    } else {
      window.addEventListener('load', finish);
    }

    window.setTimeout(finish, PRELOAD_LIMIT);
  }

  /* ------------------------------------------------------------------ 14 */
  /* Service page. The workshop map uses the same Leaflet setup as the
     showroom map, but its markers are name pills rather than dots, because
     the visitor is choosing a workshop, not browsing a region. */

  function initWorkshopMap() {
    var el = document.getElementById('workshop-map');
    if (!el || typeof L === 'undefined') { return; }

    var shops;
    try {
      shops = JSON.parse(el.getAttribute('data-workshops'));
    } catch (e) {
      return;
    }

    var map = L.map(el, {
      scrollWheelZoom: false,
      zoomControl: false,
      attributionControl: true
    });

    L.control.zoom({ position: 'topright' }).addTo(map);

    L.tileLayer('../../../tile.openstreetmap.org/%7bz%7d/%7bx%7d/%7by%7d.png', {
      maxZoom: 18,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    function pill(shop, active) {
      var dot = '<span class="wpin__dot' + (shop.open ? '' : ' wpin__dot--shut') + '"></span>';
      return L.divIcon({
        className: 'wpin-wrap',
        html: '<span class="wpin' + (active ? ' wpin--active' : '') + '">' + dot + shop.city + '</span>',
        iconSize: [0, 0],
        iconAnchor: [0, 0]
      });
    }

    var bounds = [];
    var markers = shops.map(function (shop, i) {
      var marker = L.marker([shop.lat, shop.lng], { icon: pill(shop, i === 0), title: shop.city })
        .addTo(map)
        .bindPopup('<strong>' + shop.city + '</strong><br>' + shop.bays + ' bays &middot; ' +
                   (shop.open ? 'open now' : 'closed'))
        .on('click', function () { select(i); });
      bounds.push([shop.lat, shop.lng]);
      return marker;
    });

    /* The list under the map is the map's control panel, and the bar over it
       reports whatever is chosen. */
    var rows = Array.prototype.slice.call(document.querySelectorAll('.wshop[data-workshop]'));
    var near = document.getElementById('wmap-near');

    function select(index, pan) {
      var shop = shops[index];
      if (!shop) { return; }

      markers.forEach(function (mk, i) { mk.setIcon(pill(shops[i], i === index)); });

      rows.forEach(function (row) {
        var on = Number(row.getAttribute('data-workshop')) === index;
        row.setAttribute('aria-pressed', on ? 'true' : 'false');
        row.classList.toggle('wshop--on', on);
      });

      if (near) {
        near.textContent = shop.city + ' \u2014 ' + shop.bays + ' bays, ' +
          (shop.open ? 'open until ' + shop.until : 'closed \u00b7 opens tomorrow');
      }

      if (pan !== false) {
        map.panTo([shop.lat, shop.lng], { animate: true, duration: 0.6 });
      }
    }

    rows.forEach(function (row) {
      row.addEventListener('click', function () {
        select(Number(row.getAttribute('data-workshop')));
      });
    });

    /* Room at the bottom for the "nearest to you" bar that sits over the map. */
    map.fitBounds(bounds, { paddingTopLeft: [50, 60], paddingBottomRight: [50, 130] });
    select(0, false);

    window.addEventListener('resize', function () { map.invalidateSize(); });
  }

  /* ------------------------------------------------------------------ 15 */
  /* Service booking. Same two-step pattern as the test drive on the home
     page: confirm what was chosen, then say it is done. Nothing is sent —
     the form stops here until a backend is wired to it. */

  function initServiceBooking() {
    var form = document.getElementById('bookform');
    var confirmDlg = document.getElementById('svc-confirm');
    var doneDlg = document.getElementById('svc-done');
    if (!form || !confirmDlg || !doneDlg) { return; }

    function chosen(selector, fallback) {
      var el = document.querySelector(selector);
      return el ? el.textContent.trim() : fallback;
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var box = document.getElementById('book-error');
      var ok = form.checkValidity();
      if (box) { box.hidden = ok; }

      if (!ok) {
        var first = form.querySelector(':invalid');
        if (first && typeof first.focus === 'function') { first.focus(); }
        return;
      }

      var car = document.getElementById('b-vehicle');
      var shop = document.getElementById('b-shop');
      var collect = document.getElementById('b-collect');

      setText('svc-c-service', chosen('.needchip--on', 'a workshop visit'));
      setText('svc-c-slot', chosen('.slot-pill--on', 'the next free slot'));
      setText('svc-c-shop', shop ? shop.value : 'Munich');
      setText('svc-c-car', car && car.value ? car.value : 'Vehicle details to follow');
      setText('svc-c-collect', collect ? collect.value : 'I will drop it off');

      openDialog(confirmDlg);
    });

    /* Bound to the button rather than to the dialog's close event: the button
       is the thing that was pressed, and it behaves the same in every engine. */
    var okBtn = confirmDlg.querySelector('[value="confirm"]');
    if (okBtn) {
      okBtn.addEventListener('click', function () {
        var shop = document.getElementById('b-shop');
        var slot = chosen('.slot-pill--on', 'the next free slot');
        var city = shop ? shop.value : 'Munich';

        setText('svc-d-ref', 'SV-' + String(Date.now()).slice(-6));
        setText('svc-done-text',
          'We are expecting you in ' + city + ' on ' + slot.replace(' \u00b7 ', ', ') +
          '. The written quote follows within four working hours of the inspection.');

        /* Next tick, so the confirm panel has finished closing behind it. */
        window.setTimeout(function () {
          if (confirmDlg.open) { confirmDlg.close('confirm'); }
          openDialog(doneDlg);
        }, 0);
      });
    }

    /* A click on the backdrop is a cancel, as everywhere else on the site. */
    [confirmDlg, doneDlg].forEach(function (dlg) {
      dlg.addEventListener('click', function (e) {
        if (e.target === dlg) { dlg.close('cancel'); }
      });
    });
  }

  /* ------------------------------------------------------------------ 16 */
  /* Pricing — monthly / annual billing switch.
     The annual price is ten months spread over twelve, so the two free
     months are visible in the figure itself and not only in the badge. */

  var PLAN_MONTHLY = { p1: 149, p2: 389, p3: 849 };

  function initPlans() {
    var track = document.querySelector('.planswitch__track');
    if (!track) { return; }

    var btns = Array.prototype.slice.call(track.querySelectorAll('.planswitch__btn'));
    var billed = Array.prototype.slice.call(document.querySelectorAll('[data-billed]'));

    function paint(annual) {
      Object.keys(PLAN_MONTHLY).forEach(function (id) {
        var el = document.getElementById(id);
        if (!el) { return; }
        var m = PLAN_MONTHLY[id];
        el.textContent = formatMoney(annual ? (m * 10) / 12 : m);
      });
      billed.forEach(function (el) {
        el.textContent = annual
          ? 'Billed yearly \u2014 two months free'
          : 'Billed monthly, cancel any time';
      });
      btns.forEach(function (b) {
        var on = (b.getAttribute('data-plan') === 'annual') === annual;
        b.classList.toggle('planswitch__btn--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
    }

    var annualNow = false;

    btns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        annualNow = btn.getAttribute('data-plan') === 'annual';
        paint(annualNow);
      });
    });

    /* The tier figures are money too — repaint them when the currency changes. */
    onCurrencyChange(function () { paint(annualNow); });
  }

  /* ------------------------------------------------------------------ 17 */
  /* Compare — three cars, eleven rows, one table.
     The winner rule is the whole page: a cell is BEST only when exactly ONE
     car holds the best figure. Ties mark nobody, and zero means "not
     applicable", never a value — without that an electric car would "win"
     fuel economy with a zero. The markup already ships in the initial state,
     so this module only has to re-read on a change. */

  var CMP_CARS = [
    { id: 'p911',   make: 'PORSCHE',      model: '911 Carrera GTS',   body: 'COUPE',    img: 'porsche-911-carrera-gts',
      price: 164900, monthly: 1880, power: 473,  accel: 3.7, top: 193, range: 0,   mpg: 24, seats: 4, warranty: 12, boot: 264, co2: 239 },
    { id: 'amggt',  make: 'MERCEDES-AMG', model: 'AMG GT 63',         body: 'COUPE',    img: 'mercedes-amg-gt63',
      price: 178000, monthly: 2040, power: 577,  accel: 3.2, top: 196, range: 0,   mpg: 21, seats: 4, warranty: 24, boot: 321, co2: 276 },
    { id: 'taycan', make: 'PORSCHE',      model: 'Taycan Turbo GT',   body: 'ELECTRIC', img: 'porsche-taycan-turbo-gt',
      price: 231000, monthly: 2640, power: 1020, accel: 2.1, top: 180, range: 555, mpg: 0,  seats: 4, warranty: 24, boot: 407, co2: 0 },
    { id: 'm4',     make: 'BMW',          model: 'M4 Competition',    body: 'COUPE',    img: 'bmw-m4-competition',
      price: 92300,  monthly: 1060, power: 530,  accel: 3.5, top: 180, range: 0,   mpg: 23, seats: 4, warranty: 12, boot: 440, co2: 251 },
    { id: 'etron',  make: 'AUDI',         model: 'RS e-tron GT',      body: 'ELECTRIC', img: 'audi-rs-etron-gt',
      price: 147000, monthly: 1680, power: 637,  accel: 3.3, top: 155, range: 501, mpg: 0,  seats: 5, warranty: 24, boot: 366, co2: 0 },
    { id: 'conti',  make: 'BENTLEY',      model: 'Continental GT',    body: 'LUXURY',   img: 'bentley-continental-gt',
      price: 265000, monthly: 3030, power: 650,  accel: 3.6, top: 208, range: 0,   mpg: 19, seats: 4, warranty: 36, boot: 358, co2: 311 },
    { id: 'rrs',    make: 'LAND ROVER',   model: 'Range Rover Sport', body: 'SUV',      img: 'land-rover-range-rover-sport',
      price: 96400,  monthly: 1120, power: 468,  accel: 4.9, top: 150, range: 0,   mpg: 22, seats: 5, warranty: 24, boot: 835, co2: 262 },
    { id: 'plaid',  make: 'TESLA',        model: 'Model S Plaid',     body: 'ELECTRIC', img: 'tesla-model-s-plaid',
      price: 109990, monthly: 1260, power: 1020, accel: 2.1, top: 200, range: 600, mpg: 0,  seats: 5, warranty: 48, boot: 793, co2: 0 }
  ];

  function cmpMoney(v) { return formatMoney(v); }

  var CMP_GROUPS = [
    ['COST', 'What it takes to own', [
      ['PRICE',    'On-the-road, inc. taxes', function (c) { return cmpMoney(c.price); },           function (c) { return c.price; },    'low'],
      ['MONTHLY',  '60 months, 3.9% APR',     function (c) { return cmpMoney(c.monthly) + '/mo'; }, function (c) { return c.monthly; },  'low'],
      ['WARRANTY', 'AVAVA cover, months',     function (c) { return c.warranty + ' months'; },      function (c) { return c.warranty; }, 'high']
    ]],
    ['PERFORMANCE', 'Manufacturer figures', [
      ['POWER',            'Combined output',             function (c) { return c.power + ' hp'; },          function (c) { return c.power; }, 'high'],
      ['0–100 KM/H',  'Launch control where fitted', function (c) { return c.accel.toFixed(1) + ' s'; }, function (c) { return c.accel; }, 'low'],
      ['TOP SPEED',        'Electronically limited',      function (c) { return c.top + ' mph'; },           function (c) { return c.top; },   'high']
    ]],
    ['RUNNING & PRACTICAL', 'Day to day', [
      ['RANGE',       'WLTP electric range',   function (c) { return c.range ? c.range + ' km' : '—'; },  function (c) { return c.range; }, 'high'],
      ['ECONOMY',     'Combined, petrol only', function (c) { return c.mpg ? c.mpg + ' mpg' : 'Electric'; },   function (c) { return c.mpg; },   'high'],
      ['CO₂',    'Combined, g/km',        function (c) { return c.co2 ? c.co2 + ' g/km' : '0 g/km'; },    function (c) { return c.co2; },   'low'],
      ['BOOT',        'Rear seats up, litres', function (c) { return c.boot + ' L'; },                         function (c) { return c.boot; },  'high'],
      ['SEATS',       'Homologated',           function (c) { return String(c.seats); },                       function (c) { return c.seats; }, null]
    ]]
  ];

  function initCompare() {
    var cmp = document.getElementById('cmp');
    if (!cmp) { return; }

    var selects = Array.prototype.slice.call(cmp.querySelectorAll('.pick__select'));
    var cards = Array.prototype.slice.call(cmp.querySelectorAll('.pick'));
    var ctas = Array.prototype.slice.call(cmp.querySelectorAll('.bookcta'));
    var spec = cmp.querySelector('.spec');
    var specHead = document.getElementById('spec-head');
    var verdictEl = document.getElementById('verdict');
    if (selects.length !== 3 || cards.length !== 3 || !spec || !specHead || !verdictEl) { return; }

    var pick = selects.map(function (s) { return s.value; });

    function byId(id) {
      for (var i = 0; i < CMP_CARS.length; i++) { if (CMP_CARS[i].id === id) { return CMP_CARS[i]; } }
      return CMP_CARS[0];
    }

    /* One pass over every row: the cells, the per-car tally and the count. */
    function score(picked) {
      var tally = [0, 0, 0];
      var rowCount = 0;
      var groups = CMP_GROUPS.map(function (g) {
        return {
          title: g[0],
          note: g[1],
          rows: g[2].map(function (def) {
            rowCount++;
            var val = def[3];
            var dir = def[4];
            var nums = picked.map(val);
            var bestVal = null;
            if (dir) {
              var usable = nums.filter(function (n) { return n > 0; });
              if (usable.length) {
                bestVal = dir === 'high' ? Math.max.apply(null, usable) : Math.min.apply(null, usable);
              }
            }
            var winners = nums.map(function (n) { return bestVal !== null && n === bestVal && n > 0; });
            var onlyOne = winners.filter(Boolean).length === 1;
            if (onlyOne) {
              winners.forEach(function (w, i) { if (w) { tally[i]++; } });
            }
            return {
              label: def[0],
              note: def[1],
              cells: picked.map(function (c, i) {
                var text = def[2](c);
                return { text: text, best: onlyOne && winners[i], dash: text === '—' };
              })
            };
          })
        };
      });
      return { groups: groups, tally: tally, rowCount: rowCount };
    }

    function tick() {
      var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
      svg.setAttribute('width', '9');
      svg.setAttribute('height', '9');
      svg.setAttribute('viewBox', '0 0 24 24');
      svg.setAttribute('fill', 'none');
      svg.setAttribute('stroke', 'currentColor');
      svg.setAttribute('stroke-width', '3.4');
      svg.setAttribute('stroke-linecap', 'round');
      svg.setAttribute('stroke-linejoin', 'round');
      svg.setAttribute('aria-hidden', 'true');
      svg.setAttribute('focusable', 'false');
      var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      path.setAttribute('d', 'M5 12.5l4.5 4.5L19 7');
      svg.appendChild(path);
      return svg;
    }

    function el(tag, cls, text) {
      var n = document.createElement(tag);
      if (cls) { n.className = cls; }
      if (text !== undefined) { n.textContent = text; }
      return n;
    }

    function paintTable(model, picked) {
      /* The column headers are read-only for screen readers, but they have to
         follow the selection or a row would be announced against the wrong car. */
      var heads = specHead.querySelectorAll('th');
      picked.forEach(function (c, i) {
        if (heads[i + 1]) { heads[i + 1].textContent = c.make + ' ' + c.model; }
      });

      var frag = document.createDocumentFragment();
      model.groups.forEach(function (g) {
        var body = el('tbody', 'spec__group');
        var head = el('tr', 'spec__grouphead');
        var title = el('th', 'spec__grouptitle', g.title);
        title.setAttribute('scope', 'colgroup');
        var note = el('td', 'spec__groupnote', g.note);
        note.setAttribute('colspan', '3');
        head.appendChild(title);
        head.appendChild(note);
        body.appendChild(head);

        g.rows.forEach(function (r, ri) {
          var row = el('tr', 'spec__row' + (ri % 2 ? ' spec__row--alt' : ''));
          var lbl = el('th', 'spec__head');
          lbl.setAttribute('scope', 'row');
          lbl.appendChild(el('span', 'spec__label', r.label));
          lbl.appendChild(el('span', 'spec__note', r.note));
          row.appendChild(lbl);

          r.cells.forEach(function (c) {
            var cell = el('td', 'spec__cell');
            var mod = c.best ? ' spec__val--best' : (c.dash ? ' spec__val--na' : '');
            cell.appendChild(el('span', 'spec__val' + mod, c.text));
            if (c.best) {
              var pill = el('span', 'best');
              var t = el('span', 'best__tick');
              t.setAttribute('aria-hidden', 'true');
              t.appendChild(tick());
              pill.appendChild(t);
              pill.appendChild(document.createTextNode('BEST'));
              cell.appendChild(pill);
            }
            row.appendChild(cell);
          });
          body.appendChild(row);
        });
        frag.appendChild(body);
      });

      var old = spec.querySelectorAll('tbody');
      for (var i = 0; i < old.length; i++) { spec.removeChild(old[i]); }
      spec.appendChild(frag);
    }

    function paint() {
      var picked = pick.map(byId);
      var model = score(picked);
      var tally = model.tally;
      var rowCount = model.rowCount;

      var top = Math.max.apply(null, tally);
      var leaders = [];
      tally.forEach(function (t, i) { if (t === top) { leaders.push(i); } });
      var sole = (top > 0 && leaders.length === 1) ? leaders[0] : -1;

      paintTable(model, picked);

      cards.forEach(function (card, i) {
        var c = picked[i];
        var lead = i === sole;
        var wins = tally[i];

        var img = card.querySelector('.pick__img');
        img.src = 'assets/img/cars/' + c.img + '.webp';
        img.alt = c.make + ' ' + c.model + ', front three-quarter view';

        card.classList.toggle('pick--lead', lead);
        card.querySelector('.pick__body').textContent = c.body;
        card.querySelector('.pick__tag').textContent = lead ? 'LEADS ON PAPER' : (wins ? wins + ' WINS' : 'NO WINS');
        card.querySelector('.pick__make').textContent = c.make;
        card.querySelector('.pick__model').textContent = c.model;
        card.querySelector('.pick__price').textContent = cmpMoney(c.price);
        card.querySelector('.pick__monthly').textContent = cmpMoney(c.monthly) + ' / mo';
        card.querySelector('.pick__fill').className = 'pick__fill pick__fill--w' + wins;
        card.querySelector('.pick__wins').textContent = wins + '/' + rowCount + ' BEST';

        ctas[i].classList.toggle('bookcta--lead', lead);
        ctas[i].childNodes[0].nodeValue = 'Book ' + c.model;
      });

      verdictEl.textContent = top === 0
        ? 'No clear winner on these eleven rows'
        : (leaders.length > 1
            ? 'Tied on ' + top + ' of ' + rowCount + ' rows'
            : picked[sole].model + ' leads ' + top + ' of ' + rowCount + ' rows');
    }

    selects.forEach(function (sel, i) {
      sel.addEventListener('change', function () {
        pick[i] = sel.value;
        paint();
      });
    });

    /* The markup already ships the initial state, but Firefox and Safari
       restore the selects across a reload — repaint so the table always
       describes the three cars the dropdowns actually show. */
    paint();

    /* Prices, monthly figures and the cards all restate money. */
    onCurrencyChange(paint);
  }

  /* ------------------------------------------------------------------ 18 */
  /* FAQ — six topics, three answers each.
     Picking a topic swaps, in one render: the three accordion items, the panel
     title, the count chip and the three guarantee cells in the dark panel, and
     reopens the first answer. The accordion itself is one-at-a-time, and an
     open item closes on a second click, so all three can be shut. */

  var FAQ_TOPICS = [
    {
      "id": "fees",
      "cat": "PRICE & FEES",
      "title": "Fees, in full",
      "qs": [
        {
          "q": "Is the advertised price the final price?",
          "cat": "PRICE & FEES",
          "count": "ASKED 2,410×",
          "fact": "€0 in added fees",
          "a": "Apart from registration and road tax, paid to the authority at cost and shown as a separate line. No buyer fee, no admin charge, no documentation fee, no delivery surcharge inside Europe."
        },
        {
          "q": "Do I get a better price paying cash?",
          "cat": "PRICE & FEES",
          "count": "ASKED 780×",
          "fact": "Same price either way",
          "a": "No. We take nothing from the finance, so there is nothing to discount for avoiding it."
        },
        {
          "q": "Can I see the invoice before I commit?",
          "cat": "PRICE & FEES",
          "count": "ASKED 610×",
          "fact": "Itemised, on request",
          "a": "Ask and we send the itemised handover invoice for that specific car before you book a viewing."
        }
      ],
      "facts": [
        {
          "title": "NO BUYER FEE",
          "note": "Ever, on any car"
        },
        {
          "title": "NO ADMIN CHARGE",
          "note": "Paperwork is not a product"
        },
        {
          "title": "TAX AT COST",
          "note": "Paid straight to the authority"
        }
      ]
    },
    {
      "id": "delivery",
      "cat": "BUYING A CAR",
      "title": "Delivery and collection",
      "qs": [
        {
          "q": "How long does delivery take?",
          "cat": "BUYING A CAR",
          "count": "ASKED 1,640×",
          "fact": "48h to most of Europe",
          "a": "Most cars leave within 48 hours of the paperwork clearing, on a covered transporter, to your door."
        },
        {
          "q": "Is delivery really included?",
          "cat": "BUYING A CAR",
          "count": "ASKED 920×",
          "fact": "No distance surcharge",
          "a": "Anywhere we cover inside Europe, at any distance. No surcharge appears on the invoice."
        },
        {
          "q": "Can I collect from the showroom instead?",
          "cat": "BUYING A CAR",
          "count": "ASKED 430×",
          "fact": "Any of seven doors",
          "a": "Yes, from whichever of the seven showrooms suits you — we move the car there at our cost."
        }
      ],
      "facts": [
        {
          "title": "48 HOURS",
          "note": "Typical time to your door"
        },
        {
          "title": "COVERED TRANSPORT",
          "note": "Never driven to you"
        },
        {
          "title": "7 SHOWROOMS",
          "note": "Collect from any of them"
        }
      ]
    },
    {
      "id": "warranty",
      "cat": "SERVICE & WARRANTY",
      "title": "Warranty and servicing",
      "qs": [
        {
          "q": "Does servicing here void my warranty?",
          "cat": "SERVICE & WARRANTY",
          "count": "ASKED 1,240×",
          "fact": "12 months cover",
          "a": "No. Factory parts, manufacturer schedule, book stamped — the warranty stays intact across the EU under block exemption."
        },
        {
          "q": "What does the twelve-month cover include?",
          "cat": "SERVICE & WARRANTY",
          "count": "ASKED 870×",
          "fact": "Parts and labour",
          "a": "Parts and labour on every mechanical and electrical component we inspected, at all seven workshops."
        },
        {
          "q": "Do you service cars bought elsewhere?",
          "cat": "SERVICE & WARRANTY",
          "count": "ASKED 520×",
          "fact": "Any car we would sell",
          "a": "Any car we would have sold ourselves. If a marque specialist suits you better we will say so."
        }
      ],
      "facts": [
        {
          "title": "200 POINTS",
          "note": "Published before you ask"
        },
        {
          "title": "12 MONTHS",
          "note": "Parts and labour"
        },
        {
          "title": "7 WORKSHOPS",
          "note": "One standard in all of them"
        }
      ]
    },
    {
      "id": "partex",
      "cat": "SELLING & TRADE-IN",
      "title": "Selling and part exchange",
      "qs": [
        {
          "q": "How firm is the online valuation?",
          "cat": "SELLING & TRADE-IN",
          "count": "ASKED 1,180×",
          "fact": "Within 5%, 9 times in 10",
          "a": "It uses the same figures our buyers use; the written offer after inspection lands within 5% of it nine times in ten."
        },
        {
          "q": "Can I trade in a car on finance?",
          "cat": "SELLING & TRADE-IN",
          "count": "ASKED 760×",
          "fact": "We settle your lender",
          "a": "We settle the outstanding balance with your lender directly and put any equity toward the new car."
        },
        {
          "q": "Do I have to buy from you to sell?",
          "cat": "SELLING & TRADE-IN",
          "count": "ASKED 340×",
          "fact": "Paid the same day",
          "a": "No. We buy outright and pay by transfer the day the inspection clears."
        }
      ],
      "facts": [
        {
          "title": "SAME-DAY PAYMENT",
          "note": "Once the inspection clears"
        },
        {
          "title": "FINANCE SETTLED",
          "note": "Straight with your lender"
        },
        {
          "title": "NO OBLIGATION",
          "note": "Selling does not mean buying"
        }
      ]
    },
    {
      "id": "finance",
      "cat": "FINANCE",
      "title": "Finance, one rate",
      "qs": [
        {
          "q": "What rate do you offer?",
          "cat": "FINANCE",
          "count": "ASKED 1,510×",
          "fact": "3.9% APR representative",
          "a": "3.9% APR representative across the range, quoted before you apply and not adjusted by which car you pick. Terms from 24 to 72 months."
        },
        {
          "q": "Does applying affect my credit file?",
          "cat": "FINANCE",
          "count": "ASKED 690×",
          "fact": "Soft search first",
          "a": "The initial quote is a soft search and leaves no mark. Only the full application records a hard search."
        },
        {
          "q": "Can I settle early?",
          "cat": "FINANCE",
          "count": "ASKED 410×",
          "fact": "No penalty, ever",
          "a": "At any time, and you pay interest only to the settlement date — no early-repayment penalty on any agreement we write."
        }
      ],
      "facts": [
        {
          "title": "ONE RATE",
          "note": "Same car, same figure"
        },
        {
          "title": "SOFT SEARCH",
          "note": "Quote leaves no mark"
        },
        {
          "title": "NO PENALTY",
          "note": "Settle whenever you like"
        }
      ]
    },
    {
      "id": "ret",
      "cat": "BUYING A CAR",
      "title": "The fourteen-day return",
      "qs": [
        {
          "q": "What if I change my mind after delivery?",
          "cat": "BUYING A CAR",
          "count": "ASKED 1,890×",
          "fact": "14 days to return",
          "a": "Fourteen days from handover, no questions asked. We collect the car at our cost and refund in full, delivery included."
        },
        {
          "q": "Are there mileage limits on the return?",
          "cat": "BUYING A CAR",
          "count": "ASKED 560×",
          "fact": "1,000 km included",
          "a": "Up to 1,000 km. Beyond that we deduct only the running cost, itemised."
        },
        {
          "q": "How long does the refund take?",
          "cat": "BUYING A CAR",
          "count": "ASKED 300×",
          "fact": "Three working days",
          "a": "Three working days from collection, back to the account the money came from."
        }
      ],
      "facts": [
        {
          "title": "14 DAYS",
          "note": "To change your mind"
        },
        {
          "title": "FULL REFUND",
          "note": "Delivery included"
        },
        {
          "title": "WE COLLECT",
          "note": "At our cost, not yours"
        }
      ]
    }
  ];

  function initFaq() {
    var panel = document.getElementById('qalist');
    if (!panel) { return; }

    var chips = Array.prototype.slice.call(document.querySelectorAll('.topic'));
    var pledges = document.getElementById('pledges');
    var titleEl = document.getElementById('panel-title');
    var countEl = document.getElementById('panel-count');
    if (!chips.length || !pledges || !titleEl || !countEl) { return; }

    var topic = 'fees';
    var open = 0;

    function byId(id) {
      for (var i = 0; i < FAQ_TOPICS.length; i++) {
        if (FAQ_TOPICS[i].id === id) { return FAQ_TOPICS[i]; }
      }
      return FAQ_TOPICS[0];
    }

    function svg(size, width, paths) {
      var s = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
      s.setAttribute('width', size);
      s.setAttribute('height', size);
      s.setAttribute('viewBox', '0 0 24 24');
      s.setAttribute('fill', 'none');
      s.setAttribute('stroke', 'currentColor');
      s.setAttribute('stroke-width', width);
      s.setAttribute('stroke-linecap', 'round');
      s.setAttribute('stroke-linejoin', 'round');
      s.setAttribute('aria-hidden', 'true');
      s.setAttribute('focusable', 'false');
      paths.forEach(function (d) {
        var p = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        p.setAttribute('d', d);
        s.appendChild(p);
      });
      return s;
    }

    function node(tag, cls, text) {
      var n = document.createElement(tag);
      if (cls) { n.className = cls; }
      if (text !== undefined) { n.textContent = text; }
      return n;
    }

    function buildItem(r, i) {
      var isOpen = i === open;

      var wrap = node('div', 'qa' + (isOpen ? ' qa--open' : ''));

      var mark = node('span', 'qa__mark');
      mark.setAttribute('aria-hidden', 'true');
      mark.appendChild(svg('13', '2.4', ['M12 5v14M5 12h14']));

      var meta = node('span', 'qa__meta');
      meta.appendChild(node('span', 'qa__cat', r.cat));
      var dot = node('span', 'qa__dot');
      dot.setAttribute('aria-hidden', 'true');
      meta.appendChild(dot);
      meta.appendChild(node('span', 'qa__count', r.count));

      var text = node('span', 'qa__text');
      text.appendChild(meta);
      text.appendChild(node('span', 'qa__q', r.q));

      var btn = node('button', 'qa__summary');
      btn.type = 'button';
      btn.id = 'qa-b' + i;
      btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      btn.setAttribute('aria-controls', 'qa-p' + i);
      btn.appendChild(mark);
      btn.appendChild(text);
      btn.addEventListener('click', function () {
        open = (i === open) ? -1 : i;
        syncOpen();
      });

      var h = node('h4', 'qa__h');
      h.appendChild(btn);
      wrap.appendChild(h);

      var foot = node('p', 'qa__foot');
      foot.appendChild(node('span', 'qa__fact', r.fact));
      var link = node('a', 'qa__policy', 'READ THE POLICY');
      link.href = '#';
      foot.appendChild(link);

      /* A named <section> is a region on its own — no role attribute needed. */
      var body = node('section', 'qa__answer');
      body.id = 'qa-p' + i;
      body.setAttribute('aria-labelledby', 'qa-b' + i);
      if (!isOpen) { body.hidden = true; }
      body.appendChild(node('p', 'qa__a', r.a));
      body.appendChild(foot);
      wrap.appendChild(body);

      return wrap;
    }

    /* Flip the existing nodes rather than rebuilding them. Rebuilding would
       detach the button inside its own click handler — focus would fall to
       the body, the state change would go unannounced, and the marker would
       have no previous value to run its plus-to-cross transition from. */
    function syncOpen() {
      Array.prototype.forEach.call(panel.children, function (item, i) {
        var on = i === open;
        var btn = item.querySelector('.qa__summary');
        var body = item.querySelector('.qa__answer');
        item.classList.toggle('qa--open', on);
        if (btn) { btn.setAttribute('aria-expanded', on ? 'true' : 'false'); }
        if (body) { body.hidden = !on; }
      });
    }

    function paintItems() {
      var active = byId(topic);
      var frag = document.createDocumentFragment();
      active.qs.forEach(function (r, i) { frag.appendChild(buildItem(r, i)); });
      panel.textContent = '';
      panel.appendChild(frag);
    }

    function paintTopic() {
      var active = byId(topic);

      titleEl.textContent = active.title;
      countEl.textContent = active.cat + ' \u00b7 ' + active.qs.length + ' ANSWERS';

      var frag = document.createDocumentFragment();
      active.facts.forEach(function (f) {
        var cell = node('div', 'pledge');
        var head = node('p', 'pledge__head');
        var tick = node('span', 'pledge__tick');
        tick.setAttribute('aria-hidden', 'true');
        tick.appendChild(svg('11', '3', ['M5 12.5l4.5 4.5L19 7']));
        head.appendChild(tick);
        head.appendChild(document.createTextNode(f.title));
        cell.appendChild(head);
        cell.appendChild(node('p', 'pledge__note', f.note));
        frag.appendChild(cell);
      });
      pledges.textContent = '';
      pledges.appendChild(frag);

      chips.forEach(function (c) {
        var on = c.getAttribute('data-topic') === topic;
        c.classList.toggle('topic--on', on);
        c.setAttribute('aria-pressed', on ? 'true' : 'false');
      });

      paintItems();
    }

    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        topic = chip.getAttribute('data-topic');
        open = 0;
        paintTopic();
      });
    });

    /* The markup already ships the initial state; this only re-binds it so the
       three summaries respond, and keeps one code path for every render. */
    paintTopic();
  }

  /* ------------------------------------------------------------------ 19 */
  /* Contact — the topic chips, the message form and the showroom map.
     The seven showrooms are declared once, on the map element, and drive the
     pins, the list rows and the nearest-to-you card together. */

  function initContact() {
    var form = document.getElementById('talk');
    if (!form) { return; }

    /* -- topic chips: one desk at a time --------------------------------- */
    var chips = Array.prototype.slice.call(form.querySelectorAll('.ctopic'));
    var topic = 'Buying a car';

    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        topic = chip.getAttribute('data-topic');
        chips.forEach(function (c) {
          var on = c === chip;
          c.classList.toggle('ctopic--on', on);
          c.setAttribute('aria-pressed', on ? 'true' : 'false');
        });
      });
    });

    /* -- the message ----------------------------------------------------- */
    var done = document.getElementById('ct-done');
    var errorEl = document.getElementById('talk-error');
    var nameEl = document.getElementById('ct-name');
    var mailEl = document.getElementById('ct-email');
    var msgEl = document.getElementById('ct-msg');
    var shopEl = document.getElementById('ct-shop');

    function setText(id, value) {
      var el = document.getElementById(id);
      if (el) { el.textContent = value; }
    }

    function looksLikeEmail(v) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v);
    }

    form.addEventListener('submit', function (e) {
      /* Nothing to post to in a static template — and a real submit would
         reload the page and leave the browser offering to resend it. */
      e.preventDefault();

      var bad = [];
      if (!nameEl.value.trim()) { bad.push(nameEl); }
      if (!looksLikeEmail(mailEl.value.trim())) { bad.push(mailEl); }
      if (!msgEl.value.trim()) { bad.push(msgEl); }

      [nameEl, mailEl, msgEl].forEach(function (el) {
        el.setAttribute('aria-invalid', bad.indexOf(el) >= 0 ? 'true' : 'false');
      });

      if (bad.length) {
        errorEl.hidden = false;
        bad[0].focus();
        return;
      }

      errorEl.hidden = true;
      setText('ct-d-ref', 'CT-' + String(Date.now()).slice(-6));
      setText('ct-d-topic', topic);
      setText('ct-d-shop', shopEl.value);
      setText('ct-d-email', mailEl.value.trim());

      if (done && typeof done.showModal === 'function') {
        openDialog(done);
      }
    });

    /* Clear a field's error mark as soon as the visitor fixes it. */
    [nameEl, mailEl, msgEl].forEach(function (el) {
      el.addEventListener('input', function () {
        if (el.getAttribute('aria-invalid') === 'true') {
          el.setAttribute('aria-invalid', 'false');
        }
      });
    });

    if (done) {
      done.addEventListener('click', function (e) {
        if (e.target === done) { done.close('done'); }
      });
    }

    /* -- the map, the list and the nearest-to-you card ------------------- */
    initDoors();
  }

  function initDoors() {
    var el = document.getElementById('doors-map');
    if (!el) { return; }

    var shops;
    try {
      shops = JSON.parse(el.getAttribute('data-showrooms'));
    } catch (e) {
      return;
    }

    var rows = Array.prototype.slice.call(document.querySelectorAll('.shrow'));
    var current = 0;

    /* How far through today's opening hours we are — the bar under the card.
       Before opening it reads empty, after closing it reads full. */
    function through(shop) {
      var now = new Date();
      var mins = now.getHours() * 60 + now.getMinutes();
      var from = toMinutes(shop.opens);
      var till = toMinutes(shop.closes);
      if (till <= from) { return 0; }
      /* Unrounded: rounding here would make 18:59 read as a closed shop. */
      return Math.max(0, Math.min(100, ((mins - from) / (till - from)) * 100));
    }

    function toMinutes(hhmm) {
      var parts = String(hhmm).split(':');
      return (parseInt(parts[0], 10) || 0) * 60 + (parseInt(parts[1], 10) || 0);
    }

    function paintCard(i) {
      var s = shops[i];
      var pct = through(s);
      setCardText('nearby-lbl', (i === 0 ? 'NEAREST TO YOU' : 'SELECTED SHOWROOM') + ' · ' + s.distance);
      setCardText('nearby-title', s.city + ' — ' + s.stock + ' cars on the floor');
      setCardText('nearby-note', s.address + (s.open ? ' · open until ' + s.closes : ' · closed today, opens ' + s.opens));
      /* An empty bar has to say why it is empty, or it reads as broken: before
         the doors open it is not "0% of today", it is "not open yet". */
      setCardText('nearby-open', pct <= 0 ? 'OPENS ' + s.opens : (pct >= 100 ? 'CLOSED ' + s.closes : 'TODAY ' + s.opens));
      setCardText('nearby-close', s.closes);

      var call = document.getElementById('nearby-call');
      if (call) {
        call.href = s.tel;
        call.childNodes[0].nodeValue = s.phone;
      }
      var fill = document.getElementById('nearby-fill');
      if (fill) { fill.style.setProperty('--through', Math.round(pct) + '%'); }
    }

    function setCardText(id, value) {
      var n = document.getElementById(id);
      if (n) { n.textContent = value; }
    }

    function paintRows(i) {
      rows.forEach(function (r, k) {
        var on = k === i;
        r.classList.toggle('shrow--on', on);
        r.setAttribute('aria-pressed', on ? 'true' : 'false');
        var ico = r.querySelector('.shrow__ico');
        if (ico) { ico.classList.toggle('shrow__ico--on', on); }
      });
    }

    paintCard(0);

    if (typeof L === 'undefined') {
      /* No map library, but the list and the card still work. */
      rows.forEach(function (r, i) {
        r.addEventListener('click', function () { current = i; paintRows(i); paintCard(i); });
      });
      return;
    }

    var map = L.map(el, {
      scrollWheelZoom: false,   /* let the page scroll past the map */
      zoomControl: false,       /* re-added on the right, clear of the legend */
      attributionControl: true
    });

    L.control.zoom({ position: 'topright' }).addTo(map);

    L.tileLayer('../../../tile.openstreetmap.org/%7bz%7d/%7bx%7d/%7by%7d.png', {
      maxZoom: 18,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    function pin(s, active) {
      var till = active && s.open ? '<span class="mpin__till">TO ' + s.closes + '</span>' : '';
      return L.divIcon({
        className: 'mpin-wrap',
        html: '<span class="mpin' + (active ? ' mpin--on' : '') + '">'
            + '<span class="mpin__dot' + (s.open ? '' : ' mpin__dot--off') + (active && s.open ? ' pulse' : '') + '"></span>'
            + s.city + till + '</span>',
        iconSize: [0, 0],
        iconAnchor: [0, 0]
      });
    }

    var bounds = [];
    var markers = shops.map(function (s, i) {
      bounds.push([s.lat, s.lng]);
      return L.marker([s.lat, s.lng], { icon: pin(s, i === 0), title: s.city, riseOnHover: true })
        .addTo(map)
        .on('click', function () { select(i); });
    });

    /* The overlay card is a different height at every breakpoint, so the room
       reserved for it is measured, not guessed. */
    function cardRoom() {
      var card = document.querySelector('.nearby');
      return card ? card.offsetHeight + 36 : 150;
    }

    function fit() {
      map.invalidateSize();
      map.fitBounds(bounds, { paddingTopLeft: [40, 80], paddingBottomRight: [40, cardRoom()] });
    }

    fit();
    window.addEventListener('resize', fit);

    function select(i) {
      current = i;
      markers.forEach(function (m, k) { m.setIcon(pin(shops[k], k === i)); });
      paintRows(i);
      paintCard(i);
      /* Centre the pin in the space above the card, not in the container. */
      var z = map.getZoom();
      /* Screen y grows downward, so the centre goes BELOW the pin to lift it. */
      var lifted = map.project([shops[i].lat, shops[i].lng], z).add([0, cardRoom() / 2]);
      map.panTo(map.unproject(lifted, z), { animate: true });
    }

    rows.forEach(function (r, i) {
      r.addEventListener('click', function () { select(i); });
    });
  }

  /* ------------------------------------------------------------------ 20 */
  /* Currency — the switch in the utility strip.
     Every price in the template is authored in euro; the switch converts and
     reformats. Static figures in the markup are wrapped once, on load, so the
     base amount survives repeated switching; the pages that build their own
     prices (Pricing, Compare) call formatMoney directly and repaint. */

  var CUR_STORE = 'avava-currency';
  var CUR_RATE = { EUR: 1, USD: 1.08 };   /* demo rate — one place to change it */
  var CUR_SIGN = { EUR: '€', USD: '$' };

  var currency = 'EUR';
  var moneyPainters = [];

  function readCurrency() {
    try {
      var saved = window.localStorage.getItem(CUR_STORE);
      if (saved === 'EUR' || saved === 'USD') { return saved; }
    } catch (e) {}
    return 'EUR';
  }

  /* Exposed to the price-building modules so every figure agrees. */
  function formatMoney(eur) {
    return CUR_SIGN[currency] + Math.round(eur * CUR_RATE[currency]).toLocaleString('en-US');
  }

  function onCurrencyChange(fn) { moneyPainters.push(fn); }

  /* Wrap every money string under `root` once, keeping its euro base, so
     the currency switch can repaint it. Pages that render rows after load
     call it on the rows they made. */
  function wrapMoney(root) {
    var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
      acceptNode: function (node) {
        var p = node.parentElement;
        if (!p) { return NodeFilter.FILTER_REJECT; }
        var tag = p.tagName;
        if (tag === 'SCRIPT' || tag === 'STYLE' || tag === 'TEXTAREA') { return NodeFilter.FILTER_REJECT; }
        if (p.classList.contains('money')) { return NodeFilter.FILTER_REJECT; }
        return /[€$]\s?\d/.test(node.nodeValue) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
      }
    });

    var found = [];
    var n;
    while ((n = walker.nextNode())) { found.push(n); }

    var money = /([€$])\s?(\d[\d,]*)/g;
    found.forEach(function (node) {
      var text = node.nodeValue;
      var frag = document.createDocumentFragment();
      var last = 0;
      var m;
      money.lastIndex = 0;
      while ((m = money.exec(text))) {
        if (m.index > last) { frag.appendChild(document.createTextNode(text.slice(last, m.index))); }
        var span = document.createElement('span');
        span.className = 'money';
        span.setAttribute('data-eur', m[2].replace(/,/g, ''));
        span.textContent = formatMoney(parseFloat(m[2].replace(/,/g, '')));
        frag.appendChild(span);
        last = m.index + m[0].length;
      }
      if (last < text.length) { frag.appendChild(document.createTextNode(text.slice(last))); }
      node.parentNode.replaceChild(frag, node);
    });
  }

  function initCurrency() {
    var buttons = Array.prototype.slice.call(document.querySelectorAll('.cur__btn'));
    currency = readCurrency();

    wrapMoney(document.body);

    function paint() {
      Array.prototype.forEach.call(document.querySelectorAll('.money'), function (el) {
        var eur = parseFloat(el.getAttribute('data-eur'));
        if (!isNaN(eur)) { el.textContent = formatMoney(eur); }
      });
      buttons.forEach(function (b) {
        var on = b.getAttribute('data-cur') === currency;
        b.classList.toggle('cur__btn--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      moneyPainters.forEach(function (fn) { fn(); });
    }

    buttons.forEach(function (b) {
      b.addEventListener('click', function () {
        currency = b.getAttribute('data-cur');
        try { window.localStorage.setItem(CUR_STORE, currency); } catch (e) {}
        paint();
      });
    });

    paint();
  }

  /* ------------------------------------------------------------------ 21 */
  /* Terms — the contents panel follows the reader.
     The design hard-codes the active row to clause 1; the handoff asks for it
     to be driven by scroll position instead, so the rail always says where in
     the document you are. */

  function initTerms() {
    var toc = document.querySelector('.toc__list');
    if (!toc) { return; }

    var rows = Array.prototype.slice.call(toc.querySelectorAll('.toc__row'));
    var clauses = rows.map(function (r) {
      var id = (r.getAttribute('href') || '').slice(1);
      return document.getElementById(id);
    });
    if (!clauses.length || clauses.indexOf(null) >= 0) { return; }

    var at = -1;

    function mark(i) {
      if (i === at) { return; }
      at = i;
      rows.forEach(function (r, k) {
        var on = k === i;
        r.classList.toggle('toc__row--on', on);
        if (on) { r.setAttribute('aria-current', 'true'); } else { r.removeAttribute('aria-current'); }
      });
    }

    /* The clause you are reading is the last one whose heading has passed the
       top of the screen — a plain rule, so it holds for a clause of any length
       and never leaves the rail pointing at nothing. */
    var LINE = 140;

    function follow() {
      var found = 0;
      for (var i = 0; i < clauses.length; i++) {
        if (clauses[i].getBoundingClientRect().top <= LINE) { found = i; } else { break; }
      }
      mark(found);
    }

    var queued = false;
    function onScroll() {
      if (queued) { return; }
      queued = true;
      window.requestAnimationFrame(function () {
        queued = false;
        follow();
      });
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    follow();
  }

  /* ------------------------------------------------------------------ 22 */
  /* Listings — filters, two sorts, paging.
     Four places on this page state a count: the panel counter, the panel
     button, the sort bar and the pagination. They all read the same number
     here, because three places showing different counts is how this page
     fails. The first page is already in the markup; this module re-renders
     from the same catalogue the moment anything is touched. */

  var LIST_MONTHLY = 57.92;   /* the divisor the reference cards all agree on */

  /* ------------------------------------------- Reading the search sentence

     Listings v3 opens on a sentence instead of a form, and the handoff is
     blunt about what that costs: if the sentence is not really read, the copy
     has to change. So it is really read.

     Every rule records the span of text it consumed. That is what lets a chip
     be taken off the sentence as well as off the filter — otherwise the field
     would go on claiming something the chips no longer say, which is exactly
     the dishonesty the page is built to avoid. */

  var Q_WORDS = [
    { key: 'make', value: 'Land Rover',   re: /\b(?:land[ -]?rover|range[ -]?rover|defender)\b/i },
    { key: 'make', value: 'Mercedes-AMG', re: /\b(?:mercedes[ -]?amg|mercedes|amg)\b/i },
    { key: 'make', value: 'Porsche',      re: /\bporsche\b/i },
    { key: 'make', value: 'BMW',          re: /\bbmw\b/i },
    { key: 'make', value: 'Audi',         re: /\baudi\b/i },
    { key: 'make', value: 'Bentley',      re: /\bbentley\b/i },
    { key: 'make', value: 'Tesla',        re: /\btesla\b/i },

    { key: 'body', value: 'Coupé',        re: /\bcoup[eé](?![a-z])/i },
    { key: 'body', value: 'SUV',          re: /\bsuv\b/i },
    { key: 'body', value: 'Saloon',       re: /\b(?:saloon|sedan)\b/i },
    { key: 'body', value: 'Cabriolet',    re: /\b(?:cabriolet|cabrio|convertible|roadster)\b/i },
    { key: 'body', value: 'Estate',       re: /\b(?:estate|wagon|avant)\b/i },

    { key: 'drive', value: 'All-wheel',   re: /\b(?:all[ -]?wheel(?: drive)?|awd|4wd|quattro|4matic|xdrive)\b/i },
    { key: 'drive', value: 'Rear-wheel',  re: /\b(?:rear[ -]?wheel(?: drive)?|rwd)\b/i },
    { key: 'drive', value: 'Front-wheel', re: /\b(?:front[ -]?wheel(?: drive)?|fwd)\b/i },

    { key: 'mileage', value: 'Under 5k km',  re: /\b(?:delivery mileage|as new|barely driven|hardly driven)\b/i },
    { key: 'mileage', value: 'Under 20k km', re: /\blow mileage\b/i }
  ];

  var Q_FLAGS = [
    { flag: 'electric', label: 'FUEL',   value: 'Electric',         re: /\b(?:electric|ev)\b/i },
    { flag: 'ready',    label: 'READY',  value: 'This week',        re: /\bready(?: (?:this week|now))?\b|\bavailable now\b/i },
    { flag: 'faults',   label: 'REPORT', value: 'Faults published', re: /\b(?:with faults|faults published)\b/i }
  ];

  var Q_LEAD = '(?:under|below|less than|up to|max(?:imum)?|no more than|from|after|since|newer than)';
  var Q_PRICE = [100000, 150000, 200000, 300000];
  var Q_KM = [5000, 20000, 50000, 80000];
  var Q_YEAR = [2023, 2024, 2025, 2026, 2027];

  /* The brackets are ceilings, so a typed figure takes the first one that
     covers it: "under 130k" is answered by "Up to €150k", never by €100k. */
  function bracketUp(value, list) {
    var i;
    for (i = 0; i < list.length; i++) {
      if (value <= list[i]) { return list[i]; }
    }
    return list[list.length - 1];
  }

  function parseQuery(text) {
    var out = [];
    var used = [];

    function free(from, to) {
      var i;
      for (i = 0; i < used.length; i++) {
        if (from < used[i][1] && to > used[i][0]) { return false; }
      }
      return true;
    }

    function take(key, value, from, to, flag) {
      var i;
      for (i = 0; i < out.length; i++) {
        if (out[i].key === key && out[i].flag === flag) { return; }   /* first mention wins */
      }
      if (!free(from, to)) { return; }
      used.push([from, to]);
      out.push({ key: key, value: value, from: from, to: to, flag: flag });
    }

    function scan(source, handler) {
      var g = new RegExp(source, 'gi');
      var m;
      while ((m = g.exec(text)) !== null) {
        handler(m);
        if (m.index === g.lastIndex) { g.lastIndex += 1; }
      }
    }

    /* Words first: they swallow "all-wheel drive" before a number rule can
       mistake a digit inside it for a budget. */
    Q_WORDS.forEach(function (rule) {
      var m = rule.re.exec(text);
      if (m) { take(rule.key, rule.value, m.index, m.index + m[0].length); }
    });

    /* The three the sentence can ask for that no cell holds. */
    Q_FLAGS.forEach(function (rule) {
      var m = rule.re.exec(text);
      if (m) { take(rule.label, rule.value, m.index, m.index + m[0].length, rule.flag); }
    });

    /* A four-digit 20xx is a year and nothing else. */
    scan('(?:' + Q_LEAD + '\\s+)?(20\\d\\d)\\b', function (m) {
      var y = parseInt(m[1], 10);
      if (y < Q_YEAR[0]) { y = Q_YEAR[0]; }
      if (y > Q_YEAR[Q_YEAR.length - 1]) { y = Q_YEAR[Q_YEAR.length - 1]; }
      take('year', String(y), m.index, m.index + m[0].length);
    });

    /* Then distances — they are the numbers that carry a unit. */
    scan('(?:' + Q_LEAD + '\\s+)?([\\d][\\d.,]*)\\s*(k)?\\s*(?:km|kilometres|kilometers|miles)\\b',
      function (m) {
        var v = parseFloat(m[1].replace(/,/g, ''));
        if (m[2]) { v *= 1000; }
        take('mileage', 'Under ' + (bracketUp(v, Q_KM) / 1000) + 'k km', m.index, m.index + m[0].length);
      });

    /* Whatever number is left is money. */
    scan('(?:' + Q_LEAD + '\\s+)?\\u20AC?\\s*([\\d][\\d.,]*)\\s*(k|000)?', function (m) {
      var v = parseFloat(m[1].replace(/,/g, ''));
      if (m[2] === 'k' || m[2] === 'K') { v *= 1000; }
      if (v < 1000) { return; }                   /* "two seats" is not a budget */
      take('price', 'Up to €' + (bracketUp(v, Q_PRICE) / 1000) + 'k', m.index, m.index + m[0].length);
    });

    out.sort(function (a, b) { return a.from - b.from; });
    return out;
  }

  /* Taking a chip off takes its words with it, and the comma they leave
     hanging goes too. */
  function dropSpan(text, from, to) {
    var out = text.slice(0, from) + text.slice(to);
    out = out.replace(/\s*,\s*,/g, ',').replace(/^\s*,\s*/, '').replace(/\s*,\s*$/, '');
    return out.replace(/\s{2,}/g, ' ').trim();
  }

  /* The words a filter would have been written with, so a cell set by hand can
     be spliced back into the sentence rather than silently contradicting it. */
  var FLAG_PHRASE = { electric: 'electric', ready: 'ready this week', faults: 'faults published' };

  function phraseOf(key, value) {
    if (key === 'price') { return 'under €' + value.replace(/[^\d k]/gi, '').trim(); }
    if (key === 'mileage') { return value.toLowerCase(); }
    if (key === 'drive') { return value.toLowerCase() + ' drive'; }
    if (key === 'year') { return 'from ' + value; }
    return value;
  }

  function initListing() {
    var panel = document.getElementById('filters');
    var grid = document.getElementById('grid');
    var source = document.getElementById('catalogue');
    if (!panel || !grid || !source) { return; }

    var cars;
    try {
      cars = JSON.parse(source.textContent);
    } catch (e) {
      return;
    }

    /* The map-first panel scrolls instead of paging, so it asks for the
       whole result set rather than a slice of it. */
    var sizeAttr = panel.getAttribute('data-size');
    var SIZE = sizeAttr === 'all' ? Infinity : (parseInt(sizeAttr, 10) || 24);
    /* VIEW MORE appends the next batch under the ones already read instead of
       turning to a page that replaces them. */
    var GROW = panel.hasAttribute('data-grow');
    /* v1 names the fault in words, v2 leads with the photograph, v3 turns
       the whole card to ink. */
    var CARD = grid.getAttribute('data-card') || 'fault';
    var TOTAL = cars.length;

    var selects = Array.prototype.slice.call(panel.querySelectorAll('[data-filter]'));
    var ranges = Array.prototype.slice.call(panel.querySelectorAll('[data-range]'));
    var boxes = Array.prototype.slice.call(panel.querySelectorAll('[data-flag]'));
    /* The quick picks wear two different coats — a chip on the filter panels,
       a pill on the map's control bar — but they are one control. */
    var chips = Array.prototype.slice.call(panel.querySelectorAll('.qchip, .cpill, .mseg'));

    function chipOn(b, on) {
      var base = b.classList.contains('cpill') ? 'cpill'
        : (b.classList.contains('mseg') ? 'mseg' : 'qchip');
      b.classList.toggle(base + '--on', on);
      b.setAttribute('aria-pressed', on ? 'true' : 'false');
    }
    var sorts = Array.prototype.slice.call(document.querySelectorAll('[data-sort]'));
    var emptyEl = document.getElementById('empty');
    var moreBtn = document.getElementById('more');
    var nextBtn = document.getElementById('next');
    var prevBtn = document.getElementById('prev');
    var pagesEl = document.getElementById('pages');
    var rowsEl = document.getElementById('rows');
    var ROW = rowsEl ? (rowsEl.getAttribute('data-row') || 'wide') : 'wide';
    var ticksEl = document.getElementById('ticks');

    var chip = 'all';
    /* Assigned once the map is up; the panel's RESET has to reach it. */
    var syncSearch = function () {};
    /* v1 and v2 pick one quick filter at a time; v3's suggestions and v5's
       inspection boxes stack. Each one is a predicate rather than a branch, so
       the rail can ask what a box would count without turning it on. */
    var FLAG_TEST = {
      electric: function (c) { return c.fuel === 'Electric'; },
      ready: function (c) { return !!c.ready; },
      faults: function (c) { return c.faults > 0; },
      clean: function (c) { return c.faults === 0; },
      fast48: function (c) { return !!c.fast48; },
      owner1: function (c) { return !!c.owner1; },
      history: function (c) { return !!c.history; }
    };
    var flags = {};
    var mapCity = '';
    var mapBounds = null;
    var page = 1;

    /* The wide panels spell a ceiling out — "€150,000", "20,000 km" — and the
       narrow cells abbreviate it — "Up to €150k", "Under 20k km". Same number. */
    function num(text) {
      var s = String(text);
      var k = s.match(/([\d,.]+)\s*k\b/i);
      if (k) { return Math.round(parseFloat(k[1].replace(/,/g, '')) * 1000); }
      var digits = s.replace(/[^0-9]/g, '');
      return digits ? parseInt(digits, 10) : 0;
    }

    /* -- the result set ------------------------------------------------- */
    function matches(c, except) {
      var f = {};
      selects.forEach(function (s) { f[s.getAttribute('data-filter')] = s.value; });
      /* A range only filters once it has been pulled off its own maximum. */
      ranges.forEach(function (r) {
        if (parseFloat(r.value) < parseFloat(r.max)) { f[r.getAttribute('data-range')] = r.value; }
      });

      if (f.make && c.make.toLowerCase() !== f.make.toLowerCase()) { return false; }
      if (f.body && c.body !== f.body) { return false; }
      if (f.drive && c.drive.toLowerCase().indexOf(f.drive.toLowerCase()) !== 0) { return false; }
      if (f.price && c.price > num(f.price)) { return false; }
      if (f.year && c.year < num(f.year)) { return false; }
      /* The wide panels name this field "km" and the narrow cells "mileage";
         both mean the same ceiling. */
      var kmCap = f.km || f.mileage;
      if (kmCap && c.km > num(kmCap)) { return false; }

      /* v5 narrows by seven more facets than the others. */
      if (f.model && c.model !== f.model) { return false; }
      if (f.fuel && c.fuel !== f.fuel) { return false; }
      if (f.gearbox && c.gearbox !== f.gearbox) { return false; }
      if (f.seats && c.seats !== num(f.seats)) { return false; }
      if (f.colour && c.colour !== f.colour) { return false; }
      if (f.city && c.city !== f.city) { return false; }
      if (f.yearTo && c.year > num(f.yearTo)) { return false; }

      if (chip === 'price100' && c.price >= 100000) { return false; }
      if (chip === 'electric' && c.fuel !== 'Electric') { return false; }
      if (chip === 'awd' && c.drive !== 'All-wheel drive') { return false; }
      if (chip === 'km20' && c.km >= 20000) { return false; }
      if (chip === 'ready' && !c.ready) { return false; }
      if (chip === 'faults' && !c.faults) { return false; }

      /* The map page's four pills are one exclusive condition. */
      if (except !== 'chip') {
        if (chip === 'new' && c.condition !== 'New') { return false; }
        if (chip === 'used' && c.condition !== 'Used') { return false; }
        if (chip === 'certified' && c.condition !== 'Certified') { return false; }
      }

      /* The map is a filter of its own: a door chosen by its pin, and the
         patch of Europe the viewport is showing. */
      if (mapCity && c.city !== mapCity) { return false; }
      if (mapBounds && !mapBounds.contains([c.lat, c.lng])) { return false; }

      var key;
      for (key in flags) {
        if (flags[key] && key !== except && FLAG_TEST[key] && !FLAG_TEST[key](c)) { return false; }
      }

      return true;
    }

    function sorted(list) {
      var how = sorts.length ? sorts[0].value : 'Recommended';
      var out = list.slice();
      if (how === 'Newest in stock') {
        out.sort(function (a, b) { return b.year - a.year || a.km - b.km; });
      } else if (how === 'Price: low to high') {
        out.sort(function (a, b) { return a.price - b.price; });
      } else if (how === 'Price: high to low') {
        out.sort(function (a, b) { return b.price - a.price; });
      } else if (how === 'Mileage: lowest') {
        out.sort(function (a, b) { return a.km - b.km; });
      }
      return out;   /* Recommended is the catalogue's own order */
    }

    /* -- one card ------------------------------------------------------- */
    function svgNode(width, paths) {
      var s = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
      s.setAttribute('width', '11');
      s.setAttribute('height', '11');
      s.setAttribute('viewBox', '0 0 24 24');
      s.setAttribute('fill', 'none');
      s.setAttribute('stroke', 'currentColor');
      s.setAttribute('stroke-width', width);
      s.setAttribute('stroke-linecap', 'round');
      s.setAttribute('stroke-linejoin', 'round');
      s.setAttribute('aria-hidden', 'true');
      s.setAttribute('focusable', 'false');
      paths.forEach(function (d) {
        var p = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        p.setAttribute('d', d);
        s.appendChild(p);
      });
      return s;
    }

    function el(tag, cls, text) {
      var n = document.createElement(tag);
      if (cls) { n.className = cls; }
      if (text !== undefined) { n.textContent = text; }
      return n;
    }

    /* Both card treatments state the same four things — the photograph, the
       price, the three specs and the way through to the report. Only where
       the make, the model and the fault count sit differs, so those four are
       built once and the two cards assemble them. */
    function shotOf(c) {
      var shot = el('div', 'lcard__shot');
      var img = el('img', 'lcard__img');
      img.src = 'assets/img/cars/' + c.photo + '.webp';
      img.width = 1200;
      img.height = 750;
      img.alt = c.make + ' ' + c.model;
      img.loading = 'lazy';
      img.decoding = 'async';
      shot.appendChild(img);
      return shot;
    }

    function pricesOf(c) {
      var prices = el('p', 'lcard__prices');
      prices.appendChild(el('span', 'lcard__price', formatMoney(c.price)));
      prices.appendChild(el('span', 'lcard__mo', formatMoney(c.price / LIST_MONTHLY) + '/mo'));
      return prices;
    }

    function specsOf(c) {
      var specs = el('div', 'lcard__specs');
      [[String(c.year), 'YEAR'],
       [c.km.toLocaleString('en-US') + ' km', 'MILEAGE'],
       [c.power.toLocaleString('en-US') + ' hp', 'POWER']].forEach(function (pair) {
        var cell = el('div');
        cell.appendChild(el('p', 'lcard__sval', pair[0]));
        cell.appendChild(el('p', 'lcard__slabel', pair[1]));
        specs.appendChild(cell);
      });
      return specs;
    }

    function ctaOf() {
      var cta = el('a', 'lcard__cta', 'See the report');
      cta.href = '#';
      var ctac = el('span', 'lcard__ctac');
      ctac.setAttribute('aria-hidden', 'true');
      var arrow = svgNode('2.4', ['M5 12h14', 'M13 6l6 6-6 6']);
      arrow.setAttribute('width', '15');
      arrow.setAttribute('height', '15');
      ctac.appendChild(arrow);
      cta.appendChild(ctac);
      return cta;
    }

    function faultLabel(c) {
      return c.faults
        ? c.faults + ' FAULT' + (c.faults === 1 ? '' : 'S') + ' PUBLISHED'
        : 'NO FAULTS FOUND';
    }

    /* v1 — the fault named in words, and the lead card carries it inverted. */
    function faultCard(c, lead) {
      var art = el('article', 'lcard' + (lead ? ' lcard--dark' : ''));

      var shot = shotOf(c);
      shot.appendChild(el('span', 'lcard__where', c.body + ' \u00B7 ' + c.city));
      art.appendChild(shot);

      var body = el('div', 'lcard__body');
      body.appendChild(el('p', 'lcard__make', c.make));
      body.appendChild(el('h3', 'lcard__model', c.model));
      body.appendChild(pricesOf(c));

      var fault = el('p', 'lcard__fault' + (c.faults ? '' : ' lcard__fault--clean'));
      var mark = el('span', 'lcard__mark');
      mark.setAttribute('aria-hidden', 'true');
      mark.appendChild(c.faults
        ? svgNode('3', ['M12 7v7M12 17.5v.5'])
        : svgNode('3', ['M5 12.5l4.5 4.5L19 7']));
      var ftext = el('span', 'lcard__ftext');
      ftext.appendChild(el('span', 'lcard__flabel', faultLabel(c)));
      ftext.appendChild(el('span', 'lcard__fline', c.fault));
      fault.appendChild(mark);
      fault.appendChild(ftext);
      body.appendChild(fault);

      body.appendChild(specsOf(c));
      body.appendChild(ctaOf());

      art.appendChild(body);
      return art;
    }

    /* v2 — the photograph leads, and the fault count is a corner chip. The
       lead card is marked by its edge, because this page keeps one dark
       surface and that one is the footer. */
    function photoCard(c, lead) {
      var art = el('article', 'lcard lcard--photo' + (lead ? ' lcard--lead' : ''));

      var shot = shotOf(c);
      var scrim = el('span', 'lcard__scrim');
      scrim.setAttribute('aria-hidden', 'true');
      shot.appendChild(scrim);
      shot.appendChild(el('span', 'lcard__where', c.body));
      shot.appendChild(el('span', 'lcard__faults' + (c.faults ? '' : ' lcard__faults--clean'),
        c.faults ? 'FAULTS PUBLISHED: ' + c.faults : 'NO FAULTS FOUND'));

      var over = el('div', 'lcard__over');
      over.appendChild(el('p', 'lcard__make', c.make + ' \u00B7 ' + c.city));
      over.appendChild(el('h3', 'lcard__model', c.model));
      shot.appendChild(over);
      art.appendChild(shot);

      var body = el('div', 'lcard__body');
      body.appendChild(pricesOf(c));
      body.appendChild(specsOf(c));
      body.appendChild(ctaOf());

      art.appendChild(body);
      return art;
    }

    /* v3 — the whole card turns to ink on a light page. The fault state moves
       up beside the city, and the one thing a car has earned becomes a tag on
       the photograph. Nothing inverts here: the card is already inverted. */
    function inkCard(c) {
      var art = el('article', 'vcard');

      var shot = el('div', 'vcard__shot');
      var img = el('img', 'vcard__img');
      img.src = 'assets/img/cars/' + c.photo + '.webp';
      img.width = 1200;
      img.height = 750;
      img.alt = c.make + ' ' + c.model;
      img.loading = 'lazy';
      img.decoding = 'async';
      shot.appendChild(img);
      if (!c.faults) {
        shot.appendChild(el('span', 'vcard__tag', 'CLEAN REPORT'));
      } else if (c.ready) {
        shot.appendChild(el('span', 'vcard__tag', 'READY THIS WEEK'));
      }
      art.appendChild(shot);

      var body = el('div', 'vcard__body');

      var meta = el('p', 'vcard__meta');
      meta.appendChild(el('span', 'vcard__where', c.make + ' \u00B7 ' + c.city));
      meta.appendChild(el('span', 'vcard__state' + (c.faults ? '' : ' vcard__state--clean'),
        c.faults ? c.faults + ' PUBLISHED' : 'NO FAULTS'));
      body.appendChild(meta);

      body.appendChild(el('h3', 'vcard__model', c.model));

      var prices = el('p', 'vcard__prices');
      prices.appendChild(el('span', 'vcard__price', formatMoney(c.price)));
      prices.appendChild(el('span', 'vcard__mo', formatMoney(c.price / LIST_MONTHLY) + '/mo'));
      body.appendChild(prices);

      var specs = el('div', 'vcard__specs');
      [[String(c.year), 'YEAR'],
       [c.km.toLocaleString('en-US') + ' km', 'MILEAGE'],
       [c.power.toLocaleString('en-US') + ' hp', 'POWER']].forEach(function (pair) {
        var cell = el('div', 'vcard__cell');
        cell.appendChild(el('p', 'vcard__sval', pair[0]));
        cell.appendChild(el('p', 'vcard__slabel', pair[1]));
        specs.appendChild(cell);
      });
      body.appendChild(specs);

      var cta = el('a', 'vcard__cta', 'See the report');
      cta.href = '#';
      var ctac = el('span', 'vcard__ctac');
      ctac.setAttribute('aria-hidden', 'true');
      var arrow = svgNode('2.4', ['M5 12h14', 'M13 6l6 6-6 6']);
      arrow.setAttribute('width', '14');
      arrow.setAttribute('height', '14');
      ctac.appendChild(arrow);
      cta.appendChild(ctac);
      body.appendChild(cta);

      art.appendChild(body);
      return art;
    }

    /* v4 — the spec sheet. Four facts in a table, and the published fault is
       one of them: it sits with the mileage and the power rather than in a
       badge, which is the whole argument of this card. */
    function specCard(c, lead, index) {
      var art = el('article', 'scard' + (lead ? ' scard--lead' : ''));

      var shot = el('div', 'scard__shot');
      var img = el('img', 'scard__img');
      img.src = 'assets/img/cars/' + c.photo + '.webp';
      img.width = 1200;
      img.height = 750;
      img.alt = c.make + ' ' + c.model;
      img.loading = 'lazy';
      img.decoding = 'async';
      shot.appendChild(img);
      shot.appendChild(el('span', 'scard__where', c.body + ' \u00B7 ' + c.city));
      if (!c.faults) {
        shot.appendChild(el('span', 'scard__tag', 'CLEAN REPORT'));
      } else if (c.ready) {
        shot.appendChild(el('span', 'scard__tag', 'READY THIS WEEK'));
      }
      var no = el('span', 'scard__no', (index + 1 < 10 ? '0' : '') + (index + 1));
      no.setAttribute('aria-hidden', 'true');
      shot.appendChild(no);
      art.appendChild(shot);

      var head = el('div', 'scard__head');
      var title = el('div', 'scard__title');
      title.appendChild(el('p', 'scard__make', c.make));
      title.appendChild(el('h3', 'scard__model', c.model));
      head.appendChild(title);

      var money = el('div', 'scard__money');
      money.appendChild(el('p', 'scard__price', formatMoney(c.price)));
      money.appendChild(el('p', 'scard__mo', formatMoney(c.price / LIST_MONTHLY) + '/mo'));
      head.appendChild(money);
      art.appendChild(head);

      var facts = el('div', 'scard__facts');

      function row(key, value, fault) {
        var r = el('div', 'scard__row' + (fault ? ' scard__row--fault' : ''));
        r.appendChild(el('span', 'scard__key', key));
        var v = el('span', 'scard__val');
        if (fault) {
          var dot = el('span', 'scard__dot');
          dot.setAttribute('aria-hidden', 'true');
          v.appendChild(dot);
        }
        v.appendChild(document.createTextNode(value));
        r.appendChild(v);
        facts.appendChild(r);
      }

      row('YEAR', String(c.year));
      row('MILEAGE', c.km.toLocaleString('en-US') + ' km');
      row('POWER', c.power.toLocaleString('en-US') + ' hp \u00B7 ' + c.accel.toFixed(1) + 's');
      row('FAULTS PUBLISHED', c.faults ? c.fault : 'None found', true);

      var cta = el('a', 'scard__cta', 'See the full report');
      cta.href = '#';
      var ctac = el('span', 'scard__ctac');
      ctac.setAttribute('aria-hidden', 'true');
      var arrow = svgNode('2.4', ['M5 12h14', 'M13 6l6 6-6 6']);
      arrow.setAttribute('width', '14');
      arrow.setAttribute('height', '14');
      ctac.appendChild(arrow);
      cta.appendChild(ctac);
      facts.appendChild(cta);

      art.appendChild(facts);
      return art;
    }

    /* v5's second view. The row has the width the cards do not, so the fault
       goes back into words and five facts fit on one line. */
    function rowOf(c, lead) {
      var art = el('article', 'rrow' + (lead ? ' rrow--lead' : ''));

      var shot = el('div', 'rrow__shot');
      var img = el('img', 'rrow__img');
      img.src = 'assets/img/cars/' + c.photo + '.webp';
      img.width = 1200;
      img.height = 750;
      img.alt = c.make + ' ' + c.model;
      img.loading = 'lazy';
      img.decoding = 'async';
      shot.appendChild(img);
      shot.appendChild(el('span', 'rrow__kind', c.body));
      if (c.ready) { shot.appendChild(el('span', 'rrow__tag', 'READY THIS WEEK')); }

      var mark = el('button', 'rrow__mark');
      mark.type = 'button';
      mark.setAttribute('aria-pressed', 'false');
      mark.setAttribute('aria-label', 'Save ' + c.make + ' ' + c.model);
      var bookmark = svgNode('2', ['M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z']);
      bookmark.setAttribute('width', '14');
      bookmark.setAttribute('height', '14');
      mark.appendChild(bookmark);
      mark.addEventListener('click', function () {
        var on = mark.getAttribute('aria-pressed') === 'true';
        mark.setAttribute('aria-pressed', on ? 'false' : 'true');
      });
      shot.appendChild(mark);
      art.appendChild(shot);

      var body = el('div', 'rrow__body');
      var top = el('div');
      var meta = el('p', 'rrow__meta');
      meta.appendChild(el('span', 'rrow__where', c.make));
      var d = el('span', 'rrow__dot');
      d.setAttribute('aria-hidden', 'true');
      meta.appendChild(d);
      meta.appendChild(el('span', 'rrow__where', c.city));
      top.appendChild(meta);
      top.appendChild(el('h3', 'rrow__model', c.model));
      body.appendChild(top);

      var facts = el('div', 'rrow__facts');
      [[String(c.year), 'YEAR'],
       [c.km.toLocaleString('en-US') + ' km', 'MILEAGE'],
       [c.power.toLocaleString('en-US') + ' hp', 'POWER'],
       [c.accel.toFixed(1) + 's', '0\u2013100'],
       [c.fuel, 'FUEL']].forEach(function (pair) {
        var cell = el('div');
        cell.appendChild(el('p', 'rfact__v', pair[0]));
        cell.appendChild(el('p', 'rfact__k', pair[1]));
        facts.appendChild(cell);
      });
      body.appendChild(facts);

      var fault = el('p', 'rrow__fault');
      var mk = el('span', 'rrow__mk');
      mk.setAttribute('aria-hidden', 'true');
      var mkIcon = c.faults
        ? svgNode('3.2', ['M12 7v7M12 17.5v.5'])
        : svgNode('3.2', ['M5 12.5l4.5 4.5L19 7']);
      mkIcon.setAttribute('width', '10');
      mkIcon.setAttribute('height', '10');
      mk.appendChild(mkIcon);
      fault.appendChild(mk);
      var ft = el('span', 'rrow__ftext');
      ft.appendChild(el('span', 'rrow__flabel', c.faults
        ? c.faults + ' PUBLISHED'
        : 'NO FAULTS'));
      ft.appendChild(document.createTextNode(c.fault));
      fault.appendChild(ft);
      body.appendChild(fault);
      art.appendChild(body);

      var money = el('div', 'rrow__money');
      var col = el('div');
      col.appendChild(el('p', 'rrow__price', formatMoney(c.price)));
      col.appendChild(el('p', 'rrow__mo', formatMoney(c.price / LIST_MONTHLY) + '/mo \u00B7 60 months'));
      money.appendChild(col);

      var cta = el('a', 'rrow__cta', 'See the report');
      cta.href = '#';
      var ctac = el('span', 'rrow__ctac');
      ctac.setAttribute('aria-hidden', 'true');
      var arrow = svgNode('2.4', ['M5 12h14', 'M13 6l6 6-6 6']);
      arrow.setAttribute('width', '13');
      arrow.setAttribute('height', '13');
      ctac.appendChild(arrow);
      cta.appendChild(ctac);
      money.appendChild(cta);

      var cmp = el('label', 'rrow__cmp');
      var cbx = el('input', 'rrow__cbx');
      cbx.type = 'checkbox';
      var box = el('span', 'rrow__box');
      box.setAttribute('aria-hidden', 'true');
      var tick = svgNode('3.4', ['M5 12.5l4.5 4.5L19 7']);
      tick.setAttribute('width', '10');
      tick.setAttribute('height', '10');
      box.appendChild(tick);
      cmp.appendChild(cbx);
      cmp.appendChild(box);
      cmp.appendChild(document.createTextNode('COMPARE'));
      money.appendChild(cmp);

      art.appendChild(money);
      return art;
    }

    /* The marketplace pair. Both say who is selling the car and what that
       means for the inspection — the one claim a buyer cannot check alone. */
    function sellerBadge(c, cls) {
      var b = el('span', cls + (c.seller === 'Private' ? ' ' + cls + '--private' : ''),
        c.seller === 'Private' ? 'PRIVATE' : 'DEALER');
      return b;
    }

    function whereLine(c, cls) {
      var w = el('p', cls + '__where');
      var pin = el('span', cls + '__pin');
      pin.setAttribute('aria-hidden', 'true');
      var g = svgNode('2.2', ['M12 21s-6-5.2-6-10a6 6 0 1112 0c0 4.8-6 10-6 10z']);
      g.setAttribute('width', '12');
      g.setAttribute('height', '12');
      var dot = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
      dot.setAttribute('cx', '12');
      dot.setAttribute('cy', '11');
      dot.setAttribute('r', '2');
      g.appendChild(dot);
      pin.appendChild(g);
      w.appendChild(pin);
      w.appendChild(document.createTextNode(c.city + ' \u00B7 ' + c.distance + ' km'));
      return w;
    }

    function trustLine(c, cls) {
      var certified = c.trust === 'AVAVA certified';
      return el('p', cls + '__trust' + (certified ? '' : ' ' + cls + '__trust--said'),
        certified ? 'AVAVA CERTIFIED' : 'SELLER DECLARED');
    }

    function shotOfMap(c, cls) {
      var shot = el('div', cls + '__shot');
      var img = el('img', cls + '__img');
      img.src = 'assets/img/cars/' + c.photo + '.webp';
      img.width = 1200;
      img.height = 750;
      img.alt = c.make + ' ' + c.model;
      img.loading = 'lazy';
      img.decoding = 'async';
      shot.appendChild(img);
      return shot;
    }

    function mapRow(c, lead) {
      var art = el('article', 'mrow' + (lead ? ' mrow--lead' : ''));
      art.setAttribute('data-city', c.city);

      var shot = shotOfMap(c, 'mrow');
      shot.appendChild(sellerBadge(c, 'mrow__seller'));
      art.appendChild(shot);

      var body = el('div', 'mrow__body');
      var head = el('div', 'mrow__head');
      var title = el('div', 'mrow__title');
      title.appendChild(el('p', 'mrow__make', c.make));
      title.appendChild(el('h3', 'mrow__model', c.model));
      head.appendChild(title);
      var money = el('div', 'mrow__money');
      money.appendChild(el('p', 'mrow__price', formatMoney(c.price)));
      money.appendChild(el('p', 'mrow__mo', formatMoney(c.price / LIST_MONTHLY) + '/mo'));
      head.appendChild(money);
      body.appendChild(head);

      var chips = el('p', 'mrow__chips');
      [String(c.year), c.km.toLocaleString('en-US') + ' km',
       c.power.toLocaleString('en-US') + ' hp'].forEach(function (t) {
        chips.appendChild(el('span', 'mchip', t));
      });
      body.appendChild(chips);

      var foot = el('div', 'mrow__foot');
      foot.appendChild(whereLine(c, 'mrow'));
      foot.appendChild(trustLine(c, 'mrow'));
      body.appendChild(foot);

      art.appendChild(body);
      return art;
    }

    function mapCard(c) {
      var art = el('article', 'mcard');
      art.setAttribute('data-city', c.city);

      var shot = shotOfMap(c, 'mcard');
      var scrim = el('span', 'mcard__scrim');
      scrim.setAttribute('aria-hidden', 'true');
      shot.appendChild(scrim);
      shot.appendChild(sellerBadge(c, 'mcard__seller'));

      var over = el('div', 'mcard__over');
      over.appendChild(el('p', 'mcard__make', c.make));
      over.appendChild(el('h3', 'mcard__model', c.model));
      var prices = el('p', 'mcard__prices');
      prices.appendChild(el('span', 'mcard__price', formatMoney(c.price)));
      prices.appendChild(el('span', 'mcard__mo', formatMoney(c.price / LIST_MONTHLY) + '/mo'));
      over.appendChild(prices);
      shot.appendChild(over);
      art.appendChild(shot);

      var body = el('div', 'mcard__body');
      var specs = el('div', 'mcard__specs');
      [[String(c.year), 'YEAR'],
       [c.km.toLocaleString('en-US') + ' km', 'MILEAGE'],
       [c.power.toLocaleString('en-US') + ' hp', 'POWER']].forEach(function (pair) {
        var cell = el('div', 'mcard__cell');
        cell.appendChild(el('p', 'mcard__sval', pair[0]));
        cell.appendChild(el('p', 'mcard__slabel', pair[1]));
        specs.appendChild(cell);
      });
      body.appendChild(specs);

      var foot = el('div', 'mcard__foot');
      foot.appendChild(whereLine(c, 'mcard'));
      foot.appendChild(trustLine(c, 'mcard'));
      body.appendChild(foot);

      art.appendChild(body);
      return art;
    }

    /* The map-first rail: a 132px thumbnail, the make above the model, and
       the price and the place on one line at the bottom. The place carries the
       accent only when AVAVA inspected the car — the colour is the claim. */
    function panelCard(c) {
      var art = el('article', 'pcard');
      art.setAttribute('data-city', c.city);

      var shot = shotOfMap(c, 'pcard');
      shot.appendChild(sellerBadge(c, 'pcard__badge'));
      art.appendChild(shot);

      var body = el('div', 'pcard__body');
      body.appendChild(el('p', 'pcard__make', c.make));
      body.appendChild(el('h3', 'pcard__model', c.model));

      var foot = el('div', 'pcard__foot');
      foot.appendChild(el('p', 'pcard__price', formatMoney(c.price)));
      foot.appendChild(el('p', 'pcard__place' + (c.trust === 'AVAVA certified' ? ' pcard__place--sure' : ''),
        c.city + ', ' + c.distance + ' km'));
      body.appendChild(foot);

      art.appendChild(body);
      return art;
    }

    /* The wide panel draws the same car twice: a row 192px wide when the list
       is showing, a two-up tile when the grid is. One result set, two
       renderings — a car in one and not the other would be a bug. */
    function wideCard(c, cls) {
      var art = el('article', cls);
      art.setAttribute('data-city', c.city);

      var shot = el('div', cls + '__shot');
      var img = el('img', cls + '__img');
      img.src = 'assets/img/cars/' + c.photo + '.webp';
      img.width = 1200;
      img.height = 750;
      img.alt = c.make + ' ' + c.model;
      img.loading = 'lazy';
      img.decoding = 'async';
      shot.appendChild(img);
      shot.appendChild(sellerBadge(c, cls + '__badge'));
      art.appendChild(shot);

      var body = el('div', cls + '__body');
      body.appendChild(el('p', cls + '__make', c.make));
      body.appendChild(el('h3', cls + '__model', c.model));

      var foot = el('div', cls + '__foot');
      foot.appendChild(el('p', cls + '__price', formatMoney(c.price)));
      foot.appendChild(el('p', cls + '__place' + (c.trust === 'AVAVA certified' ? ' ' + cls + '__place--sure' : ''),
        c.city + ', ' + c.distance + ' km'));
      body.appendChild(foot);

      art.appendChild(body);
      return art;
    }

    /* -- the card opens the car -----------------------------------------
       Five single layouts were built to be looked at, so the cards rotate
       through them rather than all landing on the same one.

       One anchor per card, stretched over the tile by CSS: making the card
       itself an <a> would nest the save button inside a link, which is invalid
       and unusable with a keyboard. Where the card already has a call to
       action that anchor is used; where it has none — the map results — the
       model name becomes the link. */
    function carHref(index) {
      return 'listing-single-v' + (index % 5 + 1) + '.html';
    }

    function opensCar(art, index) {
      if (!art) { return art; }
      var cta = art.querySelector('a[class$="__cta"]');
      if (cta) {
        cta.href = carHref(index);
        return art;
      }
      var head = art.querySelector('h3');
      if (head && !head.querySelector('a')) {
        var link = el('a', head.className.replace(/__model$/, '__go'), head.textContent);
        link.href = carHref(index);
        head.textContent = '';
        head.appendChild(link);
      }
      return art;
    }

    function cardOf(c, lead, index) {
      if (CARD === 'pgrid') { return opensCar(wideCard(c, 'pgrid'), index); }
      if (CARD === 'panel') { return opensCar(panelCard(c), index); }
      if (CARD === 'map') { return opensCar(mapCard(c), index); }
      if (CARD === 'spec') { return opensCar(specCard(c, lead, index), index); }
      if (CARD === 'ink') { return opensCar(inkCard(c), index); }
      return opensCar(CARD === 'photo' ? photoCard(c, lead) : faultCard(c, lead), index);
    }

    /* -- the page numbers, with the gap the design shows ----------------
       The reference reads 1 · 2 · 3 · 4 · … · 6 on the first page: the first
       and the last are always there, a short run follows the current page,
       and a gap stands in for whatever is skipped. */
    function pageList(current, last) {
      var out = [1];
      var from = Math.max(2, current - 1);
      var to = Math.min(last - 1, Math.max(current + 2, 4));
      var i;
      if (last <= 1) { return out; }
      if (from > 2) { out.push('gap'); }
      for (i = from; i <= to; i++) { out.push(i); }
      if (to < last - 1) { out.push('gap'); }
      out.push(last);
      return out;
    }

    /* v1 and v2 set the numbers as separate buttons; v3 welds them into one
       segmented rail. Same rule behind both, only the class names differ. */
    var RAIL = pagesEl ? pagesEl.hasAttribute('data-rail') : false;
    var TILE = pagesEl ? pagesEl.hasAttribute('data-tile') : false;
    var PNUM = RAIL ? 'rail__page' : (TILE ? 'mpage' : 'pnum');
    var PGAP = RAIL ? 'rail__gap' : (TILE ? 'mpage mpage--gap' : 'pnum pnum--gap');
    var PON = RAIL ? ' rail__page--on' : (TILE ? ' mpage--on' : ' pnum--on');

    function paintPages(current, last) {
      if (!pagesEl) { return; }
      pagesEl.textContent = '';
      pageList(current, last).forEach(function (p) {
        if (p === 'gap') {
          pagesEl.appendChild(el('span', PGAP, '…'));
          return;
        }
        var b = el('button', PNUM + (p === current ? PON : ''), String(p));
        b.type = 'button';
        if (p === current) { b.setAttribute('aria-current', 'page'); }
        b.addEventListener('click', function () {
          page = p;
          render(false);
          grid.scrollIntoView({ block: 'start' });
        });
        pagesEl.appendChild(b);
      });
    }

    var lastCount = TOTAL;

    /* "SCROLL FOR 8 MORE" is a geometric claim, not a count of the results:
       it is how many cards are still below the fold of the rail. Counted
       rather than written down, and recounted as the rail is scrolled. */
    var moreEl = document.getElementById('more-count');
    /* On the narrow panel the results block is the scroller itself; on the
       wide one the scroller holds two wrappers and shows one at a time. */
    var scrollEl = document.getElementById('panel-scroll') || grid;

    function paintScroll() {
      if (!moreEl) { return; }
      /* Measured against the scroller's own box: the panel is the positioned
         ancestor here, so offsetTop would be counted from the top of the head
         and put every card below the fold. A card in the hidden view has no
         box at all, and does not count. */
      var edge = scrollEl.getBoundingClientRect().top + scrollEl.clientHeight + 1;
      var n = 0;
      Array.prototype.forEach.call(scrollEl.querySelectorAll('article'), function (card) {
        var b = card.getBoundingClientRect();
        if (b.height && b.bottom > edge) { n += 1; }
      });
      moreEl.textContent = n ? 'SCROLL FOR ' + n + ' MORE' : 'THAT IS ALL OF THEM';
    }

    if (moreEl) { scrollEl.addEventListener('scroll', paintScroll); }

    /* VIEW MORE sits at the end of each wrapper, inside the scroll area, so it
       is reached by scrolling rather than by leaving the panel. render() empties
       its wrapper, so the button is put back after every paint. */
    var growBtns = Array.prototype.slice.call(document.querySelectorAll('.vmore'));

    growBtns.forEach(function (b) {
      b.addEventListener('click', function () {
        page += 1;
        render(false);
      });
    });

    function setText(id, value) {
      var n = document.getElementById(id);
      if (n) { n.textContent = value; }
    }

    /* -- one render, one count ------------------------------------------ */
    function render(reset) {
      var list = sorted(cars.filter(matches));
      lastCount = list.length;

      var last = Math.max(1, Math.ceil(lastCount / SIZE));
      if (reset) { page = 1; }
      page = Math.min(Math.max(1, page), last);

      /* Only this page's cars are on screen — the rest are on other pages.
         The scrolling rail has no pages, and 0 * Infinity is NaN, so it says
         so rather than arriving at an offset that slices nothing. */
      var from = (SIZE === Infinity || GROW) ? 0 : (page - 1) * SIZE;
      var slice = list.slice(from, GROW ? page * SIZE : from + SIZE);

      var frag = document.createDocumentFragment();
      slice.forEach(function (c, i) { frag.appendChild(cardOf(c, from + i === 0, from + i)); });
      grid.textContent = '';
      grid.appendChild(frag);

      /* v5 keeps both views in the DOM and hides one, so both are painted. */
      if (rowsEl) {
        var rfrag = document.createDocumentFragment();
        slice.forEach(function (c, i) {
          rfrag.appendChild(opensCar(ROW === 'prow' ? wideCard(c, 'prow')
            : (ROW === 'map' ? mapRow(c, from + i === 0) : rowOf(c, from + i === 0)), from + i));
        });
        rowsEl.textContent = '';
        rowsEl.appendChild(rfrag);
      }

      if (emptyEl) { emptyEl.hidden = lastCount !== 0; }

      /* Everything that states a number reads these two. */
      var seen = Math.min(page * SIZE, lastCount);

      setText('all-count', lastCount);

      growBtns.forEach(function (b) {
        var host = b.getAttribute('data-host') === 'rows' ? rowsEl : grid;
        if (host) { host.appendChild(b); }
        b.hidden = from + slice.length >= lastCount;
      });
      paintScroll();

      setText('count-num', lastCount);
      setText('count-total', TOTAL);
      setText('show-count', lastCount);
      setText('bar-count', lastCount);
      setText('shown-num', seen);
      setText('shown-total', lastCount);
      setText('left-num', lastCount - seen);

      var fill = document.getElementById('pager-fill');
      if (fill) {
        fill.style.setProperty('--seen', (lastCount ? Math.round((seen / lastCount) * 100) : 0) + '%');
      }

      paintPages(page, last);
      paintSearch(lastCount);
      paintRail(lastCount, page, last, from + slice.length);
      paintMap(list, lastCount);

      if (moreBtn) {
        moreBtn.disabled = page >= last;
        moreBtn.childNodes[0].nodeValue = 'Show ' + Math.min(SIZE, Math.max(0, lastCount - seen)) + ' more';
      }
      if (nextBtn) { nextBtn.disabled = !wraps && page >= last; }
      if (prevBtn) { prevBtn.disabled = page <= 1; }
    }

    /* -- wiring ---------------------------------------------------------- */
    selects.forEach(function (s) {
      s.addEventListener('change', function () { render(true); });
    });

    chips.forEach(function (c) {
      c.addEventListener('click', function () {
        chip = c.getAttribute('data-chip');
        chips.forEach(function (o) { chipOn(o, o === c); });
        render(true);
      });
    });

    /* Two sort controls, one order — whichever is used, both show it. */
    sorts.forEach(function (s) {
      s.addEventListener('change', function () {
        sorts.forEach(function (o) { o.value = s.value; });
        render(true);
      });
    });

    /* v5's forward button names its own behaviour — "Next 4" while there is
       more, "Back to page 1" at the end — so at the end it goes there rather
       than greying out and contradicting its label. */
    var wraps = !!document.getElementById('next-label');

    function turn() {
      var last = Math.max(1, Math.ceil(lastCount / SIZE));
      page = (wraps && page >= last) ? 1 : page + 1;
      render(false);
      grid.scrollIntoView({ block: 'start' });
    }

    if (moreBtn) { moreBtn.addEventListener('click', turn); }
    if (nextBtn) { nextBtn.addEventListener('click', turn); }

    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        page -= 1;
        render(false);
        grid.scrollIntoView({ block: 'start' });
      });
    }

    var showBtn = document.getElementById('show-btn');
    if (showBtn) {
      showBtn.addEventListener('click', function () {
        grid.scrollIntoView({ block: 'start' });
      });
    }

    var resetBtn = document.getElementById('reset');
    if (resetBtn) {
      resetBtn.addEventListener('click', function () {
        selects.forEach(function (s) { s.selectedIndex = 0; });
        sorts.forEach(function (s) { s.selectedIndex = 0; });
        chip = 'all';
        chips.forEach(function (o) { chipOn(o, o.getAttribute('data-chip') === 'all'); });
        render(true);
      });
    }

    /* -- the map, listing-map-v1 only -------------------------------------
       The point of a split view is that the two halves know about each other,
       so they do: hovering a card lifts its door, choosing a door filters the
       column to it, and "Search this area" cuts the results to whatever the
       viewport is showing. Without that the page is two lists side by side. */
    var mapEl = document.getElementById('cars-map');
    var pins = {};
    var carsMap = null;

    function pinHtml(city, count, on) {
      return '<span class="mpin' + (on ? ' mpin--on' : '') + (count ? '' : ' mpin--empty') + '">'
        + '<span class="mpin__label">'
        + '<span class="mpin__dot' + (on ? ' pulse' : '') + '"></span>' + city
        + '<span class="mpin__badge">' + count + '</span>'
        + '</span><span class="mpin__stalk"></span></span>';
    }

    function paintMap(list, count) {
      if (!carsMap) { return; }
      var byCity = {};
      list.forEach(function (c) { byCity[c.city] = (byCity[c.city] || 0) + 1; });

      Object.keys(pins).forEach(function (city) {
        var p = pins[city];
        p.marker.getElement().innerHTML = pinHtml(city, byCity[city] || 0, city === mapCity);
      });

      setText('plate-count', count);

      /* Every pill says how many it would leave, which is the same question
         the results column is answering. */
      chips.forEach(function (b) {
        var kind = b.getAttribute('data-chip');
        var out = document.getElementById('n-' + kind);
        if (!out) { return; }
        var was = chip;
        chip = kind;
        var n = 0;
        cars.forEach(function (c) { if (matches(c)) { n += 1; } });
        chip = was;
        out.textContent = n;
        b.classList.toggle('cpill--none', n === 0 && kind !== chip);
      });
    }

    if (mapEl && typeof L !== 'undefined') {
      carsMap = L.map(mapEl, {
        scrollWheelZoom: false,   /* let the page scroll past the map */
        zoomControl: false,       /* the design puts its own pair bottom-right */
        attributionControl: true
      });

      L.tileLayer('../../../tile.openstreetmap.org/%7bz%7d/%7bx%7d/%7by%7d.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
      }).addTo(carsMap);

      var seen = {};
      var frame = [];
      cars.forEach(function (c) {
        if (seen[c.city]) { return; }
        seen[c.city] = true;
        frame.push([c.lat, c.lng]);
        var marker = L.marker([c.lat, c.lng], {
          icon: L.divIcon({
            className: 'mpin-wrap',
            html: pinHtml(c.city, 0, false),
            iconSize: [0, 0],
            iconAnchor: [0, 0]
          }),
          title: c.city,
          riseOnHover: true
        }).addTo(carsMap);
        marker.on('click', function () { pickCity(c.city); });
        pins[c.city] = { marker: marker, lat: c.lat, lng: c.lng };
      });

      /* The floating rail sits over the left edge of the stage, so framing
         the seven doors evenly would put the westernmost ones behind it. The
         inset is the rail's own width plus its margin, measured rather than
         assumed, so a narrower panel on a phone insets less. */
      var railEl = document.querySelector('.mpanel');

      function frameAll() {
        carsMap.invalidateSize();
        var tl = [70, 70];
        var br = [70, 70];
        if (railEl) {
          var r = railEl.getBoundingClientRect();
          var m = mapEl.getBoundingClientRect();
          /* Which edge the rail covers decides which padding grows. As a left
             rail it eats into the top-left inset; below 780px it lies along
             the bottom edge instead, and padding the top would push the doors
             straight under it. */
          if (r.width < m.width - 80) { tl[0] = Math.round(r.right - m.left) + 40; }
          else { br[1] = Math.min(Math.round(m.bottom - r.top) + 20, Math.round(m.height * 0.82)); }
        }
        carsMap.fitBounds(frame, { paddingTopLeft: tl, paddingBottomRight: br });
      }

      /* The stage is sized by the grid around it, so Leaflet can measure it as
         nothing if it looks too early — and a map that thinks it is 0px wide
         reports a viewport that contains none of the seven doors. */
      frameAll();
      window.requestAnimationFrame(frameAll);
      window.addEventListener('load', frameAll);

      function pickCity(city) {
        mapCity = mapCity === city ? '' : city;
        mapBounds = null;
        syncSearchBtn();
        render(true);
        if (mapCity && pins[mapCity]) {
          carsMap.panTo([pins[mapCity].lat, pins[mapCity].lng], { animate: false });
        }
        /* The rail keeps its scroll position otherwise, and the cars it was
           showing are no longer the cars that are left. */
        if (moreEl) { scrollEl.scrollTop = 0; paintScroll(); }
      }

      var searchBtn = document.getElementById('map-search');
      syncSearch = function () { syncSearchBtn(); };

      function syncSearchBtn() {
        if (!searchBtn) { return; }
        var on = !!(mapBounds || mapCity);
        searchBtn.classList.toggle('msearch--on', on);
        searchBtn.childNodes[searchBtn.childNodes.length - 1].nodeValue =
          on ? 'Show every area' : 'Search this area';
      }

      if (searchBtn) {
        searchBtn.addEventListener('click', function () {
          if (mapBounds || mapCity) {
            mapBounds = null;
            mapCity = '';
          } else {
            mapBounds = carsMap.getBounds();
          }
          syncSearchBtn();
          render(true);
        });
      }

      /* Every move here is unanimated on purpose. Leaflet's animated zoom
         waits on a transition that some browsers never report finishing — and
         a zoom button that silently does nothing is worse than an instant one.
         setView with animate:false takes the same path fitBounds does. */
      function zoomBy(step) {
        carsMap.setView(carsMap.getCenter(), carsMap.getZoom() + step, { animate: false });
      }

      var zin = document.getElementById('map-in');
      var zout = document.getElementById('map-out');
      if (zin) { zin.addEventListener('click', function () { zoomBy(1); }); }
      if (zout) { zout.addEventListener('click', function () { zoomBy(-1); }); }

      /* A card lifts its own door while the cursor is on it. */
      function lightCity(city, on) {
        var p = pins[city];
        if (!p) { return; }
        var box = p.marker.getElement();
        if (box && box.firstChild) { box.firstChild.classList.toggle('mpin--lit', on); }
      }

      [grid, rowsEl].forEach(function (host) {
        if (!host) { return; }
        host.addEventListener('mouseover', function (e) {
          var card = e.target.closest ? e.target.closest('[data-city]') : null;
          if (card) { lightCity(card.getAttribute('data-city'), true); }
        });
        host.addEventListener('mouseout', function (e) {
          var card = e.target.closest ? e.target.closest('[data-city]') : null;
          if (card) { lightCity(card.getAttribute('data-city'), false); }
        });
      });

      window.addEventListener('resize', function () { carsMap.invalidateSize(); });
    }

    /* -- the rail, v5 only ------------------------------------------------
       Eleven facets, and beside every inspection box the number of cars it
       would leave. The handoff is firm that those counts have to be counted
       rather than written down, so each one asks the same result set the grid
       does, with its own box taken out of the question. */
    var chipBox5 = document.getElementById('frail-chips');
    var tickHost = ticksEl;

    function labelOf(sel) {
      return sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].textContent : sel.value;
    }

    function facetCount(flag) {
      var test = FLAG_TEST[flag];
      var n = 0;
      if (!test) { return 0; }
      cars.forEach(function (c) { if (test(c) && matches(c, flag)) { n += 1; } });
      return n;
    }

    function activeCount() {
      var n = 0;
      selects.forEach(function (sel) { if (sel.value) { n += 1; } });
      ranges.forEach(function (r) { if (parseFloat(r.value) < parseFloat(r.max)) { n += 1; } });
      boxes.forEach(function (b) { if (b.checked) { n += 1; } });
      return n;
    }

    function clearOneFacet(kind, key) {
      if (kind === 'select') {
        var sel = null;
        selects.forEach(function (x) { if (x.getAttribute('data-filter') === key) { sel = x; } });
        if (sel) { sel.selectedIndex = 0; }
      } else if (kind === 'range') {
        ranges.forEach(function (r) { if (r.getAttribute('data-range') === key) { r.value = r.max; paintRange(r); } });
      } else {
        boxes.forEach(function (b) { if (b.getAttribute('data-flag') === key) { b.checked = false; flags[key] = false; } });
      }
      render(true);
    }

    function chipFor(kind, key, text) {
      var wrap = el('span', 'fchip');
      wrap.appendChild(document.createTextNode(text));
      var x = el('button', 'fchip__x');
      x.type = 'button';
      x.setAttribute('aria-label', 'Remove the ' + text + ' filter');
      var cross = svgNode('3.4', ['M6 6l12 12M18 6L6 18']);
      cross.setAttribute('width', '9');
      cross.setAttribute('height', '9');
      x.appendChild(cross);
      x.addEventListener('click', function () { clearOneFacet(kind, key); });
      wrap.appendChild(x);
      return wrap;
    }

    /* The track is ours and the input lies over it invisibly, so the fill and
       the knob follow the value by hand. */
    function paintRange(r) {
      var min = parseFloat(r.min);
      var max = parseFloat(r.max);
      var pct = max > min ? Math.round(((parseFloat(r.value) - min) / (max - min)) * 100) : 100;
      var track = r.parentNode;
      if (track) {
        if (pct >= 100) { track.style.removeProperty('--fill'); } else { track.style.setProperty('--fill', pct + '%'); }
      }
      var out = document.getElementById(r.getAttribute('data-out'));
      if (!out) { return; }
      var v = parseFloat(r.value);
      out.textContent = r.getAttribute('data-range') === 'price'
        ? formatMoney(v)
        : v.toLocaleString('en-US') + ' km';
    }

    function paintRail(count, current, last, seen) {
      if (!panel.classList.contains('frail')) { return; }

      if (chipBox5) {
        chipBox5.textContent = '';
        selects.forEach(function (sel) {
          if (sel.value) { chipBox5.appendChild(chipFor('select', sel.getAttribute('data-filter'), labelOf(sel))); }
        });
        ranges.forEach(function (r) {
          if (parseFloat(r.value) < parseFloat(r.max)) {
            var out = document.getElementById(r.getAttribute('data-out'));
            chipBox5.appendChild(chipFor('range', r.getAttribute('data-range'),
              (r.getAttribute('data-range') === 'price' ? 'Up to ' : 'Under ') + (out ? out.textContent : r.value)));
          }
        });
        boxes.forEach(function (b) {
          if (b.checked) { chipBox5.appendChild(chipFor('flag', b.getAttribute('data-flag'), b.getAttribute('data-label'))); }
        });
      }

      var on = activeCount();
      setText('frail-count', on);
      setText('filters-on', on + (on === 1 ? ' filter on' : ' filters on'));
      setText('match-count', count);
      setText('bar-count', count);

      var checked = 0;
      boxes.forEach(function (b) {
        var flag = b.getAttribute('data-flag');
        var n = facetCount(flag);
        var out = document.getElementById('n-' + flag);
        if (out) { out.textContent = n; }
        var row = b.closest ? b.closest('.fopt') : null;
        if (row) { row.classList.toggle('fopt--none', n === 0 && !b.checked); }
        if (b.checked) { checked += 1; }
      });
      setText('insp-count', checked);

      /* The rail says how far through the floor you are, not just which page. */
      setText('seen-num', seen);
      setText('seen-total', count);
      setText('seen-pct', count ? Math.round((seen / count) * 100) + '%' : '0%');

      var fill = document.getElementById('prail-fill');
      if (fill) { fill.style.setProperty('--fill', Math.round((current / last) * 100) + '%'); }

      var nextLabel = document.getElementById('next-label');
      if (nextLabel) {
        nextLabel.textContent = current < last
          ? 'Next ' + Math.min(SIZE, Math.max(0, count - current * SIZE))
          : 'Back to page 1';
      }

      /* Nothing to page through, so the rail says nothing rather than
         offering a page that runs from one to zero. */
      var prail = document.querySelector('.prail');
      if (prail) { prail.hidden = count === 0; }

      /* One page and the buttons have nowhere to go — the figure above them
         still does, so only the pair is taken away. */
      var nav = document.querySelector('.prail__nav');
      if (nav) { nav.hidden = last <= 1; }

      if (!tickHost) { return; }
      tickHost.textContent = '';
      tickHost.style.setProperty('grid-template-columns', 'repeat(' + last + ', 1fr)');
      var i;
      for (i = 1; i <= last; i++) {
        tickHost.appendChild(tickFor(i, current, last, count));
      }
    }

    function tickFor(nth, current, last, count) {
      var b = el('button', 'ptick' + (nth === current ? ' ptick--on' : (nth < current ? ' ptick--done' : '')));
      b.type = 'button';
      var dot = el('span', 'ptick__dot');
      dot.setAttribute('aria-hidden', 'true');
      b.appendChild(dot);
      b.appendChild(el('span', 'ptick__num', (nth < 10 ? '0' : '') + nth));
      b.appendChild(el('span', 'ptick__range',
        ((nth - 1) * SIZE + 1) + '\u2013' + Math.min(nth * SIZE, count)));
      if (nth === current) { b.setAttribute('aria-current', 'page'); }
      b.addEventListener('click', function () {
        page = nth;
        render(false);
        grid.scrollIntoView({ block: 'start' });
      });
      return b;
    }

    ranges.forEach(function (r) {
      paintRange(r);
      r.addEventListener('input', function () { paintRange(r); });
      r.addEventListener('change', function () { render(true); });
    });

    boxes.forEach(function (b) {
      flags[b.getAttribute('data-flag')] = b.checked;
      b.addEventListener('change', function () {
        flags[b.getAttribute('data-flag')] = b.checked;
        render(true);
      });
    });

    var views = Array.prototype.slice.call(document.querySelectorAll('[data-view]'));
    if (views.length && rowsEl) {
      views.forEach(function (btn) {
        btn.addEventListener('click', function () {
          var want = btn.getAttribute('data-view');
          views.forEach(function (o) {
            var on = o === btn;
            /* The switch wears two coats — a segmented pill on the sidebar
               pages, a round pair on the map's control bar — so the modifier
               comes from the element rather than from one hard-coded name. */
            var base = o.classList.contains('vtog__btn') ? 'vtog__btn'
              : (o.classList.contains('vpair__btn') ? 'vpair__btn' : 'vswitch__btn');
            o.classList.toggle(base + '--on', on);
            o.setAttribute('aria-pressed', on ? 'true' : 'false');
          });
          grid.hidden = want !== 'cards';
          rowsEl.hidden = want !== 'rows';
          /* The two views are different heights, so what is below the fold
             changes with them. */
          paintScroll();
        });
      });
    }

    var railReset = document.getElementById('frail-reset');
    var barClear = document.getElementById('rbar-clear');
    [railReset, barClear].forEach(function (btn) {
      if (!btn) { return; }
      btn.addEventListener('click', function () {
        selects.forEach(function (sel) { sel.selectedIndex = 0; });
        ranges.forEach(function (r) { r.value = r.max; paintRange(r); });
        boxes.forEach(function (b) { b.checked = false; flags[b.getAttribute('data-flag')] = false; });
        render(true);
      });
    });

    /* The rail's own RESET clears the map with the filters: its head counts
       what is in view, so leaving a chosen city or a drawn area behind would
       contradict the number directly above it. */
    var panelReset = document.getElementById('panel-reset');
    if (panelReset) {
      panelReset.addEventListener('click', function () {
        selects.forEach(function (sel) { sel.selectedIndex = 0; });
        ranges.forEach(function (r) { r.value = r.max; paintRange(r); });
        chip = 'all';
        chips.forEach(function (o) { chipOn(o, o.getAttribute('data-chip') === 'all'); });
        mapCity = '';
        mapBounds = null;
        syncSearch();
        render(true);
        scrollEl.scrollTop = 0;
        paintScroll();
      });
    }

    var inspHead = document.getElementById('insp-head');
    if (inspHead) {
      inspHead.addEventListener('click', function () {
        var box = inspHead.parentNode;
        var shut = box.classList.toggle('finsp--shut');
        inspHead.setAttribute('aria-expanded', shut ? 'false' : 'true');
      });
    }

    /* The rail is a search form, so its button is a submit: with the script
       running it just walks you down to the results it has already filtered. */
    if (document.getElementById('rail-show') && panel.tagName === 'FORM') {
      panel.addEventListener('submit', function (e) {
        e.preventDefault();
        render(true);
        grid.scrollIntoView({ block: 'start' });
      });
    }

    /* -- the search panel, v3 only ---------------------------------------
       The sentence is the entry point and the chips are the promise: every
       term the page claims to have understood is shown back, and taking one
       off removes it from the sentence too, so the two can never disagree. */
    var qField = document.getElementById('lq');
    var chipBox = document.getElementById('chips');
    var readRow = document.getElementById('read-row');
    var clearAll = document.getElementById('clear-all');
    var narrow = Array.prototype.slice.call(panel.querySelectorAll('.nchip'));

    function selectFor(key) {
      var i;
      for (i = 0; i < selects.length; i++) {
        if (selects[i].getAttribute('data-filter') === key) { return selects[i]; }
      }
      return null;
    }

    /* The cell shows its own value, so the transparent select over it never
       has to be seen to be understood. */
    function paintCell(sel) {
      var cell = sel.closest ? sel.closest('.vcell') : null;
      if (!cell) { return; }
      var on = !!sel.value;
      cell.classList.toggle('vcell--on', on);
      var val = cell.querySelector('.vcell__val');
      if (val) {
        var chev = val.querySelector('.vcell__chev');
        val.textContent = sel.options[sel.selectedIndex].textContent;
        if (chev) { val.appendChild(chev); }
      }
    }

    function paintSearch(count) {
      if (!chipBox) { return; }

      chipBox.textContent = '';
      var terms = 0;

      selects.forEach(function (sel) {
        paintCell(sel);
        if (!sel.value) { return; }
        terms += 1;

        var key = sel.getAttribute('data-filter');
        var wrap = el('span', 'rchip');
        wrap.appendChild(el('span', 'rchip__key', sel.getAttribute('data-chip-label') || key.toUpperCase()));
        wrap.appendChild(el('span', 'rchip__val', sel.options[sel.selectedIndex].textContent));

        var x = el('button', 'rchip__x');
        x.type = 'button';
        x.setAttribute('aria-label', 'Remove the ' + (sel.getAttribute('data-chip-label') || key).toLowerCase() + ' filter');
        var cross = svgNode('3.2', ['M6 6l12 12M18 6L6 18']);
        cross.setAttribute('width', '10');
        cross.setAttribute('height', '10');
        x.appendChild(cross);
        x.addEventListener('click', function () { clearOne(key); });
        wrap.appendChild(x);

        chipBox.appendChild(wrap);
      });

      Object.keys(FLAG_PHRASE).forEach(function (flag) {
        if (!flags[flag]) { return; }
        terms += 1;
        var meta = flagMeta(flag);
        var wrap = el('span', 'rchip');
        wrap.appendChild(el('span', 'rchip__key', meta.label));
        wrap.appendChild(el('span', 'rchip__val', meta.value));

        var x = el('button', 'rchip__x');
        x.type = 'button';
        x.setAttribute('aria-label', 'Remove the ' + meta.value.toLowerCase() + ' filter');
        var cross = svgNode('3.2', ['M6 6l12 12M18 6L6 18']);
        cross.setAttribute('width', '10');
        cross.setAttribute('height', '10');
        x.appendChild(cross);
        x.addEventListener('click', function () { clearFlag(flag); });
        wrap.appendChild(x);

        chipBox.appendChild(wrap);
      });

      if (readRow) { readRow.classList.toggle('vread--empty', terms === 0); }
      if (clearAll) { clearAll.hidden = terms === 0; }
      setText('term-count', terms);
      setText('match-count', count);
      setText('tile-count', count);

      narrow.forEach(function (b) {
        var sets = b.getAttribute('data-sets');
        var on;
        if (sets) {
          var parts = sets.split(':');
          var sel = selectFor(parts[0]);
          on = !!sel && sel.value === parts.slice(1).join(':');
        } else {
          on = !!flags[b.getAttribute('data-chip')];
        }
        b.classList.toggle('nchip--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
    }

    function flagMeta(flag) {
      var i;
      for (i = 0; i < Q_FLAGS.length; i++) {
        if (Q_FLAGS[i].flag === flag) { return Q_FLAGS[i]; }
      }
      return { label: flag.toUpperCase(), value: flag };
    }

    /* Applying a sentence replaces the whole state: what it does not say, it
       does not ask for. */
    function applyQuery(text) {
      var hits = parseQuery(text);
      var wanted = {};
      flags = {};
      hits.forEach(function (h) {
        if (h.flag) { flags[h.flag] = true; } else { wanted[h.key] = h.value; }
      });
      selects.forEach(function (sel) {
        var key = sel.getAttribute('data-filter');
        sel.value = wanted[key] || '';
        if (sel.selectedIndex < 0) { sel.selectedIndex = 0; }
      });
      render(true);
    }

    function clearFlag(flag) {
      flags[flag] = false;
      if (qField) {
        parseQuery(qField.value).forEach(function (h) {
          if (h.flag === flag) { qField.value = dropSpan(qField.value, h.from, h.to); }
        });
      }
      render(true);
    }

    /* A suggestion pressed by hand joins the sentence, so the field still
       reads back everything the page is filtering on. */
    function writeFlag(flag, on) {
      if (!qField) { return; }
      var hit = null;
      parseQuery(qField.value).forEach(function (h) {
        if (h.flag === flag) { hit = h; }
      });
      if (hit && !on) {
        qField.value = dropSpan(qField.value, hit.from, hit.to);
      } else if (!hit && on) {
        qField.value = qField.value.trim()
          ? qField.value.trim().replace(/,$/, '') + ', ' + FLAG_PHRASE[flag]
          : FLAG_PHRASE[flag];
      }
    }

    /* Taking one term off takes its words with it. A cell set by hand has no
       words to take, so only the filter is reset. */
    function clearOne(key) {
      var sel = selectFor(key);
      if (sel) { sel.selectedIndex = 0; }
      if (qField) {
        parseQuery(qField.value).forEach(function (h) {
          if (h.key === key) { qField.value = dropSpan(qField.value, h.from, h.to); }
        });
      }
      render(true);
    }

    /* A cell used by hand writes itself back into the sentence, replacing the
       words that used to set it or joining the end of the list. */
    function writeBack(key, value) {
      if (!qField) { return; }
      var hits = parseQuery(qField.value);
      var i;
      for (i = 0; i < hits.length; i++) {
        if (hits[i].key === key) {
          qField.value = value
            ? qField.value.slice(0, hits[i].from) + phraseOf(key, value) + qField.value.slice(hits[i].to)
            : dropSpan(qField.value, hits[i].from, hits[i].to);
          return;
        }
      }
      if (!value) { return; }
      qField.value = qField.value.trim()
        ? qField.value.trim().replace(/,$/, '') + ', ' + phraseOf(key, value)
        : phraseOf(key, value);
    }

    if (qField) {
      var typing = null;
      qField.addEventListener('input', function () {
        window.clearTimeout(typing);
        typing = window.setTimeout(function () { applyQuery(qField.value); }, 300);
      });
      qField.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') { return; }
        e.preventDefault();
        window.clearTimeout(typing);
        applyQuery(qField.value);
      });

      selects.forEach(function (sel) {
        sel.addEventListener('change', function () {
          writeBack(sel.getAttribute('data-filter'), sel.value);
        });
      });
    }

    var goBtn = document.getElementById('lq-go');
    if (goBtn && qField) {
      goBtn.addEventListener('click', function () {
        applyQuery(qField.value);
        grid.scrollIntoView({ block: 'start' });
      });
    }

    if (clearAll) {
      clearAll.addEventListener('click', function () {
        if (qField) { qField.value = ''; }
        selects.forEach(function (sel) { sel.selectedIndex = 0; });
        flags = {};
        render(true);
      });
    }

    narrow.forEach(function (b) {
      b.addEventListener('click', function () {
        var sets = b.getAttribute('data-sets');
        if (sets) {
          var parts = sets.split(':');
          var key = parts[0];
          var value = parts.slice(1).join(':');
          var sel = selectFor(key);
          if (sel) {
            var next = sel.value === value ? '' : value;
            sel.value = next;
            if (sel.selectedIndex < 0) { sel.selectedIndex = 0; }
            writeBack(key, next);
          }
        } else {
          var flag = b.getAttribute('data-chip');
          flags[flag] = !flags[flag];
          writeFlag(flag, flags[flag]);
        }
        render(true);
      });
    });

    var tile = document.getElementById('show-matches');
    if (tile) {
      tile.addEventListener('click', function () {
        grid.scrollIntoView({ block: 'start' });
      });
    }

    /* Prices restate money, so they follow the currency switch. */
    onCurrencyChange(function () { render(false); });

    render(false);

    /* -- the sourcing brief --------------------------------------------- */
    var brief = document.getElementById('brief');
    if (!brief) { return; }

    var done = document.getElementById('brief-done');
    var briefError = document.getElementById('brief-error');
    var wanted = document.getElementById('brief-model');
    var mail = document.getElementById('brief-email');

    brief.addEventListener('submit', function (e) {
      e.preventDefault();
      var bad = [];
      if (!wanted.value.trim()) { bad.push(wanted); }
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(mail.value.trim())) { bad.push(mail); }
      [wanted, mail].forEach(function (f) {
        f.setAttribute('aria-invalid', bad.indexOf(f) >= 0 ? 'true' : 'false');
      });
      if (bad.length) {
        briefError.hidden = false;
        bad[0].focus();
        return;
      }
      briefError.hidden = true;
      setText('brief-ref', 'SR-' + String(Date.now()).slice(-6));
      setText('brief-what', wanted.value.trim());
      setText('brief-mail', mail.value.trim());
      if (done && typeof done.showModal === 'function') { openDialog(done); }
    });

    [wanted, mail].forEach(function (f) {
      f.addEventListener('input', function () {
        if (f.getAttribute('aria-invalid') === 'true') { f.setAttribute('aria-invalid', 'false'); }
      });
    });

    if (done) {
      done.addEventListener('click', function (e) {
        if (e.target === done) { done.close('done'); }
      });
    }
  }

  /* ------------------------------------------------------------------ Go */

  /* ------------------------------------------------------------------ 27 */
  /* Listing single — the car detail page.

     Four things move on this page, and one of them matters more than the rest:
     the monthly payment is stated twice, in the finance panel and again in the
     buy panel, and the two must never disagree. They read one calculation. */

  var SINGLE_APR = 0.039;

  function initSingle() {
    /* Five layouts, one module: the balanced grid, the heavy sidebar, the
       dossier, the wide canvas and the two rails. Guarding on one of their
       names would silently skip the others — which is exactly what it did to
       v3. */
    var page = document.querySelector('.slay, .dos, .wgal, .pen');
    if (!page) { return; }

    function byId(id) { return document.getElementById(id); }

    function setText(id, value) {
      var n = byId(id);
      if (n) { n.textContent = value; }
    }

    /* -- the gallery ---------------------------------------------------
       Six frames of this car, each thumbnail carrying its own full-size
       address: choosing one swaps what the hero is showing, and the counter
       says which frame that is. The first frame is the hero's own, so it is
       remembered before anything is swapped in. */
    var heroImg = document.querySelector('.sgal__img');
    var thumbs = Array.prototype.slice.call(document.querySelectorAll('.sthumb'));
    var firstShot = heroImg ? { src: heroImg.getAttribute('src'), alt: heroImg.getAttribute('alt') } : null;

    thumbs.forEach(function (t) {
      t.addEventListener('click', function () {
        thumbs.forEach(function (o) {
          var on = o === t;
          o.classList.toggle('sthumb--on', on);
          if (on) { o.setAttribute('aria-current', 'true'); } else { o.removeAttribute('aria-current'); }
        });
        if (heroImg) {
          heroImg.src = t.getAttribute('data-full') || firstShot.src;
          heroImg.alt = t.getAttribute('data-alt') || firstShot.alt;
        }
        setText('shot-n', t.getAttribute('data-shot') || '1');
      });
    });

    /* -- the calculator -------------------------------------------------
       principal = price - deposit, and the annuity on top of it. The three
       totals are derived from the monthly figure the page actually prints,
       so the arithmetic closes for anyone who checks it with a calculator. */
    var fins = Array.prototype.slice.call(document.querySelectorAll('[data-fin]'));
    /* v3 has no price element to read the car's price from — the dossier's
       price card carries it instead. */
    var priceEl3 = document.querySelector('.dprice__now');

    if (fins.length) {
      var price = 0;
      var priceEl = document.querySelector('.sbuy__num, .wpanel__now, .pbuy__now') || priceEl3;
      if (priceEl) { price = parseFloat(priceEl.textContent.replace(/[^0-9.]/g, '')) || 0; }

      var state = { deposit: 0, term: 60 };
      fins.forEach(function (r) { state[r.getAttribute('data-fin')] = parseFloat(r.value); });

      /* The track is the input's own parent on all three layouts — .sslider__track
         on v1 and v2, .dprice__track on v3 — so the fill needs no layout test. */
      function paintTrack(r) {
        var min = parseFloat(r.min);
        var max = parseFloat(r.max);
        var pctv = max > min ? ((parseFloat(r.value) - min) / (max - min)) * 100 : 0;
        var track = r.parentNode;
        if (track) { track.style.setProperty('--fill', Math.round(pctv) + '%'); }
      }

      function renderSingleFinance() {
        var term = Math.max(1, Math.round(state.term));
        var principal = Math.max(0, price - state.deposit);
        var r = SINGLE_APR / 12;
        var mo = principal * r / (1 - Math.pow(1 + r, -term));
        var shown = Math.round(mo);

        setText('out-deposit', formatMoney(state.deposit));
        setText('out-term', term + ' months');
        setText('fin-term', term);
        setText('fin-mo', formatMoney(mo));
        /* Three places state this payment on v2 — the calculator, the price
           line and the FINANCE FROM band — and none of them may disagree. */
        setText('buy-mo', formatMoney(mo));
        setText('side-mo', formatMoney(mo));
        setText('side-term', term);
        setText('fin-financed', formatMoney(principal));
        setText('fin-interest', formatMoney(Math.max(0, shown * term - principal)));
      }

      fins.forEach(function (r) {
        paintTrack(r);
        r.addEventListener('input', function () {
          state[r.getAttribute('data-fin')] = parseFloat(r.value);
          paintTrack(r);
          renderSingleFinance();
        });
      });

      /* The figures are money, so they are repainted when the currency is. */
      onCurrencyChange(renderSingleFinance);
    }

    /* -- the reviews ----------------------------------------------------
       Sorting reorders the cards; it does not renumber them. The MOST HELPFUL
       badge travels with its review, because it is a property of the review
       and not of the first position. */
    var list = byId('rev-list');
    var sorts = Array.prototype.slice.call(document.querySelectorAll('[data-rsort]'));

    if (list && sorts.length) {
      var cards = Array.prototype.slice.call(list.children);

      var ORDER = {
        helpful: function (a, b) { return num(b, 'helpful') - num(a, 'helpful'); },
        newest: function (a, b) { return num(a, 'age') - num(b, 'age'); },
        highest: function (a, b) { return num(b, 'score') - num(a, 'score') || num(b, 'helpful') - num(a, 'helpful'); },
        lowest: function (a, b) { return num(a, 'score') - num(b, 'score') || num(b, 'helpful') - num(a, 'helpful'); }
      };

      function num(el, key) { return parseFloat(el.getAttribute('data-' + key)) || 0; }

      sorts.forEach(function (b) {
        b.addEventListener('click', function () {
          var key = b.getAttribute('data-rsort');
          sorts.forEach(function (o) {
            var on = o === b;
            o.classList.toggle('ssort--on', on);
            o.setAttribute('aria-pressed', on ? 'true' : 'false');
          });
          cards.slice().sort(ORDER[key] || ORDER.helpful).forEach(function (c) {
            list.appendChild(c);
          });
        });
      });
    }

    /* A count that goes up and comes back down: nothing is posted anywhere,
       so pressing it twice has to leave the number where it started. */
    Array.prototype.forEach.call(document.querySelectorAll('[data-help]'), function (b) {
      var out = b.querySelector('.srevcard__helpn');
      if (!out) { return; }
      var base = parseInt(out.textContent, 10) || 0;
      b.addEventListener('click', function () {
        var on = b.classList.toggle('srevcard__help--on');
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
        out.textContent = base + (on ? 1 : 0);
        var card = b.closest ? b.closest('.srevcard') : null;
        if (card) { card.setAttribute('data-helpful', String(base + (on ? 1 : 0))); }
      });
    });

    /* -- the dossier map, v3 only -----------------------------------------
       One showroom, one pin. The listing pages build their map inside
       initListing, which needs a catalogue and a filter panel — the dossier has
       neither, so section 05 would otherwise hold an empty grey box. */
    var dmapEl = byId('cars-map');

    if (dmapEl && typeof L !== 'undefined' && !dmapEl.hasChildNodes()) {
      var where = dmapEl.getAttribute('data-at').split(',');
      var pin = dmapEl.classList.contains('pmap__canvas') ? 'ppin' : 'dpin';
      var dmap = L.map(dmapEl, {
        scrollWheelZoom: false,   /* let the page scroll past the map */
        zoomControl: true,
        attributionControl: true
      });

      L.tileLayer('../../../tile.openstreetmap.org/%7bz%7d/%7bx%7d/%7by%7d.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
      }).addTo(dmap);

      L.marker([parseFloat(where[0]), parseFloat(where[1])], {
        icon: L.divIcon({
          className: pin + '-wrap',
          html: '<span class="' + pin + '"><span class="' + pin + '__label">'
            + dmapEl.getAttribute('data-label')
            + '<span class="' + pin + '__badge">' + dmapEl.getAttribute('data-count')
            + '</span></span><span class="' + pin + '__stalk"></span>'
            + (pin === 'ppin' ? '<span class="ppin__dot"></span>' : '') + '</span>',
          iconSize: [0, 0],
          iconAnchor: [0, 0]
        }),
        title: dmapEl.getAttribute('data-label'),
        keyboard: false
      }).addTo(dmap);

      /* The panel is sized by the grid around it, so Leaflet can measure it as
         nothing if it looks too early. */
      function frameShowroom() {
        dmap.invalidateSize();
        dmap.setView([parseFloat(where[0]), parseFloat(where[1])], 14, { animate: false });
      }

      frameShowroom();
      window.requestAnimationFrame(frameShowroom);
      window.addEventListener('load', frameShowroom);
      window.addEventListener('resize', function () { dmap.invalidateSize(); });
    }

    /* -- the ten-frame gallery, v3 and v4 ---------------------------------
       Ten frames, six in the strip. prev and next wrap round the whole ten, so
       the four the strip has no room for are still reachable. The two layouts
       differ only in what their tiles are called. */
    var GALLERIES = [
      { stage: '.dgal__img', thumb: 'dthumb' },
      { stage: '.wgal__img', thumb: 'wthumb' },
      { stage: '.pgal__img', thumb: 'pthumb' }
    ];
    var TCLASS = 'dthumb';
    var dstage = null;
    GALLERIES.forEach(function (g) {
      if (dstage) { return; }
      var found = document.querySelector(g.stage);
      if (found) { dstage = found; TCLASS = g.thumb; }
    });
    var dthumbs = Array.prototype.slice.call(document.querySelectorAll('.' + TCLASS));

    if (dthumbs.length && dstage) {
      /* The strip knows about eight frames; the page carries all ten, so the
         arrows can reach the two the strip has no room for. */
      var SHOTS = dthumbs.map(function (t) {
        return { src: t.getAttribute('data-full'), label: t.getAttribute('data-label'),
                 alt: t.getAttribute('data-alt') };
      });
      var shotSrc = byId('shots');
      if (shotSrc) {
        try {
          var full = JSON.parse(shotSrc.textContent);
          if (full.length >= SHOTS.length) { SHOTS = full; }
        } catch (e) { /* the strip's eight remain */ }
      }
      var at = 0;

      function showShot(i) {
        at = ((i % SHOTS.length) + SHOTS.length) % SHOTS.length;
        dstage.src = SHOTS[at].src;
        dstage.alt = SHOTS[at].alt;
        setText('shot-label', SHOTS[at].label);
        setText('shot-n', String(at + 1));
        /* Past the sixth frame nothing in the strip is current — the arrows
           have walked off the end of it, and marking the last tile would be a
           lie about which photograph is showing. */
        dthumbs.forEach(function (o, n) {
          var on = n === at;
          o.classList.toggle(TCLASS + '--on', on);
          if (on) { o.setAttribute('aria-current', 'true'); } else { o.removeAttribute('aria-current'); }
        });
      }

      dthumbs.forEach(function (t, i) {
        t.addEventListener('click', function () { showShot(i); });
      });

      var prev = byId('shot-prev');
      var next = byId('shot-next');
      if (prev) { prev.addEventListener('click', function () { showShot(at - 1); }); }
      if (next) { next.addEventListener('click', function () { showShot(at + 1); }); }
    }

    /* Liking a comment is a local mark, like saving the car: nothing is posted,
       so the count has to come back down. */
    Array.prototype.forEach.call(document.querySelectorAll('[data-like]'), function (b) {
      var out = b.querySelector('.dqa__n, .wq__n, .pq__n');
      if (!out) { return; }
      var base = parseInt(out.textContent, 10) || 0;
      var onClass = b.classList.contains('wq__act') ? 'wq__act--on'
        : b.classList.contains('pq__act') ? 'pq__act--on'
          : 'dqa__like--on';
      b.addEventListener('click', function () {
        var on = b.classList.toggle(onClass);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
        out.textContent = base + (on ? 1 : 0);
      });
    });

    /* The comment form says it is not ready rather than hiding: there has to be
       something to post and a name to post it under. */
    var post = byId('ask-post');
    var draft = byId('ask');
    var who = byId('ask-name');

    if (post && draft && who) {
      var sent = false;

      function askReady() {
        return draft.value.trim().length > 5 && who.value.trim().length > 1;
      }

      var asksQ = post.classList.contains('wq__send') || post.classList.contains('pq__send');
      var postOn = post.classList.contains('wq__send') ? 'wq__send--live'
        : post.classList.contains('pq__send') ? 'pq__send--live'
          : 'dform__post--ready';
      var postWord = asksQ ? 'question' : 'comment';

      function paintPost() {
        var ok = askReady();
        post.classList.toggle(postOn, ok);
        post.setAttribute('aria-disabled', ok ? 'false' : 'true');
        post.childNodes[0].nodeValue = sent
          ? (postWord === 'question' ? 'Question sent' : 'Comment sent')
          : (postWord === 'question' ? 'Post question' : 'Post comment');
      }

      [draft, who].forEach(function (f) {
        f.addEventListener('input', function () { sent = false; paintPost(); });
      });

      post.addEventListener('click', function () {
        if (!askReady()) { return; }
        sent = true;
        draft.value = '';
        paintPost();
      });

      paintPost();
    }

    /* -- the heavy sidebar, v2 only --------------------------------------
       Saving is a local mark, not a request: nothing is posted anywhere, so
       pressing it twice has to leave the car exactly as it was. */
    var save = byId('save-car');
    if (save) {
      save.addEventListener('click', function () {
        /* The pill wears two coats — outlined on v2, accent-filled on v3 — so
           the modifier comes from the element rather than a hard-coded name. */
        var base = save.classList.contains('dprice__save') ? 'dprice__save'
          : save.classList.contains('wpanel__alt') ? 'wpanel__alt'
            : save.classList.contains('pbuy__alt') ? 'pbuy__alt'
              : 'sside__alt';
        var on = save.classList.toggle(base + '--on');
        save.setAttribute('aria-pressed', on ? 'true' : 'false');
        var label = save.childNodes[save.childNodes.length - 1];
        label.nodeValue = base === 'dprice__save'
          ? (on ? 'Saved' : 'Save this car')
          : (on ? 'Saved' : 'Save');
      });
    }

    /* The registration field takes the shape a plate has — upper case, spaces
       left where the driver typed them — rather than validating a format this
       template has no business deciding. */
    var reg = byId('px-reg');
    if (reg) {
      reg.addEventListener('input', function () {
        var at = reg.selectionStart;
        reg.value = reg.value.toUpperCase();
        reg.setSelectionRange(at, at);
      });
    }

    /* -- the question box ------------------------------------------------
       The starters write the question rather than sending it: the buyer still
       has to read what they are about to ask publicly. */
    var ask = byId('ask');

    Array.prototype.forEach.call(document.querySelectorAll('[data-starter]'), function (b) {
      b.addEventListener('click', function () {
        if (!ask) { return; }
        ask.value = b.getAttribute('data-starter');
        ask.focus();
      });
    });

    /* -- the contents rail, v5 only ---------------------------------------
       A contents list that never says where you are is decoration. The rail
       pins at 96px, so the line that decides which section is current is the
       same 96px: whichever section has crossed it and not yet left the
       viewport is the one being read. Nearest-above rather than
       most-intersecting, because a short section that ends above the fold
       would otherwise hand the mark back to the long one behind it. */
    var tocRows = Array.prototype.slice.call(document.querySelectorAll('[data-toc]'));

    if (tocRows.length) {
      var marks = tocRows.map(function (row) {
        return { row: row, section: byId(row.getAttribute('data-toc')) };
      }).filter(function (m) { return m.section; });

      var currentToc = null;

      function paintToc() {
        var line = 97;   /* one pixel past where the rail pins */
        var here = marks[0];

        marks.forEach(function (m) {
          if (m.section.getBoundingClientRect().top <= line) { here = m; }
        });

        /* At the very bottom the last section may never reach the line — the
           page simply runs out — so the foot of the document claims it. */
        if (window.innerHeight + window.scrollY >= document.body.scrollHeight - 2) {
          here = marks[marks.length - 1];
        }

        if (here === currentToc) { return; }
        currentToc = here;
        marks.forEach(function (m) {
          var on = m === here;
          m.row.classList.toggle('ptoc--on', on);
          if (on) { m.row.setAttribute('aria-current', 'true'); } else { m.row.removeAttribute('aria-current'); }
        });
      }

      paintToc();
      window.addEventListener('scroll', paintToc, { passive: true });
      window.addEventListener('resize', paintToc);
    }
  }



  /* ------------------------------------------------------------------ 27 */
  /* The cart.
     Three shop layouts and three product pages can add to it, so it does not
     belong to any of them: the lines live here and in the browser, which is
     what lets a buyer move between pages without the cart emptying itself.

     A line is a part number, a quantity and whether fitting was bought on it.
     Older browsers may still hold the first shape — a bare list of part
     numbers — and those are read as one of each, without fitting. The count
     in the header is the number of lines, the way the cart page counts them.

     Nothing is posted anywhere. The number is a running one and an honest
     one: press Add twice and it comes back down. */

  var CART_STORE = 'avava-cart';
  var CART_HELD = 'avava-cart-held';
  var cart = [];
  var cartSeeded = true;   /* false when the browser had never stored a cart */

  function cartLine(row) {
    if (typeof row === 'string') { return { sku: row, qty: 1, fit: false }; }
    if (!row || typeof row.sku !== 'string') { return null; }
    var qty = parseInt(row.qty, 10);
    return { sku: row.sku, qty: qty > 0 ? qty : 1, fit: row.fit === true };
  }

  function findLine(rows, sku) {
    for (var i = 0; i < rows.length; i++) {
      if (rows[i].sku === sku) { return rows[i]; }
    }
    return null;
  }

  function readCart() {
    try {
      var raw = window.localStorage.getItem(CART_STORE);
      cartSeeded = raw !== null;
      var rows = raw ? JSON.parse(raw) : [];
      if (Object.prototype.toString.call(rows) !== '[object Array]') { return []; }
      var out = [];
      for (var i = 0; i < rows.length; i++) {
        var line = cartLine(rows[i]);
        if (line && !findLine(out, line.sku)) { out.push(line); }
      }
      return out;
    } catch (e) { return []; }
  }

  /* Every change restarts the hold the cart page counts down. */
  function writeCart() {
    try {
      window.localStorage.setItem(CART_STORE, JSON.stringify(cart));
      window.localStorage.setItem(CART_HELD, String(Date.now()));
    } catch (e) {}
  }

  function paintCart() {
    var btn = document.getElementById('cart-btn');
    var out = document.getElementById('cart-n');
    if (!btn || !out) { return; }
    out.textContent = cart.length;
    btn.hidden = cart.length === 0;
    btn.setAttribute('aria-label', cart.length === 1
      ? 'Cart, 1 part' : 'Cart, ' + cart.length + ' parts');
  }

  function inCart(sku) { return !!findLine(cart, sku); }

  function setInCart(sku, on) {
    var line = findLine(cart, sku);
    if (on && !line) { cart.push({ sku: sku, qty: 1, fit: false }); }
    if (!on && line) { cart.splice(cart.indexOf(line), 1); }
    writeCart();
    paintCart();
  }

  function initCart() {
    cart = readCart();
    paintCart();
  }

  /* One arithmetic for every page that prints the basket's sum — the cart,
     the checkout, the confirmation. VAT is inside the prices, so it is read
     out of the total rather than added to it: total × rate / (1 + rate),
     rounded the way the generator rounds. */
  function cartTotals(parts, rates) {
    var sum = 0, fitting = 0, jobs = 0, hours = 0, count = 0;
    cart.forEach(function (l) {
      var p = parts[l.sku];
      if (!p) { return; }
      sum += p.price * l.qty;
      count += l.qty;
      if (l.fit && p.fitting) {
        fitting += p.fitting.price * l.qty;
        jobs += 1;
        hours += p.fitting.hours * l.qty;
      }
    });
    var free = cart.length > 0 && sum + fitting >= rates.freeOver;
    var delivery = cart.length && !free ? rates.deliveryFee : 0;
    var total = sum + fitting + delivery;
    return {
      parts: sum, fitting: fitting, jobs: jobs, hours: hours, free: free,
      delivery: delivery, toFree: Math.max(0, rates.freeOver - sum - fitting),
      vat: Math.floor(total * rates.vat / (1 + rates.vat) + .5), total: total,
      lines: cart.length, items: count
    };
  }

  /* The same words and shapes the generator prints, so a figure reads the
     same before and after a change. */
  var CART_WORDS = ['No', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight',
    'Nine', 'Ten', 'Eleven', 'Twelve'];
  var HOUR_WORDS = { 0.5: 'half an hour', 1: 'one hour', 2: 'two hours', 3: 'three hours', 4: 'four hours' };
  function cartWord(n) { return n < CART_WORDS.length ? CART_WORDS[n] : String(n); }
  function hoursWords(h) { return HOUR_WORDS[h] || String(h) + ' hours'; }
  function euro(n) { return '€' + Math.round(n).toLocaleString('en-GB'); }
  function escapeHtml(s) {
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  /* The size a replacement photograph should be. The server prints it into
     every placeholder it renders and the figure is read once, here, before
     anything is re-rendered — the first render empties the grid, so a lookup
     made later finds nothing to copy and the line comes out blank. */
  var SHOT_SIZE = '';

  function readShotSize(cls, host) {
    /* The grid carries the figure whether or not the first page happens to
       hold a placeholder — and once every card on page one has a photograph,
       it does not. Reading it off a placeholder alone is how the line came out
       blank on every page but the first. */
    if (host && host.getAttribute('data-shot')) {
      SHOT_SIZE = host.getAttribute('data-shot');
      return SHOT_SIZE;
    }
    var seen = document.querySelector('.' + cls);
    if (seen && seen.textContent) { SHOT_SIZE = seen.textContent; }
    return SHOT_SIZE;
  }

  function sizeLine(cls) {
    var span = document.createElement('span');
    span.className = cls;
    span.textContent = SHOT_SIZE;
    return span;
  }

  /* ------------------------------------------------------------------ 24 */
  /* Shop — fitment first.
     The page's premise is that it asks which car you own before it shows you
     anything, so the FITS badge is the one thing on this page that may not be
     decoration: it is the intersection of the part's own fitment list with the
     car currently chosen in the garage. Choose the other car and the badges
     move, because the parts really do differ.

     Everything countable is counted off one catalogue shipped as JSON: the
     five filter pills, the live in-stock figure, the shown-of-total caption,
     the progress bar, what is still to see, and the page rail. */

  function initShop() {
    var grid = document.getElementById('k-grid');
    var src = document.getElementById('parts');
    if (!grid || !src) { return; }

    var ALL = [];
    try { ALL = JSON.parse(src.textContent); } catch (e) { return; }
    if (!ALL.length) { return; }

    /* The card the server rendered is the template, so a card built here and a
       card built by the generator can never drift apart. It is cloned after
       the currency module has wrapped its price, which is why the clone
       already carries a .money span to write into. */
    readShotSize('kcard__phsize', grid);
    var TEMPLATE = grid.querySelector('.kcard').cloneNode(true);
    var PAGE = grid.querySelectorAll('.kcard').length;
    var empty = document.getElementById('k-empty');

    var TESTS = {
      all: function () { return true; },
      stock: function (p) { return p.stock > 0; },
      fitting: function (p) { return p.fitting; },
      genuine: function (p) { return p.genuine; },
      aftermarket: function (p) { return !p.genuine; }
    };

    var ORDER = {
      'price-low': function (a, b) { return a.price - b.price; },
      'price-high': function (a, b) { return b.price - a.price; },
      name: function (a, b) { return a.name.localeCompare(b.name); }
    };

    var state = { car: null, short: '', filter: 'all', cat: null, sort: 'relevance', page: 1, run: PAGE };

    var carButtons = Array.prototype.slice.call(document.querySelectorAll('[data-car]'));
    var chosen = carButtons.filter(function (b) { return b.classList.contains('kcar--on'); })[0]
      || carButtons[0];
    if (chosen) {
      state.car = chosen.getAttribute('data-car');
      state.short = chosen.getAttribute('data-short') || '';
    }

    /* The eight shelf icons are already in the page, on the category tiles, so
       a card without a photograph borrows the one for its shelf rather than
       carrying a second copy of the path data. */
    var SHELF = {};
    Array.prototype.forEach.call(document.querySelectorAll('[data-cat]'), function (tile) {
      var svg = tile.querySelector('.kcat__icon svg');
      if (svg && !SHELF[tile.getAttribute('data-cat')]) {
        SHELF[tile.getAttribute('data-cat')] = svg;
      }
    });

    function view() {
      var test = TESTS[state.filter] || TESTS.all;
      var rows = ALL.filter(function (p) {
        return test(p) && (!state.cat || p.cat === state.cat);
      });
      if (ORDER[state.sort]) { rows = rows.slice().sort(ORDER[state.sort]); }
      return rows;
    }

    function fits(part) {
      return state.car && part.fits.indexOf(state.car) !== -1;
    }

    function fill(node, part, lead) {
      node.classList.toggle('kcard--lead', !!lead);
      node.setAttribute('data-sku', part.sku);
      node.setAttribute('data-cat', part.cat);

      var frame = node.querySelector('.kcard__frame');
      var chip = node.querySelector('.kcard__fits');

      if (fits(part)) {
        if (!chip) {
          chip = TEMPLATE.querySelector('.kcard__fits');
          chip = chip ? chip.cloneNode(true) : null;
          if (chip) { frame.insertBefore(chip, node.querySelector('.kcard__save')); }
        }
        if (chip) {
          var name = chip.querySelector('.kcard__fitname');
          if (name) { name.textContent = state.short.toUpperCase(); }
        }
      } else if (chip) {
        chip.parentNode.removeChild(chip);
      }

      paintFrame(node, part);

      var save = node.querySelector('.kcard__save');
      save.classList.remove('kcard__save--on');
      save.setAttribute('aria-pressed', 'false');
      save.setAttribute('aria-label', 'Save ' + part.brand + ' ' + part.name);

      node.querySelector('.kcard__brand').textContent = part.brand.toUpperCase();
      node.querySelector('.kcard__sku').textContent = part.sku;
      node.querySelector('.kcard__name').textContent = part.name;

      var out = part.stock === 0;
      node.querySelector('.kcard__dot').classList.toggle('kcard__dot--out', out);
      node.querySelector('.kcard__slabel').textContent = out ? 'ON ORDER' : part.stock + ' IN STOCK';

      var price = node.querySelector('.kcard__price');
      var money = price.querySelector('.money');
      if (money) {
        money.setAttribute('data-eur', String(part.price));
        money.textContent = formatMoney(part.price);
      } else {
        price.textContent = formatMoney(part.price);
      }

      var add = node.querySelector('.kcard__add');
      add.disabled = out;
      add.setAttribute('data-add', part.sku);
      /* A part already in the cart says so, or paging away and back would
         quietly forget it. */
      var got = !out && inCart(part.sku);
      add.classList.toggle('kcard__add--in', got);
      add.childNodes[0].nodeValue = out ? 'Notify me' : (got ? 'Added' : 'Add');
      return node;
    }

    function setText(id, value) {
      var n = document.getElementById(id);
      if (n) { n.textContent = value; }
    }

    /* Photograph or shelf icon, in the same frame either way. Both shapes are
       built here rather than cloned from the template, because the template is
       only ever one of the two. */
    function paintFrame(node, part) {
      var frame = node.querySelector('.kcard__frame');
      var old = frame.querySelector('.kcard__img, .kcard__ph');
      var made;
      if (part.photo) {
        made = document.createElement('img');
        made.className = 'kcard__img';
        made.src = part.photo;
        made.width = 800;
        made.height = 600;
        made.alt = part.brand + ' ' + part.name;
        made.loading = 'lazy';
        made.decoding = 'async';
      } else {
        made = document.createElement('span');
        made.className = 'kcard__ph';
        made.setAttribute('aria-hidden', 'true');
        var shelf = SHELF[part.cat];
        if (shelf) { made.appendChild(shelf.cloneNode(true)); }
        var lbl = document.createElement('span');
        lbl.className = 'kcard__phlbl';
        var tile = document.querySelector('[data-cat="' + part.cat + '"] .kcat__name');
        lbl.textContent = tile ? tile.textContent.toUpperCase() : '';
        made.appendChild(lbl);
        made.appendChild(sizeLine('kcard__phsize'));
      }
      if (old) { frame.replaceChild(made, old); } else { frame.insertBefore(made, frame.firstChild); }
    }

    function paintRail(pages) {
      var rail = document.querySelector('.kpage__rail');
      if (!rail) { return; }
      var next = rail.querySelector('.kpage__next');
      Array.prototype.slice.call(rail.children).forEach(function (n) {
        if (n !== next) { rail.removeChild(n); }
      });

      /* An ellipsis is worth a click only when it hides more than one page:
         at six pages "1 2 3 4 … 6" buries the fifth for nothing. */
      var window4 = pages <= 6 ? pages : 4;
      var n;
      for (n = 1; n <= window4; n++) {
        rail.insertBefore(pageButton(n), next);
      }
      if (pages > window4) {
        if (pages > window4 + 1) {
          var gap = document.createElement('span');
          gap.className = 'kpage__gap';
          gap.setAttribute('aria-hidden', 'true');
          gap.textContent = '…';
          rail.insertBefore(gap, next);
        }
        rail.insertBefore(pageButton(pages), next);
      }
      next.disabled = state.page >= pages;
    }

    function pageButton(n) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'kpage__n' + (n === state.page ? ' kpage__n--on' : '');
      if (n === state.page) { b.setAttribute('aria-current', 'page'); }
      b.textContent = String(n);
      b.addEventListener('click', function () {
        state.page = n;
        state.run = PAGE;
        render();
        grid.scrollIntoView({ block: 'start' });
      });
      return b;
    }

    function render() {
      var rows = view();
      var pages = Math.max(1, Math.ceil(rows.length / PAGE));
      if (state.page > pages) { state.page = pages; }

      var from = (state.page - 1) * PAGE;
      var slice = rows.slice(from, from + state.run);

      Array.prototype.slice.call(grid.querySelectorAll('.kcard')).forEach(function (n) {
        grid.removeChild(n);
      });

      slice.forEach(function (part, i) {
        var node = TEMPLATE.cloneNode(true);
        grid.insertBefore(fill(node, part, i === 0), empty);
      });

      if (empty) { empty.hidden = slice.length > 0; }

      /* Every figure in the pagination row is this slice measured against the
         set it came from — none of them is a literal. */
      setText('k-live', String(rows.filter(function (p) { return p.stock > 0; }).length));

      var shownEl = document.querySelector('.kpage__big');
      var ofEl = document.querySelector('.kpage__of');
      var leftEl = document.querySelector('.kpage__left-n');
      var fillEl = document.querySelector('.kpage__fill');
      var moreEl = document.querySelector('.kpage__more');
      var seen = from + slice.length;
      var left = Math.max(0, rows.length - seen);

      if (shownEl) { shownEl.textContent = String(slice.length); }
      if (ofEl) { ofEl.textContent = 'of ' + rows.length + ' shown'; }
      if (leftEl) { leftEl.textContent = String(left); }
      if (fillEl) {
        fillEl.style.width = (rows.length ? (seen / rows.length) * 100 : 0).toFixed(1) + '%';
      }
      if (moreEl) {
        moreEl.childNodes[0].nodeValue = left
          ? 'Show ' + Math.min(PAGE, left) + ' more'
          : 'That is all of them';
        moreEl.setAttribute('aria-disabled', left ? 'false' : 'true');
      }

      paintRail(pages);
    }

    /* -- the garage, and the four selects that say the same thing ---------- */

    function chooseCar(id, short) {
      state.car = id;
      state.short = short || '';
      carButtons.forEach(function (b) {
        var on = b.getAttribute('data-car') === id;
        b.classList.toggle('kcar--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      render();
    }

    carButtons.forEach(function (b) {
      b.addEventListener('click', function () {
        chooseCar(b.getAttribute('data-car'), b.getAttribute('data-short'));
      });
    });

    /* Answering the four questions is the same act as picking a saved car, so
       it lands on the same state: the make and model together identify which
       car in the garage the visitor means. */
    var finds = Array.prototype.slice.call(document.querySelectorAll('[data-find]'));
    if (finds.length) {
      finds.forEach(function (sel) {
        sel.addEventListener('change', function () {
          var make = document.querySelector('[data-find="make"]');
          var model = document.querySelector('[data-find="model"]');
          var hit = carButtons.filter(function (b) {
            var text = b.textContent;
            return make && model
              && text.indexOf(make.value) !== -1
              && text.indexOf(model.value.replace(/\s*\(.*\)$/, '')) !== -1;
          })[0];
          if (hit) {
            chooseCar(hit.getAttribute('data-car'), hit.getAttribute('data-short'));
          } else {
            /* A car the garage does not hold: nothing is claimed to fit it
               rather than the badges staying on the previous car. */
            chooseCar(null, '');
          }
        });
      });
    }

    /* -- filters, shelves and sort ---------------------------------------- */

    var pills = Array.prototype.slice.call(document.querySelectorAll('[data-filter]'));

    pills.forEach(function (b) {
      b.addEventListener('click', function () {
        state.filter = b.getAttribute('data-filter');
        if (state.filter === 'all') { state.cat = null; }
        state.page = 1;
        state.run = PAGE;
        pills.forEach(function (o) {
          var on = o === b;
          o.classList.toggle('kpill--on', on);
          o.setAttribute('aria-pressed', on ? 'true' : 'false');
        });
        render();
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll('.kcat[data-cat]'), function (tile) {
      tile.addEventListener('click', function () {
        state.cat = tile.getAttribute('data-cat');
        state.page = 1;
        state.run = PAGE;
        render();
      });
    });

    var sort = document.getElementById('k-sort');
    if (sort) {
      sort.addEventListener('change', function () {
        state.sort = sort.value;
        state.page = 1;
        state.run = PAGE;
        render();
      });
    }

    var more = document.querySelector('.kpage__more');
    if (more) {
      more.addEventListener('click', function (e) {
        e.preventDefault();
        var rows = view();
        var from = (state.page - 1) * PAGE;
        if (from + state.run >= rows.length) { return; }
        state.run += PAGE;
        render();
      });
    }

    var next = document.querySelector('.kpage__next');
    if (next) {
      next.addEventListener('click', function () {
        var pages = Math.max(1, Math.ceil(view().length / PAGE));
        if (state.page >= pages) { return; }
        state.page += 1;
        state.run = PAGE;
        render();
        grid.scrollIntoView({ block: 'start' });
      });
    }

    /* -- saving and adding, both local marks ------------------------------ */

    grid.addEventListener('click', function (e) {
      var save = e.target.closest ? e.target.closest('.kcard__save') : null;
      if (save) {
        var on = save.classList.toggle('kcard__save--on');
        save.setAttribute('aria-pressed', on ? 'true' : 'false');
        return;
      }
      /* Nothing is posted anywhere and there is no cart page yet, so Add is a
         mark that comes back off — the same promise Save makes. */
      var add = e.target.closest ? e.target.closest('.kcard__add') : null;
      if (add && !add.disabled) {
        var into = add.classList.toggle('kcard__add--in');
        add.childNodes[0].nodeValue = into ? 'Added' : 'Add';
        setInCart(add.getAttribute('data-add'), into);
      }
    });

    /* The prices are money, so they are repainted when the currency is. */
    onCurrencyChange(function () {
      Array.prototype.forEach.call(grid.querySelectorAll('.kcard__price .money'), function (el) {
        var eur = parseFloat(el.getAttribute('data-eur'));
        if (!isNaN(eur)) { el.textContent = formatMoney(eur); }
      });
    });

    /* One render at the start. Without it the page keeps the server's rail,
       whose buttons carry no handlers — the arrows work and the numbers do
       nothing, which is exactly how it shipped. */
    render();
  }


  /* ------------------------------------------------------------------ 25 */
  /* Shop v2 — the filter rail.
     Same catalogue as the shop page, a different shape. Two things make this
     module more than a second copy of initShop:

     the two views are one result set rendered twice, so they are filled from
     the same slice and switching between them never re-runs the filters; and

     every count in the rail is measured inside what fits the chosen car. The
     prototype scopes its facets to the whole catalogue while the head filters
     to the car, so a box reading 142 sits above a result set of 86 — the
     number beside a control has to be the size of what pressing it gives you. */

  function initShopV2() {
    var frame = document.querySelector('.xshop');
    var src = document.getElementById('parts');
    if (!frame || !src) { return; }

    var ALL = [];
    try { ALL = JSON.parse(src.textContent); } catch (e) { return; }
    if (!ALL.length) { return; }

    var gridEl = document.getElementById('x-grid');
    var listEl = document.getElementById('x-list');
    var empty = document.getElementById('x-empty');
    if (!gridEl || !listEl) { return; }

    readShotSize('xph__size', gridEl);
    var GCARD = gridEl.querySelector('.xg').cloneNode(true);
    var LROW = listEl.querySelector('.xl').cloneNode(true);
    var PAGE = gridEl.querySelectorAll('.xg').length;

    var AVAIL = {
      stock: function (p) { return p.stock > 0; },
      fitting: function (p) { return p.fitting; },
      genuine: function (p) { return p.genuine; },
      freeship: function (p) { return p.price >= 250; }
    };

    var ORDER = {
      'price-low': function (a, b) { return a.price - b.price; },
      'price-high': function (a, b) { return b.price - a.price; },
      rating: function (a, b) { return b.stars - a.stars || b.reviews - a.reviews; }
    };

    var bounds = { min: 0, max: 0 };
    var pmin = document.getElementById('x-pmin');
    var pmax = document.getElementById('x-pmax');
    if (pmin && pmax) {
      bounds.min = parseFloat(pmin.min);
      bounds.max = parseFloat(pmax.max);
    }

    var state = {
      car: null, short: '', view: 'grid', cat: null, sort: 'relevance', page: 1,
      avail: [], brands: [], lo: bounds.min, hi: bounds.max
    };

    Array.prototype.forEach.call(document.querySelectorAll('[data-avail]'), function (b) {
      if (b.checked) { state.avail.push(b.getAttribute('data-avail')); }
    });

    var makeSel = document.getElementById('x-make');
    function makeOption() {
      return makeSel ? makeSel.options[makeSel.selectedIndex] : null;
    }
    if (makeSel) {
      state.make = makeSel.value;
      var opt0 = makeOption();
      state.short = (opt0 && opt0.getAttribute('data-short')) || '';
    }

    /* What fits the chosen make — the set every facet in the rail is counted
       inside, and the set the results are drawn from.

       A make the shop cannot answer for hands back the whole shop instead of
       an empty page: a buyer who picks their own make and is shown nothing
       learns that the shop is empty, when what is true is that this shelf was
       not authored for their car. The block says which of the two it is. */
    function fitting() {
      if (!state.make) { state.bare = false; return ALL.slice(); }
      var hit = ALL.filter(function (p) {
        return p.makes && p.makes.indexOf(state.make) !== -1;
      });
      state.bare = hit.length === 0;
      return state.bare ? ALL.slice() : hit;
    }

    function passes(p) {
      if (state.cat && p.cat !== state.cat) { return false; }
      if (p.price < state.lo || p.price > state.hi) { return false; }
      var i;
      for (i = 0; i < state.avail.length; i++) {
        if (!AVAIL[state.avail[i]] || !AVAIL[state.avail[i]](p)) { return false; }
      }
      if (state.brands.length && state.brands.indexOf(p.brand) === -1) { return false; }
      return true;
    }

    function view() {
      var rows = fitting().filter(passes);
      if (ORDER[state.sort]) { rows = rows.slice().sort(ORDER[state.sort]); }
      return rows;
    }

    function money2(v) { return formatMoney(v); }

    function setText(id, value) {
      var n = document.getElementById(id);
      if (n) { n.textContent = value; }
    }

    /* -- filling one card and one row ------------------------------------- */

    function fillPrice(el, part) {
      var m = el.querySelector('.money');
      if (m) {
        m.setAttribute('data-eur', String(part.price));
        m.textContent = money2(part.price);
      } else {
        el.textContent = money2(part.price);
      }
    }

    function fillGrid(node, part, lead) {
      node.classList.toggle('xg--lead', !!lead);
      node.setAttribute('data-sku', part.sku);

      var chip = node.querySelector('.xfits');
      var fits = !state.bare && state.make && part.makes
        && part.makes.indexOf(state.make) !== -1;
      if (fits && !chip) {
        chip = GCARD.querySelector('.xfits');
        if (chip) { node.querySelector('.xg__frame').insertBefore(chip.cloneNode(true), node.querySelector('.xsave')); }
      } else if (!fits && chip) {
        chip.parentNode.removeChild(chip);
      }

      paintFrame(node, part, 'xg');

      var save = node.querySelector('.xsave');
      save.classList.remove('xsave--on');
      save.setAttribute('aria-pressed', 'false');
      save.setAttribute('aria-label', 'Save ' + part.brand + ' ' + part.name);

      node.querySelector('.xg__brand').textContent = part.brand.toUpperCase();
      node.querySelector('.xg__sku').textContent = part.sku;
      node.querySelector('.xg__name').textContent = part.name;

      var out = part.stock === 0;
      node.querySelector('.xdot').classList.toggle('xdot--out', out);
      node.querySelector('.xg__slabel').textContent = out ? 'ON ORDER' : part.stock + ' IN STOCK';
      fillPrice(node.querySelector('.xg__price'), part);

      var add = node.querySelector('.xadd');
      add.disabled = out;
      add.setAttribute('data-add', part.sku);
      var got = !out && inCart(part.sku);
      add.classList.toggle('xadd--in', got);
      add.childNodes[0].nodeValue = got ? 'Added' : 'Add';
      return node;
    }

    function fillList(node, part, lead) {
      node.classList.toggle('xl--lead', !!lead);
      node.setAttribute('data-sku', part.sku);

      var chip = node.querySelector('.xl__fits');
      var fits = !state.bare && state.make && part.makes
        && part.makes.indexOf(state.make) !== -1;
      if (fits) {
        if (!chip) {
          chip = LROW.querySelector('.xl__fits');
          if (chip) { node.querySelector('.xl__frame').appendChild(chip.cloneNode(true)); }
          chip = node.querySelector('.xl__fits');
        }
        var nm = chip && chip.querySelector('.xl__fitname');
        if (nm) { nm.textContent = state.short.toUpperCase(); }
      } else if (chip) {
        chip.parentNode.removeChild(chip);
      }

      paintFrame(node, part, 'xl');

      node.querySelector('.xl__brand').textContent = part.brand.toUpperCase();
      node.querySelector('.xl__cat').innerHTML = part.catLabel;
      node.querySelector('.xl__stars').textContent = part.stars;
      node.querySelector('.xl__reviews').textContent = part.reviews;
      node.querySelector('.xl__name').textContent = part.name;

      var chips = node.querySelector('.xl__chips');
      var all = chips.querySelectorAll('.xchip');
      all[0].textContent = part.sku;

      var out = part.stock === 0;
      var stockChip = all[1];
      stockChip.className = 'xchip xchip--' + (out ? 'out' : 'stock');
      var dot = stockChip.querySelector('.xchip__dot');
      dot.classList.toggle('xchip__dot--out', out);
      stockChip.lastChild.nodeValue = out ? 'ON ORDER' : part.stock + ' IN STOCK';

      var fitChip = chips.querySelector('.xchip--fit');
      if (part.quote) {
        if (!fitChip) {
          fitChip = LROW.querySelector('.xchip--fit');
          if (fitChip) { chips.appendChild(fitChip.cloneNode(true)); fitChip = chips.querySelector('.xchip--fit'); }
        }
        if (fitChip) {
          fitChip.lastChild.nodeValue = 'Fitting ' + part.quote.label + ' · ' + money2(part.quote.price);
        }
      } else if (fitChip) {
        fitChip.parentNode.removeChild(fitChip);
      }

      var right = node.querySelector('.xl__right');
      var was = right.querySelector('.xl__was');
      if (part.was) {
        if (!was) {
          was = document.createElement('span');
          was.className = 'xl__was';
          right.insertBefore(was, right.firstChild);
        }
        was.textContent = money2(part.was);
      } else if (was) {
        was.parentNode.removeChild(was);
      }

      fillPrice(node.querySelector('.xl__price'), part);

      var save = node.querySelector('.xlsave');
      save.classList.remove('xlsave--on');
      save.setAttribute('aria-pressed', 'false');
      save.setAttribute('aria-label', 'Save ' + part.brand + ' ' + part.name);

      var add = node.querySelector('.xladd');
      add.disabled = out;
      add.setAttribute('data-add', part.sku);
      var got2 = !out && inCart(part.sku);
      add.classList.toggle('xladd--in', got2);
      add.childNodes[0].nodeValue = got2 ? 'Added' : 'Add';
      return node;
    }

    /* Photograph or shelf icon, in the same frame either way. Both shapes are
       built here rather than cloned, because a template is only ever one of
       the two — and a photographed part cloned from a placeholder template
       would silently lose its photograph. */
    function paintFrame(node, part, kind) {
      var frame = node.querySelector('.' + kind + '__frame');
      if (!frame) { return; }
      var old = frame.querySelector('.' + kind + '__img, .xph');
      var made;
      if (part.photo) {
        made = document.createElement('img');
        made.className = kind + '__img';
        made.src = part.photo;
        made.width = 800;
        made.height = 600;
        made.alt = part.brand + ' ' + part.name;
        made.loading = 'lazy';
        made.decoding = 'async';
      } else {
        made = document.createElement('span');
        made.className = 'xph';
        made.setAttribute('aria-hidden', 'true');
        var bank = document.querySelector('.xicons [data-icon="' + part.cat + '"] svg');
        if (bank) { made.appendChild(bank.cloneNode(true)); }
        var lbl = document.createElement('span');
        lbl.className = 'xph__lbl';
        lbl.innerHTML = (part.catLabel || '').toUpperCase();
        made.appendChild(lbl);
        made.appendChild(sizeLine('xph__size'));
      }
      if (old) { frame.replaceChild(made, old); } else { frame.insertBefore(made, frame.firstChild); }
    }

    /* -- the facets, counted inside what fits ----------------------------- */

    function paintFacets() {
      var base = fitting();
      setText('x-fitn', base.length + (base.length === 1 ? ' part' : ' parts'));

      Array.prototype.forEach.call(document.querySelectorAll('.xcat'), function (row) {
        var key = row.getAttribute('data-cat');
        var n = base.filter(function (p) { return p.cat === key; }).length;
        var out = row.querySelector('.xcat__n');
        if (out) { out.textContent = n; }
        var on = state.cat === key;
        /* An emptied shelf keeps its row and says nought — pressing it would
           only produce the empty state — but the one already chosen stays
           live, or there would be no way back off it. */
        row.disabled = n === 0 && !on;
        row.classList.toggle('xcat--on', on);
        row.setAttribute('aria-pressed', on ? 'true' : 'false');
      });

      Array.prototype.forEach.call(document.querySelectorAll('[data-avail]'), function (b) {
        var key = b.getAttribute('data-avail');
        var n = base.filter(AVAIL[key] || function () { return false; }).length;
        var out = b.parentNode.querySelector('.xcheck__n');
        if (out) { out.textContent = n; }
      });

      Array.prototype.forEach.call(document.querySelectorAll('[data-brand]'), function (b) {
        var name = b.getAttribute('data-brand');
        var n = base.filter(function (p) { return p.brand === name; }).length;
        var out = b.parentNode.querySelector('.xcheck__n');
        if (out) { out.textContent = n; }
      });

      var on = state.avail.length + state.brands.length
        + (state.cat ? 1 : 0)
        + ((state.lo > bounds.min || state.hi < bounds.max) ? 1 : 0);
      setText('x-filtern', on + (on === 1 ? ' FILTER ON' : ' FILTERS ON'));
    }

    function paintPrice() {
      var lo = Math.min(parseFloat(pmin.value), parseFloat(pmax.value));
      var hi = Math.max(parseFloat(pmin.value), parseFloat(pmax.value));
      state.lo = lo;
      state.hi = hi;
      setText('x-pricelbl', money2(lo) + ' – ' + money2(hi));
      var span = document.querySelector('.xprice__span');
      if (span) {
        var w = bounds.max - bounds.min;
        span.style.left = ((lo - bounds.min) / w * 100) + '%';
        span.style.width = ((hi - lo) / w * 100) + '%';
      }
    }

    /* -- rendering -------------------------------------------------------- */

    function paintRail(pages) {
      var rail = document.querySelector('.xpage__rail');
      if (!rail) { return; }
      var arrows = rail.querySelectorAll('.xpage__arrow');
      var prev = arrows[0];
      var next = arrows[arrows.length - 1];
      Array.prototype.slice.call(rail.children).forEach(function (n) {
        if (n !== prev && n !== next) { rail.removeChild(n); }
      });

      /* Same rule as the shelf pager: hide pages only when hiding earns
         its ellipsis. */
      var win = pages <= 6 ? pages : 4;
      var n;
      for (n = 1; n <= win; n++) { rail.insertBefore(pageButton(n), next); }
      if (pages > win) {
        if (pages > win + 1) {
          var gap = document.createElement('span');
          gap.className = 'xpage__gap';
          gap.setAttribute('aria-hidden', 'true');
          gap.textContent = '…';
          rail.insertBefore(gap, next);
        }
        rail.insertBefore(pageButton(pages), next);
      }
      prev.disabled = state.page <= 1;
      next.disabled = state.page >= pages;
    }

    function pageButton(n) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'xpage__n' + (n === state.page ? ' xpage__n--on' : '');
      if (n === state.page) { b.setAttribute('aria-current', 'page'); }
      b.textContent = String(n);
      b.addEventListener('click', function () { goto(n); });
      return b;
    }

    function goto(n) {
      state.page = n;
      render();
      frame.scrollIntoView({ block: 'start' });
    }

    function render() {
      var rows = view();
      var pages = Math.max(1, Math.ceil(rows.length / PAGE));
      if (state.page > pages) { state.page = pages; }
      var from = (state.page - 1) * PAGE;
      var slice = rows.slice(from, from + PAGE);

      Array.prototype.slice.call(gridEl.querySelectorAll('.xg')).forEach(function (n) { gridEl.removeChild(n); });
      Array.prototype.slice.call(listEl.querySelectorAll('.xl')).forEach(function (n) { listEl.removeChild(n); });

      slice.forEach(function (part, i) {
        gridEl.appendChild(fillGrid(GCARD.cloneNode(true), part, i === 0));
        listEl.appendChild(fillList(LROW.cloneNode(true), part, i === 0));
      });

      var none = slice.length === 0;
      if (empty) { empty.hidden = !none; }
      gridEl.hidden = none || state.view !== 'grid';
      listEl.hidden = none || state.view !== 'list';

      setText('x-count', rows.length + (rows.length === 1 ? ' part' : ' parts'));

      /* The sub-line says what produced the number above it, so it changes
         when the filters do rather than always claiming a plain fitment. */
      var extra = state.avail.length + state.brands.length + (state.cat ? 1 : 0)
        + ((state.lo > bounds.min || state.hi < bounds.max) ? 1 : 0);
      var base = fitting().length;
      /* With a make chosen the line has two figures to reconcile — what fits
         it, and what the shop holds. Without one there is only the shop, and
         naming it twice reads as a stutter. */
      if (state.make && !state.bare) {
        setText('x-sub', (extra ? 'of ' + base + ' that fit your ' + state.short
          : 'that fit your ' + state.short) + ' · ' + ALL.length + ' in the shop');
      } else {
        setText('x-sub', (extra ? 'of ' : '') + ALL.length + ' in the shop');
      }

      setText('x-shown', String(slice.length));
      setText('x-of', 'of ' + rows.length + ' shown');
      setText('x-left', String(Math.max(0, rows.length - (from + slice.length))));

      var fill = document.querySelector('.xpage__fill');
      if (fill) {
        fill.style.width = (rows.length ? ((from + slice.length) / rows.length) * 100 : 0).toFixed(1) + '%';
      }

      paintFacets();
      paintRail(pages);
    }

    /* -- the controls ------------------------------------------------------ */

    function showView(which) {
      state.view = which;
      var g = document.getElementById('x-view-grid');
      var l = document.getElementById('x-view-list');
      [[g, 'grid'], [l, 'list']].forEach(function (pair) {
        if (!pair[0]) { return; }
        var on = pair[1] === which;
        pair[0].classList.toggle('xtoggle__btn--on', on);
        pair[0].setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      var none = !gridEl.querySelector('.xg');
      gridEl.hidden = none || which !== 'grid';
      listEl.hidden = none || which !== 'list';
    }

    var gBtn = document.getElementById('x-view-grid');
    var lBtn = document.getElementById('x-view-list');
    if (gBtn) { gBtn.addEventListener('click', function () { showView('grid'); }); }
    if (lBtn) { lBtn.addEventListener('click', function () { showView('list'); }); }

    if (makeSel) {
      makeSel.addEventListener('change', function () {
        state.make = makeSel.value;
        state.page = 1;
        var opt = makeOption();
        state.short = (opt && opt.getAttribute('data-short')) || '';
        /* fitting() decides whether this make has anything behind it, so it
           runs before the block is written. The count itself is paintFacets'
           to write, once, on the render below. */
        fitting();
        setText('x-carname', (opt && opt.getAttribute('data-name')) || '');
        var spec = document.getElementById('x-carspec');
        if (spec) {
          spec.innerHTML = state.bare
            ? 'NOTHING CONFIRMED FOR THIS MAKE'
            : ((opt && opt.getAttribute('data-spec')) || '');
        }
        setText('x-fittail', state.bare
          ? 'in the shop, none of them confirmed for it'
          : ((opt && opt.getAttribute('data-tail')) || ''));
        render();
      });
    }

    Array.prototype.forEach.call(document.querySelectorAll('.xcat'), function (row) {
      row.addEventListener('click', function () {
        var key = row.getAttribute('data-cat');
        state.cat = state.cat === key ? null : key;
        state.page = 1;
        render();
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll('[data-avail]'), function (b) {
      b.addEventListener('change', function () {
        var key = b.getAttribute('data-avail');
        var at = state.avail.indexOf(key);
        if (b.checked && at === -1) { state.avail.push(key); }
        if (!b.checked && at !== -1) { state.avail.splice(at, 1); }
        state.page = 1;
        render();
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll('[data-brand]'), function (b) {
      b.addEventListener('change', function () {
        var name = b.getAttribute('data-brand');
        var at = state.brands.indexOf(name);
        if (b.checked && at === -1) { state.brands.push(name); }
        if (!b.checked && at !== -1) { state.brands.splice(at, 1); }
        state.page = 1;
        render();
      });
    });

    if (pmin && pmax) {
      [pmin, pmax].forEach(function (r) {
        r.addEventListener('input', function () {
          paintPrice();
          state.page = 1;
          render();
        });
      });
    }

    var sort = document.getElementById('x-sort');
    if (sort) {
      sort.addEventListener('change', function () {
        state.sort = sort.value;
        state.page = 1;
        render();
      });
    }

    var reset = document.getElementById('x-reset');
    if (reset) {
      reset.addEventListener('click', function () {
        state.cat = null;
        state.avail = [];
        state.brands = [];
        state.lo = bounds.min;
        state.hi = bounds.max;
        state.page = 1;
        Array.prototype.forEach.call(document.querySelectorAll('[data-avail], [data-brand]'), function (b) {
          b.checked = false;
        });
        if (pmin && pmax) { pmin.value = bounds.min; pmax.value = bounds.max; paintPrice(); }
        render();
      });
    }

    /* The two arrows are the only controls the rail keeps between renders, so
       they are wired once, here — paintRail rebuilds everything between them. */
    var arrows = document.querySelectorAll('.xpage__arrow');
    if (arrows.length === 2) {
      arrows[0].addEventListener('click', function () {
        if (state.page <= 1) { return; }
        state.page -= 1;
        render();
        frame.scrollIntoView({ block: 'start' });
      });
      arrows[1].addEventListener('click', function () {
        var pages = Math.max(1, Math.ceil(view().length / PAGE));
        if (state.page >= pages) { return; }
        state.page += 1;
        render();
        frame.scrollIntoView({ block: 'start' });
      });
    }

    /* Saving and adding are local marks that come back off — nothing is posted
       and there is no cart page yet. */
    frame.addEventListener('click', function (e) {
      if (!e.target.closest) { return; }
      var save = e.target.closest('.xsave, .xlsave');
      if (save) {
        var cls = save.classList.contains('xsave') ? 'xsave--on' : 'xlsave--on';
        var on = save.classList.toggle(cls);
        save.setAttribute('aria-pressed', on ? 'true' : 'false');
        /* One part, two renderings: marking it in either has to mark it in the
           other, or switching view loses the mark. */
        var row = save.closest('.xg, .xl');
        if (row) { syncMark(row.getAttribute('data-sku'), 'save', on); }
        return;
      }
      var add = e.target.closest('.xadd, .xladd');
      if (add && !add.disabled) {
        var base = add.classList.contains('xadd') ? 'xadd--in' : 'xladd--in';
        var into = add.classList.toggle(base);
        add.childNodes[0].nodeValue = into ? 'Added' : 'Add';
        var host = add.closest('.xg, .xl');
        if (host) { syncMark(host.getAttribute('data-sku'), 'add', into); }
        setInCart(add.getAttribute('data-add'), into);
      }
    });

    function syncMark(sku, kind, on) {
      if (!sku) { return; }
      Array.prototype.forEach.call(frame.querySelectorAll('[data-sku="' + sku + '"]'), function (row) {
        if (kind === 'save') {
          var s = row.querySelector('.xsave, .xlsave');
          if (!s) { return; }
          s.classList.toggle(s.classList.contains('xsave') ? 'xsave--on' : 'xlsave--on', on);
          s.setAttribute('aria-pressed', on ? 'true' : 'false');
        } else {
          var a = row.querySelector('.xadd, .xladd');
          if (!a || a.disabled) { return; }
          a.classList.toggle(a.classList.contains('xadd') ? 'xadd--in' : 'xladd--in', on);
          a.childNodes[0].nodeValue = on ? 'Added' : 'Add';
        }
      });
    }

    onCurrencyChange(function () {
      Array.prototype.forEach.call(frame.querySelectorAll('.money'), function (el) {
        var eur = parseFloat(el.getAttribute('data-eur'));
        if (!isNaN(eur)) { el.textContent = formatMoney(eur); }
      });
    });

    /* One render at the start, or the rail keeps the server's buttons and the
       page numbers do nothing. */
    render();
  }


  /* ------------------------------------------------------------------ 26 */
  /* Shop v3 — the plain one.
     A search field, category chips, one line about what the car is due, and a
     grid. The diagnostic is a hint inside a familiar shop, so the only thing
     it owns here is that line and a badge on the two cards it names.

     "Only what fits" is the switch the whole page turns on: with it off the
     head, the chips and the pagination all widen to the shop's own totals, so
     none of those numbers can be a literal. */

  function initShopV3() {
    var grid = document.getElementById('z-list');
    var src = document.getElementById('parts');
    if (!grid || !src) { return; }

    var ALL = [];
    try { ALL = JSON.parse(src.textContent); } catch (e) { return; }
    if (!ALL.length) { return; }

    readShotSize('zph__size', grid);
    var TEMPLATE = grid.querySelector('.zcard').cloneNode(true);
    var PAGE = grid.querySelectorAll('.zcard').length;
    var empty = document.getElementById('z-empty');
    var fitOnly = document.getElementById('z-fitonly');
    var search = document.getElementById('z-search');
    var sort = document.getElementById('z-sort');

    var CAR = null;
    var badged = ALL.filter(function (p) { return p.due; });
    if (badged.length && badged[0].fits.length) { CAR = badged[0].fits[badged[0].fits.length - 1]; }

    var ORDER = {
      'price-low': function (a, b) { return a.price - b.price; },
      'price-high': function (a, b) { return b.price - a.price; }
    };

    var state = { cat: null, fits: !!(fitOnly && fitOnly.checked), q: '', sort: 'relevance', page: 1 };

    /* One shelf at a time over whatever survives the filters, so the first
       page keeps its spread instead of filling with whichever shelf happens to
       be deepest. */
    function deal(rows) {
      var shelves = {};
      var order = [];
      rows.forEach(function (p) {
        if (!shelves[p.cat]) { shelves[p.cat] = []; order.push(p.cat); }
        shelves[p.cat].push(p);
      });
      var out = [];
      var depth = 0;
      while (out.length < rows.length) {
        order.forEach(function (k) {
          if (depth < shelves[k].length) { out.push(shelves[k][depth]); }
        });
        depth += 1;
      }
      return out;
    }

    function view() {
      var q = state.q.trim().toLowerCase();
      var rows = ALL.filter(function (p) {
        if (state.fits && CAR && p.fits.indexOf(CAR) === -1) { return false; }
        if (state.cat && p.cat !== state.cat) { return false; }
        if (q) {
          var hay = (p.name + ' ' + p.brand + ' ' + p.sku + ' ' + p.catLabel).toLowerCase();
          if (hay.indexOf(q) === -1) { return false; }
        }
        return true;
      });
      if (ORDER[state.sort]) { return rows.slice().sort(ORDER[state.sort]); }
      /* Recommended: what the car is due first, then a shelf at a time. */
      var due = rows.filter(function (p) { return p.due; });
      var rest = rows.filter(function (p) { return !p.due; });
      return due.concat(deal(rest));
    }

    function setText(id, value) {
      var n = document.getElementById(id);
      if (n) { n.textContent = value; }
    }

    /* Photograph or shelf icon, in the same frame either way — the template is
       only ever one of the two, so both shapes are built rather than cloned. */
    function paintFrame(node, part) {
      var frame = node.querySelector('.zcard__frame');
      var old = frame.querySelector('.zcard__img, .zph');
      var made;
      if (part.photo) {
        made = document.createElement('img');
        made.className = 'zcard__img';
        made.src = part.photo;
        made.width = 800;
        made.height = 600;
        made.alt = part.brand + ' ' + part.name;
        made.loading = 'lazy';
        made.decoding = 'async';
      } else {
        made = document.createElement('span');
        made.className = 'zph';
        made.setAttribute('aria-hidden', 'true');
        var bank = document.querySelector('.zchip[data-cat="' + part.cat + '"] svg');
        if (bank) { made.appendChild(bank.cloneNode(true)); }
        var lbl = document.createElement('span');
        lbl.className = 'zph__lbl';
        lbl.innerHTML = (part.catLabel || '').toUpperCase();
        made.appendChild(lbl);
        made.appendChild(sizeLine('zph__size'));
      }
      if (old) { frame.replaceChild(made, old); } else { frame.insertBefore(made, frame.firstChild); }
    }

    function fill(node, part) {
      node.classList.toggle('zcard--due', !!part.due);
      node.setAttribute('data-sku', part.sku);

      var frame = node.querySelector('.zcard__frame');
      var badge = node.querySelector('.zbadge');
      if (part.due && !badge) {
        badge = TEMPLATE.querySelector('.zbadge');
        if (badge) { frame.insertBefore(badge.cloneNode(true), node.querySelector('.zsave')); }
      } else if (!part.due && badge) {
        badge.parentNode.removeChild(badge);
      }

      paintFrame(node, part);

      var save = node.querySelector('.zsave');
      save.classList.remove('zsave--on');
      save.setAttribute('aria-pressed', 'false');
      save.setAttribute('aria-label', 'Save ' + part.brand + ' ' + part.name);

      node.querySelector('.zcard__brand').textContent = part.brand.toUpperCase();
      node.querySelector('.zcard__sku').textContent = part.sku;
      node.querySelector('.zcard__name').textContent = part.name;

      var out = part.stock === 0;
      node.querySelector('.zdot').classList.toggle('zdot--out', out);
      node.querySelector('.zcard__slabel').textContent = out ? 'on order' : part.stock + ' in stock';

      var price = node.querySelector('.zcard__price');
      var m = price.querySelector('.money');
      if (m) {
        m.setAttribute('data-eur', String(part.price));
        m.textContent = formatMoney(part.price);
      } else {
        price.textContent = formatMoney(part.price);
      }

      var add = node.querySelector('.zadd');
      add.disabled = out;
      add.setAttribute('data-add', part.sku);
      var got = !out && inCart(part.sku);
      add.classList.toggle('zadd--in', got);
      add.childNodes[0].nodeValue = got ? 'Added' : 'Add';
      return node;
    }

    function paintChips(base) {
      Array.prototype.forEach.call(document.querySelectorAll('.zchip'), function (chip) {
        var key = chip.getAttribute('data-cat');
        var n = key
          ? base.filter(function (p) { return p.cat === key; }).length
          : base.length;
        var out = chip.querySelector('.zchip__n');
        if (out) { out.textContent = n; }
        chip.disabled = !!key && n === 0;
        var on = (state.cat || '') === key;
        chip.classList.toggle('zchip--on', on);
        chip.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
    }

    function paintRail(pages) {
      var rail = document.querySelector('.zpage__rail');
      if (!rail) { return; }
      var arrows = rail.querySelectorAll('.zpage__arrow');
      var prev = arrows[0];
      var next = arrows[arrows.length - 1];
      Array.prototype.slice.call(rail.children).forEach(function (n) {
        if (n !== prev && n !== next) { rail.removeChild(n); }
      });
      /* Same rule as the shelf pager: hide pages only when hiding earns
         its ellipsis. */
      var win = pages <= 6 ? pages : 4;
      var n;
      for (n = 1; n <= win; n++) { rail.insertBefore(pageButton(n), next); }
      if (pages > win) {
        if (pages > win + 1) {
          var gap = document.createElement('span');
          gap.className = 'zpage__gap';
          gap.setAttribute('aria-hidden', 'true');
          gap.textContent = '…';
          rail.insertBefore(gap, next);
        }
        rail.insertBefore(pageButton(pages), next);
      }
      prev.disabled = state.page <= 1;
      next.disabled = state.page >= pages;
    }

    function pageButton(n) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'zpage__n' + (n === state.page ? ' zpage__n--on' : '');
      if (n === state.page) { b.setAttribute('aria-current', 'page'); }
      b.textContent = String(n);
      b.addEventListener('click', function () {
        state.page = n;
        render();
        grid.scrollIntoView({ block: 'start' });
      });
      return b;
    }

    function render() {
      var rows = view();
      var pages = Math.max(1, Math.ceil(rows.length / PAGE));
      if (state.page > pages) { state.page = pages; }
      var from = (state.page - 1) * PAGE;
      var slice = rows.slice(from, from + PAGE);

      Array.prototype.slice.call(grid.querySelectorAll('.zcard')).forEach(function (n) {
        grid.removeChild(n);
      });
      slice.forEach(function (part) { grid.appendChild(fill(TEMPLATE.cloneNode(true), part)); });

      var none = slice.length === 0;
      grid.hidden = none;
      if (empty) { empty.hidden = !none; }

      /* The chips count inside whatever the fit switch is showing, so the
         number on a chip is the size of what pressing it gives you. */
      var base = ALL.filter(function (p) {
        return !(state.fits && CAR && p.fits.indexOf(CAR) === -1);
      });
      paintChips(base);

      /* The head answers "how much of this shop is relevant to me", so it
         stays put while a chip narrows the grid — the pagination line below is
         what says how many are on screen. Both modes are car-or-shop wide;
         mixing one filter-scoped mode in would make the number jump for no
         reason a reader could name. */
      setText('z-count', state.fits
        ? base.length + ' parts fit your car'
        : ALL.length + ' parts');
      setText('z-sub', state.fits
        ? 'of ' + ALL.length + ' in the shop'
        : 'in the whole shop');
      setText('z-pagelbl', 'SHOWING ' + slice.length + ' OF ' + rows.length
        + (state.fits ? ' THAT FIT YOUR CAR' : ' IN THE SHOP'));

      paintRail(pages);
    }

    /* -- the controls ------------------------------------------------------ */

    Array.prototype.forEach.call(document.querySelectorAll('.zchip'), function (chip) {
      chip.addEventListener('click', function () {
        if (chip.disabled) { return; }
        var key = chip.getAttribute('data-cat') || null;
        state.cat = (state.cat === key) ? null : key;
        state.page = 1;
        render();
      });
    });

    if (fitOnly) {
      fitOnly.addEventListener('change', function () {
        state.fits = fitOnly.checked;
        state.page = 1;
        render();
      });
    }

    var showAll = document.getElementById('z-showall');
    if (showAll) {
      showAll.addEventListener('click', function () {
        if (fitOnly) { fitOnly.checked = false; }
        state.fits = false;
        state.page = 1;
        render();
      });
    }

    if (search) {
      search.addEventListener('input', function () {
        state.q = search.value;
        state.page = 1;
        render();
      });
    }

    if (sort) {
      sort.addEventListener('change', function () {
        state.sort = sort.value;
        state.page = 1;
        render();
      });
    }

    var prevBtn = document.querySelector('.zpage__arrow');
    var arrows = document.querySelectorAll('.zpage__arrow');
    if (arrows.length === 2) {
      arrows[0].addEventListener('click', function () {
        if (state.page <= 1) { return; }
        state.page -= 1;
        render();
        grid.scrollIntoView({ block: 'start' });
      });
      arrows[1].addEventListener('click', function () {
        var pages = Math.max(1, Math.ceil(view().length / PAGE));
        if (state.page >= pages) { return; }
        state.page += 1;
        render();
        grid.scrollIntoView({ block: 'start' });
      });
    }

    /* Saving and adding are local marks that come back off. */
    grid.addEventListener('click', function (e) {
      if (!e.target.closest) { return; }
      var save = e.target.closest('.zsave');
      if (save) {
        var on = save.classList.toggle('zsave--on');
        save.setAttribute('aria-pressed', on ? 'true' : 'false');
        return;
      }
      var add = e.target.closest('.zadd');
      if (add && !add.disabled) {
        var into = add.classList.toggle('zadd--in');
        add.childNodes[0].nodeValue = into ? 'Added' : 'Add';
        setInCart(add.getAttribute('data-add'), into);
      }
    });

    onCurrencyChange(function () {
      Array.prototype.forEach.call(grid.querySelectorAll('.money'), function (el) {
        var eur = parseFloat(el.getAttribute('data-eur'));
        if (!isNaN(eur)) { el.textContent = formatMoney(eur); }
      });
    });

    /* One render at the start, or the rail keeps the server's buttons and the
       page numbers do nothing. */
    render();
  }

  function init() {
    initPreloader();
    syncUnits();
    initNav();
    initToggleGroup(document.querySelector('.search__quick'), '.qp');
    initToggleGroup(document.getElementById('slots'), '.slot', function (btn) {
      var cta = document.getElementById('book-cta');
      if (!cta) { return; }
      booking.city = btn.querySelector('.slot__city').textContent;
      booking.time = btn.querySelector('.slot__time').textContent;
      cta.childNodes[0].nodeValue = 'Book ' + booking.time + ' in ' + booking.city;
    });
    initFinance();
    initTrade();
    initMarquee();
    initMap();
    initBooking();
    initAuth();
    initSteps();
    initFindPanel();
    initFeatured();
    initWorkshopMap();
    initServiceBooking();
    initPlans();
    initCompare();
    initFaq();
    initContact();
    initTerms();
    initListing();
    initSingle();
    initCurrency();
    initCart();
    initShop();
    initShopV2();
    initShopV3();
    initShopSingle();
    initShopSingleV2();
    initShopSingleV3();
    initCartPage();
    initCheckout();
    initOrderDone();
    initBlog();
    initBlogPost();
    initProfile();
    initSettings();
    initSaved();
    initBookings();
    initOrders();
    initMyListings();
    initMessages();
    initSell();
    initDealer();
    initAnalytics();
    initDealerListings();
    initEnquiries();
    initDealerBookings();
    initDealerOrders();
    initPayouts();
    initInvoices();
    initReviews();
    initTeam();
    initDealerProfile();
    initDealerSettings();
    initAddListing();
    initPhotoDrops();
    initToggleGroup(document.querySelector('.slotrail'), '.slot-pill');
    initToggleGroup(document.querySelector('.needchips'), '.needchip');

    window.addEventListener('resize', syncUnits);
    window.addEventListener('orientationchange', syncUnits);
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(syncUnits);
    }
  }

  /* ------------------------------------------------------------------ 28 */
  /* Shop single v1. The product page for one part, and the one page in the
     shop family where the two sizes are different products rather than a
     filter: choosing one has to move the price, the shelf count, the part
     number and the fitment line together, or the panel starts describing a
     part the buyer is not buying.

     The running total is the other thing that has to hold. The panel states a
     price per tyre, a price for four and a fitting charge; all three come off
     one number here, so they cannot drift the way they had in the prototype. */
  function initShopSingle() {
    var panel = document.querySelector('.ybuy');
    if (!panel) { return; }

    var raw = document.getElementById('variants');
    var VARIANTS = [];
    try { VARIANTS = JSON.parse(raw ? raw.textContent : '[]'); } catch (e) { VARIANTS = []; }
    if (!VARIANTS.length) { return; }

    var opts = Array.prototype.slice.call(document.querySelectorAll('.ysize__opt'));
    var qtyOut = document.getElementById('y-qty');
    var fitBox = document.getElementById('y-fitting');
    var addBtn = document.querySelector('.yadd');
    var WORDS = ['no', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight'];

    var state = { at: 0, qty: parseInt(qtyOut ? qtyOut.textContent : '2', 10) || 1 };

    function current() { return VARIANTS[state.at]; }

    function euro(n) {
      return '\u20ac' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function setText(id, text) {
      var el = document.getElementById(id);
      if (el) { el.textContent = text; }
    }

    /* One number in, four out — price, the piece-by-piece chip, what the bay
       charges for this many, and the sentence under the toggle.

       The hero is not one of them: the gallery owns that picture and the
       thumbnails switch it. Repainting it from the variant's catalogue shot
       is how the page came to open on a photograph nobody chose. */
    function paint() {
      var v = current();
      setText('y-price', euro(v.price));
      setText('y-four', euro(v.four) + ' FOR FOUR');
      setText('y-stock', String(v.stock));
      setText('y-sku', v.sku);
      setText('y-fitprice', euro(v.fit * state.qty));
      setText('y-qty', String(state.qty));

      var sub = document.getElementById('y-fitsub');
      if (sub) {
        var word = WORDS[state.qty] || String(state.qty);
        sub.textContent = word + (state.qty === 1 ? ' tyre, half an hour' : ' tyres, one hour')
          + ', Munich workshop';
      }

      var why = document.querySelector('.yfits__why');
      if (why) { why.innerHTML = v.why; }

      opts.forEach(function (b, i) {
        var on = i === state.at;
        b.classList.toggle('ysize__opt--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });

      if (addBtn) { addBtn.setAttribute('data-add', v.sku); }
    }

    opts.forEach(function (b, i) {
      b.addEventListener('click', function () {
        state.at = i;
        if (state.qty > VARIANTS[i].stock) { state.qty = VARIANTS[i].stock; }
        paint();
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll('.yqty__btn'), function (b) {
      b.addEventListener('click', function () {
        /* Never past the shelf: the panel says how many are in Munich two
           lines above the stepper. */
        var step = b.getAttribute('aria-label') === 'More' ? 1 : -1;
        var top = Math.max(1, current().stock);
        state.qty = Math.min(top, Math.max(1, state.qty + step));
        paint();
      });
    });

    if (fitBox) {
      fitBox.addEventListener('change', function () {
        /* On, the label confirms; off, it invites. The prototype is static and
           said "Add fitting" with the switch already on, which reads as though
           the charge had not been counted when it had. */
        var lbl = document.querySelector('.yfit__lbl');
        if (lbl) { lbl.firstChild.nodeValue = fitBox.checked ? 'Fitting added \u2014 ' : 'Add fitting \u2014 '; }
      });
    }

    if (addBtn) {
      addBtn.addEventListener('click', function () {
        var into = !inCart(addBtn.getAttribute('data-add'));
        setInCart(addBtn.getAttribute('data-add'), into);
        addBtn.childNodes[0].nodeValue = into ? 'Added to basket' : 'Add to basket';
      });
    }

    /* The gallery only has a rail when there is more than one angle. */
    var thumbs = Array.prototype.slice.call(document.querySelectorAll('.ythumb'));
    thumbs.forEach(function (b, i) {
      b.addEventListener('click', function () {
        var img = b.querySelector('.ythumb__img');
        var hero = document.querySelector('.yhero__img');
        if (img && hero) { hero.src = img.src; }
        thumbs.forEach(function (o) { o.classList.toggle('ythumb--on', o === b); });
        var count = document.querySelector('.yhero__count');
        if (count) { count.textContent = (i + 1) + ' / ' + thumbs.length; }
      });
    });

    paint();
  }

  /* ------------------------------------------------------------------ 29 */
  /* Shop single v2. The itemised panel, and the reason this layout exists:
     it does the arithmetic instead of leaving it to the buyer.

     Which is also its risk. The panel prints five figures — the tyre line,
     the fitting line, the delivery line, the total and the plus on the
     toggle — and any of them can contradict the others if they are written
     separately. They are one derivation here, run on every change. */
  function initShopSingleV2() {
    var panel = document.querySelector('.jbuy');
    if (!panel) { return; }

    var raw = document.getElementById('variants');
    var VARIANTS = [];
    try { VARIANTS = JSON.parse(raw ? raw.textContent : '[]'); } catch (e) { VARIANTS = []; }
    if (!VARIANTS.length) { return; }

    var FREE_OVER = 250;
    var opts = Array.prototype.slice.call(document.querySelectorAll('.jsize'));
    var fitBox = document.getElementById('j-fitting');
    var addBtn = document.querySelector('.jadd');
    var WORDS = ['no', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight'];

    var state = { at: 0, qty: parseInt((document.getElementById('j-qty') || {}).textContent, 10) || 1 };

    function current() { return VARIANTS[state.at]; }

    function euro(n) {
      return '\u20ac' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function setText(id, text) {
      var el = document.getElementById(id);
      if (el) { el.textContent = text; }
    }

    /* One order, read five ways. */
    function order() {
      var v = current();
      var goods = v.price * state.qty;
      var fit = (fitBox && fitBox.checked) ? v.fit * state.qty : 0;
      return { v: v, goods: goods, fit: fit, free: goods + fit >= FREE_OVER, total: goods + fit };
    }

    function paint() {
      var o = order();
      var v = o.v;

      setText('j-stock', String(v.stock));
      setText('j-avail', String(v.stock));
      setText('j-sku', v.sku);
      setText('j-qty', String(state.qty));
      setText('j-total', euro(o.total));
      setText('j-plus', '+' + euro(o.fit));

      var rows = document.querySelectorAll('[data-sum]');
      if (rows[0]) {
        rows[0].parentNode.querySelector('.jsum__k').textContent =
          state.qty + ' \u00d7 GT brake kit, ' + v.short;
        rows[0].textContent = euro(o.goods);
      }
      if (rows[1]) { rows[1].textContent = euro(o.fit); }
      if (rows[2]) {
        rows[2].textContent = o.free ? 'Free' : euro(0);
        rows[2].classList.toggle('jsum__v--free', o.free);
      }

      var say = document.getElementById('j-say');
      if (say) {
        var word = WORDS[state.qty] || String(state.qty);
        say.textContent = state.qty === 2
          ? 'A pair for the ' + v.axle.toLowerCase() + ' axle.'
          : word + (state.qty === 1 ? ' tyre' : ' tyres') + ' for the ' + v.axle.toLowerCase() + ' axle.';
      }

      /* The fitment table restates the axle, so it moves with the size. */
      var axleCell = document.querySelector('[data-fit="2"]');
      if (axleCell) { axleCell.textContent = v.axle; }

      opts.forEach(function (b, i) {
        var on = i === state.at;
        b.classList.toggle('jsize--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });

      /* Never past the shelf — the panel says how many there are two lines up. */
      var steps = document.querySelectorAll('.jstepper__btn');
      if (steps[0]) { steps[0].disabled = state.qty <= 1; }
      if (steps[1]) { steps[1].disabled = state.qty >= v.stock; }

      if (addBtn) { addBtn.setAttribute('data-add', v.sku); }
    }

    opts.forEach(function (b, i) {
      b.addEventListener('click', function () {
        state.at = i;
        if (state.qty > VARIANTS[i].stock) { state.qty = VARIANTS[i].stock; }
        paint();
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll('.jstepper__btn'), function (b) {
      b.addEventListener('click', function () {
        var step = b.getAttribute('aria-label') === 'More' ? 1 : -1;
        state.qty = Math.min(Math.max(1, current().stock), Math.max(1, state.qty + step));
        paint();
      });
    });

    if (fitBox) { fitBox.addEventListener('change', paint); }

    if (addBtn) {
      addBtn.addEventListener('click', function () {
        var into = !inCart(addBtn.getAttribute('data-add'));
        setInCart(addBtn.getAttribute('data-add'), into);
        addBtn.childNodes[0].nodeValue = into ? 'Added to basket' : 'Add to basket';
      });
    }

    var thumbs = Array.prototype.slice.call(document.querySelectorAll('.jthumb'));
    thumbs.forEach(function (b, i) {
      b.addEventListener('click', function () {
        var img = b.querySelector('.jthumb__img');
        var hero = document.querySelector('.jhero__img');
        if (img && hero) { hero.src = img.src; }
        thumbs.forEach(function (o) { o.classList.toggle('jthumb--on', o === b); });
        var count = document.querySelector('.jhero__count');
        if (count) { count.textContent = (i + 1) + ' / ' + thumbs.length; }
      });
    });

    /* The filters are a group of one: pressing one releases the others. The
       critical filter is not allowed to hide itself — it is the one that makes
       the rest of the comments worth reading. */
    var filters = Array.prototype.slice.call(document.querySelectorAll('.jfilter'));
    filters.forEach(function (b) {
      b.addEventListener('click', function () {
        filters.forEach(function (o) {
          var on = o === b;
          o.classList.toggle('jfilter--on', on);
          o.setAttribute('aria-pressed', on ? 'true' : 'false');
        });
      });
    });

    paint();
  }

  /* ------------------------------------------------------------------ 30 */
  /* Shop single v3. The template layout: a deep page with its own section
     nav, a comparison against the part this one replaced, a wear timeline and
     sixty-four comments.

     Every bar on the page is drawn from a data-w the generator computed, not
     from a width typed into the markup — the template ships without a single
     inline style, and a bar whose length disagrees with the number beside it
     is the defect this page exists to avoid. */
  function initShopSingleV3() {
    var top = document.querySelector('.v3top');
    if (!top) { return; }

    /* -- every bar, once ------------------------------------------------- */
    Array.prototype.forEach.call(document.querySelectorAll('[data-w]'), function (el) {
      var w = parseFloat(el.getAttribute('data-w'));
      if (!isNaN(w)) { el.style.width = Math.max(0, Math.min(100, w)) + '%'; }
    });

    var raw = document.getElementById('variants');
    var VARIANTS = [];
    try { VARIANTS = JSON.parse(raw ? raw.textContent : '[]'); } catch (e) { VARIANTS = []; }
    if (!VARIANTS.length) { return; }

    var opts = Array.prototype.slice.call(document.querySelectorAll('.v3size'));
    var adds = Array.prototype.slice.call(document.querySelectorAll('[data-add]'));
    var WORDS = ['no', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight'];

    var state = { at: 0, qty: parseInt((document.getElementById('v3-qty') || {}).textContent, 10) || 1 };

    function current() { return VARIANTS[state.at]; }

    function euro(n) {
      return '\u20ac' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function setText(id, text) {
      var el = document.getElementById(id);
      if (el) { el.textContent = text; }
    }

    /* The buy column and the sticky nav quote the same total, so it is worked
       out once and written to both. */
    function paint() {
      var v = current();
      var total = v.price * state.qty;

      setText('v3-price', euro(total));
      setText('v3-addtotal', euro(total));
      setText('v3-navprice', euro(total));
      setText('v3-navfor', 'for ' + (WORDS[state.qty] || state.qty));
      setText('v3-each', euro(v.price) + ' each \u00d7 ' + state.qty);
      setText('v3-stock', String(v.stock));
      setText('v3-qty', String(state.qty));

      opts.forEach(function (b, i) {
        var on = i === state.at;
        b.classList.toggle('v3size--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });

      var steps = document.querySelectorAll('.v3step__btn');
      if (steps[0]) { steps[0].disabled = state.qty <= 1; }
      if (steps[1]) { steps[1].disabled = state.qty >= v.stock; }

      adds.forEach(function (b) { b.setAttribute('data-add', v.sku); });
    }

    opts.forEach(function (b, i) {
      b.addEventListener('click', function () {
        state.at = i;
        if (state.qty > VARIANTS[i].stock) { state.qty = VARIANTS[i].stock; }
        paint();
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll('.v3step__btn'), function (b) {
      b.addEventListener('click', function () {
        var step = b.getAttribute('aria-label') === 'More' ? 1 : -1;
        state.qty = Math.min(Math.max(1, current().stock), Math.max(1, state.qty + step));
        paint();
      });
    });

    adds.forEach(function (b) {
      b.addEventListener('click', function () {
        var into = !inCart(b.getAttribute('data-add'));
        setInCart(b.getAttribute('data-add'), into);
        adds.forEach(function (o) {
          if (o.childNodes[0].nodeType === 3) {
            o.childNodes[0].nodeValue = into
              ? (o.classList.contains('v3add') ? 'Added \u2014 ' : 'Added')
              : (o.classList.contains('v3add') ? 'Add to basket \u2014 ' : 'Add to basket');
          }
        });
      });
    });

    var thumbs = Array.prototype.slice.call(document.querySelectorAll('.v3thumb'));
    thumbs.forEach(function (b, i) {
      b.addEventListener('click', function () {
        var img = b.querySelector('.v3thumb__img');
        var hero = document.querySelector('.v3hero__img');
        if (img && hero) { hero.src = img.src; }
        thumbs.forEach(function (o) { o.classList.toggle('v3thumb--on', o === b); });
        var count = document.querySelector('.v3hero__count');
        if (count) { count.textContent = (i + 1) + ' / ' + thumbs.length; }
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll('.v3bay'), function (b) {
      b.addEventListener('click', function () {
        Array.prototype.forEach.call(document.querySelectorAll('.v3bay'), function (o) {
          o.classList.toggle('v3bay--on', o === b);
        });
      });
    });

    var filters = Array.prototype.slice.call(document.querySelectorAll('.v3filter'));
    filters.forEach(function (b) {
      b.addEventListener('click', function () {
        filters.forEach(function (o) {
          var on = o === b;
          o.classList.toggle('v3filter--on', on);
          o.setAttribute('aria-pressed', on ? 'true' : 'false');
        });
      });
    });

    /* -- the nav follows the reader -------------------------------------- */
    var links = Array.prototype.slice.call(document.querySelectorAll('.v3nav__a'));
    var marks = links.map(function (a) {
      return document.querySelector(a.getAttribute('href'));
    });

    function spy() {
      /* The section whose top has last passed under the nav is the one being
         read; before the first one, the first stays lit. */
      var at = 0;
      var line = 140;
      for (var i = 0; i < marks.length; i++) {
        if (marks[i] && marks[i].getBoundingClientRect().top <= line) { at = i; }
      }
      links.forEach(function (a, i) { a.classList.toggle('v3nav__a--on', i === at); });
    }

    window.addEventListener('scroll', spy, { passive: true });
    spy();

    paint();
  }

  /* ------------------------------------------------------------------ 31 */
  /* The cart page. The basket is the browser's — module 27 — and this is
     the one page where it is laid out in full: every line, the fitting
     bought on it, and one sum that four places on the page read from.

     Nothing here is typed. The generator prints the demo basket for a page
     without a script and prints the line once more as a template; from the
     first change on, every line, the running total, the summary, the step
     rail and the headline are repainted from the same calculation, so a
     quantity cannot move one figure and leave another where it was.

     The thirty-minute hold is real: it counts from the last change to the
     basket, and the two places that state it read the same clock. */
  function initCartPage() {
    var list = document.getElementById('ck-list');
    var src = document.getElementById('cart-data');
    var lineTpl = document.getElementById('ck-line-tpl');
    var savedTpl = document.getElementById('ck-saved-tpl');
    if (!list || !src || !lineTpl || !savedTpl) { return; }
    var D;
    try { D = JSON.parse(src.textContent); } catch (e) { return; }
    var PARTS = D.parts;

    /* A visitor who arrives here first sees the basket the design describes;
       one who has already added parts on a shop page sees those instead. */
    if (!cartSeeded) {
      cart = [];
      for (var i = 0; i < D.basket.length; i++) { cart.push(cartLine(D.basket[i])); }
      writeCart();
    }
    cart = cart.filter(function (l) { return !!PARTS[l.sku]; });
    paintCart();

    var SAVED_STORE = 'avava-saved';
    var saved = readSaved();

    function readSaved() {
      try {
        var raw = window.localStorage.getItem(SAVED_STORE);
        if (raw === null) { return D.saved.slice(); }
        var rows = JSON.parse(raw);
        if (Object.prototype.toString.call(rows) !== '[object Array]') { return []; }
        return rows.filter(function (s) { return typeof s === 'string' && !!PARTS[s]; });
      } catch (e) { return D.saved.slice(); }
    }

    function writeSaved() {
      try { window.localStorage.setItem(SAVED_STORE, JSON.stringify(saved)); } catch (e) {}
    }

    /* The words and shapes are module 27's, the same the generator prints. */
    var word = cartWord;
    function fitSay(h) { return '\u2014 ' + hoursWords(h) + ', next free bay ' + D.bayName + ', Munich'; }
    function items(n) { return n + (n === 1 ? ' item' : ' items'); }
    var escape = escapeHtml;
    function set(id, text) {
      var el = document.getElementById(id);
      if (el) { el.textContent = text; }
    }
    function html(id, markup) {
      var el = document.getElementById(id);
      if (el) { el.innerHTML = markup; }
    }
    function drop(el) { if (el && el.parentNode) { el.parentNode.removeChild(el); } }

    function totals() { return cartTotals(PARTS, D); }

    function fill(tpl, map) {
      var markup = tpl.innerHTML.replace(/\{\{(\w+)\}\}/g, function (m, k) {
        return map[k] === undefined ? m : map[k];
      });
      var box = document.createElement('div');
      box.innerHTML = markup;
      return box.firstElementChild;
    }

    function lineNode(l) {
      var p = PARTS[l.sku];
      var art = fill(lineTpl, {
        sku: p.sku, alt: escape(p.name), brand: escape(p.brand).toUpperCase(), part: p.sku,
        stockn: p.stock, name: escape(p.name), variant: p.variant, lead: p.lead, href: p.href,
        qty: l.qty, total: euro(p.price * l.qty), each: euro(p.price),
        fitsay: p.fitting ? fitSay(p.fitting.hours * l.qty) : '',
        fitprice: p.fitting ? euro(p.fitting.price * l.qty) : ''
      });
      var img = art.querySelector('.ckline__img');
      img.src = p.photo;
      img.alt = p.name;
      if (!p.fits) { drop(art.querySelector('.ckfits')); }
      if (!p.low) { drop(art.querySelector('.cklow')); }
      if (!p.fitting) { drop(art.querySelector('.ckfit')); }
      paintLine(art, l);
      return art;
    }

    function paintLine(art, l) {
      var p = PARTS[l.sku];
      var on = !!(p.fitting && l.fit);
      art.querySelector('.ckqty__n').textContent = l.qty;
      art.querySelector('[data-qty="-1"]').disabled = l.qty <= 1;
      art.querySelector('[data-qty="1"]').disabled = l.qty >= p.stock;
      art.querySelector('.ckline__total').textContent = euro(p.price * l.qty);
      art.classList.toggle('ckline--fit', on);
      var box = art.querySelector('.ckfit__in');
      if (box) { box.checked = on; }
      var price = art.querySelector('.ckfit__price');
      if (price) { price.textContent = euro(p.fitting.price * l.qty); }
      var say = art.querySelector('.ckfit__t');
      if (say) { say.textContent = fitSay(p.fitting.hours * l.qty); }
    }

    function paintList() {
      list.innerHTML = '';
      cart.forEach(function (l) { list.appendChild(lineNode(l)); });
    }

    function paintSaved() {
      var box = document.getElementById('ck-saved');
      box.innerHTML = '';
      saved.forEach(function (sku) {
        var p = PARTS[sku];
        var row = fill(savedTpl, {
          sku: sku, alt: escape(p.name), brand: escape(p.brand).toUpperCase(),
          name: escape(p.name), stockn: p.stock, price: euro(p.price)
        });
        var img = row.querySelector('.ckrow2__img');
        img.src = p.photo;
        img.alt = p.name;
        box.appendChild(row);
      });
      set('ck-saved-n', saved.length);
      var none = document.getElementById('ck-saved-none');
      if (none) { none.hidden = saved.length > 0; }
      var all = document.querySelector('[data-move-all]');
      if (all) { all.disabled = saved.length === 0; }
    }

    function paintHeld() {
      var at = 0;
      try { at = parseInt(window.localStorage.getItem(CART_HELD), 10) || 0; } catch (e) {}
      if (!at) {
        at = Date.now();
        try { window.localStorage.setItem(CART_HELD, String(at)); } catch (e) {}
      }
      var left = Math.ceil((at + D.hold * 60000 - Date.now()) / 60000);
      var n = cart.length;
      var what = word(n).toUpperCase() + (n === 1 ? ' ITEM' : ' ITEMS');
      set('ck-held', n === 0 ? 'NOTHING HELD'
        : left > 0 ? what + ' HELD FOR ' + left + (left === 1 ? ' MINUTE' : ' MINUTES') : 'HOLD EXPIRED, ANY CHANGE RENEWS IT');
      set('ck-held-panel', n === 0 ? 'NOTHING HELD' : left > 0 ? 'HELD ' + left + ' MIN' : 'HOLD EXPIRED');
    }

    function paintSums() {
      var t = totals();
      var n = t.lines;
      set('ck-h1', word(n) + (n === 1 ? ' item' : ' items'));
      set('ck-step-n', (word(n) + (n === 1 ? ' ITEM' : ' ITEMS')).toUpperCase() + ' READY');
      set('ck-sum-items', items(n));
      set('ck-run-parts', euro(t.parts));
      set('ck-sum-parts', euro(t.parts));
      set('ck-run-fit', euro(t.fitting));
      set('ck-sum-fit', euro(t.fitting));
      set('ck-sum-fitk', t.jobs ? 'Fitting, ' + t.jobs + (t.jobs === 1 ? ' job' : ' jobs') : 'Fitting, none added');
      html('ck-sum-fitsub', t.jobs
        ? hoursWords(t.hours).toUpperCase() + ', ' + D.bay
        : 'TICK IT ON A LINE THE WORKSHOP CAN FIT');
      var runDel = document.getElementById('ck-run-del');
      runDel.textContent = t.free ? 'FREE' : euro(t.delivery);
      runDel.classList.toggle('ckrun__v--free', t.free);
      var sumDel = document.getElementById('ck-sum-del');
      sumDel.innerHTML = n === 0 ? '&mdash;' : t.free ? 'Free' : euro(t.delivery);
      sumDel.classList.toggle('ckrow__v--free', t.free);
      html('ck-sum-delsub', n === 0 ? 'NOTHING TO DELIVER YET'
        : t.free ? 'SAVED &euro;' + D.deliveryFee + ' &middot; FREE OVER &euro;' + D.freeOver
        : '&euro;' + t.toFree + ' MORE AND IT IS FREE');
      set('ck-sum-vat', euro(t.vat));
      set('ck-run-total', euro(t.total));
      set('ck-sum-total', euro(t.total));
      document.getElementById('ck-run').hidden = n === 0;
      var go = document.getElementById('ck-go');
      go.setAttribute('aria-disabled', n === 0 ? 'true' : 'false');
      go.classList.toggle('ckgo--off', n === 0);
      Array.prototype.forEach.call(document.querySelectorAll('.ckadd[data-add]'), function (b) {
        var on = inCart(b.getAttribute('data-add'));
        b.classList.toggle('ckadd--on', on);
        b.querySelector('.ckadd__k').textContent = on ? 'Added' : 'Add';
      });
      paintHeld();
    }

    function commit() {
      writeCart();
      paintCart();
      paintSums();
    }

    function addLine(sku) {
      var line = findLine(cart, sku);
      if (line) { line.qty = Math.min(PARTS[sku].stock, line.qty + 1); }
      else { cart.push({ sku: sku, qty: 1, fit: false }); }
    }

    function dropLine(sku) {
      var line = findLine(cart, sku);
      if (line) { cart.splice(cart.indexOf(line), 1); }
    }

    /* Quantity and fitting change the line in place, so the button that was
       pressed keeps focus; adding and removing redraw the list. */
    list.addEventListener('click', function (e) {
      var btn = e.target.closest('button');
      var art = btn && btn.closest('.ckline');
      if (!art) { return; }
      var sku = art.getAttribute('data-sku');
      var line = findLine(cart, sku);
      if (!line) { return; }
      if (btn.hasAttribute('data-qty')) {
        var step = parseInt(btn.getAttribute('data-qty'), 10);
        line.qty = Math.min(PARTS[sku].stock, Math.max(1, line.qty + step));
        paintLine(art, line);
      } else if (btn.hasAttribute('data-remove')) {
        dropLine(sku);
        drop(art);
      } else if (btn.hasAttribute('data-save')) {
        dropLine(sku);
        drop(art);
        if (saved.indexOf(sku) === -1) { saved.push(sku); }
        writeSaved();
        paintSaved();
      } else {
        return;
      }
      commit();
    });

    list.addEventListener('change', function (e) {
      var box = e.target.closest('.ckfit__in');
      var art = box && box.closest('.ckline');
      if (!art) { return; }
      var line = findLine(cart, art.getAttribute('data-sku'));
      if (!line) { return; }
      line.fit = box.checked;
      paintLine(art, line);
      commit();
    });

    var ups = document.getElementById('ck-ups');
    if (ups) {
      ups.addEventListener('click', function (e) {
        var btn = e.target.closest('.ckadd[data-add]');
        if (!btn) { return; }
        var sku = btn.getAttribute('data-add');
        if (inCart(sku)) { dropLine(sku); } else { addLine(sku); }
        paintList();
        commit();
      });
    }

    function moveToBasket(sku) {
      saved = saved.filter(function (s) { return s !== sku; });
      addLine(sku);
    }

    document.getElementById('ck-saved').addEventListener('click', function (e) {
      var btn = e.target.closest('[data-move]');
      var row = btn && btn.closest('.ckrow2');
      if (!row) { return; }
      moveToBasket(row.getAttribute('data-sku'));
      writeSaved();
      paintSaved();
      paintList();
      commit();
    });

    var all = document.querySelector('[data-move-all]');
    if (all) {
      all.addEventListener('click', function () {
        saved.slice().forEach(moveToBasket);
        writeSaved();
        paintSaved();
        paintList();
        commit();
      });
    }

    /* There are no codes; the field says so rather than pretending. */
    var promo = document.getElementById('ck-promo');
    if (promo) {
      promo.addEventListener('submit', function (e) {
        e.preventDefault();
        var code = promo.querySelector('input').value.trim();
        var note = document.getElementById('ck-promo-note');
        note.hidden = false;
        note.textContent = code ? 'NO SUCH CODE. NOTHING CHANGED' : 'TYPE A CODE FIRST';
      });
    }

    var go = document.getElementById('ck-go');
    if (go) {
      go.addEventListener('click', function (e) {
        if (go.getAttribute('aria-disabled') === 'true') { e.preventDefault(); }
      });
    }

    paintList();
    paintSaved();
    paintSums();
    window.setInterval(paintHeld, 30000);
  }

  /* ------------------------------------------------------------------ 32 */
  /* Checkout. One page, no wizard. Nothing is priced here: the summary is
     the basket's sum read again through module 27, and the fitting card
     repeats the bay the basket booked — hidden, not emptied, when no line
     carries fitting.

     What the page decides itself: how the order is fulfilled, which day, how
     it is paid. Each of those moves the summary the moment it changes, and
     Pay hands the confirmation a snapshot of the order rather than letting
     it recount a basket that has just been emptied. */
  function initCheckout() {
    var form = document.getElementById('chk-form');
    var src = document.getElementById('checkout-data');
    var tpl = document.getElementById('chk-item-tpl');
    if (!form || !src || !tpl) { return; }
    var D;
    try { D = JSON.parse(src.textContent); } catch (e) { return; }
    var PARTS = D.parts;

    if (!cartSeeded) {
      cart = [];
      for (var i = 0; i < D.basket.length; i++) { cart.push(cartLine(D.basket[i])); }
      writeCart();
    }
    cart = cart.filter(function (l) { return !!PARTS[l.sku]; });
    paintCart();

    var state = { fulfil: 'delivery', date: D.dates[0], method: 'card' };

    function set(id, text) {
      var el = document.getElementById(id);
      if (el) { el.textContent = text; }
    }
    function html(id, markup) {
      var el = document.getElementById(id);
      if (el) { el.innerHTML = markup; }
    }
    function fitted() {
      return cart.filter(function (l) { return l.fit && !!PARTS[l.sku].fitting; });
    }

    function paintItems() {
      var box = document.getElementById('chk-items');
      box.innerHTML = '';
      cart.forEach(function (l) {
        var p = PARTS[l.sku];
        var markup = tpl.innerHTML.replace(/\{\{(\w+)\}\}/g, function (m, k) {
          var map = { sku: p.sku, alt: escapeHtml(p.alt), name: p.name, sub: p.sub, qty: l.qty,
            price: euro(p.price * l.qty) };
          return map[k] === undefined ? m : map[k];
        });
        var holder = document.createElement('div');
        holder.innerHTML = markup;
        var row = holder.firstElementChild;
        var img = row.querySelector('.chkitem__img');
        img.src = p.photo;
        img.alt = p.alt;
        box.appendChild(row);
      });
    }

    function paintBay(t) {
      var card = document.getElementById('chk-bay');
      if (!card) { return; }
      var rows = fitted();
      card.hidden = rows.length === 0;
      if (!rows.length) { return; }
      set('chk-bay-when', D.bay.long + ', ' + D.bay.time);
      html('chk-bay-what', rows.map(function (l) {
        return escapeHtml(PARTS[l.sku].full) + ' &times; ' + l.qty;
      }).join(' and ') + ' &mdash; ' + hoursWords(t.hours) + ', ' + escapeHtml(D.bay.where));
      set('chk-bay-price', euro(t.fitting));
    }

    function totals() {
      var t = cartTotals(PARTS, D);
      if (state.fulfil === 'collect') {
        /* Collected, so nothing is delivered and nothing is charged for it. */
        t.delivery = 0;
        t.free = t.lines > 0;
        t.total = t.parts + t.fitting;
        t.vat = Math.floor(t.total * D.vat / (1 + D.vat) + .5);
      }
      return t;
    }

    function paintSums() {
      var t = totals();
      var n = t.lines;
      set('chk-step-n', (cartWord(n) + (n === 1 ? ' ITEM' : ' ITEMS')).toUpperCase());
      set('chk-sum-items', n + (n === 1 ? ' item' : ' items'));
      set('chk-sum-parts', euro(t.parts));
      set('chk-sum-fitk', t.jobs ? 'Fitting, ' + t.jobs + (t.jobs === 1 ? ' job' : ' jobs') : 'Fitting, none added');
      set('chk-sum-fits', t.jobs ? D.bay.day.toUpperCase() + ', ' + D.bay.time : 'TICK IT ON A LINE IN THE BASKET');
      set('chk-sum-fit', euro(t.fitting));
      set('chk-sum-delk', (state.fulfil === 'collect' ? 'Collection, ' : 'Delivery, ') + state.date);
      html('chk-sum-dels', state.fulfil === 'collect' ? 'MUNICH SHOWROOM'
        : n === 0 ? 'NOTHING TO DELIVER YET'
        : t.free ? 'SAVED &euro;' + D.deliveryFee
        : '&euro;' + t.toFree + ' MORE AND IT IS FREE');
      var del = document.getElementById('chk-sum-del');
      del.textContent = n === 0 ? '—' : t.free ? 'Free' : euro(t.delivery);
      del.classList.toggle('chkrow__v--free', t.free && n > 0);
      set('chk-sum-vat', euro(t.vat));
      set('chk-due', euro(t.total));
      set('chk-pay-n', euro(t.total));
      var pay = document.getElementById('chk-pay');
      pay.disabled = n === 0;
      pay.classList.toggle('chkpaybtn--off', n === 0);
      paintBay(t);
    }

    /* The drawn ring or box follows the real control. */
    function paintChoice(input) {
      var label = input.closest('label');
      var group = input.name ? form.querySelectorAll('input[name="' + input.name + '"]') : [input];
      Array.prototype.forEach.call(group, function (other) {
        var l = other.closest('label');
        if (l.classList.contains('chkpick__opt')) { l.classList.toggle('chkpick__opt--on', other.checked); }
        if (l.classList.contains('chkdate')) { l.classList.toggle('chkdate--on', other.checked); }
      });
      var box = label && label.querySelector('.chkbox');
      if (box) { box.classList.toggle('chkbox--on', input.checked); }
    }

    function requireAll(root, on) {
      Array.prototype.forEach.call(root.querySelectorAll('input.chkin'), function (inp) { inp.required = on; });
    }

    form.addEventListener('change', function (e) {
      var input = e.target.closest('input');
      if (!input) { return; }
      if (input.type === 'radio' || input.type === 'checkbox') { paintChoice(input); }
      if (input.name === 'fulfil' && input.checked) {
        state.fulfil = input.value;
        var address = document.getElementById('chk-address');
        address.hidden = state.fulfil === 'collect';
        requireAll(address, state.fulfil !== 'collect');
        paintSums();
      }
      if (input.name === 'date' && input.checked) {
        state.date = input.value;
        paintSums();
      }
    });

    var methods = document.getElementById('chk-methods');
    methods.addEventListener('click', function (e) {
      var btn = e.target.closest('.chkpay__m');
      if (!btn) { return; }
      state.method = btn.getAttribute('data-method');
      Array.prototype.forEach.call(methods.querySelectorAll('.chkpay__m'), function (b) {
        var on = b === btn;
        b.classList.toggle('chkpay__m--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      var cardBox = document.getElementById('chk-card');
      cardBox.hidden = state.method !== 'card';
      requireAll(cardBox, state.method === 'card');
      document.getElementById('chk-note-sepa').hidden = state.method !== 'sepa';
      document.getElementById('chk-note-bank').hidden = state.method !== 'bank';
    });

    function paidWith() {
      if (state.method === 'sepa') { return 'SEPA MANDATE, PENDING'; }
      if (state.method === 'bank') { return 'BANK TRANSFER, ON INVOICE'; }
      var digits = (document.getElementById('chk-cardno').value || '').replace(/\D/g, '');
      return 'VISA ···· ' + (digits.length >= 4 ? digits.slice(-4) : D.cardMask);
    }

    /* Nothing is posted anywhere. The order the confirmation states is the
       one that was on this screen when Pay was pressed. */
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!form.checkValidity()) { form.reportValidity(); return; }
      if (cart.length === 0) { return; }
      var t = totals();
      var snap = {
        number: D.number, total: t.total, lines: t.lines, paidWith: paidWith(),
        bay: fitted().length ? D.bay.day + ', ' + D.bay.time : null,
        eta: state.date, fulfil: state.fulfil,
        email: (document.getElementById('chk-email').value || '').trim()
      };
      try { window.sessionStorage.setItem('avava-paid', JSON.stringify(snap)); } catch (err) {}
      window.location.href = D.done;
    });

    paintItems();
    paintSums();
  }

  /* ------------------------------------------------------------------ 33 */
  /* The order confirmation. Reached from Pay it states the order that was
     just placed — the snapshot the checkout left — and empties the basket,
     because a paid basket is an order, not a basket. Opened on its own it
     shows the order the page was built with. */
  function initOrderDone() {
    var total = document.getElementById('od-total');
    if (!total) { return; }
    var snap = null;
    try { snap = JSON.parse(window.sessionStorage.getItem('avava-paid')); } catch (e) {}
    if (!snap || typeof snap.total !== 'number') { return; }
    function set(id, text) {
      var el = document.getElementById(id);
      if (el) { el.textContent = text; }
    }
    total.textContent = euro(snap.total);
    if (snap.paidWith) { set('od-card', snap.paidWith); }
    if (snap.eta) { set('od-eta', snap.eta); }
    set('od-bay', snap.bay || 'None booked');
    if (snap.email) { set('od-email', snap.email); }
    cart = [];
    writeCart();
    paintCart();
    try { window.localStorage.removeItem(CART_HELD); } catch (e) {}
  }

  /* ------------------------------------------------------------------ 34 */
  /* The blog list. Nothing here is a store: the categories, the archive and
     the tags are the links they look like. What the page can do on its own
     is search the three cards it shows, and say so when none of them match
     — the empty state the design left undrawn. The newsletter form posts
     nowhere; it says what it needs instead of pretending to subscribe. */
  function initBlog() {
    var box = document.getElementById('bl-posts');
    if (!box) { return; }
    var q = document.getElementById('bl-q');
    var empty = document.getElementById('bl-empty');
    var shown = document.getElementById('bl-shown');
    var cards = Array.prototype.slice.call(box.querySelectorAll('.blpost'));

    function filter() {
      var needle = (q.value || '').trim().toLowerCase();
      var left = 0;
      cards.forEach(function (card) {
        var hit = !needle || (card.getAttribute('data-title') + ' ' + card.textContent.toLowerCase()).indexOf(needle) !== -1;
        card.hidden = !hit;
        if (hit) { left += 1; }
      });
      empty.hidden = left > 0;
      shown.textContent = left;
    }

    if (q) {
      q.addEventListener('input', filter);
      q.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); filter(); } });
    }

    var news = document.getElementById('bl-news');
    if (news) {
      news.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!news.checkValidity()) { news.reportValidity(); return; }
        var note = document.getElementById('bl-news-note');
        note.textContent = 'CONNECT THIS FORM TO YOUR LIST — NOTHING IS SENT FROM THE TEMPLATE';
      });
    }
  }

  /* ------------------------------------------------------------------ 35 */
  /* The blog post. The contents rail follows the reader — the row whose
     heading last passed under the top menu is the lit one — and the small
     things that can honestly work on a static page do: Helpful counts up
     and back down, the filters show what they say, copy-link copies, print
     prints. Posting a comment goes nowhere and says so. */
  function initBlogPost() {
    var toc = document.getElementById('po-toc');
    if (!toc) { return; }
    var links = Array.prototype.slice.call(toc.querySelectorAll('.potoc'));
    var marks = links.map(function (a) {
      return document.getElementById((a.getAttribute('href') || '').slice(1));
    });

    function spy() {
      var at = 0;
      var line = 120;
      for (var i = 0; i < marks.length; i++) {
        if (marks[i] && marks[i].getBoundingClientRect().top <= line) { at = i; }
      }
      links.forEach(function (a, i) {
        a.classList.toggle('potoc--on', i === at);
        if (i === at) { a.setAttribute('aria-current', 'true'); } else { a.removeAttribute('aria-current'); }
      });
    }
    window.addEventListener('scroll', spy, { passive: true });
    spy();

    var note = document.getElementById('po-note');
    function say(text) {
      if (!note) { return; }
      note.textContent = text;
      note.hidden = false;
    }

    var copy = document.getElementById('po-copy');
    if (copy) {
      copy.addEventListener('click', function () {
        var url = window.location.href.split('#')[0];
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(url).then(function () { say('LINK COPIED'); }, function () { say(url); });
        } else {
          say(url);
        }
      });
    }
    var share = document.getElementById('po-share');
    if (share) {
      share.addEventListener('click', function () {
        var data = { title: document.title, url: window.location.href.split('#')[0] };
        if (navigator.share) { navigator.share(data).catch(function () {}); } else { say('SHARE: COPY THE LINK'); }
      });
    }
    var print = document.getElementById('po-print');
    if (print) { print.addEventListener('click', function () { window.print(); }); }

    /* Helpful is a toggle: once each, and back. */
    Array.prototype.forEach.call(document.querySelectorAll('.pohelp'), function (btn) {
      var out = btn.querySelector('.pohelp__n');
      var base = parseInt(out.textContent, 10) || 0;
      btn.addEventListener('click', function () {
        var on = !btn.classList.contains('pohelp--on');
        btn.classList.toggle('pohelp--on', on);
        btn.setAttribute('aria-pressed', on ? 'true' : 'false');
        out.textContent = base + (on ? 1 : 0);
      });
    });

    /* Reply puts the composer in front of the reader, addressed. */
    var area = document.getElementById('po-area');
    Array.prototype.forEach.call(document.querySelectorAll('.poreply'), function (btn) {
      btn.addEventListener('click', function () {
        var who = btn.closest('.pothread').querySelector('.pothread__name').textContent.trim();
        if (area) {
          area.value = '@' + who.split(' ')[0] + ' ';
          area.focus();
          area.scrollIntoView({ block: 'center', behavior: 'smooth' });
        }
      });
    });

    /* The filters show what they say: all, the threads with a reply, or
       newest first. */
    var threads = document.getElementById('po-threads');
    var filters = document.getElementById('po-filters');
    if (threads && filters) {
      var rows = Array.prototype.slice.call(threads.querySelectorAll('.pothread'));
      var end = threads.querySelector('.pothreads__end');
      filters.addEventListener('click', function (e) {
        var btn = e.target.closest('.pofilter');
        if (!btn) { return; }
        var mode = btn.getAttribute('data-filter');
        Array.prototype.forEach.call(filters.querySelectorAll('.pofilter'), function (b) {
          b.classList.toggle('pofilter--on', b === btn);
          b.setAttribute('aria-pressed', b === btn ? 'true' : 'false');
        });
        var list = rows.slice();
        if (mode === 'newest') { list.sort(function (a, b) { return (+a.getAttribute('data-age')) - (+b.getAttribute('data-age')); }); }
        list.forEach(function (row) {
          row.hidden = mode === 'replied' && !row.querySelector('.poanswer');
          threads.insertBefore(row, end);
        });
      });
    }

    var form = document.getElementById('po-compose');
    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!form.checkValidity()) { form.reportValidity(); return; }
        var out = document.getElementById('po-compose-note');
        if (out) {
          out.hidden = false;
          out.textContent = 'CONNECT THE FORM TO YOUR COMMENT STORE — NOTHING IS POSTED FROM THE TEMPLATE';
        }
      });
    }

    var news = document.getElementById('po-news');
    if (news) {
      news.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!news.checkValidity()) { news.reportValidity(); return; }
        var n = document.getElementById('po-news-note');
        if (n) { n.textContent = 'CONNECT THIS FORM TO YOUR LIST — NOTHING IS SENT FROM THE TEMPLATE'; }
      });
    }
  }

  /* ------------------------------------------------------------------ 36 */
  /* The profile. The one piece of copy this page must never fake is the
     unsaved count, so the pill is driven from the form: every field is
     compared with what it loaded with, and the count is what differs. Save
     makes the current values the loaded ones; Discard puts them back.

     The photo is a real drop target — drop a file on the circle, or pick
     one — and Remove leaves the monogram. Delete asks twice. Nothing here
     is posted anywhere, and the notes say so. */
  function initProfile() {
    var form = document.getElementById('ua-form');
    if (!form) { return; }
    var fields = Array.prototype.slice.call(form.querySelectorAll('input.uain, textarea.uaarea, input[type="checkbox"]'));
    var pill = document.getElementById('ua-unsaved');
    var pillText = document.getElementById('ua-unsaved-t');
    var save = document.getElementById('ua-save');
    var discard = document.getElementById('ua-discard');
    var lastSaved = document.getElementById('ua-last-saved');

    function changed(el) {
      if (el.type === 'checkbox') { return el.checked !== el.defaultChecked; }
      return el.value !== el.defaultValue;
    }
    function paintDirty() {
      var n = fields.filter(changed).length;
      pill.classList.toggle('uaunsaved--dirty', n > 0);
      pillText.textContent = n ? n + ' UNSAVED' : 'ALL SAVED';
      save.disabled = n === 0;
      discard.disabled = n === 0;
    }

    /* The bio counter is the bio's length. */
    var bio = document.getElementById('ua-bio');
    var count = document.getElementById('ua-bio-count');
    function paintBio() {
      var max = parseInt(bio.getAttribute('maxlength'), 10) || 240;
      count.textContent = bio.value.length + ' / ' + max;
      count.classList.toggle('uacount--over', bio.value.length >= max);
    }

    /* Same as billing is a flag the order uses; the delivery fields stay
       editable either way, as in the design. */
    function paintSame() {}

    form.addEventListener('input', function () { paintBio(); paintDirty(); });
    form.addEventListener('change', function () { paintDirty(); });

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      fields.forEach(function (el) {
        if (el.type === 'checkbox') { el.defaultChecked = el.checked; } else { el.defaultValue = el.value; }
      });
      lastSaved.textContent = 'LAST SAVED JUST NOW';
      paintDirty();
    });
    discard.addEventListener('click', function () {
      form.reset();
      paintSame();
      paintBio();
      paintDirty();
    });

    /* The photo: a file picked or dropped becomes the avatar in both places;
       Remove leaves the monogram, which is the empty state. */
    var slot = document.getElementById('ua-slot');
    var file = document.getElementById('ua-file');
    var slotImg = document.getElementById('ua-slot-img');
    var ava = document.getElementById('ua-ava');
    var avaImg = document.getElementById('ua-ava-img');
    var rule = document.getElementById('ua-photo-rule');
    var ruleText = rule.textContent;

    function setPhoto(src) {
      slotImg.src = src;
      avaImg.src = src;
      slot.classList.add('uaslot--photo');
      ava.classList.add('uaava--photo');
      rule.textContent = ruleText;
    }
    function takeFile(f) {
      if (!f) { return; }
      if (!/^image\/(png|jpeg)$/.test(f.type)) { rule.textContent = 'JPG or PNG only.'; return; }
      if (f.size > 5 * 1024 * 1024) { rule.textContent = 'That one is over 5 MB.'; return; }
      var reader = new FileReader();
      reader.onload = function () { setPhoto(reader.result); };
      reader.readAsDataURL(f);
    }
    document.getElementById('ua-upload').addEventListener('click', function () { file.click(); });
    document.getElementById('ua-edit').addEventListener('click', function () { file.click(); });
    file.addEventListener('change', function () { takeFile(file.files && file.files[0]); file.value = ''; });
    document.getElementById('ua-remove').addEventListener('click', function () {
      slotImg.removeAttribute('src');
      avaImg.removeAttribute('src');
      slot.classList.remove('uaslot--photo');
      ava.classList.remove('uaava--photo');
    });
    ['dragenter', 'dragover'].forEach(function (name) {
      slot.addEventListener(name, function (e) { e.preventDefault(); slot.classList.add('uaslot--over'); });
    });
    ['dragleave', 'drop'].forEach(function (name) {
      slot.addEventListener(name, function (e) { e.preventDefault(); slot.classList.remove('uaslot--over'); });
    });
    slot.addEventListener('drop', function (e) {
      takeFile(e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]);
    });

    /* Data and privacy: the export and the delete are promises the site
       has to keep; the template says what to connect. Delete asks twice. */
    function note(id, text) {
      var el = document.getElementById(id);
      if (el) { el.textContent = text; el.hidden = false; }
    }
    var exportBtn = document.getElementById('ua-export');
    if (exportBtn) {
      exportBtn.addEventListener('click', function () {
        note('ua-export-note', 'CONNECT THIS TO YOUR EXPORT JOB — NOTHING IS SENT FROM THE TEMPLATE');
      });
    }
    var del = document.getElementById('ua-delete');
    if (del) {
      var armed = false;
      del.addEventListener('click', function () {
        if (!armed) {
          armed = true;
          del.textContent = 'CLICK AGAIN TO CONFIRM';
          note('ua-delete-note', 'THIS CANNOT BE UNDONE');
          return;
        }
        note('ua-delete-note', 'CONNECT THIS TO YOUR ACCOUNT API — NOTHING IS DELETED FROM THE TEMPLATE');
      });
    }

    paintSame();
    paintBio();
    paintDirty();
  }

  /* ------------------------------------------------------------------ 37 */
  /* Settings. The switches are real controls — role="switch", toggled in
     aria-checked — and the "N OF 6 ON" pill is counted from them. The
     notifications card carries the profile's dirty-state treatment: Discard
     and Save wake only when a switch differs from what it loaded with.
     Signing a device out removes its row and the count follows. The locale
     applies as it changes and says so. Nothing is posted anywhere. */
  function initSettings() {
    var notify = document.getElementById('us-notify');
    if (!notify) { return; }

    function note(id, text) {
      var el = document.getElementById(id);
      if (el) { el.textContent = text; el.hidden = false; }
    }
    function isOn(sw) { return sw.getAttribute('aria-checked') === 'true'; }

    /* Every switch on the page flips; the notification ones also count. */
    var switches = Array.prototype.slice.call(document.querySelectorAll('.usswitch'));
    switches.forEach(function (sw) {
      sw.setAttribute('data-loaded', sw.getAttribute('aria-checked'));
      sw.addEventListener('click', function () {
        sw.setAttribute('aria-checked', isOn(sw) ? 'false' : 'true');
        if (sw.getAttribute('data-switch') === '2fa') {
          note('us-sec-note', isOn(sw) ? 'TWO-STEP VERIFICATION ON — CONNECT THE SMS STEP TO YOUR AUTH PROVIDER'
            : 'TWO-STEP VERIFICATION OFF — THE ACCOUNT NOW SIGNS IN WITH THE PASSWORD ALONE');
          return;
        }
        paintNotify();
      });
    });

    var rows = Array.prototype.slice.call(notify.querySelectorAll('.usswitch'));
    var onOut = document.getElementById('us-on');
    var save = document.getElementById('us-save');
    var discard = document.getElementById('us-discard');

    function paintNotify() {
      var on = rows.filter(isOn).length;
      var dirty = rows.filter(function (sw) { return sw.getAttribute('aria-checked') !== sw.getAttribute('data-loaded'); }).length;
      onOut.lastChild.nodeValue = on + ' OF ' + rows.length + ' ON';
      save.disabled = dirty === 0;
      discard.disabled = dirty === 0;
    }
    notify.addEventListener('submit', function (e) {
      e.preventDefault();
      rows.forEach(function (sw) { sw.setAttribute('data-loaded', sw.getAttribute('aria-checked')); });
      paintNotify();
    });
    discard.addEventListener('click', function () {
      rows.forEach(function (sw) { sw.setAttribute('aria-checked', sw.getAttribute('data-loaded')); });
      paintNotify();
    });

    /* Sign-in: the changes are flows the site has to build; the page says
       what they need. Recovery codes download as a file, marked as demo. */
    var change = document.getElementById('us-change');
    if (change) {
      change.addEventListener('click', function () {
        note('us-sec-note', 'CHANGING THE PASSWORD RE-CHECKS IT AGAINST KNOWN BREACHES — CONNECT THE DIALOG TO YOUR AUTH API');
      });
    }
    var email = document.getElementById('us-email');
    if (email) {
      email.addEventListener('change', function () {
        note('us-sec-note', 'A CONFIRMATION LINK GOES TO BOTH ADDRESSES BEFORE THE CHANGE APPLIES — CONNECT TO YOUR AUTH API');
      });
    }
    var download = document.getElementById('us-download');
    if (download) {
      download.addEventListener('click', function () {
        var lines = ['AVAVA recovery codes — demo file from the template, replace with codes from your auth provider', ''];
        for (var i = 0; i < 8; i++) {
          var code = '';
          for (var j = 0; j < 10; j++) { code += 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'.charAt(Math.floor(Math.random() * 32)); }
          lines.push(code.slice(0, 5) + '-' + code.slice(5));
        }
        var blob = new Blob([lines.join('\n') + '\n'], { type: 'text/plain' });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'avava-recovery-codes.html';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        note('us-sec-note', 'EIGHT CODES DOWNLOADED — KEEP THEM SOMEWHERE OFF THIS ACCOUNT');
      });
    }

    /* Sessions: signing a device out removes it; the count follows. */
    var list = document.getElementById('us-dev-list');
    var devices = document.getElementById('us-devices');
    function paintDevices() {
      var n = list.querySelectorAll('[data-device]').length;
      devices.textContent = n + (n === 1 ? ' DEVICE' : ' DEVICES');
    }
    list.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-signout]');
      if (!btn) { return; }
      var row = btn.closest('[data-device]');
      if (row && row.parentNode) { row.parentNode.removeChild(row); }
      paintDevices();
    });
    var everywhere = document.getElementById('us-everywhere');
    if (everywhere) {
      everywhere.addEventListener('click', function () {
        Array.prototype.forEach.call(list.querySelectorAll('[data-device]:not(.usdev--on)'), function (row) {
          row.parentNode.removeChild(row);
        });
        paintDevices();
        everywhere.textContent = 'SIGNED OUT EVERYWHERE — THIS DEVICE STAYS';
        everywhere.disabled = true;
      });
    }

    /* Locale applies as it changes, and says so. */
    var locNote = document.getElementById('us-loc-note');
    Array.prototype.forEach.call(document.querySelectorAll('[data-locale]'), function (sel) {
      sel.addEventListener('change', function () {
        var chosen = sel.options[sel.selectedIndex].text;
        locNote.textContent = sel.getAttribute('data-locale').toUpperCase() + ' NOW ' + chosen.toUpperCase() + ' — APPLIED AS YOU CHANGED IT, NOTHING TO SAVE';
        locNote.hidden = false;
      });
    });

    paintNotify();
  }

  /* ------------------------------------------------------------------ 38 */
  /* Saved cars. The rows are real and all of them are in the page; this
     module pages them seven at a time, sorts them four ways, removes a row
     with a 24-hour undo (the node is hidden, not dropped, so undo puts it
     back where it was), lets you tick cars for compare and flips the
     digest switches. Everything that states a number — the H1, the
     toolbar line, the pager, the sidebar chip — reads the live list. */
  function initSaved() {
    var list = document.getElementById('svd-list');
    if (!list) { return; }

    var SIZE = 7;
    var rows = Array.prototype.slice.call(list.querySelectorAll('[data-row]'));
    var sortSel = document.getElementById('svd-sort');
    var line = document.getElementById('svd-line');
    var h1n = document.getElementById('svd-h1-n');
    var h1d = document.getElementById('svd-h1-d');
    var shownOut = document.getElementById('svd-shown');
    var totalOut = document.getElementById('svd-total');
    var fill = document.getElementById('svd-fill');
    var pagesEl = document.getElementById('svd-pages');
    var nextBtn = document.getElementById('svd-next');
    var undo = document.getElementById('svd-undo');
    var undoName = document.getElementById('svd-undo-name');
    var compareBtn = document.getElementById('svd-compare');
    var compareT = document.getElementById('svd-compare-t');
    var chip = document.querySelector('.uanav--on .uanav__n');
    var chipSub = document.querySelector('.uanav--on .uanav__s');

    var WORDS = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve',
      'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen', 'Twenty'];
    var TENS = { 20: 'Twenty', 30: 'Thirty', 40: 'Forty' };
    function word(n) {
      if (n <= 20) { return WORDS[n]; }
      var t = Math.floor(n / 10) * 10;
      return TENS[t] + (n % 10 ? '-' + WORDS[n % 10].toLowerCase() : '');
    }

    var page = 1;
    var removed = [];           /* rows taken out, newest last; only the last is undoable */

    function alive() { return rows.filter(function (r) { return removed.indexOf(r) < 0; }); }

    function keyOf(r) {
      var k = sortSel ? sortSel.value : 'newest';
      if (k === 'drop') { return -parseInt(r.getAttribute('data-delta'), 10); }
      if (k === 'price') { return parseInt(r.getAttribute('data-price'), 10); }
      if (k === 'near') { return parseInt(r.getAttribute('data-km'), 10); }
      return -Date.parse(r.getAttribute('data-saved'));
    }

    function render() {
      var live = alive();
      var order = live.slice().sort(function (a, b) {
        var d = keyOf(a) - keyOf(b);
        return d || (Date.parse(b.getAttribute('data-saved')) - Date.parse(a.getAttribute('data-saved')));
      });
      var last = Math.max(1, Math.ceil(live.length / SIZE));
      if (page > last) { page = last; }
      var from = (page - 1) * SIZE;

      /* Hide everything, then put the page's rows back in sorted order. */
      rows.forEach(function (r) { r.hidden = true; });
      order.slice(from, from + SIZE).forEach(function (r) { r.hidden = false; list.appendChild(r); });

      var counts = { dropped: 0, reserved: 0, sold: 0 };
      live.forEach(function (r) { var s = r.getAttribute('data-status'); if (counts.hasOwnProperty(s)) { counts[s] += 1; } });
      var shown = Math.min(SIZE, Math.max(0, live.length - from));
      var seen = Math.min(page * SIZE, live.length);

      line.textContent = live.length + ' SAVED · ' + counts.dropped + ' DROPPED · ' + counts.reserved + ' RESERVED · ' + counts.sold + ' SOLD';
      h1n.textContent = word(live.length);
      h1d.textContent = word(counts.dropped).toLowerCase();
      shownOut.textContent = shown;
      totalOut.textContent = live.length;
      fill.style.setProperty('--seen', (live.length ? Math.round(seen / live.length * 100) : 0) + '%');
      if (chip) { chip.textContent = live.length; }
      if (chipSub) { chipSub.textContent = counts.dropped + ' DROPPED IN PRICE'; }

      /* Pages: one button per page, the current one filled. */
      var html = '';
      for (var n = 1; n <= last; n++) {
        html += '<li><button class="svdpage' + (n === page ? ' svdpage--on' : '') + '" type="button" data-page="' + n + '"' + (n === page ? ' aria-current="page"' : '') + '>' + n + '</button></li>';
      }
      pagesEl.innerHTML = html;
      nextBtn.disabled = page >= last;
      paintCompare();
    }

    pagesEl.addEventListener('click', function (e) {
      var b = e.target.closest('[data-page]');
      if (!b) { return; }
      page = parseInt(b.getAttribute('data-page'), 10);
      render();
      list.scrollIntoView({ block: 'start' });
    });
    nextBtn.addEventListener('click', function () {
      page += 1;
      render();
      list.scrollIntoView({ block: 'start' });
    });
    if (sortSel) { sortSel.addEventListener('change', function () { page = 1; render(); }); }

    /* Remove and undo. The strip names the car; Undo restores only the
       most recent removal, and the row comes back where the sort puts it. */
    list.addEventListener('click', function (e) {
      var rm = e.target.closest('[data-remove]');
      if (rm) {
        var row = rm.closest('[data-row]');
        removed.push(row);
        var pick = row.querySelector('[data-pick]');
        if (pick) { pick.checked = false; }
        undoName.textContent = row.getAttribute('data-name');
        undo.hidden = false;
        render();
        return;
      }
      var pb = e.target.closest('[data-pick-btn]');
      if (pb) {
        var box = pb.closest('[data-row]').querySelector('[data-pick]');
        box.checked = !box.checked;
        paintCompare();
      }
    });
    list.addEventListener('change', function (e) {
      if (e.target.matches('[data-pick]')) { paintCompare(); }
    });
    document.getElementById('svd-undo-btn').addEventListener('click', function () {
      removed.pop();
      undo.hidden = true;
      render();
    });
    document.getElementById('svd-undo-x').addEventListener('click', function () {
      undo.hidden = true;
    });

    /* Compare: the button wakes at two ticked cars and says how many. */
    function paintCompare() {
      var n = alive().filter(function (r) { return r.querySelector('[data-pick]').checked; }).length;
      rows.forEach(function (r) { r.classList.toggle('svdrow--picked', r.querySelector('[data-pick]').checked); });
      compareBtn.disabled = n < 2;
      compareT.textContent = n >= 2 ? 'Compare ' + n + ' selected' : (n === 1 ? 'Pick one more' : 'Compare selected');
    }
    compareBtn.addEventListener('click', function () {
      var href = compareBtn.getAttribute('data-href');
      if (href && href !== '#') { window.location.href = href; }
    });

    /* Digest switches: per-search flags, counted in the head band. */
    var switches = Array.prototype.slice.call(document.querySelectorAll('[data-digest]'));
    var onOut = document.getElementById('svd-on');
    switches.forEach(function (sw) {
      sw.addEventListener('click', function () {
        sw.setAttribute('aria-checked', sw.getAttribute('aria-checked') === 'true' ? 'false' : 'true');
        var on = switches.filter(function (s) { return s.getAttribute('aria-checked') === 'true'; }).length;
        onOut.textContent = on + ' OF ' + switches.length + ' ON';
      });
    });

    render();
  }

  /* ------------------------------------------------------------------ 39 */
  /* Bookings. The form is controlled: type, car, showroom, work, day, slot
     and the reminder all feed the foot-band summary and the estimate, and
     Confirm builds the new card from the page's own <template> with those
     values. Cancelling a card keeps it for undo; the toolbar line, the H1
     and the sidebar chip count the live list. Move reopens the form
     pre-filled and releases the old slot when the new one is confirmed.
     Add to calendar writes a real .ics file. Nothing is posted anywhere. */
  function initBookings() {
    var form = document.getElementById('bk-form');
    var list = document.getElementById('bk-list');
    if (!form || !list) { return; }

    var WEEK = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    var MONTHS = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
    var BAY_WORDS = ['', 'one', 'two', 'three', 'four', 'five', 'six'];
    /* Which slots go first when a day has fewer free: the same order the page shipped with. */
    var TAKEN_ORDER = ['11:30', '16:30', '10:00', '14:00', '08:30'];

    var carSel = document.getElementById('bk-car');
    var placeSel = document.getElementById('bk-place');
    var stepWork = document.getElementById('bk-step-work');
    var chips = Array.prototype.slice.call(document.querySelectorAll('#bk-chips .bkchip'));
    var days = Array.prototype.slice.call(document.querySelectorAll('#bk-days .bkday'));
    var slots = Array.prototype.slice.call(document.querySelectorAll('#bk-slots .bkslot'));
    var monthOut = document.getElementById('bk-month');
    var sum1 = document.getElementById('bk-sum1');
    var sum2 = document.getElementById('bk-sum2');
    var confirmBtn = document.getElementById('bk-confirm');
    var moving = document.getElementById('bk-moving');
    var formMeta = document.getElementById('bk-form-meta');
    var undo = document.getElementById('bk-undo');
    var undoName = document.getElementById('bk-undo-name');
    var empty = document.getElementById('bk-empty');
    var filter = document.getElementById('bk-filter');
    var upcomingOut = document.getElementById('bk-upcoming');
    var weekOut = document.getElementById('bk-week');
    var citiesOut = document.getElementById('bk-cities');
    var h1Day = document.getElementById('bk-h1-day');
    var h1Tail = document.getElementById('bk-h1-tail');
    var chip = document.querySelector('.uanav--on .uanav__n');
    var chipSub = document.querySelector('.uanav--on .uanav__s');
    var tpl = document.getElementById('bk-card-tpl');

    var last = null;          /* the card kept for undo */
    var movingCard = null;    /* the card being moved */

    function euro(n) { return '€' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
    function hoursText(min) {
      if (!min) { return 'TIME ON INSPECTION'; }
      var h = Math.floor(min / 60), m = min % 60;
      if (!h) { return m + ' MIN'; }
      if (m) { return h + 'H' + (m < 10 ? '0' + m : m); }
      return h === 1 ? 'ONE HOUR' : h + ' HOURS';
    }
    function hoursShort(min) {
      var h = Math.floor(min / 60), m = min % 60;
      return h ? h + 'H' + (m ? (m < 10 ? '0' + m : m) : '') : m + 'MIN';
    }
    function weekdayOf(iso) {
      /* The site's calendar: 2 Sep 2026 is a Tuesday. */
      var days = Math.round((Date.parse(iso) - Date.parse('2026-09-02')) / 86400000);
      return ((days + 1) % 7 + 7) % 7;
    }
    function longDate(iso) {
      var d = new Date(iso + 'T00:00:00');
      var names = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      return WEEK[weekdayOf(iso)] + ' ' + d.getDate() + ' ' + names[d.getMonth()];
    }

    /* ---- the form's state, read from the controls -------------------- */
    function state() {
      var type = (form.querySelector('input[name="bk-type"]:checked') || {}).value || 'workshop';
      var carOpt = carSel.options[carSel.selectedIndex];
      var placeOpt = placeSel.options[placeSel.selectedIndex];
      var work = chips.filter(function (c) { return c.getAttribute('aria-pressed') === 'true'; });
      var day = days.filter(function (d) { return d.classList.contains('bkday--on'); })[0];
      var slot = slots.filter(function (s) { return s.classList.contains('bkslot--on'); })[0];
      var minutes = 0, price = 0, quoted = false;
      work.forEach(function (c) {
        minutes += parseInt(c.getAttribute('data-min'), 10) || 0;
        if (c.hasAttribute('data-price')) { price += parseInt(c.getAttribute('data-price'), 10); } else { quoted = true; }
      });
      if (type === 'viewing') { minutes = 30; }
      if (type === 'collection') { minutes = 40; }
      return {
        type: type, car: carOpt.getAttribute('data-name'), carLabel: carOpt.textContent,
        city: placeOpt.getAttribute('data-city'), free: parseInt(placeOpt.getAttribute('data-free'), 10),
        work: work, minutes: minutes, price: price, quoted: quoted, day: day, slot: slot
      };
    }

    function paintSlots() {
      var day = days.filter(function (d) { return d.classList.contains('bkday--on'); })[0];
      var free = day ? parseInt(day.getAttribute('data-free'), 10) : 0;
      var taken = TAKEN_ORDER.slice(0, Math.max(0, slots.length - free));
      var s = state();
      slots.forEach(function (sl) {
        var t = sl.getAttribute('data-time');
        var isTaken = taken.indexOf(t) >= 0;
        sl.disabled = isTaken;
        var sub = sl.querySelector('.bkslot__s');
        if (isTaken) {
          sub.textContent = 'TAKEN';
          if (sl.classList.contains('bkslot--on')) { sl.classList.remove('bkslot--on'); sl.setAttribute('aria-pressed', 'false'); }
        } else if (s.type === 'workshop') {
          sub.textContent = 'BAY ' + sl.getAttribute('data-bay') + ' · ' + hoursShort(s.minutes || 120);
        } else {
          sub.textContent = (s.type === 'viewing' ? 'DESK · ' : 'HAND-OVER · ') + hoursShort(s.minutes);
        }
      });
      if (!slots.some(function (sl) { return sl.classList.contains('bkslot--on'); })) {
        var first = slots.filter(function (sl) { return !sl.disabled; })[0];
        if (first) { first.classList.add('bkslot--on'); first.setAttribute('aria-pressed', 'true'); }
      }
    }

    function paintSummary() {
      var s = state();
      var when = s.day && s.slot ? s.day.getAttribute('data-long') + ', ' + s.slot.getAttribute('data-time') : 'Pick a day and a time';
      stepWork.hidden = s.type !== 'workshop';
      monthOut.textContent = (s.day ? s.day.getAttribute('data-long').split(' ').slice(2).join(' ').toUpperCase() + ' 2026' : '') + ' · ' + s.city.toUpperCase();
      var ok = !!(s.day && s.slot);
      if (s.type === 'workshop') {
        sum1.textContent = when + ' · ' + s.city + (s.slot ? ', bay ' + s.slot.getAttribute('data-bay') : '');
        if (!s.work.length) {
          sum2.textContent = 'PICK THE WORK — THE ESTIMATE FOLLOWS';
          ok = false;
        } else {
          var labels = s.work.map(function (c) { return c.getAttribute('data-label'); }).join(' + ');
          sum2.textContent = labels + ' · ' + (s.quoted ? 'QUOTED AFTER INSPECTION' : 'ESTIMATE ' + euro(s.price)) + ' · ' + hoursText(s.minutes);
        }
      } else if (s.type === 'viewing') {
        sum1.textContent = when + ' · ' + s.city + ' showroom';
        sum2.textContent = s.car.toUpperCase() + ' · HELD 30 MINUTES · NO DEPOSIT';
      } else {
        sum1.textContent = when + ' · ' + s.city;
        sum2.textContent = s.car.toUpperCase() + ' · FORTY MINUTES WITH THE TECHNICIAN · BALANCE DUE ON THE DAY';
      }
      if (s.free === 0) {
        sum2.textContent = s.city.toUpperCase() + ' IS FULLY BOOKED THIS WEEK — PICK ANOTHER SHOWROOM';
        ok = false;
      }
      confirmBtn.disabled = !ok;
    }

    /* ---- the controls --------------------------------------------- */
    form.addEventListener('change', function (e) {
      if (e.target.name === 'bk-type') {
        Array.prototype.forEach.call(form.querySelectorAll('[data-type-cell]'), function (cell) {
          cell.classList.toggle('bktype--on', cell.querySelector('input').checked);
        });
        paintSlots();
      }
      paintSummary();
    });
    chips.forEach(function (c) {
      c.addEventListener('click', function () {
        var on = c.getAttribute('aria-pressed') !== 'true';
        c.setAttribute('aria-pressed', on ? 'true' : 'false');
        c.classList.toggle('bkchip--on', on);
        paintSlots();
        paintSummary();
      });
    });
    days.forEach(function (d) {
      d.addEventListener('click', function () {
        if (d.disabled) { return; }
        days.forEach(function (o) { o.classList.toggle('bkday--on', o === d); o.setAttribute('aria-pressed', o === d ? 'true' : 'false'); });
        paintSlots();
        paintSummary();
      });
    });
    slots.forEach(function (sl) {
      sl.addEventListener('click', function () {
        if (sl.disabled) { return; }
        slots.forEach(function (o) { o.classList.toggle('bkslot--on', o === sl); o.setAttribute('aria-pressed', o === sl ? 'true' : 'false'); });
        paintSummary();
      });
    });

    /* ---- open, close, confirm -------------------------------------- */
    function openForm(prefill) {
      movingCard = prefill || null;
      if (prefill) {
        var type = prefill.getAttribute('data-type');
        var radio = form.querySelector('input[name="bk-type"][value="' + type + '"]');
        if (radio) { radio.checked = true; radio.dispatchEvent(new Event('change', { bubbles: true })); }
        var city = prefill.getAttribute('data-city');
        Array.prototype.forEach.call(placeSel.options, function (o) { if (o.getAttribute('data-city') === city) { placeSel.value = o.value; } });
        moving.textContent = 'MOVING: ' + prefill.querySelector('[data-title]').textContent.toUpperCase() + ' — THE OLD SLOT IS RELEASED WHEN YOU CONFIRM THE NEW ONE';
        moving.hidden = false;
        formMeta.textContent = 'FREE UP TO 24 HOURS AHEAD';
      } else {
        moving.hidden = true;
        formMeta.textContent = 'NOTHING IS CHARGED UNTIL THE WORK IS DONE';
      }
      form.hidden = false;
      paintSlots();
      paintSummary();
      form.scrollIntoView({ block: 'start', behavior: 'smooth' });
    }
    function closeForm() { form.hidden = true; movingCard = null; }

    Array.prototype.forEach.call(document.querySelectorAll('[data-add]'), function (b) {
      b.addEventListener('click', function () { openForm(null); });
    });
    form.querySelector('[data-form-cancel]').addEventListener('click', closeForm);

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var s = state();
      if (confirmBtn.disabled || !s.day || !s.slot) { return; }
      var iso = s.day.getAttribute('data-day');
      var d = new Date(iso + 'T00:00:00');
      var bay = s.type === 'workshop' ? s.slot.getAttribute('data-bay') : '0';
      var kind = s.type === 'workshop' ? 'WORKSHOP · ' + s.city.toUpperCase() + ', BAY ' + bay
        : (s.type === 'viewing' ? 'VIEWING · ' + s.city.toUpperCase() + ' SHOWROOM' : 'HAND-OVER · ' + s.city.toUpperCase());
      var title = s.type === 'workshop'
        ? (s.work.map(function (c) { return c.textContent; }).join(' & ') || 'Workshop visit')
        : (s.type === 'viewing' ? 'Viewing: ' + s.car : 'Collection: ' + s.car);
      var fact = s.type === 'workshop' ? s.carLabel.split(' · ')[0] + ' · ' + (s.work.length ? hoursText(s.minutes) : 'TIME ON INSPECTION')
        : (s.type === 'viewing' ? 'Held for 30 minutes · no deposit taken' : 'Forty minutes with the technician');
      var amount = s.type === 'workshop' ? (s.quoted ? 'Quote' : euro(s.price)) : 'Free';
      var qualifier = s.type === 'workshop' ? (s.quoted ? 'PRICED AFTER INSPECTION' : 'ESTIMATE · PARTS AND LABOUR')
        : (s.type === 'viewing' ? 'NO OBLIGATION' : 'BALANCE DUE ON THE DAY');
      var note = document.getElementById('bk-note').value.trim();
      var text = 'Confirmation on its way to your email. ' + (document.getElementById('bk-remind').checked ? 'We call the day before to read the list back.' : 'No reminder call — you switched it off.')
        + (note ? ' Your note is on the job sheet.' : '');
      var vals = { type: s.type, date: iso, time: s.slot.getAttribute('data-time'), bay: bay, city: s.city, minutes: String(s.minutes),
        w: s.day.getAttribute('data-w'), n: (d.getDate() < 10 ? '0' : '') + d.getDate(), m: MONTHS[d.getMonth()],
        kind: kind, title: title, fact: fact, text: text, amount: amount, qualifier: qualifier };
      var html = tpl.innerHTML.replace(/\{\{(\w+)\}\}/g, function (_, k) { return escapeHtml(vals[k] || ''); });
      var holder = document.createElement('div');
      holder.innerHTML = html;
      var card = holder.firstElementChild;
      var first = list.querySelector('[data-card]');
      list.insertBefore(card, first || null);
      if (movingCard) { movingCard.remove(); }
      closeForm();
      applyFilter();
      sync();
      card.scrollIntoView({ block: 'center', behavior: 'smooth' });
    });

    /* ---- the list ---------------------------------------------------- */
    function cards() { return Array.prototype.slice.call(list.querySelectorAll('[data-card]')); }
    function live() { return cards().filter(function (c) { return !c.hidden; }); }

    function applyFilter() {
      var v = filter ? filter.value : 'all';
      cards().forEach(function (c) { c.classList.toggle('bkcard--filtered', v !== 'all' && c.getAttribute('data-type') !== v); });
      empty.hidden = live().some(function (c) { return !c.classList.contains('bkcard--filtered'); });
    }

    function sync() {
      var l = live();
      var weekStart = Date.parse('2026-08-31'), weekEnd = Date.parse('2026-09-07');
      var thisWeek = l.filter(function (c) { var t = Date.parse(c.getAttribute('data-date')); return t >= weekStart && t < weekEnd; }).length;
      upcomingOut.textContent = l.length + ' UPCOMING';
      weekOut.textContent = thisWeek + ' THIS WEEK';
      var cities = [];
      l.forEach(function (c) { var city = c.getAttribute('data-city').toUpperCase(); if (cities.indexOf(city) < 0) { cities.push(city); } });
      citiesOut.textContent = cities.length ? cities.join(', ') : 'NO VISITS BOOKED';
      /* the next visit: earliest date, then time */
      var next = l.slice().sort(function (a, b) {
        return (a.getAttribute('data-date') + a.getAttribute('data-time')).localeCompare(b.getAttribute('data-date') + b.getAttribute('data-time'));
      })[0];
      cards().forEach(function (c) {
        c.classList.toggle('bkcard--next', c === next);
        var flag = c.querySelector('.bkflag');
        if (flag && !c.classList.contains('bkcard--just')) { flag.hidden = c !== next; }
      });
      if (next) {
        h1Day.textContent = WEEK[weekdayOf(next.getAttribute('data-date'))] + ',';
        var bay = parseInt(next.getAttribute('data-bay'), 10);
        h1Tail.innerHTML = next.getAttribute('data-type') === 'workshop' && bay
          ? 'bay <span class="accentword">' + BAY_WORDS[bay] + '</span>'
          : '<span class="accentword">' + escapeHtml(next.getAttribute('data-city')) + '</span>';
        if (chipSub) {
          var d = new Date(next.getAttribute('data-date') + 'T00:00:00');
          chipSub.textContent = next.getAttribute('data-w') || WEEK[weekdayOf(next.getAttribute('data-date'))].slice(0, 3).toUpperCase();
          chipSub.textContent = WEEK[weekdayOf(next.getAttribute('data-date'))].slice(0, 3).toUpperCase() + ' ' + d.getDate() + ' ' + MONTHS[d.getMonth()] + ', ' + next.getAttribute('data-time');
        }
      } else {
        h1Day.textContent = 'nothing,';
        h1Tail.innerHTML = 'diary <span class="accentword">clear</span>';
        if (chipSub) { chipSub.textContent = 'NOTHING BOOKED'; }
      }
      if (chip) { chip.textContent = l.length; }
      empty.hidden = live().some(function (c) { return !c.classList.contains('bkcard--filtered'); });
    }

    if (filter) { filter.addEventListener('change', applyFilter); }

    list.addEventListener('click', function (e) {
      var cancel = e.target.closest('[data-cancel]');
      if (cancel) {
        var card = cancel.closest('[data-card]');
        last = card;
        card.hidden = true;
        undoName.textContent = card.querySelector('[data-title]').textContent;
        undo.hidden = false;
        sync();
        return;
      }
      var move = e.target.closest('[data-move]');
      if (move) { openForm(move.closest('[data-card]')); return; }
      var cal = e.target.closest('[data-calendar]');
      if (cal) { downloadIcs(cal.closest('[data-card]')); }
    });
    document.getElementById('bk-undo-btn').addEventListener('click', function () {
      if (last) { last.hidden = false; last = null; }
      undo.hidden = true;
      sync();
    });
    document.getElementById('bk-undo-x').addEventListener('click', function () {
      last = null;
      undo.hidden = true;
    });

    function downloadIcs(card) {
      var date = card.getAttribute('data-date').replace(/-/g, '');
      var t = card.getAttribute('data-time').split(':');
      var start = new Date(Date.UTC(+date.slice(0, 4), +date.slice(4, 6) - 1, +date.slice(6, 8), +t[0], +t[1]));
      var end = new Date(start.getTime() + (parseInt(card.getAttribute('data-minutes'), 10) || 60) * 60000);
      function stamp(x) { return x.toISOString().replace(/[-:]/g, '').slice(0, 15) + 'Z'; }
      var title = card.querySelector('[data-title]').textContent;
      var lines = ['BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//AVAVA//Bookings//EN', 'BEGIN:VEVENT',
        'UID:' + date + t.join('') + '@avava', 'DTSTAMP:' + stamp(new Date()), 'DTSTART:' + stamp(start), 'DTEND:' + stamp(end),
        'SUMMARY:AVAVA — ' + title, 'LOCATION:AVAVA ' + card.getAttribute('data-city'), 'END:VEVENT', 'END:VCALENDAR'];
      var blob = new Blob([lines.join('\r\n') + '\r\n'], { type: 'text/calendar' });
      var a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = 'avava-booking-' + date + '.ics';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }

    /* ---- the history: all six on request ------------------------------- */
    var all = document.getElementById('bk-hist-all');
    var shownOut = document.getElementById('bk-shown');
    var more = Array.prototype.slice.call(document.querySelectorAll('.bkhist--more'));
    if (all) {
      var allLabel = all.textContent;
      all.addEventListener('click', function () {
        var open = all.getAttribute('aria-expanded') !== 'true';
        all.setAttribute('aria-expanded', open ? 'true' : 'false');
        more.forEach(function (r) { r.hidden = !open; });
        var rows = Array.prototype.slice.call(document.querySelectorAll('[data-hist]'));
        rows.forEach(function (r) { r.classList.remove('bkhist--last'); });
        var visible = rows.filter(function (r) { return !r.hidden; });
        if (visible.length) { visible[visible.length - 1].classList.add('bkhist--last'); }
        shownOut.textContent = visible.length;
        all.textContent = open ? 'THE LAST THREE' : allLabel;
      });
    }
    Array.prototype.forEach.call(document.querySelectorAll('[data-doc]'), function (b) {
      b.addEventListener('click', function () {
        b.textContent = b.getAttribute('data-doc') === 'invoice' ? 'INVOICE — CONNECT YOUR DMS' : 'REPORT — CONNECT YOUR DMS';
      });
    });

    paintSlots();
    paintSummary();
    sync();
  }

  /* ------------------------------------------------------------------ 40 */
  /* Orders. Read-only by design: the page finds an order by its number,
     filters by kind, opens the carrier events behind a track, reveals the
     whole history, and starts a return for an item still inside its window.
     Documents and payments say where they connect; nothing is posted. */
  function initOrders() {
    var list = document.getElementById('od-list');
    if (!list) { return; }

    var search = document.getElementById('od-search');
    var filter = document.getElementById('od-filter');
    var empty = document.getElementById('od-empty');
    var carSection = document.querySelector('[data-kind-section="car"]');
    var cards = Array.prototype.slice.call(document.querySelectorAll('[data-order]'));
    var rows = Array.prototype.slice.call(document.querySelectorAll('[data-hist]'));
    var allBtn = document.getElementById('od-hist-all');
    var shownOut = document.getElementById('od-shown');
    var historyOpen = false;

    function digits(s) { return (s || '').replace(/\D/g, ''); }

    function paint() {
      var q = digits(search ? search.value : '');
      var kind = filter ? filter.value : 'all';
      var shownCards = 0;
      cards.forEach(function (c) {
        var ok = (kind === 'all' || c.getAttribute('data-kind') === kind) && (!q || digits(c.getAttribute('data-no')).indexOf(q) >= 0);
        c.hidden = !ok;
        if (ok && c.getAttribute('data-kind') !== 'car') { shownCards += 1; }
      });
      if (carSection) { carSection.hidden = carSection.querySelector('[data-order]').hidden; }
      empty.hidden = shownCards > 0 || (carSection && !carSection.hidden);
      /* the history follows the search too; the kind filter leaves it alone */
      var visible = 0;
      rows.forEach(function (r, i) {
        var match = !q || digits(r.getAttribute('data-no')).indexOf(q) >= 0;
        var inSet = q ? match : (historyOpen || i < 4);
        r.hidden = !inSet;
        r.classList.remove('odrow--last');
        if (inSet) { visible += 1; }
      });
      var last = rows.filter(function (r) { return !r.hidden; }).pop();
      if (last) { last.classList.add('odrow--last'); }
      if (shownOut) { shownOut.textContent = visible; }
    }

    if (search) { search.addEventListener('input', paint); }
    if (filter) { filter.addEventListener('change', paint); }

    if (allBtn) {
      var allLabel = allBtn.textContent;
      allBtn.addEventListener('click', function () {
        historyOpen = !historyOpen;
        allBtn.setAttribute('aria-expanded', historyOpen ? 'true' : 'false');
        allBtn.textContent = historyOpen ? 'THE LAST FOUR' : allLabel;
        paint();
      });
    }

    /* The track's CTA opens the carrier events behind the four steps. */
    document.addEventListener('click', function (e) {
      var t = e.target.closest('[data-track]');
      if (t) {
        var card = t.closest('[data-order]');
        var ev = card.querySelector('.odevents');
        var open = ev.hidden;
        ev.hidden = !open;
        t.setAttribute('aria-expanded', open ? 'true' : 'false');
        return;
      }
      var doc = e.target.closest('[data-doc]');
      if (doc) {
        var kindDoc = doc.getAttribute('data-doc');
        note(doc.closest('[data-order]') || doc.closest('[data-hist]'), ({ invoice: 'INVOICE', credit: 'CREDIT NOTE', contract: 'CONTRACT' })[kindDoc] + ' PDF — CONNECT YOUR DOCUMENT STORE; THE FILE IS ISSUED IN YOUR NAME');
        return;
      }
      var ask = e.target.closest('[data-ask]');
      if (ask) {
        note(ask.closest('[data-order]'), 'QUESTION SENT ABOUT ORDER ' + ask.closest('[data-order]').getAttribute('data-no') + ' — WE ANSWER BY EMAIL WITHIN A WORKING DAY');
        return;
      }
      var pay = e.target.closest('[data-pay]');
      if (pay) {
        note(pay.closest('[data-order]'), 'THE BALANCE IS TAKEN AT THE HAND-OVER OR THROUGH YOUR CHECKOUT — CONNECT THE PAYMENT FLOW HERE');
      }
    });

    function note(host, text) {
      if (!host) { return; }
      var n = host.querySelector('.odnote');
      if (!n) {
        /* a history row has no note slot: borrow the box foot */
        var foot = host.closest('.odbox') && host.closest('.odbox').querySelector('.odbox__note');
        if (foot) { foot.textContent = text; }
        return;
      }
      n.textContent = text;
      n.hidden = false;
    }

    /* A return: one item, one line saying what happens next. */
    var form = document.getElementById('od-return-form');
    if (form) {
      var sel = document.getElementById('od-return');
      var out = document.getElementById('od-return-note');
      var btn = document.getElementById('od-return-btn');
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var opt = sel.options[sel.selectedIndex];
        if (!opt) { return; }
        var no = opt.value;
        out.textContent = 'RETURN OPENED FOR ' + opt.text.toUpperCase() + ' · DHL COLLECTS FROM THE DELIVERY ADDRESS · REFUND TO THE CARD WITHIN FIVE WORKING DAYS';
        out.hidden = false;
        rows.forEach(function (r) {
          if (r.getAttribute('data-no') === no) {
            var pill = r.querySelector('[data-status]');
            pill.textContent = 'RETURN OPEN';
            pill.classList.add('odpill--open');
          }
        });
        opt.remove();
        if (!sel.options.length) {
          btn.disabled = true;
          sel.disabled = true;
          var none = document.createElement('option');
          none.textContent = 'Nothing inside the fourteen days';
          sel.appendChild(none);
        }
      });
    }

    paint();
  }

  /* ------------------------------------------------------------------ 41 */
  /* My listings. One comparable set drives the card's rank, the market band
     and the advice; changing the price re-runs that arithmetic here, so the
     three never disagree. Pause and withdraw change the listing's state,
     the checklist accepts real files, replies open inline, the closed
     listings export as CSV. Nothing is posted anywhere. */
  function initMyListings() {
    var card = document.getElementById('my-live');
    if (!card) { return; }

    var ORD = ['', 'first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth', 'eleventh', 'twelfth'];
    var others = card.getAttribute('data-others').split(',').map(Number);
    var price = parseInt(card.getAttribute('data-price'), 10);
    var saved = 6;
    var avgDays = 26;
    var live = parseInt((document.getElementById('my-advice').textContent.match(/live (\d+)/) || [0, 0])[1], 10);

    function euro(n) { return '€' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
    function market(p) {
      var prices = others.concat([p]).sort(function (a, b) { return a - b; });
      var n = prices.length, lo = prices[0], hi = prices[n - 1], span = hi - lo;
      var q1 = prices[Math.floor(n / 4)], med = prices[Math.floor(n / 2)], q3 = prices[Math.floor(3 * n / 4)];
      return { n: n, rank: prices.indexOf(p) + 1, min: lo, max: hi, median: med,
        tick: (p - lo) / span * 100, left: (q1 - lo) / span * 100, width: (q3 - q1) / span * 100 };
    }
    function word(n) {
      var W = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve'];
      return W[n] || String(n);
    }

    var tick = document.getElementById('my-tick');
    var iqr = document.getElementById('my-iqr');
    var rankOut = document.getElementById('my-rank');
    var insight = document.getElementById('my-insight');
    var advice = document.getElementById('my-advice');
    var youOut = document.getElementById('my-you');
    var medianOut = document.getElementById('my-median');
    var priceOut = document.getElementById('my-price');
    var sinceOut = document.getElementById('my-price-since');
    var input = document.getElementById('my-new-price');
    var changeNote = document.getElementById('my-change-note');

    function paintMarket(p, proposed) {
      var m = market(p), mp = market(proposed);
      tick.style.setProperty('--at', m.tick.toFixed(1) + '%');
      iqr.style.setProperty('--left', m.left.toFixed(1) + '%');
      iqr.style.setProperty('--width', m.width.toFixed(1) + '%');
      rankOut.textContent = m.rank + ' of ' + m.n;
      youOut.textContent = 'YOU · ' + euro(p);
      medianOut.textContent = 'MEDIAN ' + euro(m.median) + ' · ' + m.n + ' SIMILAR CARS LISTED';
      var cap = function (s) { return s.charAt(0).toUpperCase() + s.slice(1); };
      insight.textContent = (m.rank <= Math.ceil(m.n / 2)
        ? 'Below the median now — enquiries usually follow within the week. '
        : 'Plenty of views, few enquiries — that is usually the price. ')
        + cap(word(m.n)) + ' similar cars are listed right now, median ' + euro(m.median) + ', and you are ' + ORD[m.rank] + ' cheapest.';
      advice.textContent = 'Cars in this class leave the site in ' + avgDays + ' days on average. Yours has been live ' + live + '. '
        + (proposed < p
          ? 'Dropping to ' + euro(proposed) + ' would put you ' + ORD[mp.rank] + ' cheapest and, on our numbers, roughly doubles enquiries in the first week.'
          : 'Raising to ' + euro(proposed) + ' would put you ' + ORD[mp.rank] + ' cheapest — fewer enquiries, on our numbers.');
    }

    function parsePrice(s) { var n = parseInt(String(s).replace(/[^\d]/g, ''), 10); return isNaN(n) ? null : n; }

    input.addEventListener('input', function () {
      var p = parsePrice(input.value);
      if (p && p > 1000) { paintMarket(price, p); }
    });
    document.getElementById('my-price-form').addEventListener('submit', function (e) {
      e.preventDefault();
      var p = parsePrice(input.value);
      if (!p || p < 1000) { changeNote.textContent = 'ENTER A PRICE IN EURO.'; return; }
      var drop = p < price;
      price = p;
      card.setAttribute('data-price', String(p));
      priceOut.textContent = euro(p);
      sinceOut.textContent = 'ASKING · CHANGED TODAY';
      paintMarket(price, price);
      changeNote.textContent = drop
        ? 'Changed. The ' + word(saved) + ' buyers who saved your car get one message about the drop. No fee either way.'
        : 'Changed. A rise sends no message — buyers see the new figure when they look.';
    });

    /* pause / withdraw: the listing's state, on the card and in the toolbar */
    var status = document.getElementById('my-status');
    var line = document.getElementById('my-line');
    var note = document.getElementById('my-card-note');
    var pauseBtn = document.getElementById('my-pause');
    var lineText = line.textContent;
    pauseBtn.addEventListener('click', function () {
      var paused = card.getAttribute('data-status') !== 'paused';
      card.setAttribute('data-status', paused ? 'paused' : 'live');
      card.classList.toggle('mycard--paused', paused);
      status.lastChild.nodeValue = paused ? 'PAUSED' : 'LIVE';
      pauseBtn.textContent = paused ? 'RESUME' : 'PAUSE';
      line.textContent = paused ? lineText.replace('1 LIVE', '0 LIVE · 1 PAUSED') : lineText;
      note.textContent = paused ? 'PAUSED — BUYERS CANNOT SEE THE LISTING; THE 60 DAYS KEEP RUNNING' : 'LIVE AGAIN — BUYERS SEE IT NOW';
      note.hidden = false;
    });
    document.getElementById('my-withdraw').addEventListener('click', function () {
      card.setAttribute('data-status', 'withdrawn');
      card.classList.add('mycard--paused');
      status.lastChild.nodeValue = 'WITHDRAWN';
      line.textContent = lineText.replace('1 LIVE', '0 LIVE');
      note.textContent = 'WITHDRAWN — THE CAR IS OFF THE MARKET. IT JOINS THE CLOSED LISTINGS BELOW; LIST IT AGAIN WITH ONE TAP';
      note.hidden = false;
      pauseBtn.disabled = true;
    });

    /* the filter shows one state at a time */
    var filter = document.getElementById('my-filter');
    var empty = document.getElementById('my-empty');
    var sections = Array.prototype.slice.call(document.querySelectorAll('[data-section]'));
    var closedRows = Array.prototype.slice.call(document.querySelectorAll('[data-closed]'));
    filter.addEventListener('change', function () {
      var v = filter.value;
      var shown = 0;
      sections.forEach(function (s) {
        var key = s.getAttribute('data-section');
        var ok = (v === 'live' && key === 'live') || (v === 'draft' && key === 'draft') || ((v === 'sold' || v === 'withdrawn') && key === 'closed');
        s.hidden = !ok;
        if (ok) { shown += 1; }
      });
      if (v === 'sold' || v === 'withdrawn') {
        var any = 0;
        closedRows.forEach(function (r) { var ok = r.getAttribute('data-status') === v; r.hidden = !ok; if (ok) { any += 1; } });
        if (!any) { shown = 0; }
      } else {
        closedRows.forEach(function (r) { r.hidden = false; });
      }
      empty.hidden = shown > 0;
    });

    /* replies open inline */
    Array.prototype.forEach.call(document.querySelectorAll('[data-enquiry]'), function (row) {
      var box = row.querySelector('.myenq__reply');
      row.querySelector('[data-reply]').addEventListener('click', function () {
        box.hidden = !box.hidden;
        if (!box.hidden) { box.querySelector('textarea').focus(); }
      });
      row.querySelector('[data-send]').addEventListener('click', function () {
        var ta = box.querySelector('textarea');
        if (!ta.value.trim()) { ta.focus(); return; }
        box.hidden = true;
        var who = row.querySelector('.myenq__name').textContent;
        row.querySelector('.myenq__line').textContent = 'You replied to ' + who + ' just now — the thread continues in Messages.';
        row.querySelector('[data-reply]').textContent = 'Replied';
        row.querySelector('[data-reply]').disabled = true;
      });
    });

    /* the checklist accepts a file and counts it */
    var ready = document.getElementById('my-ready');
    Array.prototype.forEach.call(document.querySelectorAll('[data-upload]'), function (inp) {
      inp.addEventListener('change', function () {
        if (!inp.files || !inp.files.length) { return; }
        var row = inp.closest('[data-check]');
        row.classList.add('myq--done');
        var end = row.querySelector('.myq__end');
        end.innerHTML = '<span class="mydone">DONE</span>';
        var item = row.querySelector('.myq__item');
        item.textContent = item.textContent.replace('not uploaded', 'uploaded').replace('No walk-around video', 'Walk-around video added');
        ready.textContent = document.querySelectorAll('.myq--done').length;
      });
    });

    /* the draft can be deleted */
    var del = document.getElementById('my-draft-delete');
    if (del) {
      del.addEventListener('click', function () {
        document.getElementById('my-draft').hidden = true;
        var n = document.getElementById('my-draft-note');
        n.textContent = 'DRAFT DELETED. NOTHING WAS PUBLISHED, SO NOTHING WAS SEEN.';
        n.hidden = false;
      });
    }

    /* documents and the CSV */
    Array.prototype.forEach.call(document.querySelectorAll('[data-doc]'), function (b) {
      b.addEventListener('click', function () { b.textContent = 'CONTRACT — CONNECT YOUR DOCUMENT STORE'; });
    });
    var exp = document.getElementById('my-export');
    if (exp) {
      exp.addEventListener('click', function () {
        var rows = [['date', 'car', 'outcome', 'price_made', 'days_live']];
        closedRows.forEach(function (r) {
          var cells = r.querySelectorAll('p');
          var car = cells[1].textContent.split(' · ');
          rows.push([cells[0].textContent, car[0], car[1] || '', cells[2].textContent.replace(/[^\d]/g, ''), cells[3].textContent.replace(/\D/g, '')]);
        });
        var csv = rows.map(function (r) { return r.map(function (c) { return '"' + String(c).replace(/"/g, '""') + '"'; }).join(','); }).join('\r\n');
        var a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([csv + '\r\n'], { type: 'text/csv' }));
        a.download = 'avava-closed-listings.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
      });
    }

    paintMarket(price, parsePrice(input.value) || price);
  }

  /* ------------------------------------------------------------------ 42 */
  /* Messages. The list switches the conversation: every thread's messages
     travel with the page as JSON and are rendered through the same
     templates the shipped thread was built from. Sending appends a bubble
     and clears the unread count; the toolbar line, the H1 and the sidebar
     chip count the live list. Quick replies follow the thread's intent.
     Report writes a system line that stays. Nothing is posted anywhere. */
  function initMessages() {
    var card = document.getElementById('im-card');
    if (!card) { return; }

    var data = JSON.parse(document.getElementById('im-data').textContent);
    var threads = Array.prototype.slice.call(document.querySelectorAll('[data-thread]')).filter(function (b) { return b.classList.contains('imthread'); });
    var feed = document.getElementById('im-feed');
    var conv = document.getElementById('im-conv');
    var tplThem = document.getElementById('im-tpl-them');
    var tplMe = document.getElementById('im-tpl-me');
    var tplSys = document.getElementById('im-tpl-sys');
    var search = document.getElementById('im-search');
    var filter = document.getElementById('im-filter');
    var closedBtn = document.getElementById('im-closed');
    var shownOut = document.getElementById('im-shown');
    var empty = document.getElementById('im-empty');
    var line = document.getElementById('im-line');
    var h1n = document.getElementById('im-h1-n');
    var h1w = document.getElementById('im-h1-w');
    var chip = document.querySelector('.uanav--on .uanav__n');
    var chipSub = document.querySelector('.uanav--on .uanav__s');
    var text = document.getElementById('im-text');
    var quick = document.getElementById('im-quick');
    var composer = document.getElementById('im-composer');
    var attachNote = document.getElementById('im-attach-note');
    var menuBtn = document.getElementById('im-more');
    var menu = document.getElementById('im-menu');
    var offer = document.getElementById('im-offer');

    var WORDS = ['no', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve'];
    var REPLY_HOURS = 6;
    var showClosed = false;
    var current = conv.getAttribute('data-thread');
    var pending = {};           /* per thread: unread count, open state, waiting */
    threads.forEach(function (b) {
      var n = b.querySelector('.imthread__n');
      var unread = n ? parseInt(n.textContent, 10) : 0;
      pending[b.getAttribute('data-thread')] = { unread: unread, open: b.getAttribute('data-open') === '1',
        /* waiting: their word is the last one and has sat past the reply time — Marek wrote two hours ago, so only Lena */
        waiting: unread > 0 && b.getAttribute('data-thread') !== 't1' };
    });

    function fill(tpl, vals) {
      var html = tpl.innerHTML.replace(/\{\{(\w+)\}\}/g, function (_, k) { return vals[k] || ''; });
      var holder = document.createElement('div');
      holder.innerHTML = html;
      return holder.firstElementChild;
    }

    function nowStamp() {
      var d = new Date();
      return '2 SEP, ' + (d.getHours() < 10 ? '0' : '') + d.getHours() + ':' + (d.getMinutes() < 10 ? '0' : '') + d.getMinutes();
    }

    function render(key) {
      var t = data[key];
      if (!t) { return; }
      current = key;
      conv.setAttribute('data-thread', key);
      document.getElementById('im-head-name').textContent = t.name;
      document.getElementById('im-head-role').textContent = t.role;
      document.getElementById('im-head-prov').innerHTML = t.prov;
      var ava = document.getElementById('im-head-ava');
      var img = ava.querySelector('img');
      if (t.photo) { img.src = t.photo; ava.hidden = false; } else { ava.hidden = true; }
      document.getElementById('im-obj-title').innerHTML = t.object.title;
      document.getElementById('im-obj-facts').innerHTML = t.object.facts;
      var link = document.getElementById('im-obj-link');
      link.textContent = t.object.link;
      link.href = t.object.href;
      link.hidden = !t.object.link;
      var thumb = document.getElementById('im-obj-img');
      if (t.object.thumb) { thumb.src = t.object.thumb; thumb.parentNode.hidden = false; } else { thumb.parentNode.hidden = true; }
      feed.textContent = '';
      t.messages.forEach(function (m) {
        if (m.sys) { feed.appendChild(fill(tplSys, { text: m.text })); return; }
        var el = fill(m.who === 'me' ? tplMe : tplThem, { text: m.text, when: m.when });
        if (m.who === 'them') { el.querySelector('img').src = t.photo || 'assets/img/logo-avava.svg'; }
        feed.appendChild(el);
      });
      quick.textContent = '';
      t.quick.forEach(function (q) {
        var b = document.createElement('button');
        b.className = 'imquick__btn'; b.type = 'button'; b.setAttribute('data-quick', ''); b.textContent = q;
        quick.appendChild(b);
      });
      text.placeholder = 'Write to ' + t.name.split(' ')[0];
      offer.hidden = t.kind !== 'listing' || t.intent === 'closed';
      composer.hidden = t.intent === 'closed';
      threads.forEach(function (b) {
        var on = b.getAttribute('data-thread') === key;
        b.classList.toggle('imthread--on', on);
        if (on) { b.setAttribute('aria-current', 'true'); } else { b.removeAttribute('aria-current'); }
      });
      markRead(key);
      feed.scrollTop = feed.scrollHeight;
    }

    function markRead(key) {
      var b = threads.filter(function (x) { return x.getAttribute('data-thread') === key; })[0];
      if (!b) { return; }
      var n = b.querySelector('.imthread__n');
      if (n) { n.remove(); }
      b.classList.remove('imthread--unread');
      pending[key].unread = 0;
      pending[key].waiting = false;
      sync();
    }

    function sync() {
      var open = 0, waiting = 0, unreadThreads = 0, aboutListing = 0;
      threads.forEach(function (b) {
        var p = pending[b.getAttribute('data-thread')];
        if (p.open) { open += 1; }
        if (p.open && p.waiting) { waiting += 1; }
        if (p.unread) { unreadThreads += 1; if (b.getAttribute('data-kind') === 'listing') { aboutListing += 1; } }
      });
      var closed = threads.length - open;
      line.textContent = open + ' OPEN · ' + waiting + ' WAITING ON YOU · ' + closed + ' CLOSED';
      h1n.textContent = WORDS[open] || String(open);
      document.getElementById('im-h1-n').nextSibling.textContent = open === 1 ? ' ' : ' ';
      h1w.textContent = waiting === 0 ? 'none needs' : (waiting === 1 ? 'one needs' : (WORDS[waiting] || waiting).toLowerCase() + ' need');
      if (chip) { chip.textContent = unreadThreads; }
      if (chipSub) { chipSub.textContent = aboutListing + ' ABOUT THE SL 63'; }
      closedBtn.textContent = (showClosed ? 'HIDE ' : 'SHOW ') + closed + ' CLOSED THREADS';
      paintList();
    }

    function paintList() {
      var q = (search.value || '').trim().toLowerCase();
      var f = filter.value;
      var shown = 0;
      threads.forEach(function (b) {
        var p = pending[b.getAttribute('data-thread')];
        var hay = (b.getAttribute('data-name') + ' ' + b.getAttribute('data-subject')).toLowerCase();
        var ok = !q || hay.indexOf(q) >= 0;
        if (f === 'closed') { ok = ok && !p.open; }
        else if (f !== 'all') { ok = ok && b.getAttribute('data-kind') === f; }
        if (!p.open && f !== 'closed' && !showClosed && !q) { ok = false; }
        b.hidden = !ok;
        if (ok) { shown += 1; }
      });
      shownOut.textContent = shown;
      empty.hidden = shown > 0;
      card.hidden = shown === 0;
    }

    threads.forEach(function (b) {
      b.addEventListener('click', function () { render(b.getAttribute('data-thread')); });
    });
    search.addEventListener('input', paintList);
    filter.addEventListener('change', paintList);
    closedBtn.addEventListener('click', function () {
      showClosed = !showClosed;
      closedBtn.setAttribute('aria-expanded', showClosed ? 'true' : 'false');
      sync();
    });

    /* the composer */
    quick.addEventListener('click', function (e) {
      var b = e.target.closest('[data-quick]');
      if (!b) { return; }
      text.value = b.textContent;
      text.focus();
    });
    composer.addEventListener('submit', function (e) {
      e.preventDefault();
      var body = text.value.trim();
      if (!body) { text.focus(); return; }
      var el = fill(tplMe, { text: escapeHtml(body) + (attachNote.hidden ? '' : ' <span class="immsg__att">' + escapeHtml(attachNote.getAttribute('data-file')) + '</span>'), when: nowStamp() });
      feed.appendChild(el);
      data[current].messages.push({ who: 'me', text: escapeHtml(body), when: nowStamp() });
      text.value = '';
      attachNote.hidden = true;
      var row = threads.filter(function (x) { return x.getAttribute('data-thread') === current; })[0];
      if (row) {
        row.querySelector('.imthread__text').textContent = 'You: ' + body;
        row.querySelector('.imthread__age').textContent = 'NOW';
        pending[current].waiting = false;
      }
      sync();
      feed.scrollTop = feed.scrollHeight;
    });
    document.getElementById('im-attach').addEventListener('change', function (e) {
      var f = e.target.files && e.target.files[0];
      if (!f) { return; }
      attachNote.textContent = 'ATTACHED · ' + f.name.toUpperCase() + ' · ' + Math.max(1, Math.round(f.size / 1024)) + ' KB — SENT WITH YOUR NEXT MESSAGE';
      attachNote.setAttribute('data-file', f.name);
      attachNote.hidden = false;
    });
    document.getElementById('im-report').addEventListener('click', function () {
      feed.appendChild(fill(tplSys, { text: 'REPORTED · STAFF CAN NOW OPEN THIS THREAD · THIS NOTICE STAYS' }));
      data[current].messages.push({ sys: true, text: 'REPORTED · STAFF CAN NOW OPEN THIS THREAD · THIS NOTICE STAYS' });
      feed.scrollTop = feed.scrollHeight;
    });

    /* the ⋯ menu */
    menuBtn.addEventListener('click', function () {
      var open = menu.hidden;
      menu.hidden = !open;
      menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.immenu')) { menu.hidden = true; menuBtn.setAttribute('aria-expanded', 'false'); }
    });
    menu.addEventListener('click', function (e) {
      var item = e.target.closest('[data-menu]');
      if (!item) { return; }
      var what = item.getAttribute('data-menu');
      var row = threads.filter(function (x) { return x.getAttribute('data-thread') === current; })[0];
      if (what === 'unread' && row) {
        row.classList.add('imthread--unread');
        if (!row.querySelector('.imthread__n')) {
          var n = document.createElement('span'); n.className = 'imthread__n'; n.textContent = '1';
          row.querySelector('.imthread__last').appendChild(n);
        }
        pending[current].unread = 1;
      } else if (what === 'close' && row) {
        pending[current].open = false;
        row.classList.remove('imthread--open', 'imthread--unread');
        row.classList.add('imthread--closed');
        row.setAttribute('data-open', '0');
        feed.appendChild(fill(tplSys, { text: 'THREAD CLOSED · 2 SEP' }));
        composer.hidden = true;
      } else if (what === 'mute') {
        feed.appendChild(fill(tplSys, { text: 'NOTIFICATIONS MUTED FOR THIS THREAD · 2 SEP' }));
      }
      menu.hidden = true;
      menuBtn.setAttribute('aria-expanded', 'false');
      sync();
    });

    sync();
  }

  /* ------------------------------------------------------------------ 43 */
  /* Sell my car. Six steps stacked for review; the rail walks them one at
     a time — Next marks a step done and lights the next, Back returns, a
     done cell is clickable, an unreached one is not. Chips, fields, photo
     frames and document rows are real and feed the draft bar, the photo
     count, the ready count and the preview card. The price step re-runs
     the market arithmetic My listings uses. Publish validates and then
     says so on the page. Nothing is posted anywhere. */
  function initSell() {
    var rail = document.getElementById('sm-rail');
    if (!rail) { return; }

    var ORD = ['', 'first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth', 'eleventh'];
    var cells = Array.prototype.slice.call(rail.querySelectorAll('[data-step]'));
    var cards = Array.prototype.slice.call(document.querySelectorAll('[data-step-card]'));
    var saved = document.getElementById('sm-saved');
    var state = cells.map(function (c) { return c.classList.contains('smrail__cell--done') ? 'done' : (c.classList.contains('smrail__cell--current') ? 'current' : 'todo'); });
    var savedAt = null;

    function euro(n) { return '€' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
    function parseNum(s) { var n = parseInt(String(s).replace(/[^\d]/g, ''), 10); return isNaN(n) ? null : n; }
    function touch() {
      savedAt = Date.now();
      saved.textContent = 'DRAFT SAVED · JUST NOW';
    }
    setInterval(function () {
      if (!savedAt) { return; }
      var m = Math.round((Date.now() - savedAt) / 60000);
      saved.textContent = m < 1 ? 'DRAFT SAVED · JUST NOW' : 'DRAFT SAVED · ' + m + ' MIN AGO';
    }, 15000);

    /* ---- the rail ---------------------------------------------------- */
    function paintRail() {
      cells.forEach(function (c, i) {
        c.className = 'smrail__cell smrail__cell--' + state[i];
        c.setAttribute('aria-current', state[i] === 'current' ? 'step' : 'false');
        c.disabled = state[i] === 'todo';
        var disc = c.querySelector('.smrail__disc');
        disc.innerHTML = state[i] === 'done'
          ? '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12l4 4 10-11"></path></svg>'
          : '<span class="smrail__num">' + (i + 1) + '</span>';
      });
      cards.forEach(function (card, i) {
        card.classList.toggle('smstep--current', state[i] === 'current');
        card.classList.toggle('smstep--done', state[i] === 'done');
        var tag = card.querySelector('[data-tag]');
        if (!tag) { return; }
        if (state[i] === 'current') {
          tag.innerHTML = '<span class="smhere">YOU ARE HERE</span>';
        } else if (state[i] === 'done') {
          tag.innerHTML = '<span class="smdone"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12l4 4 10-11"></path></svg> DONE</span>';
        } else if (tag.getAttribute('data-right')) {
          tag.innerHTML = tag.getAttribute('data-right');
        }
      });
    }
    cards.forEach(function (card) {
      var tag = card.querySelector('[data-tag]');
      var meta = tag && tag.querySelector('.smmeta');
      if (meta) { tag.setAttribute('data-right', tag.innerHTML); }   /* the whole right-hand label, ids included */
    });

    function goTo(n) {
      state = state.map(function (s, i) { return i + 1 < n ? 'done' : (i + 1 === n ? 'current' : (s === 'done' ? 'done' : 'todo')); });
      paintRail();
      var card = cards[n - 1];
      if (card) { card.scrollIntoView({ block: 'start', behavior: 'smooth' }); }
    }
    cells.forEach(function (c, i) {
      c.addEventListener('click', function () { if (!c.disabled) { goTo(i + 1); } });
    });
    cards.forEach(function (card, i) {
      Array.prototype.forEach.call(card.querySelectorAll('[data-next]'), function (b) {
        b.addEventListener('click', function () {
          if (i === 1 && !defectsOk()) {
            document.getElementById('sm-chips').classList.add('smchips--missing');
            document.getElementById('sm-chips').scrollIntoView({ block: 'center', behavior: 'smooth' });
            return;
          }
          if (i + 2 <= cards.length) { goTo(i + 2); }
        });
      });
      Array.prototype.forEach.call(card.querySelectorAll('[data-back]'), function (b) {
        b.addEventListener('click', function () { if (i > 0) { goTo(i); } });
      });
    });

    /* ---- step 1: the path --------------------------------------------- */
    Array.prototype.forEach.call(document.querySelectorAll('[data-path]'), function (p) {
      p.querySelector('input').addEventListener('change', function () {
        Array.prototype.forEach.call(document.querySelectorAll('[data-path]'), function (o) { o.classList.toggle('smpath--on', o.querySelector('input').checked); });
        touch();
      });
    });
    var change = document.getElementById('sm-change');
    if (change) {
      change.addEventListener('click', function () {
        var garage = document.querySelector('input[name="sm-path"][value="garage"]');
        garage.checked = true;
        garage.dispatchEvent(new Event('change', { bubbles: true }));
        garage.focus();
      });
    }

    /* ---- step 2: condition ---------------------------------------------- */
    var chips = Array.prototype.slice.call(document.querySelectorAll('[data-defect]'));
    var words = document.getElementById('sm-words');
    function defectsOk() { return chips.some(function (c) { return c.getAttribute('aria-pressed') === 'true'; }); }
    chips.forEach(function (c) {
      c.addEventListener('click', function () {
        var on = c.getAttribute('aria-pressed') !== 'true';
        if (c.hasAttribute('data-none') && on) {
          chips.forEach(function (o) { if (o !== c) { o.setAttribute('aria-pressed', 'false'); o.classList.remove('smchip--on'); } });
        } else if (on) {
          var none = chips.filter(function (o) { return o.hasAttribute('data-none'); })[0];
          if (none) { none.setAttribute('aria-pressed', 'false'); none.classList.remove('smchip--on'); }
        }
        c.setAttribute('aria-pressed', on ? 'true' : 'false');
        c.classList.toggle('smchip--on', on);
        document.getElementById('sm-chips').classList.remove('smchips--missing');
        paintPreview();
        touch();
      });
    });
    Array.prototype.forEach.call(document.querySelectorAll('.smin, .smtext'), function (f) {
      f.addEventListener('input', function () { touch(); paintPreview(); });
    });

    /* ---- step 3: photos --------------------------------------------------- */
    var frames = Array.prototype.slice.call(document.querySelectorAll('[data-frame]'));
    var photoCount = document.getElementById('sm-photo-count');
    function countPhotos() {
      var n = frames.filter(function (f) { return !f.classList.contains('smframe--empty'); }).length;
      photoCount.textContent = n + ' of ' + frames.length + ' frames filled. Drag a frame to reorder; the first one is the main photo.';
      var preview = document.querySelector('.smcardp__img');
      var first = frames.filter(function (f) { return !f.classList.contains('smframe--empty'); })[0];
      if (preview) { preview.hidden = !first; if (first) { preview.src = first.querySelector('img').src; } }
      return n;
    }
    frames.forEach(function (f) {
      f.querySelector('[data-photo]').addEventListener('change', function (e) {
        var file = e.target.files && e.target.files[0];
        if (!file) { return; }
        var img = f.querySelector('img');
        img.src = URL.createObjectURL(file);
        f.classList.remove('smframe--empty');
        f.classList.add('smframe--own');
        countPhotos();
        touch();
      });
      /* the × empties the frame; the slot keeps its name and takes a new file */
      f.querySelector('[data-remove]').addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var img = f.querySelector('img');
        img.removeAttribute('src');
        f.querySelector('[data-photo]').value = '';
        f.classList.add('smframe--empty');
        f.classList.remove('smframe--own');
        countPhotos();
        touch();
      });
      f.setAttribute('draggable', 'true');
      f.addEventListener('dragstart', function (e) { e.dataTransfer.setData('text/plain', String(frames.indexOf(f))); });
      f.addEventListener('dragover', function (e) { e.preventDefault(); });
      f.addEventListener('drop', function (e) {
        e.preventDefault();
        var from = parseInt(e.dataTransfer.getData('text/plain'), 10);
        if (isNaN(from) || frames[from] === f) { return; }
        var host = f.parentNode;
        host.insertBefore(frames[from], f);
        frames = Array.prototype.slice.call(host.querySelectorAll('[data-frame]'));
        frames.forEach(function (x, i) {
          x.classList.toggle('smframe--main', i === 0);
          var m = x.querySelector('.smframe__main');
          if (i === 0 && !m) { var s = document.createElement('span'); s.className = 'smframe__main'; s.textContent = 'MAIN PHOTO'; x.appendChild(s); }
          if (i !== 0 && m) { m.remove(); }
        });
        countPhotos();
        touch();
      });
    });

    /* ---- step 4: documents ---------------------------------------------- */
    Array.prototype.forEach.call(document.querySelectorAll('[data-doc]'), function (inp) {
      inp.addEventListener('change', function () {
        if (!inp.files || !inp.files.length) { return; }
        var end = inp.closest('.smdoc__end');
        end.innerHTML = '<span class="smheld"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12l4 4 10-11"></path></svg> WE HOLD IT</span>';
        touch();
      });
    });

    /* ---- step 5: the price, on the market query ------------------------ */
    var step5 = document.getElementById('sm-step-5');
    var others = step5.getAttribute('data-others').split(',').map(Number);
    var price = document.getElementById('sm-price');
    var advice = document.getElementById('sm-advice');
    var priceHead = document.getElementById('sm-price-head');
    var tick = document.getElementById('sm-tick');
    var iqr = document.getElementById('sm-iqr');
    function market(p) {
      var prices = others.concat([p]).sort(function (a, b) { return a - b; });
      var n = prices.length, lo = prices[0], hi = prices[n - 1], span = hi - lo;
      var q1 = prices[Math.floor(n / 4)], med = prices[Math.floor(n / 2)], q3 = prices[Math.floor(3 * n / 4)];
      return { n: n, rank: prices.indexOf(p) + 1, median: med, tick: (p - lo) / span * 100, left: (q1 - lo) / span * 100, width: (q3 - q1) / span * 100 };
    }
    function paintPrice() {
      var p = parseNum(price.value);
      if (!p || p < 1000) { return; }
      var m = market(p);
      tick.style.setProperty('--at', Math.max(0, Math.min(100, m.tick)).toFixed(1) + '%');
      iqr.style.setProperty('--left', m.left.toFixed(1) + '%');
      iqr.style.setProperty('--width', m.width.toFixed(1) + '%');
      var W = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve'];
      advice.textContent = 'At ' + euro(p) + ' you are ' + ORD[m.rank] + ' cheapest of ' + (W[m.n] || m.n) + '. Cars in this class leave the site in 26 days on average; the three that sold fastest were all under the median.';
      if (priceHead) { priceHead.textContent = m.n + ' SIMILAR CARS LISTED · MEDIAN ' + euro(m.median); }
      document.getElementById('sm-prev-price').textContent = euro(p);
    }
    price.addEventListener('input', paintPrice);

    /* ---- step 6: preview and publish ----------------------------------- */
    function paintPreview() {
      var km = parseNum(document.getElementById('sm-mileage').value);
      if (km) { document.getElementById('sm-prev-meta').textContent = '2026 · ' + km.toLocaleString('en-GB') + ' KM · MUNICH'; }
      var picked = chips.filter(function (c) { return c.getAttribute('aria-pressed') === 'true'; }).map(function (c) { return c.textContent.trim(); });
      var text = (words.value || '').trim();
      var first = text.split(/(?<=\.)\s/)[0] || '';
      document.getElementById('sm-prev-defects').textContent = picked.some(function (x) { return /faultless/.test(x); })
        ? 'Faultless, says the seller — checked at the viewing.' : (first || picked.join(', ') || 'No defects named yet.');
    }
    var publish = document.getElementById('sm-publish');
    var out = document.getElementById('sm-publish-out');
    var ready = document.getElementById('sm-ready');
    publish.addEventListener('click', function () {
      var problems = [];
      if (!defectsOk()) { problems.push('name the defects in step 2'); }
      if (countPhotos() < 8) { problems.push('fill all eight photo frames'); }
      if (!parseNum(price.value)) { problems.push('set an asking price'); }
      if (!parseNum(document.getElementById('sm-mileage').value)) { problems.push('enter the mileage'); }
      if (problems.length) {
        out.textContent = 'NOT YET: ' + problems.join(', ').toUpperCase();
        out.hidden = false;
        return;
      }
      out.textContent = 'PUBLISHED · LIVE FOR 60 DAYS · IT IS THE FIRST ROW ON MY LISTINGS';
      out.hidden = false;
      publish.disabled = true;
      publish.firstChild.nodeValue = 'Published ';
      state = state.map(function () { return 'done'; });
      paintRail();
      if (ready) { ready.textContent = 'READY: 6 OF 6 · PUBLISHED'; }
      saved.textContent = 'PUBLISHED · JUST NOW';
    });

    paintRail();
    paintPrice();
    paintPreview();
  }

  /* ======================================================================
     44. Dealer dashboard (dealer-dashboard.html)
     The morning queue: a button finishes the thing it names. Renew settles
     the expiring row and Print labels the dispatch row; each takes its done
     words from the row's data attributes, and the rail's sub-line follows.
     Reprice cuts the three stuck cars by the page's percentage and rewrites
     the sentence under the chart from the same three prices.
     ====================================================================== */
  function initDealer() {
    var main = document.querySelector('.ddmain');
    if (!main) { return; }

    var WORDS = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six'];
    var head = document.getElementById('dd-todo-h');

    function euro(n) { return '€' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }

    function railSub(key, text) {
      var s = document.querySelector('[data-rail="' + key + '"] .uanav__s');
      if (s) { s.textContent = text; }
    }

    function settle(row) {
      row.classList.add('ddtodo--done');
      row.querySelector('.ddtodo__t').textContent = row.getAttribute('data-done-title');
      row.querySelector('.ddtodo__p').textContent = row.getAttribute('data-done-text');
      var act = row.querySelector('[data-settle]');
      var tag = document.createElement('span');
      tag.className = 'dddone';
      tag.textContent = row.getAttribute('data-done-tag');
      act.parentNode.replaceChild(tag, act);
      tag.tabIndex = -1;
      tag.focus();
      row.querySelector('.ddtile').classList.remove('ddtile--urgent');
      if (row.getAttribute('data-rail-key')) { railSub(row.getAttribute('data-rail-key'), row.getAttribute('data-rail-sub')); }
      var left = main.querySelectorAll('.ddtodo:not(.ddtodo--done)').length;
      if (head) {
        head.textContent = left === 0 ? 'Nothing waiting. Go and sell a car.'
          : left === 1 ? 'One thing, and it will not wait.'
            : WORDS[left] + ' things, none of them will wait.';
      }
    }

    Array.prototype.forEach.call(main.querySelectorAll('[data-settle]'), function (btn) {
      btn.addEventListener('click', function () { settle(btn.closest('.ddtodo')); });
    });

    /* ---- reprice the three that stand still ------------------------- */
    var reprice = main.querySelector('[data-reprice]');
    var stuck = document.getElementById('dd-stuck');
    if (reprice && stuck) {
      reprice.addEventListener('click', function () {
        var cut = parseFloat(stuck.getAttribute('data-cut')) || 3;
        var total = 0;
        var parts = stuck.getAttribute('data-stuck').split('|').map(function (item) {
          var name = item.split(':')[0];
          var price = Math.round(parseInt(item.split(':')[1], 10) * (1 - cut / 100) / 50) * 50;
          var b = document.createElement('strong');
          total += price;
          b.textContent = name;
          return b.outerHTML + ' ' + euro(price);
        });
        stuck.innerHTML = 'Repriced this morning, ' + cut + '% off: ' + parts.join(', ') + '. Together ' + euro(total) + ', and every buyer who saved one of them gets a message.';
        stuck.tabIndex = -1;
        stuck.focus();
        reprice.disabled = true;
        reprice.firstChild.textContent = 'Repriced';
        var h = document.getElementById('dd-stock-h');
        if (h) { h.textContent = h.textContent.replace('of them stuck', 'repriced this morning'); }
      });
    }
  }

  /* ======================================================================
     45. Dealer analytics (dealer-analytics.html)
     The period and showroom selects are drawn, as in the handoff; the demo
     carries one period's figures, so picking anything else only reveals the
     note that says so. Export CSV writes the six tables as they stand on
     screen — one data-csv row per line, five columns in on-screen order.
     ====================================================================== */
  function initAnalytics() {
    var bar = document.getElementById('ax-period');
    if (!bar) { return; }

    var note = document.getElementById('ax-scope');
    var preset = document.getElementById('ax-preset');
    var site = document.getElementById('ax-site');
    var csv = document.getElementById('ax-export');

    function scope() {
      note.hidden = preset.selectedIndex === 0 && site.selectedIndex === 0;
    }
    preset.addEventListener('change', scope);
    site.addEventListener('change', scope);

    if (csv) {
      csv.addEventListener('click', function (e) {
        e.preventDefault();
        var rows = ['section,label,value,detail,more'];
        Array.prototype.forEach.call(document.querySelectorAll('[data-csv]'), function (el) { rows.push(el.getAttribute('data-csv')); });
        var blob = new Blob(['﻿' + rows.join('\r\n') + '\r\n'], { type: 'text/csv;charset=utf-8' });
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = csv.getAttribute('data-file') || 'avava-analytics.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        csv.textContent = 'EXPORTED · ' + (rows.length - 1) + ' ROWS';
        setTimeout(function () { csv.textContent = 'EXPORT CSV'; URL.revokeObjectURL(url); }, 2200);
      });
    }
  }

  /* ======================================================================
     46. Dealer listings (dealer-listings.html)
     Forty-two rows ship as JSON beside a <template> cut from the same row
     function that wrote the static first page. Tabs, search, paging and
     selection re-render from that data; every action changes the row it
     names — Reprice cuts the price and re-ranks it against its own
     comparables, Renew clears the expiry, the bulk bar acts on the
     selection and always states the money at stake first.
     ====================================================================== */
  function initDealerListings() {
    var host = document.getElementById('ls-rows');
    var dataEl = document.getElementById('ls-data');
    var tpl = document.getElementById('ls-tpl-row');
    if (!host || !dataEl || !tpl) { return; }

    var data = JSON.parse(dataEl.textContent);
    var META = data.meta;
    var rows = data.rows;
    var SIZE = META.pageSize;
    var ONES = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
    var TENS = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    var FILTERS = {
      all: function () { return true; },
      live: function (r) { return r.status === 'live'; },
      expiring: function (r) { return r.expiring; },
      over90: function (r) { return r.over90; },
      noenq: function (r) { return r.noEnq; },
      prep: function (r) { return r.status === 'preparing'; },
      reserved: function (r) { return r.status === 'reserved'; },
      nobook: function (r) { return !r.book; },
      fewphotos: function (r) { return r.photos < 8; },
      nodefects: function (r) { return !r.defects; }
    };
    var HIDDEN = { nobook: 'No service book', fewphotos: 'Under 8 photos', nodefects: 'No defects named' };
    var state = { filter: 'all', q: '', page: 1, selected: {} };
    rows.forEach(function (r) { if (r.selected) { state.selected[r.id] = true; } });

    var tabs = document.getElementById('ls-tabs');
    var search = document.getElementById('ls-q');
    var head = document.getElementById('ls-list-h');
    var bulkLine = document.getElementById('ls-bulk-line');
    var cutBtn = document.getElementById('ls-cut');
    var allBox = document.getElementById('ls-all');
    var th = document.getElementById('ls-th');
    var soldCard = document.getElementById('ls-sold');
    var noneCard = document.getElementById('ls-none');
    var pager = document.getElementById('ls-pager');
    var shownOut = document.getElementById('ls-shown');
    var totalOut = document.getElementById('ls-total');
    var pagesHost = document.getElementById('ls-pages');
    var nextBtn = pager.querySelector('[data-next]');
    var strip = document.getElementById('ls-strip');

    function word(n) {
      if (n < 20) { return ONES[n]; }
      return TENS[Math.floor(n / 10)] + (n % 10 ? '-' + ONES[n % 10] : '');
    }
    function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
    function euro(n) { return '€' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
    function ordn(n) {
      var suffix = (n % 100 >= 10 && n % 100 <= 20) ? 'TH' : ({ 1: 'ST', 2: 'ND', 3: 'RD' }[n % 10] || 'TH');
      return n + suffix;
    }
    function alive() { return rows.filter(function (r) { return !r.withdrawn; }); }

    /* ---- the row's own facts, mirrored from build_listings.py ------- */
    function market(r) {
      var prices = r.others.concat([r.price]).sort(function (a, b) { return a - b; });
      var n = prices.length, rank = prices.indexOf(r.price) + 1;
      var lo = prices[0], hi = prices[n - 1], span = hi - lo || 1;
      var q1 = prices[Math.floor(n / 4)], med = prices[Math.floor(n / 2)], q3 = prices[Math.floor(3 * n / 4)];
      var fromTop = n - rank + 1;
      r.median = med;
      r.tick = Math.round((r.price - lo) / span * 1000) / 10;
      r.bandLeft = Math.round((q1 - lo) / span * 1000) / 10;
      r.bandWidth = Math.round((q3 - q1) / span * 1000) / 10;
      r.rank = r.status === 'preparing' ? 'NOT PUBLISHED' : (fromTop <= 2 ? ordn(fromTop) + ' DEAREST OF ' + n : ordn(rank) + ' OF ' + n);
    }
    function chipFor(r) {
      if (r.status === 'reserved') { return ['RESERVED', 'reserved']; }
      if (r.status === 'preparing') { return ['IN PREPARATION', 'prep']; }
      if (r.expiring) { return ['EXPIRES IN ' + r.expiresIn + ' DAYS', 'expires']; }
      return ['LIVE', 'live'];
    }
    function flagFor(r) {
      if (r.noEnq) { return 'NO ENQUIRIES'; }
      if (r.over90) { return r.days + ' DAYS'; }
      if (r.expiring) { return 'EXPIRING'; }
      return null;
    }
    function actionFor(r) {
      if (r.repriced) { return { label: 'Repriced', kind: 'done', type: 'done' }; }
      if (r.status === 'preparing') { return { label: 'Finish and publish', kind: 'out', type: 'link', href: META.links.sell }; }
      if (r.status === 'reserved') { return { label: 'Open the order', kind: 'out', type: 'link', href: META.links.order }; }
      if (r.over90 || r.noEnq) { return { label: 'Reprice', kind: 'ink', type: 'button', key: 'reprice' }; }
      if (r.expiring) { return { label: 'Renew ' + META.listingDays + ' days', kind: 'out', type: 'button', key: 'renew' }; }
      return { label: 'Open', kind: 'out', type: 'link', href: r.enquiries ? META.links.messages : META.links.single };
    }
    function photoHTML(r) {
      if (r.photo) { return '<img class="lsph__img" src="' + r.photo + '" alt="" width="1200" height="750" loading="lazy">'; }
      return '<span class="lsph__mark" aria-hidden="true">' + r.mark + '</span>';
    }
    function chipHTML(r) { var c = chipFor(r); return '<span class="lschip lschip--' + c[1] + '">' + c[0] + '</span>'; }
    function flagHTML(r) { var f = flagFor(r); return f ? '<span class="lsflag">' + f + '</span>' : ''; }
    function sparkHTML(r) { return r.spark.map(function (h) { return '<span class="lsspark__b lsspark__b--' + h + '"></span>'; }).join(''); }
    function actionHTML(r) {
      var a = actionFor(r);
      if (a.type === 'button') { return '<button class="lsact lsact--' + a.kind + '" type="button" data-act="' + a.key + '">' + a.label + '</button>'; }
      if (a.type === 'done') { return '<span class="lsact lsact--done">' + a.label + '</span>'; }
      return '<a class="lsact lsact--' + a.kind + '" href="' + a.href + '">' + a.label + '</a>';
    }
    function rowHTML(r, last) {
      var vals = {
        id: r.id, name: r.name, spec: r.spec, stockLabel: r.stockLabel, siteLabel: META.sites[r.site], viewsLabel: r.viewsLabel,
        enquiries: r.enquiries, days: r.days, daysCls: r.daysRust ? ' lsmeta--rust' : '', priceLabel: euro(r.price), rank: r.rank,
        tick: r.tick, bandLeft: r.bandLeft, bandWidth: r.bandWidth, note: r.note, view: r.view, edit: r.edit,
        problem: r.problem ? ' lsrow--problem' : '', checked: state.selected[r.id] ? ' checked' : '',
        photo: photoHTML(r), chip: chipHTML(r), flag: flagHTML(r), spark: sparkHTML(r), action: actionHTML(r)
      };
      var html = tpl.innerHTML.replace(/\{\{(\w+)\}\}/g, function (_, k) { return vals[k] === undefined ? '' : String(vals[k]); });
      return last ? html.replace('class="lsrow', 'class="lsrow lsrow--last') : html;
    }

    /* ---- the list ---------------------------------------------------- */
    function matches(r) {
      if (!state.q) { return true; }
      var q = state.q.toLowerCase();
      return r.q.indexOf(q) >= 0 || String(r.id).indexOf(q) >= 0 || r.vin.toLowerCase().indexOf(q) >= 0 || r.family.toLowerCase().indexOf(q) >= 0;
    }
    function visible() {
      if (state.filter === 'sold') { return []; }
      return alive().filter(FILTERS[state.filter] || FILTERS.all).filter(matches);
    }
    function paintBands(scope) {
      Array.prototype.forEach.call(scope.querySelectorAll('.lsband'), function (b) {
        b.style.setProperty('--at', Math.max(0, Math.min(100, parseFloat(b.getAttribute('data-at')))) + '%');
        b.style.setProperty('--l', parseFloat(b.getAttribute('data-left')) + '%');
        b.style.setProperty('--r', (parseFloat(b.getAttribute('data-left')) + parseFloat(b.getAttribute('data-width'))) + '%');
      });
    }
    function counts() {
      var live = alive();
      Array.prototype.forEach.call(tabs.querySelectorAll('[data-tab]'), function (b) {
        var key = b.getAttribute('data-tab');
        var n = key === 'sold' ? META.sold : live.filter(FILTERS[key] || FILTERS.all).length;
        b.querySelector('.lstab__n').textContent = n;
      });
      var nLive = live.filter(FILTERS.live).length, nPrep = live.filter(FILTERS.prep).length, nRes = live.filter(FILTERS.reserved).length;
      strip.textContent = live.length + ' CARS · ' + nLive + ' LIVE · ' + nPrep + ' IN PREPARATION · ' + nRes + ' RESERVED';
    }
    function bulk(pageRows) {
      var ids = Object.keys(state.selected);
      var picked = alive().filter(function (r) { return state.selected[r.id]; });
      var total = 0, stuck = 0;
      picked.forEach(function (r) { total += r.price; if (r.over90) { stuck++; } });
      if (!picked.length) {
        bulkLine.textContent = META.bulkEmpty;
      } else {
        bulkLine.textContent = picked.length + ' SELECTED · ' + euro(total) + (stuck === picked.length ? ' STANDING STILL' : ' ON THE FLOOR');
        wrapMoney(bulkLine);
      }
      cutBtn.textContent = 'Cut ' + META.cut + '% on ' + (picked.length === 1 ? 'this one' : picked.length === 2 ? 'both' : 'all ' + word(picked.length));
      Array.prototype.forEach.call(document.querySelectorAll('[data-bulk]'), function (b) { b.disabled = !picked.length; });
      var onPage = pageRows.filter(function (r) { return state.selected[r.id]; }).length;
      allBox.checked = pageRows.length > 0 && onPage === pageRows.length;
      allBox.indeterminate = onPage > 0 && onPage < pageRows.length;
      return ids.length;
    }
    function render() {
      var list = visible();
      var pages = Math.max(1, Math.ceil(list.length / SIZE));
      if (state.page > pages) { state.page = pages; }
      var pageRows = list.slice((state.page - 1) * SIZE, state.page * SIZE);
      host.innerHTML = pageRows.map(function (r, i) { return rowHTML(r, i === pageRows.length - 1); }).join('');
      paintBands(host);
      wrapMoney(host);
      var sold = state.filter === 'sold';
      soldCard.hidden = !sold;
      noneCard.hidden = sold || list.length > 0;
      th.hidden = sold || !list.length;
      pager.hidden = sold || !list.length;
      var stuck = pageRows.filter(function (r) { return r.over90; }).length;
      head.textContent = sold ? word(META.sold).replace(/^./, function (c) { return c.toUpperCase(); }) + ' cars sold, none of them here.'
        : !pageRows.length ? 'Nothing to decide on this page.'
          : cap(word(pageRows.length)) + (pageRows.length === 1 ? ' car, ' : ' cars, ') + (stuck ? word(stuck) + ' of ' + (pageRows.length === 1 ? 'it' : 'them') + ' stuck.' : 'none of them stuck.');
      shownOut.textContent = pageRows.length;
      totalOut.textContent = list.length;
      var html = '';
      for (var n = 1; n <= pages; n++) {
        html += '<button class="lspage' + (n === state.page ? ' lspage--on' : '') + '" type="button" data-page="' + n + '"' + (n === state.page ? ' aria-current="page"' : '') + '>' + n + '</button>';
      }
      pagesHost.innerHTML = html;
      nextBtn.disabled = state.page >= pages;
      Array.prototype.forEach.call(tabs.querySelectorAll('[data-tab]'), function (b) {
        var on = b.getAttribute('data-tab') === state.filter;
        b.classList.toggle('lstab--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      counts();
      bulk(pageRows);
    }

    /* ---- filters, search, paging ------------------------------------ */
    function setFilter(key) {
      if (HIDDEN[key] && !tabs.querySelector('[data-tab="' + key + '"]')) {
        var b = document.createElement('button');
        b.className = 'lstab';
        b.type = 'button';
        b.setAttribute('data-tab', key);
        b.setAttribute('aria-pressed', 'false');
        b.innerHTML = HIDDEN[key] + '<span class="lstab__n">0</span>';
        tabs.appendChild(b);
      }
      state.filter = key;
      state.page = 1;
      render();
    }
    tabs.addEventListener('click', function (e) {
      var b = e.target.closest('[data-tab]');
      if (b) { setFilter(b.getAttribute('data-tab')); }
    });
    var timer = null;
    search.addEventListener('input', function () {
      clearTimeout(timer);
      timer = setTimeout(function () { state.q = search.value.trim(); state.page = 1; render(); }, 140);
    });
    pager.addEventListener('click', function (e) {
      var b = e.target.closest('[data-page]');
      if (b) { state.page = parseInt(b.getAttribute('data-page'), 10); }
      else if (e.target.closest('[data-next]')) { state.page += 1; }
      else { return; }
      render();
      host.scrollIntoView({ block: 'start', behavior: 'smooth' });
      var on = pagesHost.querySelector('.lspage--on');
      if (on) { on.focus(); }
    });
    function fromHash() {
      var h = (location.hash || '').replace(/^#/, '');
      if (!h) { return; }
      if (h.indexOf('q=') === 0) {
        var wanted = '';
        try { wanted = decodeURIComponent(h.slice(2)); } catch (err) { wanted = ''; }
        search.value = wanted;
        state.q = search.value.trim();
        state.filter = 'all';
        state.page = 1;
        render();
      } else if (FILTERS[h] || h === 'sold') {
        setFilter(h);
      }
    }
    window.addEventListener('hashchange', fromHash);
    Array.prototype.forEach.call(document.querySelectorAll('[data-preset]'), function (a) {
      a.addEventListener('click', function (e) {
        e.preventDefault();
        setFilter(a.getAttribute('data-preset'));
        tabs.scrollIntoView({ block: 'start', behavior: 'smooth' });
      });
    });

    /* ---- selection --------------------------------------------------- */
    host.addEventListener('change', function (e) {
      var box = e.target.closest('[data-pick]');
      if (!box) { return; }
      var id = box.closest('[data-row]').getAttribute('data-row');
      if (box.checked) { state.selected[id] = true; } else { delete state.selected[id]; }
      bulk(visible().slice((state.page - 1) * SIZE, state.page * SIZE));
    });
    allBox.addEventListener('change', function () {
      var pageRows = visible().slice((state.page - 1) * SIZE, state.page * SIZE);
      pageRows.forEach(function (r) { if (allBox.checked) { state.selected[r.id] = true; } else { delete state.selected[r.id]; } });
      render();
    });

    /* ---- actions ----------------------------------------------------- */
    function byId(id) { return rows.filter(function (r) { return String(r.id) === String(id); })[0]; }
    function reprice(r) {
      if (r.repriced || r.status !== 'live') { return; }
      var was = r.price;
      r.price = Math.round(r.price * (1 - META.cut / 100) / 50) * 50;
      market(r);
      r.repriced = true;
      r.note = 'Cut ' + META.cut + '% this morning — was ' + euro(was) + '. Every buyer who saved it gets a message.';
    }
    function renew(r) {
      if (!r.expiring) { return; }
      r.expiring = false;
      r.expiresIn = META.listingDays;
      if (!r.over90 && !r.noEnq) { r.note = 'Renewed this morning — live for another ' + word(META.listingDays) + ' days.'; }
    }
    host.addEventListener('click', function (e) {
      var b = e.target.closest('[data-act]');
      if (!b) { return; }
      var id = b.closest('[data-row]').getAttribute('data-row');
      var r = byId(id);
      if (b.getAttribute('data-act') === 'reprice') { reprice(r); }
      if (b.getAttribute('data-act') === 'renew') { renew(r); }
      render();
      var again = host.querySelector('[data-row="' + id + '"] .lsact') || head;
      if (again.tabIndex < 0) { again.tabIndex = -1; }
      again.focus();
    });
    document.getElementById('ls-bulk').addEventListener('click', function (e) {
      var b = e.target.closest('[data-bulk]');
      if (!b) { return; }
      var key = b.getAttribute('data-bulk');
      var picked = alive().filter(function (r) { return state.selected[r.id]; });
      picked.forEach(function (r) {
        if (key === 'cut') { reprice(r); }
        if (key === 'renew') { renew(r); }
        if (key === 'move') { r.site = r.site === 'zurich' ? 'munich' : 'zurich'; }
        if (key === 'withdraw') { r.withdrawn = true; delete state.selected[r.id]; }
      });
      render();
      bulkLine.tabIndex = -1;
      bulkLine.focus();
    });

    paintBands(host);
    fromHash();
    bulk(visible().slice((state.page - 1) * SIZE, state.page * SIZE));
  }

  /* ======================================================================
     47. Dealer enquiries (dealer-enquiries.html)
     Eleven enquiries ship as JSON beside a <template> cut from the same row
     function that wrote the static seven. Tabs, sort, the showroom filter
     and the selection re-render from that data; the dark bar always states
     the money in the conversation before it offers an action, and a one-tap
     reply marks itself used and settles the row it belongs to.
     ====================================================================== */
  function initEnquiries() {
    var host = document.getElementById('eq-rows');
    var dataEl = document.getElementById('eq-data');
    var tpl = document.getElementById('eq-tpl-row');
    if (!host || !dataEl || !tpl) { return; }

    var data = JSON.parse(dataEl.textContent);
    var META = data.meta;
    var rows = data.rows;
    var ONES = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven'];
    var FILTERS = {
      all: function () { return true; },
      waiting: function (r) { return r.urgent; },
      viewing: function (r) { return r.intent === 'viewing'; },
      finance: function (r) { return r.intent === 'finance'; },
      exchange: function (r) { return r.intent === 'exchange'; },
      offer: function (r) { return r.intent === 'offer'; }
    };
    var state = { tab: 'all', site: 'all', sort: 'waiting', all: false, selected: {} };
    rows.forEach(function (r) { if (r.selected) { state.selected[r.id] = true; } });

    var tabs = document.getElementById('eq-tabs');
    var siteSel = document.getElementById('eq-site');
    var sortSel = document.getElementById('eq-sort');
    var head = document.getElementById('eq-queue-h');
    var status = document.getElementById('eq-status');
    var bulkLine = document.getElementById('eq-bulk-line');
    var answerBtn = document.getElementById('eq-answer');
    var moreBtn = document.getElementById('eq-more');
    var th = document.getElementById('eq-th');
    var closedCard = document.getElementById('eq-closed');
    var noneCard = document.getElementById('eq-none');

    function word(n) { return ONES[n] || String(n); }
    function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
    function euro(n) { return '€' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
    function live() { return rows.filter(function (r) { return !r.done; }); }

    /* ---- the row, mirrored from build_enquiries.py -------------------- */
    function avatarHTML(r) {
      if (r.photo) { return '<img class="eqava__img" src="' + r.photo + '" alt="" width="144" height="144">'; }
      return '<span class="eqava__mark" aria-hidden="true">' + r.mark + '</span>';
    }
    function carHTML(r) {
      if (r.carPhoto) { return '<img class="eqcar__img" src="' + r.carPhoto + '" alt="" width="1200" height="750" loading="lazy">'; }
      return '<span class="eqcar__mark" aria-hidden="true">' + r.carMark + '</span>';
    }
    function signalsHTML(r) {
      return r.signals.map(function (s) {
        return '<span class="eqsig' + (META.strong.indexOf(s) >= 0 ? ' eqsig--strong' : '') + '">' + s + '</span>';
      }).join('');
    }
    function slaHTML(r) {
      if (!r.urgent) { return ''; }
      return '<span class="eqsla eqsla--' + r.sla + ' eqsla--w' + r.fill + '" role="img" aria-label="Waiting ' + r.when.toLowerCase() + '">'
        + '<span class="eqsla__fill"></span></span><span class="eqsla__t">' + r.slaLine + '</span>';
    }
    function quickHTML(r) {
      if (!r.quick || !r.quick.length) { return ''; }
      var chips = r.quick.map(function (q) { return '<button class="eqtap" type="button" data-tap>' + q + '</button>'; }).join('');
      return '<p class="eqtaps"><span class="eqmeta eqtaps__k">ONE TAP:</span>' + chips + '</p>';
    }
    function actionHTML(r) {
      var a = r.action;
      if (a.kind === 'done') { return '<span class="eqact eqact--done">' + a.label + '</span>'; }
      var disc = a.kind === 'ink' ? '<span class="eqgo__disc"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span>' : '';
      return '<a class="eqact eqact--' + a.kind + '" href="' + a.href + '">' + a.label + disc + '</a>';
    }
    var CLOCK_ICON = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="8"></circle><path d="M12 8v4l3 2"></path></svg>';

    function rowHTML(r, last) {
      var vals = {
        id: r.id, name: r.name, who: r.who, intentLabel: r.intentLabel, when: r.when, line: r.line,
        rank: r.rank, why: r.why, carName: r.carName, priceLabel: euro(r.price), carFact: r.carFact,
        readTitle: r.readTitle, readNote: r.readNote, staff: r.staff, staffPhoto: r.staffPhoto,
        urgent: r.urgent ? ' eqrow--urgent' : '', checked: state.selected[r.id] ? ' checked' : '',
        clockCls: r.urgent ? ' eqwhen--late' : '', clockIcon: r.urgent ? CLOCK_ICON : '',
        photo: avatarHTML(r), signals: signalsHTML(r), slaBlock: slaHTML(r), carPhoto: carHTML(r),
        action: actionHTML(r), quickBlock: quickHTML(r)
      };
      var html = tpl.innerHTML.replace(/\{\{(\w+)\}\}/g, function (_, k) { return vals[k] === undefined ? '' : String(vals[k]); });
      return last ? html.replace('class="eqrow', 'class="eqrow eqrow--last') : html;
    }

    /* ---- the list ---------------------------------------------------- */
    function visible() {
      if (state.tab === 'closed') { return []; }
      var list = live().filter(FILTERS[state.tab] || FILTERS.all);
      if (state.sort === 'newest') { list = list.slice().reverse(); }
      if (state.sort === 'car') { list = list.slice().sort(function (a, b) { return a.carName.localeCompare(b.carName); }); }
      if (state.sort === 'intent') { list = list.slice().sort(function (a, b) { return a.intentLabel.localeCompare(b.intentLabel); }); }
      return list;
    }
    function counts() {
      var list = live();
      Array.prototype.forEach.call(tabs.querySelectorAll('[data-tab]'), function (b) {
        var key = b.getAttribute('data-tab');
        var n = key === 'closed' ? META.closed : list.filter(FILTERS[key] || FILTERS.all).length;
        b.querySelector('.eqtab__n').textContent = n;
      });
      var waiting = list.filter(FILTERS.waiting);
      var oldest = waiting.slice().sort(function (a, b) { return b.fill - a.fill; })[0];
      status.textContent = list.length + ' OPEN · ' + waiting.length + ' WAITING ON YOU'
        + (oldest ? ' · OLDEST ' + oldest.when.replace('WAITING ', '') : ' · NOBODY WAITING');
      return waiting;
    }
    function bulk() {
      var picked = live().filter(function (r) { return state.selected[r.id]; });
      var total = 0, waiting = 0;
      picked.forEach(function (r) { total += r.price; if (r.urgent) { waiting++; } });
      if (!picked.length) {
        bulkLine.textContent = META.bulkEmpty;
      } else {
        bulkLine.textContent = (waiting === picked.length ? picked.length + ' WAITING' : picked.length + ' SELECTED')
          + ' · ' + euro(total) + ' OF CARS IN PLAY';
        wrapMoney(bulkLine);
      }
      answerBtn.textContent = picked.length === 1 ? 'Answer this one'
        : picked.length === 2 ? 'Answer both'
          : picked.length ? 'Answer all ' + word(picked.length) : META.answerAll;
      Array.prototype.forEach.call(document.querySelectorAll('[data-bulk]'), function (b) { b.disabled = !picked.length; });
    }
    function render() {
      var list = visible();
      var shown = (state.tab === 'all' && !state.all) ? list.slice(0, META.shown) : list;
      host.innerHTML = shown.map(function (r, i) { return rowHTML(r, i === shown.length - 1); }).join('');
      wrapMoney(host);
      var closed = state.tab === 'closed';
      closedCard.hidden = !closed;
      noneCard.hidden = closed || list.length > 0;
      th.hidden = closed || !list.length;
      moreBtn.hidden = state.tab !== 'all' || list.length <= META.shown;
      moreBtn.textContent = state.all ? META.less : META.more;
      var waiting = counts();
      var n = shown.filter(function (r) { return r.urgent; }).length;
      head.textContent = closed ? META.closed + ' threads, all of them finished.'
        : !list.length ? 'Nothing in this state.'
          : waiting.length === 0 ? 'Nobody is waiting on you.'
            : n === 0 ? cap(word(waiting.length)) + ' waiting, none of them here.'
              : cap(word(n)) + (n === 1 ? ' person is' : ' people are') + ' waiting right now.';
      Array.prototype.forEach.call(tabs.querySelectorAll('[data-tab]'), function (b) {
        var on = b.getAttribute('data-tab') === state.tab;
        b.classList.toggle('eqtab--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      bulk();
    }

    tabs.addEventListener('click', function (e) {
      var b = e.target.closest('[data-tab]');
      if (!b) { return; }
      state.tab = b.getAttribute('data-tab');
      state.all = false;
      render();
    });
    sortSel.addEventListener('change', function () { state.sort = sortSel.value; render(); });
    siteSel.addEventListener('change', function () { state.site = siteSel.value; render(); });
    moreBtn.addEventListener('click', function () { state.all = !state.all; render(); moreBtn.focus(); });

    /* ---- selection and the bulk actions ------------------------------ */
    host.addEventListener('change', function (e) {
      var box = e.target.closest('[data-pick]');
      if (!box) { return; }
      var id = box.closest('[data-row]').getAttribute('data-row');
      if (box.checked) { state.selected[id] = true; } else { delete state.selected[id]; }
      bulk();
    });

    function byId(id) { return rows.filter(function (r) { return r.id === id; })[0]; }
    function settle(r, note) {
      r.urgent = false;
      r.sla = null;
      r.done = false;
      r.when = 'ANSWERED JUST NOW';
      r.rank = '&mdash;';
      r.line = r.line;
      r.readNote = note;
      r.quick = [];
      r.action = { label: 'Open the thread', kind: 'out', href: META.links.messages };
      r.selected = false;
      delete state.selected[r.id];
    }

    host.addEventListener('click', function (e) {
      var chip = e.target.closest('[data-tap]');
      if (!chip) { return; }
      var row = chip.closest('[data-row]');
      var r = byId(row.getAttribute('data-row'));
      settle(r, 'You sent: “' + chip.textContent + '”. The thread is open in the inbox.');
      render();
      head.focus();
    });

    document.getElementById('eq-bulk').addEventListener('click', function (e) {
      var b = e.target.closest('[data-bulk]');
      if (!b) { return; }
      var key = b.getAttribute('data-bulk');
      var picked = live().filter(function (r) { return state.selected[r.id]; });
      picked.forEach(function (r) {
        if (key === 'answer') { settle(r, 'Answered from the queue. The thread is open in the inbox.'); }
        if (key === 'assign') { r.staff = 'NADIA'; r.staffPhoto = 'assets/img/avatar-sofia.webp'; }
        if (key === 'slots') { settle(r, 'Saturday 10:00 and 14:00 offered. The booking writes itself when they pick one.'); }
      });
      render();
      bulkLine.tabIndex = -1;
      bulkLine.focus();
    });

    Array.prototype.forEach.call(document.querySelectorAll('[data-saved]'), function (b) {
      b.addEventListener('click', function () {
        b.textContent = 'Copied — edit it in the thread';
        setTimeout(function () { b.textContent = 'Use and edit'; }, 2400);
      });
    });

    head.tabIndex = -1;
    bulk();
    moreBtn.hidden = false;
  }

  /* ======================================================================
     48. Dealer bookings (dealer-bookings.html)
     The day is static; the new-appointment panel is a real form. The type
     drives the duration and the checklist, the day drives the slots, and
     the summary line is rebuilt from the actual choice — so Confirm books
     what the panel says. A booking on another day is stated as such: the
     strip, a JUST BOOKED row under the header, and the counters that move.
     ====================================================================== */
  function initDealerBookings() {
    var panel = document.getElementById('db-panel');
    var dataEl = document.getElementById('db-data');
    if (!panel || !dataEl) { return; }

    var D = JSON.parse(dataEl.textContent);
    var openBtn = document.getElementById('db-open');
    var done = document.getElementById('db-done');
    var rowsHost = document.getElementById('db-rows');
    var slotsHost = document.getElementById('db-slots');
    var daysHost = document.getElementById('db-days');
    var prepsHost = document.getElementById('db-preps');
    var sum = document.getElementById('db-sum');
    var sumMeta = document.getElementById('db-sum-m');
    var whenHelp = document.getElementById('db-when-help');
    var prepMeta = document.getElementById('db-prep-meta');
    var carSel = document.getElementById('db-car');
    var buyerSel = document.getElementById('db-buyer');
    var countOut = document.getElementById('db-count');
    var tabs = document.getElementById('db-tabs');

    var TODAY_I = D.todayI;
    var state = { type: D.types[0], day: D.booking.dayShort, dayI: null, slot: 1, staff: D.booking.staff };
    D.days.forEach(function (d) { if (d.long === D.booking.dayShort) { state.dayI = d.i; } });

    function esc(s) { return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;'); }
    function dayOf(i) { return D.days.filter(function (d) { return d.i === i; })[0]; }

    /* ---- the panel rebuilds what it can actually offer ---------------- */
    function paintSlots() {
      var d = dayOf(state.dayI);
      var free = d.slots.filter(function (s) { return !s.taken; });
      if (!free.length) { state.slot = 0; }
      else if (d.slots[state.slot].taken) { state.slot = d.slots.indexOf(free[0]); }
      slotsHost.innerHTML = d.slots.map(function (s, i) {
        return '<button class="dbslot' + (i === state.slot && !s.taken ? ' dbslot--on' : '') + '" type="button" data-slot="' + i + '"'
          + (s.taken ? ' disabled' : '') + '><span class="dbslot__t">' + s.time + '</span><span class="dbslot__b">'
          + (s.taken ? 'TAKEN' : s.bay) + '</span></button>';
      }).join('');
    }
    function paintPrep() {
      prepsHost.innerHTML = state.type.on.map(function (l) { return prepItem(l, true); })
        .concat(state.type.off.map(function (l) { return prepItem(l, false); })).join('');
      prepMeta.textContent = 'PRE-FILLED FOR A ' + state.type.label.toUpperCase();
    }
    function prepItem(label, on) {
      return '<label class="dbprep"><input class="visually-hidden dbprep__in" type="checkbox"' + (on ? ' checked' : '')
        + '><span class="dbprep__box" aria-hidden="true"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12l4 4 10-11"></path></svg></span><span class="dbprep__t">'
        + esc(label) + '</span></label>';
    }
    function paintSummary() {
      var d = dayOf(state.dayI);
      var slot = d.slots[state.slot];
      var car = carSel.options[carSel.selectedIndex].textContent.split('·')[1];
      var buyer = buyerSel.options[buyerSel.selectedIndex].textContent.split('·')[0].trim();
      var bay = slot ? (slot.lift ? 'bay 2' : 'bay 1') : 'no free bay';
      sum.textContent = state.type.label + ' · ' + d.long + ' ' + d.date + ' ' + d.month
        + (slot ? ', ' + slot.time : '') + ' · ' + D.site + ', ' + bay;
      sumMeta.textContent = [(car || 'No car').trim().toUpperCase(), buyer.toUpperCase(),
        (state.staff || 'unassigned').toUpperCase(), state.type.mins + ' MIN'].join(' · ');
      var free = d.slots.filter(function (x) { return !x.taken; }).length;
      whenHelp.textContent = free
        ? d.long + ' has ' + free + (free === 1 ? ' free slot' : ' free slots') + ' in ' + D.site + '.'
        : d.long + ' is full in ' + D.site + ' — pick another day.';
    }
    function paint() { paintSlots(); paintPrep(); paintSummary(); }

    panel.addEventListener('click', function (e) {
      var t = e.target.closest('[data-type]');
      if (t) {
        state.type = D.types.filter(function (x) { return x.key === t.getAttribute('data-type'); })[0];
        Array.prototype.forEach.call(panel.querySelectorAll('[data-type]'), function (c) { c.classList.toggle('dbtype--on', c === t); });
        paint();
        return;
      }
      var d = e.target.closest('[data-day]');
      if (d && !d.disabled) {
        state.dayI = parseInt(d.getAttribute('data-day'), 10);
        Array.prototype.forEach.call(daysHost.querySelectorAll('[data-day]'), function (c) { c.classList.toggle('dbday--on', c === d); });
        paint();
        return;
      }
      var s = e.target.closest('[data-slot]');
      if (s && !s.disabled) {
        state.slot = parseInt(s.getAttribute('data-slot'), 10);
        paintSlots();
        paintSummary();
        return;
      }
      var p = e.target.closest('[data-staff]');
      if (p && !p.disabled) {
        state.staff = p.getAttribute('data-staff');
        Array.prototype.forEach.call(panel.querySelectorAll('[data-staff]'), function (c) { c.classList.toggle('dbpill--on', c === p); });
        paintSummary();
      }
    });
    panel.addEventListener('change', function (e) {
      if (e.target === carSel || e.target === buyerSel) { paintSummary(); }
    });

    /* ---- open, close, confirm ---------------------------------------- */
    function open() {
      panel.hidden = false;
      paint();
      panel.scrollIntoView({ block: 'start', behavior: 'smooth' });
      panel.querySelector('[data-type]').focus();
    }
    function close() {
      panel.hidden = true;
      openBtn.focus();
    }
    openBtn.addEventListener('click', open);
    Array.prototype.forEach.call(panel.querySelectorAll('[data-close]'), function (b) { b.addEventListener('click', close); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !panel.hidden) { close(); }
    });

    document.getElementById('db-confirm').addEventListener('click', function () {
      var d = dayOf(state.dayI);
      var slot = d.slots[state.slot];
      if (!slot || slot.taken) { return; }
      var sameDay = d.i === TODAY_I;
      panel.hidden = true;
      done.hidden = false;
      document.getElementById('db-done-t').textContent = 'Booked: ' + state.type.label.toLowerCase() + ' ' + d.long + ' '
        + d.date + ' ' + d.month + ', ' + slot.time + ' · ' + D.site + ', ' + (slot.lift ? 'bay 2' : 'bay 1');
      document.getElementById('db-done-m').textContent = sumMeta.textContent.split(' · ').slice(1, 3).join(' · ')
        + ' · THREAD ANSWERED';
      done.scrollIntoView({ block: 'center', behavior: 'smooth' });

      var row = rowsHost.querySelector('[data-row="just"]');
      if (!row) {
        row = document.createElement('div');
        row.className = 'dbrow dbrow--just';
        row.setAttribute('data-row', 'just');
        rowsHost.insertBefore(row, rowsHost.firstChild);
      }
      row.innerHTML = '<span class="dbtime"><span class="dbtime__t">' + slot.time + '</span><span class="dbmeta dbtime__d">'
        + state.type.mins + ' MIN · ' + D.site.toUpperCase() + '</span></span>'
        + '<span class="dbdot" aria-hidden="true"><span class="dbdot__i"></span></span>'
        + '<span class="dbwhat"><span class="dbwhat__chips"><span class="dbkind dbkind--tradein">JUST BOOKED</span></span>'
        + '<span class="dbwhat__t">' + esc(sum.textContent) + '</span>'
        + '<span class="dbwhat__p">' + (sameDay ? 'Today, in its place above once the day refreshes.'
          : 'Not today — ' + d.long + ' ' + d.date + ' ' + d.month + ' at ' + slot.time + ', in ' + D.site + '.') + '</span></span>'
        + '<span class="dbwho"></span><span class="dbready"></span>'
        + '<span class="dbrun"><a class="dbact dbact--out" href="' + D.links.messages + '">See ' + d.long + '</a></span>';

      var n = parseInt(countOut.getAttribute('data-n') || String(parseInt(countOut.textContent, 10)), 10);
      countOut.setAttribute('data-n', n);
      countOut.textContent = sameDay ? (n + 1) + ' APPOINTMENTS' : n + ' APPOINTMENTS + 1 ON ' + d.long.toUpperCase();
      var weekTab = tabs.querySelector('[data-tab-n="week"]');
      weekTab.textContent = parseInt(weekTab.textContent, 10) + 1;
      var todayTab = tabs.querySelector('[data-tab-n="today"]');
      if (sameDay) { todayTab.textContent = parseInt(todayTab.textContent, 10) + 1; }
    });

    document.getElementById('db-done-x').addEventListener('click', function () {
      done.hidden = true;
      openBtn.focus();
    });
  }

  /* ======================================================================
     49. Dealer orders (dealer-orders.html)
     The eight tabs filter the seven rows by their tags, and everything the
     page says about the filtered set follows: the headline, the noun in
     the pager, the count, the page numbers (hidden while a filter is on)
     and the empty state. The selection is described rather than
     summarised, so the label button can say what it can actually do.
     ====================================================================== */
  function initDealerOrders() {
    var host = document.getElementById('dor-rows');
    var dataEl = document.getElementById('dor-data');
    if (!host || !dataEl) { return; }

    var D = JSON.parse(dataEl.textContent);
    var rows = Array.prototype.slice.call(host.querySelectorAll('[data-row]'));
    var byNo = {};
    D.rows.forEach(function (r) { byNo[r.no] = r; });

    var tabs = document.getElementById('dor-tabs');
    var head = document.getElementById('dor-list-h');
    var search = document.getElementById('dor-q');
    var empty = document.getElementById('dor-empty');
    var pages = document.getElementById('dor-pages');
    var shownOut = document.getElementById('dor-shown');
    var totalOut = document.getElementById('dor-total');
    var nounOut = document.getElementById('dor-noun');
    var bulkLine = document.getElementById('dor-bulk-line');
    var labelBtn = document.getElementById('dor-label');
    var messageBtn = document.getElementById('dor-message');
    var state = { tab: 'all', q: '', selected: {} };
    rows.forEach(function (el) {
      var box = el.querySelector('[data-pick]');
      if (box.checked) { state.selected[el.getAttribute('data-row')] = true; }
    });

    var ONES = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    function word(n) { return ONES[n] || String(n); }
    function euro(n) { return '€' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }

    function matches(el) {
      var no = el.getAttribute('data-row');
      var tags = el.getAttribute('data-tags').split(' ');
      if (state.tab !== 'all' && tags.indexOf(state.tab) < 0) { return false; }
      if (state.q && byNo[no].q.indexOf(state.q.toLowerCase()) < 0) { return false; }
      return true;
    }

    function describe(nos) {
      var van = 0, desk = 0, stuck = 0, rest = 0;
      nos.forEach(function (no) {
        var r = byNo[no];
        if (r.shippable) { van++; }
        else if (r.today && r.car) { desk++; }
        else if (r.late) { stuck++; }
        else { rest++; }
      });
      var bits = [];
      if (van) { bits.push(van + ' ON THE ' + D.courier + ' VAN'); }
      if (desk) { bits.push(desk + ' AT THE DESK'); }
      if (stuck) { bits.push(stuck + ' STUCK IN ITALY'); }
      if (rest) { bits.push(rest + ' WAITING'); }
      return bits.join(', ');
    }

    function bulk() {
      var nos = Object.keys(state.selected);
      var total = 0, labels = 0;
      nos.forEach(function (no) { total += byNo[no].total; if (byNo[no].shippable) { labels++; } });
      if (!nos.length) {
        bulkLine.textContent = D.bulkEmpty.replace(/&middot;/g, '·');
      } else {
        bulkLine.textContent = nos.length + ' SELECTED · ' + euro(total) + ' · ' + describe(nos);
      }
      labelBtn.textContent = 'Print ' + labels + (labels === 1 ? ' label' : ' labels');
      labelBtn.disabled = !labels;
      messageBtn.textContent = nos.length === 1 ? 'Message the buyer' : 'Message all ' + word(nos.length);
      Array.prototype.forEach.call(document.querySelectorAll('[data-bulk="dispatch"], [data-bulk="message"]'), function (b) {
        b.disabled = !nos.length;
      });
    }

    function render() {
      var shown = 0;
      rows.forEach(function (el) {
        var on = matches(el);
        el.hidden = !on;
        if (on) { shown++; }
      });
      rows.forEach(function (el) { el.classList.remove('dorrow--last'); });
      var visible = rows.filter(function (el) { return !el.hidden; });
      if (visible.length) { visible[visible.length - 1].classList.add('dorrow--last'); }
      var filtered = state.tab !== 'all' || state.q;
      var total = state.q ? shown : D.counts[state.tab];
      empty.hidden = shown > 0;
      pages.hidden = filtered;
      shownOut.textContent = shown;
      totalOut.textContent = total;
      nounOut.textContent = state.q ? (shown === 1 ? 'matching order' : 'matching orders') : D.nouns[state.tab];
      head.textContent = state.q
        ? (shown ? (shown === 1 ? 'One order matches.' : word(shown).charAt(0).toUpperCase() + word(shown).slice(1) + ' orders match.') : 'Nothing matches that.')
        : D.headlines[state.tab].replace(/&middot;/g, '·').replace(/&mdash;/g, '—');
      Array.prototype.forEach.call(tabs.querySelectorAll('[data-tab]'), function (b) {
        var on = b.getAttribute('data-tab') === state.tab;
        b.classList.toggle('dortab--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      bulk();
    }

    tabs.addEventListener('click', function (e) {
      var b = e.target.closest('[data-tab]');
      if (!b) { return; }
      state.tab = b.getAttribute('data-tab');
      render();
    });
    var timer = null;
    search.addEventListener('input', function () {
      clearTimeout(timer);
      timer = setTimeout(function () { state.q = search.value.trim(); render(); }, 140);
    });
    host.addEventListener('change', function (e) {
      var box = e.target.closest('[data-pick]');
      if (!box) { return; }
      var no = box.closest('[data-row]').getAttribute('data-row');
      if (box.checked) { state.selected[no] = true; } else { delete state.selected[no]; }
      bulk();
    });

    bulk();
  }

  /* ======================================================================
     50. Dealer payouts (dealer-payouts.html)
     The account behaviour: Add an account opens the form and confirming it
     shows the €1 strip; Send payouts here moves the default — the ink card,
     the accent badge and the payout selector all follow, and the old
     default gets its own button back. Finish verifying deliberately does
     not make an unverified account the default: it says so instead.
     ====================================================================== */
  function initPayouts() {
    var accounts = document.getElementById('pay-accounts');
    var dataEl = document.getElementById('pay-data');
    if (!accounts || !dataEl) { return; }

    var D = JSON.parse(dataEl.textContent);
    var byId = {};
    D.accounts.forEach(function (a) { byId[a.id] = a; });
    var current = D.accounts.filter(function (a) { return a.state === 'default'; })[0].id;

    var select = document.getElementById('pay-account');
    var iban = document.getElementById('pay-iban');
    var holder = document.getElementById('pay-holder');
    var fx = document.getElementById('pay-fx');
    var form = document.getElementById('pay-form');
    var added = document.getElementById('pay-added');
    var note = document.getElementById('pay-note');
    var addBtn = document.getElementById('pay-add');

    function esc(s) { return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;'); }

    /* ---- the PAYING INTO block follows the selector ------------------- */
    function paintAccount(id) {
      var a = byId[id];
      if (!a) { return; }
      iban.textContent = a.iban;
      holder.textContent = a.holder + ' · ' + a.short + ' · ' + a.currency;
      fx.innerHTML = a.fx;
    }
    select.addEventListener('change', function () { paintAccount(select.value); });

    /* ---- moving the default ------------------------------------------ */
    function setDefault(id) {
      var previous = current;
      current = id;
      D.accounts.forEach(function (a) {
        a.state = a.id === id ? 'default' : (a.id === previous ? 'verified' : a.state);
      });
      D.accounts.forEach(function (a) {
        var card = accounts.querySelector('[data-card="' + a.id + '"]');
        var badge = card.querySelector('[data-state-label]');
        card.classList.toggle('payacc--on', a.state === 'default');
        badge.className = 'paystate paystate--' + a.state;
        badge.textContent = D.stateLabel[a.state];
        var acts = card.querySelector('[data-acts]');
        var btn = acts.querySelector('[data-account]');
        if (a.state === 'default' && btn) { btn.remove(); }
        if (a.state !== 'default' && !btn) {
          btn = document.createElement('button');
          btn.className = 'payact';
          btn.type = 'button';
          btn.setAttribute('data-account', a.id);
          btn.setAttribute('data-state', a.state);
          btn.textContent = D.actionLabel[a.state];
          acts.insertBefore(btn, acts.firstChild);
        }
        if (btn) { btn.setAttribute('data-state', a.state); btn.textContent = D.actionLabel[a.state]; }
        var remove = acts.querySelector('.paylink--rust');
        if (a.state === 'default' && remove) { remove.remove(); }
        if (a.state !== 'default' && !remove) {
          var link = document.createElement('a');
          link.className = 'paylink paylink--rust';
          link.href = D.edit;
          link.textContent = 'REMOVE';
          acts.appendChild(link);
        }
      });
      select.value = id;
      paintAccount(id);
      note.hidden = true;
    }

    accounts.addEventListener('click', function (e) {
      var b = e.target.closest('[data-account]');
      if (!b) { return; }
      if (b.getAttribute('data-state') === 'verifying') {
        note.hidden = false;
        note.focus();
        return;
      }
      setDefault(b.getAttribute('data-account'));
    });

    /* ---- adding an account ------------------------------------------- */
    addBtn.addEventListener('click', function () {
      form.hidden = false;
      added.hidden = true;
      form.scrollIntoView({ block: 'start', behavior: 'smooth' });
      form.querySelector('input').focus();
    });
    Array.prototype.forEach.call(form.querySelectorAll('[data-close]'), function (b) {
      b.addEventListener('click', function () { form.hidden = true; addBtn.focus(); });
    });
    document.getElementById('pay-confirm').addEventListener('click', function () {
      form.hidden = true;
      added.hidden = false;
      added.scrollIntoView({ block: 'center', behavior: 'smooth' });
    });
    document.getElementById('pay-added-x').addEventListener('click', function () {
      added.hidden = true;
      addBtn.focus();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !form.hidden) { form.hidden = true; addBtn.focus(); }
    });

    note.tabIndex = -1;
  }

  /* ======================================================================
     51. Dealer invoices (dealer-invoices.html)
     Two flows: the eight filters, which carry the headline, the pager's
     noun and count and the empty state with them; and Open, which fills
     the whole document — not just its header — from the map the generator
     wrote, so every document shows its own address, terms, spec, VAT mode
     and totals label.
     ====================================================================== */
  function initInvoices() {
    var host = document.getElementById('iv-rows');
    var dataEl = document.getElementById('iv-data');
    if (!host || !dataEl) { return; }

    var D = JSON.parse(dataEl.textContent);
    var rows = Array.prototype.slice.call(host.querySelectorAll('[data-row]'));
    var tabs = document.getElementById('iv-tabs');
    var head = document.getElementById('iv-list-h');
    var search = document.getElementById('iv-q');
    var empty = document.getElementById('iv-empty');
    var shownOut = document.getElementById('iv-shown');
    var totalOut = document.getElementById('iv-total');
    var nounOut = document.getElementById('iv-noun');
    var bulkLine = document.getElementById('iv-bulk-line');
    var pdfBtn = document.getElementById('iv-pdfs');
    var doc = document.getElementById('iv-doc');
    var docNo = document.getElementById('iv-doc-no');
    var docState = document.getElementById('iv-doc-state');
    var docBody = document.getElementById('iv-doc-body');
    var state = { tab: 'all', q: '', selected: {} };
    rows.forEach(function (el) {
      if (el.querySelector('[data-pick]').checked) { state.selected[el.getAttribute('data-row')] = true; }
    });

    var ONES = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven'];
    function word(n) { return ONES[n] || String(n); }
    function euro(n) {
      var s = '€' + String(Math.abs(n)).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      return n < 0 ? '−' + s : s;
    }

    /* ---- the filters ------------------------------------------------- */
    function matches(el) {
      var no = el.getAttribute('data-row');
      var tags = el.getAttribute('data-tags').split(' ');
      if (state.tab !== 'all' && tags.indexOf(state.tab) < 0) { return false; }
      if (state.q && D.rows[no].q.indexOf(state.q.toLowerCase()) < 0) { return false; }
      return true;
    }

    function bulk() {
      var nos = Object.keys(state.selected);
      var gross = 0, outstanding = 0;
      nos.forEach(function (no) { gross += D.rows[no].gross; if (D.rows[no].unpaid) { outstanding += D.rows[no].balance; } });
      bulkLine.textContent = nos.length
        ? nos.length + ' SELECTED · ' + euro(gross) + (outstanding ? ' · ' + euro(outstanding) + ' STILL OUTSTANDING' : ' · NOTHING OUTSTANDING')
        : D.bulkEmpty.replace(/&middot;/g, '·');
      pdfBtn.textContent = 'Download ' + nos.length + (nos.length === 1 ? ' PDF' : ' PDFs');
      Array.prototype.forEach.call(document.querySelectorAll('[data-bulk]'), function (b) { b.disabled = !nos.length; });
    }

    function render() {
      var shown = 0;
      rows.forEach(function (el) {
        var on = matches(el);
        el.hidden = !on;
        if (on) { shown++; }
      });
      rows.forEach(function (el) { el.classList.remove('ivrow--last'); });
      var visible = rows.filter(function (el) { return !el.hidden; });
      if (visible.length) { visible[visible.length - 1].classList.add('ivrow--last'); }
      empty.hidden = shown > 0;
      shownOut.textContent = shown;
      totalOut.textContent = state.q ? shown : D.counts[state.tab];
      nounOut.textContent = state.q ? (shown === 1 ? 'matching document' : 'matching documents') : D.nouns[state.tab];
      head.textContent = state.q
        ? (shown ? (shown === 1 ? 'One document matches.' : word(shown).charAt(0).toUpperCase() + word(shown).slice(1) + ' documents match.') : 'Nothing matches that.')
        : D.headlines[state.tab].replace(/&middot;/g, '·').replace(/&mdash;/g, '—');
      Array.prototype.forEach.call(tabs.querySelectorAll('[data-tab]'), function (b) {
        var on = b.getAttribute('data-tab') === state.tab;
        b.classList.toggle('ivtab--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      bulk();
    }

    tabs.addEventListener('click', function (e) {
      var b = e.target.closest('[data-tab]');
      if (!b) { return; }
      state.tab = b.getAttribute('data-tab');
      render();
    });
    var timer = null;
    search.addEventListener('input', function () {
      clearTimeout(timer);
      timer = setTimeout(function () { state.q = search.value.trim(); render(); }, 140);
    });
    host.addEventListener('change', function (e) {
      var box = e.target.closest('[data-pick]');
      if (!box) { return; }
      var no = box.closest('[data-row]').getAttribute('data-row');
      if (box.checked) { state.selected[no] = true; } else { delete state.selected[no]; }
      bulk();
    });

    /* ---- the document ------------------------------------------------ */
    host.addEventListener('click', function (e) {
      var b = e.target.closest('[data-open]');
      if (!b) { return; }
      var d = D.docs[b.getAttribute('data-open')];
      if (!d) { return; }
      docNo.textContent = d.no;
      docState.className = 'ivstate ivstate--' + d.tone;
      docState.textContent = d.state;
      docBody.innerHTML = d.body;
      wrapMoney(docBody);
      doc.hidden = false;
      doc.scrollIntoView({ block: 'start', behavior: 'smooth' });
      document.getElementById('iv-doc-x').focus();
    });
    document.getElementById('iv-doc-x').addEventListener('click', function () {
      doc.hidden = true;
      var first = rows.filter(function (el) { return !el.hidden; })[0];
      if (first) { first.querySelector('[data-open]').focus(); }
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !doc.hidden) { document.getElementById('iv-doc-x').click(); }
    });

    bulk();
  }

  /* ======================================================================
     52. Dealer reviews (dealer-reviews.html)
     Two flows. The filters move rows, headline, pager and the empty state.
     Publishing a reply is the one that costs something: the row loses its
     accent edge and its waiting pill, gains the answer, and then six
     counters that all mean "how many are still open" are recomputed from
     the rows themselves — the rail sub-line, the status bar, the oldest
     wait, the dark strip, the tab chips and the answered figure.
     ====================================================================== */
  function initReviews() {
    var host = document.getElementById('rv-rows');
    var dataEl = document.getElementById('rv-data');
    if (!host || !dataEl) { return; }

    var D = JSON.parse(dataEl.textContent);
    var rows = Array.prototype.slice.call(host.querySelectorAll('[data-row]'));
    var tabs = document.getElementById('rv-tabs');
    var headline = document.getElementById('rv-list-h');
    var empty = document.getElementById('rv-empty');
    var shownOut = document.getElementById('rv-shown');
    var totalOut = document.getElementById('rv-total');
    var nounOut = document.getElementById('rv-noun');
    var needOut = document.getElementById('rv-need');
    var oldestOut = document.getElementById('rv-oldest');
    var stripOut = document.getElementById('rv-strip');
    var kpiOut = document.getElementById('rv-kpi');
    var kpiNote = document.getElementById('rv-kpi-note');
    var railSub = document.querySelector('[data-rail="reviews"] .uanav__s');
    var tab = 'all';
    var counts = {};
    Object.keys(D.counts).forEach(function (k) { counts[k] = D.counts[k]; });

    function tagsOf(el) { return el.getAttribute('data-tags').split(' '); }

    function render() {
      var shown = 0;
      rows.forEach(function (el) {
        var on = tab === 'all' || tagsOf(el).indexOf(tab) >= 0;
        el.hidden = !on;
        if (on) { shown++; }
      });
      empty.hidden = shown > 0;
      shownOut.textContent = shown;
      totalOut.textContent = counts[tab];
      nounOut.textContent = D.nouns[tab];
      headline.innerHTML = D.headlines[tab];
      Array.prototype.forEach.call(tabs.querySelectorAll('[data-tab]'), function (b) {
        var on = b.getAttribute('data-tab') === tab;
        b.classList.toggle('rvtab--on', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
    }

    /* ---- every counter that means "still open" ----------------------- */
    function syncCounts() {
      var open = rows.filter(function (el) { return tagsOf(el).indexOf('unanswered') >= 0; });
      var waits = open.map(function (el) { return parseInt(el.getAttribute('data-wait'), 10) || 0; });
      var oldest = waits.length ? Math.max.apply(null, waits) : 0;
      var answered = D.total - open.length;
      counts.unanswered = open.length;
      counts.answered = answered;

      needOut.textContent = open.length ? open.length + ' NEED A REPLY' : D.needDone;
      oldestOut.textContent = open.length ? ' · OLDEST WAITING ' + oldest + ' DAYS' : '';
      var hasFour = open.some(function (el) { return el.getAttribute('data-stars') === '4'; });
      stripOut.textContent = !open.length ? D.stripDone
        : (hasFour ? D.stripOpen.replace(/&middot;/g, '·').replace(/^\d+/, open.length)
                   : open.length + ' WAITING');
      if (railSub) { railSub.textContent = open.length ? open.length + ' NEED A REPLY' : D.railDone; }
      kpiOut.textContent = answered + ' of ' + D.total;
      kpiNote.textContent = D.kpiNotes[Math.min(open.length, D.kpiNotes.length - 1)];
      Array.prototype.forEach.call(document.querySelectorAll('[data-chip]'), function (c) {
        var k = c.getAttribute('data-chip');
        if (k === 'unanswered' || k === 'answered') { c.textContent = counts[k]; }
      });
      render();
    }

    tabs.addEventListener('click', function (e) {
      var b = e.target.closest('[data-tab]');
      if (!b) { return; }
      tab = b.getAttribute('data-tab');
      render();
    });

    /* ---- the composer ------------------------------------------------ */
    function formOf(key) { return host.querySelector('[data-form="' + key + '"]'); }

    host.addEventListener('click', function (e) {
      var open = e.target.closest('[data-reply]');
      var cancel = e.target.closest('[data-cancel]');
      var send = e.target.closest('[data-send]');
      var opener = e.target.closest('[data-opener]');

      if (open) {
        var key = open.getAttribute('data-reply');
        var form = formOf(key);
        form.hidden = !form.hidden;
        if (!form.hidden) { form.querySelector('.rvtext').focus(); }
        return;
      }
      if (cancel) {
        var cf = formOf(cancel.getAttribute('data-cancel'));
        cf.hidden = true;
        host.querySelector('[data-reply="' + cancel.getAttribute('data-cancel') + '"]').focus();
        return;
      }
      if (opener) {
        var box = opener.closest('.rvform__box').querySelector('.rvtext');
        var lead = opener.textContent.trim();
        box.value = box.value ? box.value.replace(/\s*$/, ' ') + lead + ' ' : lead + ' ';
        box.focus();
        box.setSelectionRange(box.value.length, box.value.length);
        return;
      }
      if (send) { publish(send.getAttribute('data-send')); }
    });

    function publish(key) {
      var row = host.querySelector('[data-row="' + key + '"]');
      var form = formOf(key);
      var text = form.querySelector('.rvtext').value.trim();
      if (!text) { form.querySelector('.rvtext').focus(); return; }

      var said = row.querySelector('.rvsaid');
      var wait = said.querySelector('.rvwait');
      if (wait) { wait.parentNode.removeChild(wait); }
      var old = said.querySelector('.rvsaid__reply');
      if (old) { old.parentNode.removeChild(old); }

      var bubble = document.createElement('span');
      bubble.className = 'rvsaid__reply';
      var head = document.createElement('span');
      head.className = 'rvreply__head';
      var who = document.createElement('span');
      who.className = 'rvreply__who';
      who.textContent = D.dealer + ' REPLIED';
      var when = document.createElement('span');
      when.className = 'rvmeta';
      when.textContent = 'JUST NOW';
      head.appendChild(who);
      head.appendChild(when);
      var body = document.createElement('span');
      body.className = 'rvreply__b';
      body.textContent = text;
      bubble.appendChild(head);
      bubble.appendChild(body);
      said.appendChild(bubble);

      row.classList.remove('rvrow--open');
      row.setAttribute('data-tags', tagsOf(row).map(function (t) {
        return t === 'unanswered' ? 'answered' : t;
      }).join(' '));
      row.setAttribute('data-wait', '');
      var btn = row.querySelector('[data-reply]');
      btn.textContent = 'Edit the reply';
      btn.classList.add('rvact--out');
      form.hidden = true;
      btn.focus();
      syncCounts();
    }

    syncCounts();
  }

  /* ======================================================================
     53. Dealer team (dealer-team.html)
     Two flows that end in the same place: inviting somebody, and changing
     one person's access. The confirmation strip is shared, so both
     branches rewrite BOTH of its lines — otherwise it would show the
     outcome of whatever happened last time.
     ====================================================================== */
  function initTeam() {
    var main = document.querySelector('.tmmain');
    var dataEl = document.getElementById('tm-data');
    if (!main || !dataEl) { return; }

    var D = JSON.parse(dataEl.textContent);
    var panel = document.getElementById('tm-invite-form');
    var openBtn = document.getElementById('tm-invite');
    var done = document.getElementById('tm-done');
    var doneT = document.getElementById('tm-done-t');
    var doneS = document.getElementById('tm-done-s');

    function say(title, meta) {
      doneT.textContent = title;
      doneS.textContent = meta;
      done.hidden = false;
      done.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }

    /* ---- invite ------------------------------------------------------ */
    openBtn.addEventListener('click', function () {
      panel.hidden = !panel.hidden;
      if (!panel.hidden) {
        panel.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        var first = panel.querySelector('.tmin');
        if (first) { first.focus(); }
      }
    });

    panel.addEventListener('click', function (e) {
      if (e.target.closest('[data-invite-close]')) {
        panel.hidden = true;
        openBtn.focus();
        return;
      }
      if (e.target.closest('#tm-invite-send')) {
        panel.hidden = true;
        say(D.invite.title.replace(/&mdash;/g, '—'), D.invite.meta.replace(/&middot;/g, '·'));
        openBtn.focus();
      }
    });

    document.getElementById('tm-done-x').addEventListener('click', function () {
      done.hidden = true;
    });

    /* ---- per-person access ------------------------------------------- */
    main.addEventListener('click', function (e) {
      var edit = e.target.closest('[data-edit]');
      var cancel = e.target.closest('[data-cancel]');
      var save = e.target.closest('[data-save]');
      var key = edit ? edit.getAttribute('data-edit')
              : cancel ? cancel.getAttribute('data-cancel')
              : save ? save.getAttribute('data-save') : null;
      if (!key) { return; }
      var form = main.querySelector('[data-form="' + key + '"]');
      if (!form) { return; }

      if (edit) {
        form.hidden = !form.hidden;
        if (!form.hidden) {
          var box = form.querySelector('.tmcheck:not([disabled])');
          if (box) { box.focus(); }
        }
        return;
      }
      form.hidden = true;
      main.querySelector('[data-edit="' + key + '"]').focus();
      if (save) { say('Access saved for ' + D.names[key], D.savedMeta.replace(/&middot;/g, '·')); }
    });
  }

  /* ======================================================================
     54. Dealer profile (dealer-profile.html)
     One strip, two outcomes: publishing the page, and opening a site for
     editing. Both branches write BOTH lines of the strip before it is
     revealed — otherwise the second action shows the first one's message.
     The city is read from a data attribute, not from the markup around it.
     ====================================================================== */
  function initDealerProfile() {
    var main = document.querySelector('.dfpmain');
    var dataEl = document.getElementById('dfp-data');
    if (!main || !dataEl) { return; }

    var D = JSON.parse(dataEl.textContent);
    var done = document.getElementById('dfp-done');
    var doneT = document.getElementById('dfp-done-t');
    var doneS = document.getElementById('dfp-done-s');
    var publish = document.getElementById('dfp-publish');

    function say(title, meta) {
      doneT.textContent = title;
      doneS.textContent = meta;
      done.hidden = false;
      done.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }

    publish.addEventListener('click', function () {
      say(D.published.title.replace(/&mdash;/g, '—'), D.published.meta.replace(/&middot;/g, '·'));
    });

    document.getElementById('dfp-done-x').addEventListener('click', function () {
      done.hidden = true;
      publish.focus();
    });

    main.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-site-edit]');
      if (!btn) { return; }
      var key = btn.getAttribute('data-site-edit');
      var row = btn.closest('[data-site]');
      var el = row && row.querySelector('[data-city]');
      var city = el ? el.textContent.trim() : D.cities[key];
      say(D.site.title.replace(/&mdash;/g, '—').replace('%s', city),
          D.site.meta.replace(/&rsquo;/g, '’'));
    });
  }

  /* ======================================================================
     55. Dealer settings (dealer-settings.html)
     Every switch carries two sentences — one per direction — and the strip
     prints the one that actually happened. The locked switch refuses out
     loud: it says why and does not move. Matrix cells toggle on their own;
     a locked channel is a lock glyph, not a control.
     ====================================================================== */
  function initDealerSettings() {
    var main = document.querySelector('.dstmain');
    var dataEl = document.getElementById('dst-data');
    if (!main || !dataEl) { return; }

    var D = JSON.parse(dataEl.textContent);
    var done = document.getElementById('dst-done');
    var doneT = document.getElementById('dst-done-t');
    var doneS = document.getElementById('dst-done-s');
    var save = document.getElementById('dst-save');

    function clean(s) {
      return s.replace(/&mdash;/g, '—').replace(/&middot;/g, '·').replace(/&ldquo;|&rdquo;/g, '"');
    }

    function say(pair, bad) {
      doneT.textContent = clean(pair[0]);
      doneS.textContent = clean(pair[1]);
      done.classList.toggle('dstdone--rust', !!bad);
      done.hidden = false;
      done.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }

    save.addEventListener('click', function () {
      say([D.saved.title, D.saved.meta], false);
    });

    document.getElementById('dst-done-x').addEventListener('click', function () {
      done.hidden = true;
      save.focus();
    });

    main.addEventListener('click', function (e) {
      var sw = e.target.closest('[data-switch]');
      if (sw) {
        var key = sw.getAttribute('data-switch');
        var spec = D.switches[key];
        if (!spec) { return; }
        if (spec.locked) { say(spec.off, true); return; }
        var on = sw.getAttribute('aria-pressed') === 'true';
        sw.setAttribute('aria-pressed', on ? 'false' : 'true');
        say(on ? spec.off : spec.on, on);
        return;
      }
      var cell = e.target.closest('[data-cell]');
      if (cell) {
        cell.setAttribute('aria-pressed', cell.getAttribute('aria-pressed') === 'true' ? 'false' : 'true');
      }
    });
  }

  /* ======================================================================
     56. Add a listing (dealer-add-listing.html)
     Publish refuses while anything real is missing, and removing the last
     defect stops the page: the gate is the dealer's own setting, so the
     strip cites it rather than explaining itself. Removing any defect
     recounts the ink header — cosmetic and mechanical are counted from
     the rows that are left, never from a stored number.
     ====================================================================== */
  function initAddListing() {
    var main = document.querySelector('.almain');
    var dataEl = document.getElementById('al-data');
    if (!main || !dataEl) { return; }

    var D = JSON.parse(dataEl.textContent);
    var host = document.getElementById('al-defects');
    var count = document.getElementById('al-defcount');
    var left = document.getElementById('al-left');
    var done = document.getElementById('al-done');
    var doneT = document.getElementById('al-done-t');
    var doneS = document.getElementById('al-done-s');
    var publish = document.getElementById('al-publish');

    function clean(s) {
      return s.replace(/&mdash;/g, '—').replace(/&middot;/g, '·')
              .replace(/&ldquo;|&rdquo;/g, '"').replace(/&rsquo;/g, '’');
    }

    function say(pair, bad) {
      doneT.textContent = clean(pair.title);
      doneS.textContent = clean(pair.meta);
      done.classList.toggle('aldone--rust', !!bad);
      done.hidden = false;
      done.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }

    function recount() {
      var rows = Array.prototype.slice.call(host.querySelectorAll('[data-defect]'));
      var mech = rows.filter(function (r) { return r.getAttribute('data-kind') === 'mechanical'; }).length;
      if (!rows.length) {
        count.textContent = clean(D.emptyHead);
        left.textContent = 'PUBLISHING IS BLOCKED BY YOUR OWN RULE';
        return;
      }
      count.textContent = rows.length + ' DEFECT' + (rows.length === 1 ? '' : 'S') + ' · ' +
        (rows.length - mech) + ' COSMETIC · ' + mech + ' MECHANICAL';
      left.textContent = clean(D.left);
    }

    publish.addEventListener('click', function () { say(D.refused, true); });

    document.getElementById('al-done-x').addEventListener('click', function () {
      done.hidden = true;
      publish.focus();
    });

    document.getElementById('al-add').addEventListener('click', function () {
      say({ title: 'A defect needs a photograph before it can be added',
            meta: 'PHOTOGRAPH, SEVERITY AND WHAT HAPPENS TO IT · ALL THREE' }, false);
    });

    main.addEventListener('click', function (e) {
      var drop = e.target.closest('[data-remove]');
      if (drop) {
        var row = drop.closest('[data-defect]');
        var next = row.nextElementSibling || row.previousElementSibling;
        row.parentNode.removeChild(row);
        recount();
        var empty = !host.querySelector('[data-defect]');
        say(empty ? D.blocked : D.removed, empty);
        var focusTo = empty ? document.getElementById('al-add') : (next && next.querySelector('[data-drop]'));
        if (focusTo) { focusTo.focus(); }
        return;
      }
      var step = e.target.closest('[data-step]');
      if (step) {
        var key = step.getAttribute('data-step');
        var head = document.getElementById('al-' + key + '-h');
        if (head) { head.scrollIntoView({ block: 'start', behavior: 'smooth' }); }
      }
    });

    recount();
  }

  /* ======================================================================
     57. Photograph drop zones (dealer profile, add a listing)
     Every empty frame on those pages is a real <input type="file"> with a
     name and an id, so a buyer of this template binds their own upload to
     it. What runs here is only the front end: pick or drop a file and the
     frame shows it at once, then tells the page so counters can move.
     ====================================================================== */
  function initPhotoDrops() {
    var zones = document.querySelectorAll('[data-drop]');
    if (!zones.length) { return; }

    function show(zone, file) {
      if (!file || !/^image\//.test(file.type)) { return; }
      var slot = zone.querySelector('.dfpdrop__in, .aldrop2__in') || zone;
      var url = URL.createObjectURL(file);
      var old = slot.querySelector('img');
      if (old && old.dataset.objectUrl) { URL.revokeObjectURL(old.src); }
      var img = document.createElement('img');
      img.className = zone.classList.contains('alframe') || zone.classList.contains('aldrop2')
        ? 'alframe__img' : (zone.classList.contains('dfpshot') ? 'dfpshot__img' : 'dfpslot__img');
      img.src = url;
      img.alt = '';
      img.dataset.objectUrl = '1';
      slot.innerHTML = '';
      slot.appendChild(img);
      zone.classList.remove('alframe--empty', 'dfpslot__box--missing', 'dfpshot--missing',
                            'dfpslot__box--square', 'dfpslot__box--cover');
      var pill = zone.querySelector('.alup, .dfpup');
      if (pill) { pill.textContent = 'REPLACE'; }
      zone.dispatchEvent(new CustomEvent('photo:added', { bubbles: true }));
    }

    Array.prototype.forEach.call(zones, function (zone) {
      var input = zone.querySelector('input[type="file"]');
      if (!input) { return; }
      input.addEventListener('change', function () { show(zone, input.files && input.files[0]); });
      ['dragenter', 'dragover'].forEach(function (t) {
        zone.addEventListener(t, function (e) {
          e.preventDefault();
          zone.classList.add(zone.classList.contains('aldrop2') ? 'aldrop2--over' : 'dfpdrop--over');
        });
      });
      ['dragleave', 'drop'].forEach(function (t) {
        zone.addEventListener(t, function () {
          zone.classList.remove('aldrop2--over', 'dfpdrop--over');
        });
      });
      zone.addEventListener('drop', function (e) {
        e.preventDefault();
        show(zone, e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]);
      });
    });

    /* The add-a-listing page counts its own slots, so it listens. */
    var steps = document.querySelector('[data-step="photos"] .alstep__s');
    if (!steps) { return; }
    document.addEventListener('photo:added', function () {
      var all = document.querySelectorAll('.alshots [data-drop], .alcover [data-drop]');
      var empty = document.querySelectorAll('.alframe--empty').length;
      steps.textContent = (all.length - empty) + ' OF ' + all.length;
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
}());
