<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">

<!-- Font Awesome -->
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"> --}}

<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Product Name</label>
                            <input type="text" id="productName" class="form-control" placeholder="Enter product name">
                        </div>

                        <div class="form-group-d">
                            <label>Status</label>
                            <select id="productStatus" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Badge</label>
                            <input type="text" id="productBadge" class="form-control" placeholder="e.g. Best Seller">
                        </div>

                        <div class="form-group-d">
                            <label>Icon</label>
                            <select id="productIcon" class="form-control">
                                <option value="">Select Developer Icon</option>
                                <option value="fas fa-code">Code</option>
                                <option value="fas fa-laptop-code">Laptop Code</option>
                                <option value="fas fa-terminal">Terminal</option>
                                <option value="fas fa-database">Database</option>
                                <option value="fas fa-server">Server</option>
                                <option value="fas fa-globe">Web</option>
                                <option value="fas fa-mobile-alt">Mobile App</option>
                                <option value="fas fa-cogs">Settings / Backend</option>
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
                                <option value="fab fa-node-js">Node.js</option>
                                <option value="fab fa-python">Python</option>
                                <option value="fab fa-github">GitHub</option>
                                <option value="fab fa-git-alt">Git</option>
                                <option value="fab fa-wordpress">WordPress</option>
                            </select>

                            <div id="iconPreview" style="margin-top:10px; font-size:24px;">
                                <i class="fas fa-code"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Short Description</label>
                        <textarea id="productDescription" class="form-control" rows="3" placeholder="Enter short description"></textarea>
                    </div>

                    <div class="form-group-d">
                        <label>Long Description / Summary / Note</label>
                        <textarea id="productLongDescription" class="form-control summernote"></textarea>
                    </div>

                    <div class="form-group-d">
                        <label>Product Image</label>
                        <input type="file" id="productImage" class="form-control" accept="image/*" onchange="previewProductImg(event)">
                        <div style="margin-top:10px;">
                            <img id="newProductImg" src="{{ asset('assets/images/default.jpg') }}" alt="Preview"
                                 style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Tags</label>
                        <div id="productTagWrapper">
                            <div class="product-tag-item" style="display:flex; gap:10px; margin-bottom:10px;">
                                <input type="text" class="productTag form-control" placeholder="Enter tag">
                                <button type="button" class="btn btn-danger" onclick="removeProductTag(this)">X</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addProductTagField()">Add Item</button>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Price</label>
                            <input type="number" id="productPrice" class="form-control" step="0.01" min="0" placeholder="Enter price">
                        </div>

                        <div class="form-group-d">
                            <label>Discount Price</label>
                            <input type="number" id="productDiscount" class="form-control" step="0.01" min="0" placeholder="Enter discount price">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Live Link</label>
                        <input type="url" id="productLiveLink" class="form-control" placeholder="https://example.com">
                    </div>

                    <button type="button" class="btn-primary-d" id="saveProductBtn" onclick="productCreate()">
                        Save Product <i class="fas fa-save ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#productLongDescription').summernote({
            placeholder: 'Write long description here...',
            tabsize: 2,
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('#productIcon').on('change', function () {
            let iconClass = $(this).val() || 'fas fa-code';
            $('#iconPreview').html(`<i class="${iconClass}"></i>`);
        });
    });

    function showLoader() {
        // optional loader show
    }

    function hideLoader() {
        // optional loader hide
    }

    function previewProductImg(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('newProductImg');
        preview.src = file ? URL.createObjectURL(file) : "{{ asset('assets/images/default.jpg') }}";
    }

    function addProductTagField(value = '') {
        let wrapper = document.getElementById('productTagWrapper');

        let div = document.createElement('div');
        div.className = 'product-tag-item';
        div.style.display = 'flex';
        div.style.gap = '10px';
        div.style.marginBottom = '10px';

        div.innerHTML = `
            <input type="text" class="productTag form-control" placeholder="Enter tag" value="${value}">
            <button type="button" class="btn btn-danger" onclick="removeProductTag(this)">X</button>
        `;

        wrapper.appendChild(div);
    }

    function removeProductTag(button) {
        let wrapper = document.getElementById('productTagWrapper');
        if (wrapper.children.length > 1) {
            button.parentElement.remove();
        }
    }

    async function productCreate() {
        const saveBtn = document.getElementById('saveProductBtn');

        try {
            const name = document.getElementById('productName').value.trim();
            const badge = document.getElementById('productBadge').value.trim();
            const description = document.getElementById('productDescription').value.trim();
            const longDescription = $('#productLongDescription').summernote('code');
            const price = document.getElementById('productPrice').value;
            const discount = document.getElementById('productDiscount').value;
            const icon = document.getElementById('productIcon').value.trim();
            const liveLink = document.getElementById('productLiveLink').value.trim();
            const status = document.getElementById('productStatus').value;
            const image = document.getElementById('productImage').files[0];

            let tagInputs = document.querySelectorAll('.productTag');
            let tags = [];

            tagInputs.forEach((item) => {
                if (item.value.trim() !== '') {
                    tags.push(item.value.trim());
                }
            });

            if (!name || !description || $('#productLongDescription').summernote('isEmpty') || !price || tags.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Name, Description, Long Description, Price and at least one Tag are required!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            saveBtn.disabled = true;
            showLoader();

            let formData = new FormData();
            formData.append('name', name);
            formData.append('badge', badge);
            formData.append('description', description);
            formData.append('long_description', longDescription);
            formData.append('price', price);
            formData.append('discount', discount ? discount : 0);
            formData.append('icon', icon);
            formData.append('live_link', liveLink);
            formData.append('status', status);

            tags.forEach((tag, index) => {
                formData.append(`tags[${index}]`, tag);
            });

            if (image) {
                formData.append('image', image);
            }

            const res = await axios.post('/product-create', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            hideLoader();
            saveBtn.disabled = false;

            if (res.data.status === 'success') {
                document.getElementById('productName').value = '';
                document.getElementById('productBadge').value = '';
                document.getElementById('productDescription').value = '';
                $('#productLongDescription').summernote('reset');
                document.getElementById('productPrice').value = '';
                document.getElementById('productDiscount').value = '';
                document.getElementById('productIcon').value = '';
                document.getElementById('productLiveLink').value = '';
                document.getElementById('productStatus').value = '1';
                document.getElementById('productImage').value = '';
                document.getElementById('newProductImg').src = "{{ asset('assets/images/default.jpg') }}";
                document.getElementById('iconPreview').innerHTML = '<i class="fas fa-code"></i>';

                document.getElementById('productTagWrapper').innerHTML = `
                    <div class="product-tag-item" style="display:flex; gap:10px; margin-bottom:10px;">
                        <input type="text" class="productTag form-control" placeholder="Enter tag">
                        <button type="button" class="btn btn-danger" onclick="removeProductTag(this)">X</button>
                    </div>
                `;

                const modalElement = document.getElementById('exampleModal');
                let modalInstance = bootstrap.Modal.getInstance(modalElement);

                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalElement);
                }

                modalInstance.hide();

                await getProductData();

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Product created successfully',
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
            console.error('Error creating product:', error);
            console.log(error.response?.data);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Create failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>