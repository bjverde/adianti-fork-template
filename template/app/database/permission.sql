CREATE TABLE system_group (
    id INTEGER PRIMARY KEY NOT NULL,
    name varchar(100));

CREATE TABLE system_program (
    id INTEGER PRIMARY KEY NOT NULL,
    name varchar(100),
    controller varchar(100));

CREATE TABLE system_unit (
    id INTEGER PRIMARY KEY NOT NULL,
    name varchar(100),
    connection_name varchar(100));

CREATE TABLE system_preference (
    id text,
    value text
);

CREATE TABLE system_user (
    id INTEGER PRIMARY KEY NOT NULL,
    name varchar(100),
    login varchar(100),
    password varchar(255),
    email varchar(100),
    accepted_term_policy char(1),
    phone TEXT,
    address TEXT,
    function_name TEXT,
    about TEXT,
    accepted_term_policy_at TEXT,
    accepted_term_policy_data TEXT,
    frontpage_id int, system_unit_id int references system_unit(id), active char(1),
    FOREIGN KEY(frontpage_id) REFERENCES system_program(id));
    
CREATE TABLE system_user_unit (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    system_unit_id int,
    FOREIGN KEY(system_user_id) REFERENCES system_user(id),
    FOREIGN KEY(system_unit_id) REFERENCES system_unit(id));

CREATE TABLE system_user_group (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    system_group_id int,
    FOREIGN KEY(system_user_id) REFERENCES system_user(id),
    FOREIGN KEY(system_group_id) REFERENCES system_group(id));
    
CREATE TABLE system_group_program (
    id INTEGER PRIMARY KEY NOT NULL,
    system_group_id int,
    system_program_id int,
    FOREIGN KEY(system_group_id) REFERENCES system_group(id),
    FOREIGN KEY(system_program_id) REFERENCES system_program(id));
    
CREATE TABLE system_user_program (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    system_program_id int,
    FOREIGN KEY(system_user_id) REFERENCES system_user(id),
    FOREIGN KEY(system_program_id) REFERENCES system_program(id));
        
CREATE TABLE system_user_old_password (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    password TEXT,
    created_at timestamp,
    FOREIGN KEY(system_user_id) REFERENCES system_user(id));

INSERT INTO system_group VALUES(1,'Admin');
INSERT INTO system_group VALUES(2,'Standard');

INSERT INTO system_program VALUES(1,'System Group Form','SystemGroupForm');
INSERT INTO system_program VALUES(2,'System Group List','SystemGroupList');
INSERT INTO system_program VALUES(3,'System Program Form','SystemProgramForm');
INSERT INTO system_program VALUES(4,'System Program List','SystemProgramList');
INSERT INTO system_program VALUES(5,'System User Form','SystemUserForm');
INSERT INTO system_program VALUES(6,'System User List','SystemUserList');
INSERT INTO system_program VALUES(7,'Common Page','CommonPage');
INSERT INTO system_program VALUES(8,'System PHP Info','SystemPHPInfoView');
INSERT INTO system_program VALUES(9,'System ChangeLog View','SystemChangeLogView');
INSERT INTO system_program VALUES(10,'Welcome View','WelcomeView');
INSERT INTO system_program VALUES(11,'System Sql Log','SystemSqlLogList');
INSERT INTO system_program VALUES(12,'System Profile View','SystemProfileView');
INSERT INTO system_program VALUES(13,'System Profile Form','SystemProfileForm');
INSERT INTO system_program VALUES(14,'System SQL Panel','SystemSQLPanel');
INSERT INTO system_program VALUES(15,'System Access Log','SystemAccessLogList');
INSERT INTO system_program VALUES(16,'System Message Form','SystemMessageForm');
INSERT INTO system_program VALUES(17,'System Message List','SystemMessageList');
INSERT INTO system_program VALUES(18,'System Message Form View','SystemMessageFormView');
INSERT INTO system_program VALUES(19,'System Notification List','SystemNotificationList');
INSERT INTO system_program VALUES(20,'System Notification Form View','SystemNotificationFormView');
INSERT INTO system_program VALUES(21,'System Document Category List','SystemDocumentCategoryFormList');
--- INSERT INTO system_program VALUES(22,'System Document Form','SystemDocumentForm');
--- INSERT INTO system_program VALUES(23,'System Document Upload Form','SystemDocumentUploadForm');
--- INSERT INTO system_program VALUES(24,'System Document List','SystemDocumentList');
--- INSERT INTO system_program VALUES(25,'System Shared Document List','SystemSharedDocumentList');
INSERT INTO system_program VALUES(26,'System Unit Form','SystemUnitForm');
INSERT INTO system_program VALUES(27,'System Unit List','SystemUnitList');
INSERT INTO system_program VALUES(28,'System Access stats','SystemAccessLogStats');
INSERT INTO system_program VALUES(29,'System Preference form','SystemPreferenceForm');
INSERT INTO system_program VALUES(30,'System Support form','SystemSupportForm');
INSERT INTO system_program VALUES(31,'System PHP Error','SystemPHPErrorLogView');
INSERT INTO system_program VALUES(32,'System Database Browser','SystemDatabaseExplorer');
INSERT INTO system_program VALUES(33,'System Table List','SystemTableList');
INSERT INTO system_program VALUES(34,'System Data Browser','SystemDataBrowser');
INSERT INTO system_program VALUES(35,'System Menu Editor','SystemMenuEditor');
INSERT INTO system_program VALUES(36,'System Request Log','SystemRequestLogList');
INSERT INTO system_program VALUES(37,'System Request Log View','SystemRequestLogView');
INSERT INTO system_program VALUES(38,'System Administration Dashboard','SystemAdministrationDashboard');
INSERT INTO system_program VALUES(39,'System Log Dashboard','SystemLogDashboard');
INSERT INTO system_program VALUES(40,'System Session dump','SystemSessionDumpView');
INSERT INTO system_program VALUES(41,'System Information','SystemInformationView');
INSERT INTO system_program VALUES(42,'System files diff','SystemFilesDiff');
INSERT INTO system_program VALUES(43,'System Documents','SystemDriveList');
INSERT INTO system_program VALUES(44,'System Folder form','SystemFolderForm');
INSERT INTO system_program VALUES(45,'System Share folder','SystemFolderShareForm');
INSERT INTO system_program VALUES(46,'System Share document','SystemDocumentShareForm');
INSERT INTO system_program VALUES(47,'System Document properties','SystemDocumentFormWindow');
INSERT INTO system_program VALUES(48,'System Folder properties','SystemFolderFormView');
INSERT INTO system_program VALUES(49,'System Document upload','SystemDriveDocumentUploadForm');

