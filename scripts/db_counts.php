<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=myproject;charset=utf8', 'root', '');
    $q1 = $pdo->query('select count(*) as c from users')->fetch(PDO::FETCH_ASSOC);
    $q2 = $pdo->query("select count(distinct users.id) as c from users left join members on members.user_id=users.id where (members.current_package_id IS NULL or members.current_package_id=0 or members.package_validity < NOW())")->fetch(PDO::FETCH_ASSOC);
    $q3 = $pdo->query("select count(distinct users.id) as c from users left join members on members.user_id=users.id where (members.current_package_id IS NULL or members.current_package_id=0 or members.package_validity < NOW()) and not exists (select 1 from fresh_data where fresh_data.mobile=users.phone and fresh_data.assigned_to IS NOT NULL)")->fetch(PDO::FETCH_ASSOC);
    echo "total_users:" . $q1['c'] . "\n";
    echo "free_members_filter:" . $q2['c'] . "\n";
    echo "database_listing:" . $q3['c'] . "\n";
} catch (PDOException $e) {
    echo "ERROR:" . $e->getMessage() . "\n";
}
