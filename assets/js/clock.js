// include_once('../config.php');

var radius = 45;
var outerRadius = radius - 10;
var dtg = new Date();
var hands = {};
var numbers = document.getElementById('numbers');
var ticks = document.getElementById('ticks');
var mark;
var rotation;
var number;
var angle;

hands.second = (dtg.getSeconds() + dtg.getMilliseconds() / 1000) / 60;
hands.minute = (dtg.getMinutes() + hands.second) / 60;
hands.hour = (dtg.getHours() % 12 + hands.minute) / 12;

for (key in hands) {
    document.getElementById(key).setAttribute('transform', "rotate(" + (hands[key] * 360) + ")");
}


function cE(type) {
    return document.createElementNS("http://www.w3.org/2000/svg", type);
}

function createMark(group, outerRadius, length, rotation) {
    var mark = cE('line');
    mark.setAttribute('x1', outerRadius - length);
    mark.setAttribute('x2', outerRadius);
    mark.setAttribute('y1', '0');
    mark.setAttribute('y2', '0');
    mark.setAttribute('transform', 'rotate(' + rotation + ')');
    group.appendChild(mark);
}

for (var i = 0; i < 12; i++) {
    number = cE('text');
    angle = Math.PI / 6 * i;
    number.setAttribute('x', radius * Math.cos(angle));
    number.setAttribute('y', radius * Math.sin(angle));
    number.innerHTML = ((i + 2) % 12 + 1);
    numbers.appendChild(number);
    rotation = i * 30;
    createMark(ticks, outerRadius, 16, rotation);

    for (j = 1; j < 5; j++) {
        createMark(ticks, outerRadius, 8, rotation + j * 6);
    }
}

$timezone = $config['timezone'];
if (is_link('/etc/localtime')) {
    $filename = readlink('/etc/localtime');
    if (strpos($filename, '/usr/share/zoneinfo/') === 0) {
        $timezone = substr($filename, 20);
    }
}
elseif(file_exists('/etc/timezone')); {
    $data = file_get_contents('/etc/timezone');
    if ($data) {
        $timezone = $data;
    }
}
elseif(file_exists('/etc/sysconfig/clock')); {
    $data = parse_ini_file('/etc/sysconfig/clock');
    if (!empty($data['ZONE'])) {
        $timezone = $data['ZONE'];
    }
}

date_default_timezone_set($timezone);
$timestamp = time();
$server_date = date("D, d M Y");
