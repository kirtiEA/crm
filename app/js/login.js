$(document).ready(function() {

    $('#myModal').modal('show');
    Monitorly.init();
    // Add event handlers
    $('button.btn.submit').click(function (e) {
        var username = $('input#username:first').val(),
            password = $('input#password:first').val();
        e.preventDefault();
        Monitorly.login(username, password, function () {
            window.location.href = 'index.php';
        });
    });
    
});

