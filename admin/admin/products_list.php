<?php
session_start();
include("../../db.php");
error_reporting(0);

if(isset($_GET['action']) && $_GET['action']!="" && $_GET['action']=='delete')
{
    $product_id = $_GET['product_id'];

    // Delete picture
    $result = mysqli_query($con, "SELECT product_image FROM products WHERE product_id='$product_id'") or die("query is incorrect...");
    list($picture) = mysqli_fetch_array($result);
    $path = "../product_images/$picture";

    if(file_exists($path))
    {
        unlink($path);
    }

    // Delete product
    mysqli_query($con, "DELETE FROM products WHERE product_id='$product_id'") or die("query is incorrect...");
}

// Pagination logic
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 12;
$pageup = ($page - 1) * $items_per_page;

include "sidenav.php";
include "topheader.php";
?>
<!-- End Navbar -->
<div class="content">
    <div class="container-fluid">
        <div class="col-md-14">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Products List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive ps">
                        <table class="table tablesorter" id="page1">
                            <thead class="text-primary">
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>
                                        <a class="btn btn-primary" href="add_products.php">Add New</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = mysqli_query($con, "SELECT product_id, product_image, product_title, product_price FROM products WHERE product_cat IN (2, 3, 4) LIMIT $pageup, $items_per_page") or die("query 1 incorrect...");

                                while (list($product_id, $image, $product_name, $price) = mysqli_fetch_array($result)) {
                                    echo "<tr>
                                        <td><img src='../../product_images/$image' style='width:50px; height:50px; border:groove #000'></td>
                                        <td>$product_name</td>
                                        <td>$price</td>
                                        <td><a class='btn btn-success' href='clothes_list.php?product_id=$product_id&action=delete'>Delete</a></td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination logic -->
            <?php
            // Count total number of products
            $paging = mysqli_query($con, "SELECT product_id FROM products WHERE product_cat IN (2, 3, 4)");
            $count = mysqli_num_rows($paging);
            $total_pages = ceil($count / $items_per_page);
            ?>

            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <!-- Previous Button -->
                    <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($page > 1){ echo 'products_list.php?page=' . ($page - 1); } else { echo '#'; } ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php 
                    for($b = 1; $b <= $total_pages; $b++) { 
                    ?>
                        <li class="page-item <?php if($b == $page){ echo 'active'; } ?>">
                            <a class="page-link" href="products_list.php?page=<?php echo $b; ?>"><?php echo $b; ?></a>
                        </li>
                    <?php 
                    } 
                    ?>

                    <!-- Next Button -->
                    <li class="page-item <?php if($page >= $total_pages){ echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($page < $total_pages) { echo 'products_list.php?page=' . ($page + 1); } else { echo '#'; } ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<?php
include "footer.php";
?>
