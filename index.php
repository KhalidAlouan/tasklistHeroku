<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Task List Khalid</title>
  </head>
 <?php
 
      $database = parse_url(getenv("DATABASE_URL"));
      $db = new PDO("pgsql:" . sprintf(
        "host=%s;port=%s;user=%s;password=%s;dbname=%s",
        $database["host"],
        $database["port"],
        $database["user"],
        $database["pass"],
        ltrim($database["path"], "/")
      ));



 if(isset( $_GET['delete'] )){
    $task = $_GET['delete'];
    $query = $db->prepare("DELETE FROM mytask WHERE id = ".$task.";");
    $query->execute();
 }
 if(isset($_GET['PorHacer'])){
  $query = $db->prepare("UPDATE mytask SET estado = 0 WHERE id = ".$_GET['PorHacer'].";");
  $query->execute();
 }
 if(isset($_GET['hecho'])){
  $query = $db->prepare("UPDATE mytask SET estado = 1 WHERE id = ".$_GET['hecho'].";");
  $query->execute();
}
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nom = $_POST['nom'];
    $query = $db->prepare("INSERT INTO mytask (nombre,estado) VALUES ('".$nom."',0)");
    $query->execute();
  }
  
    $result2 = $db->query("SELECT * FROM mytask where estado = 0");
    $result1 = $db->query("SELECT * FROM mytask where estado = 1");
  ?>
  <body>
    <h1>TASK LIST</h1>
    
    <?php
    echo "
    <div>
    <form action='' method='post'>
        Nueva tarea:
        <input type='text' name='nom'>
        <input type='submit' value='nueva tarea'>
    </form>";
    echo "</div>";
    // done
    echo "<h3>Hechos</h3>";
      echo "<table>\n";
      foreach($result1 as $row1){
        echo "\t<tr>\n";
          echo "\t\t<td>".$row1['nombre']." <a href='index.php?delete=".$row1['id']."'>Eliminar</a></td>\n";
          echo "\t\t<td>Hecho <a href='index.php?PorHacer=".$row1['id']."'>Cambiar</a></td>\n";        
          echo "\t</tr>\n";
      }
      echo "</table>\n";
      //not done
      echo "<h3>Por Hacer</h3>";
      echo "<table>\n";
      foreach($result2 as $row2){
        echo "\t<tr>\n";
          echo "\t\t<td>".$row2['nombre']." <a href='index.php?delete=".$row2['id']."'>Eliminar</a></td>\n";
          echo "\t\t<td>Por Hacer <a href='index.php?hecho=".$row2['id']."'>Cambiar</a></td>\n";    
          echo "\t</tr>\n";
      }
      echo "</table>\n";
   ?>
  </body>
