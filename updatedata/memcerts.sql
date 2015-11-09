DROP TABLE IF EXISTS {$tblprefix}memcerts;
CREATE TABLE {$tblprefix}memcerts (
  mcid int(11) NOT NULL auto_increment auto_increment,
  title varchar(50) NOT NULL,
  `level` smallint(6) NOT NULL,
  icon varchar(50) NOT NULL,
  remark varchar(255) NOT NULL,
  mchids varchar(255) NOT NULL,
  mobile varchar(25) NOT NULL,
  email varchar(25) NOT NULL,
  `fields` varchar(255) NOT NULL,
  PRIMARY KEY  (mcid)
) TYPE=MyISAM AUTO_INCREMENT=7;

