-- MySQL dump 8.22
--
-- Host: mysql    Database: phpgacl
---------------------------------------------------------
-- Server version	3.23.54

--
-- Table structure for table 'acl'
--

DROP TABLE IF EXISTS acl;
CREATE TABLE acl (
  id int(12) NOT NULL default '0',
  allow smallint(1) NOT NULL default '0',
  enabled smallint(1) NOT NULL default '0',
  return_value text,
  note text,
  updated_date int(12) NOT NULL default '0',
  UNIQUE KEY id (id),
  KEY enabled (enabled)
) TYPE=MyISAM;

--
-- Dumping data for table 'acl'
--


INSERT INTO acl VALUES (11,1,1,NULL,NULL,1038717553);
INSERT INTO acl VALUES (12,1,1,NULL,NULL,1038718017);
INSERT INTO acl VALUES (13,1,1,NULL,NULL,1038718036);
INSERT INTO acl VALUES (14,0,1,NULL,NULL,1038732445);
INSERT INTO acl VALUES (15,1,1,NULL,NULL,1038732500);
INSERT INTO acl VALUES (29,1,1,'15','',1044039073);

--
-- Table structure for table 'acl_seq'
--

DROP TABLE IF EXISTS acl_seq;
CREATE TABLE acl_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table 'acl_seq'
--


INSERT INTO acl_seq VALUES (29);

--
-- Table structure for table 'aco'
--

DROP TABLE IF EXISTS aco;
CREATE TABLE aco (
  id int(12) NOT NULL default '0',
  section_value varchar(230) NOT NULL default '0',
  value varchar(230) NOT NULL default '',
  order_value int(10) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  hidden smallint(1) NOT NULL default '0',
  UNIQUE KEY id (id),
  UNIQUE KEY value (section_value,value),
  KEY hidden (hidden)
) TYPE=MyISAM;

--
-- Dumping data for table 'aco'
--


INSERT INTO aco VALUES (10,'system','login',1,'Enable - Login',0);
INSERT INTO aco VALUES (11,'system','email_pw',2,'Email Forgotten PW',0);
INSERT INTO aco VALUES (12,'projects','add',1,'Add',0);
INSERT INTO aco VALUES (13,'projects','view',2,'View',0);
INSERT INTO aco VALUES (14,'projects','edit',3,'Edit',0);
INSERT INTO aco VALUES (15,'projects','del',4,'Delete',0);
INSERT INTO aco VALUES (34,'projects','add_limit',5,'Add Limit',0);

--
-- Table structure for table 'aco_map'
--

