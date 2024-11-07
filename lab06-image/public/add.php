<?php

/*

On the server, there will also be the following directories: 

    /images
        /full
        /thumbs

*/

// Establish a connection to the database.
require_once('/home/zchen41/data/connect.php');
$connection = db_connect();

// Initialise all of our variables.
$message = $message ?? '';
$image_title = $_POST['img-title'] ?? '';
$image_description = $_POST['img-description'] ?? '';

include('includes/header.php');
include('includes/admin-buttons.php');

// This script will process the files.
include('includes/upload.php');

?>
<main class="container">
  <section class="row justify-content-center py-5 my-5">
    <div class="col-6">
      <h1 class="fw-light mb-5">Upload Image Files</h1>

      <!-- Error Message -->
      <?php if ($message != ''): ?>
        <div class="alert alert-secondary my-3">
          <?php echo $message; ?>
        </div>
      <?php endif; ?>

      <!-- Preview: if there's a newly created image, we'll show a preview of it to the user. -->

      <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
        <!-- Image Title -->
        <div class="mb-3">
          <label for="img-title">Image Title</label>
          <input type="text" id="img-title" name="img-title" maxlength="50" class="form-control" value="<?php if (isset($img_title))
            echo $img_title; ?>" required>
        </div>

        <!-- Image Description -->
        <div class="mb-3">
          <label for="img-description">Flavour</label>
          <input type="text" id="img-description" name="img-description" maxlength="255" class="form-control" value="<?php if (isset($img_description))
            echo $img_description; ?>" required>
        </div>

        <!-- File Upload -->
        <div class="mb-3">
          <label for="img-file">Image File</label>
          <input type="file" id="img-file" name="img-file" class="form-control" accept=".jpg, .jpeg, .png, .webp"
            required>
        </div>

        <!-- Submit -->
        <div class="my-5">
          <input type="submit" name="submit" id="submit" value="Upload" class="btn btn-primary">
        </div>
      </form>

      <div class="my-5">
        <a href="gallery.php">Link To Gallery Page</a>
      </div>

    </div>
  </section>
</main>

<?php
include('includes/footer.php');

?>