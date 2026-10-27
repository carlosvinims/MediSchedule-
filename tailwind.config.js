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
                medicare: {
                    50: "#eaf5f4",
                    100: "#d4eae8",
                    600: "#176b70",
                    700: "#105256",
                },
            },
        },
    },
    plugins: [],
}