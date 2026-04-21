<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Add New Marketplace Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>User Name</label>
                            <input type="text" id="marketplaceUserName" placeholder="Enter username">
                        </div>

                        <div class="form-group-d">
                            <label>Badge</label>
                            <input type="text" id="marketplaceBadge" placeholder="Enter badge">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Description</label>
                        <textarea id="marketplaceDescription" rows="4" placeholder="Enter description"></textarea>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Profile URL</label>
                            <input type="url" id="marketplaceUrl" placeholder="https://example.com/profile">
                        </div>

                        <div class="form-group-d">
                            <label>Rating</label>
                            <input type="number" id="marketplaceRating" step="0.1" min="0" placeholder="Enter rating">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Tags</label>
                        <div id="marketplaceTagWrapper">
                            <div class="marketplace-tag-item" style="display:flex; gap:10px; margin-bottom:10px;">
                                <input type="text" class="marketplaceTag form-control" placeholder="Enter tag">
                                <button type="button" class="btn btn-danger" onclick="removeMarketplaceTag(this)">X</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addMarketplaceTagField()">Add Item</button>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Items</label>
                            <input type="number" id="marketplaceItem" min="0" value="0">
                        </div>

                        <div class="form-group-d">
                            <label>Sales</label>
                            <input type="number" id="marketplaceSales" min="0" value="0">
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Is Active</label>
                            <select id="marketplaceIsActive" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn-primary-d" id="saveMarketplaceBtn" onclick="marketplaceCreate()">
                        Save Marketplace Profile <i class="fas fa-save ms-2"></i>
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

    function addMarketplaceTagField(value = '') {
        let wrapper = document.getElementById('marketplaceTagWrapper');

        let div = document.createElement('div');
        div.className = 'marketplace-tag-item';
        div.style.display = 'flex';
        div.style.gap = '10px';
        div.style.marginBottom = '10px';

        div.innerHTML = `
            <input type="text" class="marketplaceTag form-control" placeholder="Enter tag" value="${value}">
            <button type="button" class="btn btn-danger" onclick="removeMarketplaceTag(this)">X</button>
        `;

        wrapper.appendChild(div);
    }

    function removeMarketplaceTag(button) {
        let wrapper = document.getElementById('marketplaceTagWrapper');
        if (wrapper.children.length > 1) {
            button.parentElement.remove();
        }
    }

    async function marketplaceCreate() {
        const saveBtn = document.getElementById('saveMarketplaceBtn');

        try {
            const userName = document.getElementById('marketplaceUserName').value.trim();
            const description = document.getElementById('marketplaceDescription').value.trim();
            const url = document.getElementById('marketplaceUrl').value.trim();
            const badge = document.getElementById('marketplaceBadge').value.trim();
            const item = document.getElementById('marketplaceItem').value;
            const sales = document.getElementById('marketplaceSales').value;
            const rating = document.getElementById('marketplaceRating').value;
            const isActive = document.getElementById('marketplaceIsActive').value;

            let tagInputs = document.querySelectorAll('.marketplaceTag');
            let tags = [];

            tagInputs.forEach((input) => {
                if (input.value.trim() !== '') {
                    tags.push(input.value.trim());
                }
            });

            if (!userName || !description || !url || tags.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'User Name, Description, URL and at least one Tag are required!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            saveBtn.disabled = true;
            showLoader();

            let formData = new FormData();
            formData.append('user_name', userName);
            formData.append('description', description);
            formData.append('url', url);
            formData.append('badge', badge);
            formData.append('item', item ? item : 0);
            formData.append('sales', sales ? sales : 0);
            formData.append('rating', rating ? rating : 0);
            formData.append('is_active', isActive);

            tags.forEach((tag, index) => {
                formData.append(`tags[${index}]`, tag);
            });

            let res = await axios.post('/marketplace-create', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            hideLoader();
            saveBtn.disabled = false;

            if (res.data.status === 'success') {
                document.getElementById('marketplaceUserName').value = '';
                document.getElementById('marketplaceDescription').value = '';
                document.getElementById('marketplaceUrl').value = '';
                document.getElementById('marketplaceBadge').value = '';
                document.getElementById('marketplaceItem').value = 0;
                document.getElementById('marketplaceSales').value = 0;
                document.getElementById('marketplaceRating').value = '';
                document.getElementById('marketplaceIsActive').value = 1;

                document.getElementById('marketplaceTagWrapper').innerHTML = `
                    <div class="marketplace-tag-item" style="display:flex; gap:10px; margin-bottom:10px;">
                        <input type="text" class="marketplaceTag form-control" placeholder="Enter tag">
                        <button type="button" class="btn btn-danger" onclick="removeMarketplaceTag(this)">X</button>
                    </div>
                `;

                const modalElement = document.getElementById('exampleModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalElement);
                }

                modalInstance.hide();

                await getMarketplaceData();

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Marketplace profile created successfully',
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
            console.error('Error creating marketplace profile:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Create failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>