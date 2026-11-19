function navigateToPanel(
    event: MouseEvent<HTMLAnchorElement>,
    panelIndex: number,
) {
    if (
        event.button !== 0 ||
        event.metaKey ||
        event.ctrlKey ||
        event.shiftKey ||
        event.altKey
    )
        return;

    const trigger = ScrollTrigger.getById("portfolio-lateral");
    if (!trigger) return;

    event.preventDefault();
    window.scrollTo({
        top: trigger.labelToScroll(
            ["accueil", "profil", "projets", "contact"][panelIndex],
        ),
        behavior: "smooth",
    });
}

const navigateTo = (href: string) => (event: MouseEvent<HTMLAnchorElement>) => {
    const panelIndex =
        href === "#profil"
            ? 1
            : href === "#projets"
                ? 2
                : href === "#contact"
                    ? 3
                    : 0;
    navigateToPanel(event, panelIndex);
};
