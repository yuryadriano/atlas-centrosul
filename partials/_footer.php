<?php
/**
 * Atlas Centro Sul — Footer Global
 */
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/auth.php';
?>
<!-- Contact Strip -->
<section class="contact-strip">
    <div class="container">
        <div class="contact-strip-inner">
            <div>
                <h2>Pronto para trabalhar connosco?</h2>
                <p>Entre em contacto e descubra como a Atlas pode criar valor para si.</p>
            </div>
            <a href="/atlas/contacto.php" class="btn btn-navy">Falar Connosco →</a>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Marca -->
            <div class="footer-brand">
                <img src="/atlas/assets/img/logo.png" alt="Atlas Centro Sul">
                <p><?= htmlspecialchars(truncar(config('sobre_resumo', 'A Atlas Centro Sul é uma empresa angolana multissectorial dedicada a criar valor em múltiplos sectores da economia nacional.'), 140)) ?></p>
                <div class="footer-socials">
                    <?php if (config('facebook')): ?>
                    <a href="<?= htmlspecialchars(config('facebook')) ?>" target="_blank" class="social-btn" title="Facebook">f</a>
                    <?php endif; ?>
                    <?php if (config('instagram')): ?>
                    <a href="<?= htmlspecialchars(config('instagram')) ?>" target="_blank" class="social-btn" title="Instagram">in</a>
                    <?php endif; ?>
                    <?php if (config('linkedin')): ?>
                    <a href="<?= htmlspecialchars(config('linkedin')) ?>" target="_blank" class="social-btn" title="LinkedIn">li</a>
                    <?php endif; ?>
                    <?php if (config('youtube')): ?>
                    <a href="<?= htmlspecialchars(config('youtube')) ?>" target="_blank" class="social-btn" title="YouTube">▶</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Serviços -->
            <div>
                <p class="footer-heading">Serviços</p>
                <ul class="footer-links">
                    <li><a href="/atlas/servicos/energia-industria.php">⚙️ Energia & Indústria</a></li>
                    <li><a href="/atlas/servicos/saude-bem-estar.php">🏥 Saúde & Bem-Estar</a></li>
                    <li><a href="/atlas/servicos/agronegocio.php">🌾 Agronegócio</a></li>
                    <li><a href="/atlas/servicos/comercio-investimentos.php">🏢 Comércio & Investimentos</a></li>
                </ul>
            </div>

            <!-- Navegação -->
            <div>
                <p class="footer-heading">Empresa</p>
                <ul class="footer-links">
                    <li><a href="/atlas/sobre.php">Sobre Nós</a></li>
                    <li><a href="/atlas/blog.php">Blog & Notícias</a></li>
                    <li><a href="/atlas/galeria.php">Galeria</a></li>
                    <li><a href="/atlas/contacto.php">Contacto</a></li>
                </ul>
            </div>

            <!-- Contacto -->
            <div>
                <p class="footer-heading">Contacto</p>
                <div class="footer-contact-item">
                    <span class="icon">📍</span>
                    <span><?= htmlspecialchars(config('morada', 'Bairro São Jão, Rua Principal, Junto A Escola 103')) ?>, <?= htmlspecialchars(config('municipio', 'Município de Huambo')) ?>, <?= htmlspecialchars(config('provincia', 'Huambo')) ?></span>
                </div>
                <?php if (config('telefone_1')): ?>
                <div class="footer-contact-item">
                    <span class="icon">📞</span>
                    <a href="tel:<?= preg_replace('/\s+/', '', config('telefone_1')) ?>" style="color:rgba(255,255,255,.6);">
                        <?= htmlspecialchars(config('telefone_1')) ?>
                    </a>
                </div>
                <?php endif; ?>
                <?php if (config('email_geral')): ?>
                <div class="footer-contact-item">
                    <span class="icon">✉️</span>
                    <a href="mailto:<?= htmlspecialchars(config('email_geral')) ?>" style="color:rgba(255,255,255,.6);">
                        <?= htmlspecialchars(config('email_geral')) ?>
                    </a>
                </div>
                <?php endif; ?>
                <?php if (config('whatsapp')): ?>
                <div class="footer-contact-item">
                    <span class="icon">💬</span>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', config('whatsapp')) ?>" target="_blank" style="color:rgba(255,255,255,.6);">
                        WhatsApp
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© <?= date('Y') ?> <?= htmlspecialchars(config('empresa_nome', 'Atlas Centro Sul — Comércio e Serviços, Lda')) ?>. Todos os direitos reservados.
                · <a href="/atlas/admin/login.php" style="color: inherit; text-decoration: none; opacity: 0.4; transition: opacity 0.3s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.4">Acesso Restrito</a>
            </p>
            <p>Huambo, Angola</p>
        </div>
    </div>
</footer>

<script src="/atlas/assets/js/main.js?v=<?= time() ?>"></script>
</body>
</html>
