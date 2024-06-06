const productNameInput = document.getElementById('product-name');
const imageInput = document.getElementById('image-name');
const quantityInput = document.getElementById('quantity');
const priceInput = document.getElementById('price');
const inactiveCheckbox = document.getElementById('inactive');
const productID = document.getElementById('product-id')
function highlight_row() {
    var table = document.getElementById('product-table');
    var productName = document.getElementById('product-name');
    var imageName = document.getElementById('image-name');
    var quantity = document.getElementById('quantity');
    var price = document.getElementById('price');
    var inactive = document.getElementById('inactive');
    var cells = table.getElementsByTagName('td');
    for (var i = 0; i < cells.length; i++) {
        // Take each cell
        var cell = cells[i];
        // do something on onclick event for cell
        cell.onclick = function () {
            // Get the row id where the cell exists
            var rowId = this.parentNode.rowIndex;

            var rowsNotSelected = table.getElementsByTagName('tr');
            for (var row = 0; row < rowsNotSelected.length; row++) {
                rowsNotSelected[row].style.backgroundColor = "";
            }
            var rowSelected = table.getElementsByTagName('tr')[rowId];
            rowSelected.style.backgroundColor = "yellow";

            productID.value = rowSelected.cells[0].innerHTML;
            productNameInput.value = rowSelected.cells[1].innerHTML;
            imageInput.value = rowSelected.cells[2].innerHTML;
            quantityInput.value = rowSelected.cells[3].innerHTML;
            priceInput.value = rowSelected.cells[4].innerHTML;
            inactive.value = rowSelected.cells[1].innerHTML;
            if (rowSelected.cells[5].innerHTML === 'Yes') {
                inactiveCheckbox.checked = true;
            } else {
                inactiveCheckbox.checked = false;
            }

        }
    }

}

$(document).ready(function () {
    highlight_row();
    $("#add-product-btn").click(function (e) {
        e.preventDefault();
        if (validateFields()) {
            var postData = $('form').serializeArray();
            postData.push({ name: "action", value: "create" });
            $.ajax({
                type: "POST",
                url: "Unit6_ajaxProduct.php",
                data: postData,
                cache: false,
                success: function (result) {
                    location.reload();
                }
            });
        }
    });
    $("#update-btn").click(function (e) {
        e.preventDefault();
        if (validateFields()) {
            var postData = $('form').serializeArray();
            postData.push({ name: "action", value: "update" });
            $.ajax({
                type: "POST",
                url: "Unit6_ajaxProduct.php",
                data: postData,
                cache: false,
                success: function (result) {
                    location.reload();
                }
            });
        }
    });
    $("#delete-btn").click(function (e) {
        e.preventDefault();
        if (validateFields()) {
            var postData = $('form').serializeArray();
            postData.push({ name: "action", value: "check orders" });
            $.ajax({
                type: "POST",
                url: "Unit6_ajaxProduct.php",
                data: postData,
                success: function (orderResult) {
                    if (orderResult === "true") {
                        alert("Cannot delete the product. There are existing orders.");
                    } else {
                        var confirmation = confirm("Are you sure you want to delete this product?");
                        if (confirmation) {
                            var postData = $('form').serializeArray();
                            postData.push({ name: "action", value: "delete" });

                            $.ajax({
                                type: "POST",
                                url: "Unit6_ajaxProduct.php",
                                data: postData,
                                cache: false,
                                success: function (result) {
                                    location.reload();
                                }
                            });
                        }
                    }
                }
            });
        }
    });

    function validateFields() {
        var productNameField = $("#product-name");
        var imageNameField = $("#image-name");
        var priceField = $("#price");
        var errorMessage = "";

        if (productNameField.val().trim() == "") {
            errorMessage = "Product name is required.";
            productNameField.focus();
        } else if (imageNameField.val().trim() == "") {
            errorMessage = "Image name is required.";
            imageNameField.focus();
        } else if (priceField.val().trim() == "") {
            errorMessage = "Price is required.";
            priceField.focus();
        } else {
            errorMessage = "";
        }

        $("#form-error-message").html(errorMessage);
        return errorMessage === "";
    }
});