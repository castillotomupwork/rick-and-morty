module.exports = {
  content: [
    './templates/**/*.html.twig',
    './assets/**/*.{js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {},
  },
  safelist: [
    'disabled:text-gray-400',
    'disabled:hover:text-gray-400',
    'disabled:cursor-default',
  ],
  variants: {
    extend: {
      cursor: ['disabled'],
    },
  },
  plugins: [],
}