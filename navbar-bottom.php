<?php
$current_page = basename($_SERVER['PHP_SELF']);
$project_id = $_GET['id'] ?? '';
?>

<style>
/* Light shadow effect for active icon */
.navbar-icon {
    transition: all 0.3s ease;
    padding: 0.5rem;
    border-radius: 0.5rem;
}

.navbar-icon.active {
    background-color: rgba(255, 255, 255, 0.15);
    box-shadow: 0 2px 8px rgba(255, 255, 255, 0.3),
                0 0 15px rgba(255, 255, 255, 0.2);
}
</style>

<!-- <div class="mt-5"></div> -->
<div class="row">
  <nav class="navbar fixed-bottom bg-primary text-white px-3" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
    <div class="container-fluid d-flex justify-content-around text-white">
      <a href="info_projeto_api_dados.php<?php echo $project_id ? '?id=' . htmlspecialchars($project_id) : ''; ?>"
         class="bi bi-info-circle-fill text-white navbar-icon <?php echo ($current_page === 'info_projeto_api_dados.php') ? 'active' : ''; ?>"
         style="font-size: 2rem;"></a>
      <a href="info_projeto_api_fluxo.php<?php echo $project_id ? '?id=' . htmlspecialchars($project_id) : ''; ?>"
         class="bi bi-clipboard2-data-fill text-white navbar-icon <?php echo ($current_page === 'info_projeto_api_fluxo.php') ? 'active' : ''; ?>"
         style="font-size: 2rem;"></a>
      <a href="info_projeto_api_chat.php<?php echo $project_id ? '?id=' . htmlspecialchars($project_id) : ''; ?>"
         class="bi bi-chat-left-text-fill text-white navbar-icon <?php echo ($current_page === 'info_projeto_api_chat.php') ? 'active' : ''; ?>"
         style="font-size: 2rem;"></a>
    </div>
  </nav>
</div>