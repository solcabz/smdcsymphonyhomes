document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.querySelector(".menu-toggle");
  const closeMenuBtn = document.querySelector(".close-menu");
  const backdrop = document.getElementById("menu-backdrop");
  const menuWrap = document.getElementById("mobile-menu");

  if (!menuToggle || !backdrop || !menuWrap) return;

  // Open menu
  function openMenu() {
    backdrop.classList.add('active');
    document.body.classList.add('menu-open');
    requestAnimationFrame(() => {
      menuWrap.classList.add('active');
      menuToggle.setAttribute('aria-expanded', 'true');
    });
  }

  // Close menu
  function closeMenu() {
    menuWrap.classList.remove('active');
    menuToggle.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('menu-open');

    const handler = (e) => {
      if (e.propertyName === 'transform') {
        backdrop.classList.remove('active');
        menuWrap.removeEventListener('transitionend', handler);
      }
    };
    menuWrap.addEventListener('transitionend', handler);
  }

  // Events
  menuToggle.addEventListener('click', openMenu);
  if (closeMenuBtn) closeMenuBtn.addEventListener('click', closeMenu);
  backdrop.addEventListener('click', (e) => { if (e.target === backdrop) closeMenu(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && backdrop.classList.contains('active')) closeMenu(); });
});
