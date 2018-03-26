<?php 
header('Content-Type: application/json');
$limit=16;

require_once './dblogin.php';
require_once './cookieLogin.php';
$q=$db->prepare('SELECT id, name, type, path FROM files WHERE id_user=:id AND deleted=0 ORDER BY date DESC LIMIT :limit OFFSET :offset');
$_POST['offset']--;
$q->bindValue(':id', $user['id']);
$q->bindValue(':limit', $limit, PDO::PARAM_INT);
$q->bindValue(':offset', (int)$_POST['offset'], PDO::PARAM_INT);
$count=$user['fileCount'];
$q->execute();
$q=$q->fetchAll();
$i=0;
foreach($q as $row){
    $response['data'][$i]['url']=$conf['url'].basename($row['path']);
    $response['data'][$i]['newName']=basename($row['path']);
    $response['data'][$i]['name']=$row['name'];
    $response['data'][$i]['type']=$row['type'];
    $response['data'][$i]['id']=$row['id'];
    $i++;
}
$response['total']=$count;
$response['start']=++$_POST['offset'];
$response['end']=$response['start']+count($q)-1;
echo json_encode($response);
?>
