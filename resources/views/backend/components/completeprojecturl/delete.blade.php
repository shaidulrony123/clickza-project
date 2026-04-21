<div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class="mt-3 text-warning">Delete!</h3>
                <p class="mb-3">Once deleted, you can't get it back.</p>

                <input type="hidden" id="deleteID">
            </div>

            <div class="modal-footer d-flex justify-content-end">
                <div>
                    <button type="button" id="delete-modal-close" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" id="confirmDelete" class="btn btn-danger" onclick="CompleteProjectDelete()">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function CompleteProjectDelete() {
        const deleteBtn = document.getElementById('confirmDelete');

        try {
            let id = document.getElementById('deleteID').value;

            if (!id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid project id',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            deleteBtn.disabled = true;
            showLoader();

            let response = await axios.post('/complete-project-delete', {
                id: id
            });

            hideLoader();
            deleteBtn.disabled = false;

            document.getElementById('delete-modal-close').click();

            if (response.data.status === 'success') {
                if (typeof getCompleteProjectData === 'function') {
                    await getCompleteProjectData();
                }

                Swal.fire({
                    icon: 'success',
                    title: response.data.message ?? 'Project deleted successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: response.data.message ?? 'Delete failed',
                    showConfirmButton: false,
                    timer: 2000
                });
            }

        } catch (error) {
            hideLoader();
            deleteBtn.disabled = false;
            document.getElementById('delete-modal-close').click();

            console.error('Delete error:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Delete failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>