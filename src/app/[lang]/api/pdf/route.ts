// app/api/pdf/route.ts
import puppeteer, { type Browser } from 'puppeteer-core';

export async function POST(req: Request) {
  let browser: Browser | undefined;

  try {
    const { url } = await req.json();
    if (!url) {
      return new Response(JSON.stringify({ error: 'No url provided' }), {
        status: 400,
      });
    }

    browser = await puppeteer.launch({
      executablePath: '/usr/bin/google-chrome',
      args: [
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-dev-shm-usage',
      ],
      headless: true,
      env: {
        ...process.env,
        HOME: '/home/nextjs',
        XDG_CACHE_HOME: '/home/nextjs/.cache',
        XDG_CONFIG_HOME: '/home/nextjs/.config',
      },
    });

    const page = await browser.newPage();
    await page.goto(url, { waitUntil: 'networkidle2' });

    const pdfBuffer = await page.pdf({
      format: 'A4',
      printBackground: true,
      margin: { top: '20px', bottom: '20px' },
    });

    return new Response(new Uint8Array(pdfBuffer), {
      status: 200,
      headers: {
        'Content-Type': 'application/pdf',
        'Content-Disposition':
          'attachment; filename="cv_mathieu_claessens.pdf"',
      },
    });
  } catch (error) {
    const errorMessage =
      error instanceof Error ? error.message : 'Unknown error';
    return new Response(JSON.stringify({ error: errorMessage }), {
      status: 500,
    });
  } finally {
    await browser?.close();
  }
}
