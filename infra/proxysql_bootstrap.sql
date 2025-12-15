

-- 1) Backends (hostgroups): limpiar e insertar
DELETE FROM mysql_servers WHERE hostgroup_id IN (10,20);

INSERT INTO mysql_servers (hostgroup_id, hostname, port, comment) VALUES
  (10, 'db',    3306, 'Servidor principal (master)'),
  (20, 'slave', 3306, 'Servidor secundario (replica)');

LOAD MYSQL SERVERS TO RUNTIME;
SAVE MYSQL SERVERS TO DISK;


DELETE FROM mysql_replication_hostgroups
WHERE writer_hostgroup=10 OR reader_hostgroup=20;

INSERT INTO mysql_replication_hostgroups
  (writer_hostgroup, reader_hostgroup, comment)
VALUES
  (10, 20, 'R/W split');


-- 2) Usuario de la app en proxysql
DELETE FROM mysql_users WHERE username='userSS';

INSERT INTO mysql_users
  (username, password, default_hostgroup, transaction_persistent, fast_forward)
VALUES
  ('userSS', 'pass', 10, 1, 0);

LOAD MYSQL USERS TO RUNTIME;
SAVE MYSQL USERS TO DISK;

-- 3) Reglas de enrutado

DELETE FROM mysql_query_rules WHERE rule_id IN (1,2,3,4);

-- CALL de escritura (prefijo esc_) → MASTER (HG 10)
INSERT INTO mysql_query_rules
  (rule_id, active, match_pattern, destination_hostgroup, apply, multiplex, flagOUT)
VALUES
  (1, 1, '^Call[[:space:]]+esc_', 10, 1, 0, 1);

-- CALL de lectura (prefijo lec_) → SLAVE (HG 20)
INSERT INTO mysql_query_rules
  (rule_id, active, match_pattern, destination_hostgroup, apply, multiplex, flagOUT)
VALUES
  (2, 1, '^Call[[:space:]]+lec_', 20, 1, 0, 1);

-- Todos los SELECT → SLAVE (HG 20)
INSERT INTO mysql_query_rules
  (rule_id, active, match_pattern, destination_hostgroup, apply)
VALUES
  (3, 1, '^SELECT', 20, 1);

-- (INSERT/UPDATE/DELETE/REPLACE) → MASTER (HG 10)
INSERT INTO mysql_query_rules
  (rule_id, active, match_pattern, destination_hostgroup, apply)
VALUES
  (4, 1, '^(INSERT|UPDATE|DELETE|REPLACE)', 10, 1);

LOAD MYSQL QUERY RULES TO RUNTIME;
SAVE MYSQL QUERY RULES TO DISK;

LOAD MYSQL QUERY RULES TO RUNTIME;
SAVE MYSQL QUERY RULES TO DISK;

UPDATE global_variables SET variable_value='0'
 WHERE variable_name='mysql-connection_delay_multiplex_ms';

UPDATE global_variables SET variable_value='30000'
 WHERE variable_name='mysql-session_idle_timeout';

UPDATE global_variables SET variable_value='0'
 WHERE variable_name='mysql-monitor_writer_is_also_reader';

LOAD MYSQL VARIABLES TO RUNTIME;
SAVE MYSQL VARIABLES TO DISK;

