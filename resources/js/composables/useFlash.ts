import {usePage} from "@inertiajs/vue3";
import {ref, watch} from "vue";

interface FlashMessage {
  success?: string;
  info?: string;
  warning?: string;
  error?: string;
}

export function useFlash(timeout = 5000) {
  const page = usePage();
  const flash = ref<FlashMessage>(page.props.flash || {});

  watch(() => page.props.flash, (newFlash) => {
    if (newFlash) {
      flash.value = newFlash;

      if (Object.keys(newFlash).length > 0) {
        setTimeout(() => {
          flash.value = {};
        }, timeout);
      }
    }
  });

  return flash;
}
