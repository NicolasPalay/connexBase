<?php
require_once '_connec.php';

/*Connection à la base de données*/
try {
    $pdo = new \PDO(DSN, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
    echo 'conn ok';
}catch(Exception $e)
{
    echo "Échec : " . $e->getMessage();
}

/*selection de tous les éléments de la table Friend*/
$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);

/*Affichage du content de la table Friend*/
foreach ($friends as $friend){
    echo $friend['firstname'] . ' ' . $friend['lastname'] .'<br>';
}
?>

<form action="index.php" method="post">
    <div>
        <label for="firstName">Text Input:</label>
        <input type="text" name="firstName" id="firstName" value=""/>
    </div>
    <div>
        <label for="lastName">Text Input:</label>
        <input type="text" name="lastName" id="lastName" value=""/>
    </div>

    <div>
        <input type="submit" value="Submit" />
</form>
<?php

$errors=[];
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_POST['firstname']) || trim($_POST['firstName'])=== '')
        $errors[] = "Le prénom est obligatoire";
    if(!isset($_POST['lastname']) || trim($_POST['lastName']) === '')
        $errors[] = "Le nom est obligatoire";
    if(empty($errors)) {
        // traitement du formulaire
        // puis redirection
        header('Location: index.php');
    }
}
 // Affichage des éventuelles erreurs
if (count($errors) > 0) : ?>
    <div class="border border-danger rounded p-3 m-5 bg-danger">
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach;
            ?>
        </ul>
    </div>

<?php
endif;

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];

$query = 'INSERT INTO friend (firstName, lastName ) VALUES (:firstName, :lastName )';
$statement = $pdo->prepare($query);

// On lie les valeurs saisies dans le formulaire à nos placeholders
$statement->bindValue(':firstName', $firstName, \PDO::PARAM_STR);
$statement->bindValue(':lastName', $lastName, \PDO::PARAM_STR);

$statement->execute();

?>

