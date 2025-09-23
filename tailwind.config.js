import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./app/Livewire/**/*.php",
    ],

    // This enables the dark mode to be toggled with a 'dark' class on the <html> element
    darkMode: "class",

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
            // THEME COLORS DEFINED WITH CSS VARIABLES
            colors: {
                // Background colors
                bkg: "hsl(var(--bkg))",
                "bkg-soft": "hsl(var(--bkg-soft))",
                "bkg-alt": "hsl(var(--bkg-alt))",
                // Foreground/Text colors
                fg: "hsl(var(--fg))",
                "fg-soft": "hsl(var(--fg-soft))",
                "fg-alt": "hsl(var(--fg-alt))",
                // Border color
                border: "hsl(var(--border))",
                // Primary action color
                primary: "hsl(var(--primary))",
                "primary-fg": "hsl(var(--primary-fg))",
                // Accent colors
                success: "hsl(var(--success))",
                danger: "hsl(var(--danger))",
            },
        },
    },

    plugins: [forms],
};
