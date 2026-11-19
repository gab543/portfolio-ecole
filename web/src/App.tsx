import { useRef, type MouseEvent } from 'react';
import gsap from 'gsap';
import { useGSAP } from '@gsap/react';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { heroContent } from './content/hero';

gsap.registerPlugin(ScrollTrigger, useGSAP);

const panelCount = 4;
const motionTiming = {
  compression: 0.42,
  crossing: 0.1,
  overshoot: 0.06,
  settle: 0.14,
  lateral: 2.8,
} as const;

function ArrowMark() {
  return (
    <svg aria-hidden="true" viewBox="0 0 16 16" fill="none">
      <path d="M3.25 12.75 12.5 3.5M4.5 3.5h8v8" stroke="currentColor" strokeWidth="1.4" />
    </svg>
  );
}

export function App() {
  const viewportRef = useRef<HTMLDivElement | null>(null);

  useGSAP(() => {
    const media = gsap.matchMedia();

    media.add('(min-width: 700px) and (prefers-reduced-motion: no-preference)', () => {
      const title = gsap.utils.toArray<HTMLElement>('.hero-title', viewportRef.current)[0];
      const track = gsap.utils.toArray<HTMLElement>('.story-track', viewportRef.current)[0];

      const timeline = gsap.timeline({
        scrollTrigger: {
          id: 'portfolio-lateral',
          trigger: viewportRef.current,
          start: 'top top',
          end: () => `+=${Math.round(window.innerHeight * 3.6)}`,
          pin: true,
          scrub: true,
          anticipatePin: 1,
          invalidateOnRefresh: true,
        },
      });

      timeline
        .to(title, {
          xPercent: -5,
          scaleX: 0.79,
          scaleY: 0.94,
          transformOrigin: 'right center',
          duration: motionTiming.compression,
          ease: 'none',
        })
        .to(title, {
          xPercent: 6,
          scaleX: 0.94,
          duration: motionTiming.crossing,
          ease: 'none',
        })
        .to(title, { xPercent: 9, duration: motionTiming.overshoot, ease: 'none' })
        .to(title, { xPercent: 6, duration: motionTiming.settle, ease: 'none' })
        .addLabel('accueil', 0)
        .addLabel('lateralStart')
        .to(track, {
          xPercent: -100 * (panelCount - 1) / panelCount,
          duration: motionTiming.lateral,
          ease: 'none',
        }, 'lateralStart')
        .addLabel('profil', `lateralStart+=${motionTiming.lateral / 3}`)
        .addLabel('projets', `lateralStart+=${motionTiming.lateral * 2 / 3}`)
        .addLabel('contact');
    }, viewportRef);

    media.add('(max-width: 699px) and (prefers-reduced-motion: no-preference)', () => {
      const title = gsap.utils.toArray<HTMLElement>('.hero-title', viewportRef.current)[0];
      const hero = gsap.utils.toArray<HTMLElement>('.hero', viewportRef.current)[0];

      gsap.timeline({
        scrollTrigger: {
          trigger: hero,
          start: 'top top',
          end: 'bottom top',
          scrub: true,
        },
      })
        .to(title, { xPercent: -2, scaleX: 0.94, transformOrigin: 'right center', duration: 0.55, ease: 'none' })
        .to(title, { xPercent: 2, scaleX: 0.98, duration: 0.25, ease: 'none' })
        .to(title, { xPercent: 3, duration: 0.06, ease: 'none' })
        .to(title, { xPercent: 2, duration: 0.14, ease: 'none' });
    }, viewportRef);

    return () => media.revert();
  }, { scope: viewportRef });

  function navigateToPanel(event: MouseEvent<HTMLAnchorElement>, panelIndex: number) {
    if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

    const trigger = ScrollTrigger.getById('portfolio-lateral');
    if (!trigger) return;

    event.preventDefault();
    window.scrollTo({
      top: trigger.labelToScroll(['accueil', 'profil', 'projets', 'contact'][panelIndex]),
      behavior: 'smooth',
    });
  }

  const navigateTo = (href: string) => (event: MouseEvent<HTMLAnchorElement>) => {
    const panelIndex = href === '#profil' ? 1 : href === '#projets' ? 2 : href === '#contact' ? 3 : 0;
    navigateToPanel(event, panelIndex);
  };

  return (
    <main>
      <div ref={viewportRef} className="story-viewport">
        <div className="story-track">
          <section className="hero story-panel" aria-labelledby="hero-title">
            <header className="site-header">
              <a className="wordmark" href="#accueil" onClick={navigateTo('#accueil')} aria-label="Gabriel Caboche, accueil">
                {heroContent.brand}
              </a>
              <nav className="primary-nav" aria-label="Navigation principale">
                {heroContent.navigation.map((item) => (
                  <a key={item.href} href={item.href} onClick={navigateTo(item.href)}>{item.label}</a>
                ))}
                <a className="nav-projects" href="#projets" onClick={navigateTo('#projets')}>{heroContent.allProjectsLabel}</a>
              </nav>
              <span className="cv-placeholder" aria-label="Lien vers le CV à ajouter">
                {heroContent.cvLabel}
              </span>
            </header>

            <div className="hero-body" id="accueil">
              <h1 id="hero-title" className="hero-title">
                {heroContent.title.map((line) => <span key={line}>{line}</span>)}
              </h1>
              <p className="hero-name">{heroContent.name}</p>

              <div className="hero-bottom">
                <div className="hero-introduction">
                  <p className="hero-role">{heroContent.role}</p>
                  <p className="hero-location">{heroContent.location}</p>
                </div>

                <div className="availability">
                  <span className="availability-mark" aria-hidden="true" />
                  <div>
                    <p>{heroContent.availability}</p>
                    <p>{heroContent.focus}</p>
                    <p>{heroContent.continuation}</p>
                  </div>
                </div>

                <div className="hero-actions">
                  <a className="action action-primary" href={heroContent.primaryAction.href} onClick={navigateTo('#projets')}>
                    {heroContent.primaryAction.label}<ArrowMark />
                  </a>
                  <a className="action action-secondary" href={heroContent.secondaryAction.href} onClick={navigateTo('#contact')}>
                    {heroContent.secondaryAction.label}<ArrowMark />
                  </a>
                </div>
              </div>
            </div>
            <span className="hero-index" aria-hidden="true">Paris · FR</span>
          </section>

          <section className="placeholder-section story-panel" id="profil" aria-label="Profil">
            <p>Profil — à compléter avec des contenus validés.</p>
          </section>
          <section className="placeholder-section story-panel" id="projets" aria-label="Projets">
            <p>Projets — ajouter les projets réels à présenter.</p>
          </section>
          <section className="placeholder-section story-panel" id="contact" aria-label="Contact">
            <p>Contact — coordonnées et formulaire à définir.</p>
          </section>
        </div>
      </div>
    </main>
  );
}
