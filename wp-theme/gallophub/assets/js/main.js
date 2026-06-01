/* GallopHub — main.js */
(function () {
  'use strict';

  /* ── Navbar shadow on scroll ── */
  const header = document.querySelector('.site-header');
  if (header) {
    window.addEventListener('scroll', () => {
      header.classList.toggle('scrolled', window.scrollY > 10);
    }, { passive: true });
  }

  /* ── Mobile drawer ── */
  const toggle    = document.querySelector('.nav-toggle');
  const drawer    = document.querySelector('.mobile-drawer');
  const overlay   = drawer?.querySelector('.mobile-drawer-overlay');
  const closeBtn  = drawer?.querySelector('.mobile-drawer-close');

  function openDrawer()  { drawer?.classList.add('open'); toggle?.classList.add('open'); document.body.style.overflow = 'hidden'; }
  function closeDrawer() { drawer?.classList.remove('open'); toggle?.classList.remove('open'); document.body.style.overflow = ''; }

  toggle?.addEventListener('click', () => drawer?.classList.contains('open') ? closeDrawer() : openDrawer());
  overlay?.addEventListener('click', closeDrawer);
  closeBtn?.addEventListener('click', closeDrawer);

  /* ── Language switcher ── */
  document.querySelectorAll('[data-lang]').forEach(el => {
    el.addEventListener('click', e => {
      e.preventDefault();
      const lang = el.getAttribute('data-lang');
      document.querySelectorAll('[data-lang]').forEach(x => x.classList.toggle('active', x.getAttribute('data-lang') === lang));
      localStorage.setItem('gh_lang', lang);
      // In a real WP setup, this would redirect to /?lang=xx or use Polylang/WPML
    });
  });

  /* ── FAQ accordion ── */
  document.querySelectorAll('.faq-question').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.faq-item');
      const wasOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
      if (!wasOpen) item.classList.add('open');
    });
  });

  /* ── Gallery thumbnails ── */
  const mainImg = document.querySelector('.gallery-main img');
  document.querySelectorAll('.gallery-thumb').forEach((thumb, i) => {
    thumb.addEventListener('click', () => {
      if (!mainImg) return;
      mainImg.src = thumb.querySelector('img').src;
      document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
      thumb.classList.add('active');
    });
  });

  /* ── Horse filters — AJAX ── */
  const filterForm = document.getElementById('horse-filter-form');
  if (filterForm) {
    const grid      = document.getElementById('horses-grid');
    const countEl   = document.getElementById('horses-count');
    let debounce;

    function doFilter() {
      const formData = new FormData(filterForm);
      formData.append('action', 'gh_filter_horses');
      formData.append('nonce', ghAjax.nonce);

      grid.innerHTML = Array.from({ length: 6 }, () => skeletonCard()).join('');

      fetch(ghAjax.url, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(res => {
          if (res.success) {
            grid.innerHTML = res.data.html || '<p class="horses-empty">Aucun cheval trouvé.</p>';
            if (countEl) countEl.textContent = res.data.count + ' cheval(aux) trouvé(s)';
          }
        });
    }

    filterForm.addEventListener('change', () => { clearTimeout(debounce); debounce = setTimeout(doFilter, 400); });
    filterForm.addEventListener('input',  () => { clearTimeout(debounce); debounce = setTimeout(doFilter, 600); });

    document.getElementById('filter-reset')?.addEventListener('click', () => {
      filterForm.reset();
      doFilter();
    });
  }

  /* ── Filter sidebar toggle (mobile) ── */
  const filterToggleBtn = document.querySelector('.filter-toggle');
  const sidebar         = document.querySelector('.horses-sidebar');
  const sidebarOverlay  = document.querySelector('.sidebar-overlay');

  filterToggleBtn?.addEventListener('click', () => {
    sidebar?.classList.toggle('open');
    sidebarOverlay?.style.setProperty('display', sidebar?.classList.contains('open') ? 'block' : 'none');
    document.body.style.overflow = sidebar?.classList.contains('open') ? 'hidden' : '';
  });
  sidebarOverlay?.addEventListener('click', () => {
    sidebar?.classList.remove('open');
    sidebarOverlay.style.display = 'none';
    document.body.style.overflow = '';
  });

  /* ── Contact form (AJAX) ── */
  document.querySelectorAll('.gh-contact-form').forEach(form => {
    form.addEventListener('submit', async e => {
      e.preventDefault();
      const btn = form.querySelector('[type=submit]');
      btn.disabled = true;
      btn.textContent = 'Envoi...';

      const formData = new FormData(form);
      formData.append('action', 'gh_submit_contact');
      formData.append('nonce', ghAjax.nonce);

      try {
        const res = await fetch(ghAjax.url, { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
          form.innerHTML = '<p class="success-msg">Message envoyé avec succès. Nous vous répondrons bientôt.</p>';
        } else {
          btn.disabled = false;
          btn.textContent = 'Envoyer le message';
          form.querySelector('.form-error')?.remove();
          const err = document.createElement('p');
          err.className = 'error-msg form-error';
          err.textContent = data.data || 'Erreur lors de l\'envoi.';
          form.prepend(err);
        }
      } catch {
        btn.disabled = false;
        btn.textContent = 'Envoyer le message';
      }
    });
  });

  /* ── Skeleton card helper ── */
  function skeletonCard() {
    return `<div class="horse-card">
      <div class="horse-card-img"><div class="skeleton" style="width:100%;height:100%;position:absolute;inset:0"></div></div>
      <div class="horse-card-body">
        <div class="skeleton" style="height:18px;width:75%;margin-bottom:.5rem"></div>
        <div class="skeleton" style="height:13px;width:50%;margin-bottom:.75rem"></div>
        <div style="display:flex;gap:.4rem;margin-bottom:.75rem">
          <div class="skeleton" style="height:20px;width:60px;border-radius:999px"></div>
          <div class="skeleton" style="height:20px;width:60px;border-radius:999px"></div>
        </div>
        <div class="skeleton" style="height:24px;width:40%;margin-top:auto"></div>
      </div>
    </div>`;
  }

  /* ── Share button ── */
  document.querySelector('.btn-share')?.addEventListener('click', () => {
    if (navigator.share) {
      navigator.share({ title: document.title, url: window.location.href }).catch(() => {});
    } else {
      navigator.clipboard?.writeText(window.location.href);
    }
  });

})();
