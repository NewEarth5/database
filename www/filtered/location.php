<!DOCTYPE html>
<html>
  <head>
    <title>List of Locations</title>
  </head>
  <body>
    <style>
      table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
      }

      td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
      }

      tr:nth-child(even) {
        background-color: #dddddd;
      }
    </style>
    <?php
      if(!isset($_POST['submit']) or isset($_POST['reset'])) {
        $_POST['id'] = '';
        $_POST['street'] = '';
        $_POST['city'] = '';
        $_POST['postal_code'] = '';
        $_POST['country'] = '';
        $_POST['comment'] = '';
      }

      echo '<form method="post" action="location.php">';
      echo '<input type="number" name="id"          value="' . trim($_POST['id'])          .'" placeholder="Location ID" min="1" step="1">';
      echo '<input type="text"   name="street"      value="' . trim($_POST['street'])      .'" placeholder="Steet"       maxlength="255">';
      echo '<input type="text"   name="city"        value="' . trim($_POST['city'])        .'" placeholder="City"        maxlength="255">';
      echo '<input type="text"   name="postal_code" value="' . trim($_POST['postal_code']) .'" placeholder="Postal Code" maxlength="12">';
      echo '<input type="text"   name="country"     value="' . trim($_POST['country'])     .'" placeholder="Country"     maxlength="255">';
      echo '<input type="submit" name="submit"      value="Filter">';
      echo '<input type="submit" name="reset"       value="Reset">';
      echo '</form>';

      $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');

      $sql = 'SELECT * FROM `Location` WHERE 1=1';
      $filters = [];

      if(!empty(trim($_POST['id']))) {
        $sql .= ' AND `id` = :id';
        $filters[':id'] = $_POST['id'];
      }

      if(!empty(trim($_POST['street']))) {
        $sql .= ' AND `street` LIKE :street';
        $filters[':street'] = '%' . trim($_POST['street']) . '%';
      }

      if(!empty(trim($_POST['city']))) {
        $sql .= ' AND `city` LIKE :city';
        $filters[':city'] = '%' . trim($_POST['city']) . '%';
      }

      if(!empty(trim($_POST['postal_code']))) {
        $sql .= ' AND `postal_code` LIKE :postal_code';
        $filters[':postal_code'] = '%' . trim($_POST['postal_code']) . '%';
      }

      if(!empty(trim($_POST['country']))) {
        $sql .= ' AND `country` LIKE :country';
        $filters[':country'] = '%' . trim($_POST['country']) . '%';
      }

      $statement = $bdd->prepare($sql);
      $statement->execute($filters);
      
      echo '<table>';
      echo '<tr>';
      echo '<th> Location ID </th>';
      echo '<th> Street      </th>';
      echo '<th> City        </th>';
      echo '<th> Postal Code </th>';
      echo '<th> Country     </th>';
      echo '<th> Comment     </th>';
      echo '</tr>';
      while ($row = $statement->fetch()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']          ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($row['street']      ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($row['city']        ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($row['postal_code'] ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($row['country']     ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($row['comment']     ?? '') . '</td>';
        echo '</tr>';
      }
      echo '</table>';
    ?>
  </body>
</html>