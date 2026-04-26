<div class="dash-section active" id="sec-invoices">
    <div class="section-toolbar">
        <h2 class="section-h">Invoices</h2>
        <button class="btn-primary-d" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fas fa-plus me-2"></i>Create Invoice
        </button>
    </div>

    <div class="dash-card p-0">
        <div class="table-responsive">
            <table class="dash-table" id="invoiceTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Invoice No</th>
                        <th>Client</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Cost</th>
                        <th>Profit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="invoiceTableList"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    getInvoiceData();

    function invoiceStatusBadge(status) {
        const current = String(status ?? 'draft').toLowerCase();

        if (current === 'paid') {
            return `<span class="status-badge live">Paid</span>`;
        }

        if (current === 'sent') {
            return `<span class="status-badge dev">Sent</span>`;
        }

        return `<span class="status-badge archive">${current.charAt(0).toUpperCase() + current.slice(1)}</span>`;
    }

    function invoiceMoney(value) {
        const amount = Number(value ?? 0);
        return `TK ${amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    }

    async function getInvoiceData() {
        try {
            let res = await axios.get('/invoice-list');
            let tableList = $('#invoiceTableList');

            if ($.fn.DataTable.isDataTable('#invoiceTable')) {
                $('#invoiceTable').DataTable().destroy();
            }

            tableList.empty();

            res.data.rows.forEach(function (item) {
                const total = parseFloat(item.total ?? 0) || 0;
                const internalCost = parseFloat(item.internal_cost ?? 0) || 0;
                const profit = total - internalCost;

                let row = `
                    <tr>
                        <td>${item.id ?? ''}</td>
                        <td>${item.invoice_number ?? ''}</td>
                        <td>${item.client_name ?? ''}</td>
                        <td>${item.issue_date ?? ''}</td>
                        <td>${item.due_date ?? ''}</td>
                        <td>${invoiceStatusBadge(item.status)}</td>
                        <td>${invoiceMoney(total)}</td>
                        <td>${invoiceMoney(internalCost)}</td>
                        <td>${invoiceMoney(profit)}</td>
                        <td>
                            <div class="row-actions">
                                <a href="/invoice-preview/${item.id}" class="ra-btn edit" target="_blank" title="Preview">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/invoice-download/${item.id}" class="ra-btn edit" target="_blank" title="Download PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <button data-id="${item.id}" class="ra-btn edit invoiceEditBtn">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button data-id="${item.id}" class="ra-btn del invoiceDeleteBtn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;

                tableList.append(row);
            });

            $(document).off('click', '.invoiceEditBtn').on('click', '.invoiceEditBtn', async function () {
                let id = $(this).data('id');
                await FillUpInvoiceUpdateForm(id);
                new bootstrap.Modal(document.getElementById('updateModal')).show();
            });

            $(document).off('click', '.invoiceDeleteBtn').on('click', '.invoiceDeleteBtn', function () {
                $('#deleteID').val($(this).data('id'));
                new bootstrap.Modal(document.getElementById('delete-modal')).show();
            });

            new DataTable('#invoiceTable', {
                order: [[0, 'desc']],
                lengthMenu: [10, 20, 50, 100],
            });
        } catch (error) {
            console.error('Error fetching invoice data:', error);
        }
    }
</script>
