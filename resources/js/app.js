import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/**
 * Scroll-reveal: tambah .is-visible saat [data-reveal] masuk viewport.
 * Dukung delay via data-reveal-delay="150" (ms).
 */
function initReveal() {
    const els = document.querySelectorAll('[data-reveal]:not(.is-visible)');
    if (!('IntersectionObserver' in window) || els.length === 0) {
        els.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const delay = Number.parseInt(el.dataset.revealDelay ?? '0', 10);
                window.setTimeout(() => el.classList.add('is-visible'), Number.isNaN(delay) ? 0 : delay);
                observer.unobserve(el);
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -8% 0px' },
    );

    els.forEach((el) => {
        if (reduceMotion) {
            el.classList.add('is-visible');
        } else {
            observer.observe(el);
        }
    });
}

/**
 * Counter animasi: [data-count-to="42"] mencacah 0 -> target saat terlihat.
 */
function initCounters() {
    const els = document.querySelectorAll('[data-count-to]');
    if (els.length === 0) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const animate = (el) => {
        const target = Number.parseFloat(el.dataset.countTo ?? '0');
        if (Number.isNaN(target)) return;
        const duration = Number.parseInt(el.dataset.countDuration ?? '1200', 10);

        if (reduceMotion || duration <= 0) {
            el.textContent = formatCount(target);
            return;
        }

        const start = performance.now();
        const step = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            // easeOutCubic
            const eased = 1 - (1 - progress) ** 3;
            el.textContent = formatCount(target * eased);
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    };

    const formatCount = (value) => {
        const rounded = Math.round(value);
        return rounded.toLocaleString('id-ID');
    };

    if (!('IntersectionObserver' in window)) {
        els.forEach(animate);
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                animate(entry.target);
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.4 },
    );

    els.forEach((el) => observer.observe(el));
}

/**
 * Starfield partikel di hero dengan drift dan twinkle individual.
 */
function initHeroStars() {
    const canvas = document.querySelector('[data-hero-stars]');
    if (!canvas) return;

    const context = canvas.getContext('2d');
    if (!context) return;

    const section = canvas.closest('#beranda');
    if (!section) return;

    const particles = [];
    let width = 0;
    let height = 0;
    let animationFrame = 0;
    let previousTime = 0;
    let isVisible = true;

    const createParticles = () => {
        const count = Math.min(180, Math.max(70, Math.round((width * height) / 4200)));
        particles.length = 0;

        for (let i = 0; i < count; i += 1) {
            particles.push({
                x: Math.random() * width,
                y: height * (0.58 + Math.random() * 0.44),
                radius: 0.45 + Math.random() * 1.15,
                opacity: 0.3 + Math.random() * 0.65,
                phase: Math.random() * Math.PI * 2,
                twinkleSpeed: 0.5 + Math.random() * 1.6,
                driftX: (Math.random() - 0.5) * 5,
                driftY: 2 + Math.random() * 7,
            });
        }
    };

    const resize = () => {
        const bounds = section.getBoundingClientRect();
        const pixelRatio = Math.min(window.devicePixelRatio || 1, 2);
        width = bounds.width;
        height = bounds.height;
        canvas.width = Math.round(width * pixelRatio);
        canvas.height = Math.round(height * pixelRatio);
        context.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
        createParticles();
        draw(0, false);
    };

    const draw = (time, animate) => {
        context.clearRect(0, 0, width, height);
        const delta = previousTime ? Math.min((time - previousTime) / 1000, 0.05) : 0;
        previousTime = time;

        particles.forEach((particle) => {
            if (animate) {
                particle.x += particle.driftX * delta;
                particle.y -= particle.driftY * delta;

                if (particle.y < height * 0.56) {
                    particle.y = height + Math.random() * height * 0.08;
                    particle.x = Math.random() * width;
                }
                if (particle.x < 0) particle.x = width;
                if (particle.x > width) particle.x = 0;
            }

            const twinkle = animate
                ? 0.45 + ((Math.sin(time / 1000 * particle.twinkleSpeed + particle.phase) + 1) * 0.275)
                : 0.7;
            const alpha = particle.opacity * twinkle;

            context.beginPath();
            context.fillStyle = `rgba(226, 218, 255, ${alpha})`;
            context.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
            context.fill();

            if (particle.radius > 1.25) {
                const glow = context.createRadialGradient(
                    particle.x,
                    particle.y,
                    0,
                    particle.x,
                    particle.y,
                    particle.radius * 5,
                );
                glow.addColorStop(0, `rgba(174, 143, 255, ${alpha * 0.32})`);
                glow.addColorStop(1, 'rgba(174, 143, 255, 0)');
                context.fillStyle = glow;
                context.fillRect(
                    particle.x - particle.radius * 5,
                    particle.y - particle.radius * 5,
                    particle.radius * 10,
                    particle.radius * 10,
                );
            }
        });

        if (animate && isVisible) {
            animationFrame = window.requestAnimationFrame((nextTime) => draw(nextTime, true));
        }
    };

    const start = () => {
        if (animationFrame || !isVisible) return;
        previousTime = 0;
        animationFrame = window.requestAnimationFrame((time) => {
            animationFrame = 0;
            draw(time, true);
        });
    };

    const stop = () => {
        if (animationFrame) {
            window.cancelAnimationFrame(animationFrame);
            animationFrame = 0;
        }
        previousTime = 0;
        draw(0, false);
    };

    const visibilityObserver = new IntersectionObserver(([entry]) => {
        isVisible = entry.isIntersecting;
        if (isVisible) start();
        else stop();
    });

    const resizeObserver = new ResizeObserver(resize);
    visibilityObserver.observe(section);
    resizeObserver.observe(section);
    resize();
    start();
}

