export type FormOptions = {
    onSuccess: () => void;
    preserveState: boolean;
    preserveScroll: boolean;
};

export type MakeFormOptionsOverrides = Partial<Pick<FormOptions, 'preserveState' | 'preserveScroll'>>;

/**
 * Cria opções padrão para requisições do Inertia com onSuccess customizável.
 */
export function makeFormOptions(onSuccess: () => void, overrides?: MakeFormOptionsOverrides): FormOptions;
