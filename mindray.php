<?php
if(empty($_GET['fecha']))
	die('No se ha definido una fecha');

$year = explode('/', $_GET['fecha'])[0];
$month = explode('/', $_GET['fecha'])[1];
$day = explode('/', $_GET['fecha'])[2];

$c = odbc_connect('mindray','','');

$patientInfo = [];
$sample = [];
$test = [];
$testDefine = [];

$patientInfoQry = odbc_exec($c, sprintf("SELECT ID, Name FROM PatientInfo WHERE YEAR(QuestDate) = %s AND MONTH(QuestDate) = %s AND DAY(QuestDate) = %s order by ID", $year, $month, $day));
$sampleQry = odbc_exec($c, sprintf("SELECT ID, PatientID FROM Sample WHERE YEAR(QuestDate) = %s AND MONTH(QuestDate) = %s AND DAY(QuestDate) = %s order by ID", $year, $month, $day));
$testQry = odbc_exec($c, sprintf("SELECT ItemID, SampleID, TestResult FROM TestDetail WHERE YEAR(TestTime) = %s AND MONTH(TestTime) = %s AND DAY(TestTime) = %s", $year, $month, $day));
$testDefineQry = odbc_exec($c, "SELECT ID, Name FROM TestDefine");

while($res = odbc_fetch_array($patientInfoQry)) {
	$patientInfo[$res['ID']] = $res['Name'];
}

while($res = odbc_fetch_array($testDefineQry)) {
	$testDefine[$res['ID']] = $res['Name'];
}

while($res = odbc_fetch_array($sampleQry)) {
	$numero_paciente = isset($patientInfo[$res['PatientID']]) ? $patientInfo[$res['PatientID']] : '';

	if(empty($numero_paciente)) //Omite los pacientes sin numero asignado
		continue;
	$sample[$res['ID']]['numero_paciente'] = $numero_paciente;
}

while($res = odbc_fetch_array($testQry)) {
	if(!isset($sample[$res['SampleID']]))
		continue;
	$sample[$res['SampleID']]['examenes'][] = ['examen'=>$testDefine[$res['ItemID']] ,'resultado'=>$res['TestResult']];
}

echo json_encode($sample);
