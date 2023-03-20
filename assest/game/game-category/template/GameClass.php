<?php

class Game
{
    private $mysql;
    private $user;
    private $generalRating;

    public function __construct($mysql, $user)
    {
        $this->mysql = $mysql;
        $this->user = $user;
        $this->generalRating = $this->getGeneralRating();
    }

    public function start(): array
    {
        $row = mysqli_query($this->mysql, "SELECT * FROM `a_active_test` where `user_id` = '{$this->user['id']}' LIMIT 1");

        if ($row->num_rows > 0) {
            mysqli_query($this->mysql, "DELETE FROM `a_active_test` where `user_id` = '{$this->user['id']}'");
        }

        $gameSuccess = mysqli_query($this->mysql, "SELECT `game_id` FROM `a_game_success` where `user_id` = '{$this->user['id']}'");

        $minRate = $this->generalRating['rating'] - 500;

        if ($minRate < 0) {
            $minRate = 0;
        }
        $maxRate = $this->generalRating['rating'] + 250;

        if ($gameSuccess->num_rows > 0) {
            $items = [];
            while ($row = mysqli_fetch_assoc($gameSuccess)) {
                $items[] = $row;
            }
            $rowsSuccess = implode(',', array_column($items, 'game_id'));

            $game = mysqli_query($this->mysql, "select * from `a_game_text` where `var_idtest_3` >= {$minRate} and `var_idtest_3` <= {$maxRate} and `id` not in ({$rowsSuccess}) order by RAND() limit 1");

        } else $game = mysqli_query($this->mysql, "select * from `a_game_text`  where `var_idtest_3` >= {$minRate} and `var_idtest_3` <= {$maxRate}  order by RAND() limit 1");

        $game = mysqli_fetch_assoc($game);

        if (isset($game['id'])) {
            mysqli_query($this->mysql, "INSERT INTO `a_active_test` (`user_id`, `game_id`, `time`) VALUES ('{$this->user['id']}', '{$game['id']}', UNIX_TIMESTAMP())");
            mysqli_query($this->mysql, "UPDATE `a_game_text` SET `var_idtest_10` = var_idtest_10+1 WHERE `id` = '{$game['id']}'");

            $votes = [
                1 => $game['var_idtest_2'],
                2 => $game['var_idtest_2_1'],
                3 => $game['var_idtest_2_2'],
                4 => $game['var_idtest_2_3']
            ];
            return [
                'status' => 1,
                'messages' => 'Ну давайте.',
                'votes' => [
                    'test' => $votes,
                    'id' => $game['id'],
                    'text' => $game['var_idtest_1'],
                    'rating' => $game['var_idtest_3'],
                    'rating_add' => $game['var_idtest_5'],
                    'rating_minus' => $game['var_idtest_6'],
                    'time' => $game['var_idtest_7'],
                    'speed' => $game['var_idtest_8'],
                    'count_done' => $game['var_idtest_9'],
                    'count_all' => $game['var_idtest_10'],
                ],
                "gameStatus" => $this->getGameStatus($game["id"])
            ];
        }
        return ['status' => 0, 'messages' => 'Вы прошли все тесты'];
    }


