(function () {
	'use strict';

	// Mobile menu
	var toggle = document.querySelector('.menu-toggle');
	var nav = document.getElementById('main-nav');
	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var open = nav.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			toggle.setAttribute('aria-label', open ? 'Zamknij menu' : 'Otwórz menu');
		});
		nav.addEventListener('click', function (e) {
			if (e.target.closest('a')) {
				nav.classList.remove('is-open');
				toggle.setAttribute('aria-expanded', 'false');
			}
		});
	}

	// Lite YouTube: load the player only on click
	document.addEventListener('click', function (e) {
		var btn = e.target.closest('.yt[data-yt]');
		if (!btn || btn.querySelector('iframe')) return;
		var id = btn.getAttribute('data-yt');
		var f = document.createElement('iframe');
		f.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(id) + '?autoplay=1&rel=0';
		f.title = btn.getAttribute('aria-label') || 'YouTube';
		f.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
		f.allowFullscreen = true;
		btn.innerHTML = '';
		btn.appendChild(f);
		btn.removeAttribute('aria-label');
	});

	// Respect reduced motion for hero videos
	if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		document.querySelectorAll('.hero-tile video').forEach(function (v) { v.pause(); v.removeAttribute('autoplay'); });
	}
})();

(function () {
	'use strict';
	// Click-to-load: map and Spotify
	document.addEventListener('click', function (e) {
		var btn = e.target.closest('.embed-gate-btn');
		if (!btn) return;
		var gate = btn.closest('.embed-gate');
		var f = document.createElement('iframe');
		f.src = gate.getAttribute('data-src');
		f.title = gate.getAttribute('data-title') || '';
		f.height = gate.getAttribute('data-height') || '380';
		f.loading = 'lazy';
		f.allow = 'autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture';
		f.referrerPolicy = 'no-referrer-when-downgrade';
		gate.innerHTML = '';
		gate.classList.add('is-loaded');
		gate.appendChild(f);
		f.focus();
	});
	// Copy calendar address
	document.addEventListener('click', function (e) {
		var b = e.target.closest('[data-copy]');
		if (!b) return;
		var txt = b.getAttribute('data-copy');
		var done = function () { var o = b.textContent; b.textContent = 'Skopiowano'; setTimeout(function () { b.textContent = o; }, 2000); };
		if (navigator.clipboard) { navigator.clipboard.writeText(txt).then(done, function () {}); }
		else { var i = b.parentNode.querySelector('input'); i.select(); document.execCommand('copy'); done(); }
	});
})();
