<?php include('header.php');

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>
<div class="content-wrapper">
            <!-- Content -->

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
                          <table class="table table-hover">
                              <thead>
                                  <tr>
                                      <th>S.no</th>
                                      <th>Name</th>
                                      <th>Image</th>
                                      <th>Description</th>
                                      <th>Price</th>
                                      <th>Stock</th>
                                      <th>Actions</th>
                                  </tr>
                              </thead>
                              <tbody class="table-border-bottom-0">
                                  <?php
                                  $recordsPerPage = 6;
                                  $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                                  $offset = ($currentPage - 1) * $recordsPerPage;

                                  $query = "SELECT * FROM products LIMIT $offset, $recordsPerPage";
                                  $result = mysqli_query($conn, $query);

                                  if ($result && mysqli_num_rows($result) > 0) {
                                      $sno = ($currentPage - 1) * $recordsPerPage + 1;

                                      while ($row = mysqli_fetch_assoc($result)) {
                                          echo "<tr>";
                                          echo "<td>$sno</td>";
                                          echo "<td>" . $row['name'] . "</td>";
                                          echo "<td>";

                                          $imageReferences = explode(',', $row['image']);
                                          if (!empty($imageReferences[0])) {
                                            echo '<img src="../uploads/' . $imageReferences[0] . '" alt="Image" height="50px" width="50px">';
                                        }
                                          echo "</td>";
                                          echo "<td>" . $row['description'] . "</td>";
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
