<?php

// Establish a connection to the database.
require_once('/home/zchen41/data/connect.php');
$connection = db_connect();

include('includes/header.php');
include('includes/admin-buttons.php');
?>

<main class="container">
  <section class="row justify-content-center py-5 my-5">
    <div class="col-6">
      <h1 class="fw-light mb-5">Image Gallery</h1>
      <p class="lead">Welcome to Cocktail gallery, uploaded by users just like you.</p>

      <a href="add.php" class="btn btn-primary">Upload More Cocktail Images</a>

      <hr class="my-5">

      <!-- Gallery Thumbs -->
      <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-3 g-2">
        <?php

        $query = "SELECT * FROM lab06_cocktail_prep ORDER BY uploaded_on DESC;";

        $result = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_assoc($result)) {
          $id = $row['image_id'];
          $title = $row['title'];
          $flavour = $row['flavour'];
          $filename = $row['filename'];
          $uploaded_on = $row['uploaded_on'];

          echo "
                        <div class=\"col\">
                        <div class=\"card p-0 shadow-sm\">

                            <img src=\"images/thumbs/$filename\" alt=\"$flavour\" class=\"card-img-top\">

                            <div class=\"card-body\">

                                <h2 class=\"card-text fs-4\">$title</h2>
                                <p class=\"card-text text-muted\">Added on $uploaded_on</p>

                                <button type=\"button\" class=\"btn btn-primary\" data-bs-toggle=\"modal\" data-bs-target=\"#modal$id\">
                                    View
                                </button>

                            </div>
                        </div>
                        </div>

                    ";

          ?>

          <!-- Modal -->
          <div class="modal fade" id="modal<?php echo $id; ?>" tabindex="-1"
            aria-labelledby="modal-title<?php echo $id; ?>" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title fs-5" id="modal-title<?php echo $id; ?>">
                    <?php echo $title; ?>
                  </h3>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <?php
                  echo "<img src=\"images/full/$filename\" alt=\"$flavour\" class=\"img-fluid\" title=\"$flavour\">";
                  echo "<p class=\"mt-4\"> $flavour </p>";
                  ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <?php

        } // end of while loop
        ?>
      </div>
    </div>
  </section>
</main>

<!-- BS JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>

<?php

db_disconnect($connection);

?>