import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

export function useConfirmDelete() {
    async function confirmDelete(url: string) {
        const result = await Swal.fire({
            title: 'Tem certeza?',
            text: 'Esta ação não pode ser desfeita!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar',
        });

        if (result.isConfirmed) {
            router.delete(url, {
                onSuccess: async () => {
                    await Swal.fire({
                        title: 'Excluído!',
                        text: 'O registro foi excluído com sucesso.',
                        icon: 'success',
                    });
                },
            });
        }
    }

    return { confirmDelete };
}
