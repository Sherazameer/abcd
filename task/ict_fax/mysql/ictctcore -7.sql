
MariaDB [ictfax]> select * from role_permission;
+--------------------+---------+---------------+
| role_permission_id | role_id | permission_id |
+--------------------+---------+---------------+
|                  1 |       1 |             1 |
|                  2 |       1 |             3 |
|                  3 |       1 |             6 |
|                  4 |       1 |            72 |
|                  5 |       1 |            80 |
|                  6 |       1 |            88 |
|                  7 |       1 |            94 |
|                  8 |       1 |            65 |
|                  9 |       1 |            44 |
|                 10 |       2 |             1 |
|                 11 |       2 |             4 |
|                 12 |       2 |             7 |
|                 13 |       2 |            75 |
|                 14 |       2 |            76 |
|                 15 |       2 |            83 |
|                 16 |       2 |            84 |
|                 17 |       2 |            68 |
|                 18 |       2 |            69 |
|                 19 |       2 |            67 |
|                 20 |       2 |            51 |
|                 21 |       2 |           114 |
|                 22 |       2 |           126 |
|                 23 |       2 |           108 |
|                 24 |       2 |           120 |
|                 25 |       2 |             9 |
|                 26 |       2 |            16 |
|                 27 |       2 |            24 |
|                 28 |       2 |            30 |
|                 29 |       2 |            36 |
|                 30 |       2 |            40 |
|                 31 |       2 |            58 |
|                 32 |       2 |            99 |
|                 33 |       2 |             1 |
|                 34 |       2 |             3 |
|                 35 |       2 |             6 |
|                 36 |       2 |            72 |
|                 37 |       2 |            80 |
|                 38 |       2 |            88 |
|                 39 |       2 |            94 |
|                 40 |       2 |            65 |
|                 41 |       2 |            44 |
+--------------------+---------+---------------+
41 rows in set (0.001 sec)


MariaDB [ictfax]> select * from user_role;
+---------+--------+
| role_id | usr_id |
+---------+--------+
|       2 |      2 |
|       1 |      1 |
|       2 |      1 |
|       2 |      3 |
+---------+--------+




MariaDB [ictfax]> desc account;
+--------------+------------------+------+-----+---------+----------------+
| Field        | Type             | Null | Key | Default | Extra          |
+--------------+------------------+------+-----+---------+----------------+
| account_id   | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| type         | varchar(32)      | YES  | MUL | NULL    |                |
| username     | varchar(64)      | YES  |     | NULL    |                |
| passwd       | varchar(128)     | YES  |     | NULL    |                |
| passwd_pin   | varchar(32)      | YES  |     | NULL    |                |
| first_name   | varchar(128)     | YES  |     | NULL    |                |
| last_name    | varchar(128)     | YES  |     | NULL    |                |
| phone        | varchar(16)      | YES  |     | NULL    |                |
| email        | varchar(128)     | YES  |     | NULL    |                |
| address      | varchar(128)     | YES  |     | NULL    |                |
| active       | int(1)           | NO   |     | 0       |                |
| settings     | text             | YES  |     | NULL    |                |
| date_created | int(11)          | YES  |     | NULL    |                |
| created_by   | int(11)          | YES  |     | NULL    |                |
| last_updated | int(11)          | YES  |     | NULL    |                |
| updated_by   | int(11) unsigned | YES  |     | NULL    |                |
+--------------+------------------+------+-----+---------+----------------+
16 rows in set (0.002 sec)

