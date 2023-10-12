<?php include('header.php');

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Products /</span> Manage Products</h4>
            <?php
                if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
                    $success_message = $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    echo '<div class="alert alert-success">' . $success_message . '</div>';
                }
                ?>
        <div class="card">
            <h5 class="card-header text-center">Manage Products
                <a href="addproduct.php">
                    <button type="button" class="btn btn-success float-end">Add Product</button>
                </a>
            </h5>
            <div class="table table-hover progress-table text-center">
                <table id="productform" class="table table-hover">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th id="name-header">Name<i class="fas fa-sort"></i></th>
                            <th>Image</th>
                            <th>Description</th>
                            <th id="price-header">Price<i class="fas fa-sort"></i></th>
                            <th id="stock-header">Stock<i class="fas fa-sort"></i></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        $recordsPerPage = 6;
                        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                        $offset = ($currentPage - 1) * $recordsPerPage;

                        // Fetch products and categories from your database
                        $query = "SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id LIMIT $offset, $recordsPerPage";
                        $result = mysqli_query($conn, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $sno = ($currentPage - 1) * $recordsPerPage + 1;

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr data-category-id='{$row['category_id']}'>";
                                echo "<td>$sno</td>";
                                echo "<td>" . substr($row['name'], 0, 20) . (strlen($row['name']) > 20 ? '...' : '') . "</td>";
                                echo "<td>";

                                $imageReferences = explode(',', $row['image']);
                                if (!empty($imageReferences[0])) {
                                    echo '<img src="../uploads/' . $imageReferences[0] . '" alt="Image" height="50px" width="50px">';
                                }
                                echo "</td>";
                                echo "<td>" . substr($row['description'], 0, 25) . (strlen($row['description']) > 25 ? '...' : '') . "</td>";
                                echo "<td>" . $row['price'] . "</td>";
                                echo "<td>" . $row['stock'] . "</td>";
                                echo '<td><a class="btn rounded-pill btn-outline-primary" href="editproduct.php?id=' . $row['id'] . '">Edit</a>  <a class="btn rounded-pill btn-outline-danger" href="deleteproduct.php?id=' . $row['id'] . '">Delete</a></td>';
                                echo "</tr>";
                                $sno++;
                            }
                        } else {
                            // No products found
                            echo 'No products found.';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <?php
                $totalRecordsQuery = "SELECT COUNT(*) as total FROM products";
                $totalRecordsResult = mysqli_query($conn, $totalRecordsQuery);
                $row = mysqli_fetch_assoc($totalRecordsResult);
                $totalRecords = $row['total'];
                ?>
                Showing <?php echo (($currentPage - 1) * $recordsPerPage + 1); ?>
                to <?php echo min($currentPage * $recordsPerPage, $totalRecords); ?> of <?php echo $totalRecords; ?> entries
            </div>
            <ul class="pagination justify-content-end">
                <?php
                $totalPages = ceil($totalRecords / $recordsPerPage);
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<li class="page-item ' . ($i == $currentPage ? 'active' : '') . '">';
                    echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // Initial sorting order (ascending)
    let ascending = true;

    // Function to sort the table by a specific column
    function sortTable(column) {
        const table = $("#productform"); // Replace with your table's ID
        const rows = table.find('tr:gt(0)').toArray(); // Skip the header row
        const index = column.index();
        rows.sort(function(a, b) {
            const aValue = $(a).find('td').eq(index).text();
            const bValue = $(b).find('td').eq(index).text();

            if (column.attr('id') === 'price-header') {
                return (ascending ? 1 : -1) * (parseFloat(aValue) - parseFloat(bValue));
            } else if (column.attr('id') === 'stock-header') {
                return (ascending ? 1 : -1) * (parseInt(aValue) - parseInt(bValue));
            } else {
                return (ascending ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue));
            }
        });

        ascending = !ascending; // Toggle the sorting order

        table.find('tr:gt(0)').remove();
        table.append(rows);
            column.find('i').toggleClass('fa-sort-up fa-sort-down');
    }
    $('#name-header, #price-header, #stock-header').on('click', function() {
                $('#name-header i, #price-header i, #stock-header i').attr('class', 'fas fa-sort');
                sortTable($(this));
            });

            $('#category-filter').on('change', function() {
        const selectedCategory = $(this).val();

        $('tbody tr').each(function() {
            const categoryCell = $(this).find('td').eq(3).text();
            const categoryId = $(this).data('category-id');

            if (selectedCategory === '' || categoryId === selectedCategory) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
</script>