--- INSERT INTO system_program VALUES(50,'System Tag form', 'SystemTagForm');
--- INSERT INTO system_program VALUES(51,'System Tag list', 'SystemTagList');
INSERT INTO system_program VALUES(52,'System Post list', 'SystemPostList');
INSERT INTO system_program VALUES(53,'System Post form', 'SystemPostForm');
INSERT INTO system_program VALUES(54,'Post View list', 'SystemPostFeedView');
INSERT INTO system_program VALUES(55,'Post Comment form', 'SystemPostCommentForm');
INSERT INTO system_program VALUES(56,'Post Comment list', 'SystemPostCommentList');
INSERT INTO system_program VALUES(57,'System Contacts list', 'SystemContactsList');
INSERT INTO system_program VALUES(58,'System Wiki list', 'SystemWikiList');
INSERT INTO system_program VALUES(59,'System Wiki form', 'SystemWikiForm');
INSERT INTO system_program VALUES(60,'System Wiki search', 'SystemWikiSearchList');
INSERT INTO system_program VALUES(61,'System Wiki view', 'SystemWikiView');

INSERT INTO system_user VALUES(1,'Administrator','admin','$2y$10$E1t1i2dmr2RgsfOqR.I/LOc7ob6t/2j/ewWAwvRAXLUJYeXbEBmpe','admin@admin.net','Y','+123 456 789','Admin Street, 123','Administrator','I''m the administrator',NULL,NULL,10,NULL,'Y');
INSERT INTO system_user VALUES(2,'User','user','$2y$10$nENQIUe5Ov7CTUHnq/H/cOL5mkeNmGRRp9lvJwT1QP80z4Fg1UN6i','user@user.net','Y','+123 456 789','User Street, 123','End user','I''m the end user',NULL,NULL,7,NULL,'Y');

INSERT INTO system_unit VALUES(1,'Unit A','unit_a');
INSERT INTO system_unit VALUES(2,'Unit B','unit_b');

INSERT INTO system_user_group VALUES(1,1,1);
INSERT INTO system_user_group VALUES(2,2,2);
INSERT INTO system_user_group VALUES(3,1,2);

INSERT INTO system_user_unit VALUES(1,1,1);
INSERT INTO system_user_unit VALUES(2,1,2);
INSERT INTO system_user_unit VALUES(3,2,1);
INSERT INTO system_user_unit VALUES(4,2,2);

