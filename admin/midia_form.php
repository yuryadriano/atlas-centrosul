<?php
/**
 * Atlas Centro Sul — Formulário de Multimédia (Criar/Editar)
 */
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/helpers.php';
protegerRota();

$pdo  = getDB();
$id   = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
$item = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM midia WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) { header('Location: /atlas/admin/midia.php'); exit; }
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo    = sanitizar($_POST['titulo'] ?? '');
    $descricao = sanitizar($_POST['descricao'] ?? '');
    $tipo      = sanitizar($_POST['tipo'] ?? 'foto');
    $categoria = sanitizar($_POST['categoria'] ?? 'geral');
    $destaque  = isset($_POST['destaque']) ? 1 : 0;
    $videoUrl  = sanitizar($_POST['video_url'] ?? '');

    if (empty($titulo)) $erros[] = 'O título é obrigatório.';

    $urlOuCaminho = $item['url_ou_caminho'] ?? null;

    if ($tipo === 'video') {
        if (empty($videoUrl) && !$urlOuCaminho) $erros[] = 'Insira o link do vídeo.';
        if (!empty($videoUrl)) $urlOuCaminho = $videoUrl;
    } else {
        // Upload de foto
        if (!empty($_FILES['foto']['name'])) {
            $result = uploadImagem($_FILES['foto'], 'galeria');
            if ($result) {
                $urlOuCaminho = $result;
            } else {
                $erros[] = 'Imagem inválida. Use JPG, PNG ou WebP (máx. 5MB).';
            }
        } elseif (!$urlOuCaminho) {
            $erros[] = 'Selecione uma foto.';
        }
    }

    if (empty($erros)) {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE midia SET titulo=:titulo,descricao=:descricao,tipo=:tipo,url_ou_caminho=:url,categoria=:cat,destaque=:dest WHERE id=:id");
            $stmt->execute([':titulo'=>$titulo,':descricao'=>$descricao,':tipo'=>$tipo,':url'=>$urlOuCaminho,':cat'=>$categoria,':dest'=>$destaque,':id'=>$id]);
            flashMensagem('success', 'Item de multimédia atualizado!');
        } else {
            $stmt = $pdo->prepare("INSERT INTO midia (titulo,descricao,tipo,url_ou_caminho,categoria,destaque) VALUES (:titulo,:descricao,:tipo,:url,:cat,:dest)");
            $stmt->execute([':titulo'=>$titulo,':descricao'=>$descricao,':tipo'=>$tipo,':url'=>$urlOuCaminho,':cat'=>$categoria,':dest'=>$destaque]);
            flashMensagem('success', 'Média adicionada com sucesso!');
        }
        header('Location: /atlas/admin/midia.php');
        exit;
    }
}

