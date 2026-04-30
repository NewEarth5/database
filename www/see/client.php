<!DOCTYPE html>
<html>
  <head>
    <title>List of Clients</title>
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
        $_POST['client_number'] = '';
        $_POST['first_name'] = '';
        $_POST['last_name'] = '';
        $_POST['email_address'] = '';
        $_POST['phone_number'] = '';
      }

      echo '    <form method="post" action="client.php">' . "\n";
      echo '      <input type="number" name="client_number" value="' . trim($_POST['client_number']) .'" placeholder="Client ID"    min="1" step="1">' . "\n";
      echo '      <input type="text"   name="first_name"    value="' . trim($_POST['first_name'])    .'" placeholder="First name"   maxlength="255">'  . "\n";
      echo '      <input type="text"   name="last_name"     value="' . trim($_POST['last_name'])     .'" placeholder="Last name"    maxlength="255">'  . "\n";
      echo '      <input type="text"   name="email_address" value="' . trim($_POST['email_address']) .'" placeholder="Email"        maxlength="255">'  . "\n";
      echo '      <input type="tel"    name="phone_number"  value="' . trim($_POST['phone_number'])  .'" placeholder="Phone number" maxlength="20">'   . "\n";
      echo '      <input type="submit" name="submit"        value="Filter">'                                                                           . "\n";
      echo '      <input type="submit" name="reset"         value="Reset">'                                                                            . "\n";
      echo '    </form>' . "\n";

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
      
      echo "\n";
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
        echo '        <td> ' . htmlspecialchars($row['client_number'] ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['first_name']    ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['last_name']     ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['email_address'] ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['phone_number']  ?? '') . ' </td>' . "\n";
        echo '      </tr>' . "\n";
      }
      echo '    </table>' . "\n";
    ?>
  </body>
</html>