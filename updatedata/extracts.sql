DROP TABLE IF EXISTS {$tblprefix}extracts;
CREATE TABLE {$tblprefix}extracts (
  eid int(10) unsigned NOT NULL AUTO_INCREMENT,
  mid int(10) unsigned NOT NULL,
  mname char(15) NOT NULL,
  integral float unsigned NOT NULL,
  total float NOT NULL,
  rate float NOT NULL,
  remark text NOT NULL,
  checkdate int(11) NOT NULL,
  createdate int(11) NOT NULL,
  PRIMARY KEY (eid),
  KEY mid (mid),
  KEY mid_date (mid,createdate,checkdate)
) TYPE=MyISAM AUTO_INCREMENT=10;

