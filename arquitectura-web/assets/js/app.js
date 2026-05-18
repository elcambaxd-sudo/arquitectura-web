(() => {
  const loader = document.querySelector('[data-loader]');
  window.addEventListener('load', () => {
    if (loader) window.setTimeout(() => loader.classList.add('is-hidden'), 260);
  });

  const header = document.querySelector('[data-header]');
  const syncHeader = () => {
    if (header) header.classList.toggle('is-scrolled', window.scrollY > 24);
  };
  syncHeader();
  window.addEventListener('scroll', syncHeader, { passive: true });

  const toggle = document.querySelector('[data-nav-toggle]');
  const menu = document.querySelector('[data-nav-menu]');
  if (toggle && menu) {
    toggle.addEventListener('click', () => {
      const open = menu.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(open));
    });
  }

  const homeGallery = document.querySelector('[data-home-gallery]');
  if (homeGallery) {
    const panels = Array.from(homeGallery.querySelectorAll('[data-home-slide-panel]'));
    const tabs = Array.from(homeGallery.querySelectorAll('[data-home-slide]'));
    const prev = homeGallery.querySelector('[data-home-prev]');
    const next = homeGallery.querySelector('[data-home-next]');
    const caption = homeGallery.querySelector('.home-slide-caption');
    const title = homeGallery.querySelector('[data-home-link]');
    const category = homeGallery.querySelector('[data-home-category]');
    const location = homeGallery.querySelector('[data-home-location]');
    let activeIndex = Math.max(0, panels.findIndex((panel) => panel.classList.contains('is-active')));
    let timer = null;
    let startX = 0;

    const pulse = (element) => {
      if (!element) return;
      element.classList.add('is-pressed');
      window.setTimeout(() => element.classList.remove('is-pressed'), 180);
    };

    const syncCaption = (panel) => {
      if (!panel) return;
      caption?.classList.add('is-changing');
      window.setTimeout(() => {
        if (title) {
          title.textContent = panel.dataset.title || 'Proyecto';
          title.href = panel.dataset.url || '#';
        }
        if (category) category.textContent = panel.dataset.category || 'Proyecto';
        if (location) location.textContent = panel.dataset.location || '';
        caption?.classList.remove('is-changing');
      }, 150);
    };

    const setSlide = (index, userAction = false) => {
      if (!panels.length) return;
      const nextIndex = (index + panels.length) % panels.length;
      if (nextIndex === activeIndex && !userAction) return;

      panels[activeIndex]?.classList.remove('is-active');
      panels[activeIndex]?.setAttribute('aria-hidden', 'true');
      tabs[activeIndex]?.classList.remove('is-active');
      tabs[activeIndex]?.setAttribute('aria-selected', 'false');

      activeIndex = nextIndex;

      panels[activeIndex]?.classList.add('is-active');
      panels[activeIndex]?.setAttribute('aria-hidden', 'false');
      tabs[activeIndex]?.classList.add('is-active');
      tabs[activeIndex]?.setAttribute('aria-selected', 'true');
      tabs[activeIndex]?.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
      syncCaption(panels[activeIndex]);

      if (userAction) restartTimer();
    };

    const restartTimer = () => {
      window.clearInterval(timer);
      timer = window.setInterval(() => setSlide(activeIndex + 1), 5200);
    };

    tabs.forEach((tab) => {
      tab.addEventListener('click', () => {
        pulse(tab);
        setSlide(Number(tab.dataset.homeSlide || 0), true);
      });
    });

    homeGallery.querySelectorAll('.home-bottom-nav a').forEach((link) => {
      link.addEventListener('pointerdown', () => pulse(link));
    });

    prev?.addEventListener('click', () => {
      pulse(prev);
      setSlide(activeIndex - 1, true);
    });

    next?.addEventListener('click', () => {
      pulse(next);
      setSlide(activeIndex + 1, true);
    });

    homeGallery.addEventListener('mouseenter', () => window.clearInterval(timer));
    homeGallery.addEventListener('mouseleave', restartTimer);
    homeGallery.addEventListener('pointerdown', (event) => {
      startX = event.clientX;
    });
    homeGallery.addEventListener('pointerup', (event) => {
      const distance = event.clientX - startX;
      if (Math.abs(distance) > 48) {
        setSlide(activeIndex + (distance < 0 ? 1 : -1), true);
      }
    });

    document.addEventListener('keydown', (event) => {
      const tag = document.activeElement?.tagName?.toLowerCase();
      if (tag === 'input' || tag === 'textarea' || tag === 'select') return;
      if (event.key === 'ArrowLeft') setSlide(activeIndex - 1, true);
      if (event.key === 'ArrowRight') setSlide(activeIndex + 1, true);
    });

    restartTimer();
  }

  const reveals = document.querySelectorAll('[data-reveal]');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });
  reveals.forEach((item) => observer.observe(item));

  const projectSearch = document.querySelector('[data-project-search]');
  const projectButtons = document.querySelectorAll('[data-project-filter]');
  const projectCards = document.querySelectorAll('[data-project-card]');
  const emptyState = document.querySelector('[data-empty-projects]');

  function filterProjects() {
    if (!projectCards.length) return;
    const query = (projectSearch?.value || '').toLowerCase().trim();
    const active = document.querySelector('[data-project-filter].is-active')?.dataset.projectFilter || 'all';
    let visible = 0;

    projectCards.forEach((card) => {
      const text = (card.dataset.search || '').toLowerCase();
      const category = card.dataset.category || '';
      const show = (active === 'all' || category === active) && (!query || text.includes(query));
      card.style.display = show ? '' : 'none';
      if (show) visible += 1;
    });

    if (emptyState) emptyState.style.display = visible ? 'none' : 'block';
  }

  projectButtons.forEach((button) => {
    button.addEventListener('click', () => {
      projectButtons.forEach((item) => item.classList.remove('is-active'));
      button.classList.add('is-active');
      filterProjects();
    });
  });
  projectSearch?.addEventListener('input', filterProjects);

  const blogButtons = document.querySelectorAll('[data-blog-filter]');
  const blogCards = document.querySelectorAll('[data-blog-card]');
  blogButtons.forEach((button) => {
    button.addEventListener('click', () => {
      blogButtons.forEach((item) => item.classList.remove('is-active'));
      button.classList.add('is-active');
      const active = button.dataset.blogFilter || 'all';
      blogCards.forEach((card) => {
        card.style.display = active === 'all' || card.dataset.category === active ? '' : 'none';
      });
    });
  });

  const contactForm = document.querySelector('[data-contact-form]');
  if (contactForm) {
    contactForm.addEventListener('submit', async (event) => {
      event.preventDefault();
      const button = contactForm.querySelector('[type="submit"]');
      const status = contactForm.querySelector('[data-form-status]');
      const required = contactForm.querySelectorAll('[required]');
      let hasError = false;

      required.forEach((field) => {
        const wrapper = field.closest('.field');
        const invalid = !field.value.trim() || (field.type === 'email' && !field.validity.valid);
        wrapper?.classList.toggle('has-error', invalid);
        if (invalid) hasError = true;
      });

      if (hasError) {
        if (status) status.innerHTML = '<div class="alert alert--error">Revisa los campos obligatorios.</div>';
        return;
      }

      button.disabled = true;
      const original = button.textContent;
      button.textContent = 'Enviando...';

      try {
        const response = await fetch(contactForm.action, {
          method: 'POST',
          body: new FormData(contactForm),
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const data = await response.json();
        if (!response.ok || !data.ok) throw new Error(data.message || 'No se pudo enviar el mensaje.');
        contactForm.reset();
        if (status) status.innerHTML = `<div class="alert alert--success">${data.message}</div>`;
      } catch (error) {
        if (status) status.innerHTML = `<div class="alert alert--error">${error.message}</div>`;
      } finally {
        button.disabled = false;
        button.textContent = original;
      }
    });
  }
})();
