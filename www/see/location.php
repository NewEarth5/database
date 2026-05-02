<!DOCTYPE html>
<html>
  <head>
    <?php include('../php/table.php'); ?>
    <title> List of Locations </title>
    <link href="../css/see.css" type="text/css" rel="stylesheet" />
  </head>
  <body><?php echo "\n";
      $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');
      $sql = 'SELECT * FROM `Location` WHERE 1=1';

      $table = new Table('location.php', $bdd, $sql);

      $table->add_column('id',          'id',          'ID');
      $table->add_column('street',      'street',      'Street');
      $table->add_column('city',        'city',        'City');
      $table->add_column('postal_code', 'postal_code', 'Postal code');
      $table->add_column('country',     'country',     'Country');
      $table->add_column('comment',     'comment',     'Comment');

      $table->add_filter('id',          'number', FALSE, 'id',          '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('street',      'text',   FALSE, 'street',      'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('city',        'text',   FALSE, 'city',        'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('postal_code', 'text',   FALSE, 'postal_code', 'LIKE', '%', '%', 'maxlength="12"');
      $table->add_filter('country',     'text',   FALSE, 'country',     'LIKE', '%', '%', 'maxlength="255"');

      $table->show();
    ?>
    <script src="../javascript/see.js"></script>
  </body>
</html>