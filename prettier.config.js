module.exports = {
  plugins: ["@trivago/prettier-plugin-sort-imports"],
  printWidth: 80, // default
  tabWidth: 2, // Default
  useTabs: false, // Default
  singleQuote: true, // Best practice
  jsxSingleQuote: false, // Default
  bracketSameLine: false, // Default
  bracketSpacing: true, // Default
  embeddedLanguageFormatting: "auto", // Default
  insertPragma: false, // Default
  requirePragma: false, // Default
  endOfLine: "lf", // Default
  arrowParens: "always", // Default
  htmlWhitespaceSensitivity: "css", // Default
  proseWrap: "preserve", // Default
  quoteProps: "as-needed", // Default
  singleAttributePerLine: false, // Default
  semi: true, // Default
  trailingComma: "es5", // Default
  importOrder: [
    // react > next > 3rd party lib > packages > app > local import
    "^(react/(.*)$)|^(react$)",
    "^(next/(.*)$)|^(next$)",
    "<THIRD_PARTY_MODULES>",
    "^@/(.*)$",
    "^[./]",
  ],
  importOrderSeparation: false,
  importOrderSortSpecifiers: true,
  importOrderParserPlugins: ["typescript", "jsx", "decorators-legacy"],
};
