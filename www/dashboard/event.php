<!DOCTYPE html>
<html>
  <head>
    <?php include('../php/table.php'); ?>
    <title> Event dashboard </title>
    <link href="../css/see.css" type="text/css" rel="stylesheet" />
  </head>
  <body><?php echo "\n";
      $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');
      $sql = 'SELECT
        `Event`.`id`,
        `Event`.`name`,
        `Event`.`date`,
        CASE
            WHEN `Event`.`date` < CURDATE() THEN "PASSED"
            WHEN `Event`.`date` = CURDATE() THEN "TODAY"
            ELSE "FUTUR"
        END AS `status`,
        COUNT(`Request`.`name`)                           AS count,
        COALESCE(SUM(`Request`.`price`), 0)               AS cost,
        1500 + 0.05 * COALESCE(SUM(`Request`.`price`), 0) AS total
      FROM `Event`
      LEFT JOIN `Request` ON `Request`.`event_id` = `Event`.`id`
      GROUP BY `Event`.`id`, `Event`.`name`, `Event`.`date`';

      $table = new Table('event.php', $bdd, $sql);

      $table->doSubquery();
      
      $table->add_column('id',     'id',     'Event ID');
      $table->add_column('name',   'name',   'Name');
      $table->add_column('date',   'date',   'Date');
      $table->add_column('status', 'status', 'Status');
      $table->add_column('count',  'count',  'Number of requests');
      $table->add_column('cost',   'cost',   'Cost of requests');
      $table->add_column('total',  'total',  'Total price');

      $table->add_sort('date', 'ASC');
      $table->add_sort('name', 'ASC');

      $table->add_filter('id',     'number', FALSE, 'id',     '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('name',   'text',   FALSE, 'name',   'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('date',   'date',   FALSE, 'date',   '=',    '',  '',  '');
      $table->add_filter('status', 'text',   FALSE, 'status', 'LIKE', '%', '%', 'maxlength="6"');
      $table->add_filter('count',  'number', FALSE, 'count',  '=',    '',  '',  'min="0" step="1"');
      $table->add_filter('cost',   'number', FALSE, 'cost',   '=',    '',  '',  'min="0" step="1"');
      $table->add_filter('total',  'number', FALSE, 'total',  '=',    '',  '',  'min="0" step="1"');

      $table->show();
    ?>
    <script src="../javascript/see.js"></script>
  </body>
</html>