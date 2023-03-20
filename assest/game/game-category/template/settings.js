// Audio 
window.onload = function(){
    var audio = $("#audio")[0];$("#test_1").mouseenter(function() {audio.play();});
    var audio1 = $("#audio1")[0];$("#test_2").mouseenter(function() {audio1.play();});
    var audio2 = $("#audio2")[0];$("#test_3").mouseenter(function() {audio2.play();});
    var audio3 = $("#audio3")[0];$("#test_4").mouseenter(function() {audio3.play();});
};

function settingsBoard(){
    var selectBoard = document.getElementById("selectBoard");
    var valueSelectBoard = selectBoard.options[selectBoard.selectedIndex].value;
    if (valueSelectBoard == "1"){
        alert('1')
    }else{
        alert('2')
    }
}

function settingsButtons(){
    var controlsButtons = document.getElementById("controlsButtons");
    var controlsClickBtn = controlsButtons.options[controlsButtons.selectedIndex].value;
    if (controlsClickBtn == "1"){
        alert('1b')
    }else{
        alert('2b')
    }
}


document.getElementById('mouseSound').onclick = function() {
    var isCheckedSound = false;
    if (this.checked == true) {
        // the element is checked
        isCheckedSound = true;
        document.getElementById('audio').src = '/ru/ru-en/dist/audio-soundelements/click.mp3';
        document.getElementById('audio1').src = '/ru/ru-en/dist/audio-soundelements/click.mp3';
        document.getElementById('audio2').src = '/ru/ru-en/dist/audio-soundelements/click.mp3';
        document.getElementById('audio3').src = '/ru/ru-en/dist/audio-soundelements/click.mp3';
    }else{
        document.getElementById('audio').src = '';
        document.getElementById('audio1').src = '';
        document.getElementById('audio2').src = '';
        document.getElementById('audio3').src = '';
        isCheckedSound = false;
    }
};

document.getElementById('stopwatch').onclick = function() {
    var isCheckedstopwatch = false;
    if (this.checked == true) {
        // the element is checked
        isCheckedstopwatch = true;
        document.querySelector('.group-timer').classList.add("group-timer-false");
    }else{
        isCheckedstopwatch = false;
        document.querySelector('.group-timer').classList.remove("group-timer-false");
    }
};

document.getElementById('rattings').onclick = function() {
    var isCheckedRattings = false;
    if (this.checked == true) {
        // the element is checked
        isCheckedRattings = true;
        document.querySelector('.user-content-w2').classList.add("group-timer-false");
    }else{
        isCheckedRattings = false;
        document.querySelector('.user-content-w2').classList.remove("group-timer-false");
    }
};
