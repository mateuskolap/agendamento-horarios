import { computed } from 'vue';
import { darkTheme, type GlobalTheme, type GlobalThemeOverrides } from 'naive-ui';
import { useAppearance } from './useAppearance';

export function useNaiveTheme() {
    const { appearance } = useAppearance();

    const isDarkMode = computed(() => {
        if (appearance.value === 'system') {
            return window.matchMedia('(prefers-color-scheme: dark)').matches;
        }
        return appearance.value === 'dark';
    });

    const themeOverrides: GlobalThemeOverrides = {
        common: {
            cubicBezierEaseInOut: 'none',
            cubicBezierEaseOut: 'none',
            cubicBezierEaseIn: 'none',
        },
    };

    const naiveTheme = computed<GlobalTheme | null>(() => {
        return isDarkMode.value ? darkTheme : null;
    });

    return {
        naiveTheme,
        themeOverrides,
        isDarkMode,
    };
}
