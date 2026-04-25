<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Client Source</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Name</label>
                            <input type="text" id="clientsourceName" placeholder="Enter client name">
                        </div>

                        <div class="form-group-d">
                            <label>Phone</label>
                            <input type="text" id="clientsourcePhone" placeholder="Enter phone number">
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Work</label>
                            <input type="text" id="clientsourceWork" placeholder="Enter work type">
                        </div>

                        <div class="form-group-d">
                            <label>Price</label>
                            <input type="text" id="clientsourcePrice" placeholder="Enter price">
                        </div>
                    </div>

                    <button class="btn-primary-d" id="saveClientsourceBtn" type="button" onclick="clientsourceCreate()">
                        Save Client Source <i class="fas fa-save ms-2"></i>
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

    async function clientsourceCreate() {
        const saveBtn = document.getElementById('saveClientsourceBtn');

        try {
            const name = document.getElementById('clientsourceName').value.trim();
            const phone = document.getElementById('clientsourcePhone').value.trim();
            const work = document.getElementById('clientsourceWork').value.trim();
            const price = document.getElementById('clientsourcePrice').value.trim();

            if (!name || !phone || !price) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Name, phone and price are required!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            saveBtn.disabled = true;
            showLoader();

            const res = await axios.post('/clientsource-create', {
                name: name,
                phone: phone,
                work: work,
                price: price
            });

            hideLoader();
            saveBtn.disabled = false;

            if (res.data.status === 'success') {
                document.getElementById('clientsourceName').value = '';
                document.getElementById('clientsourcePhone').value = '';
                document.getElementById('clientsourceWork').value = '';
                document.getElementById('clientsourcePrice').value = '';

                const modalElement = document.getElementById('exampleModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalElement);
                }

                modalInstance.hide();

                await getClientsourceData();

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Client source created successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: res.data.message ?? 'Create failed',
                    showConfirmButton: false,
                    timer: 2000
                });
            }

        } catch (error) {
            hideLoader();
            saveBtn.disabled = false;
            console.error('Error creating client source:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Create failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>
