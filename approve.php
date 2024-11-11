<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="approve.css">

</head>

<body>
     <!-- Navbar Section -->
     <nav class="navbar">
    <ul>
        <li><a href="product_list.php" class="navbar-btn"><button>Product type List</button></a></li>
        <li><a href="unit_list.php" class="navbar-btn"><button>Unit List</button></a></li>
        <!-- <li><a href="unit_list.php" class="navbar-btn"><button>product list</button></a></li> -->
    </ul>
    <div class="btn">
        <a href="user.php"><button>Logout</button></a>
    </div>
</nav>

    <?php
    session_start();
    if (isset($_SESSION['id']) && $_SESSION['login'] && $_SESSION['login'] == true) {
        include_once "config.php";
        $id = mysqli_real_escape_string($conn, $_SESSION['id']);
        $getid = "SELECT * FROM `login` WHERE `id` = '$id'";
        $result = mysqli_query($conn, $getid);
        $num = mysqli_num_rows($result);
        if ($num == 1) {
            $user = mysqli_fetch_assoc($result);
        } else {
            echo "<script>window.location.href = 'index.php?login=false';</script>";
        }
    } else {
        echo "fail";
    }

    ?>
    <!-- php of approve -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['id'])) {
            $id = mysqli_real_escape_string($conn, $_POST['id']);

            $status = "";
            if (isset($_POST['approve'])) {
                $status = "Approved";
            }
            if (isset($_POST['refill'])) {
                $status = "refill";
            }
        }
        if (!empty($status)) {
            $update = "UPDATE `check` SET `status` ='$status' WHERE id = '$id'";
            $updata = mysqli_query($conn, $update);
        }
        // if ($updata) {
        //     // echo "Status updated successfully!";
        // } else {
        //     echo "Error updating status: " . mysqli_error($conn); // Show error message if update fails
        // }
    }


    ?>
    <?php

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete_id'])) {
        $did = mysqli_real_escape_string($conn, $_POST['delete_id']);
        
        $getdetails = "SELECT `producttype`, `unit` FROM `check` WHERE id = $did";
        $resultdata = mysqli_query($conn, $getdetails);
        $fecdata = mysqli_fetch_assoc($resultdata);

        if($fecdata){
            $producttype = $fecdata['producttype'];
            $unit = $fecdata['unit'];
       
        $del = "DELETE FROM `check` WHERE id = '$did'";
        $delres = mysqli_query($conn, $del);

        // $del_type = "DELETE FROM `types` WHERE tid = '$producttype'";
        // $res_type = mysqli_query($conn, $del_type);
        
        // $del_unit = "DELETE FROM `units` WHERE `uid` = '$unit'";
        // $res_unit = mysqli_query($conn, $del_unit);
    }
}
    ?>

    <!-- html of approve table -->

    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Username</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Product Type</th>
                    <th scope="col">Purchase Price</th>
                    <th scope="col">Selling Price</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Status</th>
                    <th scope="col">Approve</th>
                    <th scope="col">Reject</th>
                    <th scope="col">Delete</th>
                    <th scope="col">Edit</th>
                </tr>
            </thead>
            <tbody id="tablebody">
                <?php
                include_once "config.php";
                $table = "SELECT c.id, c.username, c.productname, t.title_pro AS producttype, u.title AS unit, c.purchaseprice, c.sellingprice, c.status 
                      FROM `check` c
                      INNER JOIN `types` t ON c.producttype = t.tid
                      INNER JOIN `units` u ON c.unit = u.uid";

                // Execute the query
                $tableres = mysqli_query($conn, $table);

                // Check if there are results
                if (mysqli_num_rows($tableres) > 0) {
                    $i = 1;
                    while ($dt = mysqli_fetch_assoc($tableres)) {
                ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($dt['username']) ?></td>
                            <td><?= htmlspecialchars($dt['productname']) ?></td>
                            <td><?= htmlspecialchars($dt['producttype']) ?></td>
                            <td><?= htmlspecialchars($dt['purchaseprice']) ?></td>
                            <td><?= htmlspecialchars($dt['sellingprice']) ?></td>
                            <td><?= htmlspecialchars($dt['unit']) ?></td>
                            <td><?= htmlspecialchars($dt['status']) ?></td>
                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" name="id" value="<?= $dt['id'] ?>" />
                                    <button class="approve-btn" name="approve" onclick="approveProduct(<?= $dt['id'] ?>)">Approve</button>
                                </form>
                            </td>
                    
                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" name="id" value="<?= $dt['id'] ?>" />
                                    <button class="refill-btn" name="refill" onclick="refillProduct(<?= $dt['id'] ?>)">Refill</button>
                                </form>
                            </td>

                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" name="delete_id" value="<?= $dt['id'] ?>" />
                                    <button class="delete-btn" name="delete" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                </form>
                            </td>
                            <td ><button style=" background-color: #09efde;" onclick="openEditModal('<?= $dt['id'] ?>', '<?= $dt['productname'] ?>', '<?= $dt['producttype'] ?>', '<?= $dt['purchaseprice'] ?>', '<?= $dt['sellingprice'] ?>', '<?= $dt['unit'] ?>')" >Edit</button></td>

                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='12'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>


    <!-- php of edit modal -->

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
   
    //    if ($editres) {
    //        echo "<script>alert('Product updated successfully!');</script>";
    //    } else {
    //        echo "<script>alert('Error updating product: " . mysqli_error($conn) . "');</script>";
    //    }
   }

   
    


    ?>
    <!-- Edit Modal -->
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