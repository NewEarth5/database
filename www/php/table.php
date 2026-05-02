<?php
  class Table {
    private $page;
    private $bdd;
    private $sql;
    private $columns = [];
    private $filters = [];
    private $params  = [];
    private $sort = null;
    private $direction = 'ASC';
    private $pageNum = 1;
    private $perPage = 20;
    private $totalRows = 0;
    private $maxPages = 0;

    function __construct($page, $bdd, $sql) {
      $this->page = $page;
      $this->bdd = $bdd;
      $this->sql = $sql;
    }

    function add_column($name, $id, $label) {
      $this->columns[] = [
        'name'        => $name,
        'id'          => $id,
        'label'       => $label
      ];
    }

    function add_filter($id, $type, $showPlaceholder, $table, $relationship, $prefix, $postfix, $params) {
      $this->filters[$id] = [
        'type'            => $type,
        'showPlaceholder' => $showPlaceholder,
        'table'           => $table,
        'relationship'    => $relationship,
        'prefix'          => $prefix,
        'postfix'         => $postfix,
        'params'          => $params
      ];
    }

    private function handleRequests() {
      if (isset($_GET['sort'])) $this->sort = $_GET['sort'];

      if (isset($_GET['dir']) && in_array(strtoupper($_GET['dir']), ['ASC', 'DESC'])) $this->direction = strtoupper($_GET['dir']);

      if (isset($_GET['page']) && ctype_digit($_GET['page'])) {
        $this->pageNum = (int) $_GET['page'];
      };

      $this->totalRows = $this->getTotalRows();
      $this->maxPages = max(1, ceil($this->totalRows / $this->perPage));
      $this->pageNum = min(max(1, $this->pageNum), $this->maxPages);
    }
    
    private function getTotalRows() {
      $sql = 'SELECT COUNT(*) as total FROM (' . $this->buildQueryFilter() . ') as sub';

      $stmt = $this->bdd->prepare($sql);
      $stmt->execute($this->params);

      return (int) $stmt->fetch()['total'];
    }

    private function buildQueryFilter() {
      $sql = $this->sql;
      $this->params = [];

      foreach ($this->columns as $column) {
        if (isset($this->filters[$column['id']])) {
          $value = $_GET[$column['name']] ?? '';

          if (trim($value) !== '') {
            $filter = $this->filters[$column['id']];
            $sql .= ' AND ' . $filter['table'] . ' ' . $filter['relationship'] . ' :' . $column['name'];
            $this->params[':' . $column['name']] = $filter['prefix'] . trim($_GET[$column['name']]) . $filter['postfix'];
          }
        }
      }

      return $sql;
    }

    private function buildQuery() {
      $sql = $this->buildQueryFilter();

      if ($this->sort !== null) {
        $allowed = array_column($this->columns, 'name');

        if (in_array($this->sort, $allowed) && in_array($this->direction, ['ASC', 'DESC'])) $sql .= ' ORDER BY ' . $this->sort . ' ' . $this->direction;
      }

      $offset = ($this->pageNum - 1) * $this->perPage;
      $sql .= ' LIMIT ' . $this->perPage . ' OFFSET ' . $offset;

      return $sql;
    }

    private function buildURL($overrides = [], $doReset = FALSE) {
      if ($doReset) {
        $query = $overrides;
      } else {
        $query = array_merge($_GET, $overrides);
      }
      
      return $this->page . '?' . http_build_query($query);
    }

    private function smoothFilter($indent) {
      echo str_repeat(" ", 2 * $indent) . '<script>' . "\n";
      $indent += 1;
      if (isset($_GET['reset'])) {
        echo str_repeat(" ", 2 * $indent) . 'sessionStorage.setItem("filterMenuOpen", "false");' . "\n";
      } else {
        echo str_repeat(" ", 2 * $indent) . 'if (sessionStorage.getItem("filterMenuOpen") === "true") {' . "\n";
        $indent += 1;
        echo str_repeat(" ", 2 * $indent) . 'const style = document.createElement("style");' . "\n";
        echo str_repeat(" ", 2 * $indent) . 'style.id = "temp-collapsible";' . "\n";
        echo str_repeat(" ", 2 * $indent) . 'style.textContent = " .content { max-height: none !important; transition: none !important; }";' . "\n";
        echo str_repeat(" ", 2 * $indent) . 'document.head.appendChild(style);' . "\n";
        $indent -= 1;
        echo str_repeat(" ", 2 * $indent) . '}' . "\n";
      }
      $indent -= 1;
      echo '    </script>' . "\n";
    }

    private function renderFilter($indent, $isCollapsable, $showLabel, $showReset) {
      if ($isCollapsable) {
        $this->smoothFilter($indent);
        echo str_repeat(" ", 2 * $indent) . '<button class="collapsible"> Filters </button>' . "\n";
        echo str_repeat(" ", 2 * $indent) . '<div class="content">' . "\n";
        $indent += 1;
      }
      echo str_repeat(" ", 2 * $indent) . '<form method="get" action="' . $this->page . '">' . "\n";
      $indent++;
      
      foreach (['sort', 'dir', 'page'] as $key) {
        if (isset($_GET[$key])) {
          echo str_repeat(" ", 2 * $indent) . '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars($_GET[$key]) . '">' . "\n";
        }
      }
      echo str_repeat(" ", 2 * $indent) . '<input type="hidden" name="page" value="1">' . "\n";

      foreach ($this->columns as $column) {
        if (isset($this->filters[$column['id']])) {
          $filter = $this->filters[$column['id']];
          $value = $_GET[$column['name']] ?? '';

          if ($isCollapsable) {
            echo str_repeat(" ", 2 * $indent) . '<p>' . "\n";
            $indent += 1;
          }
          if ($showLabel) {
            echo str_repeat(" ", 2 * $indent) . '<label for="' . $column['id'] . '"> ' . $column['label'] . ': </label>' . "\n";
          }

          echo str_repeat(" ", 2 * $indent) . '<input type="' . $filter['type'] . '" id="' . $column['id'] . '" name="' . $column['name'] . '"';
          if ($filter['showPlaceholder']) {
            echo ' placeholder="' . $column['label']. '"';
          }
          echo ' value="' . htmlspecialchars($value) . '" ' . $filter['params'] . '>' . "\n";
          
          if ($isCollapsable) {
            $indent -= 1;
            echo str_repeat(" ", 2 * $indent) . '</p>' . "\n";
          }
        }
      }

      echo str_repeat(" ", 2 * $indent) . '<button type="submit" id="submit" name="submit" class="button"> Filter </button>' . "\n";
      if ($showReset) {
        $overrides = [
          'sort' => $this->sort,
          'dir' => $this->direction,
          'reset' => true
        ];
        $url = $this->buildURL($overrides, TRUE);
        echo str_repeat(" ", 2 * $indent) . '<a href="' . $url . '" class="button"> Reset </a>' . "\n";
      }

      $indent--;
      echo str_repeat(" ", 2 * $indent) . '</form>' . "\n";

      if ($isCollapsable) {
        $indent -= 1;
        echo str_repeat(" ", 2 * $indent) . '</div>' . "\n";
      }
    }

    private function renderTable($indent, $sql, $showSort) {
      $statement = $this->bdd->prepare($sql);
      $statement->execute($this->params);

      echo str_repeat(" ", 2 * $indent) . '<table>' . "\n";
      $indent++;
      
      echo str_repeat(" ", 2 * $indent) . '<tr>' . "\n";
      $indent++;
      
      foreach ($this->columns as $column) {
        if ($showSort) {
          $direction = 'ASC';
          if ($this->sort === $column['name'] && $this->direction === 'ASC') $direction = 'DESC';

          $url = $this->buildURL([
            'sort' => $column['name'],
            'dir' => $direction
          ]);

          if ($this->sort === $column['name']) {
            $class = 'sortable selected';

            if ($this->direction === 'ASC') {
              $arrow = '↑';
            } else {
              $arrow = '↓';
            }
          } else {
            $class = 'sortable';
            $arrow = '⇅';
          }

          echo str_repeat(" ", 2 * $indent) . '<th class="' . $class . '">' ."\n";
          $indent++;
          echo str_repeat(" ", 2 * $indent) . '<a href="' . $url . '">' ."\n";
          $indent++;
          echo str_repeat(" ", 2 * $indent) . '<span class ="label"> ' . $column['label'] . ' </span>' ."\n";
          echo str_repeat(" ", 2 * $indent) . '<span class="arrow"> ' . $arrow . ' </span>' ."\n";
          $indent--;
          echo str_repeat(" ", 2 * $indent) . '</a>' ."\n";
          $indent--;
          echo str_repeat(" ", 2 * $indent) . '</th>' . "\n";
        } else {
          echo str_repeat(" ", 2 * $indent) . '<th> ' . $column['label'] . ' </th>' . "\n";
        }
      }

      $indent--;
      echo str_repeat(" ", 2 * $indent) . '</tr>' . "\n";

      while ($row = $statement->fetch()) {
        echo str_repeat(" ", 2 * $indent) . '<tr>' . "\n";
        $indent++;

        foreach ($this->columns as $column) {
          echo str_repeat(" ", 2 * $indent) . '<td> ' . htmlspecialchars(trim($row[$column['name']] ?? '')) . ' </td>' . "\n";
        }

        $indent--;
        echo str_repeat(" ", 2 * $indent) . '</tr>' . "\n";
      }

      $indent--;
      echo str_repeat(" ", 2 * $indent) . '</table>' . "\n";
    }

    private function renderPagination($indent) {
      echo str_repeat(" ", 2 * $indent) . '<div class="pagination">' . "\n";
      $indent++;
      $build = function($page) {
        return $this->buildURL(['page' => $page]);
      };

      if ($this->pageNum > 1) {
        echo str_repeat(" ", 2 * $indent) . '<a href="' . $build(1) . '"> « </a>' . "\n";
        echo str_repeat(" ", 2 * $indent) . '<a href="' . $build($this->pageNum - 1) . '"> ‹ </a>' . "\n";
      }

      echo str_repeat(" ", 2 * $indent) . '<form method="get" action="' . $this->page . '" class="page-jump" style="display:inline;">' . "\n";
      $indent++;

      foreach ($_GET as $key => $value) {
        if ($key !== 'page') {
          echo str_repeat(" ", 2 * $indent) . '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars($value) . '">' . "\n";
        }
      }
      echo str_repeat(" ", 2 * $indent) . 'Page ';
      echo str_repeat(" ", 2 * $indent) . '<input type="number" name="page" min="1" max="' . $this->maxPages . '" value="' . $this->pageNum . '" style="width:60px;">' . "\n";
      echo ' / ' . $this->maxPages . "\n";
      // echo str_repeat(" ", 2 * $indent) . '<button type="submit"> Go </button>' . "\n";

      $indent--;
      echo str_repeat(" ", 2 * $indent) . '</form>' . "\n";

      if ($this->pageNum < $this->maxPages) {
        echo str_repeat(" ", 2 * $indent) . '<a href="' . $build($this->pageNum + 1) . '"> › </a>' . "\n";
        echo str_repeat(" ", 2 * $indent) . '<a href="' . $build($this->maxPages) . '"> » </a>' . "\n";
      }

      $indent--;
      echo str_repeat(" ", 2*$indent) . '</div>' . "\n";
    }

    function show($indent = 2, $showSort = TRUE, $showPagination = TRUE, $isCollapsable = TRUE, $showLabel = TRUE, $showReset = TRUE) {
      $this->handleRequests();

      $this->renderFilter($indent, $isCollapsable, $showLabel, $showReset);

      $sql = $this->buildQuery();

      $this->renderTable($indent, $sql, $showSort);

      if ($showPagination && $this->maxPages > 1) $this->renderPagination($indent);
    }
  }
?>
