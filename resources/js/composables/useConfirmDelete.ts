import Swal from 'sweetalert2';

export function useConfirmDelete() {
    /**
     * Exibe modal de confirmação de exclusão
     * @param title Título personalizado (opcional)
     * @param text Texto personalizado (opcional)
     * @returns Promise<boolean> - true se confirmado, false se cancelado
     */
    async function confirmDelete(title?: string, text?: string): Promise<boolean> {
        const result = await Swal.fire({
            title: title || 'Tem certeza?',
            text: text || 'Esta ação não pode ser desfeita!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar',
        });

        return result.isConfirmed;
    }

    /**
     * Exibe mensagem de sucesso após exclusão
     * @param title Título personalizado (opcional)
     * @param text Texto personalizado (opcional)
     */
    async function showDeleteSuccess(title?: string, text?: string): Promise<void> {
        await Swal.fire({
            title: title || 'Excluído!',
            text: text || 'O registro foi excluído com sucesso.',
            icon: 'success',
        });
    }

    return {
        confirmDelete,
        showDeleteSuccess
    };
}
