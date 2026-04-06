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
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: "#f0f9ff",
                    600: "#2563eb",
                    700: "#1d4ed8",
                },
            },
        },
    },

    plugins: [forms, flowbite],
};
