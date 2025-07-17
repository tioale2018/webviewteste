<!-- Top Navbar -->
 <?php
    include_once "funcoes.php";
    include_once "login.php";

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            header("Location: index.php");
            exit;
        }
 ?>
  <nav class="navbar navbar-light fixed-top bg-primary text-white px-3">
    <div class="d-flex align-items-center w-100 justify-content-between">
      <button class="btn btn-primary border-0 p-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
        <i class="bi bi-list fs-3"></i>
      </button>
      <span class="fw-semibold small ms-2"><?php echo strtoupper(($_SESSION['nome'])); ?></span>
      <img src="src/logo.svg" alt="Logo" class="img-fluid" style="height: 40px;">
    </div>
  </nav>
  <div class="mb-5"></div>

  <!-- Offcanvas Menu -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">Menu</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <a href="lista_editais.php" class="nav-link active text-primary">
            <i class="bi bi-house-door me-2"></i> Inscrições
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="lista_projetos.php" class="nav-link text-dark">
            <i class="bi bi-journal-text me-2"></i> Meus Projetos
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="lista_editais_encerrados.php" class="nav-link text-dark">
            <i class="bi bi-archive-fill me-2"></i> Editais Encerrados
          </a>
        </li>
        <li class="nav-item  mb-2">
          <a href="meu_cadastro.php" class="nav-link text-dark">
            <i class="bi bi-person me-2"></i> Meu Cadastro
          </a>
        </li>
        <li class="nav-item  mb-2">
          <a href="sair.php" class="nav-link text-dark">
            <i class="bi bi-box-arrow-right me-2"></i> Sair do Sistema
          </a>
        </li>
      </ul>
    </div>
  </div>