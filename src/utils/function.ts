export const slugify = (str: string) => {
  return str
    .normalize('NFD') // Décompose les accents
    .replace(/[\u0300-\u036f]/g, '') // Supprime les accents
    .replace(/'/g, '-') // Remplace les apostrophes par des tirets
    .replace(/[^a-zA-Z0-9\s-_]/g, '') // Enlève tout ce qui n'est pas lettre, chiffre, espace, tiret ou underscore
    .trim() // Trim des espaces autour
    .replace(/\s+/g, '_') // Remplace tous les espaces par des underscores
    .replace(/-+/g, '-') // Évite plusieurs tirets consécutifs
    .toLowerCase(); // Minuscule
};
