import { AppPageProps } from '@/types/index';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module 'vue' {
    interface ComponentCustomProperties {
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
        
        $t: (value: string | undefined | null, fallback?: string) => string;
        $translate: (value: string | undefined | null, fallback?: string) => string;
        $translateMany: (values: (string | undefined | null)[]) => string[];
        $translateObject: <T extends Record<string, any>>(obj: T, keys: (keyof T)[]) => T;
        $translateArray: <T extends Record<string, any>>(items: T[], keys: (keyof T)[]) => T[];
        $hasTranslation: (value: string) => boolean;
        $addTranslation: (key: string, value: string) => void;
    }
}
