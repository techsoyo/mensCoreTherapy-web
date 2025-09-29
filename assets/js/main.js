(function () {
  const topEdge = document.getElementById('mm-top-edge');
  const bottomEdge = document.getElementById('mm-bottom-edge');
  const header = document.getElementById('site-header');
  const footer = document.getElementById('site-footer');

  if (topEdge && header) {
    topEdge.addEventListener('mouseenter', () => header.classList.add('visible'));
    header.addEventListener('mouseleave', () => header.classList.remove('visible'));
  }
  if (bottomEdge && footer) {
    bottomEdge.addEventListener('mouseenter', () => footer.classList.add('visible'));
    footer.addEventListener('mouseleave', () => footer.classList.remove('visible'));
  }

  // Parallax suave en overlay del héroe
  const overlay = document.querySelector('.mm-hero__overlay');
  const hero = document.querySelector('.mm-hero');

  function onScroll() {
    if (!overlay || !hero) return;
    const rect = hero.getBoundingClientRect();
    if (rect.bottom > 0 && rect.top < window.innerHeight) {
      const progress = (window.innerHeight - rect.top) / (window.innerHeight + rect.height);
      overlay.style.transform = `translateY(${(progress - 0.5) * 20}px)`;
    }
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();
