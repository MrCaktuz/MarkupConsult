// middleware.ts
import { NextRequest, NextResponse } from 'next/server';
import { LANGS_AVAILABLE } from './utils/const';

const supportedLangs = Object.values(LANGS_AVAILABLE);
const defaultLang = LANGS_AVAILABLE.EN;

export function middleware(request: NextRequest) {
  // Récupérer la langue dans les cookies
  const langCookie = request.cookies.get('lang')?.value;
  // Sinon, utiliser la langue du navigateur
  const langBrowser = request.headers
    .get('accept-language')
    ?.split(',')[0]
    .split('-')[0];

  // Si une langue est présente dans les cookies, l'utiliser
  const lang = langCookie ?? langBrowser ?? '';

  // Si la langue n'est pas valide, utiliser la langue par défaut
  const selectedLang = supportedLangs.includes(lang) ? lang : defaultLang;

  const { pathname } = request.nextUrl;
  console.warn('Middleware path:', request.nextUrl.pathname);
  console.warn({ pathname });
  // Si l'utilisateur accède à la racine (/), rediriger vers /[lang]
  if (pathname === '/') {
    return NextResponse.redirect(new URL(`/${selectedLang}`, request.url));
  }

  // Si l'utilisateur accède à la page porfolio (/portfolio), rediriger vers /[lang]/portfolio
  if (pathname === '/portfolio') {
    return NextResponse.redirect(
      new URL(`/${selectedLang}/portfolio`, request.url)
    );
  }

  // Si la langue sélectionnée est correcte, continuer la requête
  return NextResponse.next();
}

export const config = {
  matcher: ['/', '/portfolio'], // Appliquer le middleware uniquement sur la racine et portfolio
};
