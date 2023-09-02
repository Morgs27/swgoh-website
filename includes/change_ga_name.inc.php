<?php 
include '../functions/db_connect.php';



$loadout_id = $_POST['id'];
$name = $_POST['name'];

if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $name))
{
?>
    <script>
        window.location.href = "error.php?special"
    </script>
    <?php 
    exit();       
}
else {

$sql = "UPDATE ga_loadouts SET name = '$name' WHERE loadout_id = '$loadout_id'";
echo $sql;
$result = $conn->query($sql);
}