DROP TABLE IF EXISTS aco_map;
CREATE TABLE aco_map (
  acl_id int(12) NOT NULL default '0',
  section_value varchar(230) NOT NULL default '0',
  value varchar(230) NOT NULL default '0',
  KEY acl_id (acl_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'aco_map'
--


INSERT INTO aco_map VALUES (11,'system','login');
INSERT INTO aco_map VALUES (11,'system','email_pw');
INSERT INTO aco_map VALUES (12,'projects','view');
INSERT INTO aco_map VALUES (12,'projects','add');
INSERT INTO aco_map VALUES (13,'projects','edit');
INSERT INTO aco_map VALUES (13,'projects','del');
INSERT INTO aco_map VALUES (14,'projects','add');
INSERT INTO aco_map VALUES (15,'projects','edit');
INSERT INTO aco_map VALUES (29,'projects','add_limit');

--
-- Table structure for table 'aco_sections'
--

DROP TABLE IF EXISTS aco_sections;
CREATE TABLE aco_sections (
  id int(12) NOT NULL default '0',
  value varchar(230) NOT NULL default '',
  order_value int(10) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  hidden smallint(1) NOT NULL default '0',
  UNIQUE KEY id (id),
  UNIQUE KEY value (value),
  KEY hidden (hidden)
) TYPE=MyISAM;

--
-- Dumping data for table 'aco_sections'
--


INSERT INTO aco_sections VALUES (10,'system',1,'System',0);
INSERT INTO aco_sections VALUES (11,'projects',2,'Projects',0);

--
-- Table structure for table 'aco_sections_seq'
--

DROP TABLE IF EXISTS aco_sections_seq;
CREATE TABLE aco_sections_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table 'aco_sections_seq'
--


INSERT INTO aco_sections_seq VALUES (15);

--
-- Table structure for table 'aco_seq'
--

DROP TABLE IF EXISTS aco_seq;
CREATE TABLE aco_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table 'aco_seq'
--


INSERT INTO aco_seq VALUES (49);

--
-- Table structure for table 'aro'
--

DROP TABLE IF EXISTS aro;
CREATE TABLE aro (
  id int(12) NOT NULL default '0',
  section_value varchar(230) NOT NULL default '0',
  value varchar(230) NOT NULL default '',
  order_value int(10) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  hidden smallint(1) NOT NULL default '0',
  UNIQUE KEY id (id),
  UNIQUE KEY value (section_value,value),
  KEY hidden (hidden)
) TYPE=MyISAM;

--
-- Dumping data for table 'aro'
--


INSERT INTO aro VALUES (10,'users','1',1,'John Doe',0);
INSERT INTO aro VALUES (11,'users','2',2,'Jane Doe',0);
INSERT INTO aro VALUES (12,'users','3',3,'John Smith',0);
INSERT INTO aro VALUES (13,'users','4',4,'Jane Smith',0);
INSERT INTO aro VALUES (14,'browsers','ie4',1,'Internet Explorer v4',0);
INSERT INTO aro VALUES (15,'browsers','ie5',2,'Internet Explorer v5',0);
INSERT INTO aro VALUES (16,'browsers','ie6',3,'Internet Explorer v6',0);
INSERT INTO aro VALUES (17,'browsers','ns4',4,'Netscape v4',0);
INSERT INTO aro VALUES (18,'browsers','ns6',5,'Netscape v6',0);
INSERT INTO aro VALUES (19,'browsers','moz',6,'Mozilla',0);
INSERT INTO aro VALUES (20,'browsers','opera',7,'Opera',0);
INSERT INTO aro VALUES (21,'browsers','koq',8,'Konqueror',0);
INSERT INTO aro VALUES (22,'ip_address','10.10.0.1',1,'10.10.0.1',0);
INSERT INTO aro VALUES (23,'ip_address','10.10.0.2',2,'10.10.0.2',0);

--
-- Table structure for table 'aro_groups'
--

DROP TABLE IF EXISTS aro_groups;
CREATE TABLE aro_groups (
  id int(12) NOT NULL default '0',
  parent_id int(12) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY parent_id (parent_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'aro_groups'
--


INSERT INTO aro_groups VALUES (10,0,'Root');
INSERT INTO aro_groups VALUES (15,14,'Doe Family');
INSERT INTO aro_groups VALUES (12,10,'Browsers');
INSERT INTO aro_groups VALUES (14,10,'Families');
INSERT INTO aro_groups VALUES (16,14,'Smith Family');
INSERT INTO aro_groups VALUES (17,12,'Windows Browsers');
INSERT INTO aro_groups VALUES (18,12,'*nix Browsers');
INSERT INTO aro_groups VALUES (19,12,'Cross-Platform browsers');

--
-- Table structure for table 'aro_groups_map'
--

DROP TABLE IF EXISTS aro_groups_map;
CREATE TABLE aro_groups_map (
  acl_id int(12) NOT NULL default '0',
  group_id int(12) NOT NULL default '0',
  PRIMARY KEY  (acl_id,group_id),
  KEY acl_id (acl_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'aro_groups_map'
--


INSERT INTO aro_groups_map VALUES (11,14);
INSERT INTO aro_groups_map VALUES (12,14);
INSERT INTO aro_groups_map VALUES (15,16);

--
-- Table structure for table 'aro_groups_path'
--

DROP TABLE IF EXISTS aro_groups_path;
CREATE TABLE aro_groups_path (
  id int(12) NOT NULL default '0',
  group_id int(12) NOT NULL default '0',
  tree_level int(12) NOT NULL default '0',
  PRIMARY KEY  (id,tree_level),
  KEY group_id (group_id,tree_level)
) TYPE=MyISAM;

--
-- Dumping data for table 'aro_groups_path'
--


INSERT INTO aro_groups_path VALUES (10,0,0);
INSERT INTO aro_groups_path VALUES (11,10,0);
INSERT INTO aro_groups_path VALUES (11,0,1);
INSERT INTO aro_groups_path VALUES (12,11,0);
INSERT INTO aro_groups_path VALUES (12,10,1);
INSERT INTO aro_groups_path VALUES (12,0,2);
INSERT INTO aro_groups_path VALUES (13,14,0);
INSERT INTO aro_groups_path VALUES (13,10,1);
INSERT INTO aro_groups_path VALUES (13,0,2);
INSERT INTO aro_groups_path VALUES (14,12,0);
INSERT INTO aro_groups_path VALUES (14,10,1);
INSERT INTO aro_groups_path VALUES (14,0,2);

--
-- Table structure for table 'aro_groups_path_map'
--

DROP TABLE IF EXISTS aro_groups_path_map;
CREATE TABLE aro_groups_path_map (
  path_id int(12) NOT NULL default '0',
  group_id int(12) NOT NULL default '0',
  PRIMARY KEY  (path_id,group_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'aro_groups_path_map'
--


INSERT INTO aro_groups_path_map VALUES (10,10);
INSERT INTO aro_groups_path_map VALUES (11,11);
INSERT INTO aro_groups_path_map VALUES (11,12);
INSERT INTO aro_groups_path_map VALUES (11,14);
INSERT INTO aro_groups_path_map VALUES (11,20);
INSERT INTO aro_groups_path_map VALUES (12,13);
INSERT INTO aro_groups_path_map VALUES (13,15);
INSERT INTO aro_groups_path_map VALUES (13,16);
INSERT INTO aro_groups_path_map VALUES (14,17);
INSERT INTO aro_groups_path_map VALUES (14,18);
INSERT INTO aro_groups_path_map VALUES (14,19);

--
-- Table structure for table 'aro_map'
--

DROP TABLE IF EXISTS aro_map;
CREATE TABLE aro_map (
  acl_id int(12) NOT NULL default '0',
  section_value varchar(230) NOT NULL default '0',
  value varchar(230) NOT NULL default '0',
  KEY acl_id (acl_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'aro_map'
--


INSERT INTO aro_map VALUES (13,'users','1');
INSERT INTO aro_map VALUES (13,'users','2');
INSERT INTO aro_map VALUES (14,'users','2');
INSERT INTO aro_map VALUES (29,'users','2');

--
-- Table structure for table 'aro_sections'
--

DROP TABLE IF EXISTS aro_sections;
CREATE TABLE aro_sections (
  id int(12) NOT NULL default '0',
  value varchar(255) NOT NULL default '',
  order_value int(10) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  hidden smallint(1) NOT NULL default '0',
  UNIQUE KEY value (value),
  UNIQUE KEY id (id),
  KEY hidden (hidden)
) TYPE=MyISAM;

--
-- Dumping data for table 'aro_sections'
--


INSERT INTO aro_sections VALUES (10,'users',1,'Users',0);
INSERT INTO aro_sections VALUES (11,'browsers',2,'Browsers',0);
INSERT INTO aro_sections VALUES (12,'ip_address',3,'IP Addresses',0);

--
-- Table structure for table 'axo'
--

DROP TABLE IF EXISTS axo;
CREATE TABLE axo (
  id int(12) NOT NULL default '0',
  section_value varchar(230) NOT NULL default '0',
  value varchar(230) NOT NULL default '',
  order_value int(10) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  hidden smallint(1) NOT NULL default '0',
  UNIQUE KEY id (id),
  UNIQUE KEY value (section_value,value),
  KEY hidden (hidden)
) TYPE=MyISAM;

--
-- Dumping data for table 'axo'
--


INSERT INTO axo VALUES (10,'projects','5598',1,'Accounting (ID: 5598)',0);
INSERT INTO axo VALUES (11,'projects','5599',2,'3D Software (ID: 5599)',0);
INSERT INTO axo VALUES (12,'contacts','8686',1,'Bill Gates (ID: 8686)',0);
INSERT INTO axo VALUES (13,'contacts','8687',2,'Linus Torvalds (ID: 8687)',0);

--
-- Table structure for table 'axo_groups'
--

DROP TABLE IF EXISTS axo_groups;
CREATE TABLE axo_groups (
  id int(12) NOT NULL default '0',
  parent_id int(12) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY parent_id (parent_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'axo_groups'
--


INSERT INTO axo_groups VALUES (20,0,'Root');
INSERT INTO axo_groups VALUES (22,20,'Projects');
INSERT INTO axo_groups VALUES (23,20,'Contacts');

--
-- Table structure for table 'axo_groups_map'
--

DROP TABLE IF EXISTS axo_groups_map;
CREATE TABLE axo_groups_map (
  acl_id int(12) NOT NULL default '0',
  group_id int(12) NOT NULL default '0',
  PRIMARY KEY  (acl_id,group_id),
  KEY acl_id (acl_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'axo_groups_map'
--



--
-- Table structure for table 'axo_groups_path'
--

DROP TABLE IF EXISTS axo_groups_path;
CREATE TABLE axo_groups_path (
  id int(12) NOT NULL default '0',
  group_id int(12) NOT NULL default '0',
  tree_level int(12) NOT NULL default '0',
  PRIMARY KEY  (id,tree_level),
  KEY group_id (group_id,tree_level)
) TYPE=MyISAM;

--
-- Dumping data for table 'axo_groups_path'
--


INSERT INTO axo_groups_path VALUES (10,0,0);
INSERT INTO axo_groups_path VALUES (11,20,0);
INSERT INTO axo_groups_path VALUES (11,0,1);

--
-- Table structure for table 'axo_groups_path_id_seq'
--

DROP TABLE IF EXISTS axo_groups_path_id_seq;
CREATE TABLE axo_groups_path_id_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table 'axo_groups_path_id_seq'
--


INSERT INTO axo_groups_path_id_seq VALUES (11);

--
-- Table structure for table 'axo_groups_path_map'
--

DROP TABLE IF EXISTS axo_groups_path_map;
CREATE TABLE axo_groups_path_map (
  path_id int(12) NOT NULL default '0',
  group_id int(12) NOT NULL default '0',
  PRIMARY KEY  (path_id,group_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'axo_groups_path_map'
--


INSERT INTO axo_groups_path_map VALUES (10,20);
INSERT INTO axo_groups_path_map VALUES (10,21);
INSERT INTO axo_groups_path_map VALUES (11,22);
INSERT INTO axo_groups_path_map VALUES (11,23);

--
-- Table structure for table 'axo_map'
--

DROP TABLE IF EXISTS axo_map;
CREATE TABLE axo_map (
  acl_id int(12) NOT NULL default '0',
  section_value varchar(230) NOT NULL default '0',
  value varchar(230) NOT NULL default '0',
  KEY acl_id (acl_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'axo_map'
--


INSERT INTO axo_map VALUES (15,'projects','5598');

--
-- Table structure for table 'axo_sections'
--

DROP TABLE IF EXISTS axo_sections;
CREATE TABLE axo_sections (
  id int(12) NOT NULL default '0',
  value varchar(255) NOT NULL default '',
  order_value int(10) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  hidden smallint(1) NOT NULL default '0',
  UNIQUE KEY value (value),
  UNIQUE KEY id (id),
  KEY hidden (hidden)
) TYPE=MyISAM;

--
-- Dumping data for table 'axo_sections'
--


INSERT INTO axo_sections VALUES (10,'projects',1,'Projects',0);
INSERT INTO axo_sections VALUES (11,'contacts',2,'Contacts',0);

--
-- Table structure for table 'groups_aro_map'
--

DROP TABLE IF EXISTS groups_aro_map;
CREATE TABLE groups_aro_map (
  group_id int(12) NOT NULL default '0',
  section_value varchar(230) NOT NULL default '0',
  value varchar(230) NOT NULL default '0',
  UNIQUE KEY group_id (group_id,section_value,value)
) TYPE=MyISAM;

--
-- Dumping data for table 'groups_aro_map'
--


INSERT INTO groups_aro_map VALUES (15,'users','1');
INSERT INTO groups_aro_map VALUES (15,'users','2');
INSERT INTO groups_aro_map VALUES (16,'users','3');
INSERT INTO groups_aro_map VALUES (16,'users','4');
INSERT INTO groups_aro_map VALUES (17,'browsers','ie4');
INSERT INTO groups_aro_map VALUES (17,'browsers','ie5');
INSERT INTO groups_aro_map VALUES (17,'browsers','ie6');
INSERT INTO groups_aro_map VALUES (18,'browsers','koq');
INSERT INTO groups_aro_map VALUES (19,'browsers','moz');
INSERT INTO groups_aro_map VALUES (19,'browsers','ns4');
INSERT INTO groups_aro_map VALUES (19,'browsers','ns6');
INSERT INTO groups_aro_map VALUES (19,'browsers','opera');

--
-- Table structure for table 'groups_axo_map'
--

DROP TABLE IF EXISTS groups_axo_map;
CREATE TABLE groups_axo_map (
  group_id int(12) NOT NULL default '0',
  section_value varchar(230) NOT NULL default '0',
  value varchar(230) NOT NULL default '0',
  UNIQUE KEY group_id (group_id,section_value,value)
) TYPE=MyISAM;

--
-- Dumping data for table 'groups_axo_map'
--


INSERT INTO groups_axo_map VALUES (22,'projects','5598');
INSERT INTO groups_axo_map VALUES (22,'projects','5599');
INSERT INTO groups_axo_map VALUES (23,'contacts','8686');
INSERT INTO groups_axo_map VALUES (23,'contacts','8687');

--
-- Table structure for table 'groups_id_seq'
--

DROP TABLE IF EXISTS groups_id_seq;
CREATE TABLE groups_id_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table 'groups_id_seq'
--


INSERT INTO groups_id_seq VALUES (29);

