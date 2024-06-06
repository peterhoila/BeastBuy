<?php

include 'Unit6_database.php';
$conn = getConnection();
$searchTerm = $_GET["searchTerm"];
$field = $_GET["field"];
if (strlen($searchTerm) == 0){
    echo "";
    return;
}
$rows = findMatchCustName($conn, $searchTerm, $field);  
if ($rows->num_rows == 0){
    echo "No matching customer found.";
    return;
} else {
    echo '<table id="customer-table">
    <tr>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Email</th>
    </tr>';
    foreach ($rows as $row) {
            echo '<tr>
                <td>'; echo $row['last_name']; echo '</td>
                <td>'; echo $row['first_name']; echo'</td>
                <td>'; echo $row['email']; echo'</td>
            </tr>';
        }
echo '</table>';
}
?>