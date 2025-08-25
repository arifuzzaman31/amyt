$(function () {
    // Initialize Select2 with AJAX for infinite scrolling
    $("#supplier_id").select2({
        ajax: {
            url: baseUrl + "supplier",
            dataType: "json",
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1,
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                const options = [];

                if (Array.isArray(data.data) && data.data.length > 0) {
                    data.data.forEach((val) => {
                        options.push({
                            id: val.id,
                            text: val.name,
                            supplier: val,
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
            cache: true,
        },
        minimumInputLength: 0,
        placeholder: "Select a supplier",
        allowClear: true,
    });

    // Handle supplier selection
    $("#supplier_id").on("select2:select", function (e) {
        const data = e.params.data;
        console.log("Selected supplier:", data);
    });

    // Initialize date picker
    $("#purchase_date").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true,
    });

    // Modal handling
    $("#addItemModal").on("show.bs.modal", function () {
        resetItemForm();
    });

    // Add new item row
    $("#addItemRow").click(function () {
        const newRow = `
            <tr>
                <td>
                    <select class="form-control yarn-count-select">
                        <option value="">Select Yarn Count</option>
                        ${yarnCounts
                            .map(
                                (yc) =>
                                    `<option value="${yc.id}">${yc.name}</option>`
                            )
                            .join("")}
                    </select>
                </td>
                <td><input type="number" class="form-control quantity-input" step="0.01" placeholder="Quantity"></td>
                <td><input type="number" class="form-control unit-price-input" step="0.01" placeholder="Unit Price"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
            </tr>
        `;
        $("#itemsTable tbody").append(newRow);
    });

    // Remove item row
    $(document).on("click", ".remove-item", function () {
        if ($("#itemsTable tbody tr").length > 1) {
            $(this).closest("tr").remove();
            updateTotals();
        } else {
            // Clear the row instead of removing if it's the last one
            const row = $(this).closest("tr");
            row.find("select").val("");
            row.find("input").val("");
            updateTotals();
        }
    });

    // Update totals when quantity or unit price changes
    $(document).on("input", ".quantity-input, .unit-price-input", function () {
        updateTotals();
    });

    // Calculate and update totals
    function updateTotals() {
        let subtotal = 0;
        let hasItems = false;

        $("#itemsTable tbody tr").each(function () {
            const yarnCountId = $(this).find(".yarn-count-select").val();
            const quantity =
                parseFloat($(this).find(".quantity-input").val()) || 0;
            const unitPrice =
                parseFloat($(this).find(".unit-price-input").val()) || 0;

            if (yarnCountId) {
                hasItems = true;
                subtotal += quantity * unitPrice;
            }
        });

        // Show/hide sections based on whether there are items
        if (hasItems) {
            $("#addedItemsSection").show();
            $("#noItemsSection").hide();
            $("#totalsSection").show();

            // Update added items table
            const addedItemsHtml = $("#itemsTable tbody tr")
                .map(function () {
                    const yarnCountId = $(this)
                        .find(".yarn-count-select")
                        .val();
                    if (!yarnCountId) return "";

                    const yarnCountName = $(this)
                        .find(".yarn-count-select option:selected")
                        .text();
                    const quantity =
                        parseFloat($(this).find(".quantity-input").val()) || 0;
                    const unitPrice =
                        parseFloat($(this).find(".unit-price-input").val()) ||
                        0;
                    const subtotal = quantity * unitPrice;

                    return `
                    <tr>
                        <td>${yarnCountName}</td>
                        <td>${quantity}</td>
                        <td>${unitPrice.toFixed(2)}</td>
                        <td>${subtotal.toFixed(2)}</td>
                    </tr>
                `;
                })
                .get()
                .join("");

            $("#addedItemsTable").html(addedItemsHtml);
        } else {
            $("#addedItemsSection").hide();
            $("#noItemsSection").show();
            $("#totalsSection").hide();
        }

        $("#subtotal").val(subtotal.toFixed(2));

        const discount = parseFloat($("#discount").val()) || 0;
        const discountType = $("#discount_type").val();
        let discountAmount = 0;

        if (discountType === "0") {
            // Percentage
            discountAmount = (subtotal * discount) / 100;
        } else if (discountType === "1") {
            // Fixed
            discountAmount = discount;
        }

        $("#discount_amount_display").val(discountAmount.toFixed(2));
        $("#grand_total_display").val((subtotal - discountAmount).toFixed(2));
    }

    // Reset item form
    function resetItemForm() {
        $("#itemsTable tbody").empty();
        $("#addItemRow").click(); // Add one empty row
        updateTotals();
    }

    // Form submission
    $("#purchase-form").on("submit", function (e) {
        e.preventDefault();

        // Collect form data
        const formData = new FormData(this);

        // Collect items data
        const items = [];
        $("#itemsTable tbody tr").each(function () {
            const yarnCountId = $(this).find(".yarn-count-select").val();
            const quantity = $(this).find(".quantity-input").val();
            const unitPrice = $(this).find(".unit-price-input").val();

            if (yarnCountId && quantity && unitPrice) {
                items.push({
                    yarn_count_id: yarnCountId,
                    quantity: quantity,
                    unit_price: unitPrice,
                });
            }
        });

        // Add items to form data
        formData.append("dataItem", JSON.stringify(items));
        let action = baseUrl + "purchase";
        console.log(action);
        // return;
        // Submit via AJAX
        $.ajax({
            url: action,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            enctype: "multipart/form-data",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                // alert(response.message);
                if (response.statusCode == 201) {
                    notifier({ status: "success", message: response.message });
                }
                // console.log(response);
                window.location.href = baseUrl + "purchase-list";
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || "An error occurred");
            },
        });
    });

    // Initialize with one empty row
    resetItemForm();

    // Debounce function to limit how often a function can be called
    function debounce(func, wait) {
        let timeout;
        return function () {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }

    // Create debounced version of updateTotals
    const debouncedUpdateTotals = debounce(updateTotals, 300);

    // Handle discount changes with debouncing and stop propagation
    $("#discount_type, #discount").on("input change", function (e) {
        e.stopPropagation(); // Stop event propagation
        console.log("Discount or type changed");
        debouncedUpdateTotals(); // Use debounced version
    });

    function updateTotals() {
        let subtotal = 0;
        let hasItems = false;

        $("#itemsTable tbody tr").each(function () {
            const yarnCountId = $(this).find(".yarn-count-select").val();
            const quantity =
                parseFloat($(this).find(".quantity-input").val()) || 0;
            const unitPrice =
                parseFloat($(this).find(".unit-price-input").val()) || 0;

            if (yarnCountId) {
                hasItems = true;
                subtotal += quantity * unitPrice;
            }
        });

        // Show/hide sections based on whether there are items
        if (hasItems) {
            $("#addedItemsSection").show();
            $("#noItemsSection").hide();
            $("#totalsSection").show();

            // Update added items table
            const addedItemsHtml = $("#itemsTable tbody tr")
                .map(function () {
                    const yarnCountId = $(this)
                        .find(".yarn-count-select")
                        .val();
                    if (!yarnCountId) return "";

                    const yarnCountName = $(this)
                        .find(".yarn-count-select option:selected")
                        .text();
                    const quantity =
                        parseFloat($(this).find(".quantity-input").val()) || 0;
                    const unitPrice =
                        parseFloat($(this).find(".unit-price-input").val()) ||
                        0;
                    const itemSubtotal = quantity * unitPrice; // Renamed to avoid conflict

                    return `
                <tr>
                    <td>${yarnCountName}</td>
                    <td>${quantity}</td>
                    <td>${unitPrice.toFixed(2)}</td>
                    <td>${itemSubtotal.toFixed(2)}</td>
                </tr>
            `;
                })
                .get()
                .join("");

            $("#addedItemsTable").html(addedItemsHtml);
        } else {
            $("#addedItemsSection").hide();
            $("#noItemsSection").show();
            $("#totalsSection").hide();
        }

        $("#subtotal").val(subtotal.toFixed(2));

        const discount = parseFloat($("#discount").val()) || 0;
        const discountType = $("#discount_type").val();
        let discountAmount = 0;

        if (discountType === "0") {
            // Percentage
            discountAmount = (subtotal * discount) / 100;
        } else if (discountType === "1") {
            // Fixed
            discountAmount = discount;
        }

        $("#discount_amount_display").val(discountAmount.toFixed(2));
        $("#grand_total_display").val((subtotal - discountAmount).toFixed(2));
    }

    // Handle item changes with immediate updates
    $("#itemsTable").on(
        "input change",
        ".yarn-count-select, .quantity-input, .unit-price-input",
        function (e) {
            e.stopPropagation(); // Stop event propagation
            updateTotals(); // Immediate update for item changes
        }
    );

    // Handle item removal with immediate updates
    $("#itemsTable").on("click", ".remove-item", function (e) {
        e.stopPropagation(); // Stop event propagation
        $(this).closest("tr").remove();
        updateTotals(); // Immediate update after removal
    });
});
