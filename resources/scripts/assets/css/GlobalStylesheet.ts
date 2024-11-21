import tw from 'twin.macro';
import { createGlobalStyle } from 'styled-components/macro';

export default createGlobalStyle`
    body {
        ${tw`bg-helionix-color1`};
        font-family: 'Poppins', sans-serif !important;
        letter-spacing: 0.015em !important;
    }

    h1, h2, h3, h4, h5, h6 {
        ${tw`font-medium`};
        color: var(--color-h1) !important;
    }
    label {
        color: var(--color-label) !important;
    }
    input,
    textarea {
        color: var(--color-input) !important;
    }
    p,
    select,
    option {
        color: var(--color-p) !important;
    }
    a {
        color: var(--color-a) !important;
    }
    .bi {
        display: inline-flex !important;
    }
    span {
        color: var(--color-span);
    }
    code {
        color: var(--color-code) !important;
    }
    strong {
        color: var(--color-strong) !important;
    }
    svg {
        color: var(--color-svg) !important;
    }
    .input-help.error {
        color: var(--color-invalid) !important;
    }
    button {
        color: var(--color-strong)!important ;
    }
    form {
        ${tw`m-0`};
    }
    textarea, select, input, button, button:focus, button:focus-visible {
        ${tw`outline-none`};
    }
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none !important;
        margin: 0;
    }
    input::placeholder {
        color: var(--color-input);
    }
    input::-ms-input-placeholder {
        color: var(--color-input);
    }
    input[type=number] {
        -moz-appearance: textfield !important;
    }

    /* Scroll Bar Style */
    ::-webkit-scrollbar {
        background: transparent;
        width: 10px;
        height: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--button-primary);
        border-radius: 5px;
    }

    ::-webkit-scrollbar-track-piece {
        background: transparent;
    }

    ::-webkit-scrollbar-corner {
        background: transparent;
    }

    /* Authentication Style */
    .auth-container {
        display: flex;
        height: 100%;
        position: relative;
    }
    
    .auth-container:before {
        content: "";
        position: fixed;
        height: 100%;
        width: 40%;
        background-image: linear-gradient(10deg, var(--color-1) 0%, var(--color-4) 100%);
        background-size: cover;
        transition: 1.8s ease-in-out;
        z-index: 8;
        transform: skewX(-10deg);
        transform-origin: top left;
    }
    
    .auth-container .side-container {
        margin-left: 2rem;
        z-index: 10;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 30%;
    }
    
    .auth-container .side-container .title {
        color: var(--color-h1);
        font-size: 32px;
        font-weight: 800;
    }
    
    .auth-container .side-container .description {
        color: var(--color-p);
        font-size: 20px;
        font-weight: 300;
    }
    
    .form-container {
        margin-left: auto;
        width: 60%;
        z-index: 10;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .authentication-container {
        width: 50% !important;
    }
    
    /* Media query for responsiveness */
    @media (max-width: 767px) {
        .auth-container .side-container {
            display: none;
        }
        
        .form-container {
            width: 100%;
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .auth-container:before {
            width: 55%;
            background-image: linear-gradient(5deg, var(--color-1) 0%, var(--color-4) 100%);
            transform: skewX(-5deg);
        }

        .authentication-container {
            width: 90% !important;
        }

        .header-mobile {
            ${tw`flex items-center justify-between p-4 bg-helionix-color2`};
        }

        .header-logo {
            ${tw`flex items-center`};
        }

        .header-button {
            ${tw`text-neutral-100`};
        }
    }
    
    @media (min-width: 768px) and (max-width: 1024px) {
        .auth-container .side-container {
            display: none;
        }
        
        .form-container {
            width: 100%;
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .auth-container:before {
            width: 55%;
            background-image: linear-gradient(5deg, var(--color-1) 0%, var(--color-4) 100%);
            transform: skewX(-5deg);
        }

        .authentication-container {
            width: 55% !important;
        }
    }
`;
