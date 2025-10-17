--- Create system_group table
CREATE TABLE system_group (
    id int PRIMARY KEY NOT NULL,
    name varchar(256)
);

--- Create system_program table
CREATE TABLE system_program (
    id int PRIMARY KEY NOT NULL,
    name varchar(256),
    controller varchar(256)
);

--- Create system_unit table
CREATE TABLE system_unit (
    id int PRIMARY KEY NOT NULL,
    name varchar(256),
    connection_name varchar(256),
    custom_code varchar(256)
);

--- Create system_role table
CREATE TABLE system_role (
    id int PRIMARY KEY NOT NULL,
    name varchar(256),
    custom_code varchar(256)
);

--- Create system_preference table
CREATE TABLE system_preference (
    id varchar(256),
    value text
);

--- Create system_users table
CREATE TABLE system_users (
    id int PRIMARY KEY NOT NULL,
    name varchar(256),
    login varchar(256),
    password varchar(256),
    email varchar(256),
    accepted_term_policy char(1),
    phone varchar(256),
    address varchar(256),
    function_name varchar(256),
    about text,
    accepted_term_policy_at varchar(20),
    accepted_term_policy_data text,
    frontpage_id int,
    system_unit_id int REFERENCES system_unit(id),
    active char(1),
    custom_code varchar(256),
    otp_secret varchar(256),
    FOREIGN KEY(frontpage_id) REFERENCES system_program(id)
);

--- Create system_user_unit table
CREATE TABLE system_user_unit (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    system_unit_id int,
    FOREIGN KEY(system_user_id) REFERENCES system_users(id),
    FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)
);

--- Create system_user_group table
CREATE TABLE system_user_group (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    system_group_id int,
    FOREIGN KEY(system_user_id) REFERENCES system_users(id),
    FOREIGN KEY(system_group_id) REFERENCES system_group(id)
);

--- Create system_user_role table
CREATE TABLE system_user_role (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    system_role_id int,
    FOREIGN KEY(system_user_id) REFERENCES system_users(id),
    FOREIGN KEY(system_role_id) REFERENCES system_role(id)
);

--- Create system_group_program table
CREATE TABLE system_group_program (
    id int PRIMARY KEY NOT NULL,
    system_group_id int,
    system_program_id int,
    FOREIGN KEY(system_group_id) REFERENCES system_group(id),
    FOREIGN KEY(system_program_id) REFERENCES system_program(id)
);

--- Create system_user_program table
CREATE TABLE system_user_program (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    system_program_id int,
    FOREIGN KEY(system_user_id) REFERENCES system_users(id),
    FOREIGN KEY(system_program_id) REFERENCES system_program(id)
);
    
--- Create system_user_old_password table
CREATE TABLE system_user_old_password (
    id int PRIMARY KEY NOT NULL,
    system_user_id int,
    password varchar(256),
    created_at varchar(20),
    FOREIGN KEY(system_user_id) REFERENCES system_users(id)
);

--- Create system_program_method_role table
CREATE TABLE system_program_method_role (
    id int PRIMARY KEY NOT NULL,
    system_program_id int,
    system_role_id int,
    method_name varchar(256),
    FOREIGN KEY(system_program_id) REFERENCES system_program(id),
    FOREIGN KEY(system_role_id) REFERENCES system_role(id)
);


--- Insert groups
INSERT INTO system_group values (1, 'Template - Admin');
INSERT INTO system_group values (2, 'Template - Users');
INSERT INTO system_group values (3, 'Application - Programs');


--- Insert programs
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Administration Dashboard', 'SystemAdministrationDashboard');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Program Form', 'SystemProgramForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Program List', 'SystemProgramList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Group Form', 'SystemGroupForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Group List', 'SystemGroupList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Unit Form', 'SystemUnitForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Unit List', 'SystemUnitList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Role Form', 'SystemRoleForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Role List', 'SystemRoleList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System User Form', 'SystemUserForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System User List', 'SystemUserList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Preference form', 'SystemPreferenceForm');

INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Log Dashboard', 'SystemLogDashboard');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Access Log', 'SystemAccessLogList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System ChangeLog View', 'SystemChangeLogView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Sql Log', 'SystemSqlLogList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Request Log', 'SystemRequestLogList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Request Log View', 'SystemRequestLogView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System PHP Error', 'SystemPHPErrorLogView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Session vars', 'SystemSessionVarsView');

INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Database Browser', 'SystemDatabaseExplorer');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Table List', 'SystemTableList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Data Browser', 'SystemDataBrowser');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System SQL Panel', 'SystemSQLPanel');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Modules', 'SystemModulesCheckView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System files diff', 'SystemFilesDiff');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Information', 'SystemInformationView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System PHP Info', 'SystemPHPInfoView');

INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'Common Page', 'CommonPage');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'Welcome View', 'WelcomeView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'Welcome dashboard', 'WelcomeDashboardView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Profile View', 'SystemProfileView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Profile Form', 'SystemProfileForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Notification List', 'SystemNotificationList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Notification Form View', 'SystemNotificationFormView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Support form', 'SystemSupportForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Profile 2FA Form', 'SystemProfile2FAForm');


--- Insert users
INSERT INTO system_users (id,
                          name,
                          login,
                          password,
                          email,
                          accepted_term_policy,
                          phone,
                          address,
                          function_name,
                          about,
                          active,
                          frontpage_id) 
                values ( (SELECT coalesce(max(id),0)+1 FROM system_users b),
                         'Administrator',
                         'admin',
                         '$2y$10$xuR3XEc3J6tpv7myC9gPj.Ab5GacSeHSZoYUTYtOg.cEc22G.iBwa',
                         'admin@admin.net',
                         'Y',
                         '+123 456 789',
                         'Admin Street, 123',
                         'Administrator',
                         'I''m the administrator',
                         'Y',
                         (select id from system_program where controller='WelcomeView')
                 );

INSERT INTO system_users (id,
                          name,
                          login,
                          password,
                          email,
                          accepted_term_policy,
                          phone,
                          address,
                          function_name,
                          about,
                          active,
                          frontpage_id) 
                values ( (SELECT coalesce(max(id),0)+1 FROM system_users b),
                         'User',
                         'user',
                         '$2y$10$MUYN29LOSHrCSGhrzvYG8O/PtAjbWvCubaUSTJGhVTJhm69WNFJs.',
                         'user@user.net',
                         'Y',
                         '+123 456 789',
                         'User Street, 123',
                         'End user',
                         'I''m the end user',
                         'Y',
                         (select id from system_program where controller='WelcomeView')
                 );

--- Insert units
INSERT INTO system_unit (id, name, connection_name) values ( (SELECT coalesce(max(id),0)+1 FROM system_unit b), 'Unit A', 'unit_a');
INSERT INTO system_unit (id, name, connection_name) values ( (SELECT coalesce(max(id),0)+1 FROM system_unit b), 'Unit B', 'unit_b');

--- Insert roles
INSERT INTO system_role (id, name, custom_code) values ( (SELECT coalesce(max(id),0)+1 FROM system_role b), 'Role A', '');
INSERT INTO system_role (id, name, custom_code) values ( (SELECT coalesce(max(id),0)+1 FROM system_role b), 'Role B', '');


--- Insert users in groups
INSERT INTO system_user_group (id, system_user_id, system_group_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_user_group b), (select id from system_users where login='admin'), 1);
INSERT INTO system_user_group (id, system_user_id, system_group_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_user_group b), (select id from system_users where login='admin'), 2);
INSERT INTO system_user_group (id, system_user_id, system_group_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_user_group b), (select id from system_users where login='admin'), 3);
INSERT INTO system_user_group (id, system_user_id, system_group_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_user_group b), (select id from system_users where login='user'),  2);


--- Insert users in units
INSERT INTO system_user_unit VALUES ( (SELECT coalesce(max(id),0)+1 FROM system_user_unit b), (select id from system_users where login='admin'), 1);
INSERT INTO system_user_unit VALUES ( (SELECT coalesce(max(id),0)+1 FROM system_user_unit b), (select id from system_users where login='admin'), 2);
INSERT INTO system_user_unit VALUES ( (SELECT coalesce(max(id),0)+1 FROM system_user_unit b), (select id from system_users where login='user'), 1);
INSERT INTO system_user_unit VALUES ( (SELECT coalesce(max(id),0)+1 FROM system_user_unit b), (select id from system_users where login='user'), 2);


--- Insert programs in groups
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemAdministrationDashboard'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemProgramForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemProgramList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemGroupForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemGroupList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemUnitForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemUnitList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemRoleForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemRoleList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemUserForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemUserList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemPreferenceForm'));

INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemLogDashboard'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemAccessLogList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemChangeLogView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemSqlLogList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemRequestLogList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemRequestLogView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemPHPErrorLogView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemSessionVarsView'));

INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemDatabaseExplorer'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemTableList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemDataBrowser'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemSQLPanel'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemModulesCheckView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemFilesDiff'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemInformationView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemPHPInfoView'));

INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='CommonPage'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='WelcomeView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='WelcomeDashboardView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemProfileView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemProfileForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemNotificationList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemNotificationFormView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemSupportForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemProfile2FAForm'));


----------------------------- COMMUNICATION ----------------------------------------------


--- Insert programs
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Wiki list', 'SystemWikiList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Wiki form', 'SystemWikiForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Wiki page picker', 'SystemWikiPagePicker');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Post list', 'SystemPostList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Post form', 'SystemPostForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System schedule list', 'SystemScheduleList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System schedule form', 'SystemScheduleForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System schedule log', 'SystemScheduleLogList');

INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Message Form', 'SystemMessageForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Message List', 'SystemMessageList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Message Form View', 'SystemMessageFormView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Documents', 'SystemDriveList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Folder form', 'SystemFolderForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Share folder', 'SystemFolderShareForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Share document', 'SystemDocumentShareForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Document properties', 'SystemDocumentFormWindow');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Folder properties', 'SystemFolderFormView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Document upload', 'SystemDriveDocumentUploadForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'Post View list', 'SystemPostFeedView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'Post Comment form', 'SystemPostCommentForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'Post Comment list', 'SystemPostCommentList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Wiki search', 'SystemWikiSearchList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Wiki view', 'SystemWikiView');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Message Tag form', 'SystemMessageTagForm');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System Contacts list', 'SystemContactsList');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'Text document editor', 'SystemTextDocumentEditor');
INSERT INTO system_program (id, name, controller) values ((SELECT coalesce(max(id),0)+1 FROM system_program b), 'System document create form', 'SystemDriveDocumentCreateForm');

--- Insert programs in groups
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemWikiList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemWikiForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemWikiPagePicker'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemPostList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemPostForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemScheduleList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemScheduleForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 1, (select id from system_program where controller='SystemScheduleLogList'));

INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemMessageForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemMessageList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemMessageFormView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemDriveList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemFolderForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemFolderShareForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemDocumentShareForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemDocumentFormWindow'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemFolderFormView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemDriveDocumentUploadForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemPostFeedView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemPostCommentForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemPostCommentList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemWikiSearchList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemWikiView'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemMessageTagForm'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemContactsList'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemTextDocumentEditor'));
INSERT INTO system_group_program (id, system_group_id, system_program_id) values ( (SELECT coalesce(max(id),0)+1 FROM system_group_program b), 2, (select id from system_program where controller='SystemDriveDocumentCreateForm'));

--- Create indexes
CREATE INDEX sys_user_program_idx ON system_users(frontpage_id);
CREATE INDEX sys_user_group_group_idx ON system_user_group(system_group_id);
CREATE INDEX sys_user_group_user_idx ON system_user_group(system_user_id);
CREATE INDEX sys_group_program_program_idx ON system_group_program(system_program_id);
CREATE INDEX sys_group_program_group_idx ON system_group_program(system_group_id);
CREATE INDEX sys_user_program_program_idx ON system_user_program(system_program_id);
CREATE INDEX sys_user_program_user_idx ON system_user_program(system_user_id);
CREATE INDEX sys_users_name_idx ON system_users(name);
CREATE INDEX sys_group_name_idx ON system_group(name);
CREATE INDEX sys_program_name_idx ON system_program(name);
CREATE INDEX sys_program_controller_idx ON system_program(controller);
CREATE INDEX sys_unit_name_idx ON system_unit(name);
CREATE INDEX sys_role_name_idx ON system_role(name);
CREATE INDEX sys_preference_id_idx ON system_preference(id);
CREATE INDEX sys_user_unit_user_idx ON system_user_unit(system_user_id);
CREATE INDEX sys_user_unit_unit_idx ON system_user_unit(system_unit_id);
CREATE INDEX sys_user_role_user_idx ON system_user_role(system_user_id);
CREATE INDEX sys_user_role_role_idx ON system_user_role(system_role_id);
CREATE INDEX sys_user_old_password_user_idx ON system_user_old_password(system_user_id);
CREATE INDEX sys_program_method_role_program_idx ON system_program_method_role(system_program_id);
CREATE INDEX sys_program_method_role_role_idx ON system_program_method_role(system_role_id);