MariaDB [ictfax]> desc usr;
+--------------+------------------+------+-----+---------+----------------+
| Field        | Type             | Null | Key | Default | Extra          |
+--------------+------------------+------+-----+---------+----------------+
| usr_id       | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| role_id      | int(11) unsigned | NO   |     | 0       |                |
| username     | varchar(64)      | YES  | UNI | NULL    |                |
| passwd       | varchar(128)     | YES  |     | NULL    |                |
| first_name   | varchar(128)     | YES  |     | NULL    |                |
| last_name    | varchar(128)     | YES  |     | NULL    |                |
| phone        | varchar(16)      | YES  |     | NULL    |                |
| mobile       | varchar(16)      | YES  |     | NULL    |                |
| email        | varchar(128)     | YES  | UNI | NULL    |                |
| address      | varchar(128)     | YES  |     | NULL    |                |
| company      | varchar(128)     | YES  |     | NULL    |                |
| country_id   | int(11)          | YES  |     | NULL    |                |
| language_id  | varchar(2)       | YES  |     | NULL    |                |
| timezone_id  | int(11)          | YES  |     | NULL    |                |
| active       | int(1)           | NO   |     | 0       |                |
| date_created | int(11)          | YES  |     | NULL    |                |
| created_by   | int(11)          | YES  |     | NULL    |                |
| last_updated | int(11)          | YES  |     | NULL    |                |
| updated_by   | int(11) unsigned | YES  |     | NULL    |                |
+--------------+------------------+------+-----+---------+----------------+
19 rows in set (0.001 sec)

MariaDB [ictfax]> 
MariaDB [ictfax]> 
MariaDB [ictfax]> 
MariaDB [ictfax]> 
MariaDB [ictfax]> 
MariaDB [ictfax]> 
MariaDB [ictfax]> select * from configuration_data;
Empty set (0.001 sec)

MariaDB [ictfax]> desc configuration_data;
+------------------+------------------+------+-----+---------+-------+
| Field            | Type             | Null | Key | Default | Extra |
+------------------+------------------+------+-----+---------+-------+
| configuration_id | int(11)          | NO   | PRI | NULL    |       |
| class            | int(11)          | NO   | PRI | 1       |       |
| node_id          | int(11) unsigned | NO   | PRI | 0       |       |
| campaign_id      | int(11)          | NO   | PRI | 0       |       |
| data             | varchar(255)     | YES  |     | NULL    |       |
| date_created     | int(11)          | YES  |     | NULL    |       |
| created_by       | int(11)          | NO   | PRI | 0       |       |
| last_updated     | int(11)          | YES  |     | NULL    |       |
| updated_by       | int(11) unsigned | YES  |     | NULL    |       |
+------------------+------------------+------+-----+---------+-------+
9 rows in set (0.001 sec)

MariaDB [ictfax]> desc config;
+--------------+------------------+------+-----+---------+----------------+
| Field        | Type             | Null | Key | Default | Extra          |
+--------------+------------------+------+-----+---------+----------------+
| config_id    | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| file_name    | varchar(64)      | NO   |     |         |                |
| file_path    | varchar(255)     | NO   |     |         |                |
| source       | varchar(255)     | YES  |     | NULL    |                |
| version      | int(11)          | YES  |     | 0       |                |
| gateway_flag | int(11) unsigned | YES  |     | NULL    |                |
| date_created | int(11)          | YES  |     | NULL    |                |
| created_by   | int(11) unsigned | YES  |     | NULL    |                |
| last_updated | int(11)          | YES  |     | NULL    |                |
| updated_by   | int(11) unsigned | YES  |     | NULL    |                |
+--------------+------------------+------+-----+---------+----------------+
10 rows in set (0.001 sec)

MariaDB [ictfax]> desc config_data;
+----------------+------------------+------+-----+---------+----------------+
| Field          | Type             | Null | Key | Default | Extra          |
+----------------+------------------+------+-----+---------+----------------+
| config_data_id | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| data           | text             | YES  |     | NULL    |                |
| description    | varchar(128)     | NO   |     |         |                |
| group_name     | varchar(64)      | NO   |     |         |                |
| group_child    | varchar(64)      | NO   |     |         |                |
| file_name      | varchar(64)      | NO   |     |         |                |
| node_id        | int(11) unsigned | YES  |     | 0       |                |
| gateway_flag   | int(11) unsigned | YES  |     | NULL    |                |
| date_created   | int(11)          | YES  |     | NULL    |                |
| created_by     | int(11) unsigned | YES  |     | NULL    |                |
| last_updated   | int(11)          | YES  |     | NULL    |                |
| updated_by     | int(11) unsigned | YES  |     | NULL    |                |
+----------------+------------------+------+-----+---------+----------------+
12 rows in set (0.001 sec)

