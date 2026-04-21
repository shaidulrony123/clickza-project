<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Update Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <input type="hidden" id="updateCategoryId">

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Category Name</label>
                            <input type="text" id="updateCategoryName" placeholder="Enter category name">
                        </div>
                    </div>

                    <button type="button" class="btn-primary-d" onclick="categoryUpdate()">
                        Update Category <i class="fas fa-save ms-2"></i>
                    </button>
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

    async function FillUpUpdateForm(id) {
        try {
            document.getElementById('updateCategoryId').value = id;

            let res = await axios.post('/category-by-id', { id: id });
            let data = res.data.row ?? res.data.data ?? res.data;

            document.getElementById('updateCategoryName').value = data.name ?? '';

        } catch (error) {
            console.error('Error loading category data:', error);

            Swal.fire({
                icon: 'error',
                title: 'Failed to load category data',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }

    async function categoryUpdate() {
        try {
            const categoryId = document.getElementById('updateCategoryId').value;
            const categoryName = document.getElementById('updateCategoryName').value.trim();

            if (!categoryName) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Category name is required!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            let formData = new FormData();
            formData.append('id', categoryId);
            formData.append('name', categoryName);

            showLoader();

            let res = await axios.post('/category-update', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            hideLoader();

            if (res.data.status === 'success') {
                const modalElement = document.getElementById('updateModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalElement);
                }

                modalInstance.hide();

                await getCategoryData();

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Category updated successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: res.data.message ?? 'Update failed',
                    showConfirmButton: false,
                    timer: 2000
                });
            }

        } catch (error) {
            hideLoader();
            console.error('Error updating category:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Update failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>