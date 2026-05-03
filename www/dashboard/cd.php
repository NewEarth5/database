<!DOCTYPE html>
<html>
  <head>
    <?php include('../php/table.php'); ?>
    <title> List of Clients </title>
    <link href="../css/see.css" type="text/css" rel="stylesheet" />
  </head>
  <body><?php echo "\n";
      $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');
      $sql = 'WITH RECURSIVE
      DateRange (`date`) AS (
        SELECT MIN(`date`) FROM `Event`
        UNION ALL
        SELECT DATE_ADD(`date`, INTERVAL 1 DAY)
        FROM `DateRange`
        WHERE date < (SELECT MAX(`date`) FROM `Event`)
      ),
      EventCDs (`date`, `cd_number`) AS (
        SELECT DISTINCT
          `Event`.`date`,
          `Contains`.`cd_number`
        FROM `Event`
        JOIN `Contains` ON `Contains`.`playlist` = `Event`.`playlist`
      ),
      UsedCDs (`date`, `cd_number`, `used_copies`) AS (
        SELECT
          `date`,
          `cd_number`,
          COUNT(*)
        FROM `EventCDs`
        GROUP BY `date`, `cd_number`
      )
      SELECT
        `DateRange`.`date`,
        `CD`.`cd_number`,
        `CD`.`title`,
        `CD`.`copies`,
        COALESCE(`UsedCDs`.`used_copies`, 0) AS `used_copies`
      FROM DateRange
      CROSS JOIN CD
      LEFT JOIN `UsedCDs`
        ON `UsedCDs`.`date` = `DateRange`.`date`
        AND `UsedCDs`.`cd_number` = `CD`.`cd_number`';

      $table = new Table('cd.php', $bdd, $sql);

      $table->add_column('date',        'date',        'Date');
      $table->add_column('cd_number',   'cd_number',   'CD ID');
      $table->add_column('title',       'title',       'Title');
      $table->add_column('copies',      'copies',      'Copies');
      $table->add_column('used_copies', 'used_copies', 'Number of used copies');

      $table->add_filter('date',        'date',   FALSE, 'DateRange.date',                   '=',    '',  '',  '');
      $table->add_filter('cd_number',   'number', FALSE, 'CD.cd_number',                     '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('title',       'text',   FALSE, 'CD.title',                         'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('copies',      'number', FALSE, 'CD.copies',                        '=',    '',  '',  'min="0" step="1"');
      $table->add_filter('used_copies', 'number', FALSE, 'COALESCE(UsedCDs.used_copies, 0)', '=',    '',  '',  'min="0" step="1"');

      $table->show();
    ?>
    <script src="../javascript/see.js"></script>
  </body>
</html>