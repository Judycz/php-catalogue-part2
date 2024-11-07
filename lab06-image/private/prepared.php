<?php


//edit item
$update_statement = $connection->prepare("UPDATE lab06_cocktail_prep SET title = ?, flavour = ? WHERE image_id = ?;");

//delete item
$delete_statement = $connection->prepare("DELETE FROM lab06_cocktail_prep WHERE image_id = ?;");
//select item
$select_statement = $connection->prepare("SELECT * FROM lab06_cocktail_prep;");

// Prepared statement for selecting a specific item (on edit page)
$specific_select_statement = $connection->prepare("SELECT * FROM lab06_cocktail_prep WHERE image_id = ?;");

// Function to handle database errors
function handle_database_error($statement)
{
  global $connection;
  die("Error in: " . $statement . ". Error details: " . $connection->error);
}

//select cocktail by id
function select_cocktail_by_id($image_id)
{
  global $connection;
  global $specific_select_statement;

  $specific_select_statement->bind_param("i", $image_id);

  if (!$specific_select_statement->execute()) {
    handle_database_error("selecting cocktail by ID");
  }

  $result = $specific_select_statement->get_result();
  $specific_cocktail = $result->fetch_assoc();

  return $specific_cocktail;
}
function get_all_cocktails()
{
  global $connection;
  global $select_statement;

  if (!$select_statement->execute()) {
    handle_database_error("fetching items");
  } else {
    $result = $select_statement->get_result();
    $cocktails = [];
    while ($row = $result->fetch_assoc()) {
      $cocktails[] = $row;
    } // end of while
    return $cocktails;
  } // end of else
}





// Function to update an existing one
function update_cocktail($title, $flavour, $cocktail_id)
{
  global $connection;
  global $update_statement;

  $update_statement->bind_param("ssi", $title, $flavour, $cocktail_id);
  $update_statement->execute();
}

// Function to delete a specific one by primary key
function delete_cocktail($image_id)
{
  global $connection;
  global $delete_statement;

  $delete_statement->bind_param("i", $image_id);

  if (!$delete_statement->execute()) {
    handle_database_error("deleting item");
  }
}

?>