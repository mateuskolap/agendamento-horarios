import {usePage} from "@inertiajs/vue3";
import {ref, watch} from "vue";

interface FlashMessage {
  success?: string;
  info?: string;
  warning?: string;
  error?: string;
}

export function useFlash() {
  const page = usePage();
  const flash = ref<FlashMessage>(page.props.flash || {});

  watch(() => page.props.flash, (newFlash) => {
    if (newFlash) {
      flash.value = newFlash;
    }
  });

  return flash;
}
