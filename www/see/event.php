<!DOCTYPE html>
<html>
  <head>
    <title>List of Events</title>
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
        $_POST['name'] = '';
        $_POST['date'] = '';
        $_POST['client'] = '';
        $_POST['manager'] = '';
        $_POST['event_planner'] = '';
        $_POST['dj'] = '';
        $_POST['theme'] = '';
        $_POST['type'] = '';
        $_POST['location'] = '';
        $_POST['rental_fee'] = '';
        $_POST['playlist'] = '';
      }

      echo '    <form method="post" action="event.php">' . "\n";
      echo '      <input type="number" name="id"            value="' . trim($_POST['id'])            .'" placeholder="Event ID"         min="1" step="1">'                  . "\n";
      echo '      <input type="text"   name="name"          value="' . trim($_POST['name'])          .'" placeholder="Name"             maxlength="255">'                   . "\n";
      echo '      <input type="date"   name="date"          value="' . trim($_POST['date'])          .'" placeholder="Date">'                                               . "\n";
      echo '      <input type="number" name="client"        value="' . trim($_POST['client'])        .'" placeholder="Client ID"        min="1" step="1">'                  . "\n";
      echo '      <input type="number" name="manager"       value="' . trim($_POST['manager'])       .'" placeholder="Manager ID"       min="1" step="1">'                  . "\n";
      echo '      <input type="number" name="event_planner" value="' . trim($_POST['event_planner']) .'" placeholder="Event planner ID" min="1" step="1">'                  . "\n";
      echo '      <input type="number" name="dj"            value="' . trim($_POST['dj'])            .'" placeholder="DJ ID"            min="1" step="1">'                  . "\n";
      echo '      <input type="text"   name="theme"         value="' . trim($_POST['theme'])         .'" placeholder="Theme"            maxlength="255">'                   . "\n";
      echo '      <input type="text"   name="type"          value="' . trim($_POST['type'])          .'" placeholder="Type"             maxlength="255">'                   . "\n";
      echo '      <input type="number" name="location"      value="' . trim($_POST['location'])      .'" placeholder="Location ID"      min="1" step="1">'                  . "\n";
      echo '      <input type="number" name="rental_fee"    value="' . trim($_POST['rental_fee'])    .'" placeholder="Rental fee"       min="0" max="9999999" step="0.01">' . "\n";
      echo '      <input type="text"   name="playlist"      value="' . trim($_POST['playlist'])      .'" placeholder="Playlist"         maxlength="255">'                   . "\n";
      echo '      <input type="submit" name="submit"        value="Filter">'                                                                                                . "\n";
      echo '      <input type="submit" name="reset"         value="Reset">'                                                                                                 . "\n";
      echo '    </form>' . "\n";

      $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');

      $sql = 'SELECT * FROM `Event` WHERE 1=1';
      $filters = [];

      if(!empty(trim($_POST['id']))) {
        $sql .= ' AND `id` = :id';
        $filters[':id'] = $_POST['id'];
      }

      if(!empty(trim($_POST['name']))) {
        $sql .= ' AND `name` LIKE :name';
        $filters[':name'] = '%' . trim($_POST['name']) . '%';
      }

      if(!empty(trim($_POST['date']))) {
        $sql .= ' AND `date` = :date';
        $filters[':date'] = $_POST['date'];
      }

      if(!empty(trim($_POST['client']))) {
        $sql .= ' AND `client` = :client';
        $filters[':client'] = $_POST['client'];
      }

      if(!empty(trim($_POST['manager']))) {
        $sql .= ' AND `manager` = :manager';
        $filters[':manager'] = $_POST['manager'];
      }

      if(!empty(trim($_POST['event_planner']))) {
        $sql .= ' AND `event_planner` = :event_planner';
        $filters[':event_planner'] = $_POST['event_planner'];
      }

      if(!empty(trim($_POST['dj']))) {
        $sql .= ' AND `dj` = :dj';
        $filters[':dj'] = $_POST['dj'];
      }

      if(!empty(trim($_POST['theme']))) {
        $sql .= ' AND `theme` LIKE :theme';
        $filters[':theme'] = '%' . trim($_POST['theme']) . '%';
      }

      if(!empty(trim($_POST['type']))) {
        $sql .= ' AND `type` LIKE :type';
        $filters[':type'] = '%' . trim($_POST['type']) . '%';
      }

      if(!empty(trim($_POST['location']))) {
        $sql .= ' AND `location` = :location';
        $filters[':location'] = $_POST['location'];
      }

      if(!empty(trim($_POST['rental_fee']))) {
        $sql .= ' AND `rental_fee` = :rental_fee';
        $filters[':rental_fee'] = $_POST['rental_fee'];
      }

      if(!empty(trim($_POST['playlist']))) {
        $sql .= ' AND `playlist` LIKE :playlist';
        $filters[':playlist'] = '%' . trim($_POST['playlist']) . '%';
      }

      $statement = $bdd->prepare($sql);
      $statement->execute($filters);
      
      echo "\n";
      echo '    <table>';
      echo '      <tr>';
      echo '        <th> Event ID         </th>' . "\n";
      echo '        <th> Name             </th>' . "\n";
      echo '        <th> Date             </th>' . "\n";
      echo '        <th> Description      </th>' . "\n";
      echo '        <th> Client ID        </th>' . "\n";
      echo '        <th> Manager ID       </th>' . "\n";
      echo '        <th> Event planner ID </th>' . "\n";
      echo '        <th> DJ ID            </th>' . "\n";
      echo '        <th> Theme            </th>' . "\n";
      echo '        <th> Type             </th>' . "\n";
      echo '        <th> Location ID      </th>' . "\n";
      echo '        <th> Rental fee       </th>' . "\n";
      echo '        <th> Playlist         </th>' . "\n";
      echo '      </tr>' . "\n";
      while ($row = $statement->fetch()) {
        echo '      <tr>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['id']            ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['name']          ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['date']          ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['description']   ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['client']        ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['manager']       ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['event_planner'] ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['dj']            ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['theme']         ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['type']          ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['location']      ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['rental_fee']    ?? '') . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars($row['playlist']      ?? '') . ' </td>' . "\n";
        echo '      </tr>' . "\n";
      }
      echo '    </table>' . "\n";
    ?>
  </body>
</html>