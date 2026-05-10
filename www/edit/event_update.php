<?php
try {
    $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$message = "";

function cleanName($str) {
    return trim(str_replace(["\n", "\r"], '', $str));
}

if (isset($_POST['update_event'])) {
    $event_id      = $_POST['event_id'];
    $dj_id         = $_POST['dj_id'];
    $planner_id    = $_POST['planner_id'];
    $playlist_name = cleanName($_POST['playlist_name']);
    $location_id   = $_POST['location_id'];

    try {
        $stmt = $bdd->prepare("SELECT date FROM Event WHERE id = ?");
        $stmt->execute([$event_id]);
        $event_data = $stmt->fetch();
        $event_date = $event_data['date'];

        $checkDJ = $bdd->prepare("SELECT COUNT(*) FROM Event WHERE dj = ? AND date = ? AND id != ?");
        $checkDJ->execute([$dj_id, $event_date, $event_id]);

        $checkPlanner = $bdd->prepare("SELECT COUNT(*) FROM Event WHERE event_planner = ? AND date = ? AND id != ?");
        $checkPlanner->execute([$planner_id, $event_date, $event_id]);

        $playlist_final = null;
        if (!empty($playlist_name)) {
            $allPlaylists = $bdd->query("SELECT name FROM Playlist")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($allPlaylists as $pl) {
                if (cleanName($pl) === $playlist_name) {
                    $playlist_final = $pl;
                    break;
                }
            }
        }

        $djCount      = $checkDJ->fetchColumn();
        $plannerCount = $checkPlanner->fetchColumn();

        if ($djCount > 0) {
            $message = "<p style='color:red;'>Erreur : Ce DJ est déjà pris le $event_date.</p>";
        } elseif ($plannerCount > 0) {
            $message = "<p style='color:red;'>Erreur : Ce planificateur est déjà pris le $event_date.</p>";
        } else {
            $update = $bdd->prepare("UPDATE Event SET dj = ?, event_planner = ?, playlist = ?, location = ? WHERE id = ?");
            $update->execute([$dj_id, $planner_id, $playlist_final, $location_id, $event_id]);
            $message = "<p style='color:green;'>Événement mis à jour avec succès !</p>";
        }
    } catch (PDOException $e) {
        $message = "<p style='color:red;'>Erreur technique : " . $e->getMessage() . "</p>";
    }
}

$events = $bdd->query("SELECT id, name, date, dj, event_planner, location, playlist FROM Event ORDER BY date ASC")->fetchAll(PDO::FETCH_ASSOC);

$djs = $bdd->query("SELECT DJ.id, CONCAT(Employee.first_name, ' ', Employee.last_name) as Fullname 
                    FROM DJ 
                    JOIN Employee ON DJ.id = Employee.id 
                    ORDER BY Employee.last_name ASC")->fetchAll();

$planners = $bdd->query("SELECT EventPlanner.id, CONCAT(Employee.first_name, ' ', Employee.last_name) as Fullname 
                         FROM EventPlanner 
                         JOIN Employee ON EventPlanner.id = Employee.id 
                         ORDER BY Employee.last_name ASC")->fetchAll();

$locations = $bdd->query("SELECT id, city FROM Location ORDER BY city ASC")->fetchAll();

$playlists = $bdd->query("SELECT name FROM Playlist ORDER BY name ASC")->fetchAll();

$eventsJson = json_encode($events);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mise à jour Événement</title>
    <link rel="stylesheet" href="../css/see.css">
    <style>
        .form-container { margin: 20px; padding: 20px; border: 1px solid #ccc; background: #f9f9f9; border-radius: 8px; max-width: 600px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        select, input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; border-radius: 4px; font-size: 16px; margin-top: 10px; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <h1>Mise à jour des Événements</h1>

    <div class="form-container">
        <?php echo $message; ?>

        <form method="POST">
            <div class="form-group">
                <label>Événement à modifier :</label>
                <select name="event_id" id="event_id" required>
                    <?php foreach ($events as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['name']) ?> (<?= $e['date'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Assigner un DJ :</label>
                <select name="dj_id" id="dj_id" required>
                    <?php foreach ($djs as $dj): ?>
                        <option value="<?= $dj['id'] ?>"><?= htmlspecialchars($dj['Fullname']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Assigner un Planificateur :</label>
                <select name="planner_id" id="planner_id" required>
                    <?php foreach ($planners as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['Fullname']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Lieu :</label>
                <select name="location_id" id="location_id" required>
                    <?php foreach ($locations as $l): ?>
                        <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['city']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Playlist :</label>
                <select name="playlist_name" id="playlist_name">
                    <option value="">-- Aucune playlist --</option>
                    <?php foreach ($playlists as $pl): ?>
                        <!-- On nettoie le \n pour la valeur de l'option -->
                        <option value="<?= htmlspecialchars(cleanName($pl['name'])) ?>">
                            <?= htmlspecialchars(cleanName($pl['name'])) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" name="update_event">Enregistrer les modifications</button>
        </form>
    </div>

    <p><a href="../index.html">Retour à l'accueil</a></p>

    <script>
        const events = <?= $eventsJson ?>;

        function cleanName(str) {
            return str ? str.replace(/[\n\r]/g, '').trim() : '';
        }

        function prefillForm(eventId) {
            const event = events.find(e => e.id == eventId);
            if (!event) return;

            document.getElementById('dj_id').value       = event.dj;
            document.getElementById('planner_id').value  = event.event_planner;
            document.getElementById('location_id').value = event.location;

            // Nettoyage du \n pour matcher les options du select
            const playlistSelect = document.getElementById('playlist_name');
            const playlistValue  = cleanName(event.playlist || '');
            playlistSelect.value = playlistValue;

            if (playlistSelect.value !== playlistValue) {
                playlistSelect.value = '';
            }
        }

        const eventSelect = document.getElementById('event_id');
        prefillForm(eventSelect.value);
        eventSelect.addEventListener('change', function() {
            prefillForm(this.value);
        });
    </script>
</body>
</html>