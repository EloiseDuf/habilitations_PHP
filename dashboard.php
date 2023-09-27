<?php

session_start();

require('connection.php');


    $collaborateurId = $_SESSION['LOGGED_USER_COLLAB_ID'];


$sqlQuery = 'SELECT c.name, c.firstname, ch.collaborateurs_id as chId, ch.habilitations_id, h.name, ch.training_date, ch.expiration_date FROM collaborateurs_habilitations as ch
JOIN collaborateurs as c ON ch.collaborateurs_id= c.id
JOIN habilitations as h ON ch.habilitations_id=h.id
WHERE c.id=?';
$habilitationsStatement = $mysqlClient->prepare($sqlQuery);
$habilitationsStatement->bindParam(1, $collaborateurId, PDO::PARAM_INT);
$habilitationsStatement->execute();
$habilitations = $habilitationsStatement->fetchAll();

$sqlQuery2 = 'SELECT count(habilitations_id) as nbHab FROM collaborateurs_habilitations as ch
WHERE ch.collaborateurs_id = ?';
$countHabilitationsStatement = $mysqlClient->prepare($sqlQuery2);
$countHabilitationsStatement->bindParam(1, $collaborateurId, PDO::PARAM_INT);
$countHabilitationsStatement->execute();
$countHabilitations = $countHabilitationsStatement->fetch();

$sqlQuery3 = 'SELECT count(expiration_date) as nbExp FROM collaborateurs_habilitations as ch
WHERE ch.collaborateurs_id = ? and expiration_date<CURDATE()';
$countExpirationsStatement = $mysqlClient->prepare($sqlQuery3);
$countExpirationsStatement->bindParam(1, $collaborateurId, PDO::PARAM_INT);
$countExpirationsStatement->execute();
$countExpirations = $countExpirationsStatement->fetch();

?>

<!-- SELECT c.name, c.firstname, ch.collaborateurs_id as chId, ch.habilitations_id, h.name FROM collaborateurs_habilitations as ch
JOIN collaborateurs as c ON ch.collaborateurs_id= c.id
JOIN habilitations as h ON ch.habilitations_id=h.id
WHERE c.id=2; -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./CSS/navBar.css">
    <link rel="stylesheet" href="./CSS/dashboard.css">
</head>
<body>
    <header>
        <?php include_once('navBar.php');?>
    </header>
    <main class="dashboardContents">
        <section class="synthese">
            <div class="containerSynthese">
                <p>Bonjour <?php echo $_SESSION['LOGGED_USER_FIRSTNAME'];?>
                <br>vous détenez <?php echo $countHabilitations['nbHab']; ?> habilitation(s) 
                </br> et vous avez <?php echo $countExpirations['nbExp'];?> habilitation(s) expirées</p>
            </div>
        </section>
        <section class="board">
            <div class="boardContent">
                <p>Vos habilitations</p>
                <table>
                    <thead>
                        <tr>
                            <th>Habilitation</th>
                            <th>Date de formation</th>
                            <th>Date de validité</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($habilitations as $habilitation ): ?>
                        <tr>
                            <td><?php echo $habilitation['name']; ?> </td>
                            <td><?php echo $habilitation['training_date']; ?> </td>
                            <td><?php echo $habilitation['expiration_date']; ?> </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>