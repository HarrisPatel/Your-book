<?php
include '../config.php';
$user_id = $_POST['user_id'];

$req_sql = "SELECT follower_id FROM followers WHERE user_id = $user_id AND id_status = 'requested'";
$req_result = mysqli_query($conn, $req_sql) or die("Query Failed: " . mysqli_error($conn));

$request_ids = [];
while ($reqid_row = mysqli_fetch_assoc($req_result)) {
    $request_ids[] = $reqid_row['follower_id'];
}


if (!empty($request_ids)) {
    $request_ids_str = implode(',', $request_ids);
    if (!empty($request_ids_str)) {
        $reqinfo_sql = "SELECT user_table.*
                        FROM user_table
                        INNER JOIN followers ON user_table.id = followers.follower_id
                        WHERE followers.follower_id IN ($request_ids_str) ORDER BY user_table.id DESC;";

        $reqinfo_result = mysqli_query($conn, $reqinfo_sql) or die("Query Failed: " . mysqli_error($conn));
        if (mysqli_num_rows($reqinfo_result) > 0) {
            echo '<div class="option-content">';
            echo '<h4 style="margin-bottom: 10px; text-align: center">Request</h4>';
            while ($req_row = mysqli_fetch_assoc($reqinfo_result)) {
                echo '<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom : 10px">';
                echo '<div style="display: flex; gap: 10px; align-items: center;">';
                echo '<div class="req-profile-pic">';
                echo '<img src="../process/upload/' . $req_row['profile_image'] . '" width="50px" height="50px" alt="Profile Picture">';
                echo '</div>';
                echo '<div class="user-info">';
                echo '<p><b>@' . $req_row['username'] . '</b></p>';
                echo '<p>' . $req_row['first_name'] . ' ' . $req_row['last_name'] . '</p>';
                echo '</div></div>';
                echo '<div class="action-buttons" style="display:flex;gap:7px">';
                echo '<button class="req-res-btn accept-btn req-btn" data-user-id="' . $user_id . '" data-follower-id="' . $req_row['id'] . '" data-btn-type="accept">Accept</button>';
                echo '<button class="req-res-btn decline-btn req-btn" data-user-id="' . $user_id . '" data-follower-id="' . $req_row['id'] . '" data-btn-type="decline">Decline</button>';
                echo '</div></div>';
            }
            echo '</div>';
        } else {
            echo '<p>No Requests</p>';
        }
    }
} else {
    echo 'No Requests';
}
