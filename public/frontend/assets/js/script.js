/* ============================================================
   PORTFOLIO — SCRIPT.JS  (Full Build)
   ============================================================ */

$(document).ready(function () {

  gsap.registerPlugin(ScrollTrigger, TextPlugin);

  /* ── 1. THEME ── */
  const html  = document.documentElement;
  const saved = localStorage.getItem('theme') || 'dark';
  html.setAttribute('data-theme', saved);
  updateThemeIcon(saved);

  $('#themeToggle').on('click', function () {
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateThemeIcon(next);
  });
  function updateThemeIcon(t) {
    $('#themeIcon').toggleClass('fa-moon', t === 'dark').toggleClass('fa-sun', t === 'light');
  }

  /* ── 2. CUSTOM CURSOR ── */
  const dot = $('.cursor-dot'), outline = $('.cursor-outline');
  let mouseX = 0, mouseY = 0, ox = 0, oy = 0;
  $(document).on('mousemove', function (e) {
    mouseX = e.clientX; mouseY = e.clientY;
    dot.css({ left: mouseX, top: mouseY });
  });
  (function loop() {
    ox += (mouseX - ox) * 0.12; oy += (mouseY - oy) * 0.12;
    outline.css({ left: ox, top: oy });
    requestAnimationFrame(loop);
  })();
  $('a, button, .filter-btn, .t-dot, .proj-link, .author-btn').on('mouseenter', function () {
    dot.css({ width: '12px', height: '12px' });
    outline.css({ width: '50px', height: '50px', borderColor: 'rgba(0,229,255,.6)' });
  }).on('mouseleave', function () {
    dot.css({ width: '6px', height: '6px' });
    outline.css({ width: '32px', height: '32px', borderColor: 'rgba(0,229,255,.4)' });
  });

  /* ── 3. NAVBAR SCROLL ── */
  $(window).on('scroll.nav', function () {
    $('#mainNav').toggleClass('scrolled', $(this).scrollTop() > 60);
  });

  /* ── 4. BACK TO TOP ── */
  const $btt = $('#backToTop');
  $(window).on('scroll.btt', function () {
    $btt.toggleClass('show', $(this).scrollTop() > 400);
  });
  $btt.on('click', function () {
    $('html, body').animate({ scrollTop: 0 }, 600, 'swing');
  });

  /* ── 5. HERO GSAP ENTRANCE ── */
  const tl = gsap.timeline({ delay: 0.3 });
  tl.from('.hero-badge',       { opacity: 0, y: 30, duration: 0.6, ease: 'power3.out' })
    .from('.line-inner',       { y: '100%', opacity: 0, duration: 0.9, stagger: 0.12, ease: 'power4.out' }, '-=0.2')
    .from('.hero-sub',         { opacity: 0, y: 20, duration: 0.6, ease: 'power3.out' }, '-=0.4')
    .from('.hero-actions',     { opacity: 0, y: 20, duration: 0.5, ease: 'power3.out' }, '-=0.3')
    .from('.hero-stats .stat, .hero-stats .stat-divider', { opacity: 0, y: 20, duration: 0.5, stagger: 0.1, ease: 'power3.out' }, '-=0.2')
    .from('.hero-avatar-wrap', { opacity: 0, scale: 0.85, x: 40, duration: 1, ease: 'power3.out' }, '-=0.8')
    .from('.float-card',       { opacity: 0, y: 20, duration: 0.5, stagger: 0.15, ease: 'back.out(1.4)' }, '-=0.5')
    .from('.scroll-indicator', { opacity: 0, y: 10, duration: 0.5, ease: 'power2.out' }, '-=0.2');

  /* ── 6. TYPED TEXT ── */
  const words = ['Laravel Developer','WordPress Expert','Plugin Developer','Bug Fixer','Full Stack Dev','API Architect'];
  let wi = 0, ci = 0, del = false;
  function typeLoop() {
    const w = words[wi];
    if (del) {
      ci--;
      $('.typed-text').text(w.substring(0, ci));
      if (ci === 0) { del = false; wi = (wi + 1) % words.length; setTimeout(typeLoop, 400); return; }
      setTimeout(typeLoop, 45);
    } else {
      ci++;
      $('.typed-text').text(w.substring(0, ci));
      if (ci === w.length) { del = true; setTimeout(typeLoop, 2000); return; }
      setTimeout(typeLoop, 80);
    }
  }
  setTimeout(typeLoop, 1500);

  /* ── 7. COUNTER UTILITY ── */
  function runCounter($els) {
    $els.each(function () {
      const $el = $(this);
      if ($el.data('counted')) return;
      $el.data('counted', true);
      const raw = $el.data('target'), isFloat = String(raw).includes('.');
      $({ val: 0 }).animate({ val: parseFloat(raw) }, {
        duration: 2000, easing: 'swing',
        step: function () { $el.text(isFloat ? parseFloat(this.val).toFixed(1) : Math.floor(this.val).toLocaleString()); },
        complete: function () { $el.text(isFloat ? parseFloat(raw).toFixed(1) : parseInt(raw).toLocaleString()); }
      });
    });
  }

  ScrollTrigger.create({ trigger: '.hero-stats', start: 'top 80%', once: true,
    onEnter: function () { runCounter($('.stat-num')); }
  });
  ScrollTrigger.create({ trigger: '.author-combined', start: 'top 85%', once: true,
    onEnter: function () { runCounter($('.combined-num')); }
  });

  /* ── 8. SKILL BARS ── */
  ScrollTrigger.create({ trigger: '#skills', start: 'top 70%', once: true, onEnter: function () {
    $('.skill-fill').each(function () { $(this).css('width', $(this).data('width') + '%'); });
  }});

  /* ── 9. SCROLL ANIMATIONS ── */
  gsap.from('.marquee-strip',{scrollTrigger:{trigger:'.marquee-strip',start:'top 90%'},opacity:0,duration:.6});
  gsap.from('.about-img-wrap',{scrollTrigger:{trigger:'#about',start:'top 70%'},opacity:0,x:-60,duration:.9,ease:'power3.out'});
  gsap.from('.about-section .section-label,.about-section .section-title,.about-text,.about-tags,.about-section .btn-primary-custom',{scrollTrigger:{trigger:'#about',start:'top 70%'},duration:.8,stagger:.12,ease:'power3.out'});
  gsap.from('.skill-card',{scrollTrigger:{trigger:'#skills',start:'top 72%'}, y:50,duration:.7,stagger:.08,ease:'power3.out'});
  gsap.from('.project-card',{scrollTrigger:{trigger:'#projects',start:'top 72%'}, y:60,duration:.7,stagger:.09,ease:'power3.out'});
  gsap.from('.author-card',{scrollTrigger:{trigger:'#author',start:'top 72%'},y:50,duration:.7,stagger:.15,ease:'power3.out'});
  gsap.from('.author-combined',{scrollTrigger:{trigger:'.author-combined',start:'top 85%'},opacity:0,y:30,duration:.6,ease:'power3.out'});
  gsap.from('.product-card',{scrollTrigger:{trigger:'#products',start:'top 72%'}, y:60,duration:.7,stagger:.12,ease:'power3.out'});
  gsap.from('.contact-section .col-lg-5 > *',{scrollTrigger:{trigger:'#contact',start:'top 72%'},opacity:0,x:-40,duration:.7,stagger:.1,ease:'power3.out'});
  gsap.from('.contact-form-wrap',{scrollTrigger:{trigger:'#contact',start:'top 72%'},opacity:0,x:40,duration:.8,ease:'power3.out'});

  /* ── 10. PROJECT FILTER ── */
  $('.filter-btn').on('click', function () {
    $('.filter-btn').removeClass('active');
    $(this).addClass('active');
    const f = $(this).data('filter');
    if (f === 'all') {
      $('.project-card').removeClass('hidden').css({ opacity: 0, transform: 'scale(0.9)' });
      gsap.to('.project-card', { opacity: 1, scale: 1, duration: 0.4, stagger: 0.07 });
    } else {
      $('.project-card').each(function () {
        if ($(this).data('cat') === f) {
          $(this).removeClass('hidden').css({ opacity: 0, transform: 'scale(0.9)' });
          gsap.to($(this), { opacity: 1, scale: 1, duration: 0.4 });
        } else {
          $(this).addClass('hidden');
        }
      });
    }
  });

  /* ── 11. TESTIMONIAL SLIDER ── */
  let curT = 0;
  function showTesti(i) {
    $('.testi-card').removeClass('active');
    $('.t-dot').removeClass('active');
    $($('.testi-card')[i]).addClass('active');
    $($('.t-dot')[i]).addClass('active');
    curT = i;
  }
  $('.t-dot').on('click', function () { showTesti(parseInt($(this).data('idx'))); });
  setInterval(function () { showTesti((curT + 1) % $('.testi-card').length); }, 4000);

  /* ── 12. CONTACT FORM ── */
  $('#sendBtn').on('click', function () {
    const $b = $(this), orig = $b.html();
    $b.html('<i class="fas fa-spinner fa-spin me-2"></i> Sending...').prop('disabled', true);
    // Example axios POST (replace URL with real endpoint)
    // axios.post('/api/contact', { name, email, message }).then(...)
    setTimeout(function () {
      $b.html('<i class="fas fa-check me-2"></i> Sent!').css({ background: '#00e676', boxShadow: '0 0 20px rgba(0,230,118,.4)' });
      setTimeout(function () { $b.html(orig).prop('disabled', false).css({ background: '', boxShadow: '' }); }, 3000);
    }, 1500);
  });

  /* ── 13. PARALLAX ORBS ── */
  $(document).on('mousemove', function (e) {
    const x = e.clientX / window.innerWidth - 0.5;
    const y = e.clientY / window.innerHeight - 0.5;
    gsap.to('.orb1', { x: x * 40, y: y * 40, duration: 1.5, ease: 'power1.out' });
    gsap.to('.orb2', { x: -x * 30, y: -y * 30, duration: 1.5, ease: 'power1.out' });
    gsap.to('.orb3', { x: x * 20, y: y * 20, duration: 1.5, ease: 'power1.out' });
  });

  /* ── 14. SMOOTH ANCHOR SCROLL ── */
  $('a[href^="#"]').on('click', function (e) {
    const t = $($(this).attr('href'));
    if (t.length) { e.preventDefault(); $('html,body').animate({ scrollTop: t.offset().top - 80 }, 700); }
  });

  /* ── 15. SECTION REVEAL ── */
  gsap.utils.toArray('.section-pad').forEach(function (s) {
    gsap.from(s, { scrollTrigger: { trigger: s, start: 'top 88%', toggleActions: 'play none none none' }, opacity: 0, y: 24, duration: .65, ease: 'power2.out' });
  });

  /* ── 16. AXIOS EXAMPLE (ready to use) ── */
  // Example: load portfolio stats via API
  // axios.get('/api/stats').then(function(res) {
  //   console.log(res.data);
  // });

  /* ── 17. DATATABLES EXAMPLE (ready to use) ── */
  // If you add a <table id="projectsTable"> anywhere, init like:
  // $('#projectsTable').DataTable({ responsive: true, pageLength: 5 });

});