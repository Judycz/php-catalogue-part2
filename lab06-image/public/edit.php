<?php

// Establishing connection to the database.
require_once('/home/zchen41/data/connect.php');
$connection = db_connect();

// Importing our prepared statements.
require_once('../private/prepared.php');

// We need to define a unique title for this page.
$title = "Edit a cocktail | Zhu's Cocktail Gallery";
include("includes/header.php");

include('includes/admin-buttons.php');
// If the user isn't logged in, kick them out!

if (isset($_GET['cocktail_id'])) {
  $cocktail_id = $_GET['cocktail_id'];
} elseif (isset($_POST['cocktail_id'])) {
  $cocktail_id = $_POST['cocktail_id'];
} else {
  $cocktail_id = "";
}

$message = "";
$update_message = "";

$user_title = isset($_POST['submit']) ? trim($_POST['title']) : "";
$user_flavour = isset($_POST['submit']) ? trim($_POST['flavour']) : "";
if (isset($cocktail_id)) {
  if (is_numeric($cocktail_id) && $cocktail_id > 0) {
    $cocktail = select_cocktail_by_id($cocktail_id);
    if ($cocktail) {
      $existing_title = $cocktail['title'];
      $existing_flavour = $cocktail['flavour'];

    } else {
      $message .= "Sorry, there are no records available that match your query.";

    }
  }
}

if (isset($_POST['submit'])) {
  $do_i_proceed = TRUE;

  $user_title = filter_var($user_title, FILTER_SANITIZE_STRING);
  $user_title = mysqli_real_escape_string($connection, $user_title);
  if (strlen($user_title) < 2 || strlen($user_title) > 50) {
    $do_i_proceed = FALSE;
    $update_message .= "<p>Please enter a cocktail title that is shorter than 50 characters.</p>";
  }


  $user_flavour = filter_var($user_flavour, FILTER_SANITIZE_STRING);
  $user_flavour = mysqli_real_escape_string($connection, $user_flavour);
  if (strlen($user_flavour) < 2 || strlen($user_flavour) > 255) {
    $do_i_proceed = FALSE;
    $update_message .= "<p>Please enter a cocktail flavour that is shorter than 255 characters.</p>";
  }

  if ($do_i_proceed == TRUE) {
    update_cocktail($user_title, $user_flavour, $cocktail_id);
    $message .= "<p>" . $user_title . " updated successfully!</p>";

    // Now, let's blank out some variables, which should close the modal window.
    $cocktail_id = "";
  }
}

?>

<main>
  <section>
    <h1 class="fw-light text-center mt-5">Edit A Record</h1>
    <p class="text-muted mb-5">To edit a record in our database, click 'Edit' beside the row you would like to
      change. Next, add your updated values into the form and hit 'Save'.</p>
    <?php if ($message != ""): ?>
      <div class="alert alert-info" role="alert">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
    <div class="list">
      <!-- same code as home page almost -->
      <?php
      $cocktails = get_all_cocktails();

      if (count($cocktails) > 0) {
        echo "<table  class=\"table table-bordered table-hover\">";
        echo "<tr>";
        echo "<th scope=\"col\">Title</th>";
        echo "<th scope=\"col\">Flavour</th>";
        echo "<th scope=\"col\">Filename</th>";
        echo "<th scope=\"col\">uploaded On</th>";

        echo "<th scope=\"col\">Edit?</th>";
        echo "</tr>";
        foreach ($cocktails as $cocktail) {
          extract($cocktail);
          echo "<tr>
                        <td>$title</td>
                        <td>$flavour</td>
                        <td>$filename</td>
                        <td>$uploaded_on</td>
                        <td><a href=\"edit.php?cocktail_id=$image_id\">Edit</a></td>
                    </tr>";
        }
        echo "</table>";
      } else {
        echo "<p>Sorry there are no records available that match your query</p>";
      }
      ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title fs-5" id="exampleModalLabel">
              Edit
              <?php echo $existing_title; ?>
            </h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <!-- EDIT FORM -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

              <?php if (isset($update_message)): ?>
                <div class="message text-danger">
                  <?php echo $update_message; ?>
                </div>
              <?php endif; ?>

              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php
                if ($user_title != "") {
                  echo $user_title;
                } else {
                  echo $existing_title;
                }
                ?>">
              </div>

              <div class="mb-3">
                <label for="flavour" class="form-label">Flavour</label>
                <input type="text" id="flavour" name="flavour" class="form-control" value="<?php
                if ($user_flavour != "") {
                  echo $user_flavour;
                } else {
                  echo $existing_flavour;
                }
                ?>">
              </div>


              <!-- Hidden Values -->
              <input type="hidden" name="cocktail_id" value="<?php echo $cocktail_id; ?>">

              <!-- Submit -->
              <input type="submit" value="Save" name="submit" class="btn btn-success">
            </form>
          </div>
        </div>
      </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<?php if ($cocktail_id): ?>

  <script>
    var myModal = new bootstrap.Modal(document.getElementById("exampleModal"), {});

    document.onreadystatechange = function () {
      myModal.show();
    };
  </script>

<?php endif; ?>


<?php

include('includes/footer.php');
db_disconnect($connection);
?>