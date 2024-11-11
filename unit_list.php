
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Table Styling */
.table {
    width: 100%;
    border-collapse: collapse; /* Ensures borders are collapsed into a single line */
    margin: 20px 0; /* Adds some space around the table */
    font-family: Arial, sans-serif; /* Sets a clean font for the table */
}

/* Table Header Styling */
.table thead {
    background-color: #4b8bf5; /* Blue background for the header */
    color: white; /* White text color */
}

.table th {
    padding: 12px 20px; /* Adds padding to table headers */
    text-align: left; /* Align text to the left */
    font-size: 1.1em; /* Slightly larger text for headers */
}

/* Table Body Styling */
.table tbody tr {
    background-color: #f9f9f9; /* Light background for rows */
    border-bottom: 1px solid #ddd; /* Adds border between rows */
}

.table tbody tr:hover {
    background-color: #f1f1f1; /* Slightly darker background on hover */
}

.table td {
    padding: 12px 20px; /* Padding for table cells */
    text-align: left; /* Align text to the left */
    font-size: 1em; /* Regular font size for table cells */
}

/* Add a border to the table */
.table, .table th, .table td {
    border: 1px solid #ddd; /* Light grey border for the table */
}

/* For Responsive Design: */
@media (max-width: 768px) {
    .table th, .table td {
        padding: 8px; /* Reduces padding on smaller screens */
    }

    .table thead {
        font-size: 0.9em; /* Smaller font size for smaller screens */
    }
}


/* Button styling */
.btn button {
    margin-left: 80%;
    background-color: #4b8bf5; /* Button color */
    color: white; /* Text color */
    border: none; /* Remove default border */
    padding: 10px 20px; /* Padding inside the button */
    font-size: 1em; /* Font size of the button text */
    cursor: pointer; /* Changes the cursor to a pointer when hovered */
    border-radius: 5px; /* Rounded corners for the button */
    transition: background-color 0.3s ease; /* Smooth transition for hover effect */
}

/* Button hover effect */
.btn button:hover {
    background-color: #3578e5; /* Darker shade of blue when hovering */
}


    </style>
</head>

<body>
    <?php
   if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])){
    include_once "config.php";
   $del = mysqli_real_escape_string($conn, $_POST['delete_id']);
   $unitdata = "DELETE FROM `units` WHERE `uid` = '$del'";
   $unitres = mysqli_query($conn, $unitdata);
   }
    ?>
    <div class="btn">   
       <a href="approve.php"><button>back</button></a> 
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">unit</th>
                <th scope="col">time</th>
                <th scope="col">Action</th>
               
            </tr>
        </thead>
        <tbody id="tablebody">
            <?php
            include_once "config.php";
            $sql = "SELECT * FROM `units`";
            $result = mysqli_query($conn, $sql);
            $row = (mysqli_num_rows($result));
            if ($row > 0) {
                $i = 1;
                while ($data = mysqli_fetch_assoc($result)) {
                    $checkrs = "SELECT * FROM `check` WHERE `unit` = $data[uid]";
                    $checkres = mysqli_query($conn, $checkrs);
                    $product_count = mysqli_num_rows($checkres);
            ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $data['title'] ?></td>
                        <td><?= $data['datetime'] ?></td>
                        <td>
                        <?php
                         if($product_count == 0){

                        ?>
                        <form action="" method="POST">
                                <input type="hidden" name="delete_id" value="<?= $data['uid']; ?>"> 
                                <button type="submit" name="delete-btn">Delete</button>
                         
                            </form>
                            <?php
                            }
                            ?>
                        </td>
                       
                    </tr>

            <?php
                }
            }
            ?>
</body>

</html>