    public function info(): array
    {

        if (isset($_REQUEST['id']) and isset($_REQUEST['timer'])) {
            $answerId = $_REQUEST['id'];
            $timer = $_REQUEST['timer'];
            $tester = (int)$_REQUEST['tester'];
            $restart = (int)$_REQUEST['restart'];

            $row = mysqli_query($this->mysql, "SELECT * FROM `a_active_test` where `user_id` = '{$this->user['id']}' LIMIT 1");
            $game = mysqli_fetch_assoc($row);

            if (isset($game['id'])) {
                $votes = mysqli_query($this->mysql, "SELECT * FROM `a_game_text` where `id` = '{$game['game_id']}' LIMIT 1");
                $votes = mysqli_fetch_assoc($votes);


                if (isset($votes['id']) and isset($votes['var_idtest_2_4'])) {
                    $gameStatusRow = mysqli_query($this->mysql, "SELECT * FROM `a_game_success` where `user_id` = '{$this->user['id']}' and `game_id` = '{$game['game_id']}' LIMIT 1");

                    if ($answerId == $votes['var_idtest_2_4']) {
                        if ($gameStatusRow->num_rows == 0 and $tester == 1) {
                            $timePercent = $timer / $votes['var_idtest_7'] * 100;
                            $bonusRating = 0;

                            if ($timePercent <= 25) $bonusRating = 3;
                            else if ($timePercent <= 50) $bonusRating = 2;
                            else if ($timePercent <= 75) $bonusRating = 1;

                            $sumRating = $this->generalRating['rating'];

                            if ($restart <= 0) {
                                $ratingPlus = $votes['var_idtest_5'] + $bonusRating;
                                $sumRating += $ratingPlus;
                            }

                            $series_max = $this->generalRating["series_max"];
                            if (++$this->generalRating["current_series"] > $series_max) {
                                $series_max = $this->generalRating["current_series"];
                            }

                            $ratingMax = $this->generalRating["rating_max"];
                            $ratingMaxTime = $this->generalRating["rating_max_time"];
                            if ($sumRating > $ratingMax) {
                                $ratingMax = $sumRating;
                                $ratingMaxTime = time();
                            }

                            mysqli_query($this->mysql, "UPDATE `general_rating_game` SET `current_series` = current_series + 1, `series_max` = $series_max, `rating_max` = $ratingMax, `rating_max_time` = $ratingMaxTime, `votes_count` = votes_count+1, `votes_done` = votes_done+1, `rating` = $sumRating WHERE `username` = '{$this->user['username']}'");
                            mysqli_query($this->mysql, "UPDATE `a_game_text` SET `var_idtest_9` = var_idtest_9+1 WHERE `id` = '{$votes['id']}'");
                            mysqli_query($this->mysql, "INSERT INTO `a_game_success` (`user_id`, `game_id`, `created_at`, `time`, `status`, `reward`) VALUES ('{$this->user['id']}', '{$votes['id']}', UNIX_TIMESTAMP(), '{$timer}', 1, $ratingPlus)");
                            mysqli_query($this->mysql, "INSERT INTO `a_game_rating` (`user_id`, `game_id`, `time`, `rating`) VALUES ('{$this->user['id']}', '{$votes['id']}', UNIX_TIMESTAMP(), '{$sumRating}')");
                        } elseif ($tester == 2 and $gameStatusRow->num_rows == 0) {
                            $sumRating = $this->generalRating['rating'];

                            mysqli_query($this->mysql, "INSERT INTO `a_game_success` (`user_id`, `game_id`, `created_at`, `time`, `status`, `reward`) VALUES ('{$this->user['id']}', '{$votes['id']}', UNIX_TIMESTAMP(), '{$timer}', 0, 0)");
                            mysqli_query($this->mysql, "INSERT INTO `a_game_rating` (`user_id`, `game_id`, `time`, `rating`) VALUES ('{$this->user['id']}', '{$votes['id']}', UNIX_TIMESTAMP(), '{$sumRating}')");
                            mysqli_query($this->mysql, "UPDATE `general_rating_game` SET `current_series` = 0, `votes_none` = votes_none+1, `rating` = '{$sumRating}' WHERE `username` = '{$this->user['username']}'");

                        }

                        return [
                            'status' => 1,
                            'update_stats' => getRating('array'),
                            'rate' => $this->getGeneralRating()["rating"],
                        ];
                    }

                    if ($gameStatusRow->num_rows == 0) {
                        $minusRating = $restart <= 0 ? -$votes['var_idtest_6'] : 0;
                        $sumRating = $this->generalRating['rating'] + $minusRating;

                        if ($sumRating < 300) $sumRating = 300;

                        mysqli_query($this->mysql, "INSERT INTO `a_game_success` (`user_id`, `game_id`, `created_at`, `time`, `status`, `reward`) VALUES ('{$this->user['id']}', '{$votes['id']}', UNIX_TIMESTAMP(), '{$timer}', 0, {$minusRating})");
                        mysqli_query($this->mysql, "INSERT INTO `a_game_rating` (`user_id`, `game_id`, `time`, `rating`) VALUES ('{$this->user['id']}', '{$votes['id']}', UNIX_TIMESTAMP(), '{$sumRating}')");
                        mysqli_query($this->mysql, "UPDATE `general_rating_game` SET `current_series` = 0, `votes_none` = votes_none+1, `rating` = '{$sumRating}' WHERE `username` = '{$this->user['username']}'");
                    }

                    return [
                        'status' => 3,
                        'update_stats' => getRating('array'),
                        'rate' => $this->getGeneralRating()["rating"],
                    ];
                }
                return ['status' => 3, 'messages' => 'Нет активной игры!'];
            }
            return ['status' => 0, 'messages' => 'Нет активной игры!'];
        }
        return ['status' => 0, 'messages' => 'Нет активной игры!'];
    }

