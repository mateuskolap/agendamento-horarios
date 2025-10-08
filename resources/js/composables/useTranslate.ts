import { translations } from '@/translations';

/**
 * Composable para traduzir valores que vêm do backend
 */
export function useTranslate() {
    /**
     * Traduz um valor do inglês para português
     * @param value - Valor em inglês para traduzir
     * @param fallback - Valor padrão caso não encontre tradução (opcional)
     * @returns Valor traduzido ou o valor original se não encontrar tradução
     */
    const translate = (value: string | undefined | null, fallback?: string): string => {
        if (!value) return fallback || '';
        
        const lowerValue = value.toLowerCase();
        const translation = translations[lowerValue];
        
        return translation || fallback || value;
    };

    /**
     * Alias para a função translate (mais curto)
     */
    const t = translate;

    /**
     * Traduz múltiplos valores de uma vez
     * @param values - Array de valores para traduzir
     * @returns Array com valores traduzidos
     */
    const translateMany = (values: (string | undefined | null)[]): string[] => {
        return values.map(value => translate(value));
    };

    /**
     * Traduz propriedades de um objeto
     * @param obj - Objeto com propriedades para traduzir
     * @param keys - Array com as chaves que devem ser traduzidas
     * @returns Objeto com propriedades traduzidas
     */
    const translateObject = <T extends Record<string, any>>(
        obj: T, 
        keys: (keyof T)[]
    ): T => {
        const result = { ...obj };
        
        keys.forEach(key => {
            if (typeof result[key] === 'string') {
                (result[key] as any) = translate(result[key]);
            }
        });
        
        return result;
    };

    /**
     * Traduz arrays de objetos - versão super simples para roles, etc.
     * @param items - Array de objetos
     * @param keys - Chaves que devem ser traduzidas em cada objeto
     * @returns Array com objetos traduzidos
     */
    const translateArray = <T extends Record<string, any>>(
        items: T[], 
        keys: (keyof T)[]
    ): T[] => {
        return items.map(item => translateObject(item, keys));
    };

    /**
     * Verifica se existe tradução para um valor
     * @param value - Valor para verificar
     * @returns true se existe tradução, false caso contrário
     */
    const hasTranslation = (value: string): boolean => {
        return value.toLowerCase() in translations;
    };

    /**
     * Adiciona uma nova tradução dinamicamente
     * @param key - Chave em inglês
     * @param value - Tradução em português
     */
    const addTranslation = (key: string, value: string): void => {
        translations[key.toLowerCase()] = value;
    };

    return {
        translate,
        t,
        translateMany,
        translateObject,
        translateArray,
        hasTranslation,
        addTranslation,
    };
}