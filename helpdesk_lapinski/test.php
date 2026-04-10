<?php
require_once 'include/navbar.php';
require_once 'config/config.php';

// Récupération du rôle
$perm_stmt = mysqli_query($link, "SELECT perm FROM users WHERE id = " . $_SESSION['id_users']);
$perm = mysqli_fetch_assoc($perm_stmt);
$role = $perm['perm'];

// Création ticket
if (isset($_POST['envoyer'])) {
    if (!empty($_POST['titre']) && !empty($_POST['description'])) {
        $sql = "INSERT INTO tickets (titre, description, created, statut, user_id) VALUES (?, ?, NOW(), 'ouvert', ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $_POST['titre'], $_POST['description'], $_SESSION['id_users']);
        mysqli_stmt_execute($stmt);

        $new_id = mysqli_insert_id($link);

        $sql2 = "INSERT INTO messages (ticket_id, user_id, message, created) VALUES (?, ?, ?, NOW())";
        $stmt2 = mysqli_prepare($link, $sql2);
        mysqli_stmt_bind_param($stmt2, "iis", $new_id, $_SESSION['id_users'], $_POST['description']);
        mysqli_stmt_execute($stmt2);

    } else {
        $message = "<span class='message'>* Vous devez remplir tous les champs !!</span>";
    }
}

// Envoi d'un message
if (isset($_POST['envoyer_message']) && !empty($_POST['message']) && !empty($_POST['ticket_id'])) {
    $sql = "INSERT INTO messages (ticket_id, user_id, message, created) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $_POST['ticket_id'], $_SESSION['id_users'], $_POST['message']);
    mysqli_stmt_execute($stmt);
    header('Location: index.php?id=' . (int)$_POST['ticket_id'] . '#tickets-view');
    exit;
}

// Changement de statut (technicien + admin uniquement)
if (isset($_POST['changer_statut']) && in_array($role, ['technicien', 'admin'])) {
    $sql = "UPDATE tickets SET statut = ? WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "si", $_POST['statut'], $_POST['ticket_id']);
    mysqli_stmt_execute($stmt);

    $msg_sys = "Statut changé → " . $_POST['statut'];
    $sql2 = "INSERT INTO messages (ticket_id, user_id, message, created) VALUES (?, ?, ?, NOW())";
    $stmt2 = mysqli_prepare($link, $sql2);
    mysqli_stmt_bind_param($stmt2, "iis", $_POST['ticket_id'], $_SESSION['id_users'], $msg_sys);
    mysqli_stmt_execute($stmt2);

    header('Location: index.php?id=' . (int)$_POST['ticket_id'] . '#tickets-view');
    exit;
}

// Liste des tickets
$stmt = mysqli_query($link, "SELECT id, titre, statut, created FROM tickets WHERE user_id = " . $_SESSION['id_users'] . " ORDER BY created DESC");
$tickets = $stmt->fetch_all(MYSQLI_ASSOC);

