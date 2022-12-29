import { buildLegacyTheme, defineConfig } from "sanity";

const props = {
  "--my-white": "#fff",
  "--my-black": "#1f1f1f",
  "--my-blue": "#42bcf4",
  "--my-red": "#ef3131",
  "--my-yellow": "#f8d300",
  "--my-green": "#03dd32",
  "--CodeVESIT-theme": "#f0b000",
};

export const myTheme = buildLegacyTheme({
  /* Base theme colors */
  "--black": props["--my-black"],
  "--white": props["--my-white"],

  "--gray": "#666",
  "--gray-base": "#666",

  "--component-bg": props["--my-black"],
  "--component-text-color": props["--my-white"],

  /* Brand */
  "--brand-primary": props["--CodeVESIT-theme"],

  // Default button
  "--default-button-color": "#666",
  "--default-button-primary-color": props["--CodeVESIT-theme"],
  "--default-button-success-color": props["--my-green"],
  "--default-button-warning-color": props["--my-yellow"],
  "--default-button-danger-color": props["--my-red"],

  /* State */
  "--state-info-color": props["--my-blue"],
  "--state-success-color": props["--my-green"],
  "--state-warning-color": props["--my-yellow"],
  "--state-danger-color": props["--my-red"],

  /* Navbar */
  "--main-navigation-color": props["--my-black"],
  "--main-navigation-color--inverted": props["--my-white"],

  "--focus-color": props["--CodeVESIT-theme"],
});
