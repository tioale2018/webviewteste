<?php
$current_page = basename($_SERVER['PHP_SELF']);
$project_id = $_GET['id'] ?? '';
?>

<!-- <div class="mt-5"></div> -->
<div class="row">
  <nav class="navbar fixed-bottom  bg-primary text-white px-3">
    <div class="container-fluid d-flex justify-content-around text-white">
      <a href="info_projeto_api_dados.php<?php echo $project_id ? '?id=' . htmlspecialchars($project_id) : ''; ?>"
         style="font-size: 2rem; margin: 0 0.5rem;"
         class="bi bi-info-circle-fill <?php echo ($current_page === 'info_projeto_api_dados.php') ? 'text-warning' : 'text-white'; ?>"></a>
      <a href="info_projeto_api_fluxo.php<?php echo $project_id ? '?id=' . htmlspecialchars($project_id) : ''; ?>"
         style="font-size: 2rem; margin: 0 0.5rem;"
         class="bi bi-clipboard2-data-fill <?php echo ($current_page === 'info_projeto_api_fluxo.php') ? 'text-warning' : 'text-white'; ?>"></a>
      <a href="info_projeto_api_chat.php<?php echo $project_id ? '?id=' . htmlspecialchars($project_id) : ''; ?>"
         style="font-size: 2rem; margin: 0 0.5rem;"
         class="bi bi-chat-left-text-fill <?php echo ($current_page === 'info_projeto_api_chat.php') ? 'text-warning' : 'text-white'; ?>"></a>
    </div>
  </nav>
</div>