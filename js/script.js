// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.getElementById('menuToggle');
  var nav = document.getElementById('mainNav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      nav.classList.toggle('mobile-open');
    });
  }

  // Popup form — show once after 15 seconds
  setTimeout(function () {
    var overlay = document.getElementById('popupOverlay');
    if (overlay) overlay.classList.add('active');
  }, 15000);

  var popupClose = document.getElementById('popupClose');
  var popupOverlay = document.getElementById('popupOverlay');
  if (popupClose && popupOverlay) {
    popupClose.addEventListener('click', function () {
      popupOverlay.classList.remove('active');
    });
    popupOverlay.addEventListener('click', function (e) {
      if (e.target === this) this.classList.remove('active');
    });
  }

  // Basic form submit handlers (replace with your real form endpoint / API)
  ['heroForm', 'popupForm'].forEach(function (id) {
    var f = document.getElementById(id);
    if (f) {
      f.addEventListener('submit', function (e) {
        e.preventDefault();
        alert('Thank you! Our team will call you shortly to confirm your free site visit.');
        f.reset();
        if (popupOverlay) popupOverlay.classList.remove('active');
      });
    }
  });
});
