<?php

    include("../database/database.php");
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/popup.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
            <div class="side">
                <?php include("../navigation/sidebar.php"); ?>
            </div>
            <div class="maincontent" style="margin-left: 250px; padding-left:10px; width:100%;">
                <div class="header">
                    <div><h1>Products Inventory</h1></div>
                    <a id="openPopupLink" class="btn btn-dark">Add</a>
                </div>
                
                <div class="pop-container" id="popupContainer">
                    <div class="popup-content">
                        <h2>Add product</h2>
                        <form id="popupForm" action="inventory.php" method="POST">
                            <div class="row">
                                <div class="col">
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" id="name" name="name" class="form-control" autocomplete="off" required/>
                                </div>

                                <div class="col">
                                    <label for="category" class="form-label">Category:</label>
                                    <input type="text" id="category" name="category"  class="form-control"  autocomplete="off" required/>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col">
                                <label for="type" class="form-label">Type:</label>
                                <input type="text" id="type" name="type" class="form-control" autocomplete="off" required/>
                                </div>

                                <div class="col">
                                <label for="quantity" class="form-label">Quantity:</label>
                                <input type="number" id="quantity" name="quantity" class="form-control" autocomplete="off" required/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                <label for="purchase" class="form-label">Purchase_Price:</label>
                                <input type="number" id="purchase" name="purchase" class="form-control"autocomplete="off" required/>
                                </div>

                                <div class="col">
                                <label for="wholesale" class="form-label">Wholesale_Price:</label>
                                <input type="number" id="wholesale" name="wholesale" class="form-control" autocomplete="off" required/>
                                </div>

                                <div class="col">
                                <label for="retail" class="form-label">Retail_price:</label>
                                <input type="number" id="retail" name="retail" class="form-control" autocomplete="off" required/>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success" name="add">Add product</button>
                            <button type="button" class="close-popup btn btn-dark">Close</button>

                        </form>
                    </div>
                </div>

                <script>
                    //Get elements
                    const openPopupLink= document.getElementById('openPopupLink');
                    const popupContainer= document.getElementById('popupContainer');
                    const closePopupButtons=document.querySelectorAll('.close-popup');

                    //open popup

                    openPopupLink.addEventListener('click', function (event){
                        event.preventDefault();
                        popupContainer.style.display='flex';
                    });

                    //close popup

                    closePopupButtons.forEach(button=>{
                        button.addEventListener('click', function(){
                            popupContainer.style.display='none';
                        })
                    });

                    //click outside to close.

                    popupContainer.addEventListener('click', function (event){
                        if(event.target === popupContainer){
                            popupContainer.style.display='none';
                        }
                    });

                </script>
            

            <table class="table table-striped-column table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Purchase_Price</th>
                        <th>Wholesale_Price</th>
                        <th>Retail_price</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $sql = "SELECT * FROM storeproducts";
                        $result = $connection->query($sql);

                        if (mysqli_num_rows($result) > 0) {
                            // Loop through all rows
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "
                                <tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['category']}</td>
                                    <td>{$row['type']}</td>
                                    <td>{$row['quantity']}kgs.</td>
                                    <td>Ksh.{$row['Purchase_price']}</td>
                                    <td>Ksh.{$row['Wholesale_price']}</td>
                                    <td>Ksh.{$row['Retail_price']}@</td>
                                    <td>
                                    <a href='./editRecord.php?id={$row['id']}'  class='btn btn-success' style='margin-right:60px;' >update</a>
                                    <a href='./deleteRecord.php?id={$row['id']} ' class='btn btn-danger'>Delete</a>
                                    
                                    </td>

                                </tr>
                                ";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No products found</td></tr>";
                        }


                        // get data from form and post to my db

                        if(isset($_POST["add"]) ){
                            $name=$_POST["name"];
                            $category=$_POST["category"];
                            $type=$_POST["type"];
                            $quantity=$_POST["quantity"];
                            $purchase=$_POST["purchase"];
                            $wholesale=$_POST["wholesale"];
                            $retail=$_POST["retail"];

                    
                            $sql = "INSERT INTO storeproducts(name, category, type, quantity, Purchase_price, Wholesale_price, Retail_price)
                                        VALUES('$name','$category', '$type','$quantity','$purchase','$wholesale','$retail')";
                            
                            try{
                                mysqli_query($connection,$sql);
                                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> New item added.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                            }catch(exception){
                                echo "<script>alert('adding failed')</script>";
                            }
                    
                        }

                        mysqli_close($connection);
                    ?>
                </tbody>
            </table>
            </div>
    </div>
</body>
</html>