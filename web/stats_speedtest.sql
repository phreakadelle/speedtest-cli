
CREATE TABLE IF NOT EXISTS `stats_speedtest` (
  `stats_id` int(11) NOT NULL AUTO_INCREMENT,
  `stats_ts` int(11) NOT NULL,
  `stats_date` date NOT NULL,
  `stats_time` time NOT NULL,
  `stats_download` int(11) NOT NULL,
  `stats_ping` int(11) NOT NULL,
  `stats_upload` int(11) NOT NULL,
  `stats_promo` int(11) NOT NULL,
  `stats_startmode` int(11) NOT NULL,
  `stats_pingselect` int(11) NOT NULL,
  `stats_recommendedserverid` int(11) NOT NULL,
  `stats_accuracy` int(11) NOT NULL,
  `stats_serverid` int(11) NOT NULL,
  `stats_hash` varchar(32) NOT NULL,
  `stats_apikey` varchar(32) NOT NULL,
  PRIMARY KEY (`stats_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;