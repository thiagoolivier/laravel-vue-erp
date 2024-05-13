/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    extend: {
      colors: {
        primary: 'rgb(var(--primary))',
        secondary: 'rgb(var(--secondary))',
        tertiary: 'rgb(var(--tertiary))',

        "tr-primary": "rgb(var(--tr-primary))",
        "tr-secondary": "rgb(var(--tr-secondary))",
      },
    },
  },
  plugins: [],
  darkMode: 'selector',
};
