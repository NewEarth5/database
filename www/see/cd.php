<!DOCTYPE html>
<html>
  <head>
    <?php include('../php/table.php'); ?>
    <title>List of CD details</title>
    <link href="../css/see.css" type="text/css" rel="stylesheet" />
  </head>
  <body><?php echo "\n";
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
        `CD`.`copies`,
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

      $table = new Table('cd.php', $bdd, $sql);

      $table->add_column('cd_number',       'cd_number',       'CD ID');
      $table->add_column('title',           'title',           'Title');
      $table->add_column('producer',        'producer',        'Producer');
      $table->add_column('year',            'year',            'Year');
      $table->add_column('copies',          'copies',          'Number of copies');
      $table->add_column('duration_tot',    'duration_tot',    'Total duration');
      $table->add_column('duration_max',    'duration_max',    'Maximum duration');
      $table->add_column('duration_min',    'duration_min',    'Minimum duration');
      $table->add_column('duration_avg',    'duration_avg',    'Average duration');
      $table->add_column('playlist_amount', 'playlist_amount', 'Playlist amount');
      $table->add_column('genres',          'genres',          'Genres');

      $table->add_filter('cd_number',       'number', FALSE, 'CD.cd_number',                               '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('title',           'text',   FALSE, 'CD.title',                                   'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('producer',        'text',   FALSE, 'CD.producer',                                'LIKE', '%', '%', 'maxlength="255"');
      $table->add_filter('year',            'number', FALSE, 'CD.year',                                    '=',    '',  '',  'min="1901" max="2155" step="1"');
      $table->add_filter('copies',          'number', FALSE, 'CD.copies',                                  '=',    '',  '',  'min="1" step="1"');
      $table->add_filter('duration_tot',    'time',   FALSE, 'SongStats.duration_tot',                     '=',    '',  '',  'step="1"');
      $table->add_filter('duration_max',    'time',   FALSE, 'SongStats.duration_max',                     '=',    '',  '',  'step="1"');
      $table->add_filter('duration_min',    'time',   FALSE, 'SongStats.duration_min',                     '=',    '',  '',  'step="1"');
      $table->add_filter('duration_avg',    'time',   FALSE, 'SongStats.duration_avg',                     '=',    '',  '',  'step="1"');
      $table->add_filter('playlist_amount', 'number', FALSE, 'COALESCE(PlaylistCount.playlist_amount, 0)', '=',    '',  '',  'min=0 step="1"');
      $table->add_filter('genres',          'text',   FALSE, 'GenreList.genres',                           'LIKE', '%', '%', '');

      $table->show(2, TRUE, TRUE, TRUE);
    ?>
    <script src="../javascript/see.js"></script>
  </body>
</html>
