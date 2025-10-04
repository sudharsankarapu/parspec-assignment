<?php
$dbfile = __DIR__.'/demo.db';
if (!file_exists($dbfile)) {
  $db = new PDO('sqlite:'.$dbfile);
  $db->exec("CREATE TABLE users (id INTEGER PRIMARY KEY, username TEXT UNIQUE, password TEXT);");
  $db->exec("INSERT INTO users (username,password) VALUES ('admin','adminpass');");
  $db->exec("INSERT INTO users (username,password) VALUES ('user','userpass');");
  echo "DB created\n";
} else {
  echo "DB exists\n";
}