INSERT INTO system_group_program VALUES(1,1,1);
INSERT INTO system_group_program VALUES(2,1,2);
INSERT INTO system_group_program VALUES(3,1,3);
INSERT INTO system_group_program VALUES(4,1,4);
INSERT INTO system_group_program VALUES(5,1,5);
INSERT INTO system_group_program VALUES(6,1,6);
INSERT INTO system_group_program VALUES(7,1,8);
INSERT INTO system_group_program VALUES(8,1,9);
INSERT INTO system_group_program VALUES(9,1,11);
INSERT INTO system_group_program VALUES(10,1,14);
INSERT INTO system_group_program VALUES(11,1,15);
INSERT INTO system_group_program VALUES(12,2,10);
INSERT INTO system_group_program VALUES(13,2,12);
INSERT INTO system_group_program VALUES(14,2,13);
INSERT INTO system_group_program VALUES(15,2,16);
INSERT INTO system_group_program VALUES(16,2,17);
INSERT INTO system_group_program VALUES(17,2,18);
INSERT INTO system_group_program VALUES(18,2,19);
INSERT INTO system_group_program VALUES(19,2,20);
INSERT INTO system_group_program VALUES(20,1,21);
--- INSERT INTO system_group_program VALUES(21,2,22);
--- INSERT INTO system_group_program VALUES(22,2,23);
--- INSERT INTO system_group_program VALUES(23,2,24);
--- INSERT INTO system_group_program VALUES(24,2,25);
INSERT INTO system_group_program VALUES(25,1,26);
INSERT INTO system_group_program VALUES(26,1,27);
INSERT INTO system_group_program VALUES(27,1,28);
INSERT INTO system_group_program VALUES(28,1,29);
INSERT INTO system_group_program VALUES(29,2,30);
INSERT INTO system_group_program VALUES(30,1,31);
INSERT INTO system_group_program VALUES(31,1,32);
INSERT INTO system_group_program VALUES(32,1,33);
INSERT INTO system_group_program VALUES(33,1,34);
INSERT INTO system_group_program VALUES(34,1,35);
INSERT INTO system_group_program VALUES(36,1,36);
INSERT INTO system_group_program VALUES(37,1,37);
INSERT INTO system_group_program VALUES(38,1,38);
INSERT INTO system_group_program VALUES(39,1,39);
INSERT INTO system_group_program VALUES(40,1,40);
INSERT INTO system_group_program VALUES(41,1,41);
INSERT INTO system_group_program VALUES(42,1,42);
INSERT INTO system_group_program VALUES(43,1,43);
INSERT INTO system_group_program VALUES(44,1,44);
INSERT INTO system_group_program VALUES(45,1,45);
INSERT INTO system_group_program VALUES(46,1,46);
INSERT INTO system_group_program VALUES(47,1,47);
INSERT INTO system_group_program VALUES(48,1,48);
INSERT INTO system_group_program VALUES(49,1,49);

--- INSERT INTO system_group_program VALUES(50,1,50);
--- INSERT INTO system_group_program VALUES(51,1,51);
INSERT INTO system_group_program VALUES(52,1,52);
INSERT INTO system_group_program VALUES(53,1,53);
INSERT INTO system_group_program VALUES(54,1,54);
INSERT INTO system_group_program VALUES(55,1,55);
INSERT INTO system_group_program VALUES(56,1,56);
INSERT INTO system_group_program VALUES(57,1,57);
INSERT INTO system_group_program VALUES(58,1,58);
INSERT INTO system_group_program VALUES(59,1,59);
INSERT INTO system_group_program VALUES(60,1,60);
INSERT INTO system_group_program VALUES(61,1,61);

INSERT INTO system_group_program VALUES(62,2,54);
INSERT INTO system_group_program VALUES(63,2,60);

INSERT INTO system_group_program VALUES(64,2,43);
INSERT INTO system_group_program VALUES(65,2,44);
INSERT INTO system_group_program VALUES(66,2,45);
INSERT INTO system_group_program VALUES(67,2,46);
INSERT INTO system_group_program VALUES(68,2,47);
INSERT INTO system_group_program VALUES(69,2,48);
INSERT INTO system_group_program VALUES(70,2,49);
INSERT INTO system_group_program VALUES(71,2,55);
INSERT INTO system_group_program VALUES(72,2,56);
INSERT INTO system_group_program VALUES(73,2,61);
                                        
INSERT INTO system_user_program VALUES(1,2,7);

CREATE INDEX sys_user_program_idx ON system_user(frontpage_id);
CREATE INDEX sys_user_group_group_idx ON system_user_group(system_group_id);
CREATE INDEX sys_user_group_user_idx ON system_user_group(system_user_id);
CREATE INDEX sys_group_program_program_idx ON system_group_program(system_program_id);
CREATE INDEX sys_group_program_group_idx ON system_group_program(system_group_id);
CREATE INDEX sys_user_program_program_idx ON system_user_program(system_program_id);
CREATE INDEX sys_user_program_user_idx ON system_user_program(system_user_id);
