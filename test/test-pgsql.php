<?php
require('../src/PDOWrapper.php');
$db = PDOWrapper::instance();

$db->configMaster('localhost', 'test', 'somebody', '', null, 'pgsql');
$db->configSlave('localhost', 'test', 'somebody', '', null, 'pgsql'); */


echo $db->create("testing",
                 array("id"    => "int not null auto_increment",
                       "name"  => "varchar(20)",
                       "email" => "varchar(20)",
                       "stamp" => "timestamp not null default current_timestamp"),
                       "id");

for($i = 0; $i < 9; $i++) {
  $rand = rand();
  $db->insert('testing', 
              array('name' => 'bob'.$rand,
	                  'email' => 'bob'.$rand.'@email.com'));
}

$result = $db->select('testing',
                      array("id", "name", "email"),
                      array("id" => 1, "name" => "bob%", "email" => "%3%"),
                      array(">", "LIKE", "LIKE"),
                      array("AND","AND"),
                      20,
                      null,
                      array('id'),
                      array('id' => ''),
                      false);


echo $db->select_max("testing","id")."<br />";
echo $db->select_min("testing","id",array("name" => "%3%"), array("LIKE"));

print_r($db->rowcount('testing',
                      null,
                      array("id" => 1, "name" => "bob%", "email" => "%3%"),
                      array(">", "LIKE", "LIKE"),
                      array("AND","AND")));
                      
echo $db->update('testing',
                 array("id" => 2),
                 array("id" => 3));
                 
if(empty($result[0])) {
  print_r($result);
}
else {
  foreach($result as $key => $value) {
    echo "Id: ".$value['id']."<br />";
	  echo "Name: ".$value['name']."<br />";
	  echo "Email: ".$value['email']."<br />";
	  echo "<hr />";
  }
}

echo $db->delete('testing',
                 array("id" => 1, "name" => "bob%"),
                 array("=", "LIKE"),
                 array("AND"));

print_r($db->query("SELECT COUNT(*) FROM testing WHERE name LIKE :name",array("name" => "%3%")));

echo $db->truncate("testing");
echo $db->drop("testing");
?>
