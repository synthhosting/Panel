const colors = require('tailwindcss/colors');

const helionix = {
    color1: "var(--color-1)",
    color2: "var(--color-2)",
    color3: "var(--color-3)",
    color4: "var(--color-4)",
    color5: "var(--color-5)",
    color6: "var(--color-6)",
    console: "var(--color-console)",
    editor: "var(--color-editor)",
    colorH1: "var(--color-h1)",
    colorLabel: "var(--color-label)",
    colorP: "var(--color-p)",
    colorA: "var(--color-a)",
    colorSpan: "var(--color-span)",
    colorCode: "var(--color-code)",
    colorStrong: "var(--color-strong)",
    colorSvg: "var(--color-svg)",
    colorInvalid: "var(--color-invalid)",
    btnPrimary: "var(--button-primary)",
    btnPrimaryHover: "var(--button-primary-hover)",
    btnSecondary: "var(--button-secondary)",
    btnSecondaryHover: "var(--button-secondary-hover)",
    btnDanger: "var(--button-danger)",
    btnDangerHover: "var(--button-danger-hover)",
    alertColorInformation: "var(--alert-color-information)",
    alertColorUpdate: "var(--alert-color-update)",
    alertColorWarning: "var(--alert-color-warning)",
    alertColorError: "var(--alert-color-error)",
};

const gray = {
    50: "hsl(216, 33%, 97%)",
    100: "hsl(214, 15%, 91%)",
    200: "hsl(210, 16%, 82%)",
    300: "hsl(211, 13%, 65%)",
    400: "hsl(211, 10%, 53%)",
    500: "hsl(211, 12%, 43%)",
    600: "hsl(209, 14%, 37%)",
    700: "hsl(209, 18%, 30%)",
    800: "hsl(209, 20%, 25%)",
    900: "hsl(210, 24%, 16%)",
};  

module.exports = {
    content: [
        './resources/scripts/**/*.{js,ts,tsx}',
    ],
    theme: {
        extend: {
            fontFamily: {
                header: ['"IBM Plex Sans"', '"Roboto"', 'system-ui', 'sans-serif'],
            },
            colors: {
                black: '#131a20',
                // "primary" and "neutral" are deprecated, prefer the use of "blue" and "gray"
                // in new code.
                primary: colors.blue,
                gray: gray,
                neutral: gray,
                cyan: colors.cyan,
                helionix: helionix,
            },
            fontSize: {
                '2xs': '0.625rem',
            },
            transitionDuration: {
                250: '250ms',
            },
            borderColor: theme => ({
                default: theme('colors.neutral.400', 'currentColor'),
            }),
        },
    },
    plugins: [
        require('@tailwindcss/line-clamp'),
        require('@tailwindcss/forms')({
            strategy: 'class',
        }),
    ]
};