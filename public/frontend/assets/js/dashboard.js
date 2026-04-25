/* ============================================================
   DASHBOARD — DASHBOARD.JS (Complete Fixed Build)
   ============================================================ */

$(document).ready(function () {

  /* ── 1. THEME ── */
  const html  = document.documentElement;
  const saved = localStorage.getItem('dashTheme') || 'dark';
  html.setAttribute('data-theme', saved);
  updateThemeIcon(saved);
  if (saved === 'light') $('#darkModeToggle').prop('checked', false);

  $('#dashThemeToggle').on('click', toggleTheme);
  $('#darkModeToggle').on('change', function () {
    const t = $(this).is(':checked') ? 'dark' : 'light';
    html.setAttribute('data-theme', t);
    localStorage.setItem('dashTheme', t);
    updateThemeIcon(t);
  });

  function toggleTheme() {
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('dashTheme', next);
    updateThemeIcon(next);
    $('#darkModeToggle').prop('checked', next === 'dark');
  }
  function updateThemeIcon(t) {
    $('#dashThemeIcon').toggleClass('fa-moon', t === 'dark').toggleClass('fa-sun', t === 'light');
  }

  /* ── 2. NAVIGATION ── */
  window.showSection = function (name) {
    $('.dash-section').removeClass('active');
    $('#sec-' + name).addClass('active');
    $('.sidebar-link').removeClass('active');
    $('[data-section="' + name + '"]').addClass('active');
    $('#pageTitle').text(name.charAt(0).toUpperCase() + name.slice(1));
    if (name === 'overview') { setTimeout(animateMiniSkills, 300); }
    animateSectionIn();
  };

  $(document).on('click', '[data-section]', function (e) {
    const s = $(this).data('section');
    if (!s) return;
    const targetSection = document.getElementById('sec-' + s);
    if (!targetSection) return;
    // Prevent default only for plain anchor links (not buttons)
    if ($(this).is('a')) { e.preventDefault(); }
    // Close any open Bootstrap dropdowns
    const $dropdown = $(this).closest('.dropdown');
    if ($dropdown.length) {
      const dropdownEl = $dropdown.find('[data-bs-toggle="dropdown"]')[0];
      if (dropdownEl) {
        const bsDropdown = bootstrap.Dropdown.getInstance(dropdownEl);
        if (bsDropdown) bsDropdown.hide();
      }
    }
    showSection(s);
  });

  function animateSectionIn() {
    if (typeof gsap !== 'undefined') {
      gsap.from('.dash-section.active > *', { y: 12, opacity: 0, duration: 0.35, stagger: 0.05, ease: 'power2.out' });
    }
  }

  /* ── 3. STAT COUNTERS ── */
  function animateCounters() {
    $('.stat-val').each(function () {
      const $el = $(this), raw = $el.data('target'), isFloat = String(raw).includes('.');
      $({ val: 0 }).animate({ val: parseFloat(raw) }, {
        duration: 1800, easing: 'swing',
        step: function () { $el.text(isFloat ? parseFloat(this.val).toFixed(1) : Math.floor(this.val).toLocaleString()); },
        complete: function () { $el.text(isFloat ? parseFloat(raw).toFixed(1) : parseInt(raw).toLocaleString()); }
      });
    });
  }
  setTimeout(animateCounters, 400);

  /* ── 4. MINI SKILL BARS ── */
  function animateMiniSkills() {
    $('.ms-fill').each(function () { $(this).css('width', $(this).data('w') + '%'); });
  }
  animateMiniSkills();

  /* ── 5. CHARTS ── */
  // Visitor chart
  const visitorCtx = document.getElementById('visitorChart');
  if (visitorCtx) {
    const vc = new Chart(visitorCtx, {
      type: 'line',
      data: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        datasets: [{
          label: 'Visitors', data: [340,520,410,780,650,920,840],
          fill: true,
          backgroundColor: function (ctx) {
            const g = ctx.chart.ctx.createLinearGradient(0,0,0,280);
            g.addColorStop(0, 'rgba(0,229,255,0.2)'); g.addColorStop(1, 'rgba(0,229,255,0)'); return g;
          },
          borderColor: '#00e5ff', borderWidth: 2.5,
          pointBackgroundColor: '#00e5ff', pointBorderColor: '#080b10',
          pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7, tension: 0.4
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: true,
        plugins: { legend: { display: false }, tooltip: { backgroundColor: '#12181f', borderColor: 'rgba(0,229,255,0.3)', borderWidth: 1, titleColor: '#e8edf2', bodyColor: '#8899aa', padding: 12, cornerRadius: 10 } },
        scales: {
          x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#8899aa', font: { family: 'JetBrains Mono', size: 11 } } },
          y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#8899aa', font: { family: 'JetBrains Mono', size: 11 } } }
        }
      }
    });
    const chartData = {
      Week:  { labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'], data: [340,520,410,780,650,920,840] },
      Month: { labels: ['Wk1','Wk2','Wk3','Wk4'], data: [3200,4100,3750,5200] },
      Year:  { labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'], data: [8200,7400,9300,11200,10800,13400,12100,14200,11900,15300,14100,16800] }
    };
    $('.ct').on('click', function () {
      $('.ct').removeClass('active'); $(this).addClass('active');
      const d = chartData[$(this).text()];
      vc.data.labels = d.labels; vc.data.datasets[0].data = d.data; vc.update('active');
    });
  }

  // Source doughnut
  const sourceCtx = document.getElementById('sourceChart');
  if (sourceCtx) {
    const sd = { labels: ['Direct','GitHub','LinkedIn','Google','Other'], datasets: [{ data: [35,25,20,15,5], backgroundColor: ['#00e5ff','#7b61ff','#ff4d6d','#00e676','#ff9800'], borderColor: '#12181f', borderWidth: 3, hoverOffset: 8 }] };
    new Chart(sourceCtx, { type: 'doughnut', data: sd, options: { responsive: true, cutout: '70%', plugins: { legend: { display: false }, tooltip: { backgroundColor: '#12181f', borderColor: 'rgba(255,255,255,0.1)', borderWidth: 1, titleColor: '#e8edf2', bodyColor: '#8899aa', padding: 10, cornerRadius: 8 } } } });
    const cols = ['#00e5ff','#7b61ff','#ff4d6d','#00e676','#ff9800'];
    sd.labels.forEach(function (l, i) {
      $('#sourceLegend').append(`<div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted)"><span style="width:10px;height:10px;border-radius:50%;background:${cols[i]};flex-shrink:0"></span><span style="flex:1">${l}</span><span style="color:var(--text)">${sd.datasets[0].data[i]}%</span></div>`);
    });
  }

  // Monthly bar
  const monthCtx = document.getElementById('monthlyChart');
  if (monthCtx) {
    new Chart(monthCtx, {
      type: 'bar',
      data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
          label: 'Visitors', data: [8200,7400,9300,11200,10800,13400,12100,14200,11900,15300,14100,16800],
          backgroundColor: function (ctx) { const g = ctx.chart.ctx.createLinearGradient(0,0,0,300); g.addColorStop(0,'rgba(0,229,255,0.7)'); g.addColorStop(1,'rgba(123,97,255,0.4)'); return g; },
          borderRadius: 8, borderSkipped: false
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: true,
        plugins: { legend: { display: false }, tooltip: { backgroundColor: '#12181f', borderColor: 'rgba(0,229,255,0.3)', borderWidth: 1, titleColor: '#e8edf2', bodyColor: '#8899aa', padding: 12, cornerRadius: 10 } },
        scales: { x: { grid: { display: false }, ticks: { color: '#8899aa', font: { family: 'JetBrains Mono', size: 11 } } }, y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#8899aa', font: { family: 'JetBrains Mono', size: 11 } } } }
      }
    });
  }

  // Device pie
  const deviceCtx = document.getElementById('deviceChart');
  if (deviceCtx) {
    new Chart(deviceCtx, {
      type: 'pie',
      data: { labels: ['Desktop','Mobile','Tablet'], datasets: [{ data: [58,34,8], backgroundColor: ['#00e5ff','#7b61ff','#ff9800'], borderColor: '#12181f', borderWidth: 3, hoverOffset: 6 }] },
      options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { color: '#8899aa', padding: 16, font: { family: 'JetBrains Mono', size: 12 } } }, tooltip: { backgroundColor: '#12181f', borderColor: 'rgba(255,255,255,0.1)', borderWidth: 1, titleColor: '#e8edf2', bodyColor: '#8899aa', padding: 10, cornerRadius: 8 } } }
    });
  }

  /* ── 6. SKILL SLIDERS ── */
  $(document).on('input', '.skill-range', function () {
    const v = $(this).val();
    $(this).siblings('.sm-pct').text(v + '%');
    $(this).css('--val', v + '%');
  });
  $('.skill-range').each(function () { $(this).css('--val', $(this).val() + '%'); });

  /* ── 7. MESSAGE REPLY ── */
  function sendReply() {
    const txt = $('#replyInput').val().trim(); if (!txt) return;
    const $b = $(`<div class="msg-bubble sent" style="opacity:0;transform:translateY(10px)"><p>${$('<div>').text(txt).html()}</p><small>Just now</small></div>`);
    $('.msg-content').append($b);
    if (typeof gsap !== 'undefined') {
      gsap.to($b[0], { opacity: 1, y: 0, duration: 0.4, ease: 'power2.out' });
    } else { $b.css({ opacity: 1, transform: 'none' }); }
    $('#replyInput').val('');
    $('.msg-content').scrollTop(99999);
  }
  $('#sendReply').on('click', sendReply);
  $('#replyInput').on('keydown', function (e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendReply(); } });

  /* ── 8. MESSAGE ROWS ── */
  $(document).on('click', '.msg-row', function () {
    $('.msg-row').removeClass('active');
    $(this).addClass('active').find('.unread-dot').remove();
  });

  /* ── 9. TABLE DELETE ── */
  $(document).on('click', '.ra-btn.del', function () {
    const $row = $(this).closest('tr');
    if (typeof gsap !== 'undefined') {
      gsap.to($row[0], { opacity: 0, x: -20, duration: 0.3, ease: 'power2.in', onComplete: function () { $row.remove(); } });
    } else { $row.remove(); }
  });

  /* ── 10. SEARCH ── */
  $('#dashSearch').on('input', function () {
    const q = $(this).val().toLowerCase();
    if (q) { $('.dash-table tbody tr').each(function () { $(this).toggle($(this).text().toLowerCase().includes(q)); }); }
    else { $('.dash-table tbody tr').show(); }
  });

  /* ── 11. NOTIFICATION CLEAR ── */
  $('#notifBtn').on('click', function () { $(this).find('.notif-dot').fadeOut(200); });

  /* ── 12. GSAP ENTRANCE ── */
  if (typeof gsap !== 'undefined') {
    gsap.from('.topbar',              { y: -20, opacity: 0, duration: 0.6, ease: 'power3.out' });
    gsap.from('.stat-card',           { y: 30, opacity: 1, duration: 0.6, ease: 'power3.out', delay: 0.2 });
    gsap.from('.chart-card, .dash-card', { y: 20, opacity: 0, duration: 0.5, stagger: 0.07, ease: 'power2.out', delay: 0.4 });
  }

});
