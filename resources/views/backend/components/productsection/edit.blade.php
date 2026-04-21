
<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Update Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <input type="hidden" id="updateProductId">
                    <input type="hidden" id="oldProductImage">

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Product Title</label>
                            <input type="text" id="updateProductTitle" class="form-control" placeholder="Enter product title">
                        </div>

                        <div class="form-group-d">
                            <label>Status</label>
                            <select id="updateProductStatus" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Badge</label>
                            <input type="text" id="updateProductBadge" class="form-control" placeholder="e.g. Best Seller">
                        </div>

                        <div class="form-group-d">
                            <label>Icon</label>
                            <select id="updateProductIcon" class="form-control">
                                <option value="">Select Developer Icon</option>
                                <option value="fas fa-code">Code</option>
                                <option value="fas fa-laptop-code">Laptop Code</option>
                                <option value="fas fa-terminal">Terminal</option>
                                <option value="fas fa-database">Database</option>
                                <option value="fas fa-server">Server</option>
                                <option value="fas fa-globe">Web</option>
                                <option value="fas fa-mobile-alt">Mobile App</option>
                                <option value="fas fa-cogs">Backend</option>
                                <option value="fas fa-bug">Bug Fix</option>
                                <option value="fas fa-shield-alt">Security</option>
                                <option value="fas fa-cloud">Cloud</option>
                                <option value="fas fa-network-wired">Network</option>
                                <option value="fas fa-microchip">Technology</option>
                                <option value="fab fa-html5">HTML5</option>
                                <option value="fab fa-css3-alt">CSS3</option>
                                <option value="fab fa-js">JavaScript</option>
                                <option value="fab fa-php">PHP</option>
                                <option value="fab fa-laravel">Laravel</option>
                                <option value="fab fa-react">React</option>
                                <option value="fab fa-vuejs">Vue</option>
                                <option value="fab fa-node-js">Node JS</option>
                                <option value="fab fa-python">Python</option>
                                <option value="fab fa-github">GitHub</option>
                                <option value="fab fa-git-alt">Git</option>
                                <option value="fab fa-wordpress">WordPress</option>
                            </select>

                            <div id="updateIconPreview" style="margin-top:10px; font-size:24px;">
                                <i class="fas fa-code"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Description</label>
                        <textarea id="updateProductDescription" class="form-control" rows="3" placeholder="Enter short description"></textarea>
                    </div>

                    <div class="form-group-d">
                        <label>Long Description</label>
                        <textarea id="updateProductLongDescription" class="form-control"></textarea>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Price</label>
                            <input type="number" id="updateProductPrice" class="form-control" step="0.01" min="0" placeholder="Enter price">
                        </div>

                        <div class="form-group-d">
                            <label>Discount Price</label>
                            <input type="number" id="updateProductDiscount" class="form-control" step="0.01" min="0" placeholder="Enter discount price">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Live Link</label>
                        <input type="url" id="updateProductLiveLink" class="form-control" placeholder="https://example.com">
                    </div>

                    <div class="form-group-d">
                        <label>Product Image</label>
                        <input type="file" id="updateProductImage" class="form-control" accept="image/*" onchange="previewUpdateProductImg(event)">
                        <div style="margin-top:10px;">
                            <img id="updateProductPreview" src="{{ asset('assets/images/default.jpg') }}" alt="Preview"
                                 style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Tags</label>
                        <div id="updateProductTagWrapper"></div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addUpdateProductTagField()">Add Item</button>
                    </div>

                    <button type="button" class="btn-primary-d" id="updateProductBtn" onclick="productUpdate()">
                        Update Product <i class="fas fa-save ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        if ($.fn.summernote) {
            $('#updateProductLongDescription').summernote({
                placeholder: 'Write long description here...',
                tabsize: 2,
                height: 250,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        } else {
            console.error('Summernote failed to load. Check jQuery and script order.');
        }

        $('#updateProductIcon').on('change', function () {
            let iconClass = $(this).val() || 'fas fa-code';
            $('#updateIconPreview').html(`<i class="${iconClass}"></i>`);
        });
    });

    function showLoader() {
        // optional loader show
    }

    function hideLoader() {
        // optional loader hide
    }

    function previewUpdateProductImg(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('updateProductPreview');
        preview.src = file ? URL.createObjectURL(file) : "{{ asset('assets/images/default.jpg') }}";
    }

    function addUpdateProductTagField(value = '') {
        let wrapper = document.getElementById('updateProductTagWrapper');

        let div = document.createElement('div');
        div.className = 'product-tag-item';
        div.style.display = 'flex';
        div.style.gap = '10px';
        div.style.marginBottom = '10px';

        div.innerHTML = `
            <input type="text" class="updateProductTag form-control" placeholder="Enter tag" value="${value}">
            <button type="button" class="btn btn-danger" onclick="removeUpdateProductTag(this)">X</button>
        `;

        wrapper.appendChild(div);
    }

    function removeUpdateProductTag(button) {
        let wrapper = document.getElementById('updateProductTagWrapper');
        if (wrapper.children.length > 1) {
            button.parentElement.remove();
        }
    }

    async function FillUpUpdateForm(id) {
        try {
            document.getElementById('updateProductId').value = id;
            document.getElementById('updateProductTagWrapper').innerHTML = '';

            let res = await axios.post('/product-by-id', { id: id });
            let data = res.data.row ?? res.data.data ?? res.data;

            document.getElementById('updateProductTitle').value = data.name ?? '';
            document.getElementById('updateProductBadge').value = data.badge ?? '';
            document.getElementById('updateProductDescription').value = data.description ?? '';
            $('#updateProductLongDescription').summernote('code', data.long_description ?? '');
            document.getElementById('updateProductPrice').value = data.price ?? '';
            document.getElementById('updateProductDiscount').value = data.discount ?? '';
            document.getElementById('updateProductIcon').value = data.icon ?? '';
            document.getElementById('updateProductLiveLink').value = data.live_link ?? '';
            document.getElementById('updateProductStatus').value = String(data.status ?? '1');
            document.getElementById('oldProductImage').value = data.image ?? '';
            document.getElementById('updateProductPreview').src =
                data.image ? `/${data.image}` : "{{ asset('assets/images/default.jpg') }}";

            let iconClass = data.icon ?? 'fas fa-code';
            document.getElementById('updateIconPreview').innerHTML = `<i class="${iconClass}"></i>`;

            let tags = [];
            try {
                tags = data.tag ? (Array.isArray(data.tag) ? data.tag : JSON.parse(data.tag)) : [];
            } catch (e) {
                tags = [];
            }

            if (tags.length > 0) {
                tags.forEach(tag => addUpdateProductTagField(tag));
            } else {
                addUpdateProductTagField('');
            }
        } catch (error) {
            console.error('Error filling update form:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Failed to load product data',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }

    async function productUpdate() {
        const updateBtn = document.getElementById('updateProductBtn');

        try {
            const productId = document.getElementById('updateProductId').value;
            const name = document.getElementById('updateProductTitle').value.trim();
            const badge = document.getElementById('updateProductBadge').value.trim();
            const description = document.getElementById('updateProductDescription').value.trim();
            const longDescription = $('#updateProductLongDescription').summernote('code');
            const price = document.getElementById('updateProductPrice').value;
            const discount = document.getElementById('updateProductDiscount').value;
            const icon = document.getElementById('updateProductIcon').value.trim();
            const liveLink = document.getElementById('updateProductLiveLink').value.trim();
            const status = document.getElementById('updateProductStatus').value;
            const image = document.getElementById('updateProductImage').files[0];
            const oldImage = document.getElementById('oldProductImage').value;

            let tags = [];
            document.querySelectorAll('.updateProductTag').forEach((item) => {
                if (item.value.trim() !== '') {
                    tags.push(item.value.trim());
                }
            });

            if (!productId || !name || !description || $('#updateProductLongDescription').summernote('isEmpty') || !price || tags.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Name, Description, Long Description, Price and at least one Tag are required!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            updateBtn.disabled = true;
            showLoader();

            let formData = new FormData();
            formData.append('id', productId);
            formData.append('name', name);
            formData.append('badge', badge);
            formData.append('description', description);
            formData.append('long_description', longDescription);
            formData.append('price', price);
            formData.append('discount', discount ? discount : 0);
            formData.append('icon', icon);
            formData.append('live_link', liveLink);
            formData.append('status', status);
            formData.append('old_image', oldImage);

            tags.forEach((tag, index) => {
                formData.append(`tags[${index}]`, tag);
            });

            if (image) {
                formData.append('image', image);
            }

            let res = await axios.post('/product-update', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            hideLoader();
            updateBtn.disabled = false;

            if (res.data.status === 'success') {
                document.getElementById('updateProductImage').value = '';

                let modalElement = document.getElementById('updateModal');
                let modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
                modalInstance.hide();

                await getProductData();

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Product updated successfully',
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
            console.error('Error updating product:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Update failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>