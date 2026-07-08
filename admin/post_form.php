<?php
/**
 * Atlas Centro Sul — Formulário de Post (Criar/Editar)
 */
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/helpers.php';
protegerRota();

$pdo   = getDB();
$flash = obterFlash();
$id    = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
$post  = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $post = $stmt->fetch();
    if (!$post) { header('Location: /atlas/admin/posts.php'); exit; }
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo    = sanitizar($_POST['titulo'] ?? '');
    $resumo    = sanitizar($_POST['resumo'] ?? '');
    $corpo     = $_POST['corpo'] ?? '';       // HTML do editor rico — não sanitizar aqui
    $categoria = sanitizar($_POST['categoria'] ?? 'geral');
    $publicado = isset($_POST['publicado']) ? 1 : 0;
    $slug      = criarSlug($titulo);

    if (empty($titulo)) $erros[] = 'O título é obrigatório.';
    if (empty($corpo))  $erros[] = 'O corpo do artigo é obrigatório.';

    // Upload de imagem
    $imagemDestaque = $post['imagem_destaque'] ?? null;
    if (!empty($_FILES['imagem']['name'])) {
        $uploadResult = uploadImagem($_FILES['imagem'], 'posts');
        if ($uploadResult) {
            $imagemDestaque = $uploadResult;
        } else {
            $erros[] = 'Imagem inválida. Use JPG, PNG ou WebP (máx. 5MB).';
        }
    }

    if (empty($erros)) {
        if ($id) {
            // Atualizar
            $stmt = $pdo->prepare("UPDATE posts SET titulo=:titulo, slug=:slug, resumo=:resumo, corpo=:corpo, categoria=:categoria, imagem_destaque=:img, publicado=:pub WHERE id=:id");
            $stmt->execute([':titulo'=>$titulo,':slug'=>$slug,':resumo'=>$resumo,':corpo'=>$corpo,':categoria'=>$categoria,':img'=>$imagemDestaque,':pub'=>$publicado,':id'=>$id]);
            flashMensagem('success', 'Post atualizado com sucesso!');
        } else {
            // Criar
            // Garantir slug único
            $slugOriginal = $slug;
            $counter = 1;
            while ($pdo->prepare("SELECT id FROM posts WHERE slug = ?")->execute([$slug]) && $pdo->query("SELECT COUNT(*) FROM posts WHERE slug = '$slug'")->fetchColumn() > 0) {
                $slug = $slugOriginal . '-' . $counter++;
            }
            $stmt = $pdo->prepare("INSERT INTO posts (titulo,slug,resumo,corpo,categoria,imagem_destaque,publicado) VALUES (:titulo,:slug,:resumo,:corpo,:categoria,:img,:pub)");
            $stmt->execute([':titulo'=>$titulo,':slug'=>$slug,':resumo'=>$resumo,':corpo'=>$corpo,':categoria'=>$categoria,':img'=>$imagemDestaque,':pub'=>$publicado]);
            flashMensagem('success', 'Post criado com sucesso!');
        }
        header('Location: /atlas/admin/posts.php');
        exit;
    }
}

$categorias = ['energia-industria','saude-bem-estar','agronegocio','comercio-investimentos','geral'];
$titulo    = $_POST['titulo']    ?? ($post['titulo']    ?? '');
$resumo    = $_POST['resumo']    ?? ($post['resumo']    ?? '');
$corpo     = $_POST['corpo']     ?? ($post['corpo']     ?? '');
$categoria = $_POST['categoria'] ?? ($post['categoria'] ?? 'geral');
$publicado = isset($_POST['publicado']) ? 1 : ($post['publicado'] ?? 1);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Editar' : 'Novo' ?> Post — Atlas Centro Sul Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/atlas/assets/css/admin.css">
</head>
<body>
<?php include __DIR__ . '/_sidebar.php'; ?>
<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-title"><?= $id ? 'Editar Post' : 'Novo Post' ?></div>
        <div class="topbar-right">
            <a href="/atlas/admin/posts.php" class="btn btn-outline btn-sm">← Voltar</a>
        </div>
    </header>

    <main class="content">
        <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <div>
                <strong>⚠️ Atenção:</strong>
                <ul style="margin-top:6px;padding-left:16px;">
                    <?php foreach ($erros as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">

                <!-- Coluna principal -->
                <div style="display:flex;flex-direction:column;gap:20px;">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="titulo">Título do Artigo *</label>
                                <input type="text" id="titulo" name="titulo" class="form-control"
                                       placeholder="Escreva um título apelativo..."
                                       value="<?= htmlspecialchars($titulo) ?>" required>
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label for="resumo">Resumo (Opcional)</label>
                                <textarea id="resumo" name="resumo" class="form-control" rows="2"
                                          placeholder="Breve descrição para listagens e SEO..."><?= htmlspecialchars($resumo) ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h2>✍️ Conteúdo</h2></div>
                        <div class="card-body">
                            <div class="form-group" style="margin-bottom:0">
                                <textarea id="corpo" name="corpo" class="form-control" rows="16"><?= htmlspecialchars($corpo) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coluna lateral -->
                <div style="display:flex;flex-direction:column;gap:20px;">

                    <!-- Publicar -->
                    <div class="card">
                        <div class="card-header"><h2>📤 Publicação</h2></div>
                        <div class="card-body">
                            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-bottom:20px;">
                                <input type="checkbox" name="publicado" id="publicado" <?= $publicado ? 'checked' : '' ?>
                                       style="width:18px;height:18px;accent-color:var(--navy);">
                                <span style="font-weight:600;color:var(--navy);">Publicar no site</span>
                            </label>
                            <div style="display:flex;gap:10px;">
                                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;">
                                    💾 <?= $id ? 'Guardar' : 'Criar Post' ?>
                                </button>
                            </div>
                            <?php if ($id): ?>
                            <a href="/atlas/post.php?slug=<?= htmlspecialchars($post['slug'] ?? '') ?>" target="_blank"
                               style="display:block;text-align:center;margin-top:12px;font-size:13px;color:var(--navy);">
                                🌐 Ver no site →
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Categoria -->
                    <div class="card">
                        <div class="card-header"><h2>🏷️ Categoria</h2></div>
                        <div class="card-body">
                            <div class="form-group" style="margin-bottom:0">
                                <select name="categoria" id="categoria" class="form-control">
                                    <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat ?>" <?= $categoria === $cat ? 'selected' : '' ?>>
                                        <?= iconeCategoria($cat) ?> <?= labelCategoria($cat) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Imagem de Destaque -->
                    <div class="card">
                        <div class="card-header"><h2>🖼️ Imagem de Destaque</h2></div>
                        <div class="card-body">
                            <?php if (!empty($post['imagem_destaque'])): ?>
                            <img src="<?= htmlspecialchars($post['imagem_destaque']) ?>" alt="Destaque"
                                 style="width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:12px;">
                            <?php endif; ?>
                            <input type="file" name="imagem" id="imagem" class="form-control"
                                   accept="image/jpeg,image/png,image/webp">
                            <p class="form-hint">JPG, PNG, WebP — máx. 5MB</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>

<!-- TinyMCE Editor Rico -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#corpo',
    language: 'pt_PT',
    height: 420,
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    content_style: 'body { font-family: Inter, sans-serif; font-size: 15px; line-height: 1.7; color: #0D1B35; }',
    branding: false,
});
</script>
</body>
</html>
