(() => {
  const key = `scroll:${window.location.pathname}`;
  let resetOnNavigation = false;

  if ('scrollRestoration' in history) history.scrollRestoration = 'manual';

  const save = () => {
    if (!resetOnNavigation) sessionStorage.setItem(key, String(window.scrollY));
  };
  const restore = () => {
    const saved = sessionStorage.getItem(key);
    if (window.location.hash || saved === null) return;

    const scrollY = Number(saved);
    if (Number.isFinite(scrollY)) window.scrollTo(0, scrollY);
  };

  window.addEventListener('scroll', save, { passive: true });
  window.addEventListener('pagehide', save);
  window.addEventListener('pageshow', restore);
  document.addEventListener('click', (event) => {
    if (!(event.target instanceof Element)) return;

    const link = event.target.closest('a[href]');
    if (!link) return;

    const destination = new URL(link.href, window.location.href);
    if (
      !window.location.pathname.endsWith('/tanaman.php') ||
      destination.pathname !== window.location.pathname ||
      destination.search === window.location.search
    ) return;

    resetOnNavigation = true;
    sessionStorage.removeItem(key);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
})();
