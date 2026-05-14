/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#00e5ff',
          dark: '#00bcd4',
          light: '#00f2ff',
        },
        dark: {
          DEFAULT: '#080a0f',
          lighter: '#0c0e14',
          card: '#111418',
          'card-hover': '#15181e',
        },
        neon: {
          cyan: '#00e5ff',
          yellow: '#ffab00',
          red: '#ff5252',
          green: '#00d1ff',
        },
      },
      boxShadow: {
        'neon': '0 0 20px rgba(0, 229, 255, 0.4)',
        'neon-strong': '0 0 40px rgba(0, 229, 255, 0.7)',
        'neon-sm': '0 0 10px rgba(0, 229, 255, 0.3)',
      },
      animation: {
        'fade-in': 'fadeIn 0.6s ease-out',
        'slide-in': 'slideIn 0.4s ease-out',
        'pulse-custom': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0', transform: 'translateY(10px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        slideIn: {
          '0%': { opacity: '0', transform: 'translateX(20px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' },
        },
      },
      backdropBlur: {
        'custom': '20px',
      },
    },
  },
  plugins: [],
}
