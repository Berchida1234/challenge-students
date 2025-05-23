<?php
//decalaration des variables de connexion a la base de donnee 
$host = "localhost";
$dbname = "projet";
$user = "root";
$pass = "";
//creeation de cette connection avec pdo
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
//gestion des erreures
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Telechargemet du donnees de l'etudiant
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $statement = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
    $statement->execute([$id]);
    $etud = $statement->fetch(PDO::FETCH_ASSOC);
}

//Mise a jour des donnees modifies
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $statement = $pdo->prepare("UPDATE etudiants SET nometud=?, prenometud=?, emailetud=?
    , age=?, tele=?, adressetud=? WHERE id=?");
    $statement->execute([
        $_POST['nometud'],
        $_POST['prenometud'],
        $_POST['emailetud'],
        $_POST['age'],
        $_POST['tele'],
        $_POST['adressetud'],
        $_POST['id']
    ]);
    echo "<script>alert('Étudiant modifié avec succès !'); window.location.href='afficher.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier étudiant</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Modifier étudiant</h2>
        <form method="post" action="modifier.php">
            <input type="hidden" name="id" value="<?= $etud['id'] ?>">
            <label>Nom :</label>
            <input type="text" name="nometud" value="<?= htmlspecialchars($etud['nometud']) ?>" required>
            <label>Prénom :</label>
            <input type="text" name="prenometud" value="<?= htmlspecialchars($etud['prenometud']) ?>" required>
            <label>Email :</label>
            <input type="email" name="emailetud" value="<?= htmlspecialchars($etud['emailetud']) ?>" required>
            <label>Âge :</label>
            <input type="number" name="age" value="<?= htmlspecialchars($etud['age']) ?>" required>
            <label>Téléphone :</label>
            <input type="tel" name="tele" value="<?= htmlspecialchars($etud['tele']) ?>" required>
            <label>Adresse :</label>
            <textarea name="adressetud" rows="3" required><?= htmlspecialchars($etud['adressetud']) ?></textarea>
            <button type="submit">Enregistrer les modifications</button>
        </form>
    </div>
</body>
</html>
