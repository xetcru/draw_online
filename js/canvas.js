var canvas = document.getElementById('canvas');
var ctx = canvas.getContext('2d');

var painting = false;
var color = '#000000';
var thickness = 1;
var startX, startY;

document.getElementById('color').onchange = function() {
    color = this.value;
}

document.getElementById('thickness').onchange = function() {
    thickness = this.value;
}

canvas.onmousedown = function(e) {
    painting = true;
    startX = e.clientX - canvas.offsetLeft;
    startY = e.clientY - canvas.offsetTop;
    ctx.beginPath();
    ctx.moveTo(startX, startY);
}

canvas.onmousemove = function(e) {
    if(painting) {
        var endX = e.clientX - canvas.offsetLeft;
        var endY = e.clientY - canvas.offsetTop;
        ctx.lineTo(endX, endY);
        ctx.strokeStyle = color;
        ctx.lineWidth = thickness;
        ctx.stroke();

        var line = {startX: startX, startY: startY, endX: endX, endY: endY, color: color, thickness: thickness};
        var lines = JSON.parse(localStorage.getItem('lines')) || [];
        lines.push(line);
        localStorage.setItem('lines', JSON.stringify(lines));

        startX = endX;
        startY = endY;
    }
}

canvas.onmouseup = function() {
    painting = false;
    var lines = JSON.parse(localStorage.getItem('lines')) || [];
    lines.forEach(function(line) {
        fetch('php/canvas_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({line: line}),
        });
    });
    localStorage.removeItem('lines');
}
// автообновление
/*setInterval(function() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}, 2000);*/
setInterval(function() {
    fetch('php/canvas_data.php')
        .then(response => response.json())
        .then(data => {
            // Очистите холст
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Нарисуйте данные на холсте
            data.forEach(drawLine);
        });
}, 2000);
function drawLine(line) {
    ctx.beginPath();
    ctx.moveTo(line.startX, line.startY);
    ctx.lineTo(line.endX, line.endY);
    ctx.strokeStyle = line.color;
    ctx.lineWidth = line.thickness;
    ctx.stroke();
}
// очистить все
document.getElementById('clearall').onclick = function() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}
