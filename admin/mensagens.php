<?php
/**
 * Atlas Centro Sul — Mensagens do Formulário de Contacto
 */
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/helpers.php';
protegerRota();

$pdo   = getDB();
$flash = obterFlash();

// Marcar como lida
if (isset($_GET['ler']) && is_numeric($_GET['ler'])) {
    $pdo->prepare("UPDATE mensagens SET lida = 1 WHERE id = ?")->execute([(int)$_GET['ler']]);
    header('Location: /atlas/admin/mensagens.php');
    exit;
}

// Eliminar
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    $pdo->prepare("DELETE FROM mensagens WHERE id = ?")->execute([(int)$_GET['eliminar']]);
    flashMensagem('success', 'Mensagem eliminada.');
    header('Location: /atlas/admin/mensagens.php');
    exit;
}

// Ver detalhe
$detalhe = null;
if (isset($_GET['ver']) && is_numeric($_GET['ver'])) {
    $stmt = $pdo->prepare("SELECT * FROM mensagens WHERE id = ? LIMIT 1");
    $stmt->execute([(int)$_GET['ver']]);
    $detalhe = $stmt->fetch();
    if ($detalhe && !$detalhe['lida']) {
        $pdo->prepare("UPDATE mensagens SET lida = 1 WHERE id = ?")->execute([$detalhe['id']]);
    }
}

$total = (int)$pdo->query("SELECT COUNT(*) FROM mensagens")->fetchColumn();
$paginaActual = max(1, (int)($_GET['p'] ?? 1));
$pag = paginar($total, 15, $paginaActual);

$stmt = $pdo->prepare("SELECT * FROM mensagens ORDER BY lida ASC, criado_em DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $pag['por_pagina'], PDO::PARAM_INT);
$stmt->bindValue(':offset', $pag['offset'], PDO::PARAM_INT);
$stmt->execute();
$mensagens = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens — Atlas Centro Sul Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/atlas/assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/_sidebar.php'; ?>
<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-title">✉️ Mensagens (<?= $total ?>)</div>
    </header>
    <main class="content">
        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['tipo'] ?>"><?= htmlspecialchars($flash['mensagem']) ?></div>
        <?php endif; ?>

        <?php if ($detalhe): ?>
        <!-- Detalhe da mensagem -->
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <h2>📩 <?= htmlspecialchars($detalhe['assunto'] ?? 'Sem assunto') ?></h2>
                <a href="/atlas/admin/mensagens.php" class="btn btn-outline btn-sm">← Voltar</a>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
                    <div>
                        <label style="font-size:12px;color:var(--gray-500);font-weight:600;text-transform:uppercase;">Remetente</label>
                        <p style="font-weight:700;color:var(--navy);font-size:16px;"><?= htmlspecialchars($detalhe['nome']) ?></p>
                    </div>
                    <div>
                        <label style="font-size:12px;color:var(--gray-500);font-weight:600;text-transform:uppercase;">Email</label>
                        <p><a href="mailto:<?= htmlspecialchars($detalhe['email']) ?>" style="color:var(--navy);font-weight:600;"><?= htmlspecialchars($detalhe['email']) ?></a></p>
                    </div>
                    <?php if ($detalhe['telefone']): ?>
                    <div>
                        <label style="font-size:12px;color:var(--gray-500);font-weight:600;text-transform:uppercase;">Telefone</label>
                        <p style="font-weight:600;"><?= htmlspecialchars($detalhe['telefone']) ?></p>
                    </div>
                    <?php endif; ?>
                    <div>
                        <label style="font-size:12px;color:var(--gray-500);font-weight:600;text-transform:uppercase;">Data</label>
                        <p><?= formatarData($detalhe['criado_em']) ?></p>
                    </div>
                </div>
                <div style="background:var(--gray-100);border-radius:10px;padding:20px;line-height:1.7;color:var(--navy-dark);">
                    <?= nl2br(htmlspecialchars($detalhe['mensagem'])) ?>
                </div>
                <div style="margin-top:20px;display:flex;gap:12px;">
                    <a href="mailto:<?= htmlspecialchars($detalhe['email']) ?>?subject=Re: <?= urlencode($detalhe['assunto'] ?? '') ?>" class="btn btn-primary">📧 Responder por Email</a>
                    <a href="/atlas/admin/mensagens.php?eliminar=<?= $detalhe['id'] ?>" class="btn btn-danger" onclick="return confirm('Eliminar esta mensagem?')">🗑️ Eliminar</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="card">
            <?php if (empty($mensagens)): ?>
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h3>Nenhuma mensagem ainda</h3>
                <p>As mensagens do formulário de contacto aparecerão aqui.</p>
            </div>
            <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>Remetente</th><th>Assunto</th><th>Data</th><th>Estado</th><th>Ações</th></tr></thead>
                    <tbody>
                    <?php foreach ($mensagens as $msg): ?>
                        <tr style="<?= !$msg['lida'] ? 'font-weight:700;' : '' ?>">
                            <td>
                                <div style="font-weight:<?= !$msg['lida'] ? '800' : '600' ?>;color:var(--navy);"><?= htmlspecialchars($msg['nome']) ?></div>
                                <div style="font-size:12px;color:var(--gray-500);"><?= htmlspecialchars($msg['email']) ?></div>
                            </td>
                            <td><?= htmlspecialchars(truncar($msg['assunto'] ?? 'Sem assunto', 40)) ?></td>
                            <td style="font-size:13px;color:var(--gray-500);white-space:nowrap;"><?= formatarData($msg['criado_em']) ?></td>
                            <td><?= !$msg['lida'] ? '<span class="badge badge-info">Nova</span>' : '<span class="badge badge-success">Lida</span>' ?></td>
                            <td>
                                <div style="display:flex;gap:6px;">
                                    <a href="/atlas/admin/mensagens.php?ver=<?= $msg['id'] ?>" class="btn btn-outline btn-sm">👁️ Ver</a>
                                    <a href="/atlas/admin/mensagens.php?eliminar=<?= $msg['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Eliminar?')">🗑️</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>
