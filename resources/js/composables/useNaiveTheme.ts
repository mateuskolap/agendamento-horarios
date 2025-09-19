import { darkTheme, type GlobalTheme, type GlobalThemeOverrides } from 'naive-ui';
import { computed } from 'vue';
import { useAppearance } from './useAppearance';

export function useNaiveTheme() {
    const { appearance } = useAppearance();

    const isDarkMode = computed(() => {
        if (appearance.value === 'system') {
            return window.matchMedia('(prefers-color-scheme: dark)').matches;
        }
        return appearance.value === 'dark';
    });

    const themeOverrides = computed<GlobalThemeOverrides>(() => ({
        common: {
            cubicBezierEaseInOut: 'none',
            cubicBezierEaseOut: 'none',
            cubicBezierEaseIn: 'none',
        },
        Select: {
            peers: {
                InternalSelection: {
                    borderRadius: '0.375rem', // rounded-md
                    heightSmall: '2.25rem', // h-9
                    paddingSingle: '0.75rem', // p-3
                    border: isDarkMode.value ? '1px solid #6b7280' : '1px solid black', // dark:border-gray-500 / border-black
                    color: isDarkMode.value ? 'rgba(var(--color-input), 0.3);' : 'transparent', // dark:bg-input/30 / bg-gray-200
                    colorActive: isDarkMode.value ? 'rgba(var(--color-input), 0.3);' : '#e5e7eb',
                    colorHover: isDarkMode.value ? 'rgba(var(--color-input), 0.3);' : '#e5e7eb',
                },
            },
        },
    }));

    const naiveTheme = computed<GlobalTheme | null>(() => {
        return isDarkMode.value ? darkTheme : null;
    });

    return {
        naiveTheme,
        themeOverrides,
        isDarkMode,
    };
}
