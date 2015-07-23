CREATE TABLE reserve_table (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	site VARCHAR(100),
	plane VARCHAR(100),
	name VARCHAR(100),
	node INTEGER,
	startdate INTEGER(8),
	enddate INTEGER(8),
	state ENUM('Reserved','Revising','Bloqued'),
	userinfo VARCHAR(500),
	eventinfo VARCHAR(500),
	necesities VARCHAR(500)
);

CREATE TABLE info_table (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	site VARCHAR(100),
	plane VARCHAR(100),
	name VARCHAR(100),
	node INTEGER,
	inv VARCHAR(50),
	data VARCHAR(100)
);

CREATE TABLE inventory_table(
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	site VARCHAR(100),
	plane VARCHAR(100),
	name VARCHAR(100),
	inventory VARCHAR(500)
);

CREATE TABLE norm_table(
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	site VARCHAR(100),
	plane VARCHAR(100),
	norm VARCHAR(500)
);

INSERT INTO info_table VALUES(null, 'Universidad de Jaén', 'Espacios Ujaen', 'Sala de Grados D1',0,'111101111000100010','{"visible":{"Capacidad":"82", "Audio PC": "Sí", "Cable red":"Sí", "Cable portátil":"Sí", "Micro Inalámbrico":"Sí", "Atril":"Sí", "Cañon":"Sí", "CPU":"Sí", "Megafonía": "Sí", "Traducción simultánea":"Sí"}, "warning":[0]}');

INSERT INTO inventory_table VALUES (null, 'Universidad de Jaén', 'Espacios Ujaen','Sala de Grados D1', '"sin_necesidades", "canon","CPU","megafonia","megafonia_debate","audio_pc","cable_red","cable_portatil","altavoces","micro_inalambrico","atril","tv","videoconferencia","traduccion_simultanea","conexion_cuadro_electrico","agua_caliente_camerinos","botellas","num_personas_mesa_presidencial"');


INSERT INTO reserve_table VALUES (null, 'Universidad de Jaén', 'Espacios Ujaen','Sala de Grados D1', 0, UNIX_TIMESTAMP('2015-07-22 09:00:00'), UNIX_TIMESTAMP('2015-07-22 14:00:00'), 'Reserved', 'No data yet','No data yet','No data yet');
INSERT INTO reserve_table VALUES (null, 'Universidad de Jaén', 'Espacios Ujaen', 12, UNIX_TIMESTAMP('2015-07-09 15:00:00'), UNIX_TIMESTAMP('2015-07-09 15:30:00'), 'Revising', 'No data yet');
INSERT INTO reserve_table VALUES (null, 'Universidad de Jaén', 'Espacios Ujaen', 5, UNIX_TIMESTAMP('2015-07-09 15:00:00'), UNIX_TIMESTAMP('2015-07-09 15:30:00'), 'Bloqued', 'No data yet');