<!DOCTYPE html>
<html>
  <head>
    <title> List of Events </title>
    <link href="../css/see.css" type="text/css" rel="stylesheet" />
  </head>
  <body><?php
      echo "\n";
      echo '    <script>' . "\n";
      if (!isset($_POST['submit']) or isset($_POST['reset'])) {
        echo '      sessionStorage.setItem("filterMenuOpen", "false");' . "\n";
      } else {
        echo '      if (sessionStorage.getItem("filterMenuOpen") === "true") {' . "\n";
        echo '        document.write(\'<style id="temp-collapsible"> .content { max-height: none !important; transition: none !important; } </style>\');' . "\n";
        echo '      }' . "\n";
      }
      echo '    </script>' . "\n";
      if(!isset($_POST['submit']) or isset($_POST['reset'])) {
        $_POST['id']            = '';
        $_POST['name']          = '';
        $_POST['date']          = '';
        $_POST['client']        = '';
        $_POST['manager']       = '';
        $_POST['event_planner'] = '';
        $_POST['dj']            = '';
        $_POST['theme']         = '';
        $_POST['type']          = '';
        $_POST['location']      = '';
        $_POST['rental_fee']    = '';
        $_POST['playlist']      = '';
      }

      echo '    <button class="collapsible"> Filters </button>'                                                                                                                             . "\n";
      echo '    <div class="content">'                                                                                                                                                      . "\n";
      echo '      <form method="post" action="event.php">'                                                                                                                                  . "\n";
      echo '        <p>'                                                                                                                                                                    . "\n";
      echo '          <label for=id>            ID:               </label>'                                                                                                                 . "\n";
      echo '          <input type="number" id="id"            name="id"            value="' . htmlspecialchars(trim($_POST['id']            ?? '')) .'" min="1" step="1">'                  . "\n";
      echo '        </p>'                                                                                                                                                                   . "\n";
      echo '        <p>'                                                                                                                                                                    . "\n";
      echo '          <label for=name>          Name:             </label>'                                                                                                                 . "\n";
      echo '          <input type="text"   id="name"          name="name"          value="' . htmlspecialchars(trim($_POST['name']          ?? '')) .'" maxlength="255">'                   . "\n";
      echo '        </p>'                                                                                                                                                                   . "\n";
      echo '        <p>'                                                                                                                                                                    . "\n";
      echo '          <label for=date>          Date:             </label>'                                                                                                                 . "\n";
      echo '          <input type="date"   id="date"          name="date"          value="' . htmlspecialchars(trim($_POST['date']          ?? '')) .'" >'                                  . "\n";
      echo '        </p>'                                                                                                                                                                   . "\n";
      echo '        <p>'                                                                                                                                                                    . "\n";
      echo '          <label for=client>        Client ID:        </label>'                                                                                                                 . "\n";
      echo '          <input type="number" id="client"        name="client"        value="' . htmlspecialchars(trim($_POST['client']        ?? '')) .'" min="1" step="1">'                  . "\n";
      echo '        </p>'                                                                                                                                                                   . "\n";
      echo '        <p>'                                                                                                                                                                    . "\n";
      echo '          <label for=manager>       Manager ID:       </label>'                                                                                                                 . "\n";
      echo '          <input type="number" id="manager"       name="manager"       value="' . htmlspecialchars(trim($_POST['manager']       ?? '')) .'" min="1" step="1">'                  . "\n";
      echo '        </p>'                                                                                                                                                                   . "\n";
      echo '        <p>'                                                                                                                                                                    . "\n";
      echo '          <label for=event_planner> Event planner ID: </label>'                                                                                                                 . "\n";
      echo '          <input type="number" id="event_planner" name="event_planner" value="' . htmlspecialchars(trim($_POST['event_planner'] ?? '')) .'" min="1" step="1">'                  . "\n";
      echo '        </p>'                                                                                                                                                                   . "\n";
      echo '        <p>'                                                                                                                                                                    . "\n";
      echo '          <label for=dj>            DJ ID:            </label>'                                                                                                                 . "\n";
      echo '          <input type="number" id="dj"            name="dj"            value="' . htmlspecialchars(trim($_POST['dj']            ?? '')) .'" min="1" step="1">'                  . "\n";
      echo '        </p>'                                                                                                                                                                   . "\n";
      echo '        <p>'                                                                                                                                                                    . "\n";
      echo '          <label for=theme>         Theme:            </label>'                                                                                                                 . "\n";
      echo '          <input type="text"   id="theme"         name="theme"         value="' . htmlspecialchars(trim($_POST['theme']         ?? '')) .'" maxlength="255">'                   . "\n";
      echo '        </p>'                                                                                                                                                                   . "\n";
      echo '        <p>'                                                                                                                                                                    . "\n";
      echo '          <label for=type>          Type:             </label>'                                                                                                                 . "\n";
      echo '          <input type="text"   id="type"          name="type"          value="' . htmlspecialchars(trim($_POST['type']          ?? '')) .'" maxlength="255">'                   . "\n";
      echo '        </p>'                                                                                                                                                                   . "\n";
      echo '        <p>'                                                                                                                                                                    . "\n";
      echo '          <label for=location>      Location ID:      </label>'                                                                                                                 . "\n";
      echo '          <input type="number" id="location"      name="location"      value="' . htmlspecialchars(trim($_POST['location']      ?? '')) .'" min="1" step="1">'                  . "\n";
      echo '        </p>'                                                                                                                                                                   . "\n";
      echo '        <p>'                                                                                                                                                                    . "\n";
      echo '          <label for=rental_fee>    Rental fee:       </label>'                                                                                                                 . "\n";
      echo '          <input type="number" id="rental_fee"    name="rental_fee"    value="' . htmlspecialchars(trim($_POST['rental_fee']    ?? '')) .'" min="0" max="9999999" step="0.01">' . "\n";
      echo '        </p>'                                                                                                                                                                   . "\n";
      echo '        <p>'                                                                                                                                                                    . "\n";
      echo '          <label for=playlist>      Playlist:         </label>'                                                                                                                 . "\n";
      echo '          <input type="text"   id="playlist"      name="playlist"      value="' . htmlspecialchars(trim($_POST['playlist']      ?? '')) .'" maxlength="255">'                   . "\n";
      echo '        </p>'                                                                                                                                                                   . "\n";
      echo '        <input   type="submit" id="submit"        name="submit"        value="Filter">'                                                                                         . "\n";
      echo '        <input   type="submit" id="reset"         name="reset"         value="Reset">'                                                                                          . "\n";
      echo '      </form>'                                                                                                                                                                  . "\n";
      echo '    </div>'                                                                                                                                                                     . "\n";

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
        echo '        <td> ' . htmlspecialchars(trim($row['id']            ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['name']          ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['date']          ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['description']   ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['client']        ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['manager']       ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['event_planner'] ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['dj']            ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['theme']         ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['type']          ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['location']      ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['rental_fee']    ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['playlist']      ?? '')) . ' </td>' . "\n";
        echo '      </tr>' . "\n";
      }
      echo '    </table>' . "\n";
    ?>
    <script src="../javascript/see.js"></script>
  </body>
</html>