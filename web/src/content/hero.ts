export const heroContent = {
  brand: 'GC',
  navigation: [
    { label: 'Profil', href: '#profil' },
    { label: 'Projets', href: '#projets' },
    { label: 'Contact', href: '#contact' },
  ],
  allProjectsLabel: 'Tous les projets',
  cvLabel: 'CV à ajouter',
  title: ['Du développement', 'à la cybersécurité'],
  name: 'Gabriel Caboche',
  role: 'Étudiant en développement full stack, orienté vers la cybersécurité.',
  location: 'Paris / Île-de-France',
  availability: 'Stage de 4 mois · avril à juillet 2027',
  focus: 'Cybersécurité en priorité · ouvert au full stack',
  continuation: 'Possibilité de poursuivre en alternance',
  primaryAction: { label: 'Découvrir mes projets', href: '#projets' },
  secondaryAction: { label: 'Me contacter', href: '#contact' },
  // TODO: Replace provisional copy with API-managed hero content.
} as const;
