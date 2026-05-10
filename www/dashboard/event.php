<!DOCTYPE html>
<html>
  <head>
    <?php include('../php/table.php'); ?>
    <title> Tableau de bord Évènement </title>
    <link href="../css/see.css" type="text/css" rel="stylesheet" />
  </head>
  <body><?php echo "\n";
      $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');
      
      // Traduction des statuts directement dans la requête SQL
      $sql = 'SELECT
        `Event`.`id`,
        `Event`.`name`,
        `Event`.`date`,
        CASE
            WHEN `Event`.`date` < CURDATE() THEN "PASSÉ"
            WHEN `Event`.`date` = CURDATE() THEN "AUJOURD\'HUI"
            ELSE "FUTUR"
        END AS `status`,
        COUNT(`Request`.`name`)                           AS count,
        COALESCE(SUM(`Request`.`price`), 0)               AS cost,
        1500 + 1.05 * COALESCE(SUM(`Request`.`price`), 0) AS total
      FROM `Event`
      LEFT JOIN `Request` ON `Request`.`event_id` = `Event`.`id`
      GROUP BY `Event`.`id`, `Event`.`name`, `Event`.`date`';

      $table = new Table('event.php', $bdd, $sql);

      $table->doSubquery();
      
      // Traduction des colonnes
      $table->add_column('id',     'id',     'ID Évènement');
      $table->add_column('name',   'name',   'Nom');
      $table->add_column('date',   'date',   'Date');
      $table->add_column('status', 'status', 'Statut');
      $table->add_column('count',  'count',  'Nombre de requêtes');
      $table->add_column('cost',   'cost',   'Coût des requêtes');
      $table->add_column('total',  'total',  'Prix Total');

      $table->add_sort('date', 'DESC');
      $table->add_sort('name', 'ASC');

      // Note : on garde les IDs techniques pour les filtres, mais on change juste l'affichage
      $table->add_filter('id',     'number', FALSE, 'id',     '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('name',   'text',   FALSE, 'name',   'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('date',   'date',   FALSE, 'date',   '=',    '',  '',  '');
      $table->add_filter('status', 'text',   FALSE, 'status', 'LIKE', '%', '%', 'maxlength="12"');
      $table->add_filter('count',  'number', FALSE, 'count',  '=',    '',  '',  'min="0" step="1"');
      $table->add_filter('cost',   'number', FALSE, 'cost',   '=',    '',  '',  'min="0" step="1"');
      $table->add_filter('total',  'number', FALSE, 'total',  '=',    '',  '',  'min="0" step="1"');

      $table->show();
    ?>
    <p style="margin: 20px;"><a href="../index.html">Retour à l'accueil</a></p>
    <script src="../javascript/see.js"></script>
  </body>
</html>