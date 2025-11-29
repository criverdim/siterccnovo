/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        'rcc-red': '#DC2626',
        'rcc-cyan': '#0891B2',
        'rcc-gray-50': '#F9FAFB',
        'rcc-gray-100': '#F3F4F6',
        'rcc-gray-200': '#E5E7EB',
        'rcc-gray-300': '#D1D5DB',
        'rcc-gray-400': '#9CA3AF',
        'rcc-gray-500': '#6B7280',
        'rcc-gray-600': '#4B5563',
        'rcc-gray-700': '#374151',
        'rcc-gray-800': '#1F2937',
        'rcc-gray-900': '#111827',
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
      },
      animation: {
        'fade-in': 'fadeIn 0.2s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
      },
    },
  },
  plugins: [],
}
