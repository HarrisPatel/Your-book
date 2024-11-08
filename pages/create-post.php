<style>
    .create {
        background-color: #2d2d2d;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.7);
        border-radius: 10px;
        transition: 0.5s;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        margin: 20px 10px;
    }

    .create h2 {
        margin: 0;
        margin-bottom: 10px;
    }

    .top {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .top img {
        border-radius: 100px;
        width: 50px;
        height: 50px;
        object-fit: cover;
    }

    .input-fields {
        width: 100%;
    }

    .create input {
        width: 100%;
        padding: 12px;
        background-color: #3d3d3d;
        color: #fff;
        border: 1px solid #4a4a4a;
        outline: none;
        border-radius: 40px;
        font-size: 17px;
    }

    .create textarea {
        width: 100%;
        padding: 12px;
        background-color: #3d3d3d;
        color: #fff;
        border: 1px solid #4a4a4a;
        outline: none;
        border-radius: 20px;
        font-size: 17px;
        margin: 10px 0;
    }

    .create input::placeholder {
        color: lightgray;
    }

    .create textarea::placeholder {
        color: lightgray;
    }

    .bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .create button {
        width: 37%;
        padding: 12px;
        background-color: #6932a8;
        color: white;
        font-family: 'Poppins', sans-serif;
        border: none;
        border-radius: 40px;
        cursor: pointer;
        font-size: 16px;
    }

    .bottom input {
        display: none;
    }

    .div-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .div-container img {
        width: 100%;
        height: 300px;
        object-fit: contain;
        margin-bottom: 20px;
    }


    @media (max-width: 768px) {
        .create input {
            font-size: 13px;
        }

        .create textarea {
            font-size: 13px;
        }
    }

    @media (max-width: 550px) {


        .create textarea {

            margin: 4px 0;
        }

        .create button {
            width: 48%;
            padding: 12px;
            background-color: #6932a8;
            color: white;
            font-family: 'Poppins', sans-serif;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-size: 12px;
            margin-top: 4px;
        }
    }
</style>

<div class="create">
    <h2>Create Post</h2>
    <form action="../process/upload-post.php" method="POST" enctype="multipart/form-data">
        <div class="div-container" style="display: none">
            <img class="pre-img" src="" alt="" style="display:none;">
            <video class="pre-video" src="" controls style="display:none; width:100%; height:300px;"></video>
        </div>

        <div class="top">
            <div class="image">
                <img src="../process/upload/<?php echo $_SESSION['profile_image'] ?>" alt="">
            </div>
            <div class="input-fields">
                <input type="text" id="title" name="title"
                    placeholder="What's On Your Mind, <?php echo $_SESSION['first_name'] ?> <?php echo $_SESSION['last_name'] ?>"
                    maxlength="150" />
            </div>
        </div>
        <textarea type="text" id="description" name="description" placeholder="Add Description Here" maxlength="400"
        style='resize : none'></textarea>
        <div class="bottom">
            <button type="button" class="select-btn"><b>Select Image/Video</b></button>
            <input type="file" id="select1-image" name="create-image" accept="image/*,video/*">
            <button type="submit" class="w-100 submit"><b>Upload Post</b></button>
        </div>
        <p class="error-message" style="color:red; display:none; margin:0">Please select an image or video to upload!</p>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

        $('.submit').on('click', function(event) {
            $('.div-container').hide();

            if (!$('#select1-image').val()) {
                event.preventDefault();
                $('.error-message').show();
            } else {
                $('.error-message').hide();
            }
        });


        $('.select-btn').on('click', function() {
            $('#select1-image').click();
        });

        $('#select1-image').on('change', function() {
            const file = this.files[0];
            if (file) {
                const fileType = file.type
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (fileType.startsWith('image/')) {
                        $('.div-container').show();
                        $('.pre-img').attr('src', e.target.result).show();
                        $('.pre-video').hide();
                    } else if (fileType.startsWith('video/')) {
                        $('.div-container').show();
                        $('.pre-video').attr('src', e.target.result).show();
                        $('.pre-img').hide();
                    }
                };
                reader.readAsDataURL(file);
            }
        });

    });
</script>