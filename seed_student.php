<?php
// seed.php - run once to create admin + sample data
require_once 'connection.php';

try {
    // admin
    $a = $conn->prepare("INSERT IGNORE INTO admin_info (username,password) VALUES (:u,:p)");
    $a->execute([
        ':u' => 'admin',
        ':p' => password_hash('admin123', PASSWORD_DEFAULT)
    ]);

    // sample student
    $s = $conn->prepare("INSERT IGNORE INTO student_info (id,name,email,contact,gender,password,DOB,department,course,branch,year,sports,player)
        VALUES (:id,:name,:email,:contact,:gender,:password,:dob,:department,:course,:branch,:year,:sports,:player)");
    $s->execute([
        ':id' => '22671A1210',
        ':name' => 'D SANJAY',
        ':email' => 'sanjay@example.com',
        ':contact' => '9988776655',
        ':gender' => 'Male',
        ':password' => password_hash('student123', PASSWORD_DEFAULT),
        ':dob' => '2003-01-01',
        ':department' => 'B_Tech',
        ':course' => 'B_Tech',
        ':branch' => 'CSE',
        ':year' => '3rdyear',
        ':sports' => 'Cricket,Football',
        ':player' => 'Player1'
    ]);

    // sample events
    $ev = $conn->prepare("INSERT INTO events (event_name,event_type,college_name,location,date,prize1,prize2,prize3,sports)
        VALUES (:en,:et,:cn,:loc,:d,:p1,:p2,:p3,:sp)");
    $ev->execute([
        ':en'=>'Intercol Cricket',':et'=>'Outdoor',':cn'=>'JBIET',':loc'=>'Main Ground',':d'=>'2025-09-15',
        ':p1'=>'Trophy',':p2'=>'5000',':p3'=>'2000',':sp'=>'Cricket'
    ]);
    $ev->execute([
        ':en'=>'Table Tennis Open',':et'=>'Indoor',':cn'=>'JBIET',':loc'=>'Indoor Hall',':d'=>'2025-09-18',
        ':p1'=>'Medal',':p2'=>'3000',':p3'=>'1000',':sp'=>'Table Tennis'
    ]);

    // sample participation
    $pid = $conn->prepare("INSERT INTO participation (student_id,event_id,player_role,location,college) VALUES (:s,:e,:r,:loc,:col)");
    $pid->execute([':s'=>'22671A1210', ':e'=>1, ':r'=>'Player1', ':loc'=>'Main Ground', ':col'=>'JBIET']);

    // sample result
    $res = $conn->prepare("INSERT INTO results (student_id,name,course,branch,roll_number,sports,location,date,position) VALUES
        (:sid,:name,:course,:branch,:roll,:sports,:loc,:d,:pos)");
    $res->execute([
        ':sid'=>'22671A1210', ':name'=>'D SANJAY', ':course'=>'B_Tech', ':branch'=>'CSE', ':roll'=>'22671A1210',
        ':sports'=>'Cricket', ':loc'=>'Main Ground', ':d'=>'2025-09-15', ':pos'=>'1st'
    ]);

    echo "Seed complete. Admin: admin/admin123  Student: 22671A1210/student123";
} catch (PDOException $ex) {
    echo "Seed error: " . $ex->getMessage();
}
