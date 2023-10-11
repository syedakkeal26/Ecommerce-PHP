<?php include('header.php');

$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
?>
<div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
                <h4 class="py-3 mb-4"><span class="text-muted fw-light">Categories /</span> Manage Categories</h4>

                <?php
                if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
                    $success_message = $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    echo '<div class="alert alert-success">' . $success_message . '</div>';
                }
                ?>

                <div class="card">
                    <h5 class="card-header text-center"> Manage Categories
                        <a href="addcategory.php">
                            <button type="button" class="btn btn-success float-end">Add Category</button>
                        </a>
                    </h5>
                            <div class="table table-hover progress-table text-center">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                          <th>S .no</th>
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    <?php
                                      $sno = 1;
                                      while ($row = mysqli_fetch_assoc($result)) {
                                          echo "<tr>";
                                          echo "<td>$sno</td>";
                                          echo "<td>" . $row['name'] . "</td>";
                                          echo '<td><a class="btn btn-outline-primary" href="editcategory.php?id=' . $row['id'] . '">Edit</a>  <a class="btn btn-outline-danger" href="deletecategory.php?id=' . $row['id'] . '">Delete</a></td>';
                                          echo "</tr>";
                                          $sno++;
                                      }
                                      ?>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        <!--/ Hoverable Table rows -->
            </div>
</div>
