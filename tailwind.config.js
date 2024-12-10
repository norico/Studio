/** @type {import('tailwindcss').Config} */
module.exports = {
    safelist: [
    ],
    content: [
        "./wp-content/themes/intranet/**/*.{html,js,php}",
        "./wp-content/plugins/intranet/**/*.{html,js,php}"

    ],
    theme: {
        extend: {},
    },
    plugins: [],
}