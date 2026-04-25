<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Client Source</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <input type="hidden" id="updateClientsourceId">

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Name</label>
                            <input type="text" id="updateClientsourceName" placeholder="Enter client name">
                        </div>

                        <div class="form-group-d">
                            <label>Phone</label>
                            <input type="text" id="updateClientsourcePhone" placeholder="Enter phone number">
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Work</label>
                            <input type="text" id="updateClientsourceWork" placeholder="Enter work type">
                        </div>

                        <div class="form-group-d">
                            <label>Price</label>
                            <input type="text" id="updateClientsourcePrice" placeholder="Enter price">
                        </div>
                    </div>

                    <button type="button" class="btn-primary-d" onclick="clientsourceUpdate()">
                        Update Client Source <i class="fas fa-save ms-2"></i>
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
            document.getElementById('updateClientsourceId').value = id;

            let res = await axios.post('/clientsource-by-id', { id: id });
            let data = res.data.row ?? {};

            document.getElementById('updateClientsourceName').value = data.name ?? '';
            document.getElementById('updateClientsourcePhone').value = data.phone ?? '';
            document.getElementById('updateClientsourceWork').value = data.work ?? '';
            document.getElementById('updateClientsourcePrice').value = data.price ?? '';

        } catch (error) {
            console.error('Error loading client source data:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Failed to load client source data',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }

    async function clientsourceUpdate() {
        try {
            const id = document.getElementById('updateClientsourceId').value;
            const name = document.getElementById('updateClientsourceName').value.trim();
            const phone = document.getElementById('updateClientsourcePhone').value.trim();
            const work = document.getElementById('updateClientsourceWork').value.trim();
            const price = document.getElementById('updateClientsourcePrice').value.trim();

            if (!name || !phone || !price) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Name, phone and price are required!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            showLoader();

            let res = await axios.post('/clientsource-update', {
                id: id,
                name: name,
                phone: phone,
                work: work,
                price: price
            });

            hideLoader();

            if (res.data.status === 'success') {
                const modalElement = document.getElementById('updateModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalElement);
                }

                modalInstance.hide();

                await getClientsourceData();

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Client source updated successfully',
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
            console.error('Error updating client source:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Update failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>
