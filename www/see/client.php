<!DOCTYPE html>
<html>
  <head>
    <title> List of Clients </title>
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
        $_POST['client_number'] = '';
        $_POST['first_name']    = '';
        $_POST['last_name']     = '';
        $_POST['email_address'] = '';
        $_POST['phone_number']  = '';
      }

      echo '    <button class="collapsible"> Filters </button>'                                                                                                            . "\n";
      echo '    <div class="content">'                                                                                                                                     . "\n";
      echo '      <form method="post" action="client.php">'                                                                                                                . "\n";
      echo '        <p>'                                                                                                                                                   . "\n";
      echo '          <label for=client_number> Client ID:  </label>'                                                                                                      . "\n";
      echo '          <input type="number" id="client_number" name="client_number" value="' . htmlspecialchars(trim($_POST['client_number'] ?? '')) .'" min="1" step="1">' . "\n";
      echo '        </p>'                                                                                                                                                  . "\n";
      echo '        <p>'                                                                                                                                                   . "\n";
      echo '          <label for=first_name>    First name: </label>'                                                                                                      . "\n";
      echo '          <input type="text"   id="first_name"    name="first_name"    value="' . htmlspecialchars(trim($_POST['first_name']    ?? '')) .'" maxlength="255">'  . "\n";
      echo '        </p>'                                                                                                                                                  . "\n";
      echo '        <p>'                                                                                                                                                   . "\n";
      echo '          <label for=last_name>     Last name:  </label>'                                                                                                      . "\n";
      echo '          <input type="text"   id="last_name"     name="last_name"     value="' . htmlspecialchars(trim($_POST['last_name']     ?? '')) .'" maxlength="255">'  . "\n";
      echo '        </p>'                                                                                                                                                  . "\n";
      echo '        <p>'                                                                                                                                                   . "\n";
      echo '          <label for=email_address> Email:      </label>'                                                                                                      . "\n";
      echo '          <input type="text"   id="email_address" name="email_address" value="' . htmlspecialchars(trim($_POST['email_address'] ?? '')) .'" maxlength="255">'  . "\n";
      echo '        </p>'                                                                                                                                                  . "\n";
      echo '        <p>'                                                                                                                                                   . "\n";
      echo '          <label for=phone_number>  Phone:      </label>'                                                                                                      . "\n";
      echo '          <input type="tel"    id="phone_number"  name="phone_number"  value="' . htmlspecialchars(trim($_POST['phone_number']  ?? '')) .'" maxlength="20">'   . "\n";
      echo '        </p>'                                                                                                                                                  . "\n";
      echo '        <input   type="submit" id="submit"        name="submit"        value="Filter">'                                                                        . "\n";
      echo '        <input   type="submit" id="reset"         name="reset"         value="Reset">'                                                                         . "\n";
      echo '      </form>'                                                                                                                                                 . "\n";
      echo '    </div>'                                                                                                                                                    . "\n";

      $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');

      $sql = 'SELECT * FROM `Client` WHERE 1=1';
      $filters = [];

      if(!empty(trim($_POST['client_number']))) {
        $sql .= ' AND `client_number` = :client_number';
        $filters[':client_number'] = $_POST['client_number'];
      }

      if(!empty(trim($_POST['first_name']))) {
        $sql .= ' AND `first_name` LIKE :first_name';
        $filters[':first_name'] = '%' . trim($_POST['first_name']) . '%';
      }

      if(!empty(trim($_POST['last_name']))) {
        $sql .= ' AND `last_name` LIKE :last_name';
        $filters[':last_name'] = '%' . trim($_POST['last_name']) . '%';
      }

      if(!empty(trim($_POST['email_address']))) {
        $sql .= ' AND `email_address` LIKE :email_address';
        $filters[':email_address'] = '%' . trim($_POST['email_address']) . '%';
      }

      if(!empty(trim($_POST['phone_number']))) {
        $sql .= ' AND `phone_number` LIKE :phone_number';
        $filters[':phone_number'] = '%' . trim($_POST['phone_number']) . '%';
      }

      $statement = $bdd->prepare($sql);
      $statement->execute($filters);
      
      echo '    <table>' . "\n";
      echo '      <tr>' . "\n";
      echo '        <th> Client ID    </th>' . "\n";
      echo '        <th> First name   </th>' . "\n";
      echo '        <th> Last name    </th>' . "\n";
      echo '        <th> Email        </th>' . "\n";
      echo '        <th> Phone number </th>' . "\n";
      echo '      </tr>' . "\n";
      while ($row = $statement->fetch()) {
        echo '      <tr>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['client_number'] ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['first_name']    ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['last_name']     ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['email_address'] ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['phone_number']  ?? '')) . ' </td>' . "\n";
        echo '      </tr>' . "\n";
      }
      echo '    </table>' . "\n";
    ?>
    <script src="../javascript/see.js"></script>
  </body>
</html>