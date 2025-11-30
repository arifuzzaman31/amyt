document.addEventListener("DOMContentLoaded", function () {
    // Global variables
    let yarnCounts = [];
    let attributes = {};
    let customerYarnCounts = [];
    let serviceInfo = {
        id: null,
        customer_id: null,
        service_date: "",
        invoice_no: "",
        document_link: null,
        total_amount: 0,
        payment_status: 0,
        discount: 0,
        discount_type: 0,
        status: 0,
        description: "",
        dataItem: [],
    };

    // DOM elements
    const serviceForm = document.getElementById("service-form");
    const customerSelect = document.getElementById("customer_id");
    const openModalBtn = document.getElementById("openModalBtn");
    const addItemBtn = document.getElementById("addItemBtn");
    const saveItemsBtn = document.getElementById("saveItemsBtn");
    const itemsTableBody = document.getElementById("itemsTableBody");
    const addedItemsSection = document.getElementById("addedItemsSection");
    const noItemsSection = document.getElementById("noItemsSection");
    const addedItemsTableBody = document.getElementById("addedItemsTableBody");
    const addItemModal = document.getElementById("addItemModal");
    const serviceIdInput = document.querySelector('input[name="service_id"]');
    const invoiceNoInput = document.getElementById("invoice_no");

    // Initialize the form with existing service data
    function initializeEditForm() {
        // Get service data from the form data attributes
        const serviceData = serviceForm.dataset.service
            ? JSON.parse(serviceForm.dataset.service)
            : null;
        const serviceItems = serviceForm.dataset.serviceItems
            ? JSON.parse(serviceForm.dataset.serviceItems)
            : [];

        if (serviceData) {
            // Set service info from the passed data
            serviceInfo.id = serviceData.id;
            serviceInfo.customer_id = serviceData.customer_id;
            serviceInfo.service_date = serviceData.service_date;
            serviceInfo.invoice_no = serviceData.invoice_no;
            serviceInfo.description = serviceData.description;
            serviceInfo.payment_status = serviceData.payment_status;
            serviceInfo.discount = serviceData.discount;
            serviceInfo.discount_type = serviceData.discount_type;
            serviceInfo.status = serviceData.status;

            // Format date for input field (yyyy-MM-dd)
            if (serviceData.service_date) {
                const date = new Date(serviceData.service_date);
                const formattedDate = date.toISOString().split("T")[0];
                document.getElementById("basicFlatpickr").value = formattedDate;
            }

            // Set invoice number
            document.getElementById("invoice_no").value = serviceData.invoice_no || "";

            // Set description
            const descriptionElement = document.querySelector('textarea[name="description"]');
            if (descriptionElement) {
                descriptionElement.value = serviceData.description || "";
            }

            // Set the customer in the select2 dropdown
            if (serviceData.customer_id) {
                // Create an option for the customer
                const option = new Option(
                    serviceData.customer?.name || `Customer ${serviceData.customer_id}`,
                    serviceData.customer_id,
                    true,
                    true
                );
                $(customerSelect).append(option).trigger("change");
            }

            // Set items if they exist
            if (serviceItems && serviceItems.length > 0) {
                // Clear the existing dataItem array
                serviceInfo.dataItem = [];

                // Push each existing item to the dataItem array with ID preserved
                serviceItems.forEach((item) => {
                    serviceInfo.dataItem.push({
                        id: item.id, // This is crucial - preserve the original ID
                        yarn_count_id: item.yarn_count_id,
                        unit_attr_id: item.unit_attr_id,
                        quantity: parseFloat(item.quantity) || 0,
                        unit_price: parseFloat(item.unit_price) || 0,
                        extra_quantity: parseFloat(item.extra_quantity) || 0,
                        extra_quantity_price: parseFloat(item.extra_quantity_price) || 0,
                        color_id: item.color_id,
                        gross_weight: parseFloat(item.gross_weight) || 0,
                        net_weight: parseFloat(item.net_weight) || 0,
                        weight_attr_id: item.weight_attr_id,
                        bobin: parseInt(item.bobin) || 0,
                        remark: item.remark,
                    });
                });

                // Render the items table
                renderAddedItemsTable();
            }
        }
    }

    // Fetch yarn counts
    async function getYarnCounts() {
        try {
            const response = await fetch(baseUrl + "all-yarn-counts");
            const data = await response.json();
            yarnCounts = data.data || data;
        } catch (error) {
            console.error("Error fetching yarn counts:", error);
        }
    }

    // Fetch attributes
    async function getAttribute() {
        try {
            const response = await fetch(baseUrl + "attribute");
            const data = await response.json();
            attributes = data.reduce((acc, item) => {
                if (!acc[item.type]) {
                    acc[item.type] = [];
                }
                acc[item.type].push(item);
                return acc;
            }, {});
        } catch (error) {
            console.error("Error fetching attributes:", error);
        }
    }

    // Fetch customer's yarn counts
    async function fetchCustomersYarnCounts() {
        if (serviceInfo.customer_id) {
            try {
                const response = await fetch(
                    baseUrl + `customer/${serviceInfo.customer_id}`
                );
                const data = await response.json();
                // Update customerYarnCounts with the latest data
                customerYarnCounts = data?.customer_stock || [];
            } catch (error) {
                console.error(
                    "Error fetching yarn counts for customer:",
                    error
                );
            }
        } else {
            customerYarnCounts = [];
        }
    }

    // Get yarn quantity information
    function getYarnQuantity(item) {
        // Check if customerYarnCounts is defined and is an array
        if (!customerYarnCounts || !Array.isArray(customerYarnCounts)) {
            return "client:0,amyt:0";
        }

        const customerStock = customerYarnCounts.find(
            (yc) => yc.yarn_count_id == item.yarn_count_id
        );
        const customerQuantity = customerStock
            ? parseFloat(customerStock.quantity)
            : 0;

        // Check if yarnCounts is defined and is an array
        if (!yarnCounts || !Array.isArray(yarnCounts)) {
            return `client:${customerQuantity},amyt:0`;
        }

        const foundYarn = yarnCounts.find((yc) => yc.id == item.yarn_count_id);
        const amytStock = foundYarn?.amyt_stock?.quantity || 0;
        return `client:${customerQuantity},amyt:${amytStock}`;
    }

    // Get yarn count name by ID
    function getYarnCountName(id) {
        if (!yarnCounts || !Array.isArray(yarnCounts)) {
            return "N/A";
        }
        const found = yarnCounts.find((yc) => yc.id == id);
        return found ? found.name : "N/A";
    }

    // Get attribute name by ID and type
    function getAttrName(id, attr) {
        if (!id) {
            return "";
        }

        if (
            !attributes ||
            !attributes[attr] ||
            !Array.isArray(attributes[attr])
        ) {
            return "";
        }

        const found = attributes[attr].find((a) => a.id == id);
        return found ? found.name : "";
    }

    // Get customer stock for a specific yarn count from the customerYarnCounts array
    function getCustomerStock(yarnCountId) {
        if (!customerYarnCounts || !Array.isArray(customerYarnCounts)) {
            return 0;
        }
        const stockItem = customerYarnCounts.find(
            (item) => item.yarn_count_id == yarnCountId
        );
        return stockItem ? parseFloat(stockItem.quantity) : 0;
    }

    // Add new item row to the modal
    function addItem() {
        serviceInfo.dataItem.push({
            yarn_count_id: "",
            unit_attr_id: "",
            quantity: 0,
            unit_price: 0,
            extra_quantity: 0,
            extra_quantity_price: 0,
            color_id: "",
            gross_weight: 0,
            net_weight: 0,
            weight_attr_id: "",
            bobin: "",
            remark: "",
        });
        renderItemsTable();
    }

    // Remove item from the modal
    function removeItem(index) {
        if (serviceInfo.dataItem.length > 1) {
            serviceInfo.dataItem.splice(index, 1);
        } else {
            // Reset the first item instead of removing it
            Object.assign(serviceInfo.dataItem[0], {
                yarn_count_id: "",
                unit_attr_id: "",
                quantity: 0,
                unit_price: 0,
                extra_quantity: 0,
                extra_quantity_price: 0,
                color_id: "",
                gross_weight: 0,
                net_weight: 0,
                weight_attr_id: "",
                bobin: "",
                remark: "",
            });
        }
        renderItemsTable();
    }

    // Update extra quantity based on quantity and available stock
    function updateExtraQuantity(index, quantity) {
        const row = itemsTableBody.children[index];
        const extraQuantityInput = row.querySelector(
            'input[placeholder="Extra Quantity"]'
        );
        const yarnCountId = serviceInfo.dataItem[index].yarn_count_id;

        if (!yarnCountId) {
            return;
        }

        const availableStock = getCustomerStock(yarnCountId);
        let extraQuantity = 0;

        // Calculate extra quantity if quantity exceeds available stock
        if (quantity > availableStock) {
            extraQuantity = quantity - availableStock;
        }

        // Update the extra quantity input
        extraQuantityInput.value = extraQuantity;

        // Update the serviceInfo data
        serviceInfo.dataItem[index].extra_quantity = extraQuantity;
    }

    // Render items table in the modal
    function renderItemsTable() {
        itemsTableBody.innerHTML = "";

        // Check if yarnCounts is loaded
        if (!yarnCounts || !Array.isArray(yarnCounts)) {
            const row = document.createElement("tr");
            const cell = document.createElement("td");
            cell.colSpan = 10; // Updated to 10 columns
            cell.textContent = "Loading yarn counts...";
            cell.className = "text-center";
            row.appendChild(cell);
            itemsTableBody.appendChild(row);
            return;
        }

        serviceInfo.dataItem.forEach((item, index) => {
            const row = document.createElement("tr");

            // Add hidden input for item ID if it exists
            if (item.id) {
                const idInput = document.createElement("input");
                idInput.type = "hidden";
                idInput.className = "item-id";
                idInput.value = item.id;
                row.appendChild(idInput);
            }

            // Yarn Count column
            const yarnCountCell = document.createElement("td");
            const yarnCountSelect = document.createElement("select");
            yarnCountSelect.className = "form-control";
            yarnCountSelect.innerHTML =
                '<option value="">Select Yarn Count</option>';
            yarnCounts.forEach((yc) => {
                const option = document.createElement("option");
                option.value = yc.id;
                option.textContent = yc.name;
                if (yc.id == item.yarn_count_id) option.selected = true;
                yarnCountSelect.appendChild(option);
            });

            yarnCountSelect.addEventListener("change", function () {
                serviceInfo.dataItem[index].yarn_count_id = this.value;
                updateStockInfo(index, this.value);
                // Update extra quantity when yarn count changes
                const quantityInput = row.querySelector(
                    'input[placeholder="Quantity"]'
                );
                if (quantityInput.value) {
                    updateExtraQuantity(
                        index,
                        parseFloat(quantityInput.value) || 0
                    );
                }
            });

            yarnCountCell.appendChild(yarnCountSelect);
            row.appendChild(yarnCountCell);

            // Available Stock column
            const stockCell = document.createElement("td");
            stockCell.className = "stock-cell";
            stockCell.textContent = item.yarn_count_id ? "Loading..." : "N/A";
            row.appendChild(stockCell);

            // Color column
            const colorCell = document.createElement("td");
            const colorSelect = document.createElement("select");
            colorSelect.className = "form-control";
            colorSelect.innerHTML = '<option value="">Select Color</option>';
            if (attributes.color) {
                attributes.color.forEach((attr) => {
                    const option = document.createElement("option");
                    option.value = attr.id;
                    option.textContent = attr.name;
                    if (attr.id == item.color_id) option.selected = true;
                    colorSelect.appendChild(option);
                });
            }

            colorSelect.addEventListener("change", function () {
                serviceInfo.dataItem[index].color_id = this.value;
            });

            colorCell.appendChild(colorSelect);
            row.appendChild(colorCell);

            // Quantity column
            const quantityCell = document.createElement("td");
            const quantityInput = document.createElement("input");
            quantityInput.type = "number";
            quantityInput.className = "form-control form-control-sm";
            quantityInput.placeholder = "Quantity";
            quantityInput.value = item.quantity;
            quantityInput.addEventListener("input", function () {
                const quantity = parseFloat(this.value) || 0;
                serviceInfo.dataItem[index].quantity = quantity;
                // Update extra quantity when quantity changes
                updateExtraQuantity(index, quantity);
            });

            const unitSelect = document.createElement("select");
            unitSelect.className = "form-control form-control-sm my-1";
            unitSelect.innerHTML = '<option value="">Select Unit</option>';
            if (attributes.weight) {
                attributes.weight.forEach((attr) => {
                    const option = document.createElement("option");
                    option.value = attr.id;
                    option.textContent = attr.name;
                    if (attr.id == item.unit_attr_id) option.selected = true;
                    unitSelect.appendChild(option);
                });
            }

            unitSelect.addEventListener("change", function () {
                serviceInfo.dataItem[index].unit_attr_id = this.value;
            });

            const unitPriceInput = document.createElement("input");
            unitPriceInput.type = "number";
            unitPriceInput.className = "form-control form-control-sm";
            unitPriceInput.placeholder = "Unit Price";
            unitPriceInput.value = item.unit_price;
            unitPriceInput.addEventListener("input", function () {
                serviceInfo.dataItem[index].unit_price =
                    parseFloat(this.value) || 0;
            });

            quantityCell.appendChild(quantityInput);
            quantityCell.appendChild(unitSelect);
            quantityCell.appendChild(unitPriceInput);
            row.appendChild(quantityCell);

            // Extra Quantity column
            const extraQuantityCell = document.createElement("td");
            const extraQuantityInput = document.createElement("input");
            extraQuantityInput.type = "number";
            extraQuantityInput.className = "form-control";
            extraQuantityInput.placeholder = "Extra Quantity";
            extraQuantityInput.value = item.extra_quantity;
            extraQuantityInput.disabled = true; // Initially disabled
            extraQuantityInput.addEventListener("input", function () {
                serviceInfo.dataItem[index].extra_quantity =
                    parseFloat(this.value) || 0;
            });

            const extraQuantityPriceInput = document.createElement("input");
            extraQuantityPriceInput.type = "number";
            extraQuantityPriceInput.className =
                "form-control form-control-sm mt-1";
            extraQuantityPriceInput.placeholder = "Extra Quantity Price";
            extraQuantityPriceInput.value = item.extra_quantity_price;
            extraQuantityPriceInput.addEventListener("input", function () {
                serviceInfo.dataItem[index].extra_quantity_price =
                    parseFloat(this.value) || 0;
            });

            extraQuantityCell.appendChild(extraQuantityInput);
            extraQuantityCell.appendChild(extraQuantityPriceInput);
            row.appendChild(extraQuantityCell);

            // Gross Weight column
            const grossWeightCell = document.createElement("td");
            const grossWeightInput = document.createElement("input");
            grossWeightInput.type = "number";
            grossWeightInput.className = "form-control form-control-sm";
            grossWeightInput.placeholder = "Gross Weight";
            grossWeightInput.value = item.gross_weight;
            grossWeightInput.addEventListener("input", function () {
                serviceInfo.dataItem[index].gross_weight =
                    parseFloat(this.value) || 0;
            });

            const weightAttrSelect = document.createElement("select");
            weightAttrSelect.className = "form-control form-control-sm my-1";
            weightAttrSelect.innerHTML =
                '<option value="">Select Unit</option>';
            if (attributes.weight) {
                attributes.weight.forEach((attr) => {
                    const option = document.createElement("option");
                    option.value = attr.id;
                    option.textContent = attr.name;
                    if (attr.id == item.weight_attr_id) option.selected = true;
                    weightAttrSelect.appendChild(option);
                });
            }

            weightAttrSelect.addEventListener("change", function () {
                serviceInfo.dataItem[index].weight_attr_id = this.value;
            });

            grossWeightCell.appendChild(grossWeightInput);
            grossWeightCell.appendChild(weightAttrSelect);
            row.appendChild(grossWeightCell);

            // Net Weight column
            const netWeightCell = document.createElement("td");
            const netWeightInput = document.createElement("input");
            netWeightInput.type = "number";
            netWeightInput.className = "form-control form-control-sm";
            netWeightInput.placeholder = "Net Weight";
            netWeightInput.value = item.net_weight;
            netWeightInput.addEventListener("input", function () {
                serviceInfo.dataItem[index].net_weight =
                    parseFloat(this.value) || 0;
            });

            const netWeightAttrSelect = document.createElement("select");
            netWeightAttrSelect.className = "form-control form-control-sm my-1";
            netWeightAttrSelect.innerHTML =
                '<option value="">Select Unit</option>';
            if (attributes.weight) {
                attributes.weight.forEach((attr) => {
                    const option = document.createElement("option");
                    option.value = attr.id;
                    option.textContent = attr.name;
                    if (attr.id == item.weight_attr_id) option.selected = true;
                    netWeightAttrSelect.appendChild(option);
                });
            }

            netWeightAttrSelect.addEventListener("change", function () {
                serviceInfo.dataItem[index].weight_attr_id = this.value;
            });

            netWeightCell.appendChild(netWeightInput);
            netWeightCell.appendChild(netWeightAttrSelect);
            row.appendChild(netWeightCell);

            // Bobin column
            const bobinCell = document.createElement("td");
            const bobinInput = document.createElement("input");
            bobinInput.type = "number";
            bobinInput.className = "form-control";
            bobinInput.placeholder = "Bobin";
            bobinInput.value = item.bobin;
            bobinInput.addEventListener("input", function () {
                serviceInfo.dataItem[index].bobin = parseFloat(this.value) || 0;
            });

            bobinCell.appendChild(bobinInput);
            row.appendChild(bobinCell);

            // Remark column
            const remarkCell = document.createElement("td");
            const remarkInput = document.createElement("input");
            remarkInput.type = "text";
            remarkInput.className = "form-control";
            remarkInput.placeholder = "Remark";
            remarkInput.value = item.remark;
            remarkInput.addEventListener("input", function () {
                serviceInfo.dataItem[index].remark = this.value;
            });

            remarkCell.appendChild(remarkInput);
            row.appendChild(remarkCell);

            // Action column
            const actionCell = document.createElement("td");
            const deleteButton = document.createElement("button");
            deleteButton.type = "button";
            deleteButton.className = "btn btn-danger btn-sm";
            deleteButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            `;
            deleteButton.addEventListener("click", function () {
                removeItem(index);
            });

            actionCell.appendChild(deleteButton);
            row.appendChild(actionCell);

            itemsTableBody.appendChild(row);

            // If we have a yarn count selected, update the stock info
            if (item.yarn_count_id) {
                updateStockInfo(index, item.yarn_count_id);
                // Also update extra quantity based on current quantity
                if (item.quantity > 0) {
                    updateExtraQuantity(index, item.quantity);
                }
            }
        });
    }

    // Update stock information for a specific row
    function updateStockInfo(index, yarnCountId) {
        const row = itemsTableBody.children[index];
        const stockCell = row.querySelector(".stock-cell");
        const extraQuantityInput = row.querySelector(
            'input[placeholder="Extra Quantity"]'
        );

        if (!yarnCountId || !serviceInfo.customer_id) {
            stockCell.textContent = "N/A";
            extraQuantityInput.disabled = true;
            return;
        }

        // Get customer stock from the customerYarnCounts array
        const stock = getCustomerStock(yarnCountId);
        stockCell.textContent = stock;

        // Enable extra quantity if stock is 0 or null
        if (stock <= 0) {
            extraQuantityInput.disabled = false;
            extraQuantityInput.classList.remove("bg-light");
        } else {
            extraQuantityInput.disabled = true;
            extraQuantityInput.classList.add("bg-light");
        }
    }

    // Open modal
    function openModal() {
        // get customer id
        const customerId = $(customerSelect).val();
        if (!customerId) {
            alert("Please select a customer first.");
            return;
        }
        
        // If there are no items, add one empty item
        if (serviceInfo.dataItem.length === 0) {
            addItem();
        }
        
        renderItemsTable();
        $("#addItemModal").modal("show");
    }

    // Close modal
    function closeModal() {
        $("#addItemModal").modal("hide");
    }

    // Save items and close modal
    function saveItems() {
        // Ensure all modal data is saved to serviceInfo.dataItem
        const rows = itemsTableBody.querySelectorAll("tr");
        rows.forEach((row, index) => {
            if (index < serviceInfo.dataItem.length) {
                // Update existing item
                const item = serviceInfo.dataItem[index];

                // Get the ID from the hidden input if it exists
                const idInput = row.querySelector(".item-id");
                if (idInput) {
                    item.id = idInput.value;
                }

                // Get values from the row
                const yarnCountSelect = row.querySelector("select");
                const colorSelect = row.querySelectorAll("select")[1];
                const quantityInput = row.querySelector('input[type="number"]');
                const unitSelect = row.querySelectorAll("select")[2];
                const unitPriceInput = row.querySelectorAll(
                    'input[type="number"]'
                )[1];
                const extraQuantityInput = row.querySelectorAll(
                    'input[type="number"]'
                )[2];
                const extraQuantityPriceInput = row.querySelectorAll(
                    'input[type="number"]'
                )[3];
                const grossWeightInput = row.querySelectorAll(
                    'input[type="number"]'
                )[4];
                const weightAttrSelect = row.querySelectorAll("select")[3];
                const netWeightInput = row.querySelectorAll(
                    'input[type="number"]'
                )[5];
                const netWeightAttrSelect = row.querySelectorAll("select")[4];
                const bobinInput = row.querySelectorAll(
                    'input[type="number"]'
                )[6];
                const remarkInput = row.querySelector('input[type="text"]');

                // Update the item object - convert empty strings to null for integer fields
                item.yarn_count_id = yarnCountSelect
                    ? yarnCountSelect.value || null
                    : null;
                item.color_id = colorSelect ? colorSelect.value || null : null;
                item.quantity = quantityInput
                    ? parseFloat(quantityInput.value) || 0
                    : 0;
                item.unit_attr_id = unitSelect
                    ? unitSelect.value || null
                    : null;
                item.unit_price = unitPriceInput
                    ? parseFloat(unitPriceInput.value) || 0
                    : 0;
                item.extra_quantity = extraQuantityInput
                    ? parseFloat(extraQuantityInput.value) || 0
                    : 0;
                item.extra_quantity_price = extraQuantityPriceInput
                    ? parseFloat(extraQuantityPriceInput.value) || 0
                    : 0;
                item.gross_weight = grossWeightInput
                    ? parseFloat(grossWeightInput.value) || 0
                    : 0;
                item.weight_attr_id = weightAttrSelect
                    ? weightAttrSelect.value || null
                    : null;
                item.net_weight = netWeightInput
                    ? parseFloat(netWeightInput.value) || 0
                    : 0;
                item.bobin = bobinInput
                    ? bobinInput.value === ""
                        ? null
                        : parseFloat(bobinInput.value) || 0
                    : null;
                item.remark = remarkInput ? remarkInput.value || null : null;
            }
        });

        // Now render the updated items
        renderAddedItemsTable();
        $("#addItemModal").modal("hide");
    }

    // Render added items table
    function renderAddedItemsTable() {
        const hasItems = serviceInfo.dataItem.some(
            (item) => item.yarn_count_id
        );
        if (hasItems) {
            addedItemsSection.style.display = "block";
            noItemsSection.style.display = "none";
            addedItemsTableBody.innerHTML = "";

            // Check if yarnCounts is loaded
            if (!yarnCounts || !Array.isArray(yarnCounts)) {
                const row = document.createElement("tr");
                const cell = document.createElement("td");
                cell.colSpan = 8;
                cell.textContent = "Loading yarn counts...";
                cell.className = "text-center";
                row.appendChild(cell);
                addedItemsTableBody.appendChild(row);
                return;
            }

            // Calculate totals
            let totalQuantity = 0;
            let totalExtraQuantity = 0;
            let totalGrossWeight = 0;
            let totalNetWeight = 0;
            let totalBobin = 0;

            // Track units for display
            let unitAttr = "";
            let weightAttr = "";

            serviceInfo.dataItem.forEach((item) => {
                if (item.yarn_count_id) {
                    const row = document.createElement("tr");

                    // Yarn Count
                    const yarnCountCell = document.createElement("td");
                    yarnCountCell.textContent = getYarnCountName(
                        item.yarn_count_id
                    );
                    row.appendChild(yarnCountCell);

                    // Color - Fixed to handle missing attributes
                    const colorCell = document.createElement("td");
                    let colorName = getAttrName(item.color_id, "color");
                    // If color is "N/A" or empty, show a placeholder
                    colorCell.textContent =
                        colorName && colorName !== "N/A"
                            ? colorName
                            : "No Color";
                    row.appendChild(colorCell);

                    // Quantity - Fixed to properly format with unit
                    const quantityCell = document.createElement("td");
                    const quantityValue = parseFloat(item.quantity) || 0;
                    const itemUnitAttr = getAttrName(
                        item.unit_attr_id,
                        "weight"
                    );
                    // Only show unit if it's valid
                    const unitText =
                        itemUnitAttr && itemUnitAttr !== "N/A"
                            ? itemUnitAttr
                            : "";
                    quantityCell.textContent = `${quantityValue.toFixed(
                        2
                    )} ${unitText}`.trim();
                    row.appendChild(quantityCell);

                    // Extra Quantity
                    const extraQuantityCell = document.createElement("td");
                    const extraQuantityValue =
                        parseFloat(item.extra_quantity) || 0;
                    extraQuantityCell.textContent =
                        extraQuantityValue.toFixed(2);
                    row.appendChild(extraQuantityCell);

                    // Gross Weight
                    const grossWeightCell = document.createElement("td");
                    const grossWeightValue = parseFloat(item.gross_weight) || 0;
                    const itemWeightAttr = getAttrName(
                        item.weight_attr_id,
                        "weight"
                    );
                    // Only show unit if it's valid
                    const weightText =
                        itemWeightAttr && itemWeightAttr !== "N/A"
                            ? itemWeightAttr
                            : "";
                    grossWeightCell.textContent = `${grossWeightValue.toFixed(
                        2
                    )} ${weightText}`.trim();
                    row.appendChild(grossWeightCell);

                    // Net Weight
                    const netWeightCell = document.createElement("td");
                    const netWeightValue = parseFloat(item.net_weight) || 0;
                    netWeightCell.textContent = `${netWeightValue.toFixed(
                        2
                    )} ${weightText}`.trim();
                    row.appendChild(netWeightCell);

                    // Bobin
                    const bobinCell = document.createElement("td");
                    const bobinValue = parseInt(item.bobin) || 0;
                    bobinCell.textContent = bobinValue;
                    row.appendChild(bobinCell);

                    // Remark
                    const remarkCell = document.createElement("td");
                    remarkCell.className = "text-wrap";
                    remarkCell.textContent = item.remark || "";
                    row.appendChild(remarkCell);

                    addedItemsTableBody.appendChild(row);

                    // Update totals
                    totalQuantity += quantityValue;
                    totalExtraQuantity += extraQuantityValue;
                    totalGrossWeight += grossWeightValue;
                    totalNetWeight += netWeightValue;
                    totalBobin += bobinValue;

                    // Track units for display (use first item with valid units)
                    if (!unitAttr && unitText) {
                        unitAttr = unitText;
                    }
                    if (!weightAttr && weightText) {
                        weightAttr = weightText;
                    }
                }
            });

            // Update total cells with proper formatting
            document.getElementById(
                "totalQuantityCell"
            ).textContent = `${totalQuantity.toFixed(2)} ${unitAttr}`;
            document.getElementById("totalExtraQuantityCell").textContent =
                totalExtraQuantity.toFixed(2);
            document.getElementById(
                "totalGrossWeightCell"
            ).textContent = `${totalGrossWeight.toFixed(2)} ${weightAttr}`;
            document.getElementById(
                "totalNetWeightCell"
            ).textContent = `${totalNetWeight.toFixed(2)} ${weightAttr}`;
            document.getElementById("totalBobinCell").textContent = totalBobin;
        } else {
            addedItemsSection.style.display = "none";
            noItemsSection.style.display = "block";
        }
    }

    // Handle form submission
    async function submitForm(e) {
        e.preventDefault();

        // Get references to hidden fields
        const dataItemField = document.getElementById("dataItem");
        const totalAmountField = document.getElementById("total_amount");
        const paymentStatusField = document.getElementById("payment_status");
        const discountField = document.getElementById("discount");
        const discountTypeField = document.getElementById("discount_type");
        const statusField = document.getElementById("status");

        // Verify the fields exist
        if (!dataItemField) {
            console.error("dataItem field not found in the form");
            alert("Form configuration error: dataItem field missing");
            return;
        }

        // Set the values
        const dataItemJson = JSON.stringify(serviceInfo.dataItem);
        dataItemField.value = dataItemJson;
        totalAmountField.value = serviceInfo.total_amount;
        paymentStatusField.value = serviceInfo.payment_status;
        discountField.value = serviceInfo.discount;
        discountTypeField.value = serviceInfo.discount_type;
        statusField.value = serviceInfo.status;

        // Create FormData object
        const formData = new FormData(serviceForm);

        // Manually append dataItem if it's not in the FormData
        if (!formData.has("dataItem")) {
            formData.append("dataItem", dataItemJson);
        }

        try {
            // Use the form's action URL
            const url = serviceForm.action;
            const response = await fetch(url, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            });

            const data = await response.json();

            if (response.ok) {
                notifier({
                    status: "success",
                    message: data.message || "Form submitted successfully",
                });
                window.location.href = baseUrl + "challan-list";
            } else {
                alert(data.message || "An error occurred");
            }
        } catch (error) {
            console.error("Error submitting form:", error);
            alert("An error occurred while submitting the form");
        }
    }

    // Event listeners
    openModalBtn.addEventListener("click", openModal);
    addItemBtn.addEventListener("click", addItem);
    saveItemsBtn.addEventListener("click", saveItems);
    serviceForm.addEventListener("submit", submitForm);

    // Handle invoice input change
    if (invoiceNoInput) {
        invoiceNoInput.addEventListener("input", function () {
            serviceInfo.invoice_no = this.value;
        });
    }

    // Handle document upload
    document
        .getElementById("document_link")
        .addEventListener("change", function (e) {
            serviceInfo.document_link = e.target.files[0] || null;
        });

    // Initialize
    async function initialize() {
        // Load data first
        await Promise.all([getYarnCounts(), getAttribute()]);
        
        // Initialize the form with existing data
        initializeEditForm();
    }

    initialize();
});

// Initialize Select2 separately to avoid conflicts
 $(function () {
    $("#customer_id").select2({
        ajax: {
            url: baseUrl + "customer",
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
                            customer: val,
                            customer_stock: val.customer_stock || [], // Include customer_stock in the option data
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
        placeholder: "Select a customer",
        allowClear: true,
    });

    // Handle customer selection
    $("#customer_id").on("select2:select", function (e) {
        const data = e.params.data;
        // Store customer stock from the selected option
        customerYarnCounts = data.customer_stock || [];
    });
});