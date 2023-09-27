<?php
require('connection.php');


if(!empty($_POST['email']) && !empty($_POST['password'])){
    $email = $_POST['email'];

    $sqlQuery = 'SELECT u.id, u.password, u.collaborateurs_id, u.profile_id, u.email, c.firstname FROM utilisateurs as u 
    join collaborateurs as c On c.id=u.collaborateurs_id
    where email =:varEmail';
    $userStatement = $mysqlClient->prepare($sqlQuery);
    $userStatement->execute([
        'varEmail' => $email
    ]);
    $user = $userStatement->fetch();
    
    
    if ($user['email'] === $_POST['email'] && $user['password'] === $_POST['password']
    ) {
        $_SESSION['LOGGED_USER_ID']=$user['id'];
        $_SESSION['LOGGED_USER_COLLAB_ID']=$user['collaborateurs_id'];
        $_SESSION['LOGGED_USER_FIRSTNAME']=$user['firstname'];
        header("Location: dashboard.php");
        exit();
    } else {
        $errorMessage = 'Les informations envoyées ne permettent pas de vous identifier';
    }
        
}


?>

<form class="formLogin" action="" method="post">
    <H1>Connectez vous !</H1> 
    <div class="connectionInfo">
        <div class="labelInput">
            <label for="email">Email</label>
            <input type="email" name="email" placehorder="name@exemple.com">
        </div>
        <div class="labelInput">
            <label for="password">Password</label>
            <input type="password" name=password >
        </div>
        <?php if(isset($errorMessage)) : ?>
            <div>
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
    </div>
    <button type="submit">Se connecter</button>
    
</form>