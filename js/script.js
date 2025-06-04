//confirma se o usuário deseja sair apos clicar no link sair

window.onload = function() {
    document.querySelector('.logout').addEventListener('click', function(e) {
        if (!confirm('Tem certeza que deseja sair?')) {
            e.preventDefault();
        }
    });
};