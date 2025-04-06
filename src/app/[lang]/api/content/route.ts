/* eslint-disable @typescript-eslint/no-explicit-any */
import { NextResponse, NextRequest } from "next/server";
import { getLocalFilesContent } from "@/connectors/localFiles";
import { LANGS_AVAILABLE } from "@/utils/const";

const extractLocalizedContent = (
  data: Record<string, any>,
  lang: string,
  fallback: string = LANGS_AVAILABLE.EN,
): any => {
  if (typeof data === "string") return data;

  if (Array.isArray(data)) {
    return data.map((item) => extractLocalizedContent(item, lang, fallback));
  }

  if (typeof data === "object") {
    // Check if object has lang keys
    if (Object.keys(data).every((key) => typeof data[key] === "string")) {
      return data[lang] ?? data[fallback] ?? "";
    }

    const result: Record<string, JSON> = {};
    for (const key in data) {
      result[key] = extractLocalizedContent(data[key], lang, fallback);
    }
    return result;
  }

  return "";
};

export async function GET(
  request: NextRequest,
  {
    params,
  }: {
    params: Promise<{ lang: string }>;
  },
) {
  const { searchParams } = new URL(request.url);
  const fileName = searchParams.get("file");
  const { lang } = await params;

  if (!fileName) {
    return NextResponse.json({ error: "File not found." }, { status: 404 });
  }

  try {
    const rawData = getLocalFilesContent(fileName);
    const localizedData = extractLocalizedContent(rawData, lang);
    return NextResponse.json(localizedData);
  } catch (error) {
    return NextResponse.json(
      { error: "Content not found.", detail: error },
      { status: 404 },
    );
  }
}