$categorias = ['energia-industria','saude-bem-estar','agronegocio','comercio-investimentos','geral'];
$tipo      = $_POST['tipo']      ?? ($item['tipo']      ?? 'foto');
$titulo    = $_POST['titulo']    ?? ($item['titulo']    ?? '');
$descricao = $_POST['descricao'] ?? ($item['descricao'] ?? '');
$categoria = $_POST['categoria'] ?? ($item['categoria'] ?? 'geral');
$destaque  = isset($_POST['destaque']) ? 1 : ($item['destaque'] ?? 0);
$videoUrl  = $_POST['video_url'] ?? ($tipo === 'video' ? ($item['url_ou_caminho'] ?? '') : '');
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Editar' : 'Adicionar' ?> Multimédia — Atlas Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/atlas/assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/_sidebar.php'; ?>
<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-title"><?= $id ? 'Editar' : 'Adicionar' ?> Multimédia</div>
        <div class="topbar-right">
            <a href="/atlas/admin/midia.php" class="btn btn-outline btn-sm">← Voltar</a>
        </div>
    </header>

    <main class="content">
        <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <div><strong>⚠️ Atenção:</strong>
                <ul style="margin-top:6px;padding-left:16px;">
                    <?php foreach ($erros as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div style="display:grid;grid-template-columns:1fr 300px;gap:24px;align-items:start;">

                <div class="card">
                    <div class="card-body" style="display:flex;flex-direction:column;gap:20px;">

                        <!-- Tipo -->
                        <div class="form-group" style="margin:0;">
                            <label>Tipo de Multimédia *</label>
                            <div style="display:flex;gap:12px;margin-top:8px;">
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:12px 20px;border:2px solid var(--gray-200);border-radius:8px;flex:1;transition:.2s;" id="lbl-foto">
                                    <input type="radio" name="tipo" value="foto" <?= $tipo === 'foto' ? 'checked' : '' ?> onchange="toggleTipo('foto')">
                                    <span>📷 Foto</span>
                                </label>
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:12px 20px;border:2px solid var(--gray-200);border-radius:8px;flex:1;transition:.2s;" id="lbl-video">
                                    <input type="radio" name="tipo" value="video" <?= $tipo === 'video' ? 'checked' : '' ?> onchange="toggleTipo('video')">
                                    <span>🎥 Vídeo</span>
                                </label>
                            </div>
                        </div>

                        <!-- Upload Foto -->
                        <div id="campo-foto" style="<?= $tipo === 'video' ? 'display:none' : '' ?>">
                            <div class="form-group" style="margin:0;">
                                <label>Ficheiro de Imagem</label>
                                <?php if ($id && $tipo === 'foto' && !empty($item['url_ou_caminho'])): ?>
                                <img src="<?= htmlspecialchars($item['url_ou_caminho']) ?>" alt=""
                                     style="width:100%;height:200px;object-fit:cover;border-radius:8px;margin-bottom:10px;">
                                <?php endif; ?>
                                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp">
                                <p class="form-hint">JPG, PNG, WebP — máx. 5MB</p>
                            </div>
                        </div>

                        <!-- URL Vídeo -->
                        <div id="campo-video" style="<?= $tipo === 'foto' ? 'display:none' : '' ?>">
                            <div class="form-group" style="margin:0;">
                                <label for="video_url">Link do Vídeo (YouTube / Vimeo)</label>
                                <input type="url" id="video_url" name="video_url" class="form-control"
                                       placeholder="https://www.youtube.com/embed/..."
                                       value="<?= htmlspecialchars($videoUrl) ?>">
                                <p class="form-hint">Use o link de incorporação (embed) do YouTube ou Vimeo.</p>
                            </div>
                        </div>

                        <div class="form-group" style="margin:0;">
                            <label for="titulo">Título *</label>
                            <input type="text" id="titulo" name="titulo" class="form-control"
                                   value="<?= htmlspecialchars($titulo) ?>" placeholder="Título descritivo..." required>
                        </div>

                        <div class="form-group" style="margin:0;">
                            <label for="descricao">Descrição (Opcional)</label>
                            <textarea id="descricao" name="descricao" class="form-control" rows="3"
                                      placeholder="Breve descrição deste conteúdo..."><?= htmlspecialchars($descricao) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Lateral -->
                <div style="display:flex;flex-direction:column;gap:20px;">
                    <div class="card">
                        <div class="card-header"><h2>⚙️ Definições</h2></div>
                        <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">
                            <div class="form-group" style="margin:0;">
                                <label for="categoria">Categoria</label>
                                <select name="categoria" id="categoria" class="form-control">
                                    <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat ?>" <?= $categoria === $cat ? 'selected' : '' ?>>
                                        <?= iconeCategoria($cat) ?> <?= labelCategoria($cat) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                                <input type="checkbox" name="destaque" <?= $destaque ? 'checked' : '' ?>
                                       style="width:18px;height:18px;accent-color:var(--navy);">
                                <span style="font-weight:600;color:var(--navy);">⭐ Destaque na Home</span>
                            </label>
                            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                                💾 <?= $id ? 'Guardar' : 'Adicionar' ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>
<script>
function toggleTipo(tipo) {
    document.getElementById('campo-foto').style.display  = tipo === 'foto'  ? '' : 'none';
    document.getElementById('campo-video').style.display = tipo === 'video' ? '' : 'none';
}
</script>
</body>
</html>
