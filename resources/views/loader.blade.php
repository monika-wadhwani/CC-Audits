<div id="loader-container">
    <img id="loader-image" src="" alt="">
    <p id="loader-text"></p>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $.get('/loader', function(data) {
            $('#loader-image').attr('src', data.image_url);
            $('#loader-text').text(data.text);
        });
    });
</script>
