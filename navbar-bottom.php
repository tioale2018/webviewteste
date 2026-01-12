
<!-- <div class="mt-5"></div> -->
<div class="row">
  <nav class="navbar fixed-bottom bg-primary text-white px-3">
    <div class="container-fluid d-flex justify-content-around text-white">
      <a id="info_projeto_dados" href="#" data-page="info_projeto_dados_api.php" style="font-size: 2rem; margin: 0 0.5rem;" class="bi bi-info-circle-fill text-white"></a>
      <a id="info_projeto_fluxo" href="#" data-page="info_projeto_fluxo_api.php" style="font-size: 2rem; margin: 0 0.5rem;" class="bi bi-clipboard2-data-fill text-white"></a>
      <a id="info_projeto_chat" href="#" data-page="info_projeto_chat_api.php" style="font-size: 2rem; margin: 0 0.5rem;" class="bi bi-chat-left-text-fill text-white"></a>
    </div>
  </nav>
</div>

<script>
// Automatically propagate project ID to navigation links
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const projectId = urlParams.get('id');

    if (projectId) {
        // Update all navigation links with the project ID
        document.querySelectorAll('[data-page]').forEach(function(link) {
            const page = link.getAttribute('data-page');
            link.href = page + '?id=' + encodeURIComponent(projectId);
        });
    }
});
</script>