<div class="dash-section active" id="sec-clientsource">
    <div class="section-toolbar">
        <h2 class="section-h">Client Sources</h2>
        <button class="btn-primary-d" id="addClientsourceBtn" type="button" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            <i class="fas fa-plus me-2"></i>Add Client Source
        </button>
    </div>

    <div class="dash-card p-0">
        <div class="table-responsive">
            <table class="dash-table" id="example">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Work</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableList"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    getClientsourceData();

    async function getClientsourceData() {
        try {
            let res = await axios.get('/clientsource-list');
            let tableList = $('#tableList');

            if ($.fn.DataTable.isDataTable('#example')) {
                $('#example').DataTable().destroy();
            }

            tableList.empty();

            res.data.rows.forEach(function (item) {
                let row = `
                    <tr>
                        <td>${item.id ?? ''}</td>
                        <td>${item.name ?? ''}</td>
                        <td>${item.phone ?? ''}</td>
                        <td>${item.work ?? ''}</td>
                        <td>${item.price ?? ''}</td>
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
                $('#deleteID').val(id);

                let deleteModal = new bootstrap.Modal(document.getElementById('delete-modal'));
                deleteModal.show();
            });

            new DataTable('#example', {
                order: [[0, 'desc']],
                lengthMenu: [10, 20, 50, 100],
            });

        } catch (error) {
            console.error('Error fetching client source data:', error);
        }
    }
</script>
