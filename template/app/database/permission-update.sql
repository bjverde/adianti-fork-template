

--- new programs of 3.0.0
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System PHP Info','SystemPHPInfoView');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System ChangeLog View','SystemChangeLogView');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'Welcome View','WelcomeView');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Sql Log','SystemSqlLogList');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Profile View','SystemProfileView');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Profile Form','SystemProfileForm');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System SQL Panel','SystemSQLPanel');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Access Log','SystemAccessLogList');

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemPHPInfoView'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemChangeLogView'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemSqlLogList'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemSQLPanel'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemAccessLogList'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='WelcomeView'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemProfileView'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemProfileForm'));
UPDATE system_user set frontpage_id = (select id from system_program b where controller='WelcomeView') where id=1;


--- new programs of 4.0
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Message Form','SystemMessageForm');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Message List','SystemMessageList');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Message Form View','SystemMessageFormView');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Notification List','SystemNotificationList');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Notification Form View','SystemNotificationFormView');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Document Form','SystemDocumentForm');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Document Upload Form','SystemDocumentUploadForm');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Document List','SystemDocumentList');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Shared Document List','SystemSharedDocumentList');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Unit Form','SystemUnitForm');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Unit List','SystemUnitList');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Access stats','SystemAccessLogStats');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Preference form','SystemPreferenceForm');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Support form','SystemSupportForm');


INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemMessageForm'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemMessageList'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemMessageFormView'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemNotificationList'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemNotificationFormView'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemDocumentForm'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemDocumentUploadForm'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemDocumentList'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemSharedDocumentList'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemUnitForm'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemUnitList'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemAccessLogStats'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemPreferenceForm'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemSupportForm'));

CREATE TABLE system_unit (
    id int PRIMARY KEY NOT NULL,
    name varchar(100)
);
    
ALTER TABLE system_user add column system_unit_id int REFERENCES system_unit(id);
ALTER TABLE system_user add column active char(1);
UPDATE system_user set active='Y';

CREATE TABLE system_preference (
    id varchar(256),
    value text
);


--- new programs of 5.0
CREATE TABLE system_user_unit (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    system_unit_id int,
    FOREIGN KEY(system_user_id) REFERENCES system_user(id),
    FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)
);

INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System PHP Error','SystemPHPErrorLogView');

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemPHPErrorLogView'));

INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Database Browser','SystemDatabaseExplorer');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Table List','SystemTableList');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Data Browser','SystemDataBrowser');

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemDatabaseExplorer'));
                                        
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemTableList'));
                                        
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemDataBrowser'));
                                        
--- new programs of 7.0
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Menu Editor','SystemMenuEditor');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemMenuEditor'));

INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Request Log','SystemRequestLogList');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemRequestLogList'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Request Log View','SystemRequestLogView');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemRequestLogView'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Administration Dashboard','SystemAdministrationDashboard');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemAdministrationDashboard'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Log Dashboard','SystemLogDashboard');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemLogDashboard'));
                                        
ALTER TABLE system_unit add column connection_name varchar(256);
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Session dump','SystemSessionDumpView');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemSessionDumpView'));

--- new programs of 7.4
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System files diff','SystemFilesDiff');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemFilesDiff'));

INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Information','SystemInformationView');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemInformationView'));

--- new columns of 7.4
ALTER TABLE system_user add column accepted_term_policy char(1);
ALTER TABLE system_user add column accepted_term_policy_at varchar(256);
UPDATE system_user set accepted_term_policy='N';

--- new table/columns of 7.5
ALTER TABLE system_user add column accepted_term_policy_data text;
ALTER TABLE system_user add column phone varchar(256);
ALTER TABLE system_user add column address varchar(256);
ALTER TABLE system_user add column about varchar(256);
ALTER TABLE system_user add column function_name varchar(255);

CREATE TABLE system_user_old_password (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    password varchar(256),
    created_at varchar(20),
    FOREIGN KEY(system_user_id) REFERENCES system_user(id)
);

INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System documents','SystemDriveList');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemDriveList'));

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemDriveList'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Folder form','SystemFolderForm');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemFolderForm'));

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemFolderForm'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'Share Share folder','SystemFolderShareForm');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemFolderShareForm'));

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemFolderShareForm'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'Share Share document','SystemDocumentShareForm');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemDocumentShareForm'));

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemDocumentShareForm'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Document properties','SystemDocumentFormWindow');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemDocumentFormWindow'));

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemDocumentFormWindow'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'Folder Folder properties','SystemFolderFormView');

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemFolderFormView'));

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemFolderFormView'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Document upload','SystemDriveDocumentUploadForm');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemDriveDocumentUploadForm'));

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemDriveDocumentUploadForm'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Post list','SystemPostList');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemPostList'));

INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Post form','SystemPostForm');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemPostForm'));

INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'Post View list','SystemPostFeedView');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemPostFeedView'));

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemPostFeedView'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'Post Comment form','SystemPostCommentForm');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemPostCommentForm'));

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemPostCommentForm'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'Post Comment list','SystemPostCommentList');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemPostCommentList'));

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemPostCommentList'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Contacts list','SystemContactsList');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemContactsList'));

INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Wiki list','SystemWikiList');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemWikiList'));

INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Wiki form','SystemWikiForm');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemWikiForm'));

INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Wiki search','SystemWikiSearchList');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemWikiSearchList'));

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemWikiSearchList'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Wiki view','SystemWikiView');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemWikiView'));

INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Wiki view','SystemWikiView');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemWikiView'));

UPDATE system_user set active='Y' WHERE active is null;


--- changes from 7.6.0
ALTER TABLE system_unit add column custom_code varchar(256);
ALTER TABLE system_user add column custom_code varchar(256);
ALTER TABLE system_user add column otp_secret varchar(256);

CREATE TABLE system_role (
    id int PRIMARY KEY NOT NULL,
    name varchar(256),
    custom_code varchar(256)
);

CREATE TABLE system_user_role (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    system_role_id int,
    FOREIGN KEY(system_user_id) REFERENCES system_user(id),
    FOREIGN KEY(system_role_id) REFERENCES system_role(id)
);

CREATE TABLE system_program_method_role (
    id int PRIMARY KEY NOT NULL,
    system_program_id int,
    system_role_id int,
    method_name varchar(256),
    FOREIGN KEY(system_program_id) REFERENCES system_program(id),
    FOREIGN KEY(system_role_id) REFERENCES system_role(id)
);
    
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Role List','SystemRoleList');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemRoleList'));
                                        
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Role Form','SystemRoleForm');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemRoleForm'));

INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'System Profile 2FA Form','SystemProfile2FAForm');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 2,
                                        (select id from system_program where controller='SystemProfile2FAForm'));

ALTER TABLE system_user RENAME TO system_users;

CREATE INDEX sys_users_name_idx ON system_users(name);
CREATE INDEX sys_group_name_idx ON system_group(name);
CREATE INDEX sys_program_name_idx ON system_program(name);
CREATE INDEX sys_program_controller_idx ON system_program(controller);
CREATE INDEX sys_unit_name_idx ON system_unit(name);
CREATE INDEX sys_role_name_idx ON system_role(name);
CREATE INDEX sys_preference_id_idx ON system_preference(id);
CREATE INDEX sys_preference_value_idx ON system_preference(value);
CREATE INDEX sys_user_unit_user_idx ON system_user_unit(system_user_id);
CREATE INDEX sys_user_unit_unit_idx ON system_user_unit(system_unit_id);
CREATE INDEX sys_user_role_user_idx ON system_user_role(system_user_id);
CREATE INDEX sys_user_role_role_idx ON system_user_role(system_role_id);
CREATE INDEX sys_user_old_password_user_idx ON system_user_old_password(system_user_id);
CREATE INDEX sys_program_method_role_program_idx ON system_program_method_role(system_program_id);
CREATE INDEX sys_program_method_role_role_idx ON system_program_method_role(system_role_id);


INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program b),'Session vars','SystemSessionVarsView');
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program b), 1,
                                        (select id from system_program where controller='SystemSessionVarsView'));

--- changes from 8.0.0
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System document create form', 'SystemDriveDocumentCreateForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Message Tag form', 'SystemMessageTagForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System schedule list', 'SystemScheduleList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System schedule form', 'SystemScheduleForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System schedule log', 'SystemScheduleLogList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'Text document editor', 'SystemTextDocumentEditor');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Wiki page picker', 'SystemWikiPagePicker');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Modules Check View', 'SystemModulesCheckView');


INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemDriveDocumentCreateForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemMessageTagForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemScheduleList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemScheduleForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemScheduleLogList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemTextDocumentEditor'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemWikiPagePicker'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemModulesCheckView'));
