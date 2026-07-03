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
                // Material Design 3 color tokens (design system dari mockup)
                primary:                   '#006c49',
                'on-primary':              '#ffffff',
                'primary-container':       '#10b981',
                'on-primary-container':    '#00422b',
                'primary-fixed':           '#6ffbbe',
                'primary-fixed-dim':       '#4edea3',
                'on-primary-fixed':        '#002113',
                'on-primary-fixed-variant':'#005236',
                'inverse-primary':         '#4edea3',

                secondary:                 '#855300',
                'on-secondary':            '#ffffff',
                'secondary-container':     '#fea619',
                'on-secondary-container':  '#684000',
                'secondary-fixed':         '#ffddb8',
                'secondary-fixed-dim':     '#ffb95f',
                'on-secondary-fixed':      '#2a1700',
                'on-secondary-fixed-variant':'#653e00',

                tertiary:                  '#a43a3a',
                'on-tertiary':             '#ffffff',
                'tertiary-container':      '#fc7c78',
                'on-tertiary-container':   '#711419',
                'tertiary-fixed':          '#ffdad7',
                'tertiary-fixed-dim':      '#ffb3af',
                'on-tertiary-fixed':       '#410005',
                'on-tertiary-fixed-variant':'#842225',

                error:                     '#ba1a1a',
                'on-error':                '#ffffff',
                'error-container':         '#ffdad6',
                'on-error-container':      '#93000a',

                background:                '#f8f9ff',
                'on-background':           '#0b1c30',

                surface:                   '#f8f9ff',
                'on-surface':              '#0b1c30',
                'surface-variant':         '#d3e4fe',
                'on-surface-variant':      '#3c4a42',
                'surface-container-lowest':'#ffffff',
                'surface-container-low':   '#eff4ff',
                'surface-container':       '#e5eeff',
                'surface-container-high':  '#dce9ff',
                'surface-container-highest':'#d3e4fe',
                'surface-bright':          '#f8f9ff',
                'surface-dim':             '#cbdbf5',
                'surface-tint':            '#006c49',
                'inverse-surface':         '#213145',
                'inverse-on-surface':      '#eaf1ff',

                outline:                   '#6c7a71',
                'outline-variant':         '#bbcabf',
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