MariaDB [ictfax]> desc config_node;
+--------------+------------------+------+-----+---------+-------+
| Field        | Type             | Null | Key | Default | Extra |
+--------------+------------------+------+-----+---------+-------+
| config_id    | int(11) unsigned | NO   |     | NULL    |       |
| node_id      | int(11) unsigned | NO   |     | NULL    |       |
| version      | int(11)          | NO   |     | 0       |       |
| date_created | int(11)          | YES  |     | NULL    |       |
| last_updated | int(11)          | YES  |     | NULL    |       |
+--------------+------------------+------+-----+---------+-------+
5 rows in set (0.001 sec)

MariaDB [ictfax]> desc node;
+--------------+------------------+------+-----+---------+----------------+
| Field        | Type             | Null | Key | Default | Extra          |
+--------------+------------------+------+-----+---------+----------------+
| node_id      | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| name         | varchar(64)      | NO   |     |         |                |
| api_host     | varchar(32)      | NO   |     |         |                |
| api_port     | varchar(16)      | NO   |     |         |                |
| api_user     | varchar(32)      | NO   |     |         |                |
| api_pass     | varchar(32)      | NO   |     |         |                |
| ssh_host     | varchar(32)      | NO   |     |         |                |
| ssh_port     | varchar(16)      | NO   |     |         |                |
| ssh_user     | varchar(32)      | NO   |     |         |                |
| ssh_pass     | varchar(32)      | NO   |     |         |                |
| gateway_flag | varchar(16)      | YES  |     | NULL    |                |
| channel      | int(11)          | NO   |     | 0       |                |
| cps          | int(11)          | NO   |     | 1       |                |
| server_flag  | int(11) unsigned | YES  |     | NULL    |                |
| active       | int(11)          | NO   |     | 1       |                |
| weight       | int(11)          | NO   |     | 0       |                |
+--------------+------------------+------+-----+---------+----------------+
16 rows in set (0.001 sec)

MariaDB [ictfax]> desc transmission;
+-----------------+------------------+------+-----+---------+----------------+
| Field           | Type             | Null | Key | Default | Extra          |
+-----------------+------------------+------+-----+---------+----------------+
| transmission_id | int(11)          | NO   | PRI | NULL    | auto_increment |
| title           | varchar(255)     | YES  |     | NULL    |                |
| service_flag    | int(11) unsigned | YES  |     | NULL    |                |
| account_id      | int(11)          | YES  |     | NULL    |                |
| contact_id      | int(11)          | YES  |     | NULL    |                |
| program_id      | int(11) unsigned | YES  |     | NULL    |                |
| origin          | varchar(128)     | YES  |     | NULL    |                |
| direction       | varchar(128)     | YES  |     | NULL    |                |
| status          | varchar(128)     | YES  |     | NULL    |                |
| response        | varchar(255)     | NO   |     |         |                |
| try_allowed     | int(2)           | NO   |     | 1       |                |
| try_done        | int(2)           | NO   |     | 0       |                |
| last_run        | int(11)          | YES  |     | NULL    |                |
| is_deleted      | int(1)           | NO   |     | 0       |                |
| campaign_id     | int(11)          | YES  |     | NULL    |                |
| date_created    | int(11)          | YES  |     | NULL    |                |
| created_by      | int(11) unsigned | YES  |     | NULL    |                |
| last_updated    | int(11)          | YES  |     | NULL    |                |
| updated_by      | int(11) unsigned | YES  |     | NULL    |                |
+-----------------+------------------+------+-----+---------+----------------+
19 rows in set (0.001 sec)

