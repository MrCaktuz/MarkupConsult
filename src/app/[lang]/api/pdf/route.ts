// app/api/pdf/route.js
import puppeteer from 'puppeteer';

export async function POST(req: Request) {
  try {
    const { url } = await req.json();
    if (!url) {
      return new Response(JSON.stringify({ error: 'No url provided' }), {
        status: 400,
      });
    }

    const browser = await puppeteer.launch({
      executablePath: '/usr/bin/google-chrome',
      args: [
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-dev-shm-usage',
      ],
      headless: true,
      env: {
        ...process.env,
        HOME: '/home/nextjs', // Assure la bonne variable HOME
        XDG_CACHE_HOME: '/home/nextjs/.cache',
        XDG_CONFIG_HOME: '/home/nextjs/.config',
      },
    });

    const page = await browser.newPage();
    await page.goto(url, { waitUntil: 'networkidle2' }); // charge la page Next.js

    const pdfBuffer = await page.pdf({
      format: 'A4',
      printBackground: true,
      margin: { top: '20px', bottom: '20px' },
    });

    await browser.close();

    return new Response(pdfBuffer, {
      status: 200,
      headers: {
        'Content-Type': 'application/pdf',
        'Content-Disposition':
          'attachment; filename="cv_mathieu_claessens.pdf"',
      },
    });
  } catch (error) {
    let errorMessage = 'Unknown error';
    if (error instanceof Error) {
      errorMessage = error.message;
    }
    return new Response(JSON.stringify({ error: errorMessage }), {
      status: 500,
    });
  }
}
