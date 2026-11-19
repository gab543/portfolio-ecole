import { useRef, type MouseEvent } from 'react';
import gsap from 'gsap';
import { useGSAP } from '@gsap/react';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import './styles/_story.scss';

gsap.registerPlugin(ScrollTrigger, useGSAP);

const panelCount = 4;
const motionTiming = {
  compression: 0.42,
  crossing: 0.1,
  overshoot: 0.06,
  settle: 0.14,
  lateral: 2.8,
} as const;


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

  return (
    <main>
      <div ref={viewportRef} className="story-viewport">
        <div className="story-track">
          <section className="placeholder-section story-panel" id="profil" aria-label="Profil">
            <p>Profil</p>
          </section>
          <section className="placeholder-section story-panel" id="projets" aria-label="Projets">
            <p>Projets</p>
          </section>
          <section className="placeholder-section story-panel" id="contact" aria-label="Contact">
            <p>Contact</p>
          </section>
        </div>
      </div>
    </main>
  );
}
