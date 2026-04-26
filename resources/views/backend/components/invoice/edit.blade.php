<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="dash-card profile-form-card">
                    <input type="hidden" id="updateInvoiceId">

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Invoice Number</label>
                            <input type="text" id="updateInvoiceNumber" placeholder="Enter invoice number">
                        </div>

                        <div class="form-group-d">
                            <label>Status</label>
                            <select id="updateInvoiceStatus">
                                <option value="draft">Draft</option>
                                <option value="sent">Sent</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Client Name</label>
                            <input type="text" id="updateInvoiceClientName" placeholder="Enter client name">
                        </div>

                        <div class="form-group-d">
                            <label>Client Email</label>
                            <input type="email" id="updateInvoiceClientEmail" placeholder="Enter client email">
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Client Phone</label>
                            <input type="text" id="updateInvoiceClientPhone" placeholder="Enter client phone">
                        </div>

                        <div class="form-group-d">
                            <label>Issue Date</label>
                            <input type="date" id="updateInvoiceIssueDate">
                        </div>
                    </div>

                    <div class="form-row-d">
                        <div class="form-group-d">
                            <label>Due Date</label>
                            <input type="date" id="updateInvoiceDueDate">
                        </div>

                        <div class="form-group-d">
                            <label>Discount</label>
                            <input type="number" step="0.01" min="0" id="updateInvoiceDiscount" value="0">
                        </div>
                    </div>

                    <div class="form-group-d">
                        <label>Internal Cost</label>
                        <input type="number" step="0.01" min="0" id="updateInvoiceInternalCost" value="0">
                    </div>

                    <div class="form-group-d">
                        <label>Client Address</label>
                        <textarea id="updateInvoiceClientAddress" rows="3" placeholder="Enter client address"></textarea>
                    </div>

                    <div class="form-group-d">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="mb-0">Invoice Items</label>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="addUpdateInvoiceItemRow()">Add Item</button>
                        </div>
                        <div id="updateInvoiceItemsWrapper"></div>
                    </div>

                    <div class="form-group-d">
                        <label>Notes</label>
                        <textarea id="updateInvoiceNotes" rows="3" placeholder="Enter invoice notes"></textarea>
                    </div>

                    <div class="invoice-summary-box">
                        <span>Subtotal: <strong id="updateInvoiceSubtotalText">0.00</strong></span>
                        <span>Total: <strong id="updateInvoiceTotalText">0.00</strong></span>
                        <span>Cost: <strong id="updateInvoiceInternalCostText">0.00</strong></span>
                        <span>Profit: <strong id="updateInvoiceProfitText">0.00</strong></span>
                    </div>

                    <button type="button" class="btn-primary-d" onclick="invoiceUpdate()">
                        Update Invoice <i class="fas fa-save ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addUpdateInvoiceItemRow(item = { description: '', qty: 1, rate: 0 }) {
        let wrapper = document.getElementById('updateInvoiceItemsWrapper');
        let row = document.createElement('div');
        row.className = 'update-invoice-item-row';
        row.innerHTML = `
            <div class="invoice-item-grid">
                <input type="text" class="update-invoice-item-description" placeholder="Item description" value="${item.description ?? ''}">
                <input type="number" class="update-invoice-item-qty" placeholder="Qty" min="0" step="0.01" value="${item.qty ?? 1}">
                <input type="number" class="update-invoice-item-rate" placeholder="Rate" min="0" step="0.01" value="${item.rate ?? 0}">
                <div class="invoice-item-amount">0.00</div>
                <button type="button" class="btn btn-danger invoice-item-remove" onclick="removeUpdateInvoiceItemRow(this)">X</button>
            </div>
        `;
        wrapper.appendChild(row);
        recalculateUpdateInvoiceSummary();
    }

    function removeUpdateInvoiceItemRow(button) {
        button.closest('.update-invoice-item-row').remove();
        if (!document.querySelector('#updateInvoiceItemsWrapper .update-invoice-item-row')) {
            addUpdateInvoiceItemRow();
        }
        recalculateUpdateInvoiceSummary();
    }

    function recalculateUpdateInvoiceSummary() {
        let subtotal = 0;

        document.querySelectorAll('#updateInvoiceItemsWrapper .update-invoice-item-row').forEach((row) => {
            const qty = parseFloat(row.querySelector('.update-invoice-item-qty')?.value ?? 0);
            const rate = parseFloat(row.querySelector('.update-invoice-item-rate')?.value ?? 0);
            const amount = qty * rate;
            subtotal += amount;
            row.querySelector('.invoice-item-amount').textContent = amount.toFixed(2);
        });

        const discount = parseFloat(document.getElementById('updateInvoiceDiscount').value || 0);
        const total = Math.max(subtotal - discount, 0);
        const internalCost = parseFloat(document.getElementById('updateInvoiceInternalCost').value || 0);
        const profit = total - internalCost;

        document.getElementById('updateInvoiceSubtotalText').textContent = subtotal.toFixed(2);
        document.getElementById('updateInvoiceTotalText').textContent = total.toFixed(2);
        document.getElementById('updateInvoiceInternalCostText').textContent = internalCost.toFixed(2);
        document.getElementById('updateInvoiceProfitText').textContent = profit.toFixed(2);
    }

    document.addEventListener('input', function (event) {
        if (event.target.matches('#updateInvoiceItemsWrapper input, #updateInvoiceDiscount, #updateInvoiceInternalCost')) {
            recalculateUpdateInvoiceSummary();
        }
    });

    async function FillUpInvoiceUpdateForm(id) {
        try {
            let res = await axios.post('/invoice-by-id', { id: id });
            let data = res.data.row ?? {};

            document.getElementById('updateInvoiceId').value = data.id ?? '';
            document.getElementById('updateInvoiceNumber').value = data.invoice_number ?? '';
            document.getElementById('updateInvoiceClientName').value = data.client_name ?? '';
            document.getElementById('updateInvoiceClientEmail').value = data.client_email ?? '';
            document.getElementById('updateInvoiceClientPhone').value = data.client_phone ?? '';
            document.getElementById('updateInvoiceClientAddress').value = data.client_address ?? '';
            document.getElementById('updateInvoiceIssueDate').value = data.issue_date ?? '';
            document.getElementById('updateInvoiceDueDate').value = data.due_date ?? '';
            document.getElementById('updateInvoiceStatus').value = data.status ?? 'draft';
            document.getElementById('updateInvoiceDiscount').value = data.discount ?? 0;
            document.getElementById('updateInvoiceInternalCost').value = data.internal_cost ?? 0;
            document.getElementById('updateInvoiceNotes').value = data.notes ?? '';
            document.getElementById('updateInvoiceItemsWrapper').innerHTML = '';

            if (Array.isArray(data.items) && data.items.length > 0) {
                data.items.forEach((item) => addUpdateInvoiceItemRow(item));
            } else {
                addUpdateInvoiceItemRow();
            }

            recalculateUpdateInvoiceSummary();
        } catch (error) {
            console.error('Error loading invoice data:', error);
            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Failed to load invoice data',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }

    async function invoiceUpdate() {
        try {
            const payload = {
                id: document.getElementById('updateInvoiceId').value,
                invoice_number: document.getElementById('updateInvoiceNumber').value.trim(),
                client_name: document.getElementById('updateInvoiceClientName').value.trim(),
                client_email: document.getElementById('updateInvoiceClientEmail').value.trim(),
                client_phone: document.getElementById('updateInvoiceClientPhone').value.trim(),
                client_address: document.getElementById('updateInvoiceClientAddress').value.trim(),
                issue_date: document.getElementById('updateInvoiceIssueDate').value,
                due_date: document.getElementById('updateInvoiceDueDate').value,
                status: document.getElementById('updateInvoiceStatus').value,
                discount: document.getElementById('updateInvoiceDiscount').value,
                internal_cost: document.getElementById('updateInvoiceInternalCost').value,
                notes: document.getElementById('updateInvoiceNotes').value.trim(),
                items: collectInvoiceItems('#updateInvoiceItemsWrapper'),
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

            showLoader();
            let res = await axios.post('/invoice-update', payload);
            hideLoader();

            if (res.data.status === 'success') {
                let modalInstance = bootstrap.Modal.getInstance(document.getElementById('updateModal'));
                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(document.getElementById('updateModal'));
                }
                modalInstance.hide();

                await getInvoiceData();

                Swal.fire({
                    icon: 'success',
                    title: res.data.message ?? 'Invoice updated successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        } catch (error) {
            hideLoader();
            console.error('Error updating invoice:', error);

            Swal.fire({
                icon: 'error',
                title: error.response?.data?.message ?? 'Update failed',
                showConfirmButton: false,
                timer: 2000
            });
        }
    }
</script>
