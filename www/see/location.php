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
      echo "\n";
      if(!isset($_POST['submit']) or isset($_POST['reset'])) {
        $_POST['id'] = '';
        $_POST['street'] = '';
        $_POST['city'] = '';
        $_POST['postal_code'] = '';
        $_POST['country'] = '';
        $_POST['comment'] = '';
      }

      echo '    <form method="post" action="location.php">' . "\n";
      echo '      <input type="number" name="id"          value="' . htmlspecialchars(trim($_POST['id']          ?? '')) .'" placeholder="Location ID" min="1" step="1">' . "\n";
      echo '      <input type="text"   name="street"      value="' . htmlspecialchars(trim($_POST['street']      ?? '')) .'" placeholder="Steet"       maxlength="255">'  . "\n";
      echo '      <input type="text"   name="city"        value="' . htmlspecialchars(trim($_POST['city']        ?? '')) .'" placeholder="City"        maxlength="255">'  . "\n";
      echo '      <input type="text"   name="postal_code" value="' . htmlspecialchars(trim($_POST['postal_code'] ?? '')) .'" placeholder="Postal Code" maxlength="12">'   . "\n";
      echo '      <input type="text"   name="country"     value="' . htmlspecialchars(trim($_POST['country']     ?? '')) .'" placeholder="Country"     maxlength="255">'  . "\n";
      echo '      <input type="submit" name="submit"      value="Filter">'                                                                        . "\n";
      echo '      <input type="submit" name="reset"       value="Reset">'                                                                         . "\n";
      echo '    </form>' . "\n";

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
      
      echo "\n";
      echo '    <table>' . "\n";
      echo '      <tr>' . "\n";
      echo '        <th> Location ID </th>' . "\n";
      echo '        <th> Street      </th>' . "\n";
      echo '        <th> City        </th>' . "\n";
      echo '        <th> Postal Code </th>' . "\n";
      echo '        <th> Country     </th>' . "\n";
      echo '        <th> Comment     </th>' . "\n";
      echo '      </tr>' . "\n";
      while ($row = $statement->fetch()) {
        echo '      <tr>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['id']          ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['street']      ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['city']        ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['postal_code'] ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['country']     ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['comment']     ?? '')) . ' </td>' . "\n";
        echo '      </tr>' . "\n";
      }
      echo '    </table>' . "\n";
    ?>
  </body>
</html>