    public function help(): array
    {
        $row = mysqli_query($this->mysql, "SELECT * FROM `a_active_test` where `user_id` = '{$this->user['id']}' LIMIT 1");
        $game = mysqli_fetch_assoc($row);
        $timer = $_REQUEST['timer'];

        if (isset($game['id'])) {
            $votes = mysqli_query($this->mysql, "SELECT * FROM `a_game_text` where `id` = '{$game['game_id']}' LIMIT 1");
            $votes = mysqli_fetch_assoc($votes);

            if (isset($votes['id'])) {
                if (isset($_REQUEST['req'])) {
                    $sumRating = $this->generalRating['rating'];

                    if ($_REQUEST['req'] == 2 && $_REQUEST['restart'] <= 0) {
                        $gameStatus = mysqli_query($this->mysql, "SELECT * FROM `a_game_success` where `user_id` = '{$this->user['id']}' and `game_id` = '{$game['game_id']}' LIMIT 1");

                        if ($gameStatus->num_rows == 0) {
                            $sumRating -= $votes['var_idtest_6'];

                            if ($sumRating < 300) $sumRating = 300;

                            mysqli_query($this->mysql, "INSERT INTO `a_game_success` (`user_id`, `game_id`, `created_at`, `time`, `status`, `reward`) VALUES ('{$this->user['id']}', '{$votes['id']}', UNIX_TIMESTAMP(), '{$timer}', 0, -{$votes['var_idtest_6']})");
                            mysqli_query($this->mysql, "INSERT INTO `a_game_rating` (`user_id`, `game_id`, `time`, `rating`) VALUES ('{$this->user['id']}', '{$votes['id']}', UNIX_TIMESTAMP(), '{$sumRating}')");
                            mysqli_query($this->mysql, "UPDATE `general_rating_game` SET `rating` = '{$sumRating}' WHERE `username` = '{$this->user['username']}'");
                        }
                    }

                    return [
                        'status' => 1,
                        'sumRating' => $sumRating,
                        'update_stats' => getRating('array'),
                        'data' => $votes['var_idtest_2_4']
                    ];
                }
            }
            return ['status' => 0, 'messages' => 'Тест не найден!'];
        }
        return ['status' => 0, 'messages' => 'Нет активной игры!'];
    }

    public function reload()
    {
        $row = mysqli_query($this->mysql, "SELECT * FROM `a_active_test` where `user_id` = '{$this->user['id']}' LIMIT 1");
        $row = mysqli_fetch_assoc($row);

        if (isset($row['id'])) {

            $game = mysqli_query($this->mysql, "select * from `a_game_text` WHERE `id` = '{$row['game_id']}' limit 1");
            $game = mysqli_fetch_assoc($game);

            if (isset($game['id'])) {
//                mysqli_query($this->mysql, "DELETE FROM `a_game_rating` WHERE `user_id` = {$this->user['id']} AND `game_id` = {$game["id"]}");
//                mysqli_query($this->mysql, "DELETE FROM `a_game_success` WHERE `user_id` = {$this->user['id']} AND `game_id` = {$game["id"]}");

                $votes = [
                    1 => $game['var_idtest_2'],
                    2 => $game['var_idtest_2_1'],
                    3 => $game['var_idtest_2_2'],
                    4 => $game['var_idtest_2_3']
                ];
                return [
                    'status' => 1,
                    'messages' => 'Ну давайте.',
                    'votes' => [
                        'test' => $votes,
                        'id' => $game['id'],
                        'text' => $game['var_idtest_1'],
                        'rating' => $game['var_idtest_3'],
                        'rating_add' => $game['var_idtest_5'],
                        'rating_minus' => $game['var_idtest_6'],
                        'time' => $game['var_idtest_7'],
                        'speed' => $game['var_idtest_8'],
                        'count_done' => $game['var_idtest_9'],
                        'count_all' => $game['var_idtest_10'],
                    ],
                    "gameStatus" => $this->getGameStatus($game["id"])
                ];
            }
            return ['status' => 0, 'messages' => 'Все тесты пройдены'];
        }
        return ['status' => 0, 'messages' => 'Игры нет'];
    }

    private function getGeneralRating()
    {
        $q = mysqli_query($this->mysql, "SELECT * FROM `general_rating_game` WHERE `username` = '{$this->user["username"]}'");

        return mysqli_fetch_assoc($q);
    }

    private function getGameStatus(int $gameId)
    {
        $gameStatus = mysqli_query($this->mysql, "SELECT * FROM `a_game_success` WHERE `game_id` = $gameId");
        $win = $lose = $winPercent = 0;

        while ($res = mysqli_fetch_assoc($gameStatus)) {
            if ($res["status"] == 1) $win++;
            else $lose++;
        }

        if ($lose == 0) {
            if ($win > 0) $winPercent = 100;
        } else {
            $winPercent = round($win / $lose * 100, 2);

            if ($winPercent > 100) $winPercent = 100;
        }

        return [
            "win" => $win,
            "lose" => $lose,
            "winPercent" => $winPercent
        ];
    }
}
