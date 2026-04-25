<div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class="mt-3 text-warning">Delete!</h3>
                <p class="mb-3">Once deleted, you can't get it back.</p>
                <input class="d-none" id="deleteID" />
            </div>
            <div class="modal-footer d-flex justify-content-end">
                <div>
                    <button type="button" id="delete-modal-close" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button onclick="invoiceDelete()" type="button" id="confirmDelete" class="btn btn-danger">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function invoiceDelete() {
        try {
            let id = document.getElementById('deleteID').value;

            document.getElementById('delete-modal-close').click();

            showLoader();
            let response = await axios.post('/invoice-delete', { id: id });
            hideLoader();

            if (response.data.status === 'success') {
                await getInvoiceData();

                Swal.fire({
                    icon: 'success',
                    title: response.data.message ?? 'Invoice deleted successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        } catch (error) {
            hideLoader();
            console.error('Error deleting invoice:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Delete failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>
