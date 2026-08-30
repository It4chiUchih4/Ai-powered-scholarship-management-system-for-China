<?php
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$tables = ['universities','programs','scholarships','recommendations','users'];
foreach ($tables as $table) {
    $count = $db->query("SELECT count(*) AS c FROM $table")->fetch(PDO::FETCH_ASSOC)['c'];
    echo "$table=$count\n";
}
$active = $db->query("SELECT count(*) AS c FROM universities WHERE is_active=1")->fetch(PDO::FETCH_ASSOC)['c'];
echo "active_universities=$active\n";
$active = $db->query("SELECT count(*) AS c FROM programs WHERE is_active=1")->fetch(PDO::FETCH_ASSOC)['c'];
echo "active_programs=$active\n";
$user = $db->query("SELECT user_id,email,preferred_field,cgpa,language_proficiency,degree_seeking FROM users LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if ($user) {
    echo "user={$user['email']} prefer={$user['preferred_field']} cgpa={$user['cgpa']} lang={$user['language_proficiency']} degree={$user['degree_seeking']}\n";
}