// Ticket sélectionné + messages
$ticket_ouvert = null;
$messages = [];
if (isset($_GET['id'])) {
    $tid = (int)$_GET['id'];
    $t = mysqli_query($link, "SELECT * FROM tickets WHERE id = $tid AND user_id = " . $_SESSION['id_users']);
    $ticket_ouvert = mysqli_fetch_assoc($t);

    if ($ticket_ouvert) {
        $m = mysqli_query($link, "
            SELECT m.*, u.nom, u.perm
            FROM messages m
            JOIN users u ON m.user_id = u.id
            WHERE m.ticket_id = $tid
            ORDER BY m.created ASC
        ");
        $messages = $m->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk - Lapinski</title>
    <link rel="stylesheet" href="css/creation.css">
    <style>
        .split-layout {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            height: 500px;
            margin-top: 2rem;
        }
        .sidebar {
            width: 260px;
            min-width: 260px;
            border-right: 1px solid #ddd;
            overflow-y: auto;
            background: #f9f9f9;
        }
        .sidebar-header {
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #999;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
        }
        .ticket-item {
            display: block;
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            text-decoration: none;
            color: inherit;
        }
        .ticket-item:hover { background: #f0f0f0; }
        .ticket-item.active { background: #e8eeff; border-left: 3px solid #4f6ef7; }
        .ticket-item-titre {
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .ticket-item-date { font-size: 12px; color: #999; margin-top: 3px; }

        .panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #fff;
        }
        .panel-header {
            padding: 12px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .panel-header h3 { margin: 0; font-size: 15px; }
        .panel-empty {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #bbb;
            font-size: 14px;
        }
        .messages-fil {
            flex: 1;
            overflow-y: auto;
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .msg-bubble { max-width: 65%; }
        .msg-bubble.moi { align-self: flex-end; }
        .msg-bubble.autre { align-self: flex-start; }
        .msg-meta { font-size: 11px; color: #999; margin-bottom: 3px; }
        .msg-bubble.moi .msg-meta { text-align: right; }
        .msg-content {
            padding: 8px 12px;
            border-radius: 12px;
            font-size: 14px;
            line-height: 1.5;
        }
        .msg-bubble.moi .msg-content { background: #4f6ef7; color: #fff; border-bottom-right-radius: 3px; }
        .msg-bubble.autre .msg-content { background: #f0f0f0; color: #222; border-bottom-left-radius: 3px; }
        .msg-event { text-align: center; font-size: 12px; color: #bbb; font-style: italic; }

        .panel-footer {
            padding: 12px 16px;
            border-top: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .footer-row { display: flex; gap: 8px; align-items: center; }
        .footer-row input[type="text"] {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        .footer-row select {
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
        }
        .btn-send {
            padding: 8px 16px;
            background: #4f6ef7;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-statut {
            padding: 8px 12px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }
    </style>
</head>
<body>

<?php if (isset($message)) echo $message; ?>

<form action="" method="POST" class="ticket-form">
    <div class="field">
        <label>🏷️ Titre</label>
        <input type="text" name="titre" placeholder="Titre du ticket..." autocomplete="off" required>
    </div>
    <div class="field">
        <label>📝 Description</label>
        <input type="text" name="description" placeholder="Décrivez votre problème..." autocomplete="off" required>
    </div>
    <button type="submit" class="btn btn-primary" name="envoyer">🗃️ Créer votre ticket</button>
</form>

<div class="split-layout" id="tickets-view">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-header">Mes tickets</div>
        <?php foreach ($tickets as $t): ?>
            <?php $active = (isset($_GET['id']) && $_GET['id'] == $t['id']) ? 'active' : ''; ?>
            <a href="?id=<?= $t['id'] ?>#tickets-view" class="ticket-item <?= $active ?>">
                <div class="ticket-item-titre"><?= htmlspecialchars($t['titre']) ?></div>
                <div class="ticket-item-date"><?= date('d/m/Y H:i', strtotime($t['created'])) ?></div>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- PANEL DROIT -->
    <div class="panel">
        <?php if (!$ticket_ouvert): ?>
            <div class="panel-empty">Sélectionnez un ticket</div>

        <?php else: ?>
            <div class="panel-header">
                <h3><?= htmlspecialchars($ticket_ouvert['titre']) ?></h3>
                <span><?= htmlspecialchars($ticket_ouvert['statut']) ?></span>
            </div>

            <div class="messages-fil">
                <?php foreach ($messages as $msg): ?>
                    <?php if (str_starts_with($msg['message'], 'Statut changé →')): ?>
                        <div class="msg-event">— <?= htmlspecialchars($msg['message']) ?> —</div>
                    <?php else: ?>
                        <?php $moi = ($msg['user_id'] == $_SESSION['id_users']) ? 'moi' : 'autre'; ?>
                        <div class="msg-bubble <?= $moi ?>">
                            <div class="msg-meta">
                                <?= htmlspecialchars($msg['nom']) ?> · <?= date('d/m H:i', strtotime($msg['created'])) ?>
                            </div>
                            <div class="msg-content"><?= htmlspecialchars($msg['message']) ?></div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="panel-footer">

                <?php if (in_array($role, ['technicien', 'admin'])): ?>
                <form action="" method="POST">
                    <input type="hidden" name="ticket_id" value="<?= $ticket_ouvert['id'] ?>">
                    <div class="footer-row">
                        <select name="statut">
                            <option value="ouvert"   <?= $ticket_ouvert['statut'] == 'ouvert'   ? 'selected' : '' ?>>Ouvert</option>
                            <option value="en cours" <?= $ticket_ouvert['statut'] == 'en cours' ? 'selected' : '' ?>>En cours</option>
                            <option value="resolu"   <?= $ticket_ouvert['statut'] == 'resolu'   ? 'selected' : '' ?>>Résolu</option>
                            <option value="ferme"    <?= $ticket_ouvert['statut'] == 'ferme'    ? 'selected' : '' ?>>Fermé</option>
                        </select>
                        <button type="submit" name="changer_statut" class="btn-statut">Mettre à jour</button>
                    </div>
                </form>
                <?php endif; ?>

                <form action="" method="POST">
                    <input type="hidden" name="ticket_id" value="<?= $ticket_ouvert['id'] ?>">
                    <div class="footer-row">
                        <input type="text" name="message" placeholder="Écrire un message..." autocomplete="off" required>
                        <button type="submit" name="envoyer_message" class="btn-send">Envoyer</button>
                    </div>
                </form>

            </div>
        <?php endif; ?>
    </div>

</div>

</body>
</html>