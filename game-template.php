<?php
require_once __DIR__ . "/../backend/db.php";
require_once __DIR__ . "/assest/game/game-category/template/functions.php";
require_once __DIR__ . '/../backend/config/receiving-views.php';

check_auth();

setcookie("startGameTime", time(), strtotime("+1 Year"), "/");
$_SESSION["startGameTime"] = time();

$general_rating_game = mysqli_query($con, "SELECT * FROM `general_rating_game` WHERE `username` = '{$_SESSION["logged_user"]["username"]}'");
$general_rating_game = mysqli_fetch_assoc($general_rating_game);

mysqli_query($con, "UPDATE `general_rating_game` SET `start_rating` = {$general_rating_game["rating"]} WHERE `username` = '{$_SESSION["logged_user"]["username"]}'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../components/url-link-script/link.php' ?>
    <link rel="stylesheet" type="text/css" href="/ru/ru-en/packs/assest/game/game-category/template/style.css">
</head>
<body>
    <?php include '../components/include-top-and-bottom/after-body.php' ?>
<div id="wrapper" class="is-verticle">
    <?php include '../components/nav/menu-navbar/nav-top.php' ?>

    <!-- Main Contents -->
    <main class="main_content">
        <div class="container">
            <?php include '../components/include-top-and-bottom/top.php' ?>

            <h1 class="fs0">Игра по английскому языку - онлайн сборник</h1>
            <div id="game-content">
                <div class="game-content">
                    <div class="game-spation">
                        <div class="game-spation-center">

                            <div class="game-question">
                                <p class="game-question-paragraph" id="votes-text" style="display: block"></p>
                                <p class="game-question-paragraph" id="votes-start" style="display: block">
                                    Начать тесты
                                </p>
                            </div>

                            <div class="flex justify-center">
                                <div class="variant-question" id="votes-list"></div>
                            </div>
                            
                            <!-- src='/ru/ru-en/dist/audio-soundelements/click.mp3' -->
                            <audio id="audio" src="/ru/ru-en/dist/audio-soundelements/click.mp3">
                            </audio>
                            <audio id="audio1" src="/ru/ru-en/dist/audio-soundelements/click.mp3"> 
                            </audio>
                            <audio id="audio2" src="/ru/ru-en/dist/audio-soundelements/click.mp3"> 
                            </audio>
                            <audio id="audio3" src="/ru/ru-en/dist/audio-soundelements/click.mp3"> 
                            </audio>
                        </div>
                    </div>
                    <div class="game-setings">
                        <div class="buttons-setings">
                            <a href="#settings-play-chose" uk-toggle class="btn-controls-setings">
                                <i class="fa-solid fa-gear"></i>
                            </a>
                            <div class="btn-controls-setings" id="fa-expand">
                                <i class="fa-solid fa-expand"></i>
                            </div> 
                            <div class="btn-controls-setings d-none" id="fa-compress">
                                <i class="fa-solid fa-compress"></i>
                            </div> 
                        </div>
                    </div>
                    <section class="game-detailes">
                        <ul class="user-content-top btn-bg-answer" id="header-game">
                            <li class="">
                                <a href="#" class="back-games-category">
                                    <i class="fa-solid fa-arrow-left"></i>
                                </a>
                            </li>
                            <li class="flex-1">
                                <p class="info-title-game">Выберите правильный ответ</p>
                            </li>
                        </ul>
                        <ul class="user-content-middle">
                            <li class="user-content-w1">
                                <img class="user-content-image" alt="user profile photo"
                                     src="/ru/ru-en/dist/images/user-images/<?= $_SESSION["logged_user"]["image"] ?>">
                            </li>
                            <li class="user-content-w2" id="rate">
                                <p class="group-rating">
                                    <p class="summ-rating"><?= $general_rating_game["rating"] ?></p>
                                    <p class="added-rating rating-status"></p>
                                </p>
                            </li>  
                            <li class="user-content-w3">
                                <p> 
                                    <span id="time-percent" style="display: none">100%</span>
                                </p>
                            </li>
                            <li class="user-content-w3">
                                <p class="group-timer">
                                    <i class="fa-solid fa-clock"></i>
                                    <span id="timer">00:00</span>
                                </p>
                            </li>
                        </ul>
                        <div class="data-quiz-middle">
                            <div class="content-games-center">
                                <div class="data-chartjs">
                                    <div id="myChart"></div>
                                </div>

                                <div class="data-quiz" style="display: none">
                                    <div class="flex">
                                        <p class="flex-1">Рейтинг задачи <span class="code-quiz" id="votes-id"></span></p>
                                        <p class="data-content-user" id="votes-id-rate">0</p>
                                    </div>
                                    <div class="flex">
                                        <p class="flex-1">Целевое время <span
                                                    uk-tooltip="title: Думаете быстрее игрок с вашим рейтингом должен решать задачу за это или меньшее время чтобы набрать 100%; pos: bottom;"
                                                    aria-expanded="false" title=""><i class="fa-solid fa-circle-info"></i></span>
                                        </p>
                                        <p class="data-content-user" id="votes-time">0:0</p>
                                    </div>
                                    <div class="flex">
                                        <p class="flex-1">Бонус скорости</p>
                                        <p class="data-content-user" id="votes-bonus">0 <span>%</span></p>
                                    </div>
                                    <div class="flex">
                                        <p class="flex-1">Справились</p>
                                        <p class="data-content-user" id="votes-done">0 <span>%</span></p>
                                    </div>
                                    <div class="flex">
                                        <p class="flex-1">Попыток</p>
                                        <p class="data-content-user" id="votes-try">0</p>
                                    </div>
                                </div>
                                <div class="rated-sidebar-footer" style="display: none">
                                    <div class="group-buttons-end">
                                        <div class="group-btn-li group-btn-pr help-reload" style="display: none" onclick="reload()">
                                            <button class="button-3 bg-9 group-btn-end btn-bg-retry">
                                                <i class="fa-solid fa-rotate-right flip-text"></i>
                                            </button>
                                        </div>
                                        <div style="width: 100%" class="group-btn-li group-btn-pr help-votes" onclick="help()">
                                            <button class="button-3 bg-9 group-btn-end game-button">
                                                <i class='fa-solid fa-lightbulb'></i>
                                            </button>
                                        </div>
                                        <div class="group-btn-li">
                                            <button class="button-3 bg-9 group-btn-end btn-bg-next next-votes"
                                                    style="display: none">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <?php include '../components/include-top-and-bottom/bottom.php' ?>
        </div>

        <!-- footer -->
        <div class="d-none">
            <?php include '../components/nav/footer/footer.php' ?>
        </div>
    </main>
    <?php include '../components/nav/menu-navbar/nav-left.php' ?>
</div>


<div id="settings-play-chose" class="items-center" uk-modal>
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical"> 
        <div class="uk-modal-body" uk-overflow-auto>
            <div class="settings-flex">
                <div class="settings-text">
                    <p class="p-settings">Доска</p>
                </div>
                <div class="settings-control">
                    <select class="uk-select uk-select-settings" id="selectBoard" onchange="settingsBoard()">
                      <option value="1">One</option>
                      <option value="2">Two</option>
                    </select>
                </div>
            </div>

            <div class="settings-flex">
                <div class="settings-text">
                    <p class="p-settings">Кнопки</p>
                </div>
                <div class="settings-control">
                    <select class="uk-select uk-select-settings" id="controlsButtons" onchange="settingsButtons()">
                      <option value="1">One</option>
                      <option value="2">Two</option>
                    </select>
                </div>
            </div> 
             
            <div class="settings-flex">
                <div class="settings-text">
                    <p class="p-settings">Включить звук</p>
                </div>
                <div class="settings-control switch-settings-control">
                    <input type="checkbox" hidden="hidden" id="mouseSound" checked>
                    <label class="switch uk-switch-settings" for="mouseSound"></label>
                </div>
            </div>
            <div class="settings-flex">
                <div class="settings-text">
                    <p class="p-settings">Скрыть секундомер</p>
                </div>
                <div class="settings-control switch-settings-control">
                    <input type="checkbox" hidden="hidden" id="stopwatch">
                    <label class="switch uk-switch-settings" for="stopwatch"></label>
                </div>
            </div>
            <div class="settings-flex">
                <div class="settings-text">
                    <p class="p-settings">Скрыть рейтинг</p>
                </div>
                <div class="settings-control switch-settings-control">
                    <input type="checkbox" hidden="hidden" id="rattings">
                    <label class="switch uk-switch-settings" for="rattings"></label>
                </div>
            </div>
        </div> 
    </div>
</div>


</body>
<?php include '../components/url-link-script/script.php' ?>
<script src="/ru/ru-en/plugins/echarts/echarts.min.js"></script>
<script type="text/javascript" src="/ru/ru-en/packs/assest/game/game-category/template/timer.js"></script>
<script type="text/javascript" src="/ru/ru-en/packs/assest/game/game-category/template/scripts.js"></script>
<script type="text/javascript" src="/ru/ru-en/packs/assest/game/game-category/template/settings.js"></script>
<script type="text/javascript">
    let rating = <?= $general_rating_game["rating"] ?>;
    let startChartData = <?= getRating() ?>;
    let dataLineStart = startChartData.length - 1
    startChartData = startChartData.slice(dataLineStart)

    let chartSel = document.getElementById("myChart")
    const chart = echarts.init(chartSel);

    let chartOption = {
        grid: {
            height: 100,
            width: "85%",
            top: 30
        },
        xAxis: {
            type: 'category',
            data: [1, 2, 3, 4, 5, 6, 7, 8],
            axisLabel: {
                show: false
            },
            axisTick: {
                show: false
            },
            axisLine: {
                show: false
            },
            splitLine: {
                show: true,
                lineStyle: {
                    color: "rgba(242, 241, 241, 1)"
                }
            },
        },
        yAxis: {
            type: 'value',
            interval: 30,
            max: Number(startChartData[0]) + 30,
            min: Number(startChartData[0]) - 30,
            splitLine: {
                lineStyle: {
                    color: "rgba(242, 241, 241, 1)"
                }
            },
            axisLabel: {
                show: true,
                fontWeight: "bold",
                fontSize: 13,
                color: "rgb(29,29,29)"
            }
        },
        series: [
            {
                data: startChartData,
                symbolSize: 8,
                type: 'line',
                itemStyle: {
                    color: "#4c504f"
                }
            }
        ]
    };

    chart.setOption(chartOption);

    window.addEventListener('resize', chart.resize);

    let reqCount = 1;
    let seconds = 0;
    let tester = 0;
    let restart = 0;
    let helpCount = 0;
    let questionTime = 0;

    function renders(data, timer, updates) {
        if (timer) {
            startTimer(timerTick);
        }
        if (updates) {
            seconds = 1;
        } else seconds = 0;

        $('#votes-text').html(data.votes.text);

        let votesList = []

        for (let i in data.votes.test) {
            votesList.push('<div class="block-answer">' +
                '<button class="button-2 btn-answer votes-data" data-id="' + i + '" id="test_' + i + '" data-help="0" onclick="votes(this)">' +
                ' <div class="w-full flex">' +
                ' <p class="flex-1">' + data.votes.test[i] + '</p> ' +
                '</div> ' +
                '</button> ' +
                '</div>')
        }

        votesList.sort(() => Math.random() - 0.5)

        for (const vote of votesList) {
            $('#votes-list').append(vote)
        }

        questionTime = Number(data.votes.time)
        $('#votes-id').html('(#' + data.votes.id + ')');
        $('#votes-id-rate').html(data.votes.rating);
        $('#votes-time').html(data.votes.time);
        $('#votes-bonus').html(restart > 0 ? "-" : "+3");
        $('#time-percent').html(restart > 0 ? "" : "100%");
        $('#votes-done').html(data.gameStatus.winPercent + '%');
        $('#votes-try').html(Number(data.gameStatus.lose) + Number(data.gameStatus.win));
        $('.rated-sidebar-footer, .data-quiz').show();
    }

    function votes(el, timers) {

        if (!$(el).prop('disabled')) {
            $('.votes-data').each(function () {
                $(this).prop('disabled', true);
            });
            let time = timer;

            if(helpCount == 0) {
                reqCount = 1;
            }

            stopTimer();

            $.ajax({
                type: 'POST',
                url: '/ru/ru-en/packs/assest/game/game-category/template/game.php',
                data: {
                    method: 'info',
                    id: el.dataset.id,
                    timer: time,
                    tester: tester,
                    restart: restart
                },
                success: function (data) {
                    if (sendError(data)) return

                    setRating(data.rate)

                    if (data.status == 1) {
                        $(el).addClass('answer-accepted');
                        if (!$(el).find('p').hasClass('votes-success')) {
                            $(el).find('p').after('<p class="votes-success"><i class="fa-solid fa-check"></i></p>');
                        }
                        $("#time-percent").show()
                        $('.next-votes').show().closest("div").css("width", "48%");
                        $('.help-votes').hide();
                        $('.help-reload').show().css("width", "52%");
                        $('.help-reload').find('button').removeClass('btn-bg-retry');
                        $('#header-game').removeClass('btn-bg-answer').addClass('btn-bg-next');

                        if (!$('.next-votes').hasClass('btn-bg-next')) {
                            $('.next-votes').addClass('btn-bg-next');
                        }

                        updateChart(data.update_stats)
                    } else if (data.status == 3) {
                        $(el).addClass('answer-error');
                        $(el).find('p').after('<p><i class="fa-solid fa-xmark"></i></p>');

                        $('.help-reload').show().attr("style", "");
                        $('.help-votes').show().attr("style", "");
                        $('.next-votes').show().closest("div").attr("style", "");
                        $('#votes-bonus').html("-");
                        $("#time-percent").html("");

                        $('#header-game').removeClass('btn-bg-answer').addClass('btn-bg-retry');
                        $('.next-votes').removeClass('btn-bg-next');

                        updateChart(data.update_stats)
                        seconds = 5;
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    }

    function reload() {
        $('.help-reload').hide();
        $('.next-votes').hide();
        $("#time-percent").hide()
        startTimer(timerTick);

        $('#header-game').removeClass('btn-bg-retry').removeClass('btn-bg-next').addClass('btn-bg-answer');

        reqCount = 1;
        seconds = 0;

        $.ajax({
            type: 'POST',
            url: '/ru/ru-en/packs/assest/game/game-category/template/game.php',
            data: {
                method: 'reload',
            },
            success: function (data) {
                if (sendError(data)) return

                $('#votes-list').empty();
                $('.help-votes').show().css("width", "100%");
                restart++;

                renders(data, false);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    function help() {
        $('#header-game').removeClass('btn-bg-retry').removeClass('btn-bg-next').addClass('btn-bg-answer');

        if (reqCount == 1 || reqCount == 2) {
            $.ajax({
                type: 'POST',
                url: '/ru/ru-en/packs/assest/game/game-category/template/game.php',
                data: {
                    method: 'help',
                    req: reqCount,
                    restart: restart,
                    timer: timer
                },
                success: function (data) {
                    if (sendError(data)) return

                    if (data.status == 1) {
                        $('#votes-bonus').html("-");
                        $("#time-percent").html("")

                        if (reqCount == 1) {
                            helpCount = 1;

                            $('#helpIcon').each(function () {
                                $(this).remove();
                            })

                            let count = 0;

                            if (seconds != 5) {
                                seconds = 4;
                            }

                            for (let i = 1; i < 5; i++) {
                                if (data.data != i && count < 2) {
                                    if (!$('#test_' + i).hasClass('answer-error') && !$('#test_' + i).hasClass('answer-close')) {
                                        $('#test_' + i).prop('disabled', true);
                                        $('#test_' + i).removeClass('answer-close').addClass('answer-close');
                                        $('#test_' + i).find('p').after('<p id="helpIcon"><i class="fa-solid fa-bomb"></i></p>');
                                        count++;
                                    }
                                }
                                if (data.data == i) {
                                    $('#test_' + i).prop('disabled', false);
                                }
                                $('#test_' + i).attr("data-help","1")
                            }
                            reqCount = 2;
                            tester = 2;
                        } else if (reqCount == 2) {
                            stopTimer()
                            seconds = 5;
                            $('.next-votes').show().closest("div").css("width", "48%");
                            $('.help-votes').css("width", "52%")
                            $('.help-reload').hide();
                            $('#test_' + data.data).addClass('answer-accepted');
                            $('#test_' + data.data).find('p').after('<p class="votes-success" id="help_success"><i class="fa-solid fa-check"></i></p>');

                            setRating(data.sumRating)

                            for (let i = 1; i < 5; i++) {
                                $('#test_' + i).prop('disabled', true);
                            }
                            reqCount = 3;

                            updateChart(data.update_stats)
                        }
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    }

    function render() {
        $('#votes-start').hide();
        $('#votes-text').show();
        $.ajax({
            type: 'POST',
            url: '/ru/ru-en/packs/assest/game/game-category/template/game.php',
            data: {
                method: 'start'
            },
            success: function (data) {
                if (sendError(data)) return

                if (data.status === 1) {
                    reqCount = 1;
                    tester = 1;
                    restart = 0;
                    helpCount = 0;
                    renders(data, true, 1);
                } else $('#votes-text').html(data.messages);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    function updateChart(data) {
        if (data.length - dataLineStart >= 20) dataLineStart += 5

        data = data.slice(dataLineStart)
        let xAxisData = data.length <= 8 ? chartOption.xAxis.data : []

        for (let i = 0; i < data.length; i++) {
            let chartMin = chartOption.yAxis.min
            let chartMax = chartOption.yAxis.max
            let interval = chartOption.yAxis.interval

            if (chartMin > Number(data[i]) || chartMax < Number(data[i])) {
                chartMin -= 30
                chartMax += 30
                interval += 30
            }

            chartOption.yAxis.min = chartMin
            chartOption.yAxis.max = chartMax
            chartOption.yAxis.interval = interval

            if (i >= xAxisData.length) {
                xAxisData.push(i)
            }
        }

        chartOption.series[0].data = data
        chartOption.xAxis.data = xAxisData

        chart.setOption(chartOption)
    }

    function setRating(newRating) {
        const additionalRating = newRating - rating
        const ratingStatus = $('#rate .rating-status')
        const sumRating = $('#rate .summ-rating')
        rating = newRating

        if (additionalRating === 0) {
            ratingStatus.html("")
        } else if (additionalRating > 0) {
            ratingStatus.removeClass("taken-rating")
            ratingStatus.addClass("added-rating")
            ratingStatus.html("+" + additionalRating)
        } else {
            ratingStatus.addClass("taken-rating")
            ratingStatus.removeClass("added-rating")
            ratingStatus.html(additionalRating)
        }

        sumRating.html(rating)
    }

    let timePercent = 0
    function timerTick(time) {
        if (questionTime > 0) {
            timePercent = Math.floor(time / questionTime * 100)

            if (timePercent > 100) return

            if (restart <= 0) {
                if (timePercent <= 25) $('#votes-bonus').html("+3");
                else if (timePercent <= 50) $('#votes-bonus').html("+2");
                else if (timePercent <= 75) $('#votes-bonus').html("+1");

                if (timePercent > 75 || helpCount > 0) {
                    $('#votes-bonus').html("-");
                    $('#time-percent').html("");
                } else {
                    $('#time-percent').html(`${100 - timePercent}%`);
                }
            } else {
                $('#votes-bonus').html("-");
                $('#time-percent').html("");
            }
        }
    }

    function sendError(data) {
        if (data.status == 0) {
            warning_modal(data.messages)

            return true
        }

        return false
    }

    $('.btn-bg-next').click(function () {
        $('#votes-list').empty();
        $('.help-reload').hide();
        $('.next-votes').hide();
        $("#time-percent").hide()
        $('.help-votes').show().css("width", "100%")
        $('#rate .rating-status').html("")
        $('#header-game').removeClass('btn-bg-retry').removeClass('btn-bg-next').addClass('btn-bg-answer');
        render();
    });
    render()
 

</script>

<script>
    window.addEventListener("load", () => supplementAnalytics())

    function supplementAnalytics(plusTime = 0) {
        fetch(location.pathname, {
            method: "POST",
            headers: {
                "Content-type": "application/x-www-form-urlencoded"
            },
            body: `width=${innerWidth}x${innerHeight}&plusTime=${plusTime}`
        })
    }

    // Update time after 60 sec
    setInterval(() => supplementAnalytics(60), 60000)
</script>
</html>
