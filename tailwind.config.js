/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/**/*.php",
    "./resources/**/*.css"
  ],
  theme: {
    extend: {
      colors: {
        nepal: {
          primary: '#9D2235',
          secondary: '#C9A227',
          bg: '#FAF7F2',
          text: '#2B2B2B',
          border: '#E5E5E5'
        }
      },
      fontFamily: {
        logo: ['"Playfair Display"', 'serif'],
        heading: ['"Playfair Display"', 'serif'],
        body: ['Inter', 'sans-serif'],
        button: ['Inter', 'sans-serif']
      }
    }
  },
  plugins: [],
}
