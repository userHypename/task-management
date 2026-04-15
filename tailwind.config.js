import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import flowbite from "flowbite/plugin";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                // Single font family for consistency
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Minimal color palette: primary, neutral, text
                primary: {
                    DEFAULT: "#2563eb",
                    600: "#2563eb",
                    700: "#1d4ed8",
                },
                neutral: {
                    DEFAULT: "#f3f4f6",
                },
                text: {
                    DEFAULT: "#0f172a",
                },
            },
        },
    },

    plugins: [forms, flowbite],
};
