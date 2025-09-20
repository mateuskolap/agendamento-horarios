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
                    boxShadowActive: 'none',
                    borderRadius: '0.375rem', // rounded-md
                    borderHover: 'none',
                    borderActive: '3px solid var(--color-ring)',
                    borderFocus: 'none',
                    boxShadowFocus: '0 0 0 3px var(--color-ring/50)',

                    heightSmall: '2.25rem', // h-9
                    paddingSingle: '0.75rem', // p-3

                    border: isDarkMode.value ? '1px solid var(--color-ring)' : '1px solid black', // dark:border-gray-500 / border-black
                    color: isDarkMode.value ? 'var(--color-input)' : 'transparent', // dark:bg-input/30 / bg-gray-200
                    colorActive: isDarkMode.value ? 'var(--color-input)' : 'transparent',
                    placeholderColor: 'var(--color-muted-foreground)',
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
