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
      $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');

      if (isset($_POST['delete'])) {
        try {
          $bdd->beginTransaction();
          $sql_delete = 'DELETE FROM `Song` WHERE `cd_number` = :cd_number AND `track_number` = :track_number';
          $params_delete[':cd_number']    = $_POST['cd'];
          $params_delete[':track_number'] = $_POST['song'];
          $req_delete = $bdd->prepare($sql_delete);
          $req_delete->execute($params_delete);
          $bdd->commit();
        } catch (\PDOException $error) {
          $bdd->rollBack();
          echo '    <script> alert("Error: ' . htmlspecialchars($error->getMessage()) . '") </script>';
        }
      }

      if (isset($_POST['save_edit'])) {
        try {
          $bdd->beginTransaction();
          $sql_save_edit = 'UPDATE `Song` SET `title` = :title, `artist` = :artist, `duration` = :duration, `genre` = :genre WHERE `cd_number` = :cd_number and `track_number` = :track_number';
          $params_save_edit[':cd_number']    = $_POST['cd'];
          $params_save_edit[':track_number'] = $_POST['song'];
          $params_save_edit[':title']        = $_POST['title'];
          $params_save_edit[':artist']       = $_POST['artist'];
          $params_save_edit[':duration']     = $_POST['duration'];
          $params_save_edit[':genre']        = $_POST['song_genre'];
          $req_save_edit = $bdd->prepare($sql_save_edit);
          $req_save_edit->execute($params_save_edit);
          $bdd->commit();
        } catch (\PDOException $error) {
          $bdd->rollBack();
          echo '    <script> alert("Error: ' . htmlspecialchars($error->getMessage()) . '") </script>';
        }
      }

      if (isset($_POST['save_add'])) {
        try {
          $bdd->beginTransaction();
          $sql_save_add = 'INSERT INTO `Song` (`cd_number`, `track_number`, `title`, `artist`, `duration`, `genre`) VALUES (:cd_number, :track_number, :title, :artist, :duration, :genre)';
          $params_save_add[':cd_number']    = $_POST['cd'];
          $params_save_add[':track_number'] = $_POST['track_number'];
          $params_save_add[':title']        = $_POST['title'];
          $params_save_add[':artist']       = $_POST['artist'];
          $params_save_add[':duration']     = $_POST['duration'];
          $params_save_add[':genre']        = $_POST['song_genre'];
          $req_save_add = $bdd->prepare($sql_save_add);
          $req_save_add->execute($params_save_add);
          $bdd->commit();
        } catch (\PDOException $error) {
            $bdd->rollBack();
          if ($error->errorInfo[0] == '23000' and $error->errorInfo[1] == 1062) {
            echo '    <script> alert("A song already has this track number on this CD.") </script>';
          } else {
            echo '    <script> alert("Error: ' . htmlspecialchars($error->getMessage()) . '") </script>';
          }
          $_POST['add'] = True;
        }
      }

      $sql_cd = 'SELECT * FROM `CD`';
      $req_cd = $bdd->query($sql_cd);
      $rows_cd = $req_cd->fetchAll();
      $amount_cd = count($rows_cd);

      if ($amount_cd > 0) {
        echo '    <form method="post" action="song.php">' . "\n";
        echo '      <select name="cd">' . "\n";
        foreach ($rows_cd as $row) {
          echo '        <option value="' . $row['cd_number'] . '"';
          if(isset($_POST['cd']) and $row['cd_number'] == $_POST['cd']) echo ' selected';
          echo '> ' . htmlspecialchars(trim($row['producer'] ?? '')) . ' | ' . htmlspecialchars(trim($row['title'] ?? '')) . ' - ' . htmlspecialchars(trim($row['year'] ?? '')) . ' (' . htmlspecialchars(trim($row['copies'] ?? '')) . ') </option>' . "\n";
        }
        echo '      </select>' . "\n";
        echo '      <input type="submit" name="cd_submit" value="Select">' . "\n";
        echo '    </form>' . "\n";

        if (isset($_POST['cd'])) {
          $sql_song = 'SELECT * FROM `Song` WHERE `cd_number` = :cd_number';
          $params_song[':cd_number'] = $_POST['cd'];
          $req_song = $bdd->prepare($sql_song);
          $req_song->execute($params_song);
          $rows_song = $req_song->fetchAll();
          $amount_song = count($rows_song);

          echo "\n";
          echo '    <form method="post" action="song.php">' . "\n";
          if ($amount_song > 0) {
            echo '      <select name="song">' . "\n";
            foreach ($rows_song as $row) {
              echo '        <option value="' . $row['track_number'] . '"';
              if(isset($_POST['song']) and $row['track_number'] == $_POST['song']) echo ' selected';
              echo '> ' . htmlspecialchars(trim($row['artist'] ?? '')) . ' | ' . htmlspecialchars(trim($row['title'] ?? '')) . ' - ' . htmlspecialchars(trim($row['genre'] ?? '')) . ' (' . htmlspecialchars(trim($row['duration'] ?? '')) . ') </option>' . "\n";
            }
            echo '      </select>' . "\n";
            echo '      <input type="submit" name="delete" value="Delete">' . "\n";
            echo '      <input type="submit" name="edit"   value="Edit">'   . "\n";
          } else {
            echo '      The CD currently has no songs assigned to it' . "\n";
          }
          echo '      <input type="submit" name="add"    value="Add">' . "\n";
          echo '      <input type="hidden" name="cd"     value="' . $_POST['cd'] . '">' . "\n";
          echo '    </form>' . "\n";

          if (isset($_POST['edit']) or isset($_POST['add'])) {
            $sql_genre = 'SELECT * FROM `Genre`';
            $req_genre = $bdd->query($sql_genre);
            $rows_genre = $req_genre->fetchAll();
            $amount_genre = count($rows_genre);
          }

          if (isset($_POST['edit'])) {
            $sql_edit = 'SELECT * FROM `Song` WHERE `cd_number` = :cd_number AND `track_number` = :track_number';
            $params_edit[':cd_number'] = $_POST['cd'];
            $params_edit[':track_number'] = $_POST['song'];
            $req_edit = $bdd->prepare($sql_edit);
            $req_edit->execute($params_edit);
            $song = $req_edit->fetch();

            echo '    <form method="post" action="song.php">' . "\n";
            echo '      <input type="text" name="artist"   value="' . htmlspecialchars(trim($song['artist']   ?? '')) . '" placeholder="Artist"   maxlength="255">' . "\n";
            echo '      <input type="text" name="title"    value="' . htmlspecialchars(trim($song['title']    ?? '')) . '"  placeholder="Title"    maxlength="255">' . "\n";
            echo '      <input type="time" name="duration" value="' . htmlspecialchars(trim($song['duration'] ?? '')) . '" placeholder="Duration">'                 . "\n";
            if ($amount_genre > 0) {
              echo '      <select name="song_genre">' . "\n";
              foreach ($rows_genre as $row) {
                echo '        <option value="' . $row["name"] . '"';
                if ($row['name'] == $song['genre']) echo ' selected';
                echo '> ' . htmlspecialchars(trim($row['name'] ?? '')) . '</option>' . "\n";
              }
              echo '      </select>' . "\n";
            }
            echo '      <input type="submit" name="save_edit" value="Save">' . "\n";
            echo '      <input type="hidden" name="cd"   value="' . $_POST['cd']   . '">' . "\n";
            echo '      <input type="hidden" name="song" value="' . $_POST['song'] . '">' . "\n";
            echo '    </form>' . "\n";
          }

          if (isset($_POST['add'])) {
            echo '    <form method="post" action="song.php">' . "\n";
            echo '      <input type="number" name="track_number" value="' . htmlspecialchars(trim($_POST['track_number'] ?? '')) . '" placeholder="Track number" min="1" step="1">' . "\n";
            echo '      <input type="text"   name="artist"       value="' . htmlspecialchars(trim($_POST['artist']       ?? '')) . '" placeholder="Artist"       maxlength="255">'  . "\n";
            echo '      <input type="text"   name="title"        value="' . htmlspecialchars(trim($_POST['title']        ?? '')) . '" placeholder="Title"        maxlength="255">'  . "\n";
            echo '      <input type="time"   name="duration"     value="' . htmlspecialchars(trim($_POST['duration']     ?? '')) . '" placeholder="Duration">'                      . "\n";
            if ($amount_genre > 0) {
              echo '      <select name="song_genre">' . "\n";
              foreach ($rows_genre as $row) {
                echo '        <option value="' . $row["name"] . '"';
                if (isset($_POST['song_genre']) and $row['name'] == $_POST['song_genre']) echo ' selected';
                echo '> ' . htmlspecialchars(trim($row['name'] ?? '')) . '</option>' . "\n";
              }
              echo '      </select>' . "\n";
            }
            echo '      <input type="submit" name="save_add" value="Save">' . "\n";
            echo '      <input type="hidden" name="cd"   value="' . $_POST['cd']   . '">' . "\n";
            echo '    </form>' . "\n";
          }
        }
      } else {
        echo '    There are currently no CDs' . "\n";
      }
    ?>
  </body>
</html>