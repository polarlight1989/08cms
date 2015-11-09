DROP TABLE IF EXISTS {$tblprefix}mcrecords;
CREATE TABLE {$tblprefix}mcrecords (
  crid int(11) NOT NULL auto_increment auto_increment,
  mid int(11) NOT NULL,
  mname char(15) NOT NULL,
  mcid int(11) NOT NULL,
  needtime int(11) NOT NULL,
  checktime int(11) NOT NULL,
  certdata text NOT NULL,
  PRIMARY KEY  (crid),
  KEY `check` (mid,checktime)
) TYPE=MyISAM AUTO_INCREMENT=20;

