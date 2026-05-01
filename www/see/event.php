<!DOCTYPE html>
<html>
  <head>
    <?php include('../php/table.php'); ?>
    <title> List of Clients </title>
    <link href="../css/see.css" type="text/css" rel="stylesheet" />
  </head>
  <body><?php echo "\n";
      $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');
      $sql = 'SELECT * FROM `Event` WHERE 1=1';

      $table = new Table('event.php', $bdd, $sql);

      $table->add_column('id',            'id',            'ID');
      $table->add_column('name',          'name',          'Name');
      $table->add_column('date',          'date',          'Date');
      $table->add_column('client',        'client',        'Client ID');
      $table->add_column('manager',       'manager',       'Manager ID');
      $table->add_column('event_planner', 'event_planner', 'Event planner ID');
      $table->add_column('dj',            'dj',            'DJ ID');
      $table->add_column('theme',         'theme',         'Theme');
      $table->add_column('type',          'type',          'Type');
      $table->add_column('location',      'location',      'Location ID');
      $table->add_column('rental_fee',    'rental_fee',    'Rental fee');
      $table->add_column('playlist',      'playlist',      'Playlist');

      $table->add_filter('id',            'number', FALSE, 'id',            '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('name',          'text',   FALSE, 'name',          'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('date',          'date',   FALSE, 'date',          '=',    '',  '',  '');
      $table->add_filter('client',        'number', FALSE, 'client',        '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('manager',       'number', FALSE, 'manager',       '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('event_planner', 'number', FALSE, 'event_planner', '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('dj',            'number', FALSE, 'dj',            '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('theme',         'text',   FALSE, 'theme',         'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('type',          'text',   FALSE, 'type',          'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('location',      'number', FALSE, 'location',      '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('rental_fee',    'number', FALSE, 'rental_fee',    '=',    '',  '',  'min="0" max="9999999" step="0.01"');
      $table->add_filter('playlist',      'text',   FALSE, 'playlist',      'LIKE', '%', '%', 'maxlength="255"');

      $table->show(2, TRUE, TRUE, TRUE);
    ?>
    <script src="../javascript/see.js"></script>
  </body>
</html>