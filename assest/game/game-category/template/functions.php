<?php

function getRating($resultType = 'json') {
    global $con, $id, $ex;

    $timeStart = (int)($_COOKIE["startGameTime"] ?? $_SESSION["startGameTime"] ?? time());

    $stmt = mysqli_prepare($con, "SELECT * FROM `table_example` WHERE user_id = ? AND time >= ? ORDER BY id");
    mysqli_stmt_bind_param($stmt, "ii", $id, $timeStart);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($con, "SELECT * FROM `table_example` WHERE `username` = ?");
    mysqli_stmt_bind_param($stmt, "s", $ex["username"]);
    mysqli_stmt_execute($stmt);
    $general_rating_game = mysqli_stmt_get_result($stmt);
    $general_rating_game = mysqli_fetch_assoc($general_rating_game);
    mysqli_stmt_close($stmt);

    if($result->num_rows > 0 && $general_rating_game) {
        $items = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }

        $rating = array_column($items, 'rating');
        $rating = array_merge([$general_rating_game['start_rating']], $rating);
    } elseif ($general_rating_game) {
        $rating = [$general_rating_game['start_rating']];
    } else {
        $rating = [0];
    }

    if($resultType == 'json') {
        return json_encode($rating);
    }
    return $rating;
}
