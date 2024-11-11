<?php

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once "config.php";
    if (isset($_POST['email'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        if ($email == "" || $password == "") {
            echo "Please fill all fields.";
        } else {
            $data = "SELECT * FROM `login` WHERE `email` = '$email' AND `password` = '$password'";
            $res = mysqli_query($conn, $data);
            $num = mysqli_num_rows($res);

            if ($num == 1) {
                $row = mysqli_fetch_assoc($res);
                $_SESSION['id'] = $row['id'];
                $_SESSION['login'] = true;
                $_SESSION['name'] = $row['name'];

                echo "<script>window.location.href = 'approve.php';</script>";
            } else {
                // echo "Login failed. Incorrect email or password.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maker-Checker Product Management System</title>
    <link rel="stylesheet" href="user.css">
</head>

<body>



    <div class="admin">
        <a href="login.php">
            <button class="admin-btn">Admin Login</button>
        </a>
    </div>



    <!-- new code -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['username'])) {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $productname = mysqli_real_escape_string($conn, $_POST['productname']);
            $producttype = mysqli_real_escape_string($conn, $_POST['producttype']);
            $purchaseprice = mysqli_real_escape_string($conn, $_POST['purchaseprice']);
            $sellingprice = mysqli_real_escape_string($conn, $_POST['sellingprice']);
            $unit = mysqli_real_escape_string($conn, $_POST['unit']);

            // Check if product type exists in the `types` table
            $pro = "SELECT * FROM `types` WHERE title_pro = '$producttype'";
            $prores = mysqli_query($conn, $pro);

            if (mysqli_num_rows($prores) == 0) { // If product type doesn't exist
                // Insert product type into `types` table
                $proins = "INSERT INTO `types` (title_pro) VALUES ('$producttype')";
                mysqli_query($conn, $proins);
            }

            // Check if unit exists in the `units` table
            $checkunit = "SELECT * FROM `units` WHERE title = '$unit'";
            $checkres = mysqli_query($conn, $checkunit);

            if (mysqli_num_rows($checkres) == 0) { // If unit doesn't exist
                // Insert unit into `units` table
                $checkins = "INSERT INTO `units` (title) VALUES ('$unit')";
                mysqli_query($conn, $checkins);
            }

            // Fetch the product type ID from the `types` table
            $typedata = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `tid` FROM types WHERE title_pro = '$producttype'"));
            $producttype_id = $typedata['tid'];

            // Fetch the unit ID from the `units` table
            $unitdata = mysqli_fetch_assoc(mysqli_query($conn, "SELECT  `uid` FROM units WHERE title = '$unit'"));
            $unit_id = $unitdata['uid'];

            // Insert the product into the `check` table
            $insertProduct = "INSERT INTO `check` (username ,productname, producttype, purchaseprice, sellingprice, unit, status) 
                          VALUES ( '$username','$productname', '$producttype_id', '$purchaseprice', '$sellingprice', '$unit_id', 'pending')";

            if (mysqli_query($conn, $insertProduct)) {
                echo "Product added successfully!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
    ?>


    <!-- Maker Section -->
    <?php
    // if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //     if (isset($_POST['username'])) {
    //         $username = mysqli_real_escape_string($conn, $_POST['username']);
    //         $productname = mysqli_real_escape_string($conn, $_POST['productname']);
    //         $producttype = mysqli_real_escape_string($conn, $_POST['producttype']);
    //         $purchaseprice = mysqli_real_escape_string($conn, $_POST['purchaseprice']);
    //         $sellingprice = mysqli_real_escape_string($conn, $_POST['sellingprice']);
    //         $unit = mysqli_real_escape_string($conn, $_POST['unit']);

    //         if ($username == "" || $productname == "" || $producttype == "" || $purchaseprice == "" || $sellingprice == "" || $unit == "") {
    //             echo "not connected";
    //         } else {
    //             $maker = "INSERT INTO `check` (username, productname, producttype, purchaseprice, sellingprice, unit, status) VALUES ('$username', '$productname', '$producttype', '$purchaseprice', '$sellingprice', '$unit', 'pending')";
    //             $makerres = mysqli_query($conn, $maker);
    //             if ($makerres) {
    //                 // Redirect to prevent form resubmission on refresh
    //                 header("Location: user.php?submitted=true");
    //                 exit();
    //             } else {
    //                 echo "Error: " . mysqli_error($conn);
    //             }
    //         }
    //     }
    // }
    ?>
    <section id="maker-section">
        <h2>Maker: Add or Update Product</h2>
        <form id="productForm" action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter username" required />

            <label for="productName">Product Name:</label>
            <input type="text" id="productname" name="productname" placeholder="Product Name" required />

            <label for="producttype">Product Type:</label>
            <select id="producttype" name="producttype" required>
                <option value="" disabled selected>Choose product type</option>
                <option value="Food">Food</option>
                <option value="Cosmetic">Cosmetic</option>
                <option value="Other">Other</option>
            </select>


            <label for="purchasePrice">Purchase Price:</label>
            <input type="number" step="0.01" id="purchaseprice" name="purchaseprice" placeholder="Purchase Price"
                required />

            <label for="sellingPrice">Selling Price:</label>
            <input type="number" step="0.01" id="sellingprice" name="sellingprice" placeholder="Selling Price"
                required />

            <label for="unit">Unit:</label>
            <select id="unit" name="unit" required>
                <option value="" disabled selected>Select unit</option>
                <option value="kg">kg</option>
                <option value="ml">ml</option>
                <option value="pcs">pcs</option>
                <option value="pcs">other</option>
            </select>


            <button type="submit">Submit for Approval</button>
            <div id="makerStatus" class="status-message"></div>
        </form>
    </section>
    </div>

    <div class="container">

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Username</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Product Type</th>
                    <th scope="col">Purchase Price</th>
                    <th scope="col">Selling Price</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <?php
            include_once "config.php";

            // Update your query to join the 'types' and 'units' tables
            $table = "SELECT c.id, c.username, c.productname, t.title_pro AS producttype, u.title AS unit, c.purchaseprice, c.sellingprice, c.status 
              FROM `check` c
              INNER JOIN `types` t ON c.producttype = t.tid
              INNER JOIN `units` u ON c.unit = u.uid";

            // Execute the query
            $tableres = mysqli_query($conn, $table);

            // Check if there are results
            $row = mysqli_num_rows($tableres);
            if ($row > 0) {
                $i = 1;
                while ($dt = mysqli_fetch_assoc($tableres)) {
            ?>

            <tbody id="tablebody">
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $dt['username'] ?></td>
                    <td><?= $dt['productname'] ?></td>
                    <td><?= $dt['producttype'] ?></td> <!-- This will display the product type name -->
                    <td><?= $dt['purchaseprice'] ?></td>
                    <td><?= $dt['sellingprice'] ?></td>
                    <td><?= $dt['unit'] ?></td>
                    <?php
                            if($dt['status'] == 'refill'){
                                ?>
                    <td>
                        <button style=" background-color: #09efde;"
                            onclick="openEditModal('<?= $dt['id'] ?>', '<?= $dt['productname'] ?>', '<?= $dt['producttype'] ?>', '<?= $dt['purchaseprice'] ?>', '<?= $dt['sellingprice'] ?>', '<?= $dt['unit'] ?>')">Edit</button>
                    </td>
                    <?php
                            }else{
                            ?>
                    <td> <?=$dt['status']?></td>
                    <?php

                            }
                            ?>




                    </td>
                </tr>
                <?php
                }
            }
                ?>
            </tbody>
        </table>
    </div>

    <?php
     if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_product'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $productname = mysqli_real_escape_string($conn, $_POST['productname']);
        $producttype = mysqli_real_escape_string($conn, $_POST['producttype']);
        $purchaseprice = mysqli_real_escape_string($conn, $_POST['purchaseprice']);
        $sellingprice = mysqli_real_escape_string($conn, $_POST['sellingprice']);
        $unit = mysqli_real_escape_string($conn, $_POST['unit']);
 
 
        $pro = "SELECT * FROM `types` WHERE title_pro = '$producttype'";
        $prores = mysqli_query($conn, $pro);
        
        if (mysqli_num_rows($prores) == 0) { // If product type doesn't exist
            // Insert product type into `types` table
            $proins = "INSERT INTO `types` (title_pro) VALUES ('$producttype')";
            mysqli_query($conn, $proins);
        }
        
        // Check if unit exists in the `units` table
        $checkunit = "SELECT * FROM `units` WHERE title = '$unit'";
        $checkres = mysqli_query($conn, $checkunit);
        
        if (mysqli_num_rows($checkres) == 0) { // If unit doesn't exist
            // Insert unit into `units` table
            $checkins = "INSERT INTO `units` (title) VALUES ('$unit')";
            mysqli_query($conn, $checkins);
        }
 
        // Fetch the product type ID from the `types` table
        $typedata = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `tid` FROM types WHERE title_pro = '$producttype'"));
        $producttype_id = $typedata['tid'];
 
        // Fetch the unit ID from the `units` table
        $unitdata = mysqli_fetch_assoc(mysqli_query($conn, "SELECT  `uid` FROM units WHERE title = '$unit'"));
        $unit_id = $unitdata['uid'];
 
    
        $editdata = "UPDATE `check` SET productname = '$productname', producttype = '$producttype_id', purchaseprice = '$purchaseprice', sellingprice = '$sellingprice', unit = '$unit_id' WHERE id = '$id'";
        $editres = mysqli_query($conn, $editdata);
    }
    ?>


    <div id="editModal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn" onclick="closeEditModal()">X</span>
            <h2>Edit Product</h2>
            <form action="" method="POST">
                <input type="hidden" id="edit-id" name="id" />
                <label for="edit-productname">Product Name:</label>
                <input type="text" id="edit-productname" name="productname" required />
                <br />
                <label for="edit-producttype">Product Type:</label>
                <select id="edit-producttype" name="producttype" required>
                    <option value="" disabled selected>Choose product type</option>
                    <option value="Food">Food</option>
                    <option value="Cosmetic">Cosmetic</option>
                    <option value="Other">Other</option>
                </select>
                <br />
                <label for="edit-purchaseprice">Purchase Price:</label>
                <input type="number" id="edit-purchaseprice" name="purchaseprice" required />
                <br />
                <label for="edit-sellingprice">Selling Price:</label>
                <input type="number" id="edit-sellingprice" name="sellingprice" required />
                <br />
                <label for="edit-unit">Unit:</label>
                <select id="edit-unit" name="unit" required>
                    <option value="" disabled selected>Select unit</option>
                    <option value="kg">kg</option>
                    <option value="ml">ml</option>
                    <option value="pcs">pcs</option>
                    <option value="pcs">other</option>
                </select>

                <br />
                <button type="submit" name="edit_product">Update Product</button>
            </form>
        </div>
    </div>

    <script>
    function openEditModal(id, productname, producttype, purchaseprice, sellingprice, unit) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-productname').value = productname;
        document.getElementById('edit-producttype').value = producttype;
        document.getElementById('edit-purchaseprice').value = purchaseprice;
        document.getElementById('edit-sellingprice').value = sellingprice;
        document.getElementById('edit-unit').value = unit;

        document.getElementById('editModal').style.display = "block";
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = "none";
    }
    </script>


</body>

</html>