<?php
$pageTitle = 'Perfil | GreenPoints';
include __DIR__ . '/partials/header.php';
?>

<div class="container py-5">
  <h2 class="mb-4">Mi Perfil</h2>
  <?php if (isset($_SESSION['usuario_id'])): ?>
    <div class="card p-4 shadow-sm">
      <p><strong>Nombre:</strong> <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['usuario_email'] ?? '') ?></p>
      <p><strong>Puntos:</strong> <?= htmlspecialchars($_SESSION['usuario_puntos'] ?? 0) ?></p>
    </div>
  <?php else: ?>
    <p class="text-muted">Debes iniciar sesión para ver tu perfil.</p>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
