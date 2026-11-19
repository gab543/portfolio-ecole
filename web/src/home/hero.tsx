import { useRef, type MouseEvent } from 'react';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { heroContent } from '../content/hero';
import './_hero.scss';

export function Hero(){
    const viewportRef = useRef<HTMLDivElement | null>(null);
    
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

    function ArrowMark() {
        return (
            <svg aria-hidden="true" viewBox="0 0 16 16" fill="none">
            <path d="M3.25 12.75 12.5 3.5M4.5 3.5h8v8" stroke="currentColor" strokeWidth="1.4" />
            </svg>
        );
    }

    return (
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
    )
}
