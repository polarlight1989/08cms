DROP TABLE IF EXISTS {$tblprefix}mcnodes;
CREATE TABLE {$tblprefix}mcnodes (
  cnid smallint(6) unsigned NOT NULL auto_increment auto_increment,
  ename char(12) NOT NULL default '',
  alias varchar(30) NOT NULL default '',
  appurl varchar(80) NOT NULL default '',
  mcnvar char(10) NOT NULL default 'caid',
  mcnid smallint(6) unsigned NOT NULL default '0',
  addnum tinyint(1) unsigned NOT NULL default '0',
  tpls varchar(30) NOT NULL default '',
  urls varchar(255) NOT NULL default '',
  needstatics varchar(20) NOT NULL default '',
  statics varchar(2) NOT NULL default '',
  periods varchar(80) NOT NULL default '',
  PRIMARY KEY  (cnid),
  KEY ename (ename)
) TYPE=MyISAM AUTO_INCREMENT=43;

