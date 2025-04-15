<?php
// الاتصال بقاعدة البيانات
$host = "localhost";
$dbname = "gestion";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion: " . $e->getMessage());
}

// إضافة أو تعديل بيانات الطالب
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nomuser"];
    $prenom = $_POST["prenomuser"];
    $email = $_POST["emailuser"];
    $age = $_POST["age"];
    
    if (!empty($_POST["id"])) {
        // تعديل الطالب
        $id = $_POST["id"];
        $stmt = $pdo->prepare("UPDATE users SET nomuser=?, prenomuser=?, emailuser=?, age=? WHERE id=?");
        $stmt->execute([$nom, $prenom, $email, $age, $id]);
    } else {
        // إضافة طالب جديد
        $stmt = $pdo->prepare("INSERT INTO users (nomuser, prenomuser, emailuser, age) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $age]);
    }
}

// حذف بيانات الطالب
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$id]);
}

// جلب البيانات من قاعدة البيانات
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo "<tr>
            <td>" . $user['id'] . "</td>
            <td>" . $user['nomuser'] . "</td>
            <td>" . $user['prenomuser'] . "</td>
            <td>" . $user['emailuser'] . "</td>
            <td>" . $user['age'] . "</td>
            <td>
                <a href='?edit=" . $user['id'] . "'>Modifier</a>
                <a href='?delete=" . $user['id'] . "'>Supprimer</a>
            </td>
          </tr>";
}

// إذا كان هناك تعديل:
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if ($user) {
        // قم بتعبئة الفورم بقيم البيانات الموجودة (للتعديل)
        echo "<script>
                document.getElementById('id').value = '" . $user['id'] . "';
                document.getElementById('nomuser').value = '" . $user['nomuser'] . "';
                document.getElementById('prenomuser').value = '" . $user['prenomuser'] . "';
                document.getElementById('emailuser').value = '" . $user['emailuser'] . "';
                document.getElementById('age').value = '" . $user['age'] . "';
              </script>";
    }
}
?>