/* =========================================================
   PLAYFUL GEOMETRIC DESIGN SYSTEM — JS
   Handles: pop-in reveal on scroll, marquee content duplication.
   Respects prefers-reduced-motion.
   ========================================================= */
(function () {
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  document.addEventListener('DOMContentLoaded', function () {
    document.documentElement.classList.add('js-reveal-ready');

    // --- Duplicate marquee content so the scroll loop is seamless ---
    document.querySelectorAll('.pg-marquee-track').forEach(function (track) {
      if (reduceMotion) return;
      track.innerHTML += track.innerHTML;
    });

    // --- Pop-in reveal on scroll for elements with .pg-reveal ---
    var revealEls = document.querySelectorAll('.pg-reveal');
    if (!revealEls.length) return;

    if (reduceMotion || !('IntersectionObserver' in window)) {
      revealEls.forEach(function (el) { el.classList.add('pg-in-view'); });
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('pg-in-view');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });

    revealEls.forEach(function (el) { observer.observe(el); });
  });
})();

    // FAQ accordion (hardened: safe against duplicate binding)
    document.querySelectorAll('.pg-faq-question').forEach(function (btn) {
      if (btn.dataset.faqBound === '1') return;
      btn.dataset.faqBound = '1';
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        var item = btn.closest('.pg-faq-item');
        var icon = btn.querySelector('.pg-faq-icon');
        var wasOpen = item.classList.contains('open');
        document.querySelectorAll('.pg-faq-item.open').forEach(function (el) {
          el.classList.remove('open');
          var i = el.querySelector('.pg-faq-icon');
          if (i) i.textContent = '+';
        });
        if (!wasOpen) { item.classList.add('open'); if (icon) icon.textContent = '−'; }
      });
    });
