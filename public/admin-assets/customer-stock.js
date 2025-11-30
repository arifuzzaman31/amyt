$(function () {
    // Check if yarnCounts global variable is available
    // console.log('Yarn Counts:', yarnCounts);
    
    // Initialize yarnCounts with a fallback empty array
    const safeYarnCounts = Array.isArray(yarnCounts) ? yarnCounts : [];
    
    if (typeof yarnCounts === 'undefined' || !Array.isArray(yarnCounts)) {
        console.error('Yarn Counts data is missing or not an array. Using empty array as fallback.');
    }

    // --- Core Data Storage ---
    // Stores items temporarily added in the modal
    let customerStockItems = [];
    
    // --- Request Management ---
    let customerRequestTimeout = null;
    let activeCustomerRequest = null;
    const CUSTOMER_REQUEST_DELAY = 300; // ms

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
        safeYarnCounts.forEach(yc => {
            const selected = yc.id == selectedValue ? 'selected' : '';
            options += `<option value="${yc.id}" ${selected}>${yc.name}</option>`;
        });
        return options;
    }

    // 3. Initialize Date Picker
    function initializeDatePicker() {
        // Try to use Bootstrap Datepicker first
        if (typeof $.fn.datepicker === 'function') {
            $('#stock_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
            console.log('Bootstrap Datepicker initialized');
        } 
        // Fallback to HTML5 date input if datepicker is not available
        else {
            $('#stock_date').attr('type', 'date');
            // Set today's date as default
            const today = new Date().toISOString().split('T')[0];
            $('#stock_date').val(today);
            console.log('Using HTML5 date input as fallback');
        }
    }

    // 4. Add Item Row in Modal
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
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-item-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 delete-note"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>
                </td>
            </tr>
        `;
        $('#itemsModalTableBody').append(newRow);
        
        toggleRemoveButtons();
    }

    // 5. Toggle Remove Buttons
    function toggleRemoveButtons() {
        const rowCount = $('#itemsModalTableBody tr').length;
        // Disable remove button if only one row remains
        $('.remove-item-row').prop('disabled', rowCount <= 1);
    }

    // 6. Calculate and Update Totals
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

    // 7. Update Added Items Display Table (in main form)
    function updateAddedItemsDisplay() {
        let addedItemsHtml = '';
        
        if (customerStockItems.length === 0) {
            // Hide the table and show the empty state message
            $('#itemsDisplayTable').hide();
            $('#noItemsMessage').show();
            $('#totalsSection').hide();
        } else {
            customerStockItems.forEach(item => {
                // Find the name using the ID from the global array
                const yarnCountName = safeYarnCounts.find(yc => yc.id == item.yarn_count_id)?.name || 'N/A';
                const subtotal = (parseFloat(item.quantity) * parseFloat(item.unit_price)).toFixed(2);
                
                addedItemsHtml += `
                    <tr>
                        <td>${yarnCountName}</td>
                        <td>${item.quantity}</td>
                        <td>${parseFloat(item.unit_price).toFixed(2)}</td>
                        <td class="text-right">${subtotal}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-display-item" data-index="${customerStockItems.indexOf(item)}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 delete-note"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </button>
                        </td>
                    </tr>
                `;
            });
            // Show the table and hide the empty state message
            $('#itemsDisplayTable').show();
            $('#noItemsMessage').hide();
            $('#totalsSection').show();
        }
        
        // Update the main form display table
        $('#itemsDisplayTableBody').html(addedItemsHtml);
        
        // Update hidden input for form submission
        $('#item_data_input').val(JSON.stringify(customerStockItems));
        
        // Recalculate and display totals
        calculateTotals();
    }

    // 8. Collect and Validate Items from Modal
    function collectModalItems() {
        let tempItems = [];
        let isValid = true;

        $('#itemsModalTableBody tr').each(function () {
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
    // Initialize Select2 for Customer with proper pagination and request management
    $('#customer_id').select2({
        ajax: {
            url: baseUrl + "customer",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page || 1, // page number
                    limit: 20 // page size
                };
              
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                
                const options = [];
                
                // Check if data is valid before processing
                if (data && data.data && Array.isArray(data.data)) {
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
                        more: data.current_page < data.last_page,
                    },
                };
            },
            cache: true
        },
        minimumInputLength: 0,
        placeholder: 'Select a customer',
        allowClear: true
    });
    $("#customer_id").on("select2:select", function (e) {
        const data = e.params.data;
        console.log("Selected customer:", data);
    });
    // Initialize date picker with fallback
    initializeDatePicker();

    // Challan No initialization
    $('#challan_no').val(generateChallanNo());

    // Handle edit button for challan number
    $('#editChallanNo').click(function() {
        const challanInput = $('#challan_no');
        
        if (challanInput.attr('readonly')) {
            // Enable editing
            challanInput.removeAttr('readonly');
            challanInput.focus();
            $(this).html('<i class="fas fa-check"></i>');
            $(this).removeClass('btn-outline-secondary').addClass('btn-outline-success');
        } else {
            // Disable editing
            challanInput.attr('readonly', true);
            $(this).html('<i class="fas fa-edit"></i>');
            $(this).removeClass('btn-outline-success').addClass('btn-outline-secondary');
        }
    });

    // Handle file input label update
    $('#document_link').on('change', function () {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Choose file');
    });

    // Listeners for Total Calculations
    $('#discount, #discount_type').on('input change', calculateTotals);
    
    // Add Item Row button in Modal
    $('#addItemRow').click(function () {
        addItemRow();
    });

    // Remove Item Row in Modal (Delegated event listener)
    $(document).on('click', '#itemsModalTableBody .remove-item-row', function () {
        if ($('#itemsModalTableBody tr').length > 1) {
            $(this).closest('tr').remove();
            toggleRemoveButtons();
        } else {
            alert('At least one item row must remain.');
        }
    });

    // Remove Item from Display Table (Delegated event listener)
    $(document).on('click', '.remove-display-item', function () {
        const index = $(this).data('index');
        
        // Remove from our data array
        customerStockItems.splice(index, 1);
        
        // Update the display
        updateAddedItemsDisplay();
        
        // If no items left, reset the modal for next time
        if (customerStockItems.length === 0) {
            $('#itemsModalTableBody').empty();
        }
    });

    // When modal is shown, re-populate it with existing data
    $('#addItemModal').on('show.bs.modal', function () {
        $('#itemsModalTableBody').empty();
        if (customerStockItems.length === 0) {
            addItemRow(); // Start with one empty row
        } else {
            customerStockItems.forEach(item => addItemRow(item));
        }
    });

    // Save Items button in Modal
    $('#saveItems').click(function () {
        if (collectModalItems()) {
            updateAddedItemsDisplay();
            $('#addItemModal').modal('hide');
        }
    });
    
    // --- Form Submission ---
    $('#customer-stock-form').on('submit', function (e) {
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
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                alert(response.message || 'Customer Stock record created successfully! 🎉');
                window.location.href = baseUrl + 'customer-stock-list'; // Redirect after success
            },
            error: function (xhr) {
                let errorMsg = xhr.responseJSON?.message || 'An error occurred during submission.';
                if (xhr.responseJSON?.errors) {
                    // Display validation errors clearly
                    errorMsg += '\n\nValidation Errors:\n' + Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                alert(errorMsg);
            }
        });
    });

    // Reset form
    $('button[type="reset"]').click(function() {
        $('#customer_id').val(null).trigger('change');
        $('#document_link').next('.custom-file-label').html('Choose file');
        $('#challan_no').val(generateChallanNo());
        customerStockItems = [];
        updateAddedItemsDisplay();
    });

    // --- Initialization ---
    updateAddedItemsDisplay();
});