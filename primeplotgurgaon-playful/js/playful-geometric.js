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
