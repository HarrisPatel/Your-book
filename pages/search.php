<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/bottom-bar.css">
    <link rel="stylesheet" href="../css/search.css">
</head>

<body>
    <div class="container">
        <input type="text" placeholder="Search Here" id="search">
        <div class="search-profile" id="results"></div>
        <div class="profile-posts">
            <div class="post-grid">
            </div>
        </div>
        <div class="no-post" style="margin-top: 100px;">
            <svg xmlns="http://www.w3.org/2000/svg" height="100" width="100" viewBox="0 0 512 512" style="width:100%">
                <path fill="#4a4a4a" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
            </svg>
            <p class="no-post" style="text-align : center; color : #a4a4a4">No Post Available</p>

        </div>
    </div>

</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var query = $(this).val().trim();
            if (query !== '') {
                $.ajax({
                    url: '../process/search-profile.php',
                    method: 'POST',
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $('#results').html(data);
                    }
                });
            } else {
                $('#results').html('');
            }
        });

        $('#search').on('keyup', function() {
            var query = $(this).val().trim();
            if (query !== '') {
                $.ajax({
                    url: '../process/search-post.php',
                    method: 'POST',
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $('.post-grid').html(data);
                        $('.no-post').hide()
                    }
                });
            } else {
                $('.post-grid').html('No Post Available');
            }
        });



    });
</script>
<?php
include 'bottom-bar.php'
?>