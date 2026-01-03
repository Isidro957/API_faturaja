import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#123859',   // Azul escuro forte
                accent: '#F9941F',    // Laranja âmbar intenso
                surface: '#F2F2F2',   // Cinza muito claro
                border: '#E5E5E5',    // Cinza intermédio
            },
            boxShadow: {
                soft: '0 8px 20px rgba(0,0,0,0.05)',
            },
        },
        fontFamily: {
            sans: ['Inter', ...defaultTheme.fontFamily.sans],
        },
    },
    plugins: [],
};
// tailwind.config.js
module.exports = {
    theme: {
        extend: {
            keyframes: {
                fadeInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-50px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                fadeInRight: {
                    '0%': { opacity: '0', transform: 'translateX(50px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            animation: {
                fadeInLeft: 'fadeInLeft 0.8s ease-out forwards',
                fadeInRight: 'fadeInRight 0.8s ease-out forwards',
                fadeInUp: 'fadeInUp 0.8s ease-out forwards',
            },
        },
    },
}
