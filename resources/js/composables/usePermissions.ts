import { usePage } from "@inertiajs/vue3";
import Permission from "@/types/Permission";

export function usePermission(permissionList?: Permission[]) {
  const page = usePage();
  const userPermissions: Permission[] = permissionList ?? page.props.auth.permissions ?? [];

  return (ability: string): boolean => {
    return userPermissions.some(p => p.name === ability);
  };
}