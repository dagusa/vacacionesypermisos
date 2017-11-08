<?php 
  require('wp-load.php');
  global $wpdb;
  $users = $wpdb->get_results("SELECT TIMESTAMPDIFF(YEAR, fecha_ingreso, CURDATE()) as antiguedad, id_user, dias_vacaciones from users where day(fecha_ingreso)=day(NOW()) and month(fecha_ingreso)=month(NOW())");
  $dias = 0;
  foreach ($users as $user) {
    if ($user->antiguedad == 1) {
      $dias = 6;
    }else if ($user->antiguedad == 2) {
      $dias = 8;
    }else if ($user->antiguedad == 3) {
      $dias = 10;
    }else if ($user->antiguedad == 4) {
      $dias = 12;
    }else if ($user->antiguedad >= 5 && $user->antiguedad <= 9) {
      $dias = 14;
    }else if ($user->antiguedad >= 10 && $user->antiguedad <= 14) {
      $dias = 16;
    }else if ($user->antiguedad >= 15 && $user->antiguedad <= 19) {
      $dias = 18;
    }
    $dias_antiguedad = $user->dias_vacaciones;
    date_default_timezone_set('america/mexico_city');
    $fecha = date('Y-m-d');
    $wpdb->update(
      'users',
      ['dias_vacaciones' => $dias],
      ['id_user' => $user->id_user],
      ['%d'],['%d']
    );
    $wpdb->query("INSERT INTO bitacora_diaria VALUES (NULL, $user->id_user, $dias_antiguedad, $dias, '$fecha')");
  }  
?>