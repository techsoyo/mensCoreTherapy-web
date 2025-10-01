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
})();
