<?php

function run_in_background($Command, $Priority = 0) {
    if($Priority)
        $PID = shell_exec("nohup nice -n $Priority $Command > ./wss.log & echo $!");
    else
        $PID = shell_exec("nohup $Command >> ./wss.log & echo $!");
    return($PID);
}

$pid = null;
$pidFileExist = false;

// Prevent from recreating new process
if (file_get_contents('.wsserver')) {
	$pidFileExist = true;
	$pid = file_get_contents('.wsserver');
	if (!empty($pid) && (empty($_GET) || isset($_GET['run']))) {
		header("Location: /?pid=" . $pid);
		return;
	}
}

if (isset($_GET['run'])) {
	$pid = run_in_background('sh start.sh');
	file_put_contents('.wsserver', $pid);
	header("Location: /?pid=" . $pid);
	return;
}

$pidFileExist = false;
// Prevent from killing no process
if (file_get_contents('.wsserver')) {
	$pidFileExist = true;
	$pid = file_get_contents('.wsserver');
	if (isset($_GET['kill']) && empty($pid)) {
		header("Location: /");
		return;
	}
}

if (isset($_GET['kill'])) {
	file_put_contents('.wsserver', '');
	$wss = $_GET['kill'] +1;
	$pid = run_in_background('sh stop.sh ' . $wss);
	header("Location: /");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Run Shell script</title>
</head>
<body>
	<h1><?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?></h1>
	<a href="/?run" title="Start WS Server">Start WS Server</a>
	<a href="/?kill=<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>" title="Start WS Server">Stop WS Server</a>
</body>
</html>