-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Sep 06, 2017 at 06:59 AM
-- Server version: 10.1.24-MariaDB-cll-lve
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ytapeawp_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `pt3_ads`
--

CREATE TABLE IF NOT EXISTS `pt3_ads` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_name` varchar(150) NOT NULL,
  `ad_group_id` int(11) NOT NULL,
  `width` varchar(4) NOT NULL,
  `height` varchar(4) NOT NULL,
  `code` text NOT NULL,
  `position` int(2) NOT NULL,
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `pt3_ads`
--

INSERT INTO `pt3_ads` (`ad_id`, `ad_name`, `ad_group_id`, `width`, `height`, `code`, `position`) VALUES
(1, 'Ads 468', 4, '468', '60', '<img src="images/sample_ad_468x60_video_detail.gif" />', 0),
(5, 'Leaderboard Ads', 6, '728', '0', '<img src="images/sample_ad_728x90.gif" />', 0),
(12, 'Video Detail Below Video', 3, '468', '60', '<img src="images/sample_ad_468x60_video_detail.gif" />', 0),
(13, 'Video Detail Above Video', 7, '468', '60', '<img src="images/sample_ad_468x60_video_detail.gif" />', 0),
(7, 'Above Video Detail Ads', 9, '300', '250', '<img src="images/sample_ad_300x250.gif" />', 0),
(9, 'Sitewide - Above Video Tags Ads', 10, '300', '250', '<img src="images/sample_ad_300x250.gif" />', 0),
(10, 'Above Related Videos', 2, '300', '250', '<img src="images/sample_ad_300x250.gif" />', 0),
(11, 'Above Video Desc', 8, '300', '250', '<img src="images/sample_ad_300x250.gif" />', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pt3_ad_group`
--

