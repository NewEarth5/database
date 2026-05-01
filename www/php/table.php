<?php
  class Table {
    private $page;
    private $bdd;
    private $sql;
    private $amount  = 0;
    private $columns = [];
    private $filters = [];
    private $params  = [];

    function __construct($page, $bdd, $sql) {
      $this->page = $page;
      $this->bdd = $bdd;
      $this->sql = $sql;
    }

    function add_column($name, $id, $label) {
      $this->columns[$this->amount] = [
        'name'        => $name,
        'id'          => $id,
        'label'       => $label
      ];
      $this->amount += 1;
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

    function show($indent, $isCollapsable, $showLabel, $showReset) {
      if (count($this->filters) > 0) {
        $this->initialise();
        if ($isCollapsable) $this->smoothFilter($indent);
        $this->filter($indent, $isCollapsable, $showLabel, $showReset);
        $this->update();
      }
      $this->print($indent);
    }

    private function smoothFilter($indent) {
      echo str_repeat(" ", 2 * $indent) . '<script>' . "\n";
      $indent += 1;
      if (!isset($_POST['submit']) or isset($_POST['reset'])) {
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

    private function initialise() {
      if(!isset($_POST['submit']) or isset($_POST['reset'])) {
        foreach($this->columns as $column) {
          $_POST[$column['name']] = '';
        }
      }
    }

    private function filter($indent, $isCollapsable, $showLabel, $showReset) {
      if ($isCollapsable) {
        $this->smoothFilter($indent);
        echo str_repeat(" ", 2 * $indent) . '<button class="collapsible"> Filters </button>' . "\n";
        echo str_repeat(" ", 2 * $indent) . '<div class="content">' . "\n";
        $indent += 1;
      }
      echo str_repeat(" ", 2 * $indent) . '<form method="post" action="' . $this->page . '">' . "\n";
      $indent += 1;

      foreach ($this->columns as $column) {
        if (isset($this->filters[$column['id']])) {
          $filter = $this->filters[$column['id']];
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
          echo ' value="' . htmlspecialchars(trim($_POST[$column['name']])) . '" ' . $filter['params'] . '>' . "\n";
          if ($isCollapsable) {
            $indent -= 1;
            echo str_repeat(" ", 2 * $indent) . '</p>' . "\n";
          }
        }
      }

      echo str_repeat(" ", 2 * $indent) . '<input type="submit" id="submit" name="submit" value="Filter">' . "\n";
      if ($showReset) {
        echo str_repeat(" ", 2 * $indent) . '<input type="submit" id="reset" name="reset" value="Reset">' . "\n";
      }
      $indent -= 1;
      echo str_repeat(" ", 2 * $indent) . '</form>' . "\n";
      if ($isCollapsable) {
        $indent -= 1;
        echo str_repeat(" ", 2 * $indent) . '</div>' . "\n";
      }
    }

    private function update() {
      foreach ($this->columns as $column) {
        if (isset($this->filters[$column['id']])) {
          $filter = $this->filters[$column['id']];

          if(!empty(trim($_POST[$column['name']]))) {
            $this->sql .= ' AND ' . $filter['table'] . ' ' . $filter['relationship'] . ' :' . $column['name'];
            $this->params[':' . $column['name']] = $filter['prefix'] . trim($_POST[$column['name']]) . $filter['postfix'];
          }
        }
      }
    }

    private function print($indent) {
      $statement = $this->bdd->prepare($this->sql);
      $statement->execute($this->params);
      
      echo str_repeat(" ", 2 * $indent) . '<table>' . "\n";
      $indent += 1;
      echo str_repeat(" ", 2 * $indent) . '<tr>' . "\n";
      $indent += 1;
      foreach ($this->columns as $column) {
        echo str_repeat(" ", 2 * $indent) . '<th> ' . $column['label'] . ' </th>' . "\n";
      }
      $indent -= 1;
      echo str_repeat(" ", 2 * $indent) . '</tr>' . "\n";
      while ($row = $statement->fetch()) {
        echo str_repeat(" ", 2 * $indent) . '<tr>' . "\n";
        $indent += 1;
        foreach ($this->columns as $column) {
          echo str_repeat(" ", 2 * $indent) . '<td> ' . htmlspecialchars(trim($row[$column['name']] ?? '')) . ' </td>' . "\n";
        }
        $indent -= 1;
        echo str_repeat(" ", 2 * $indent) . '</tr>' . "\n";
      }
      $indent -= 1;
      echo str_repeat(" ", 2 * $indent) . '</table>' . "\n";
    }
  }
?>