MariaDB [ictfax]> 
MariaDB [ictfax]> 
MariaDB [ictfax]> 
MariaDB [ictfax]> 
MariaDB [ictfax]> desc campaign;
+---------------+------------------+------+-----+---------+-------+
| Field         | Type             | Null | Key | Default | Extra |
+---------------+------------------+------+-----+---------+-------+
| campaign_id   | int(11)          | NO   | PRI | 0       |       |
| program_id    | int(11)          | NO   |     | NULL    |       |
| group_id      | int(11)          | NO   |     | NULL    |       |
| account_id    | int(11)          | YES  |     | NULL    |       |
| cpm           | int(11)          | NO   |     | 2       |       |
| try_allowed   | int(11)          | NO   |     | 1       |       |
| contact_total | int(11)          | NO   |     | 0       |       |
| contact_done  | int(11)          | NO   |     | 0       |       |
| status        | varchar(128)     | NO   |     |         |       |
| source        | varchar(128)     | NO   |     |         |       |
| pid           | varchar(128)     | NO   |     |         |       |
| last_run      | int(11)          | YES  |     | NULL    |       |
| date_created  | int(11)          | YES  |     | NULL    |       |
| created_by    | int(11)          | YES  | MUL | NULL    |       |
| last_updated  | int(11)          | YES  |     | NULL    |       |
| updated_by    | int(11) unsigned | YES  |     | NULL    |       |
+---------------+------------------+------+-----+---------+-------+
16 rows in set (0.001 sec)

MariaDB [ictfax]> desc spool;
+-----------------+------------------+------+-----+---------+----------------+
| Field           | Type             | Null | Key | Default | Extra          |
+-----------------+------------------+------+-----+---------+----------------+
| spool_id        | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| time_spool      | int(11)          | YES  |     | NULL    |                |
| time_start      | int(11)          | YES  |     | NULL    |                |
| time_connect    | int(11)          | YES  |     | NULL    |                |
| time_end        | int(11)          | YES  | MUL | NULL    |                |
| call_id         | varchar(80)      | NO   |     |         |                |
| status          | varchar(80)      | NO   |     |         |                |
| response        | varchar(80)      | NO   |     |         |                |
| amount          | int(11)          | NO   |     | 0       |                |
| service_flag    | int(11) unsigned | YES  |     | NULL    |                |
| transmission_id | int(11) unsigned | YES  | MUL | NULL    |                |
| provider_id     | int(11) unsigned | YES  |     | NULL    |                |
| node_id         | int(11) unsigned | YES  |     | NULL    |                |
| account_id      | int(11)          | YES  | MUL | NULL    |                |
+-----------------+------------------+------+-----+---------+----------------+
14 rows in set (0.001 sec)

MariaDB [ictfax]> desc contact;
+--------------+------------------+------+-----+---------+----------------+
| Field        | Type             | Null | Key | Default | Extra          |
+--------------+------------------+------+-----+---------+----------------+
| contact_id   | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| first_name   | varchar(64)      | YES  |     | NULL    |                |
| last_name    | varchar(64)      | YES  |     | NULL    |                |
| phone        | varchar(32)      | YES  |     | NULL    |                |
| email        | varchar(64)      | YES  |     | NULL    |                |
| address      | varchar(128)     | YES  |     | NULL    |                |
| custom1      | varchar(128)     | YES  |     | NULL    |                |
| custom2      | varchar(128)     | YES  |     | NULL    |                |
| custom3      | varchar(128)     | YES  |     | NULL    |                |
| description  | varchar(255)     | YES  |     | NULL    |                |
| date_created | int(11)          | YES  |     | NULL    |                |
| created_by   | int(11)          | YES  | MUL | NULL    |                |
| last_updated | int(11)          | YES  |     | NULL    |                |
| updated_by   | int(11) unsigned | YES  |     | NULL    |                |
+--------------+------------------+------+-----+---------+----------------+
14 rows in set (0.001 sec)

MariaDB [ictfax]> desc contact_group;
+---------------+------------------+------+-----+---------+----------------+
| Field         | Type             | Null | Key | Default | Extra          |
+---------------+------------------+------+-----+---------+----------------+
| group_id      | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| name          | varchar(128)     | NO   |     | NULL    |                |
| description   | varchar(255)     | NO   |     |         |                |
| contact_total | int(11)          | NO   |     | 0       |                |
| date_created  | int(11)          | YES  |     | NULL    |                |
| created_by    | int(11)          | YES  |     | NULL    |                |
| last_updated  | int(11)          | YES  |     | NULL    |                |
| updated_by    | int(11) unsigned | YES  |     | NULL    |                |
+---------------+------------------+------+-----+---------+----------------+
8 rows in set (0.001 sec)

