import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // ── Azmedia Corporate Blue Design System ──────────────────
                // Primary: Biru Azmedia Corporate
                primary:                   '#2563EB',
                'on-primary':              '#ffffff',
                'primary-container':       '#DBEAFE',
                'on-primary-container':    '#1E3A8A',
                'primary-fixed':           '#EFF6FF',
                'primary-fixed-dim':       '#BFDBFE',
                'on-primary-fixed':        '#1E40AF',
                'on-primary-fixed-variant':'#1D4ED8',
                'inverse-primary':         '#93C5FD',

                // Secondary: Biru medium/indigo
                secondary:                 '#4F46E5',
                'on-secondary':            '#ffffff',
                'secondary-container':     '#E0E7FF',
                'on-secondary-container':  '#312E81',
                'secondary-fixed':         '#EEF2FF',
                'secondary-fixed-dim':     '#C7D2FE',
                'on-secondary-fixed':      '#1E1B4B',
                'on-secondary-fixed-variant':'#3730A3',

                // Tertiary: Biru muda / cyan
                tertiary:                  '#0284C7',
                'on-tertiary':             '#ffffff',
                'tertiary-container':      '#BAE6FD',
                'on-tertiary-container':   '#0C4A6E',
                'tertiary-fixed':          '#E0F2FE',
                'tertiary-fixed-dim':      '#7DD3FC',
                'on-tertiary-fixed':       '#082F49',
                'on-tertiary-fixed-variant':'#0369A1',

                // Error
                error:                     '#DC2626',
                'on-error':                '#ffffff',
                'error-container':         '#FEE2E2',
                'on-error-container':      '#991B1B',

                // Background & Surface: Biru sangat muda / putih
                background:                '#F0F7FF',
                'on-background':           '#0F172A',

                surface:                   '#FFFFFF',
                'on-surface':              '#0F172A',
                'surface-variant':         '#DBEAFE',
                'on-surface-variant':      '#334155',
                'surface-container-lowest':'#FFFFFF',
                'surface-container-low':   '#F8FAFF',
                'surface-container':       '#EFF6FF',
                'surface-container-high':  '#DBEAFE',
                'surface-container-highest':'#BFDBFE',
                'surface-bright':          '#FFFFFF',
                'surface-dim':             '#CBD5E1',
                'surface-tint':            '#2563EB',
                'inverse-surface':         '#1E293B',
                'inverse-on-surface':      '#F1F5F9',

                // Outline
                outline:                   '#94A3B8',
                'outline-variant':         '#CBD5E1',
            },
            borderRadius: {
                DEFAULT: '0.125rem',
                lg:      '0.25rem',
                xl:      '0.5rem',
                full:    '0.75rem',
            },
            spacing: {
                xs:           '4px',
                sm:           '8px',
                md:           '16px',
                lg:           '24px',
                xl:           '32px',
                'touch-target': '48px',
            },
            fontSize: {
                'headline-lg': ['28px', { lineHeight: '36px', letterSpacing: '-0.02em', fontWeight: '700' }],
                'headline-lg-mobile': ['24px', { lineHeight: '32px', letterSpacing: '-0.01em', fontWeight: '700' }],
                'headline-md': ['20px', { lineHeight: '28px', fontWeight: '600' }],
                'body-lg':     ['16px', { lineHeight: '24px', fontWeight: '400' }],
                'body-sm':     ['14px', { lineHeight: '20px', fontWeight: '400' }],
                'label-md':    ['12px', { lineHeight: '16px', letterSpacing: '0.05em', fontWeight: '600' }],
                'status-badge':['13px', { lineHeight: '16px', fontWeight: '700' }],
            },
        },
    },

    plugins: [forms],
};
