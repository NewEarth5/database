<!DOCTYPE html>
<html>
  <head>
    <title>List of CD details</title>
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
        $_POST['cd_number']       = '';
        $_POST['title']           = '';
        $_POST['producer']        = '';
        $_POST['year']            = '';
        $_POST['duration_tot']    = '';
        $_POST['duration_max']    = '';
        $_POST['duration_min']    = '';
        $_POST['duration_avg']    = '';
        $_POST['playlist_amount'] = '';
        $_POST['genres']          = '';
      }

      echo '    <button class="collapsible"> Filters </button>'                                                                                                                                . "\n";
      echo '    <div class="content">'                                                                                                                                                         . "\n";
      echo '      <form method="post" action="cd.php">'                                                                                                                                        . "\n";
      echo '        <p>'                                                                                                                                                                       . "\n";
      echo '          <label for="cd_number">       CD ID:            </label>'                                                                                                                . "\n";
      echo '          <input type="number" id="cd_number"       name="cd_number"       value="' . htmlspecialchars(trim($_POST['cd_number']       ?? '')) .'" min="1" step="1">'               . "\n";
      echo '        </p>'                                                                                                                                                                      . "\n";
      echo '        <p>'                                                                                                                                                                       . "\n";
      echo '          <label for="title">           Title:            </label>'                                                                                                                . "\n";
      echo '          <input type="text"   id="title"           name="title"           value="' . htmlspecialchars(trim($_POST['title']           ?? '')) .'" maxlength="255">'                . "\n";
      echo '        </p>'                                                                                                                                                                      . "\n";
      echo '        <p>'                                                                                                                                                                       . "\n";
      echo '          <label for="producer">        Producer:         </label>'                                                                                                                . "\n";
      echo '          <input type="text"   id="producer"        name="producer"        value="' . htmlspecialchars(trim($_POST['producer']        ?? '')) .'" maxlength="255">'                . "\n";
      echo '        </p>'                                                                                                                                                                      . "\n";
      echo '        <p>'                                                                                                                                                                       . "\n";
      echo '          <label for="year">            Year:             </label>'                                                                                                                . "\n";
      echo '          <input type="number" id="year"            name="year"            value="' . htmlspecialchars(trim($_POST['year']            ?? '')) .'" min="1901" max="2155" step="1">' . "\n";
      echo '        </p>'                                                                                                                                                                      . "\n";
      echo '        <p>'                                                                                                                                                                       . "\n";
      echo '          <label for="duration_tot">    Total duration:   </label>'                                                                                                                . "\n";
      echo '          <input type="time"   id="duration_tot"    name="duration_tot"    value="' . htmlspecialchars(trim($_POST['duration_tot']    ?? '')) .'" step="1">'                       . "\n";
      echo '        </p>'                                                                                                                                                                      . "\n";
      echo '        <p>'                                                                                                                                                                       . "\n";
      echo '          <label for="duration_max">    Maximum duration: </label>'                                                                                                                . "\n";
      echo '          <input type="time"   id="duration_max"    name="duration_max"    value="' . htmlspecialchars(trim($_POST['duration_max']    ?? '')) .'" step="1">'                       . "\n";
      echo '        </p>'                                                                                                                                                                      . "\n";
      echo '        <p>'                                                                                                                                                                       . "\n";
      echo '          <label for="duration_min">    Minimum duration: </label>'                                                                                                                . "\n";
      echo '          <input type="time"   id="duration_min"    name="duration_min"    value="' . htmlspecialchars(trim($_POST['duration_min']    ?? '')) .'" step="1">'                       . "\n";
      echo '        </p>'                                                                                                                                                                      . "\n";
      echo '        <p>'                                                                                                                                                                       . "\n";
      echo '          <label for="duration_avg">    Average duration: </label>'                                                                                                                . "\n";
      echo '          <input type="time"   id="duration_avg"    name="duration_avg"    value="' . htmlspecialchars(trim($_POST['duration_avg']    ?? '')) .'" step="1">'                       . "\n";
      echo '        </p>'                                                                                                                                                                      . "\n";
      echo '        <p>'                                                                                                                                                                       . "\n";
      echo '          <label for="playlist_amount"> Playlist amount:  </label>'                                                                                                                . "\n";
      echo '          <input type="number" id="playlist_amount" name="playlist_amount" value="' . htmlspecialchars(trim($_POST['playlist_amount'] ?? '')) .'" min=0 step="1">'                 . "\n";
      echo '        </p>'                                                                                                                                                                      . "\n";
      echo '        <p>'                                                                                                                                                                       . "\n";
      echo '          <label for="genres">          Genres:           </label>'                                                                                                                . "\n";
      echo '          <input type="text"   id="genres"          name="genres"          value="' . htmlspecialchars(trim($_POST['genres']          ?? '')) .'">'                                . "\n";
      echo '        </p>'                                                                                                                                                                      . "\n";
      echo '        <input   type="submit" id="submit"          name="submit"          value="Filter">'                                                                                        . "\n";
      echo '        <input   type="submit" id="reset"           name="reset"           value="Reset">'                                                                                         . "\n";
      echo '      </form>'                                                                                                                                                                     . "\n";
      echo '    </div>'                                                                                                                                                                        . "\n";

      $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');
      $sql = 'WITH RECURSIVE
      SongStats (`cd_number`, `duration_tot`, `duration_max`, `duration_min`, `duration_avg`) AS (
          SELECT
            `cd_number`,
            SEC_TO_TIME(SUM(TIME_TO_SEC(`duration`))),
            MAX(`duration`),
            MIN(`duration`),
            SEC_TO_TIME(AVG(TIME_TO_SEC(`duration`)))
          FROM `Song`
          GROUP BY `cd_number`
        ),
        PlaylistCount (`cd_number`, `playlist_amount`) AS (
          SELECT
            `cd_number`,
            COUNT(*)
          FROM `Contains`
          GROUP BY `cd_number`
        ),
        GenreRecursive (`cd_number`, `genre_name`) AS (
          SELECT DISTINCT
            `cd_number`,
            `genre`
          FROM `Song`
          WHERE `genre` IS NOT NULL
          UNION
          SELECT
            `GenreRecursive`.`cd_number`,
            `Specializes`.`genre`
          FROM `GenreRecursive`
          JOIN `Specializes` ON `Specializes`.`subgenre` = `GenreRecursive`.`genre_name`
        ),
        GenreList (`cd_number`, `genres`) AS (
          SELECT
            `cd_number`,
            GROUP_CONCAT(DISTINCT `genre_name` ORDER BY `genre_name` SEPARATOR ", ")
          FROM `GenreRecursive`
          GROUP BY `cd_number`
        )
        SELECT
          `CD`.`cd_number`,
          `CD`.`title`,
          `CD`.`producer`,
          `CD`.`year`,
          COALESCE(`SongStats`.`duration_tot`,        "N/A") AS `duration_tot`,
          COALESCE(`SongStats`.`duration_max`,        "N/A") AS `duration_max`,
          COALESCE(`SongStats`.`duration_min`,        "N/A") AS `duration_min`,
          COALESCE(`SongStats`.`duration_avg`,        "N/A") AS `duration_avg`,
          COALESCE(`PlaylistCount`.`playlist_amount`, 0)     AS `playlist_amount`,
          COALESCE(`GenreList`.`genres`,              "N/A") AS `genres`
        FROM `CD`
        LEFT JOIN `SongStats`     ON `SongStats`.`cd_number`     = `CD`.`cd_number`
        LEFT JOIN `PlaylistCount` ON `PlaylistCount`.`cd_number` = `CD`.`cd_number`
        LEFT JOIN `GenreList`     ON `GenreList`.`cd_number`     = `CD`.`cd_number`
        WHERE 1=1';
      $filters = [];

      if(!empty(trim($_POST['cd_number']))) {
        $sql .= ' AND `CD`.`cd_number` = :cd_number';
        $filters[':cd_number'] = $_POST['cd_number'];
      }

      if(!empty(trim($_POST['title']))) {
        $sql .= ' AND `CD`.`title` LIKE :title';
        $filters[':title'] = '%' . trim($_POST['title']) . '%';
      }

      if(!empty(trim($_POST['producer']))) {
        $sql .= ' AND `CD`.`producer` LIKE :producer';
        $filters[':producer'] = '%' . trim($_POST['producer']) . '%';
      }

      if(!empty(trim($_POST['year']))) {
        $sql .= ' AND `CD`.`year` = :year';
        $filters[':year'] = $_POST['year'];
      }

      if(!empty(trim($_POST['duration_tot']))) {
        $sql .= ' AND COALESCE(`SongStats`.`duration_tot`, 0) = :duration_tot';
        $filters[':duration_tot'] = $_POST['duration_tot'];
      }

      if(!empty(trim($_POST['duration_max']))) {
        $sql .= ' AND COALESCE(`SongStats`.`duration_max`, 0) = :duration_max';
        $filters[':duration_max'] = $_POST['duration_max'];
      }

      if(!empty(trim($_POST['duration_min']))) {
        $sql .= ' AND COALESCE(`SongStats`.`duration_min`, 0) = :duration_min';
        $filters[':duration_min'] = $_POST['duration_min'];
      }

      if(!empty(trim($_POST['duration_avg']))) {
        $sql .= ' AND COALESCE(`SongStats`.`duration_avg`, 0) = :duration_avg';
        $filters[':duration_avg'] = $_POST['duration_avg'];
      }

      if(!empty(trim($_POST['playlist_amount']))) {
        $sql .= ' AND COALESCE(`PlaylistCount`.`playlist_amount`, 0) = :playlist_amount';
        $filters[':playlist_amount'] = $_POST['playlist_amount'];
      }

      if(!empty(trim($_POST['genres']))) {
        $sql .= ' AND `GenreList`.`genres` LIKE :genres';
        $filters[':genres'] = '%' . trim($_POST['genres']) . '%';
      }

      $statement = $bdd->prepare($sql);
      $statement->execute($filters);
      
      echo '    <table>' . "\n";
      echo '      <tr>' . "\n";
      echo '        <th> CD ID            </th>' . "\n";
      echo '        <th> Title            </th>' . "\n";
      echo '        <th> Producer         </th>' . "\n";
      echo '        <th> Year             </th>' . "\n";
      echo '        <th> Total duration   </th>' . "\n";
      echo '        <th> Maximum duration </th>' . "\n";
      echo '        <th> Minimum duration </th>' . "\n";
      echo '        <th> Average duration </th>' . "\n";
      echo '        <th> Playlist amount  </th>' . "\n";
      echo '        <th> Genres           </th>' . "\n";
      echo '      </tr>' . "\n";
      while ($row = $statement->fetch()) {
        echo '      <tr>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['cd_number']       ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['title']           ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['producer']        ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['year']            ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['duration_tot']    ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['duration_max']    ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['duration_min']    ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['duration_avg']    ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['playlist_amount'] ?? '')) . ' </td>' . "\n";
        echo '        <td> ' . htmlspecialchars(trim($row['genres']          ?? '')) . ' </td>' . "\n";
        echo '      </tr>' . "\n";
      }
      echo '    </table>' . "\n";
    ?>
    <script src="../javascript/see.js"></script>
  </body>
</html>
