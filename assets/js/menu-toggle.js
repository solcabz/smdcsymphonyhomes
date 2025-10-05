const menuToggle = document.querySelector(".menu-toggle");
const menuWrap = document.querySelector(".menu-wrap");

menuToggle.addEventListener("click", () => {
  const isOpen = menuWrap.classList.toggle("active");
  menuToggle.setAttribute("aria-expanded", isOpen);
});