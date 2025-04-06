// middleware.ts
import { NextRequest, NextResponse } from "next/server";
import { LANGS_AVAILABLE } from "./utils/const";

const supportedLangs = Object.values(LANGS_AVAILABLE);
const defaultLang = LANGS_AVAILABLE.EN;

export function middleware(request: NextRequest) {
  // Récupérer la langue dans les cookies
  const langCookie = request.cookies.get("lang")?.value;
  // Sinon, utiliser la langue du navigateur
  const langBrowser = request.headers
    .get("accept-language")
    ?.split(",")[0]
    .split("-")[0];

  // Si une langue est présente dans les cookies, l'utiliser
  const lang = langCookie ?? langBrowser ?? "";

  // Si la langue n'est pas valide, utiliser la langue par défaut
  const selectedLang = supportedLangs.includes(lang) ? lang : defaultLang;

  // Si l'utilisateur accède à la racine (/), rediriger vers /[lang]
  if (request.nextUrl.pathname === "/") {
    return NextResponse.redirect(new URL(`/${selectedLang}`, request.url));
  }

  // Si la langue sélectionnée est correcte, continuer la requête
  return NextResponse.next();
}

export const config = {
  matcher: "/", // Appliquer le middleware uniquement sur la racine
};
