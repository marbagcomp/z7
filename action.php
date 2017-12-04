<?php
session_start;
function format_folder_size($size)
{
 if ($size >= 1073741824)
 {
  $size = number_format($size / 1073741824, 2) . ' GB';
 }
    elseif ($size >= 1048576)
    {
        $size = number_format($size / 1048576, 2) . ' MB';
    }
    elseif ($size >= 1024)
    {
        $size = number_format($size / 1024, 2) . ' KB';
    }
    elseif ($size > 1)
    {
        $size = $size . ' bytes';
    }
    elseif ($size == 1)
    {
        $size = $size . ' byte';
    }
    else
    {
        $size = '0 bytes';
    }
 return $size;
}
function get_folder_size($folder_name)
{
 $total_size = 0;
 $file_data = scandir($folder_name);
 foreach($file_data as $file)
 {
  if($file === '.' or $file === '..')
  {
   continue;
  }
  else
  {
   $path = $folder_name . '/' . $file;
   $total_size = $total_size + filesize($path);
  }
 }
 return format_folder_size($total_size);
}
if(isset($_POST["action"]))
{
 if($_POST["action"] == "fetch")
 {
 $folder = array_filter(glob('pliki/'.$_COOKIE['login'].'/*'), 'is_dir');
  
  $output = '
  <table class="table table-striped"
   <tr class="danger">
    <th>Nazwa folderu</th>
    <th>Ilość plików</th>
	<th>Dodaj plik</th>
    <th>Zmień nazwę</th>
    <th>Usuń folder</th>
    <th>Pokaż pliki</th>
   </tr>
   ';
  if(count($folder) > 0)
  {
   foreach($folder as $name)
   {
	$name2=str_replace('pliki/'.$_COOKIE['login'].'/', '', "$name");
    $output .= '
     <tr class="info">
      <td>'.$name2.'</td>
      <td>'.(count(scandir($name)) - 2).'</td>
	  <td><button type="button" name="upload" data-name="'.$name.'" class="upload btn btn-success btn-sm">Dodaj plik</button></td>
      <td><button type="button" name="update" data-name="'.$name.'" class="update btn btn-warning btn-xs">Zmień nazwę</button></td>
      <td><button type="button" name="delete" data-name="'.$name.'" class="delete btn btn-danger btn-xs">Usuń folder</button></td>
      <td><button type="button" name="view_files" data-name="'.$name.'" class="view_files btn btn-info btn-xs">Pokaż pliki</button></td>
     </tr>';
   }
  }
  else
  {
   $output .= '
    <tr>
     <td colspan="6">Brak folderów do wyświetlenia</td>
    </tr>
   ';
  }
  $output .= '</table>';
  echo $output;
 }
 
 if($_POST["action"] == "create")
 {
  if(!file_exists($_POST["folder_name"])) 
  {
	 $fname = $_POST['folder_name'];
	$path55 = 'pliki' . DIRECTORY_SEPARATOR .$_COOKIE['login']. DIRECTORY_SEPARATOR . basename($fname);
  mkdir($path55, 0777, true);
   echo 'Folder stworzony';
  }
  else
  {
   echo 'Istnieje już taki folder';
  }
 }
 if($_POST["action"] == "change")
 {
  if(!file_exists($_POST["folder_name"]))
  {
   rename($_POST["old_name"], $_POST["folder_name"]);
   echo 'Zmieniono nazwę';
  }
  else
  {
   echo 'Istnieje już taki folder';
  }
 }
 
 if($_POST["action"] == "delete")
 {
  $files = scandir($_POST["folder_name"]);
  foreach($files as $file)
  {
   if($file === '.' or $file === '..')
   {
    continue;
   }
   else
   {
    unlink($_POST["folder_name"] . '/' . $file);
   }
  }
  if(rmdir($_POST["folder_name"]))
  {
   echo 'Folder został usunięty';
  }
 }
 
 if($_POST["action"] == "fetch_files")
 {
  $file_data = scandir($_POST["folder_name"]);
  $output = '
  <table class="table table-bordered table-striped">
   <tr>
    <th>Plik</th>
    <th>Nazwa pliku</th>
	<th>Ściągnij</th>
    <th>Usuń</th>
   </tr>
  ';
  
  foreach($file_data as $file)
  {
   if($file === '.' or $file === '..')
   {
    continue;
   }
   else
   {
    $path = $_POST["folder_name"] . '/' . $file;
    $output .= '
    <tr>
     <td><img src="'.$path.'" class="img-thumbnail" height="50" width="50" /></td>
     <td contenteditable="true" data-folder_name="'.$_POST["folder_name"].'"  data-file_name = "'.$file.'" class="change_file_name">'.$file.'</td>
     <td> <a href="'.$path.'">
          <span class="glyphicon glyphicon-download-alt"></span>
        </a></td>
	 <td><button name="remove_file" class="remove_file btn btn-danger btn-xs" id="'.$path.'">Usuń</button></td>
    </tr>
    ';
   }
  }
  $output .='</table>';
  echo $output;
 }
 
 if($_POST["action"] == "remove_file")
 {
  if(file_exists($_POST["path"]))
  {
   unlink($_POST["path"]);
   echo 'Plik został usunięty';
  }
 }
 if($_POST["action"] == "change_file_name")
 {
  $old_name = $_POST["folder_name"] . '/' . $_POST["old_file_name"];
  $new_name = $_POST["folder_name"] . '/' . $_POST["new_file_name"];
  if(rename($old_name, $new_name))
  {
   echo 'Nazwa pliku została zmieniona';
  }
  else
  {
   echo 'Nazwa pliku nie została zmieniona';
  }
 }
}
?>