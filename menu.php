<?php


$json = file_get_contents("menu_categorias.json");
$data = json_decode($json);

$categories = [];
$sub = [];
$items = [];

$dataList = $data->{"result"}[0];

foreach($dataList as $key => $value) {
	if ($value->{"NUM_FILHO"} == $value->{"QTD_FILHO"}) {
		$categories[$value->{"COD_PAI"}] = $value->{"DESC_PAI"};
	}
	if ($value->{"NUM_NETO"} == $value->{"QTD_NETO"} || $value->{"QTD_NETO"} == "0") {
		$sub[$value->{"COD_PAI"}][$value->{"COD_FILHO"}] = $value->{"DESC_FILHO"};
	}
	if ($value->{"COD_NETO"} != 0) {
		$items[$value->{"COD_FILHO"}][$value->{"COD_NETO"}] = $value->{"DESC_NETO"};
	}
}

function renderMenu($categories, $sub, $items)
{
	echo <<<EOT
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Todas as Categorias<b class="caret"></b></a>
				<ul class="dropdown-menu multi-level">
EOT;
	foreach($categories as $k => $cat) {
			echo "<li class='dropdown-submenu'>" . PHP_EOL;
			echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>$cat</a>" . PHP_EOL;
			echo "<ul class='dropdown-menu'>" . PHP_EOL;
			foreach($sub[$k] as $key => $value) {
				if (array_key_exists($key, $items)) {
					echo "<li class='dropdown-submenu'>" . PHP_EOL;
					echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>$value</a>" . PHP_EOL;
					echo "<ul class='dropdown-menu'>" . PHP_EOL;
					foreach($items[$key] as $item) {
						echo "<li><a href='#'>$item</a></li>" . PHP_EOL;
					}
					echo "</ul>" . PHP_EOL;
				}
				else {
					echo "<li><a href='#'>$value</a></li>" . PHP_EOL;
				}
			}
			echo "</ul>" . PHP_EOL;
	}
	echo <<<EOT
				</ul>
      </li>
    </ul>
  </div>
EOT;
}
