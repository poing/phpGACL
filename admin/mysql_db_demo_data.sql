CREATE TABLE acl_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;

INSERT INTO acl_seq VALUES (11);

CREATE TABLE aco_sections_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;

INSERT INTO aco_sections_seq VALUES (11);

CREATE TABLE aco_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;

INSERT INTO aco_seq VALUES (17);

CREATE TABLE aro_sections_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;

INSERT INTO aro_sections_seq VALUES (12);

CREATE TABLE aro_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;

INSERT INTO aro_seq VALUES (24);

CREATE TABLE groups_id_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;

INSERT INTO groups_id_seq VALUES (18);

CREATE TABLE groups_path_id_seq (
  id int(11) NOT NULL default '0'
) TYPE=MyISAM;

INSERT INTO groups_path_id_seq VALUES (15);


INSERT INTO acl VALUES (11,1,1,1028506045);

INSERT INTO aco VALUES (10,10,'10',10,'Enable - Login');
INSERT INTO aco VALUES (11,10,'20',20,'Email forgotten Password');
INSERT INTO aco VALUES (12,11,'10',10,'Enable - Projects');
INSERT INTO aco VALUES (13,11,'20',20,'View - Projects');
INSERT INTO aco VALUES (14,11,'30',30,'View Own - Projects');
INSERT INTO aco VALUES (15,11,'40',40,'Edit - Projects');
INSERT INTO aco VALUES (16,11,'50',50,'Edit Own - Projects');
INSERT INTO aco VALUES (17,11,'60',60,'Add - Projects');

INSERT INTO aco_sections VALUES (10,'10',10,'System');
INSERT INTO aco_sections VALUES (11,'20',20,'Projects');

INSERT INTO aro VALUES (10,10,'10',10,'John Doe');
INSERT INTO aro VALUES (11,10,'20',20,'Jane Doe');
INSERT INTO aro VALUES (12,10,'30',30,'Betty Doe');
INSERT INTO aro VALUES (13,10,'40',40,'Doe Doe');
INSERT INTO aro VALUES (14,11,'10',10,'Internet Explorer v5');
INSERT INTO aro VALUES (15,11,'20',20,'Internet Explorder v6');
INSERT INTO aro VALUES (16,11,'30',30,'Netscape v4');
INSERT INTO aro VALUES (17,11,'40',40,'Netscape v6');
INSERT INTO aro VALUES (18,11,'50',50,'Opera');
INSERT INTO aro VALUES (19,11,'60',60,'Mozilla');
INSERT INTO aro VALUES (20,11,'70',70,'Konqueror');
INSERT INTO aro VALUES (21,10,'100',100,'John Smith');
INSERT INTO aro VALUES (22,10,'110',110,'Jane Smith');
INSERT INTO aro VALUES (23,10,'120',120,'Betty Smith');
INSERT INTO aro VALUES (24,10,'130',130,'Doe Smith');
		
INSERT INTO aro_sections VALUES (10,'10',10,'Users');
INSERT INTO aro_sections VALUES (11,'20',20,'Browsers');
INSERT INTO aro_sections VALUES (12,'30',30,'IP Addresses');

INSERT INTO aro_sections_seq VALUES (12);

INSERT INTO aro_seq VALUES (24);

INSERT INTO groups VALUES (10,0,'Root');

INSERT INTO groups VALUES (11,13,'Windows Only Browsers');
INSERT INTO groups VALUES (12,13,'*nix Only Browsers');
INSERT INTO groups VALUES (13,10,'Browsers');
INSERT INTO groups VALUES (14,13,'Cross-Platform Browsers');
INSERT INTO groups VALUES (15,10,'Users');
INSERT INTO groups VALUES (16,15,'Family\'s');
INSERT INTO groups VALUES (17,16,'Doe Family');
INSERT INTO groups VALUES (18,16,'Smith Family');

INSERT INTO aco_map VALUES (11, 10);

INSERT INTO groups_aro_map VALUES (11,14);
INSERT INTO groups_aro_map VALUES (11,15);
INSERT INTO groups_aro_map VALUES (12,20);
INSERT INTO groups_aro_map VALUES (14,16);
INSERT INTO groups_aro_map VALUES (14,17);
INSERT INTO groups_aro_map VALUES (14,18);
INSERT INTO groups_aro_map VALUES (14,19);
INSERT INTO groups_aro_map VALUES (17,10);
INSERT INTO groups_aro_map VALUES (17,11);
INSERT INTO groups_aro_map VALUES (17,12);
INSERT INTO groups_aro_map VALUES (17,13);
INSERT INTO groups_aro_map VALUES (18,21);
INSERT INTO groups_aro_map VALUES (18,22);
INSERT INTO groups_aro_map VALUES (18,23);
INSERT INTO groups_aro_map VALUES (18,24);

INSERT INTO groups_map VALUES (11,14);

INSERT INTO groups_path VALUES (10,0,0);
INSERT INTO groups_path VALUES (11,10,0);
INSERT INTO groups_path VALUES (11,0,1);
INSERT INTO groups_path VALUES (12,11,0);
INSERT INTO groups_path VALUES (12,10,1);
INSERT INTO groups_path VALUES (12,0,2);
INSERT INTO groups_path VALUES (13,13,0);
INSERT INTO groups_path VALUES (13,10,1);
INSERT INTO groups_path VALUES (13,0,2);
INSERT INTO groups_path VALUES (14,15,0);
INSERT INTO groups_path VALUES (14,10,1);
INSERT INTO groups_path VALUES (14,0,2);
INSERT INTO groups_path VALUES (15,16,0);
INSERT INTO groups_path VALUES (15,15,1);
INSERT INTO groups_path VALUES (15,10,2);
INSERT INTO groups_path VALUES (15,0,3);

INSERT INTO groups_path_map VALUES (10,10);
INSERT INTO groups_path_map VALUES (11,13);
INSERT INTO groups_path_map VALUES (11,15);
INSERT INTO groups_path_map VALUES (13,11);
INSERT INTO groups_path_map VALUES (13,12);
INSERT INTO groups_path_map VALUES (13,14);
INSERT INTO groups_path_map VALUES (14,16);
INSERT INTO groups_path_map VALUES (15,17);
INSERT INTO groups_path_map VALUES (15,18);
