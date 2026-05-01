<!DOCTYPE html>
<html>
  <head>
    <title> List of Locations </title>
    <link href="../css/see.css" type="text/css" rel="stylesheet" />
  </head>
  <body><?php
      echo "\n";
      echo '    <script>' . "\n";
      if (!isset($_POST['submit']) or isset($_POST['reset'])) {
        echo '      sessionStorage.setItem("filterMenuOpen", "false");' . "\n";
      } else {
        echo '      if (sessionStorage.getItem("filterMenuOpen") === "true") {' . "\n";
        echo '        const style = document.createElement("style");' . "\n";
        echo '        style.id = "temp-collapsible";' . "\n";
        echo '        style.textContent = " .content { max-height: none !important; transition: none !important; }";' . "\n";
        echo '        document.head.appendChild(style);' . "\n";
        echo '      }' . "\n";
      }
      echo '    </script>' . "\n";
      if(!isset($_POST['submit']) or isset($_POST['reset'])) {
        $_POST['id']          = '';
        $_POST['street']      = '';
        $_POST['city']        = '';
        $_POST['postal_code'] = '';
        $_POST['country']     = '';
        $_POST['comment']     = '';
      }

      echo '    <button class="collapsible"> Filters </button>'                                                                                                      . "\n";
      echo '    <div class="content">'                                                                                                                               . "\n";
      echo '      <form method="post" action="location.php">'                                                                                                        . "\n";
      echo '        <p>'                                                                                                                                             . "\n";
      echo '          <label for=id>          ID:          </label>'                                                                                                 . "\n";
      echo '          <input type="number" id="id"          name="id"          value="' . htmlspecialchars(trim($_POST['id']          ?? '')) .'" min="1" step="1">' . "\n";
      echo '        </p>'                                                                                                                                            . "\n";
      echo '        <p>'                                                                                                                                             . "\n";
      echo '          <label for=street>      Street:      </label>'                                                                                                 . "\n";
      echo '          <input type="text"   id="street"      name="street"      value="' . htmlspecialchars(trim($_POST['street']      ?? '')) .'" maxlength="255">'  . "\n";
      echo '        </p>'                                                                                                                                            . "\n";
      echo '        <p>'                                                                                                                                             . "\n";
      echo '          <label for=city>        City:        </label>'                                                                                                 . "\n";
      echo '          <input type="text"   id="city"        name="city"        value="' . htmlspecialchars(trim($_POST['city']        ?? '')) .'" maxlength="255">'  . "\n";
      echo '        </p>'                                                                                                                                            . "\n";
      echo '        <p>'                                                                                                                                             . "\n";
      echo '          <label for=postal_code> Postal code: </label>'                                                                                                 . "\n";
      echo '          <input type="text"   id="postal_code" name="postal_code" value="' . htmlspecialchars(trim($_POST['postal_code'] ?? '')) .'" maxlength="12">'   . "\n";
      echo '        </p>'                                                                                                                                            . "\n";
      echo '        <p>'                                                                                                                                             . "\n";
      echo '          <label for=country>     Country:     </label>'                                                                                                 . "\n";
      echo '          <input type="text"   id="country"     name="country"     value="' . htmlspecialchars(trim($_POST['country']     ?? '')) .'" maxlength="255">'  . "\n";
      echo '        </p>'                                                                                                                                            . "\n";
      echo '        <input   type="submit" id="submit"      name="submit"      value="Filter">'                                                                      . "\n";
      echo '        <input   type="submit" id="reset"       name="reset"       value="Reset">'                                                                       . "\n";
      echo '      </form>'                                                                                                                                           . "\n";
      echo '    </div>'                                                                                                                                              . "\n";

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
    <script src="../javascript/see.js"></script>
  </body>
</html>