CREATE TABLE IF NOT EXISTS `pt3_ad_group` (
  `ad_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
  `orientation` varchar(15) NOT NULL,
  `width` varchar(4) NOT NULL,
  `height` varchar(4) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`ad_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `pt3_ad_group`
--

INSERT INTO `pt3_ad_group` (`ad_group_id`, `group_name`, `orientation`, `width`, `height`, `active`) VALUES
(1, 'Top', 'horizontal', '468', '65', 1),
(2, 'Video Detail - Above Related Videos', 'horizontal', '302', '250', 1),
(3, 'Video Detail - Below Video', 'vertical', '468', '62', 1),
(4, 'Center', 'horizontal', '565', '68', 1),
(5, 'Bottom', 'horizontal', '730', '92', 1),
(6, 'Leader Board', 'vertical', '730', '0', 1),
(7, 'Video Detail - Above Video', 'vertical', '468', '60', 1),
(8, 'Video Detail - Above Video Description', 'vertical', '302', '250', 1),
(9, 'Sitewide - Above Category', 'vertical', '302', '250', 1),
(10, 'Sitewide - Above Video Tags', 'vertical', '302', '250', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pt3_categories`
--

CREATE TABLE IF NOT EXISTS `pt3_categories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `position` varchar(255) NOT NULL DEFAULT '',
  `c_name` varchar(255) NOT NULL DEFAULT '',
  `c_listing_source` varchar(50) NOT NULL DEFAULT 'keyword',
  `c_desc` text NOT NULL,
  `c_keyword` varchar(255) NOT NULL DEFAULT '',
  `c_user_videos` varchar(50) NOT NULL,
  `author_username` varchar(100) NOT NULL,
  `c_playlist_id` varchar(34) NOT NULL,
  `c_group` varchar(255) NOT NULL DEFAULT '0',
  `date_added` date NOT NULL,
  `enable_publishdate` tinyint(1) NOT NULL,
  `publishdate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1002 ;

--
-- Dumping data for table `pt3_categories`
--

INSERT INTO `pt3_categories` (`id`, `position`, `c_name`, `c_listing_source`, `c_desc`, `c_keyword`, `c_user_videos`, `author_username`, `c_playlist_id`, `c_group`, `date_added`, `enable_publishdate`, `publishdate`) VALUES
(681, '681>', 'Funny Videos', 'keyword', '', 'FUNNY', '', '', '', '0', '2010-01-20', 0, 0),
(682, '681>682>', 'HOME VIDEOS', 'keyword', '', 'FUNIEST HOME VIDEOS', '', '', '', '0', '2010-01-20', 0, 0),
(687, '687>', 'Comedy Videos', 'keyword', '', 'Comedy', '', '', '', '0', '2010-01-20', 0, 0),
(688, '687>688>', 'Stand Up Comedy', 'keyword', '', 'Stand Up Comedy', '', '', '', '0', '2010-01-20', 0, 0),
(689, '687>689>', 'george lopez', 'keyword', '', 'george lopez', '', '', '', '0', '2010-01-20', 0, 0),
(690, '687>690>', 'Chris Rock', 'keyword', '', 'Chris Rock', '', '', '', '0', '2010-01-20', 0, 0),
(691, '687>691>', 'Bill Cosby', 'keyword', '', 'Bill Cosby', '', '', '', '0', '2010-01-20', 0, 0),
(692, '687>692>', 'Chris Tucker', 'keyword', '', 'Chris Tucker', '', '', '', '0', '2010-01-20', 0, 0),
(693, '687>693>', 'Dave Chappelle', 'keyword', '', 'Dave Chappelle', '', '', '', '0', '2010-01-20', 0, 0),
(694, '687>694>', 'Eddie Murphy', 'keyword', '', 'Eddie Murphy', '', '', '', '0', '2010-01-20', 0, 0),
(695, '687>695>', 'George Carlin', 'keyword', '', 'George Carlin', '', '', '', '0', '2010-01-20', 0, 0),
(696, '687>696>', 'Jamie Foxx', 'keyword', '', 'Jamie Foxx', '', '', '', '0', '2010-01-20', 0, 0),
(697, '687>697>', 'Jerry Seinfeld', 'keyword', '', 'Jerry Seinfeld', '', '', '', '0', '2010-01-20', 0, 0),
(698, '687>698>', 'Jim Carrey', 'keyword', '', 'Jim Carrey', '', '', '', '0', '2010-01-20', 0, 0),
(699, '687>699>', 'Martin Lawrence', 'keyword', '', 'Martin Lawrence', '', '', '', '0', '2010-01-20', 0, 0),
(700, '687>700>', 'Mitch Hedberg', 'keyword', '', 'Mitch Hedberg', '', '', '', '0', '2010-01-20', 0, 0),
(701, '687>701>', 'Richard Pryor', 'keyword', '', 'Richard Pryor', '', '', '', '0', '2010-01-20', 0, 0),
(702, '687>702>', 'Robin Williams', 'keyword', '', 'Robin Williams', '', '', '', '0', '2010-01-20', 0, 0),
(703, '687>703>', 'Rodney Dangerfield', 'keyword', '', 'Rodney Dangerfield', '', '', '', '0', '2010-01-20', 0, 0),
(704, '687>704>', 'Russell Peters', 'keyword', '', 'Russell Peters', '', '', '', '0', '2010-01-20', 0, 0),
(705, '687>705>', 'Russell Peters', 'keyword', '', 'Russell Peters', '', '', '', '0', '2010-01-20', 0, 0),
(706, '687>706>', 'Steve Martin', 'keyword', '', 'Steve Martin', '', '', '', '0', '2010-01-20', 0, 0),
(707, '687>707>', 'Steven Wright', 'keyword', '', 'Steven Wright', '', '', '', '0', '2010-01-20', 0, 0),
(708, '681>708>', 'Funny people', 'keyword', '', 'Funny People', '', '', '', '0', '2010-01-20', 0, 0),
(709, '681>709>', 'funny actors', 'keyword', '', 'funny actors', '', '', '', '0', '2010-01-20', 0, 0),
(710, '681>710>', 'funny animals', 'keyword', '', 'funny animals', '', '', '', '0', '2010-01-20', 0, 0),
(711, '681>711>', 'funny auditions', 'keyword', '', 'funny auditions', '', '', '', '0', '2010-01-20', 0, 0),
(712, '681>712>', 'funny baby', 'keyword', '', 'funny baby', '', '', '', '0', '2010-01-20', 0, 0),
(713, '681>713>', 'funny birthdays', 'keyword', '', 'funny birthdays', '', '', '', '0', '2010-01-20', 0, 0),
(714, '681>714>', 'funny wedding', 'keyword', '', 'funny wedding', '', '', '', '0', '2010-01-20', 0, 0),
(715, '681>715>', 'funny party', 'keyword', '', 'funny party', '', '', '', '0', '2010-01-20', 0, 0),
(716, '681>716>', 'funny boating', 'keyword', '', 'funny boating', '', '', '', '0', '2010-01-20', 0, 0),
(717, '681>717>', 'funny boating', 'keyword', '', 'funny boating', '', '', '', '0', '2010-01-20', 0, 0),
(718, '681>718>', 'funny camping', 'keyword', '', 'funny camping', '', '', '', '0', '2010-01-20', 0, 0),
(719, '681>719>', 'funny cars', 'keyword', '', 'funny cars', '', '', '', '0', '2010-01-20', 0, 0),
(720, '681>720>', 'funny cats', 'keyword', '', 'funny cats', '', '', '', '0', '2010-01-20', 0, 0),
(721, '681>721>', 'funny comedians', 'keyword', '', 'funny comedians', '', '', '', '0', '2010-01-20', 0, 0),
(722, '681>722>', 'funny dads', 'keyword', '', 'funny dads', '', '', '', '0', '2010-01-20', 0, 0),
(723, '681>723>', 'funny dating', 'keyword', '', 'funny dating', '', '', '', '0', '2010-01-20', 0, 0),
(724, '681>724>', 'funny faces', 'keyword', '', 'funny faces', '', '', '', '0', '2010-01-20', 0, 0),
(725, '681>725>', 'funny fishing', 'keyword', '', 'funny fishing', '', '', '', '0', '2010-01-20', 0, 0),
(726, '681>726>', 'funny gifts', 'keyword', '', 'funny gifts', '', '', '', '0', '2010-01-20', 0, 0),
(727, '681>727>', 'funny imitations', 'keyword', '', 'funny imitations', '', '', '', '0', '2010-01-20', 0, 0),
(728, '681>728>', 'funny jobs', 'keyword', '', 'funny jobs', '', '', '', '0', '2010-01-20', 0, 0),
(729, '681>729>', 'funny kids', 'keyword', '', 'funny kids', '', '', '', '0', '2010-01-20', 0, 0),
(730, '681>730>', 'funny laughs', 'keyword', '', 'funny laughs', '', '', '', '0', '2010-01-20', 0, 0),
(731, '681>731>', 'funny looking', 'keyword', '', 'funny looking', '', '', '', '0', '2010-01-20', 0, 0),
(732, '681>732>', 'funny moms', 'keyword', '', 'funny moms', '', '', '', '0', '2010-01-20', 0, 0),
(733, '681>733>', 'funny school', 'keyword', '', 'funny school', '', '', '', '0', '2010-01-20', 0, 0),
(734, '681>734>', 'funny snowmen', 'keyword', '', 'funny snowmen', '', '', '', '0', '2010-01-20', 0, 0),
(735, '681>735>', 'funny sports', 'keyword', '', 'funny sports', '', '', '', '0', '2010-01-20', 0, 0),
(737, '681>737>', 'funny jokes', 'keyword', '', 'funny jokes', '', '', '', '0', '2010-01-20', 0, 0),
(738, '681>738>', 'funny vacations', 'keyword', '', 'funny vacations', '', '', '', '0', '2010-01-20', 0, 0),
(739, '681>739>', 'funny home video', 'keyword', '', 'funny home video', '', '', '', '0', '2010-01-20', 0, 0),
(740, '681>740>', 'funny stupid', 'keyword', '', 'funny stupid', '', '', '', '0', '2010-01-20', 0, 0),
(741, '681>741>', 'funny accidents', 'keyword', '', 'funny accidents', '', '', '', '0', '2010-01-20', 0, 0),
(759, '684>759>', 'Boxing', 'keyword', '', 'Boxing', '', '', '', '0', '2010-01-20', 0, 0),
(760, '684>760>', 'Capoeira', 'keyword', '', 'Capoeira', '', '', '', '0', '2010-01-20', 0, 0),
(761, '684>761>', 'ComBaton', 'keyword', '', 'ComBaton', '', '', '', '0', '2010-01-20', 0, 0),
(763, '684>763>', 'Hooligans', 'keyword', '', 'Hooligans', '', '', '', '0', '2010-01-20', 0, 0),
(764, '684>764>', 'Judo', 'keyword', '', 'Judo', '', '', '', '0', '2010-01-20', 0, 0),
(765, '684>765>', 'Jujitsu', 'keyword', '', 'Jujitsu', '', '', '', '0', '2010-01-20', 0, 0),
(766, '684>766>', 'Karate', 'keyword', '', 'Karate', '', '', '', '0', '2010-01-20', 0, 0),
(767, '684>767>', 'Kendo', 'keyword', '', 'Kendo', '', '', '', '0', '2010-01-20', 0, 0),
(768, '684>768>', 'Kickboxing', 'keyword', '', 'Kickboxing', '', '', '', '0', '2010-01-20', 0, 0),
(770, '684>770>', 'Kung Fu', 'keyword', '', 'Kung Fu', '', '', '', '0', '2010-01-20', 0, 0),
(771, '684>771>', 'Muay Thai', 'keyword', '', 'Muay Thai', '', '', '', '0', '2010-01-20', 0, 0),
(772, '772>', 'Extreme videos', 'keyword', '', 'Extreme', '', '', '', '0', '2010-01-20', 0, 0),
(773, '772>773>', 'parkour', 'keyword', '', 'parkour', '', '', '', '0', '2010-01-20', 0, 0),
(775, '684>775>', 'TaeKwonDo', 'keyword', '', 'TaeKwonDo', '', '', '', '0', '2010-01-20', 0, 0),
(777, '684>777>', 'Wrestling', 'keyword', '', 'Wrestling', '', '', '', '0', '2010-01-20', 0, 0),
(778, '684>778>', 'WWE', 'keyword', '', 'WWE', '', '', '', '0', '2010-01-20', 0, 0),
(779, '684>779>', 'RAW', 'keyword', '', 'RAW', '', '', '', '0', '2010-01-20', 0, 0),
(780, '772>780>', 'extreme sport', 'keyword', '', 'extreme sport', '', '', '', '0', '2010-01-20', 0, 0),
(781, '772>781>', 'zorbing', 'keyword', '', 'zorbing', '', '', '', '0', '2010-01-20', 0, 0),
(782, '772>782>', 'windsurfing', 'keyword', '', 'windsurfing', '', '', '', '0', '2010-01-20', 0, 0),
(783, '772>783>', 'extreme stunts', 'keyword', '', 'extreme stunts', '', '', '', '0', '2010-01-20', 0, 0),
(784, '772>784>', 'stunts', 'keyword', '', 'stunts', '', '', '', '0', '2010-01-20', 0, 0),
(785, '772>785>', 'stupid stunts', 'keyword', '', 'stupid stunts', '', '', '', '0', '2010-01-20', 0, 0),
(786, '772>786>', 'crazy stunts', 'keyword', '', 'crazy stunts', '', '', '', '0', '2010-01-20', 0, 0),
(787, '772>787>', 'motorcycle stunts', 'keyword', '', 'motorcycle stunts', '', '', '', '0', '2010-01-20', 0, 0),
(788, '772>788>', 'bike stunts', 'keyword', '', 'bike stunts', '', '', '', '0', '2010-01-20', 0, 0),
(789, '772>789>', 'car drifting', 'keyword', '', 'car drifting', '', '', '', '0', '2010-01-20', 0, 0),
(790, '772>790>', 'rc drifting', 'keyword', '', 'rc drifting', '', '', '', '0', '2010-01-20', 0, 0),
(791, '681>791>', 'Funny Commercials', 'keyword', '', 'Funny Commercials', '', '', '', '0', '2010-01-20', 0, 0),
(792, '684>792>', 'ADCC', 'keyword', '', 'ADCC', '', '', '', '0', '2010-01-20', 0, 0),
(793, '684>793>', 'FCFF', 'keyword', '', 'FCFF', '', '', '', '0', '2010-01-20', 0, 0),
(794, '684>794>', 'IFL', 'keyword', '', 'IFL', '', '', '', '0', '2010-01-20', 0, 0),
(795, '684>795>', 'KOTC', 'keyword', '', 'KOTC', '', '', '', '0', '2010-01-20', 0, 0),
(817, '817>', 'Autos & Vehicles', 'keyword', '', 'Autos & Vehicles', '', '', '', '0', '2010-01-20', 0, 0),
(818, '818>', 'Music Videos', 'keyword', '', 'Music Videos', '', '', '', '0', '2010-01-20', 0, 0),
(819, '819>', 'Sports Videos', 'keyword', '', 'Sports Videos', '', '', '', '0', '2010-01-20', 0, 0),
(820, '820>', 'TV Shows', 'keyword', '', 'TV Shows', '', '', '', '0', '2010-01-20', 0, 0),
(821, '821>', 'Entertainment', 'keyword', '', 'Entertainment', '', '', '', '0', '2010-01-20', 0, 0),
(822, '822>', 'Movies', 'keyword', '', 'Movies', '', '', '', '0', '2010-01-20', 0, 0),
(823, '823>', 'Games Videos', 'keyword', '', 'Games Videos', '', '', '', '0', '2010-01-20', 0, 0),
(824, '824>', 'Celebrities Videos', 'keyword', '', 'Celebrities', '', '', '', '0', '2010-01-20', 0, 0),
(826, '826>', 'Magic & Illusion Videos', 'keyword', 'Lots of Magic & Illusion Videos', 'Magic & Illusion Videos', '', '', '', '0', '2010-01-20', 0, 0),
(827, '827>', 'Anime Videos', 'keyword', '', 'Anime Videos', '', '', '', '0', '2010-01-20', 0, 0),
(828, '828>', 'Cartoon Videos', 'keyword', '', 'Cartoon Videos', '', '', '', '0', '2010-01-20', 0, 0),
(829, '829>', 'Accident & crash Videos', 'keyword', 'Very Funny Accident Videos, Must Watch!!!', 'Accident Videos', '', '', '', '0', '2010-01-20', 0, 0),
(830, '826>830>', 'Criss Angel', 'keyword', '', 'criss angel', '', '', '', '0', '2010-01-20', 0, 0),
(831, '826>831>', 'david blaine', 'keyword', '', 'david blaine', '', '', '', '0', '2010-01-20', 0, 0),
(832, '826>832>', 'david copperfield', 'keyword', '', 'david copperfield', '', '', '', '0', '2010-01-20', 0, 0),
(833, '826>833>', 'harry anderson', 'keyword', '', 'harry anderson', '', '', '', '0', '2010-01-20', 0, 0),
(834, '826>834>', 'lance burton', 'keyword', '', 'lance burton', '', '', '', '0', '2010-01-20', 0, 0),
(835, '826>835>', 'siegried and roy', 'keyword', '', 'siegried and roy', '', '', '', '0', '2010-01-20', 0, 0),
(836, '826>836>', 'penn and teller', 'keyword', '', 'penn and teller', '', '', '', '0', '2010-01-20', 0, 0),
(837, '826>837>', 'harry blackstone', 'keyword', '', 'harry blackstone', '', '', '', '0', '2010-01-20', 0, 0),
(838, '826>838>', 'houdini', 'keyword', '', 'houdini', '', '', '', '0', '2010-01-20', 0, 0),
(839, '826>839>', 'card tricks', 'keyword', '', 'card tricks', '', '', '', '0', '2010-01-20', 0, 0),
(840, '826>840>', 'close up magic', 'keyword', '', 'close up magic', '', '', '', '0', '2010-01-20', 0, 0),
(841, '826>841>', 'coin tricks', 'keyword', '', 'coin tricks', '', '', '', '0', '2010-01-20', 0, 0),
(842, '826>842>', 'escape magic', 'keyword', '', 'escape magic', '', '', '', '0', '2010-01-20', 0, 0),
(843, '826>843>', 'magic levitation', 'keyword', '', 'magic levitation', '', '', '', '0', '2010-01-20', 0, 0),
(844, '826>844>', 'magic quick change', 'keyword', '', 'magic quick change', '', '', '', '0', '2010-01-20', 0, 0),
(845, '826>845>', 'magic slight of hand', 'keyword', '', 'magic slight of hand', '', '', '', '0', '2010-01-20', 0, 0),
(846, '826>846>', 'street magic', 'keyword', '', 'street magic', '', '', '', '0', '2010-01-20', 0, 0),
(847, '826>847>', 'magic disappearing', 'keyword', '', 'magic disappearing', '', '', '', '0', '2010-01-20', 0, 0),
(848, '818>848>', 'Pop Music', 'keyword', '', 'Pop Music', '', '', '', '0', '2010-01-20', 0, 0),
(849, '827>849>', 'avatar', 'keyword', '', 'avatar', '', '', '', '0', '2010-01-20', 0, 0),
(850, '827>850>', 'Azumanga Daioh', 'keyword', '', 'Azumanga Daioh', '', '', '', '0', '2010-01-20', 0, 0),
(851, '827>851>', 'Berserk', 'keyword', '', 'Berserk', '', '', '', '0', '2010-01-20', 0, 0),
(852, '827>852>', 'Bleach', 'keyword', '', 'Bleach', '', '', '', '0', '2010-01-20', 0, 0),
(853, '827>853>', 'Claymore', 'keyword', '', 'Claymore', '', '', '', '0', '2010-01-20', 0, 0),
(854, '827>854>', 'Darker Than Black', 'keyword', '', 'Darker Than Black', '', '', '', '0', '2010-01-20', 0, 0),
(855, '827>855>', 'Death Note', 'keyword', '', 'Death Note', '', '', '', '0', '2010-01-20', 0, 0),
(856, '827>856>', 'Devil May Cry', 'keyword', '', 'Devil May Cry', '', '', '', '0', '2010-01-20', 0, 0),
(857, '827>857>', 'DN Angel', 'keyword', '', 'DN Angel', '', '', '', '0', '2010-01-20', 0, 0),
(858, '827>858>', 'Escaflowne', 'keyword', '', 'Escaflowne', '', '', '', '0', '2010-01-20', 0, 0),
(859, '827>859>', 'Full Metal Alchemist', 'keyword', '', 'Full Metal Alchemist', '', '', '', '0', '2010-01-20', 0, 0),
(860, '827>860>', 'Hellsing', 'keyword', '', 'Hellsing', '', '', '', '0', '2010-01-20', 0, 0),
(861, '827>861>', 'Inuyasha', 'keyword', '', 'Inuyasha', '', '', '', '0', '2010-01-20', 0, 0),
(862, '827>862>', 'Kateikyoushi Hitman Reborn', 'keyword', '', 'Kateikyoushi Hitman Reborn', '', '', '', '0', '2010-01-20', 0, 0),
(863, '827>863>', 'Kaze no stigma', 'keyword', '', 'Kaze no stigma', '', '', '', '0', '2010-01-20', 0, 0),
(864, '827>864>', 'Kenichi', 'keyword', '', 'Kenichi', '', '', '', '0', '2010-01-20', 0, 0),
(866, '827>866>', 'Naruto Shippuden', 'keyword', '', 'Naruto Shippuden', '', '', '', '0', '2010-01-20', 0, 0),
(867, '827>867>', 'Naturo', 'keyword', '', 'Naturo', '', '', '', '0', '2010-01-20', 0, 0),
(868, '827>868>', 'negima', 'keyword', '', 'negima', '', '', '', '0', '2010-01-20', 0, 0),
(869, '827>869>', 'One Piece', 'keyword', '', 'One Piece', '', '', '', '0', '2010-01-20', 0, 0),
(870, '827>870>', 'Trigun', 'keyword', '', 'Trigun', '', '', '', '0', '2010-01-20', 0, 0),
(871, '827>871>', 'pokemon', 'keyword', '', 'pokemon', '', '', '', '0', '2010-01-20', 0, 0),
(872, '829>872>', 'accidents', 'keyword', '', 'accidents', '', '', '', '0', '2010-01-20', 0, 0),
(873, '829>873>', 'car accidents', 'keyword', '', 'car accidents', '', '', '', '0', '2010-01-20', 0, 0),
(874, '829>874>', 'motorcycle accidents', 'keyword', '', 'motorcycle accidents', '', '', '', '0', '2010-01-20', 0, 0),
(875, '829>875>', 'truck accident', 'keyword', '', 'truck accident', '', '', '', '0', '2010-01-20', 0, 0),
(876, '829>876>', 'fatal accident', 'keyword', '', 'fatal accident', '', '', '', '0', '2010-01-20', 0, 0),
(877, '829>877>', 'traffic accident', 'keyword', '', 'traffic accident', '', '', '', '0', '2010-01-20', 0, 0),
(878, '829>878>', 'air accident', 'keyword', '', 'air accident', '', '', '', '0', '2010-01-20', 0, 0),
(879, '829>879>', 'motorcycle accident', 'keyword', '', 'motorcycle accident', '', '', '', '0', '2010-01-20', 0, 0),
(880, '829>880>', 'air accident', 'keyword', '', 'air accident', '', '', '', '0', '2010-01-20', 0, 0),
(881, '881>', 'plane accident', 'keyword', '', 'plane accident', '', '', '', '0', '2010-01-20', 0, 0),
(882, '829>882>', 'deer accident', 'keyword', '', 'deer accident', '', '', '', '0', '2010-01-20', 0, 0),
(883, '829>883>', 'drunk driving accident', 'keyword', '', 'drunk driving accident', '', '', '', '0', '2010-01-20', 0, 0),
(884, '829>884>', 'work accident', 'keyword', '', 'work accident', '', '', '', '0', '2010-01-20', 0, 0),
(885, '829>885>', 'airplane accident', 'keyword', '', 'airplane accident', '', '', '', '0', '2010-01-20', 0, 0),
(886, '829>886>', 'train accident', 'keyword', '', 'train accident', '', '', '', '0', '2010-01-20', 0, 0),
(887, '829>887>', 'home accident', 'keyword', '', 'home accident', '', '', '', '0', '2010-01-20', 0, 0),
(888, '829>888>', 'four wheeler accident', 'keyword', '', 'four wheeler accident', '', '', '', '0', '2010-01-20', 0, 0),
(889, '829>889>', 'bad accident', 'keyword', '', 'bad accident', '', '', '', '0', '2010-01-20', 0, 0),
(890, '829>890>', 'fireworks accident', 'keyword', '', 'fireworks accident', '', '', '', '0', '2010-01-20', 0, 0),
(892, '829>892>', 'road accident', 'keyword', '', 'road accident', '', '', '', '0', '2010-01-20', 0, 0),
(893, '829>893>', 'bicycle accident', 'keyword', '', 'bicycle accident', '', '', '', '0', '2010-01-20', 0, 0),
(894, '829>894>', 'bike accident', 'keyword', '', 'bike accident', '', '', '', '0', '2010-01-20', 0, 0),
(895, '829>895>', 'police accident', 'keyword', '', 'police accident', '', '', '', '0', '2010-01-20', 0, 0),
(896, '829>896>', 'skateboard accident', 'keyword', '', 'skateboard accident', '', '', '', '0', '2010-01-20', 0, 0),
(897, '829>897>', 'plane crash', 'keyword', '', 'plane crash', '', '', '', '0', '2010-01-20', 0, 0),
(898, '829>898>', 'airplane crash', 'keyword', '', 'airplane crash', '', '', '', '0', '2010-01-20', 0, 0),
(899, '829>899>', 'air crash', 'keyword', '', 'air crash', '', '', '', '0', '2010-01-20', 0, 0),
(900, '829>900>', 'car crash', 'keyword', '', 'car crash', '', '', '', '0', '2010-01-20', 0, 0),
(901, '829>901>', 'jet crash', 'keyword', '', 'jet crash', '', '', '', '0', '2010-01-20', 0, 0),
(902, '827>902>', 'Ninja Scroll', 'keyword', '', 'Ninja Scroll', '', '', '', '0', '2010-01-20', 0, 0),
(903, '827>903>', 'Ouran High School Host Club', 'keyword', '', 'Ouran High School Host Club', '', '', '', '0', '2010-01-20', 0, 0),
(905, '827>905>', 'Paranoia Agent', 'keyword', '', 'Paranoia Agent', '', '', '', '0', '2010-01-20', 0, 0),
(906, '827>906>', 'Read or Die the TV', 'keyword', '', 'Read or Die the TV', '', '', '', '0', '2010-01-20', 0, 0),
(907, '827>907>', 'Samurai Champloo', 'keyword', '', 'Samurai Champloo', '', '', '', '0', '2010-01-20', 0, 0),
(908, '827>908>', 'Shingetsutan Tsukihime', 'keyword', '', 'Shingetsutan Tsukihime', '', '', '', '0', '2010-01-20', 0, 0),
(910, '827>910>', 'The Twelve Kingdoms', 'keyword', '', 'The Twelve Kingdoms', '', '', '', '0', '2010-01-20', 0, 0),
(911, '827>911>', 'Tide Line Blue', 'keyword', '', 'Tide Line Blue', '', '', '', '0', '2010-01-20', 0, 0),
(912, '827>912>', 'Tokyo Mew Mew', 'keyword', '', 'Tokyo Mew Mew', '', '', '', '0', '2010-01-20', 0, 0),
(913, '827>913>', 'Afro Samurai', 'keyword', '', 'Afro Samurai', '', '', '', '0', '2010-01-20', 0, 0),
(914, '827>914>', 'Darker Than Black', 'keyword', '', 'Darker Than Black', '', '', '', '0', '2010-01-20', 0, 0),
(915, '827>915>', 'Kemonozume', 'keyword', '', 'Kemonozume', '', '', '', '0', '2010-01-20', 0, 0),
(916, '827>916>', 'Kenichi', 'keyword', '', 'Kenichi', '', '', '', '0', '2010-01-20', 0, 0),
(917, '827>917>', 'Kono Minikuku', 'keyword', '', 'Kono Minikuku', '', '', '', '0', '2010-01-20', 0, 0),
(918, '827>918>', 'Love Hina', 'keyword', '', 'Love Hina', '', '', '', '0', '2010-01-20', 0, 0),
(919, '827>919>', 'Magikano', 'keyword', '', 'Magikano', '', '', '', '0', '2010-01-20', 0, 0),
(920, '827>920>', 'Magikano', 'keyword', '', 'Magikano', '', '', '', '0', '2010-01-20', 0, 0),
(921, '827>921>', 'Midori No Hibi', 'keyword', '', 'Midori No Hibi', '', '', '', '0', '2010-01-20', 0, 0),
(922, '827>922>', 'Kateikyoushi Hitman Reborn', 'keyword', '', 'Kateikyoushi Hitman Reborn', '', '', '', '0', '2010-01-20', 0, 0),
(923, '827>923>', 'I My Me! Strawberry Eggs', 'keyword', '', 'I My Me! Strawberry Eggs', '', '', '', '0', '2010-01-20', 0, 0),
(924, '827>924>', 'Gunslinger Girl', 'keyword', '', 'Gunslinger Girl', '', '', '', '0', '2010-01-20', 0, 0),
(925, '827>925>', 'Ghost in the Shell SAC', 'keyword', '', 'Ghost in the Shell SAC', '', '', '', '0', '2010-01-20', 0, 0),
(926, '827>926>', 'Gungrave', 'keyword', '', 'Gungrave', '', '', '', '0', '2010-01-20', 0, 0),
(927, '827>927>', 'GetBackers', 'keyword', '', 'GetBackers', '', '', '', '0', '2010-01-20', 0, 0),
(928, '827>928>', 'Fumoffu', 'keyword', '', 'Fumoffu', '', '', '', '0', '2010-01-20', 0, 0),
(929, '827>929>', 'Genshiken', 'keyword', '', 'Genshiken', '', '', '', '0', '2010-01-20', 0, 0),
(930, '827>930>', 'Full Metal Panic! The Second Raid', 'keyword', '', 'Full Metal Panic! The Second Raid', '', '', '', '0', '2010-01-20', 0, 0),
(931, '827>931>', 'Full Metal Alchemist', 'keyword', '', 'Full Metal Alchemist', '', '', '', '0', '2010-01-20', 0, 0),
(932, '827>932>', 'Fooly Cooly', 'keyword', '', 'Fooly Cooly', '', '', '', '0', '2010-01-20', 0, 0),
(933, '827>933>', 'Gantz', 'keyword', '', 'Gantz', '', '', '', '0', '2010-01-20', 0, 0),
(934, '827>934>', 'Full Metal Panic', 'keyword', '', 'Full Metal Panic', '', '', '', '0', '2010-01-20', 0, 0),
(935, '827>935>', 'Fruits Basket', 'keyword', '', 'Fruits Basket', '', '', '', '0', '2010-01-20', 0, 0),
(936, '827>936>', 'Fate Stay Night', 'keyword', '', 'Fate Stay Night', '', '', '', '0', '2010-01-20', 0, 0),
(937, '827>937>', 'Excel Saga', 'keyword', '', 'Excel Saga', '', '', '', '0', '2010-01-20', 0, 0),
(938, '827>938>', 'Eureka Seven', 'keyword', '', 'Eureka Seven', '', '', '', '0', '2010-01-20', 0, 0),
(939, '827>939>', 'Escaflowne', 'keyword', '', 'Escaflowne', '', '', '', '0', '2010-01-20', 0, 0),
(940, '827>940>', 'Ergo Proxy', 'keyword', '', 'Ergo Proxy', '', '', '', '0', '2010-01-20', 0, 0),
(941, '827>941>', 'Elfen Lied', 'keyword', '', 'Elfen Lied', '', '', '', '0', '2010-01-20', 0, 0),
(942, '827>942>', 'DN Angel', 'keyword', '', 'DN Angel', '', '', '', '0', '2010-01-20', 0, 0),
(943, '827>943>', 'Detective Conan', 'keyword', '', 'Detective Conan', '', '', '', '0', '2010-01-20', 0, 0),
(944, '827>944>', 'Demonbane', 'keyword', '', 'Demonbane', '', '', '', '0', '2010-01-20', 0, 0),
(945, '827>945>', 'Coyote Ragtime Show', 'keyword', '', 'Coyote Ragtime Show', '', '', '', '0', '2010-01-20', 0, 0),
(946, '827>946>', 'Cowboy Bebop', 'keyword', '', 'Cowboy Bebop', '', '', '', '0', '2010-01-20', 0, 0),
(947, '827>947>', 'Chobits', 'keyword', '', 'Chobits', '', '', '', '0', '2010-01-20', 0, 0),
(948, '827>948>', 'Boys Be', 'keyword', '', 'Boys Be', '', '', '', '0', '2010-01-20', 0, 0),
(949, '827>949>', 'Blue Gender', 'keyword', '', 'Blue Gender', '', '', '', '0', '2010-01-20', 0, 0),
(950, '827>950>', 'Blood+', 'keyword', '', 'Blood+', '', '', '', '0', '2010-01-20', 0, 0),
(951, '827>951>', 'Black Lagoon', 'keyword', '', 'Black Lagoon', '', '', '', '0', '2010-01-20', 0, 0),
(952, '827>952>', 'Amaenaideyo', 'keyword', '', 'Amaenaideyo', '', '', '', '0', '2010-01-20', 0, 0),
(953, '827>953>', 'Air Gear', 'keyword', '', 'Air Gear', '', '', '', '0', '2010-01-20', 0, 0),
(954, '827>954>', 'Legend of the Twilight', 'keyword', '', 'Legend of the Twilight', '', '', '', '0', '2010-01-20', 0, 0),
(955, '827>955>', 'hack roots', 'keyword', '', 'hack roots', '', '', '', '0', '2010-01-20', 0, 0),
(956, '827>956>', 'D Gray Man', 'keyword', '', 'D Gray Man', '', '', '', '0', '2010-01-20', 0, 0),
(957, '820>957>', 'Prison Break', 'keyword', '', 'Prison Break', '', '', '', '0', '2010-01-20', 0, 0),
(958, '820>958>', '24', 'keyword', '', '24', '', '', '', '0', '2010-01-20', 0, 0),
(959, '820>959>', 'Bones', 'keyword', '', 'Bones', '', '', '', '0', '2010-01-20', 0, 0),
(960, '820>960>', 'American Dad', 'keyword', '', 'American Dad', '', '', '', '0', '2010-01-20', 0, 0),
(961, '820>961>', 'Family Guy', 'keyword', '', 'Family Guy', '', '', '', '0', '2010-01-20', 0, 0),
(962, '820>962>', 'King of the Hill', 'keyword', '', 'King of the Hill', '', '', '', '0', '2010-01-20', 0, 0),
(963, '820>963>', 'K-Ville', 'keyword', '', 'K-Ville', '', '', '', '0', '2010-01-20', 0, 0),
(964, '820>964>', 'Hell''s Kitchen', 'keyword', '', 'Hell''s Kitchen', '', '', '', '0', '2010-01-20', 0, 0),
(965, '820>965>', 'MadTV', 'keyword', '', 'MadTV', '', '', '', '0', '2010-01-20', 0, 0),
(966, '820>966>', 'The Simpsons', 'keyword', '', 'The Simpsons', '', '', '', '0', '2010-01-20', 0, 0),
(967, '820>967>', 'Kitchen Nightmares', 'keyword', '', 'Kitchen Nightmares', '', '', '', '0', '2010-01-20', 0, 0),
(968, '820>968>', 'Alias', 'keyword', '', 'Alias', '', '', '', '0', '2010-01-20', 0, 0),
(969, '820>969>', 'The Bachelor', 'keyword', '', 'The Bachelor', '', '', '', '0', '2010-01-20', 0, 0),
(970, '820>970>', 'Big Shots', 'keyword', '', 'Big Shots', '', '', '', '0', '2010-01-20', 0, 0),
(971, '820>971>', 'Brothers and Sisters', 'keyword', '', 'Brothers and Sisters', '', '', '', '0', '2010-01-20', 0, 0),
(972, '820>972>', 'Carpoolers', 'keyword', '', 'Carpoolers', '', '', '', '0', '2010-01-20', 0, 0),
(973, '820>973>', 'Cavemen', 'keyword', '', 'Cavemen', '', '', '', '0', '2010-01-20', 0, 0),
(974, '820>974>', 'Dancing with the Stars', 'keyword', '', 'Dancing with the Stars', '', '', '', '0', '2010-01-20', 0, 0),
(975, '820>975>', 'Desperate Housewives', 'keyword', '', 'Desperate Housewives', '', '', '', '0', '2010-01-20', 0, 0),
(977, '820>977>', 'Extreme Makeover Home Edition', 'keyword', '', 'Extreme Makeover Home Edition', '', '', '', '0', '2010-01-20', 0, 0),
(978, '820>978>', 'Grey''s Anatomy', 'keyword', '', 'Grey''s Anatomy', '', '', '', '0', '2010-01-20', 0, 0),
(979, '820>979>', 'Lost', 'keyword', '', 'Lost', '', '', '', '0', '2010-01-20', 0, 0),
(980, '820>980>', 'Men In Trees', 'keyword', '', 'Men In Trees', '', '', '', '0', '2010-01-20', 0, 0),
(981, '820>981>', 'October Road', 'keyword', '', 'October Road', '', '', '', '0', '2010-01-20', 0, 0),
(982, '820>982>', 'private practise', 'keyword', '', 'private practise', '', '', '', '0', '2010-01-20', 0, 0),
(983, '820>983>', 'Bad Girls Club', 'keyword', '', 'Bad Girls Club', '', '', '', '0', '2010-01-20', 0, 0),
(984, '820>984>', 'Ugly Betty', 'keyword', '', 'Ugly Betty', '', '', '', '0', '2010-01-20', 0, 0),
(985, '985>', 'Big Brother', 'keyword', '', 'big brother', '', '', '', '0', '2010-01-20', 0, 0),
(986, '820>986>', 'big brother', 'keyword', '', 'big brother', '', '', '', '0', '2010-01-20', 0, 0),
(987, '987>', 'Youtube Celebrities', 'keyword', 'Youtube celebrities', 'Youtube celebrities', '', '', '0', '0', '2010-01-20', 0, 0),
(988, '987>988>', 'BaratsAndBereta', 'author', 'BaratsAndBereta', '', 'BaratsAndBereta', '', '0', '0', '2010-01-20', 0, 0),
(989, '987>989>', 'ijustine', 'author', 'ijustine', '', 'ijustine', '', '0', '0', '2010-01-20', 0, 0),
(990, '987>990>', 'LikeTotallyAwesome', 'author', 'LikeTotallyAwesome', '', 'LikeTotallyAwesome', '', '0', '0', '2010-01-20', 0, 0),
(991, '987>991>', 'kevjumba', 'author', 'kevjumba', '', 'kevjumba', '', '0', '0', '2010-01-20', 0, 0),
(992, '987>992>', 'SHAYTARDS', 'author', 'SHAYTARDS', '', 'SHAYTARDS', '', '0', '0', '2010-01-20', 0, 0),
(993, '987>993>', 'LisaNova', 'author', 'LisaNova', '', 'LisaNova', '', '0', '0', '2010-01-20', 0, 0),
(994, '987>994>', 'lonelygirl15', 'author', 'lonelygirl15', '', 'lonelygirl15', '', '0', '0', '2010-01-20', 0, 0),
(995, '987>995>', 'VenetianPrincess', 'author', 'VenetianPrincess', '', 'VenetianPrincess', '', '0', '0', '2010-01-20', 0, 0),
(996, '987>996>', 'mariedigby', 'author', 'mariedigby', '', 'mariedigby', '', '0', '0', '2010-01-20', 0, 0),
(997, '987>997>', 'WilliamSledd', 'author', 'WilliamSledd', '', 'WilliamSledd', '', '0', '0', '2010-01-20', 0, 0),
(998, '987>998>', 'TayZonday', 'author', 'TayZonday', '', 'TayZonday', '', '0', '0', '2010-01-20', 0, 0),
(999, '999>', 'Misc Playlists', 'playlist_id', 'Misc Playlists', '', '', 'Eric16nigga', '3B4866B62FAACC60', '0', '2010-01-20', 0, 0),
(1000, '999>1000>', 'Ugly Celebrities', 'playlist_id', 'Ugly Celebrities', '', '', '45493601', '278F6270B22A7DE9', '0', '2010-01-20', 0, 0),
(1001, '999>1001>', 'Latest Movie Trailers', 'playlist_id', 'Latest Movie Trailers', '', '', 'trailers', 'F604B547DF2ABF92', '0', '2010-01-20', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pt3_config`
--

CREATE TABLE IF NOT EXISTS `pt3_config` (
  `name` varchar(150) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pt3_config`
--

INSERT INTO `pt3_config` (`name`, `value`) VALUES
('pt_version', '3.32');

-- --------------------------------------------------------

--
-- Table structure for table `pt3_filter`
--

CREATE TABLE IF NOT EXISTS `pt3_filter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=116 ;

--
-- Dumping data for table `pt3_filter`
--

INSERT INTO `pt3_filter` (`id`, `keyword`) VALUES
(1, 'fights'),
(2, 'upskirt'),
(3, 'porn'),
(4, 'nude'),
(5, 'sex'),
(6, 'tit'),
(7, 'busty'),
(8, 'nipslip'),
(9, 'boob'),
(10, 'pornstar'),
(11, 'nipple'),
(12, 'ass'),
(13, 'underwear'),
(14, 'desnuda'),
(15, 'pussy'),
(16, 'topless'),
(17, 'downblouse'),
(18, 'threesome'),
(19, 'bangbus'),
(20, 'blowjob'),
(21, 'panties'),
(22, 'thong'),
(23, 'cleavage'),
(24, 'bikini'),
(25, 'naked'),
(26, 'clit'),
(27, 'clitoris'),
(28, 'lesbian'),
(29, 'lesben'),
(30, 'pantyhose'),
(31, 'strip'),
(32, 'milf'),
(33, 'bra'),
(34, 'penthouse'),
(35, 'shemale'),
(36, 'slut'),
(37, 'breast'),
(38, 'fucking'),
(39, 'fuck'),
(40, 'cum'),
(41, 'horny'),
(42, 'lingerie'),
(43, 'youporn'),
(44, 'redtube'),
(45, 'x-rated'),
(46, 'erotic'),
(47, 'masturbation'),
(48, 'masturbating'),
(49, 'masterbate'),
(50, 'undergarment'),
(51, 'undergarments'),
(52, 'fetish'),
(53, 'nudist'),
(54, 'mature'),
(55, 'stipping'),
(56, 'booty'),
(57, 'butt'),
(58, 'bum'),
(59, 'backside'),
(60, 'naken'),
(61, 'jizz'),
(62, 'sperm'),
(63, 'bangbros'),
(64, 'jacking'),
(65, 'dick'),
(66, 'lesbi'),
(67, 'xxx'),
(68, 'lapdance'),
(69, 'nigger'),
(70, 'nigga'),
(71, 'escort'),
(72, 'dildo'),
(73, 'bondage'),
(74, 'gay'),
(75, 'homo'),
(76, 'masseuse'),
(77, 'pissing'),
(78, 'pee'),
(79, 'fingering'),
(80, 'orgasm'),
(81, 'climax'),
(82, 'vagina'),
(83, 'pubes'),
(84, 'pubic'),
(85, 'rape'),
(86, 'corset'),
(87, 'massage'),
(88, 'dominatrix'),
(89, 'ladyboy'),
(90, 'prostitute'),
(91, 'whore'),
(92, 'anal'),
(93, 'cock'),
(94, 'casino'),
(95, 'poker'),
(96, 'gambling'),
(97, 'betting'),
(98, 'roulette'),
(99, 'fight'),
(100, 'bloody'),
(101, 'uncensored'),
(102, 'slave'),
(103, 'genital'),
(104, 'buceta'),
(105, 'xoxota'),
(106, 'xana'),
(107, 'xota'),
(108, 'cooch'),
(109, 'cunt'),
(110, 'punani'),
(111, 'gostosa'),
(112, 'calcinha'),
(113, 'bunda'),
(114, 'bundao'),
(115, 'geje');

-- --------------------------------------------------------

--
-- Table structure for table `pt3_filter_match`
--

CREATE TABLE IF NOT EXISTS `pt3_filter_match` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pt3_guest_log`
--

CREATE TABLE IF NOT EXISTS `pt3_guest_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=428 ;

--
-- Dumping data for table `pt3_guest_log`
--

INSERT INTO `pt3_guest_log` (`id`, `time`, `ip`) VALUES
(427, 1504694717, '39.48.9.22'),
(426, 1504691627, '198.54.126.97');

-- --------------------------------------------------------

--
-- Table structure for table `pt3_links`
--

CREATE TABLE IF NOT EXISTS `pt3_links` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `pt3_links`
--

INSERT INTO `pt3_links` (`id`, `title`, `url`) VALUES
(1, 'Your Own Link like this one', 'http://www.google.com');

-- --------------------------------------------------------

--
-- Table structure for table `pt3_mail_notification_log`
--

CREATE TABLE IF NOT EXISTS `pt3_mail_notification_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purpose` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `message` text NOT NULL,
  `subject` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pt3_main_menu`
--

CREATE TABLE IF NOT EXISTS `pt3_main_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `class` varchar(100) NOT NULL,
  `new_window` tinyint(1) NOT NULL,
  `order` int(2) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `pt3_main_menu`
--

INSERT INTO `pt3_main_menu` (`id`, `title`, `url`, `class`, `new_window`, `order`, `time`) VALUES
(1, 'Top Rated', 'feed/top_rated.html', 'medium', 0, 1, 1263284396),
(2, 'Top Favorites', 'feed/top_favorites.html', 'large', 0, 2, 1263284535),
(3, 'Most Viewed', 'feed/most_viewed.html', 'medium', 0, 3, 1263284535),
(4, 'Most Recent', 'feed/most_recent.html', 'medium', 0, 4, 1263284535),
(5, 'Most Discussed', 'feed/most_discussed.html', 'medium', 0, 5, 1263284535),
(7, 'Most Responded', 'feed/most_responded.html', 'large', 0, 7, 1263284535),
(8, 'Recently Featured', 'feed/recently_featured.html', 'large', 0, 8, 1263284535);

-- --------------------------------------------------------

--
-- Table structure for table `pt3_pages`
--

CREATE TABLE IF NOT EXISTS `pt3_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) NOT NULL,
  `page_sef_url` varchar(255) NOT NULL,
  `page_content` text NOT NULL,
  `page_status` tinyint(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pt3_search_log`
--

CREATE TABLE IF NOT EXISTS `pt3_search_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_term` varchar(255) NOT NULL,
  `ip_address` varchar(25) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `pt3_search_log`
--

INSERT INTO `pt3_search_log` (`id`, `search_term`, `ip_address`, `time`) VALUES
(1, 'cool', '39.48.61.86', 1503254815),
(2, 'daniyal arain', '61.5.130.234', 1503716886),
(3, 'cooking recipes', '39.48.12.35', 1504258804),
(4, 'googlr', '39.48.17.206', 1504264282),
(5, 'Accident Videos', '45.56.159.254', 1504522049),
(6, 'tvile', '104.236.74.212', 1504522186),
(7, 'Chobits', '45.56.159.254', 1504522834),
(8, 'Search for videos...', '45.56.159.254', 1504523572),
(9, 'Search for videos...', '45.56.159.254', 1504523577);

-- --------------------------------------------------------

--
-- Table structure for table `pt3_videos`
--

CREATE TABLE IF NOT EXISTS `pt3_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` varchar(15) NOT NULL,
  `title` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `tube_type` char(2) NOT NULL,
  `keywords` text NOT NULL,
  `author` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `view_count` int(11) NOT NULL,
  `comment_count` int(11) NOT NULL,
  `favorite_count` int(11) NOT NULL,
  `rating_count` int(11) NOT NULL,
  `rating` float(4,2) NOT NULL,
  `response_count` int(11) NOT NULL,
  `publish_time` int(11) NOT NULL,
  `last_viewed` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `video_id` (`video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pt3_videos_upload_log`
--

CREATE TABLE IF NOT EXISTS `pt3_videos_upload_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `youtube_id` varchar(15) NOT NULL,
  `title` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_upload` int(11) NOT NULL,
  `country_code` char(2) NOT NULL,
  `ip_addr` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pt3_video_comments`
--

CREATE TABLE IF NOT EXISTS `pt3_video_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vid` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `posted` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`),
  KEY `vid` (`vid`(200))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pt3_video_comments`
--

INSERT INTO `pt3_video_comments` (`id`, `vid`, `user`, `comment`, `posted`) VALUES
(1, 'g5RM5StrMXY', 'hi', 'hello', 1504272456);

-- --------------------------------------------------------

--
-- Table structure for table `pt3_video_tags`
--

CREATE TABLE IF NOT EXISTS `pt3_video_tags` (
  `tag` varchar(250) NOT NULL DEFAULT '',
  `quantity` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pt3_video_tags`
--

INSERT INTO `pt3_video_tags` (`tag`, `quantity`) VALUES
('cool', 2),
('authenticate', 3),
('blog', 29),
('wordpress', 12),
('wp', 54),
('administrator', 25),
('joomla', 1),
('includes', 30),
('products', 3),
('product', 3),
('category', 3),
('templates', 5),
('big', 2),
('brother', 2),
('daniyal', 1),
('arain', 1),
('admin', 12),
('uploads', 1),
('upload.php?type=file', 4),
('uploadify.php?folder=', 1),
('chat', 30),
('temp', 62),
('en', 1),
('radiochat', 2),
('flashchat', 6),
('social', 1),
('sohbet', 1),
('e', 1),
('dinar', 1),
('stjepkovica', 1),
('flash_chat', 1),
('e107_plugins', 1),
('onlineinfo_menutemp', 1),
('shbt', 1),
('haces', 1),
('bebica', 1),
('islamski', 1),
('sem', 1),
('registo', 1),
('chat55', 1),
('2', 1),
('xfchat', 1),
('chatroom', 1),
('radiodalmatino', 1),
('v6', 1),
('0', 1),
('8', 1),
('deface_flashchat', 1),
('page', 1),
('video', 4),
('watch', 1),
('tutaf', 1),
('baixar', 1),
('how', 1),
('install', 1),
('free', 2),
('music', 1),
('Search', 4),
('list', 1),
('jingyan', 1),
('description', 1),
('28709', 1),
('posts', 1),
('m', 1),
('home', 1),
('cooking', 1),
('recipes', 1),
('Cartoon', 1),
('videos', 8),
('googlr', 1),
('air', 5),
('Accident', 7),
('airplane', 1),
('TJJidnWFpLI', 1),
('FUNNY', 1),
('accidents', 3),
('bad', 1),
('wishlist', 1),
('Anime', 2),
('Autos', 1),
('&', 1),
('Vehicles', 1),
('crash', 1),
('Youtube', 1),
('celebrities', 1),
('Chobits', 2),
('tvile', 1),
('Games', 1),
('orderby', 1),
('viewCount', 1),
('videos...', 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
