
/*==============================================================*/
/* Table: contact                                               */
/* Desc: user can upload contact lists here                     */
/*==============================================================*/
CREATE TABLE contact
(
   contact_id                    int(11) unsigned       NOT NULL auto_increment,
   tenant_id                     int(11)                default NULL,
   first_name                    varchar(64)            default NULL,
   last_name                     varchar(64)            default NULL,
   phone                         varchar(32)            default NULL,
   email                         varchar(64)            default NULL,
   address                       varchar(128)           default NULL,
   custom1                       varchar(128)           default NULL,
   custom2                       varchar(128)           default NULL,
   custom3                       varchar(128)           default NULL,
   description                   varchar(255)           default NULL,
   date_created                  int(11)                default NULL,
   created_by                    int(11)                default NULL,
   last_updated                  int(11)                default NULL,
   updated_by                    int(11) unsigned       default NULL,
   job_title                     varchar(128)           default NULL,
   organization_name             varchar(128)           default NULL,
   assigned_owner                varchar(128)           default NULL,
   do_not_call                   varchar(128)           default NULL,
   status_description            varchar(128)           default NULL,
   opportunity_name              varchar(128)           default NULL,
   opportunity_amount            varchar(128)           default NULL,
   account_name                  varchar(128)           default NULL,
   status                        varchar(128)           default NULL,
   lead_conversion               varchar(128)           default NULL,
   PRIMARY KEY (contact_id)
) ENGINE = InnoDB;
CREATE INDEX contact_created_by ON contact (created_by);

//
/*==============================================================*/
/* Table: lead                                               */
/* Desc: user can upload lead lists here                     */
/*==============================================================*/
CREATE TABLE lead
(
   lead_id                    int(11) unsigned       NOT NULL auto_increment,
   first_name                    varchar(64)            default NULL,
   last_name                     varchar(64)            default NULL,
   phone                         varchar(32)            default NULL,
   email                         varchar(64)            default NULL,
   address                       varchar(128)           default NULL,
   custom1                       varchar(128)           default NULL,
   custom2                       varchar(128)           default NULL,
   custom3                       varchar(128)           default NULL,
   description                   varchar(255)           default NULL,
   date_created                  int(11)                default NULL,
   created_by                    int(11)                default NULL,
   last_updated                  int(11)                default NULL,
   updated_by                    int(11) unsigned       default NULL,
   PRIMARY KEY (lead_id)
) ENGINE = InnoDB;
CREATE INDEX lead_created_by ON lead (created_by);
//
/*==============================================================*/
/* Table: accounts                                               */
/* Desc: user can upload accounts lists here                     */
/*==============================================================*/
CREATE TABLE accounts
(
   account_id                          int(11) unsigned       NOT NULL auto_increment,
   opportunity_name                    varchar(64)            default NULL,
   opportunity_amount                  varchar(64)            default NULL,
   account_name                        varchar(32)            default NULL,
   campaign                            varchar(64)            default NULL,
   email                               varchar(128)           default NULL,
   phone                               varchar(128)           default NULL,
   shipping                            varchar(128)           default NULL,
   description                         varchar(255)           default NULL,
   PRIMARY KEY (account_id)
) ENGINE = InnoDB;
CREATE INDEX accounts_created_by ON accounts (created_by);
//
/*==============================================================*/
/* Table: campaigns                                               */
/* Desc: user can upload campaigns lists here                     */
/*==============================================================*/
CREATE TABLE campaigns
(
   campaign_id                          int(11) unsigned       NOT NULL auto_increment,
   campaign_name                        varchar(64)            default NULL,
   status                               varchar(64)            default NULL,
   type                                 varchar(32)            default NULL,
   date_created                         varchar(64)            default NULL,
   end_date                             varchar(128)           default NULL,
   user                                 varchar(255)           default NULL,
   PRIMARY KEY (campaign_id)
) ENGINE = InnoDB;
CREATE INDEX campaigns_created_by ON campaigns (created_by);
//
/*==============================================================*/
/* Table: crm_report                                               */
/* Desc: user can upload crm_report lists here                     */
/*==============================================================*/
CREATE TABLE crm_report
(
   crm_report_id                          int(11) unsigned       NOT NULL auto_increment,
   name                                 varchar(64)            default NULL,
   report_module                        varchar(64)            default NULL,
   assigned_to                          varchar(32)            default NULL,
   date_created                         varchar(64)            default NULL,
   end_date                             varchar(128)           default NULL,
   PRIMARY KEY (crm_report_id)
) ENGINE = InnoDB;
CREATE INDEX crm_report_created_by ON crm_report (created_by);
//
//
/*==============================================================*/
/* Table: crm_opportunity                                               */
/* Desc: user can upload crm_opportunity lists here                     */
/*==============================================================*/
CREATE TABLE crm_opportunity
(
   crm_opportunity_id                          int(11) unsigned       NOT NULL auto_increment,
   opportunity_name                            varchar(64)            default NULL,
   opportunity_amount                          varchar(64)            default NULL,
   account_name                                varchar(32)            default NULL,
   campaign                                    varchar(64)            default NULL,
   probability                                 varchar(128)           default NULL,
   description                                 varchar(255)           default NULL,
   PRIMARY KEY (crm_opportunity_id)
) ENGINE = InnoDB;
CREATE INDEX crm_opportunity_created_by ON crm_opportunity (created_by);
//
