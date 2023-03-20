//объявляем переменные
var base = 60;
var clocktimer, dateObj, dh, dm, ds, ms;
var readout = '';
var h = 1,
    m = 1,
    tm = 1,
    s = 0,
    ts = 0,
    ms = 0,
    timer = 0,
    init = 0;

//функция для очистки поля
function ClearСlock() {
    clearTimeout(clocktimer);
    h = 1;
    m = 1;
    tm = 1;
    s = 0;
    ts = 0;
    ms = 0;
    init = 0;
    readout = '00:00';
    timer = 0;
    document.querySelector('#timer').innerHTML = readout;
}

//функция для старта секундомера
function StartTIME(tickFunc = null) {
    var cdateObj = new Date();
    var t = (cdateObj.getTime() - dateObj.getTime()) - (s * 1000);
    if (t > 999) {
        s++;
    }
    if (s >= (m * base)) {
        ts = 0;
        m++;
    } else {
        ts = parseInt((ms / 100) + s);
        if (ts >= base) {
            ts = ts - ((m - 1) * base);
        }
    }
    if (m > (h * base)) {
        tm = 1;
        h++;
    } else {
        tm = parseInt((ms / 100) + m);
        if (tm >= base) {
            tm = tm - ((h - 1) * base);
        }
    }
    ms = Math.round(t / 10);
    if (ms > 99) {
        ms = 0;
    }
    if (ms == 0) {
        ms = '00';
    }
    if (ms > 0 && ms <= 9) {
        ms = '0' + ms;
    }
    if (ts > 0) {
        ds = ts;
        if (ts < 10) {
            ds = '0' + ts;
        }
    } else {
        ds = '00';
    }
    dm = tm - 1;
    if (dm > 0) {
        if (dm < 10) {
            dm = '0' + dm;
        }
    } else {
        dm = '00';
    }
    dh = h - 1;
    if (dh > 0) {
        if (dh < 10) {
            dh = '0' + dh;
        }
    } else {
        dh = '00';
    }
    document.querySelector('#timer').innerHTML = dm + ':' + ds;

    if (tickFunc) tickFunc(timer)

    timer++;

    clocktimer = setTimeout(() => StartTIME(tickFunc), 1000);
}

//Функция запуска и
function StartStop(tickFunc = null) {
    if (init == 0) {
        ClearСlock();
        dateObj = new Date();
        StartTIME(tickFunc);
        init = 1;
    } else {
        clearTimeout(clocktimer);
        init = 0;
    }
}

function startTimer(tickFunc = null) {
    if (init == 0) {
        ClearСlock();
        dateObj = new Date();
        StartTIME(tickFunc);
        init = 1;
    }
}

function stopTimer() {
    if (init != 0) {
        clearTimeout(clocktimer);
        init = 0;
    }
}
