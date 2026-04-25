<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Invoice Number</label>
                            <input type="text" id="invoiceNumber" placeholder="Auto generate if empty">
                        </div>

                        <div class="form-group-d">
                            <label>Status</label>
                            <select id="invoiceStatus">
                                <option value="draft">Draft</option>
                                <option value="sent">Sent</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Client Name</label>
                            <input type="text" id="invoiceClientName" placeholder="Enter client name">
                        </div>

                        <div class="form-group-d">
                            <label>Client Email</label>
                            <input type="email" id="invoiceClientEmail" placeholder="Enter client email">
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Client Phone</label>
                            <input type="text" id="invoiceClientPhone" placeholder="Enter client phone">
                        </div>

                        <div class="form-group-d">
                            <label>Issue Date</label>
                            <input type="date" id="invoiceIssueDate" value="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Due Date</label>
                            <input type="date" id="invoiceDueDate">
                        </div>

                        <div class="form-group-d">
                            <label>Discount</label>
                            <input type="number" step="0.01" min="0" id="invoiceDiscount" value="0">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Client Address</label>
                        <textarea id="invoiceClientAddress" rows="3" placeholder="Enter client address"></textarea>
                    </div>

                    <div class="form-group-d">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="mb-0">Invoice Items</label>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="addInvoiceItemRow()">Add Item</button>
                        </div>
                        <div id="invoiceItemsWrapper"></div>
                    </div>

                    <div class="form-group-d">
                        <label>Notes</label>
                        <textarea id="invoiceNotes" rows="3" placeholder="Enter invoice notes"></textarea>
                    </div>

                    <div class="invoice-summary-box">
                        <span>Subtotal: <strong id="invoiceSubtotalText">0.00</strong></span>
                        <span>Total: <strong id="invoiceTotalText">0.00</strong></span>
                    </div>

                    <button type="button" class="btn-primary-d" id="saveInvoiceBtn" onclick="invoiceCreate()">
                        Save Invoice <i class="fas fa-save ms-2"></i>
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

    function addInvoiceItemRow(item = { description: '', qty: 1, rate: 0 }) {
        let wrapper = document.getElementById('invoiceItemsWrapper');
        let row = document.createElement('div');
        row.className = 'invoice-item-row';
        row.innerHTML = `
            <div class="invoice-item-grid">
                <input type="text" class="invoice-item-description" placeholder="Item description" value="${item.description ?? ''}">
                <input type="number" class="invoice-item-qty" placeholder="Qty" min="0" step="0.01" value="${item.qty ?? 1}">
                <input type="number" class="invoice-item-rate" placeholder="Rate" min="0" step="0.01" value="${item.rate ?? 0}">
                <div class="invoice-item-amount">0.00</div>
                <button type="button" class="btn btn-danger invoice-item-remove" onclick="removeInvoiceItemRow(this)">X</button>
            </div>
        `;
        wrapper.appendChild(row);
        recalculateInvoiceSummary();
    }

    function removeInvoiceItemRow(button) {
        button.closest('.invoice-item-row').remove();
        if (!document.querySelector('#invoiceItemsWrapper .invoice-item-row')) {
            addInvoiceItemRow();
        }
        recalculateInvoiceSummary();
    }

    function collectInvoiceItems(wrapperSelector) {
        let items = [];

        document.querySelectorAll(`${wrapperSelector} .invoice-item-row, ${wrapperSelector} .update-invoice-item-row`).forEach((row) => {
            const description = row.querySelector('.invoice-item-description, .update-invoice-item-description')?.value.trim() ?? '';
            const qty = parseFloat(row.querySelector('.invoice-item-qty, .update-invoice-item-qty')?.value ?? 0);
            const rate = parseFloat(row.querySelector('.invoice-item-rate, .update-invoice-item-rate')?.value ?? 0);

            if (description !== '' && qty > 0) {
                items.push({ description, qty, rate });
            }
        });

        return items;
    }

    function recalculateInvoiceSummary() {
        let subtotal = 0;

        document.querySelectorAll('#invoiceItemsWrapper .invoice-item-row').forEach((row) => {
            const qty = parseFloat(row.querySelector('.invoice-item-qty')?.value ?? 0);
            const rate = parseFloat(row.querySelector('.invoice-item-rate')?.value ?? 0);
            const amount = qty * rate;
            subtotal += amount;
            row.querySelector('.invoice-item-amount').textContent = amount.toFixed(2);
        });

        const discount = parseFloat(document.getElementById('invoiceDiscount').value || 0);
        const total = Math.max(subtotal - discount, 0);

        document.getElementById('invoiceSubtotalText').textContent = subtotal.toFixed(2);
        document.getElementById('invoiceTotalText').textContent = total.toFixed(2);
    }

    document.addEventListener('input', function (event) {
        if (event.target.matches('#invoiceItemsWrapper input, #invoiceDiscount')) {
            recalculateInvoiceSummary();
        }
    });

    document.getElementById('exampleModal').addEventListener('show.bs.modal', function () {
        if (!document.querySelector('#invoiceItemsWrapper .invoice-item-row')) {
            addInvoiceItemRow();
        }
    });

    async function invoiceCreate() {
        const saveBtn = document.getElementById('saveInvoiceBtn');

        try {
            const payload = {
                invoice_number: document.getElementById('invoiceNumber').value.trim(),
                client_name: document.getElementById('invoiceClientName').value.trim(),
                client_email: document.getElementById('invoiceClientEmail').value.trim(),
                client_phone: document.getElementById('invoiceClientPhone').value.trim(),
                client_address: document.getElementById('invoiceClientAddress').value.trim(),
                issue_date: document.getElementById('invoiceIssueDate').value,
                due_date: document.getElementById('invoiceDueDate').value,
                status: document.getElementById('invoiceStatus').value,
                discount: document.getElementById('invoiceDiscount').value,
                notes: document.getElementById('invoiceNotes').value.trim(),
                items: collectInvoiceItems('#invoiceItemsWrapper'),
            };

            if (!payload.client_name || !payload.issue_date || payload.items.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Client name, issue date and at least one item are required!',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            saveBtn.disabled = true;
            showLoader();

            let res = await axios.post('/invoice-create', payload);

            hideLoader();
            saveBtn.disabled = false;

            if (res.data.status === 'success') {
                document.getElementById('invoiceNumber').value = '';
                document.getElementById('invoiceClientName').value = '';
                document.getElementById('invoiceClientEmail').value = '';
                document.getElementById('invoiceClientPhone').value = '';
                document.getElementById('invoiceClientAddress').value = '';
                document.getElementById('invoiceIssueDate').value = '{{ now()->format('Y-m-d') }}';
                document.getElementById('invoiceDueDate').value = '';
                document.getElementById('invoiceStatus').value = 'draft';
                document.getElementById('invoiceDiscount').value = '0';
                document.getElementById('invoiceNotes').value = '';
                document.getElementById('invoiceItemsWrapper').innerHTML = '';
                addInvoiceItemRow();

                let modalInstance = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(document.getElementById('exampleModal'));
                }
                modalInstance.hide();

                await getInvoiceData();

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Invoice created successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        } catch (error) {
            hideLoader();
            saveBtn.disabled = false;
            console.error('Error creating invoice:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Create failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>

<style>
    .invoice-item-grid {
        display: grid;
        grid-template-columns: 2fr 0.7fr 0.9fr 0.9fr auto;
        gap: 12px;
        margin-bottom: 12px;
        align-items: center;
    }

    .invoice-item-amount {
        border: 1px solid var(--border-vis);
        border-radius: 10px;
        padding: 11px 14px;
        color: var(--text);
        background: rgba(255, 255, 255, 0.02);
    }

    .invoice-summary-box {
        display: flex;
        justify-content: flex-end;
        gap: 20px;
        color: var(--text);
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .invoice-item-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
