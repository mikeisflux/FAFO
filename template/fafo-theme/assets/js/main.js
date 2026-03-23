/**
 * FAFO Theme - Main JavaScript
 * For America First Only
 */

(function($) {
    'use strict';

    // ============================================================
    // MOBILE NAV TOGGLE
    // ============================================================
    const navToggle = document.getElementById('navToggle');
    const navMenu   = document.getElementById('primaryMenu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            const isOpen = navMenu.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        // Close nav on outside click
        document.addEventListener('click', function(e) {
            if (!navMenu.contains(e.target) && !navToggle.contains(e.target)) {
                navMenu.classList.remove('open');
                navToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // ============================================================
    // TICKER PAUSE ON HOVER
    // ============================================================
    const ticker = document.getElementById('fafoTicker');
    if (ticker) {
        ticker.parentElement.addEventListener('mouseenter', function() {
            ticker.style.animationPlayState = 'paused';
        });
        ticker.parentElement.addEventListener('mouseleave', function() {
            ticker.style.animationPlayState = 'running';
        });
    }

    // ============================================================
    // COPY LINK BUTTON
    // ============================================================
    const copyBtn = document.getElementById('copyLinkBtn');
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(window.location.href).then(function() {
                    copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    setTimeout(function() {
                        copyBtn.innerHTML = '<i class="far fa-copy"></i> Copy Link';
                    }, 2500);
                });
            } else {
                // Fallback
                const el = document.createElement('textarea');
                el.value = window.location.href;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(function() {
                    copyBtn.innerHTML = '<i class="far fa-copy"></i> Copy Link';
                }, 2500);
            }
        });
    }

    // ============================================================
    // NEWSLETTER AJAX FORM
    // ============================================================
    const newsletterForm = document.getElementById('fafoNewsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email  = newsletterForm.querySelector('input[type="email"]').value;
            const btn    = newsletterForm.querySelector('button[type="submit"]');
            const orig   = btn.innerHTML;

            if (!email) return;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing up...';
            btn.disabled  = true;

            // If fafoData is available (AJAX)
            if (typeof fafoData !== 'undefined') {
                fetch(fafoData.ajaxUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=fafo_newsletter&email=' + encodeURIComponent(email) + '&nonce=' + fafoData.nonce
                })
                .then(r => r.json())
                .then(function(data) {
                    if (data.success) {
                        btn.innerHTML = '<i class="fas fa-check"></i> ' + (data.data.message || 'Subscribed!');
                        btn.style.background = '#155724';
                    } else {
                        btn.innerHTML = '<i class="fas fa-times"></i> Error. Try again.';
                        btn.style.background = '#721c24';
                        setTimeout(function() {
                            btn.innerHTML = orig;
                            btn.style.background = '';
                            btn.disabled = false;
                        }, 3000);
                    }
                })
                .catch(function() {
                    btn.innerHTML = orig;
                    btn.disabled  = false;
                });
            } else {
                // Demo fallback
                setTimeout(function() {
                    btn.innerHTML = '<i class="fas fa-check"></i> Thank you, Patriot!';
                    btn.style.background = '#155724';
                }, 1000);
            }
        });
    }

    // ============================================================
    // STICKY HEADER SHRINK ON SCROLL
    // ============================================================
    const mainNav = document.querySelector('.main-nav');
    let lastScroll = 0;

    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;

        if (mainNav) {
            if (currentScroll > 200) {
                mainNav.style.boxShadow = '0 4px 30px rgba(0,0,0,0.5)';
            } else {
                mainNav.style.boxShadow = '0 4px 20px rgba(0,0,0,0.4)';
            }
        }

        lastScroll = currentScroll;
    }, { passive: true });

    // ============================================================
    // LAZY LOAD IMAGES (IntersectionObserver)
    // ============================================================
    if ('IntersectionObserver' in window) {
        const imgs = document.querySelectorAll('img[data-src]');
        const imgObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imgObserver.unobserve(img);
                }
            });
        }, { rootMargin: '200px' });

        imgs.forEach(function(img) { imgObserver.observe(img); });
    }

    // ============================================================
    // SMOOTH SCROLL FOR ANCHOR LINKS
    // ============================================================
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ============================================================
    // NEWS CARD HOVER EFFECTS (keyboard accessible)
    // ============================================================
    document.querySelectorAll('.news-card a, .hero-main a, .hero-thumb a').forEach(function(link) {
        link.addEventListener('focus', function() {
            this.closest('.news-card, .hero-main, .hero-thumb')?.classList.add('focused');
        });
        link.addEventListener('blur', function() {
            this.closest('.news-card, .hero-main, .hero-thumb')?.classList.remove('focused');
        });
    });

    // ============================================================
    // DATE FORMATTING: "X minutes ago" for recent posts
    // ============================================================
    document.querySelectorAll('[data-timestamp]').forEach(function(el) {
        const ts   = parseInt(el.dataset.timestamp, 10) * 1000;
        const now  = Date.now();
        const diff = Math.floor((now - ts) / 1000);

        let label;
        if      (diff < 60)    label = 'Just now';
        else if (diff < 3600)  label = Math.floor(diff / 60) + ' min ago';
        else if (diff < 86400) label = Math.floor(diff / 3600) + ' hr ago';
        else                   return;

        el.textContent = label;
    });

})(typeof jQuery !== 'undefined' ? jQuery : { fn: {} });
