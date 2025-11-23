$(document).ready(function() {
    // Check if yarnCounts global variable is available
    if (typeof yarnCounts === 'undefined' || !Array.isArray(yarnCounts)) {
        console.error('Yarn Counts data is missing or not an array. Please ensure it is passed from the Blade template.');
        var yarnCounts = []; // Fallback to empty array
    }

    // --- Core Data Storage ---
    // Stores items temporarily added in the modal
    let customerStockItems = [];
    
    // --- Utility Functions ---
    
    // 1. Challan Number Auto-Generation
    function generateChallanNo() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hour = String(now.getHours()).padStart(2, '0');
        const minute = String(now.getMinutes()).padStart(2, '0');
        const second = String(now.getSeconds()).padStart(2, '0');
        return `CUST-${year}${month}${day}-${hour}${minute}${second}`;
    }

    // 2. Populate Yarn Count Select
    function getYarnCountOptions(selectedValue = null) {
        let options = '<option value="">Select Yarn Count</option>';
        yarnCounts.forEach(yc => {
            const selected = yc.id == selectedValue ? 'selected' : '';
            options += `<option value="${yc.id}" ${selected}>${yc.name}</option>`;
        });
        return options;
    }

    // 3. Add Item Row in Modal
    function addItemRow(item = {}) {
        const yarnCountId = item.yarn_count_id || '';
        const quantity = item.quantity || '';
        const unitPrice = item.unit_price || '';

        const newRow = `
            <tr>
                <td>
                    <select class="form-control form-control-sm yarn-count-select" required>
                        ${getYarnCountOptions(yarnCountId)}
                    </select>
                </td>
                <td><input type="number" class="form-control form-control-sm quantity-input" step="any" min="0.01" placeholder="Quantity" value="${quantity}" required></td>
                <td><input type="number" class="form-control form-control-sm unit-price-input" step="0.01" min="0" placeholder="Unit Price" value="${unitPrice}" required></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-item-row">❌ Remove</button></td>
            </tr>
        `;
        $('#itemsModalTableBody').append(newRow);
        
        toggleRemoveButtons();
    }
    
    // 4. Toggle Remove Buttons
    function toggleRemoveButtons() {
        const rowCount = $('#itemsModalTableBody tr').length;
        // Disable remove button if only one row remains
        $('.remove-item-row').prop('disabled', rowCount <= 1);
    }

    // 5. Calculate and Update Totals
    function calculateTotals() {
        // Calculate subtotal based on stored items
        let subtotal = 0;
        customerStockItems.forEach(item => {
            subtotal += (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0);
        });

        // Calculate Discount
        const discountValue = parseFloat($('#discount').val()) || 0;
        const discountType = $('#discount_type').val();
        let discountAmount = 0;

        if (discountType === '0') { // Percentage
            discountAmount = (subtotal * discountValue) / 100;
        } else if (discountType === '1') { // Fixed
            discountAmount = discountValue;
        }

        const grandTotal = subtotal - discountAmount;

        // Update Display Fields
        $('#subtotal').val(subtotal.toFixed(2));
        $('#discount_amount_display').val(discountAmount.toFixed(2));
        $('#grand_total_display').val(grandTotal.toFixed(2));
    }
    
    // 6. Update Added Items Display Table (in main form)
    function updateAddedItemsDisplay() {
        let addedItemsHtml = '';
        
        if (customerStockItems.length === 0) {
            addedItemsHtml = '<tr><td colspan="4" class="text-center text-muted" id="noItemsMessage">No items added yet.</td></tr>';
        } else {
            customerStockItems.forEach(item => {
                // Find the name using the ID from the global array
                const yarnCountName = yarnCounts.find(yc => yc.id == item.yarn_count_id)?.name || 'N/A';
                const subtotal = (parseFloat(item.quantity) * parseFloat(item.unit_price)).toFixed(2);
                
                addedItemsHtml += `
                    <tr>
                        <td>${yarnCountName}</td>
                        <td>${item.quantity}</td>
                        <td>${parseFloat(item.unit_price).toFixed(2)}</td>
                        <td class="text-right">${subtotal}</td>
                    </tr>
                `;
            });
        }
        
        // Update the main form display table
        $('#addedItemsTable').html(addedItemsHtml);
        
        // Update hidden input for form submission
        $('#item_data_input').val(JSON.stringify(customerStockItems));
        
        // Recalculate and display totals
        calculateTotals();
    }
    
    // 7. Collect and Validate Items from Modal
    function collectModalItems() {
        let tempItems = [];
        let isValid = true;

        $('#itemsModalTableBody tr').each(function() {
            const row = $(this);
            const yarnCountId = row.find('.yarn-count-select').val();
            const quantity = row.find('.quantity-input').val();
            const unitPrice = row.find('.unit-price-input').val();
            
            row.removeClass('table-danger'); // Clear previous validation highlight

            if (yarnCountId && parseFloat(quantity) > 0 && parseFloat(unitPrice) >= 0) {
                 tempItems.push({
                    yarn_count_id: yarnCountId,
                    quantity: quantity,
                    unit_price: unitPrice
                });
            } else if (yarnCountId || quantity || unitPrice) {
                 // Partially filled rows are invalid
                isValid = false;
                row.addClass('table-danger');
            }
        });
        
        if (!isValid) {
            alert('Please ensure all required fields (Yarn Count, Quantity, Unit Price) are filled correctly, or remove incomplete rows (highlighted in red).');
            return false;
        }

        customerStockItems = tempItems;
        return true;
    }


    // --- Event Handlers ---

    // Initialize Select2 for Customer (as in your original code)
    $('#customer_id').select2({
        ajax: {
            url: baseUrl + "/customer", 
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    page: params.page || 1,
                    limit: 20
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                const options = [];
                
                if (Array.isArray(data.data) && data.data.length > 0) {
                    data.data.forEach(val => {
                        options.push({
                            id: val.id,
                            text: `${val.name} - ${val.customer_group?.name || 'N/A'}`,
                            customer: val 
                        });
                    });
                }
                
                return {
                    results: options,
                    pagination: {
                        more: data.current_page < data.last_page
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 0,
        placeholder: 'Select a customer',
        allowClear: true
    });

    // Challan No initialization and Date default value
    $('#challan_no').val(generateChallanNo());
    $('#stock_date').attr('value', new Date().toISOString().substring(0, 10));

    // Listeners for Total Calculations
    $('#discount, #discount_type').on('input change', calculateTotals);
    
    // Add Item Row button in Modal
    $('#addItemRow').click(function() {
        addItemRow();
    });

    // Remove Item Row in Modal (Delegated event listener)
    $(document).on('click', '#itemsModalTableBody .remove-item-row', function() {
        if ($('#itemsModalTableBody tr').length > 1) {
            $(this).closest('tr').remove();
        }
        toggleRemoveButtons();
    });

    // Update totals when inputs in the modal are changed (delegated event)
    $(document).on('input', '#itemsModalTableBody .quantity-input, #itemsModalTableBody .unit-price-input, #itemsModalTableBody .yarn-count-select', function() {
        // This input only forces a recalculation on save, not live.
        // We ensure data is correct in collectModalItems on 'saveItems'.
    });
    
    // When modal is shown, re-populate it with existing data
    $('#addItemModal').on('show.bs.modal', function() {
        $('#itemsModalTableBody').empty();
        if (customerStockItems.length === 0) {
            addItemRow(); // Start with one empty row
        } else {
            customerStockItems.forEach(item => addItemRow(item));
        }
    });

    // Save Items button in Modal
    $('#saveItems').click(function() {
        if (collectModalItems()) {
            updateAddedItemsDisplay();
            $('#addItemModal').modal('hide');
        }
    });
    
    // Handle File Input label update
    $('#document_link').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Choose file');
    });

    // --- Form Submission ---
    $('#customer-stock-form').on('submit', function(e) {
        e.preventDefault();
        
        // Final check for items
        if (customerStockItems.length === 0) {
            alert('Please add at least one item to the customer stock before submitting.');
            return;
        }
        
        const form = $(this);
        const formData = new FormData(this);
        
        // Items JSON is already set in the hidden input by updateAddedItemsDisplay()
        
        // Submit via AJAX
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.message || 'Customer Stock record created successfully! 🎉');
                window.location.href = baseUrl + '/customer-stock-list'; // Redirect after success
            },
            error: function(xhr) {
                let errorMsg = xhr.responseJSON?.message || 'An error occurred during submission.';
                if (xhr.responseJSON?.errors) {
                    // Display validation errors clearly
                    errorMsg += '\n\nValidation Errors:\n' + Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                alert(errorMsg);
            }
        });
    });

    // --- Initialization ---
    updateAddedItemsDisplay();
});