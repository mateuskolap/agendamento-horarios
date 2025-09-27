/**
 * Implementação runtime para criação de opções de formulário do Inertia.
 * Permite sobrescrever preserveState e preserveScroll via overrides.
 */
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
