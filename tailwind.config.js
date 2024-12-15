/** @type {import('tailwindcss').Config} */
module.exports = {
    safelist: [
    ],
    content: [
        "./wp-content/themes/intranet/**/*.{html,js,php}",
        "./wp-content/plugins/intranet/**/*.{html,js,php}"

    ],
    theme: {
        container: {
            center: true,

        },
        extend: {
            aspectRatio: {
                'cinemascope': '32 /9'
            },
            maxWidth: {
                'screen-3xl': '1920px' // ou la largeur exacte que vous souhaitez
            },
        },
    },
    plugins: [],
}