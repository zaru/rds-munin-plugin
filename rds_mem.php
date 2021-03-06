#!/usr/bin/php
<?php
$rundir = realpath(dirname($argv[0]));
require_once ($rundir . '/rds_config.php');

if (isset($argv['1']) && $argv['1'] == 'autoconf') {
	echo 'yes' . "\n";
} else if (isset($argv['1']) && $argv['1']== 'config') {
	
	echo <<<EOM
graph_title AWS RDS Freeable Memory
graph_args --base 1000 -l 0
graph_vlabel Freeable Memory
graph_category AWS_RDS
graph_info This graph shows the freeable memory in the RDS.
aws_rds_connection.label memory
aws_rds_connection.draw LINE2
aws_rds_connection.info The current freeable memory.

EOM;
	
} else {

	$result = $client->getMetricStatistics(array(
		'Namespace'  => 'AWS/RDS',
		'MetricName' => 'FreeableMemory',
		'Dimensions' => $dimensions,
		'StartTime'  => strtotime('-5 min'),
		'EndTime'    => strtotime('now'),
		'Period'     => 300,
		'Statistics' => array('Maximum'),
	));
	foreach ($result['Datapoints'] as $val) {
		echo 'aws_rds_mem.value ' . $val['Maximum'] . "\n";
	}

}