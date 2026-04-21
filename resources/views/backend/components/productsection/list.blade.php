<div class="dash-section active" id="sec-projects">
    <div class="section-toolbar">
        <h2 class="section-h">Products</h2>
        <button class="btn-primary-d" id="addProjectBtn" type="button" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            <i class="fas fa-plus me-2"></i>Add Product
        </button>
    </div>

    <div class="dash-card p-0">
        <div class="table-responsive">
            <table class="dash-table" id="example">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Long Description</th>
                        <th>Tags</th>
                        <th>Price</th>
                        <th>Discount Price</th>
                        <th>Status</th>
                        <th>Icon</th>
                        <th>Live Link</th>
                        <th>Badge</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableList"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    getProductData();

    async function getProductData() {
        try {
            let res = await axios.get('/product-list');
            let tableList = $("#tableList");

            if ($.fn.DataTable.isDataTable('#example')) {
                $('#example').DataTable().destroy();
            }

            tableList.empty();

            res.data.rows.forEach(function (item) {
                let statusClass = '';
                let statusText = '';

                if (String(item.status) === '1') {
                    statusClass = 'live';
                    statusText = 'Active';
                } else {
                    statusClass = 'archive';
                    statusText = 'Inactive';
                }

                let tags = [];

                try {
                    tags = item.tag
                        ? (Array.isArray(item.tag) ? item.tag : JSON.parse(item.tag))
                        : [];
                } catch (e) {
                    tags = [];
                }

                let row = `
                    <tr>
                        <td>
                            <img src="/${item.image ?? ''}" alt="Product Image" style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                        </td>
                        <td>${item.name ?? ''}</td>
                        <td>${item.description ?? ''}</td>
                        <td>${item.long_description ?? ''}</td>
                        <td>
                            ${tags.map(tag => `<span class="tech-tag">${tag}</span>`).join(' ')}
                        </td>
                        <td>${item.price ?? 0}</td>
                        <td>${item.discount ?? 0}</td>
                        <td>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </td>
                        <td>
                            ${item.icon ? `<i class="${item.icon}"></i> <span style="margin-left:6px;">${item.icon}</span>` : ''}
                        </td>
                        <td>
                            ${item.live_link ? `<a href="${item.live_link}" target="_blank">${item.live_link}</a>` : ''}
                        </td>
                        <td>${item.badge ?? ''}</td>
                        <td>
                            <div class="row-actions">
                                <button data-id="${item.id}" class="ra-btn edit editBtn">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button data-id="${item.id}" class="ra-btn del deleteBtn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;

                tableList.append(row);
            });

            $(document).off('click', '.editBtn').on('click', '.editBtn', async function () {
                let id = $(this).data('id');
                await FillUpUpdateForm(id);

                let updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
                updateModal.show();
            });

            $(document).off('click', '.deleteBtn').on('click', '.deleteBtn', function () {
                let id = $(this).data('id');
                $("#deleteID").val(id);

                let deleteModal = new bootstrap.Modal(document.getElementById('delete-modal'));
                deleteModal.show();
            });

            new DataTable('#example', {
                order: [[0, 'desc']],
                lengthMenu: [10, 20, 50, 100],
            });

        } catch (error) {
            console.error('Error fetching product data:', error);
        }
    }
</script>