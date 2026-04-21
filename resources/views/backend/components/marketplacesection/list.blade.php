<div class="dash-section active" id="sec-projects">
    <div class="section-toolbar">
        <h2 class="section-h">Marketplace Profiles</h2>
        <button class="btn-primary-d" id="addProjectBtn" type="button" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            <i class="fas fa-plus me-2"></i>Add Marketpace Profile
        </button>
    </div>

    <div class="dash-card p-0">
        <div class="table-responsive">
            <table class="dash-table" id="example">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Description</th>
                        <th>Url</th>
                        <th>Badge</th>
                        <th>Tag</th>
                        <th>Item</th>
                        <th>Sales</th>
                        <th>Rating</th>
                        <th>Is Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableList"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    getMarketplaceData();

    async function getMarketplaceData() {
        try {
            let res = await axios.get('/marketplace-list');
            let tableList = $("#tableList");

            if ($.fn.DataTable.isDataTable('#example')) {
                $('#example').DataTable().destroy();
            }

            tableList.empty();

            res.data.rows.forEach(function (item) {
                let activeBadge = item.is_active == 1
                    ? `<span class="status-badge live">Active</span>`
                    : `<span class="status-badge archive">Inactive</span>`;

                let row = `
                    <tr>
                        <td>${item.user_name ?? ''}</td>
                        <td>${item.description ?? ''}</td>
                        <td>
                            ${item.url ? `<a href="${item.url}" target="_blank">${item.url}</a>` : ''}
                        </td>
                        <td>${item.badge ?? ''}</td>
                        <td>${item.tag ?? ''}</td>
                        <td>${item.item ?? 0}</td>
                        <td>${item.sales ?? 0}</td>
                        <td>${item.rating ?? 0}</td>
                        <td>${activeBadge}</td>
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
            console.error('Error fetching marketplace data:', error);
        }
    }
</script>