<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Update Marketplace Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">

                    <input type="hidden" id="updateMarketplaceId">

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>User Name</label>
                            <input type="text" id="updateMarketplaceUserName" placeholder="Enter username">
                        </div>

                        <div class="form-group-d">
                            <label>Badge</label>
                            <input type="text" id="updateMarketplaceBadge" placeholder="Enter badge">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Description</label>
                        <textarea id="updateMarketplaceDescription" rows="4" placeholder="Enter description"></textarea>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Profile URL</label>
                            <input type="url" id="updateMarketplaceUrl" placeholder="https://example.com/profile">
                        </div>

                        <div class="form-group-d">
                            <label>Rating</label>
                            <input type="number" id="updateMarketplaceRating" step="0.1" min="0" placeholder="Enter rating">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Tags</label>
                        <div id="updateMarketplaceTagWrapper"></div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addUpdateMarketplaceTagField()">Add Item</button>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Items</label>
                            <input type="number" id="updateMarketplaceItem" min="0" value="0">
                        </div>

                        <div class="form-group-d">
                            <label>Sales</label>
                            <input type="number" id="updateMarketplaceSales" min="0" value="0">
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Is Active</label>
                            <select id="updateMarketplaceIsActive" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn-primary-d" id="updateMarketplaceBtn" onclick="marketplaceUpdate()">
                        Update Marketplace <i class="fas fa-save ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addUpdateMarketplaceTagField(value = '') {
        let wrapper = document.getElementById('updateMarketplaceTagWrapper');

        let div = document.createElement('div');
        div.className = 'marketplace-tag-item';
        div.style.display = 'flex';
        div.style.gap = '10px';
        div.style.marginBottom = '10px';

        div.innerHTML = `
            <input type="text" class="updateMarketplaceTag form-control" placeholder="Enter tag" value="${value}">
            <button type="button" class="btn btn-danger" onclick="removeUpdateMarketplaceTag(this)">X</button>
        `;

        wrapper.appendChild(div);
    }

    function removeUpdateMarketplaceTag(button) {
        let wrapper = document.getElementById('updateMarketplaceTagWrapper');
        if (wrapper.children.length > 1) {
            button.parentElement.remove();
        }
    }

    async function FillUpUpdateForm(id) {
        try {
            document.getElementById('updateMarketplaceId').value = id;
            document.getElementById('updateMarketplaceTagWrapper').innerHTML = '';

            let res = await axios.post('/marketplace-by-id', { id: id });
            let data = res.data.row ?? res.data.data ?? res.data;

            document.getElementById('updateMarketplaceUserName').value = data.user_name ?? '';
            document.getElementById('updateMarketplaceDescription').value = data.description ?? '';
            document.getElementById('updateMarketplaceUrl').value = data.url ?? '';
            document.getElementById('updateMarketplaceBadge').value = data.badge ?? '';
            document.getElementById('updateMarketplaceItem').value = data.item ?? 0;
            document.getElementById('updateMarketplaceSales').value = data.sales ?? 0;
            document.getElementById('updateMarketplaceRating').value = data.rating ?? 0;
            document.getElementById('updateMarketplaceIsActive').value = data.is_active ?? 1;

            let tags = [];
            try {
                tags = data.tag
                    ? (Array.isArray(data.tag) ? data.tag : JSON.parse(data.tag))
                    : [];
            } catch (e) {
                tags = [];
            }

            if (tags.length > 0) {
                tags.forEach(tag => addUpdateMarketplaceTagField(tag));
            } else {
                addUpdateMarketplaceTagField('');
            }

        } catch (error) {
            console.error('Error loading marketplace data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Failed to load marketplace data',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }

    async function marketplaceUpdate() {
        const updateBtn = document.getElementById('updateMarketplaceBtn');

        try {
            const marketplaceId = document.getElementById('updateMarketplaceId').value;
            const userName = document.getElementById('updateMarketplaceUserName').value.trim();
            const description = document.getElementById('updateMarketplaceDescription').value.trim();
            const url = document.getElementById('updateMarketplaceUrl').value.trim();
            const badge = document.getElementById('updateMarketplaceBadge').value.trim();
            const item = document.getElementById('updateMarketplaceItem').value;
            const sales = document.getElementById('updateMarketplaceSales').value;
            const rating = document.getElementById('updateMarketplaceRating').value;
            const isActive = document.getElementById('updateMarketplaceIsActive').value;

            let tagInputs = document.querySelectorAll('.updateMarketplaceTag');
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

            updateBtn.disabled = true;
            showLoader();

            let formData = new FormData();
            formData.append('id', marketplaceId);
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

            let res = await axios.post('/marketplace-update', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            hideLoader();
            updateBtn.disabled = false;

            if (res.data.status === 'success') {
                const modalElement = document.getElementById('updateModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalElement);
                }

                modalInstance.hide();

                await getMarketplaceData();

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Marketplace profile updated successfully',
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
            updateBtn.disabled = false;
            console.error('Error updating marketplace profile:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Update failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>