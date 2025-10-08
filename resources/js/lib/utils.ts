import { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function urlIsActive(urlToCheck: NonNullable<InertiaLinkProps['href']>, currentUrl: string) {
    return toUrl(urlToCheck) === currentUrl;
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function makeFormOptions(
    onSuccess: () => void,
    overrides?: { preserveState?: boolean; preserveScroll?: boolean },
) {
    return {
        onSuccess,
        preserveState: true,
        preserveScroll: true,
        ...(overrides ?? {}),
    };
}
