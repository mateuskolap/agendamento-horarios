import {ref} from "vue";

export function useModal<T>(initialParams?: T) {
  const isOpen = ref<boolean>(false);
  const params = ref<T|null>(initialParams || null);

  function toggle(): void {
    isOpen.value = !isOpen.value;
  }

  function open(data?: T): void {
    if (data) {
      params.value = data;
    }
    isOpen.value = true;
  }

  function close(): void {
    isOpen.value = false;
  }

  function setOpen(open: boolean) {
    isOpen.value = open;
  }

  function setParams(data: T | undefined): void {
    params.value = data;
  }

  function getParams(): T | undefined {
    return params.value;
  }

  function modalBindInfo() {
    return {
      ...(params.value ?? {}) as T,
      isOpen: isOpen.value,
    }
  }

  return {
    isOpen,
    toggle,
    open,
    close,
    setOpen,
    setParams,
    getParams,
    modalBindInfo
  };
}
