DROP TABLE IF EXISTS {$tblprefix}domains;
CREATE TABLE {$tblprefix}domains (
  id smallint(6) unsigned NOT NULL auto_increment auto_increment,
  domain varchar(100) NOT NULL default '',
  folder varchar(100) NOT NULL default '',
  isreg tinyint(1) unsigned NOT NULL default '0',
  vieworder smallint(6) unsigned NOT NULL default '0',
  remark varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM AUTO_INCREMENT=9;

