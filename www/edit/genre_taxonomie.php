<!DOCTYPE html>
<html>
  <head>
    <title> Genre Taxonomy </title>
    <link rel="stylesheet" href="../css/see.css">
    <style>
      .form-container { margin: 20px; padding: 20px; border: 1px solid #ccc; background: #f9f9f9; border-radius: 8px; max-width: 600px; }
      .form-group { margin-bottom: 15px; }
      label { display: block; font-weight: bold; margin-bottom: 5px; }
      textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-family: monospace; font-size: 14px; }
      button { padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; border-radius: 4px; font-size: 16px; margin-top: 10px; }
      button:hover { background-color: #218838; }
      .message-success { color: green; margin: 10px 0; }
      .message-error   { color: red;   margin: 10px 0; }
    </style>
  </head>
  <body><?php
      echo "\n";
      $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');
      $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $messages = [];

      if (isset($_POST['save_taxonomy'])) {
          $textarea = trim($_POST['taxonomy']);
          $lines    = explode("\n", $textarea);
          $pairs    = [];
          $errors   = [];

          foreach ($lines as $i => $line) {
              $line = trim($line);
              if (empty($line)) continue;

              $parts = explode(",", $line);
              if (count($parts) !== 2) {
                  $errors[] = "Ligne " . ($i + 1) . " ignorée (format invalide) : \"" . htmlspecialchars($line) . "\"";
                  continue;
              }

              $subgenre = trim($parts[0]);
              $genre    = trim($parts[1]);

              if (empty($subgenre) || empty($genre)) {
                  $errors[] = "Ligne " . ($i + 1) . " ignorée (valeur vide) : \"" . htmlspecialchars($line) . "\"";
                  continue;
              }

              if ($subgenre === $genre) {
                  $errors[] = "Ligne " . ($i + 1) . " ignorée (un genre ne peut pas être son propre parent) : \"" . htmlspecialchars($line) . "\"";
                  continue;
              }

              $pairs[] = ['subgenre' => $subgenre, 'genre' => $genre];
          }

          $validPairs = [];
          foreach ($pairs as $pair) {
              $hasCycle = false;
              foreach ($pairs as $other) {
                  if ($pair['subgenre'] === $other['genre'] && $pair['genre'] === $other['subgenre']) {
                      $hasCycle = true;
                      break;
                  }
              }
              if ($hasCycle) {
                  $errors[] = "Cycle détecté et ignoré : \"" . htmlspecialchars($pair['subgenre']) . "\" ↔ \"" . htmlspecialchars($pair['genre']) . "\"";
              } else {
                  $validPairs[] = $pair;
              }
          }

          if (!empty($validPairs)) {
              try {
                  $bdd->beginTransaction();

                  $checkGenre  = $bdd->prepare("SELECT COUNT(*) FROM Genre WHERE name = ?");
                  $insertGenre = $bdd->prepare("INSERT IGNORE INTO Genre (name) VALUES (?)");
                  $checkSpec   = $bdd->prepare("SELECT COUNT(*) FROM Specializes WHERE subgenre = ? AND genre = ?");
                  $checkCycleDB = $bdd->prepare("SELECT COUNT(*) FROM Specializes WHERE subgenre = ? AND genre = ?");
                  $insertSpec  = $bdd->prepare("INSERT INTO Specializes (subgenre, genre) VALUES (?, ?)");

                  $inserted = 0;
                  $skipped  = 0;

                  foreach ($validPairs as $pair) {
                      $subgenre = $pair['subgenre'];
                      $genre    = $pair['genre'];

                      $insertGenre->execute([$subgenre]);
                      $insertGenre->execute([$genre]);

                      $checkCycleDB->execute([$genre, $subgenre]);
                      if ($checkCycleDB->fetchColumn() > 0) {
                          $errors[] = "Cycle avec la BDD ignoré : \"" . htmlspecialchars($subgenre) . "\" → \"" . htmlspecialchars($genre) . "\" (relation inverse déjà existante)";
                          $skipped++;
                          continue;
                      }

                      $checkSpec->execute([$subgenre, $genre]);
                      if ($checkSpec->fetchColumn() > 0) {
                          $skipped++;
                          continue;
                      }

                      $insertSpec->execute([$subgenre, $genre]);
                      $inserted++;
                  }

                  $bdd->commit();

                  if ($inserted > 0) {
                      $messages[] = ['type' => 'success', 'text' => "$inserted relation(s) insérée(s) avec succès."];
                  }
                  if ($skipped > 0) {
                      $messages[] = ['type' => 'success', 'text' => "$skipped relation(s) déjà existante(s) ignorée(s)."];
                  }

              } catch (\PDOException $error) {
                  if ($bdd->inTransaction()) {
                      $bdd->rollBack();
                  }
                  $messages[] = ['type' => 'error', 'text' => "Erreur technique : " . htmlspecialchars($error->getMessage())];
              }
          }

          foreach ($errors as $err) {
              $messages[] = ['type' => 'error', 'text' => $err];
          }

          if (empty($validPairs) && empty($errors)) {
              $messages[] = ['type' => 'error', 'text' => "Aucune donnée valide à insérer."];
          }
      }
    ?>

    <h1>Taxonomie des Genres</h1>

    <div class="form-container">
        <?php foreach ($messages as $msg): ?>
            <p class="message-<?= $msg['type'] ?>"><?= $msg['text'] ?></p>
        <?php endforeach; ?>

        <form method="post" action="">
            <div class="form-group">
                <label>Saisir les relations (une par ligne, format : ENFANT, PARENT) :</label>
                <textarea name="taxonomy" rows="10" placeholder="POP ROCK, POP&#10;POP ROCK, ROCK&#10;GLAM ROCK, ROCK"><?= isset($_POST['taxonomy']) ? htmlspecialchars($_POST['taxonomy']) : '' ?></textarea>
            </div>
            <button type="submit" name="save_taxonomy">Enregistrer</button>
        </form>
    </div>

    <p style="margin: 20px;"><a href="../index.html">Retour à l'accueil</a></p>
  </body>
</html>