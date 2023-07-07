<?php
if(!$api = file_get_contents('http://localhost/mindray.php?fecha=2019/10/24'))
	die('Error al intentar comunicar con API');

$data = json_decode($api);

if(!$data || is_null($data))
	die('Error al decodificar datos');

foreach($data as $paciente) {
	echo $paciente->numero_paciente; //Código que le asignan en el nombre del paciente en el equipo.
	echo '<br/>';
	foreach($paciente->examenes as $resultado) {
		echo $resultado->examen; //El nombre del examen
		echo $resultado->resultado; //resultado del examen
		echo '<br/>';
	}
	echo '<hr>';
}

/*
GLUCOSA
COLESTEROL
TRIGLICERIDO
AC. URICO
UREA
CREATININA
PROTEINAS T.
(ALP)F. ALCALINA
ALBUMINA
LDL COL
GGT
AMILASA
LDH
TGO SPINREACT
TGP SPINREACT
PCR
FR(FACTOR REUMATOID)
LIPASA
*/