MariaDB [ictfax]> desc program;
+--------------+------------------+------+-----+---------+----------------+
| Field        | Type             | Null | Key | Default | Extra          |
+--------------+------------------+------+-----+---------+----------------+
| program_id   | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| name         | varchar(64)      | NO   |     |         |                |
| type         | varchar(64)      | NO   |     |         |                |
| data         | text             | YES  |     | NULL    |                |
| parent_id    | int(11) unsigned | YES  |     | NULL    |                |
| date_created | int(11)          | YES  |     | NULL    |                |
| created_by   | int(11)          | YES  |     | NULL    |                |
| last_updated | int(11)          | YES  |     | NULL    |                |
| updated_by   | int(11) unsigned | YES  |     | NULL    |                |
+--------------+------------------+------+-----+---------+----------------+
9 rows in set (0.001 sec)

MariaDB [ictfax]> descapplication;
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'descapplication' at line 1
MariaDB [ictfax]> desc application;
+----------------+------------------+------+-----+---------+----------------+
| Field          | Type             | Null | Key | Default | Extra          |
+----------------+------------------+------+-----+---------+----------------+
| application_id | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| name           | varchar(64)      | NO   |     |         |                |
| type           | varchar(64)      | NO   |     |         |                |
| data           | text             | YES  |     | NULL    |                |
| weight         | int(4)           | NO   |     | 0       |                |
| program_id     | int(11) unsigned | YES  | MUL | NULL    |                |
+----------------+------------------+------+-----+---------+----------------+
6 rows in set (0.001 sec)

MariaDB [ictfax]> desc  action;
+----------------+------------------+------+-----+---------+----------------+
| Field          | Type             | Null | Key | Default | Extra          |
+----------------+------------------+------+-----+---------+----------------+
| action_id      | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| type           | varchar(128)     | NO   |     |         |                |
| action         | int(11)          | NO   |     | 0       |                |
| data           | varchar(64)      | NO   |     |         |                |
| weight         | int(4)           | NO   |     | 0       |                |
| is_default     | int(1)           | YES  |     | 0       |                |
| application_id | int(11) unsigned | YES  | MUL | NULL    |                |
+----------------+------------------+------+-----+---------+----------------+
7 rows in set (0.001 sec)

MariaDB [ictfax]> desc provider;
+--------------+------------------+------+-----+---------+----------------+
| Field        | Type             | Null | Key | Default | Extra          |
+--------------+------------------+------+-----+---------+----------------+
| provider_id  | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
| name         | varchar(128)     | NO   |     |         |                |
| service_flag | int(11) unsigned | YES  |     | NULL    |                |
| node_id      | int(11)          | YES  |     | NULL    |                |
| host         | varchar(128)     | NO   |     |         |                |
| port         | int(6)           | NO   |     | 5060    |                |
| username     | varchar(128)     | NO   |     |         |                |
| password     | varchar(128)     | NO   |     |         |                |
| dialstring   | varchar(255)     | NO   |     |         |                |
| prefix       | varchar(255)     | NO   |     |         |                |
| settings     | text             | YES  |     | NULL    |                |
| description  | varchar(255)     | NO   |     |         |                |
| register     | varchar(255)     | YES  |     | NULL    |                |
| weight       | int(11)          | YES  |     | 0       |                |
| type         | varchar(32)      | YES  |     | NULL    |                |
| active       | int(1)           | NO   |     | 1       |                |
| date_created | int(11)          | YES  |     | NULL    |                |
| created_by   | int(11)          | YES  | MUL | NULL    |                |
| last_updated | int(11)          | YES  |     | NULL    |                |
| updated_by   | int(11) unsigned | YES  |     | NULL    |                |
+--------------+------------------+------+-----+---------+----------------+
20 rows in set (0.001 sec)

