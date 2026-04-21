<div class="modal fade" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class=" mt-3 text-warning">Delete !</h3>
                <p class="mb-3">Once delete, you can't get it back.</p>
                <input class="d-none" id="deleteID"/>
                <input class="d-none" id="deleteFilePath"/>

            </div>
            <div class="modal-footer d-flex justify-content-end">
                <div>
                    <button type="button" id="delete-modal-close" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="CategoryDelete()" type="button" id="confirmDelete" class="btn btn-danger" >Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function showLoader() {
        // optional loader show
    }

    function hideLoader() {
        // optional loader hide
    }

    async function CategoryDelete() {
        try {
            let id = document.getElementById('deleteID').value;

            if (!id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid category id',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            document.getElementById('delete-modal-close').click();

            showLoader();

            let response = await axios.post("/category-delete", {
                id: id
            });

            hideLoader();

            if (response.data.status === 'success') {
                await getCategoryData();

                Swal.fire({
                    icon: 'success',
                    title: response.data.message ?? 'Category deleted successfully',
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

        } catch (e) {
            hideLoader();
            console.error('Delete error:', e);

            Swal.fire({
                icon: 'error',
                title: e.response?.data?.message ?? 'Delete failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>