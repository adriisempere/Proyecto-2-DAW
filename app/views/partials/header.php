<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="?action=home">GreenPoints</a>

        <div class="d-flex">
            <?php if (!isset($_SESSION['user'])): ?>
                <a href="?action=login" class="btn btn-outline-light me-2">Login</a>
                <a href="?action=register" class="btn btn-success">Registro</a>
            <?php else: ?>
                <a href="?action=logout" class="btn btn-danger">Salir</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
