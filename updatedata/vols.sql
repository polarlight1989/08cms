DROP TABLE IF EXISTS {$tblprefix}vols;
CREATE TABLE {$tblprefix}vols (
  vid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  aid mediumint(8) unsigned NOT NULL DEFAULT '0',
  volid smallint(6) unsigned NOT NULL DEFAULT '1',
  vtitle varchar(80) NOT NULL,
  PRIMARY KEY (vid),
  KEY aid (aid),
  KEY volid (volid)
) TYPE=MyISAM AUTO_INCREMENT=17;

