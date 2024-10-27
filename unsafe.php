$id = $_GET['id'];
$result = mysql_query("SELECT * FROM users WHERE id = $id");

