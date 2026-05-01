<!DOCTYPE html>
<html>
  <head>
    <?php include('../php/table.php'); ?>
    <title> List of Clients </title>
    <link href="../css/see.css" type="text/css" rel="stylesheet" />
  </head>
  <body><?php echo "\n";
      $bdd = new PDO('mysql:host=db;dbname=group17;charset=utf8', 'group17', '1234');
      $sql = 'SELECT * FROM `Client` WHERE 1=1';

      $table = new Table('client.php', $bdd, $sql);

      $table->add_column('client_number', 'client_number', 'Client ID');
      $table->add_column('first_name',    'first_name',    'First name');
      $table->add_column('last_name',     'last_name',     'Last name');
      $table->add_column('email_address', 'email_address', 'Email');
      $table->add_column('phone_number',  'phone_number',  'Phone');

      $table->add_filter('client_number', 'number', FALSE, 'client_number', '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('first_name',    'text',   FALSE, 'first_name',    'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('last_name',     'text',   FALSE, 'last_name',     'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('email_address', 'text',   FALSE, 'email_address', 'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('phone_number',  'tel',    FALSE, 'phone_number',  'LIKE', '%', '%', 'maxlength="20"');

      $table->show(2, TRUE, TRUE, TRUE);
    ?>
    <script src="../javascript/see.js"></script>
  </body>
</html>