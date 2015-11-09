DROP TABLE IF EXISTS {$tblprefix}repus;
CREATE TABLE {$tblprefix}repus (
  rid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  repus int(10) NOT NULL DEFAULT '0',
  mid mediumint(8) unsigned NOT NULL DEFAULT '0',
  mname char(15) NOT NULL,
  createdate int(10) unsigned NOT NULL DEFAULT '0',
  reason varchar(255) NOT NULL,
  PRIMARY KEY (rid),
  KEY mid (mid)
) TYPE=MyISAM AUTO_INCREMENT=33;

