import type { App } from 'vue';
import { useTranslate } from '@/composables/useTranslate';

const { translate, t, translateMany, translateObject, translateArray, hasTranslation, addTranslation } = useTranslate();

export const translatePlugin = {
    install(app: App) {
        app.config.globalProperties.$t = t;
        app.config.globalProperties.$translate = translate;
        app.config.globalProperties.$translateMany = translateMany;
        app.config.globalProperties.$translateObject = translateObject;
        app.config.globalProperties.$translateArray = translateArray;
        app.config.globalProperties.$hasTranslation = hasTranslation;
        app.config.globalProperties.$addTranslation = addTranslation;

        app.provide('translate', translate);
        app.provide('t', t);
        app.provide('translateMany', translateMany);
        app.provide('translateObject', translateObject);
        app.provide('translateArray', translateArray);
        app.provide('hasTranslation', hasTranslation);
        app.provide('addTranslation', addTranslation);
    }
};

export { translate, t, translateMany, translateObject, translateArray, hasTranslation, addTranslation };