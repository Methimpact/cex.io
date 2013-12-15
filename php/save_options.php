<?php
$OPTION = $_GET['option'];
$Uname = $_GET['username'];
$Api = $_GET['api'];
$Secret = $_GET['secret'];
$Filename = "options.csv";

switch ($OPTION)
{
case "create":
  $list = array (
    array('USER', $Uname),
    array('API', $Api),
    array('SECRET', $Secret)
  );
  $fp = fopen($Filename, 'w');
  foreach ($list as $fields)
  {
   fputcsv($fp, $fields);
  }

fclose($fp);
echo "Created";
  break;
case "test":
    if($_GET['filename']){$filename = $_GET['filename'];}
    else $filename = "options.csv";

if (file_exists($filename)) {
    echo "The file $filename exists";
} else {
    echo "The file $filename does not exist";
}
  break;
case "delete":
  
if (!unlink($Filename))
  {
  echo ("Error deleting");
  }
else
  {
  echo ("Deleted");
  }
  break;
case "read":
  $row = 1;
if (($handle = fopen($Filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        //echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            echo $data[$c].",";// . "<br />\n";
        }
    }
    fclose($handle);}
    break;
default:
  echo "Must be called from within CEX bot!";
}




?> 