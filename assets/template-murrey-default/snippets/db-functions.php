<?php
// Т.к. contenteditable создаёт теги &nbsp на месте пробелов, очищаем отдельно
function dbFunctionsWrapper() {
  // UTILITY
  function sTrim($str) {
    return trim(str_replace("&nbsp;", "", $str));
  }

  // UPDATE/DELETE
  function changeServices($link, $db_table, $id, $name, $price, $measure) {    
    // Выбираем тип действия. Если name пустое, то удаляем, иначе обновляем
    if ($name === "\n" || $name === '' || $name === "<br>" || $name === '&nbsp;') {
      // Удаляем
      $sql = "DELETE FROM `$db_table` WHERE `id` = $id";
      sendQueryToDB($link, $sql, 'Позиция удалена', 'При удалении произошла ошибка');
    } else {
      // Обновляем
      $sql = "UPDATE `$db_table`
      SET
        `name` = '$name',
        `price` = '$price',
        `measure` = '$measure'
      WHERE
        `id` = $id";
      sendQueryToDB($link, $sql, '', 'При изменении произошла ошибка');
    }
  }



  // ADD
  function addToServices($link, $db_table, $name, $price, $measure) {
    $sql = "INSERT INTO `$db_table` (`id`, `name`, `price`, `measure`) VALUES (NULL, '$name', '$price', '$measure')";
    sendQueryToDB($link, $sql, '', 'Добавить позицию не удалось');
  }



  // BUILD
  function buildServices($link, $db_table) {
    $sql = "SELECT * FROM `$db_table`";
    $sql_result = mysqli_fetch_all($link->query($sql), 1);
    $result = '';
    foreach ($sql_result as $service) {
      $result .= "<tr id='service-".$service['id']."'>";
	      $result .= "<td class='name' contenteditable='true' onBlur='updateDB(".$service['id'].")'>";
		      $result .= $service['name'] . '<br>';
	      $result .= "</td><td class='price' contenteditable='true' onBlur='updateDB(".$service['id'].")'>";
		      $result .= $service['price'] . '<br>';
	      $result .= "</td><td class='measure' contenteditable='true' onBlur='updateDB(".$service['id'].")'>";
		      $result .= $service['measure'] . '<br>';
	      $result .= "</td>";
      $result .= "</tr>";
    }
    echo $result;
    return;
  }



  // END RESULT
  function sendQueryToDB($link, $sql, $messageSuccess, $messageError) {
    try {
      $link->query($sql);
      echo $messageSuccess;
      return;
    } catch (Exception $e) {
      echo $messageError . ': ' . $e->getMessage();
      return;
    }
  }



  // DB
  $db_table = 'maybe_smart_services';
  if (preg_match('/OSPanel/', $_SERVER['DOCUMENT_ROOT'])) { // Местонахождение сайта (чтобы работало на базе)
    $link = new mysqli('localhost', 'root', '', 'maybe_smart');
  } else {
    $link = new mysqli('localhost', 'test1-u', 'Y9v0F7z4', 'test1-b');
  };
  mysqli_set_charset($link, "utf8mb4");
  if (mysqli_connect_errno()) {
    echo "Ошибка соединения с базой данных: %s\n", mysqli_connect_error();
    exit();
  }


  // GETTING DATA
  $post = json_decode(file_get_contents('php://input'), true);
  if ($post['type'] == 'build') {
    buildServices($link, $db_table);
    return;
  }


  // SENDING DATA
  if (isset($post['id'])) { $id = $post['id']; }
  $name = sTrim($post['name']);
  $price = sTrim($post['price']);
  $measure = sTrim($post['measure']);
  // ACTIONS
  if ($post['type'] == 'update') {
    changeServices($link, $db_table, $id, $name, $price, $measure);
  } elseif ($post['type'] === 'add') {
    addToServices($link, $db_table, $name, $price, $measure);
  }
}
dbFunctionsWrapper();