/**
 * Lightbox global: klik [data-lightbox] membuka overlay fullscreen.
 * Grup navigasi via data-lightbox-group="nama-grup".
 */
function initLightbox() {
    const overlay = document.getElementById('lightbox');
    if (!overlay) return;
    const img = overlay.querySelector('[data-lightbox-image]');
    const caption = overlay.querySelector('[data-lightbox-caption]');
    const prevBtn = overlay.querySelector('[data-lightbox-prev]');
    const nextBtn = overlay.querySelector('[data-lightbox-next]');
    let groupItems = [];
    let currentIndex = -1;

    const showItem = (index) => {
        if (groupItems.length === 0) return;
        currentIndex = (index + groupItems.length) % groupItems.length;
        const item = groupItems[currentIndex];
        img.src = item.src;
        img.alt = item.alt;
        if (caption) caption.textContent = item.caption;
        const single = groupItems.length <= 1;
        prevBtn?.classList.toggle('hidden', single);
        nextBtn?.classList.toggle('hidden', single);
    };

    const open = (trigger) => {
        const group = trigger.dataset.lightboxGroup;
        if (group) {
            groupItems = [...document.querySelectorAll(`[data-lightbox][data-lightbox-group="${group}"]`)].map((el) => ({
                src: el.dataset.lightbox ?? el.getAttribute('href') ?? el.src,
                alt: el.dataset.lightboxAlt ?? el.querySelector('img')?.alt ?? '',
                caption: el.dataset.lightboxCaption ?? '',
            }));
            currentIndex = groupItems.findIndex(
                (item) => item.src === (trigger.dataset.lightbox ?? trigger.getAttribute('href') ?? trigger.src),
            );
        } else {
            groupItems = [
                {
                    src: trigger.dataset.lightbox ?? trigger.getAttribute('href') ?? trigger.src,
                    alt: trigger.dataset.lightboxAlt ?? trigger.querySelector('img')?.alt ?? '',
                    caption: trigger.dataset.lightboxCaption ?? '',
                },
            ];
            currentIndex = 0;
        }
        showItem(currentIndex < 0 ? 0 : currentIndex);
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    const close = () => {
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        img.removeAttribute('src');
        groupItems = [];
        currentIndex = -1;
    };

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-lightbox]');
        if (!trigger) return;
        // Biarkan link tanpa grup tetap dinavigasi bila user Ctrl/Cmd+klik.
        if (event.metaKey || event.ctrlKey) return;
        event.preventDefault();
        open(trigger);
    });

    overlay.addEventListener('click', (event) => {
        if (event.target.closest('[data-lightbox-close]') || event.target === overlay) close();
    });

    prevBtn?.addEventListener('click', (event) => {
        event.stopPropagation();
        showItem(currentIndex - 1);
    });
    nextBtn?.addEventListener('click', (event) => {
        event.stopPropagation();
        showItem(currentIndex + 1);
    });

    document.addEventListener('keydown', (event) => {
        if (overlay.classList.contains('hidden')) return;
        if (event.key === 'Escape') close();
        if (event.key === 'ArrowLeft') showItem(currentIndex - 1);
        if (event.key === 'ArrowRight') showItem(currentIndex + 1);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initReveal();
    initCounters();
    initHeroStars();
    initLightbox();
});
