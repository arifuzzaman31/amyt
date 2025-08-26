document.addEventListener("DOMContentLoaded", function () {
    // Global variables
    let yarnCounts = [];
    let attributes = {};
    let customerYarnCounts = [];
    let serviceInfo = {
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
        dataItem: [
            {
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
            },
        ],
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
    // Updated to directly use document.getElementById in submitForm

    // Initialize Select2 for customer selection
    function initializeCustomerSelect() {
        $(customerSelect).select2({
            ajax: {
                url: baseUrl + "customer",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        page: params.page || 1,
                        limit: 20,
                        search: params.term || "",
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    const options = [];
                    if (Array.isArray(data.data) && data.data.length > 0) {
                        data.data.forEach((val) => {
                            options.push({
                                id: val.id,
                                text: `${
                                    val.name + "-" + val.customer_group?.name
                                }`,
                                product: val,
                            });
                        });
                    }
                    return {
                        results: options,
                        pagination: {
                            more:
                                data.current_page >= data.last_page
                                    ? false
                                    : true,
                        },
                    };
                },
                cache: true,
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 0,
            templateResult: formatProduct,
            templateSelection: formatProductSelection,
        });

        // Handle customer selection
        $(customerSelect).on("select2:select", function (e) {
            serviceInfo.customer_id = e.params.data.id;
            fetchCustomersYarnCounts();
        });

        $(customerSelect).on("select2:unselect", function () {
            serviceInfo.customer_id = null;
            customerYarnCounts = [];
        });
    }

    function formatProduct(product) {
        if (product.loading) {
            return product.text;
        }
        return product.text;
    }

    function formatProductSelection(product) {
        return product.text || product.id;
    }

    // Fetch yarn counts
    async function getYarnCounts() {
        try {
            const response = await fetch(
                baseUrl + "yarn-count?isPaginate=no&relation[]=amytStock"
            );
            const data = await response.json();
            yarnCounts = data;
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
                customerYarnCounts = data.data?.customer_stock || [];
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

        const yarnCount = customerYarnCounts.find(
            (yc) => yc.yarn_count_id == item.yarn_count_id
        );

        // Check if yarnCounts is defined and is an array
        if (!yarnCounts || !Array.isArray(yarnCounts)) {
            return `client:${yarnCount?.quantity || 0},amyt:0`;
        }

        const foundYarn = yarnCounts.find((yc) => yc.id == item.yarn_count_id);
        const amytStock = foundYarn?.amyt_stock?.quantity || 0;
        return `client:${yarnCount?.quantity || 0},amyt:${amytStock}`;
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
        if (
            !attributes ||
            !attributes[attr] ||
            !Array.isArray(attributes[attr])
        ) {
            return "N/A";
        }
        const found = attributes[attr].find((a) => a.id == id);
        return found ? found.name : "N/A";
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

    // Render items table in the modal
    function renderItemsTable() {
        itemsTableBody.innerHTML = "";
        // Check if yarnCounts is loaded
        if (!yarnCounts || !Array.isArray(yarnCounts)) {
            const row = document.createElement("tr");
            const cell = document.createElement("td");
            cell.colSpan = 9;
            cell.textContent = "Loading yarn counts...";
            cell.className = "text-center";
            row.appendChild(cell);
            itemsTableBody.appendChild(row);
            return;
        }
        serviceInfo.dataItem.forEach((item, index) => {
            const row = document.createElement("tr");

            // Yarn Count column
            const yarnCountCell = document.createElement("td");
            const yarnQuantityInfo = document.createElement("small");
            yarnQuantityInfo.style.fontSize = "12px";
            yarnQuantityInfo.style.color = "#555";
            yarnQuantityInfo.textContent = getYarnQuantity(item);

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
            });

            yarnCountCell.appendChild(yarnQuantityInfo);
            yarnCountCell.appendChild(yarnCountSelect);
            row.appendChild(yarnCountCell);

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
                serviceInfo.dataItem[index].quantity =
                    parseFloat(this.value) || 0;
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
        });
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

            serviceInfo.dataItem.forEach((item) => {
                if (item.yarn_count_id) {
                    const row = document.createElement("tr");

                    // Yarn Count
                    const yarnCountCell = document.createElement("td");
                    yarnCountCell.textContent = getYarnCountName(
                        item.yarn_count_id
                    );
                    row.appendChild(yarnCountCell);

                    // Color
                    const colorCell = document.createElement("td");
                    colorCell.textContent = getAttrName(item.color_id, "color");
                    row.appendChild(colorCell);

                    // Quantity
                    const quantityCell = document.createElement("td");
                    quantityCell.textContent = `${
                        item.quantity || 0
                    } ${getAttrName(item.unit_attr_id, "weight")}`;
                    row.appendChild(quantityCell);

                    // Extra Quantity
                    const extraQuantityCell = document.createElement("td");
                    extraQuantityCell.textContent = item.extra_quantity || 0;
                    row.appendChild(extraQuantityCell);

                    // Gross Weight
                    const grossWeightCell = document.createElement("td");
                    grossWeightCell.textContent = `${
                        item.gross_weight || 0
                    } ${getAttrName(item.weight_attr_id, "weight")}`;
                    row.appendChild(grossWeightCell);

                    // Net Weight
                    const netWeightCell = document.createElement("td");
                    netWeightCell.textContent = `${
                        item.net_weight || 0
                    } ${getAttrName(item.weight_attr_id, "weight")}`;
                    row.appendChild(netWeightCell);

                    // Bobin
                    const bobinCell = document.createElement("td");
                    bobinCell.textContent = item.bobin || 0;
                    row.appendChild(bobinCell);

                    // Remark
                    const remarkCell = document.createElement("td");
                    remarkCell.className = "text-wrap";
                    remarkCell.textContent = item.remark || "";
                    row.appendChild(remarkCell);

                    addedItemsTableBody.appendChild(row);

                    // Update totals
                    totalQuantity += item.quantity || 0;
                    totalExtraQuantity += item.extra_quantity || 0;
                    totalGrossWeight += item.gross_weight || 0;
                    totalNetWeight += item.net_weight || 0;
                    totalBobin += item.bobin || 0;
                }
            });

            // Update total cells
            const firstItem = serviceInfo.dataItem.find(
                (item) => item.yarn_count_id
            );
            const unitAttr = firstItem
                ? getAttrName(firstItem.unit_attr_id, "weight")
                : "";
            const weightAttr = firstItem
                ? getAttrName(firstItem.weight_attr_id, "weight")
                : "";

            document.getElementById(
                "totalQuantityCell"
            ).textContent = `${totalQuantity} ${unitAttr}`;
            document.getElementById("totalExtraQuantityCell").textContent =
                totalExtraQuantity;
            document.getElementById(
                "totalGrossWeightCell"
            ).textContent = `${totalGrossWeight} ${weightAttr}`;
            document.getElementById(
                "totalNetWeightCell"
            ).textContent = `${totalNetWeight} ${weightAttr}`;
            document.getElementById("totalBobinCell").textContent = totalBobin;
        } else {
            addedItemsSection.style.display = "none";
            noItemsSection.style.display = "block";
        }
    }

    // Open modal
    function openModal() {
        renderItemsTable();
        $("#addItemModal").modal("show");
    }

    // Close modal
    function closeModal() {
        const newLocal = "#addItemModal";
        $(newLocal).modal("hide");
    }

    // Save items and close modal
    function saveItems() {
        renderAddedItemsTable();
        $("#addItemModal").modal("hide");
    }

    // Initialize modal
    function initializeModal() {
        // Handle modal close events
        $("#addItemModal").on("hidden.bs.modal", function () {
            // Any cleanup needed when modal is closed
        });
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
        if (!formData.has('dataItem')) {
            formData.append('dataItem', dataItemJson);
        }
        
        try {
            const response = await fetch(baseUrl + "service", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            });
            
            const data = await response.json();
            // console.log("Response:", data);
            
            if (response.ok) {
                // alert(data.message);
                notifier({ status: "success", message: data.message || "Form submitted successfully" });
                resetForm();
                window.location.href = baseUrl + "challan-list";
            } else {
                alert(data.message || "An error occurred");
            }
        } catch (error) {
            console.error("Error submitting form:", error);
            alert("An error occurred while submitting the form");
        }
    }

    // Reset form
    function resetForm() {
        serviceInfo.dataItem = [
            {
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
            },
        ];
        serviceInfo.customer_id = null;
        serviceInfo.service_date = "";
        serviceInfo.invoice_no = "";
        serviceInfo.document_link = null;
        serviceInfo.total_amount = 0;
        serviceInfo.payment_status = 0;
        serviceInfo.discount = 0;
        serviceInfo.discount_type = 0;
        serviceInfo.status = 0;
        serviceInfo.description = "";

        // Reset form fields
        serviceForm.reset();
        $(customerSelect).val(null).trigger("change");

        // Reset UI
        addedItemsSection.style.display = "none";
        noItemsSection.style.display = "block";
        closeModal();
    }

    // Event listeners
    openModalBtn.addEventListener("click", openModal);
    addItemBtn.addEventListener("click", addItem);
    saveItemsBtn.addEventListener("click", saveItems);
    // modalBackdrop.addEventListener("click", closeModal);
    serviceForm.addEventListener("submit", submitForm);

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

        // Then initialize components
        initializeCustomerSelect();
        initializeModal();
        renderAddedItemsTable();
    }

    initialize();
});
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
        console.log("Selected customer:", data);
    });
});
