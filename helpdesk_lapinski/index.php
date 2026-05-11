<?php

require_once 'include/auth.php';
require_once 'include/navbar.php';
require_once 'config/config.php';

if (isset($_POST['envoyer'])) {
    if (!empty($_POST['titre']) && !empty($_POST['description'])) {
        $sql = "INSERT INTO tickets (titre, description, cree_le, statut, user_id) VALUES (?, ?, NOW(), 'ouvert', ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $_POST['titre'], $_POST['description'], $_SESSION['id_users']);
        mysqli_stmt_execute($stmt);
    } else {
        $message = "<span class='message'>* Vous devez remplir tous les champs !!</span>";
    }
}

$stmt = mysqli_prepare($link, "SELECT id, titre, description, statut, user_id, cree_le FROM tickets WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['id_users']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tickets = $result->fetch_all(MYSQLI_ASSOC);

// Filtre via ?statut=ouvert ou ?statut=ferme (par défaut : tout afficher)
$filtre = $_GET['statut'] ?? '';

$displayticketnow     = ($filtre === 'ferme')  ? 'none' : 'block';
$displayticketarchive = ($filtre === 'ouvert' || $filtre === 'en_cours') ? 'none' : 'block';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk - Lapinski</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
</head>
<body>

<?php if (isset($message)) print($message); ?>

<form action="" method="POST" class="ticket-form">
    <div class="field">
        <label>🏷️ Titre</label>
        <input type="text" name="titre" placeholder="Titre du ticket..." autocomplete="off" required>
    </div>
    <div class="field">
        <label>📝 Description</label>
        <input type="text" name="description" placeholder="Décrivez votre problème..." autocomplete="off" required>
    </div>
    <button type="submit" class="btn btn-primary" name="envoyer">
        🗃️ Créer votre ticket
    </button>
</form>

<!-- Tickets ouverts -->
<div class="tickets-container" style="display: <?= $displayticketnow ?>;">
    <h2>Tickets ouverts</h2>
    <table class="tickets-table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Titre</th>
                <th scope="col">Description</th>
                <th scope="col">Statut</th>
                <th scope="col">Date de création</th>
                <th>Voir le ticket</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <?php if ($ticket['statut'] === 'ouvert' || $ticket['statut'] === 'en_cours'): ?>
                    <tr>
                        <td><?= htmlspecialchars($ticket['id']) ?></td>
                        <td><?= htmlspecialchars($ticket['titre']) ?></td>
                        <td><?= htmlspecialchars($ticket['description']) ?></td>
                        <td><?= htmlspecialchars($ticket['statut']) ?></td>
                        <td><?= htmlspecialchars($ticket['cree_le']) ?></td>
                        <td>
                            <a class="view-ticket" href="historique.php?id=<?= htmlspecialchars($ticket['id']) ?>" role="button">
                                <input class="voir-ticket" type="submit" value="voir">
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Tickets archivés (fermés) -->
<div class="tickets-container" style="display: <?= $displayticketarchive ?>;">
    <h2>Tickets archivés</h2>
    <table class="tickets-table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Titre</th>
                <th scope="col">Description</th>
                <th scope="col">Statut</th>
                <th scope="col">Date de création</th>
                <th>Voir le ticket</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <?php if ($ticket['statut'] === 'ferme'): ?>
                    <tr>
                        <td><?= htmlspecialchars($ticket['id']) ?></td>
                        <td><?= htmlspecialchars($ticket['titre']) ?></td>
                        <td><?= htmlspecialchars($ticket['description']) ?></td>
                        <td><?= htmlspecialchars($ticket['statut']) ?></td>
                        <td><?= htmlspecialchars($ticket['cree_le']) ?></td>
                        <td>
                            <a class="view-ticket" href="historique.php?id=<?= htmlspecialchars($ticket['id']) ?>" role="button">
                                <input class="voir-ticket" type="submit" value="voir">
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>