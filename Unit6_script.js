const productSelect = document.getElementById("product");
const stockField = document.getElementById("available");
// Handle select change
productSelect.addEventListener("change", showStock);

function showStock() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            stock = this.responseText;
            // if (stock == 0) {
            // 	stock = "ERROR";
            // }
            stockField.value = stock;
        }
    };
    xmlhttp.open("GET", "Unit6_get_quantity.php?product_id=" + product.value, true);
    xmlhttp.send();
}

const customerTable = document.getElementById("customer-lookup")

function showHint(searchTerm, field) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            customerTable.innerHTML = this.responseText;
            highlight_row();
        }
    };
    xmlhttp.open("GET", "Unit6_get_customer_table.php?searchTerm=" + searchTerm + "&field=" + field, true);
    xmlhttp.send();
}



function highlight_row() {
    var table = document.getElementById('customer-table');
    var firstName = document.getElementById('first-name');
    var lastName = document.getElementById('last-name');
    var email = document.getElementById('email');
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

            firstName.value = rowSelected.cells[1].innerHTML;
            lastName.value = rowSelected.cells[0].innerHTML;
            email.value = rowSelected.cells[2].innerHTML;
        }
    }

}


$(document).ready(function () {
    $("#submit").click(function (e) {
        e.preventDefault();
        
        var firstName = $("#first-name").val();
        var lastName = $("#last-name").val();
        var email = $("#email").val();
        var productId = $("#product").val();
        var quantity = parseInt($("#quantity").val());
        var inStock = parseInt($("#product option:selected").data("in-stock"));

        if (firstName == '' || lastName == '' || email == ''|| isNaN(quantity)) {
            alert("Please Fill All Fields");
        } else if (productId ==  null){
            alert("Please select a product");
        } else if (quantity > inStock) {
            alert("Quantity entered (" + quantity + ") is greater than available (" + inStock + ")!");
        } else {
            var dataString = $("form").serialize();
            $.ajax({
                type: "POST",
                url: "Unit6_ajaxsubmit.php",
                data: dataString,
                cache: false,
                success: function (result) {
                    customerTable.innerHTML = result;
                    $("form")[0].reset();
                }
            });
        }
        return false;
    });
});


//Cookie code
function setCookie(name, value, daysToLive) {
    // Encode value in order to escape semicolons, commas, and whitespace
    var cookie = name + "=" + encodeURIComponent(value);
    
    if(typeof daysToLive === "number") {
        /* Sets the max-age attribute so that the cookie expires
        after the specified number of days */
        cookie += "; max-age=" + (daysToLive*24*60*60);
        
        document.cookie = cookie;
    }
}

function getCookie(name) {
    // Split cookie string and get all individual name=value pairs in an array
    var cookieArr = document.cookie.split(";");
    
    // Loop through the array elements
    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        
        /* Removing whitespace at the beginning of the cookie name
        and compare it with the given string */
        if(name == cookiePair[0].trim()) {
            // Decode the cookie value and return
            return decodeURIComponent(cookiePair[1]);
        }
    }
    
    // Return null if not found
    return null;
}

function existsCookie(name) {
    if (getCookie(name) !== null) {
        return true;
    }
    return false;
}

function deleteCookie(name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
}