<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <div class="form-group-d">
                        <label>Category Name</label>
                        <input type="text" id="categoryName" placeholder="Enter category name">
                    </div>

                    <button class="btn-primary-d" id="saveCategoryBtn" type="button" onclick="categoryCreate()">
                        Save Category <i class="fas fa-save ms-2"></i>
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

    async function categoryCreate() {
        const saveBtn = document.getElementById('saveCategoryBtn');

        try {
            const categoryName = document.getElementById('categoryName').value.trim();

            if (!categoryName) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Category name is required!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            saveBtn.disabled = true;
            showLoader();

            const res = await axios.post('/category-create', {
                name: categoryName
            });

            hideLoader();
            saveBtn.disabled = false;

            if (res.data.status === 'success') {
                document.getElementById('categoryName').value = '';

                const modalElement = document.getElementById('exampleModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalElement);
                }

                modalInstance.hide();

                await getCategoryData();

                Swal.fire({
                    icon: 'success',
                    title: res.data.message || 'Category created successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: res.data.message || 'Create failed',
                    showConfirmButton: false,
                    timer: 2000
                });
            }

        } catch (error) {
            hideLoader();
            saveBtn.disabled = false;
            console.error('Error creating category:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message || 'Create failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>