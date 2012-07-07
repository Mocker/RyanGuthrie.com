-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 05, 2012 at 09:36 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `getxp`
--

-- --------------------------------------------------------

--
-- Table structure for table `bulletins`
--

DROP TABLE IF EXISTS `bulletins`;
CREATE TABLE IF NOT EXISTS `bulletins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `center_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(40) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `bulletins`
--

INSERT INTO `bulletins` (`id`, `center_id`, `network_id`, `profile_id`, `posted`, `title`, `content`) VALUES
(2, 1, NULL, NULL, '2012-02-27 16:04:15', 'First Bullet', 'Here is a message\nabout stuff'),
(3, NULL, 1, NULL, '2012-02-27 19:15:25', 'Test', 'First Networks first bulletin');

-- --------------------------------------------------------

--
-- Table structure for table `centers`
--

DROP TABLE IF EXISTS `centers`;
CREATE TABLE IF NOT EXISTS `centers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(20) NOT NULL,
  `street` varchar(50) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `courts` int(11) DEFAULT '0',
  `surfaces` int(11) DEFAULT NULL,
  `features` varchar(10) NOT NULL DEFAULT '0000000000',
  `sport_id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `status` varchar(90) NOT NULL DEFAULT '',
  `profile_pic` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `centers`
--

INSERT INTO `centers` (`id`, `name`, `description`, `city`, `state`, `street`, `zip`, `phone`, `courts`, `surfaces`, `features`, `sport_id`, `owner`, `status`, `profile_pic`) VALUES
(1, 'Central Centerr', 'A center central to whateverville', 'Whateverville', 'fdsfsd', 'SomeLane', '97402', '15413577487', -1, NULL, '0000000000', 1, 1, 'Getting ready for a tournament!', 'center_1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `center_members`
--

DROP TABLE IF EXISTS `center_members`;
CREATE TABLE IF NOT EXISTS `center_members` (
  `center_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'member' COMMENT 'fan,member,staff',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `note` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `center_members`
--


-- --------------------------------------------------------

--
-- Table structure for table `center_reviews`
--

DROP TABLE IF EXISTS `center_reviews`;
CREATE TABLE IF NOT EXISTS `center_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `center_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `center_reviews_id` int(11) DEFAULT NULL,
  `review` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `center_reviews`
--


-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

DROP TABLE IF EXISTS `chats`;
CREATE TABLE IF NOT EXISTS `chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `center_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `profile_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` varchar(90) NOT NULL,
  `from_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='chat room messages' AUTO_INCREMENT=31 ;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `center_id`, `network_id`, `profile_id`, `room_id`, `date`, `message`, `from_name`) VALUES
(1, 1, NULL, 1, NULL, '2012-02-27 11:00:22', 'test', 'Tennis Ryan'),
(2, 1, NULL, 1, NULL, '2012-02-27 11:05:53', 'test', 'Tennis Ryan'),
(3, 1, NULL, 1, NULL, '2012-02-27 11:06:51', 'test 3', 'Tennis Ryan'),
(4, 1, NULL, 1, NULL, '2012-02-27 11:07:32', 'test 4', 'Tennis Ryan'),
(5, 1, NULL, 1, NULL, '2012-02-27 11:07:42', 'test 5', 'Tennis Ryan'),
(6, 1, NULL, 1, NULL, '2012-02-27 11:07:44', 'test 6', 'Tennis Ryan'),
(7, 1, NULL, 1, NULL, '2012-02-27 11:07:46', 'test 7', 'Tennis Ryan'),
(8, 1, NULL, 1, NULL, '2012-02-27 11:07:51', 'test 8', 'Tennis Ryan'),
(9, 1, NULL, 1, NULL, '2012-02-27 11:07:53', 'test 9', 'Tennis Ryan'),
(10, 1, NULL, 1, NULL, '2012-02-27 11:07:56', 'test 10', 'Tennis Ryan'),
(11, 1, NULL, 1, NULL, '2012-02-27 11:08:00', 'test 11', 'Tennis Ryan'),
(12, 1, NULL, 1, NULL, '2012-02-27 11:08:03', 'sf', 'Tennis Ryan'),
(13, 1, NULL, 1, NULL, '2012-02-27 11:08:06', 'dfgf', 'Tennis Ryan'),
(14, 1, NULL, 1, NULL, '2012-02-27 11:08:09', 'ghfgh', 'Tennis Ryan'),
(15, 1, NULL, 1, NULL, '2012-02-27 11:08:13', 'vngfhg', 'Tennis Ryan'),
(16, 1, NULL, 1, NULL, '2012-02-27 11:08:16', 'esfdsf', 'Tennis Ryan'),
(17, 1, NULL, 1, NULL, '2012-02-27 11:08:18', 'dfgfdgfdg', 'Tennis Ryan'),
(18, 1, NULL, 1, NULL, '2012-02-27 11:08:20', 'ghgfh', 'Tennis Ryan'),
(19, 1, NULL, 1, NULL, '2012-02-27 11:08:23', 'bvnvb', 'Tennis Ryan'),
(20, 1, NULL, 1, NULL, '2012-02-27 11:08:54', 'bvnvb', 'Tennis Ryan'),
(21, 1, NULL, 1, NULL, '2012-02-27 11:08:57', 'bvnvb', 'Tennis Ryan'),
(22, 1, NULL, 1, NULL, '2012-02-27 11:09:00', 'bvnvb', 'Tennis Ryan'),
(23, 1, NULL, 1, NULL, '2012-02-27 11:09:02', 'bvnvb', 'Tennis Ryan'),
(24, 1, NULL, 1, NULL, '2012-02-27 11:09:04', 'bvnvb', 'Tennis Ryan'),
(25, 1, NULL, 1, NULL, '2012-02-27 11:09:06', 'bvnvb', 'Tennis Ryan'),
(26, 1, NULL, 1, NULL, '2012-02-27 11:09:08', 'bvnvb', 'Tennis Ryan'),
(27, 1, NULL, 1, NULL, '2012-02-27 11:09:09', 'bvnvb', 'Tennis Ryan'),
(28, 1, NULL, 1, NULL, '2012-02-27 11:09:10', 'bvnvb', 'Tennis Ryan'),
(29, NULL, 1, 1, NULL, '2012-02-27 11:15:03', 'Hello First Network', 'Tennis Ryan'),
(30, NULL, 1, 1, NULL, '2012-02-27 11:15:11', 'How is everyone today?', 'Tennis Ryan');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `center_id` int(11) DEFAULT NULL,
  `reply_id` int(11) DEFAULT NULL,
  `from_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment` text NOT NULL,
  `poster_name` varchar(30) NOT NULL,
  `asdf` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `profile_id`, `network_id`, `center_id`, `reply_id`, `from_id`, `date`, `comment`, `poster_name`, `asdf`) VALUES
(1, 2, NULL, NULL, NULL, 1, '2012-02-27 08:10:33', 'My first comment', 'Tennis Ryan', NULL),
(2, 2, NULL, NULL, NULL, 1, '2012-02-27 08:19:18', 'Hi there', 'Tennis Ryan', NULL),
(3, 2, NULL, NULL, NULL, 1, '2012-02-27 08:19:59', 'Third comment!', 'Tennis Ryan', NULL),
(4, 2, NULL, NULL, NULL, 1, '2012-02-27 08:46:50', 'I call fourth!', 'Tennis Ryan', NULL),
(5, NULL, NULL, 1, NULL, 1, '2012-03-05 02:29:50', 'dsfd', 'Tennis Ryan', NULL),
(6, NULL, NULL, 1, NULL, 1, '2012-03-05 02:32:22', 'Review rabble rabble', 'Tennis Ryan', NULL),
(7, NULL, 1, NULL, NULL, 1, '2012-03-05 02:34:16', 'test', 'Tennis Ryan', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `competitions`
--

DROP TABLE IF EXISTS `competitions`;
CREATE TABLE IF NOT EXISTS `competitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `network_id` int(11) NOT NULL,
  `center_id` int(11) DEFAULT NULL,
  `title` varchar(70) NOT NULL,
  `location` text,
  `zip` varchar(10) NOT NULL,
  `fee` varchar(60) DEFAULT NULL,
  `fee_paypal` varchar(60) DEFAULT NULL COMMENT 'paypal email for a button to accept payments',
  `organizer_id` int(11) DEFAULT NULL COMMENT 'user id of organizer',
  `approved` tinyint(1) NOT NULL COMMENT 'if network approved this ',
  `description` text NOT NULL,
  `deadline` datetime NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `competitions`
--


-- --------------------------------------------------------

--
-- Table structure for table `competitors`
--

DROP TABLE IF EXISTS `competitors`;
CREATE TABLE IF NOT EXISTS `competitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) DEFAULT NULL,
  `player_name` varchar(50) DEFAULT NULL COMMENT 'optional name of competitor - can be used for manual entries',
  `league_id` int(11) DEFAULT NULL,
  `approved` int(11) NOT NULL DEFAULT '0',
  `members` int(11) NOT NULL DEFAULT '1' COMMENT 'number of ppl on team',
  `profile2_id` int(11) NOT NULL COMMENT '2nd player id for duo''s',
  `level` varchar(25) DEFAULT NULL COMMENT 'beginner,advanced etc',
  `tournament_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `league_profile` (`league_id`,`profile_id`,`tournament_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `competitors`
--

INSERT INTO `competitors` (`id`, `profile_id`, `player_name`, `league_id`, `approved`, `members`, `profile2_id`, `level`, `tournament_id`) VALUES
(2, 1, 'Tennis Ryan', 2, 0, 1, 0, NULL, NULL),
(3, 1, 'Tennis Ryan', 1, 0, 1, 0, NULL, NULL),
(4, 0, 'Tennis Pro', NULL, 1, 1, 0, NULL, 1),
(9, NULL, 'Rob', NULL, 1, 1, 0, NULL, 1),
(10, NULL, 'Kevin', NULL, 1, 1, 0, NULL, 1),
(11, NULL, 'Steve', NULL, 1, 1, 0, NULL, 1),
(12, NULL, 'Joe', NULL, 1, 1, 0, NULL, 1),
(13, NULL, 'Chris', NULL, 1, 1, 0, NULL, 1),
(14, NULL, 'Pedro', NULL, 1, 1, 0, NULL, 1),
(15, NULL, 'Dan', NULL, 1, 1, 0, NULL, 1),
(16, NULL, 'uno', NULL, 1, 1, 0, NULL, 2),
(17, NULL, 'dos', NULL, 1, 1, 0, NULL, 2),
(18, NULL, 'tres', NULL, 1, 1, 0, NULL, 2),
(19, NULL, 'cuatro', NULL, 1, 1, 0, NULL, 2),
(20, NULL, 'cinco', NULL, 1, 1, 0, NULL, 2),
(21, NULL, 'seis', NULL, 1, 1, 0, NULL, 2),
(22, NULL, 'siete', NULL, 1, 1, 0, NULL, 2),
(23, 1, 'Tennis Ryan', NULL, 1, 1, 0, NULL, 3),
(24, 1, 'Tennis Ryan', NULL, 1, 1, 0, NULL, 4),
(25, NULL, 'Jimmy', NULL, 1, 1, 0, NULL, 4),
(26, NULL, 'Ralph', NULL, 1, 1, 0, NULL, 4),
(27, NULL, 'Steve', NULL, 1, 1, 0, NULL, 4),
(28, NULL, 'Joe', NULL, 1, 1, 0, NULL, 4),
(29, NULL, 'Pedro', NULL, 1, 1, 0, NULL, 4),
(30, NULL, 'Kim', NULL, 1, 1, 0, NULL, 5),
(31, NULL, 'Joey', NULL, 1, 1, 0, NULL, 5),
(32, NULL, 'Rebecca', NULL, 1, 1, 0, NULL, 5),
(33, NULL, 'Ivette', NULL, 1, 1, 0, NULL, 5),
(34, NULL, 'Sasha', NULL, 1, 1, 0, NULL, 5),
(35, NULL, 'Paula', NULL, 1, 1, 0, NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `courts`
--

DROP TABLE IF EXISTS `courts`;
CREATE TABLE IF NOT EXISTS `courts` (
  `center_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT 'optional name of court',
  `description` text COMMENT 'optional description',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='list of courts/rooms/fields whatever for each center' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `courts`
--

INSERT INTO `courts` (`center_id`, `name`, `description`, `id`) VALUES
(1, 'Big Room', 'it''s a big room for playing sports			', 1),
(1, 'Small Room', 'it''s a small room for playing small sports					', 2);

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

DROP TABLE IF EXISTS `friends`;
CREATE TABLE IF NOT EXISTS `friends` (
  `profile_id` int(11) NOT NULL,
  `profile2_id` int(11) NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sport_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`profile_id`, `profile2_id`, `added`, `sport_id`, `id`) VALUES
(2, 1, '0000-00-00 00:00:00', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

DROP TABLE IF EXISTS `friend_requests`;
CREATE TABLE IF NOT EXISTS `friend_requests` (
  `profile_id` int(11) NOT NULL,
  `profile2_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` text NOT NULL,
  `sport_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profiles` (`profile_id`,`profile2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='pending friend requests' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `friend_requests`
--


-- --------------------------------------------------------

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(30) NOT NULL,
  `img` varchar(70) NOT NULL,
  `description` text NOT NULL,
  `link` varchar(90) DEFAULT NULL,
  `embed` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `games`
--


-- --------------------------------------------------------

--
-- Table structure for table `leagues`
--

DROP TABLE IF EXISTS `leagues`;
CREATE TABLE IF NOT EXISTS `leagues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) NOT NULL COMMENT 'hosts profile id',
  `center_id` int(11) DEFAULT NULL COMMENT 'venue center id',
  `network_id` int(11) DEFAULT NULL,
  `max_competitors` int(11) DEFAULT NULL,
  `type` varchar(40) DEFAULT NULL,
  `format` varchar(40) DEFAULT NULL,
  `match_type` varchar(40) DEFAULT NULL,
  `championship` varchar(40) DEFAULT NULL,
  `registration` varchar(15) DEFAULT 'open' COMMENT 'open,closed or invite',
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `fee` varchar(40) DEFAULT NULL,
  `age_min` int(11) DEFAULT NULL,
  `age_max` int(11) DEFAULT NULL,
  `name` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='League data' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `leagues`
--

INSERT INTO `leagues` (`id`, `profile_id`, `center_id`, `network_id`, `max_competitors`, `type`, `format`, `match_type`, `championship`, `registration`, `start`, `end`, `fee`, `age_min`, `age_max`, `name`) VALUES
(1, 1, 1, NULL, 3, 'auto_rr', 'single', 'Best of 3', 'auto_group', 'open', '2011-12-09 16:12:00', '2011-12-09 16:12:00', '0', 1, 30, 'Fancy Pants'),
(2, 1, NULL, 1, 15, 'auto_rr', 'single', 'Best of 3', 'auto_group', 'open', '2011-12-09 16:45:00', '2011-12-09 16:45:00', '15', NULL, NULL, 'Net League');

-- --------------------------------------------------------

--
-- Table structure for table `mains`
--

DROP TABLE IF EXISTS `mains`;
CREATE TABLE IF NOT EXISTS `mains` (
  `whut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mains`
--


-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
CREATE TABLE IF NOT EXISTS `matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p1_id` int(11) DEFAULT NULL,
  `p1_name` varchar(45) NOT NULL,
  `p2_id` int(11) DEFAULT NULL,
  `p2_name` varchar(45) NOT NULL,
  `p1_score` varchar(20) NOT NULL,
  `p2_score` varchar(20) NOT NULL,
  `winner` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time` datetime DEFAULT NULL COMMENT 'when match occurs',
  `league_id` int(11) NOT NULL,
  `tournament_id` int(11) DEFAULT NULL,
  `round` int(11) DEFAULT NULL,
  `pool` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='individual match data for league competitions' AUTO_INCREMENT=33 ;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `p1_id`, `p1_name`, `p2_id`, `p2_name`, `p1_score`, `p2_score`, `winner`, `created`, `time`, `league_id`, `tournament_id`, `round`, `pool`) VALUES
(6, NULL, 'Tennis Pro', NULL, 'Rob', '', '', 0, '0000-00-00 00:00:00', NULL, 0, 1, 1, NULL),
(7, NULL, 'Kevin', NULL, 'Steve', '', '', 0, '0000-00-00 00:00:00', NULL, 0, 1, 1, NULL),
(8, NULL, 'Joe', NULL, 'Chris', '', '', 0, '0000-00-00 00:00:00', NULL, 0, 1, 1, NULL),
(9, NULL, 'Pedro', NULL, 'Dan', '', '', 0, '0000-00-00 00:00:00', NULL, 0, 1, 1, NULL),
(10, NULL, 'uno', NULL, 'dos', '2', '1', 1, '0000-00-00 00:00:00', NULL, 0, 2, 1, NULL),
(11, NULL, 'tres', NULL, 'cuatro', '34', '34', 1, '0000-00-00 00:00:00', NULL, 0, 2, 1, NULL),
(12, NULL, 'cinco', NULL, 'seis', '6', '2', 1, '0000-00-00 00:00:00', NULL, 0, 2, 1, NULL),
(13, NULL, 'siete', NULL, 'BYE', '1', '0', 1, '0000-00-00 00:00:00', NULL, 0, 2, 1, NULL),
(14, NULL, 'uno', NULL, 'tres', '4', '1', 1, '0000-00-00 00:00:00', NULL, 0, 2, 2, NULL),
(15, NULL, 'cinco', NULL, 'siete', '1', '2', 1, '0000-00-00 00:00:00', NULL, 0, 2, 2, NULL),
(16, NULL, 'uno', NULL, 'cinco', '4', '5', 1, '0000-00-00 00:00:00', NULL, 0, 2, 3, NULL),
(17, 1, 'Tennis Ryan', NULL, 'Jimmy', '2', '1', 1, '0000-00-00 00:00:00', NULL, 0, 4, 1, NULL),
(18, NULL, 'Ralph', NULL, 'Steve', '0', '5', 1, '0000-00-00 00:00:00', NULL, 0, 4, 1, NULL),
(19, NULL, 'Joe', NULL, 'Pedro', '4', '6', 1, '0000-00-00 00:00:00', NULL, 0, 4, 1, NULL),
(26, 1, 'Tennis Ryan', NULL, 'Ralph', '5', '1', 1, '0000-00-00 00:00:00', NULL, 0, 4, 2, NULL),
(27, NULL, 'Joe', NULL, 'BYE', '1', '0', 1, '0000-00-00 00:00:00', NULL, 0, 4, 2, NULL),
(28, NULL, 'Jimmy', NULL, 'Steve', '5', '3', 1, '0000-00-00 00:00:00', NULL, 0, 4, 2, 2),
(29, NULL, 'Pedro', NULL, 'BYE', '1', '0', 1, '0000-00-00 00:00:00', NULL, 0, 4, 2, 2),
(30, 1, 'Tennis Ryan', NULL, 'Joe', '5', '4', 1, '0000-00-00 00:00:00', NULL, 0, 4, 3, NULL),
(31, NULL, 'Jimmy', NULL, 'Pedro', '3', '4', 1, '0000-00-00 00:00:00', NULL, 0, 4, 3, 2),
(32, 1, 'Tennis Ryan', NULL, 'Jimmy', '1', '345', 1, '0000-00-00 00:00:00', NULL, 0, 4, 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `message` text NOT NULL,
  `profile_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read` int(11) NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT 'mail' COMMENT '"mail","chat","system"',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='messaging system' AUTO_INCREMENT=40 ;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message`, `profile_id`, `to_id`, `sent`, `read`, `type`, `id`) VALUES
('What the hell', 2, 1, '2011-12-05 14:10:23', 1, 'message', 6),
('Test hey', 1, 2, '2011-12-05 14:29:05', 1, 'message', 7),
('woop', 2, 1, '2011-12-05 14:29:28', 1, 'message', 8),
('yo im', 1, 2, '2011-12-09 11:51:07', 1, 'im', 10),
('and another', 1, 2, '2011-12-09 12:20:12', 1, 'im', 11),
('sup dawg', 2, 1, '2011-12-09 12:41:07', 1, 'im', 12),
('new im', 2, 1, '2011-12-09 12:49:45', 1, 'im', 13),
('new im2', 2, 1, '2011-12-09 12:58:19', 1, 'im', 14),
('heyo', 1, 2, '2011-12-09 12:58:26', 1, 'im', 15),
('yarrr', 2, 1, '2011-12-09 13:05:34', 1, 'im', 16),
('beep', 2, 1, '2011-12-09 13:08:57', 1, 'im', 17),
('woot', 1, 2, '2011-12-09 13:12:09', 1, 'im', 18),
('yarr', 1, 2, '2011-12-09 13:12:15', 1, 'im', 19),
('looong', 1, 2, '2011-12-09 13:12:20', 1, 'im', 20),
('zoom zoom zoom', 1, 2, '2011-12-09 13:12:26', 1, 'im', 21),
('woooosh', 1, 2, '2011-12-09 13:12:31', 1, 'im', 22),
('kabam', 1, 2, '2011-12-09 13:12:36', 1, 'im', 23),
('rzing song', 1, 2, '2011-12-09 13:12:43', 1, 'im', 24),
('woosh', 1, 2, '2011-12-09 13:21:44', 1, 'im', 25),
('kabam', 1, 2, '2011-12-09 13:21:54', 1, 'im', 26),
('crikey', 1, 2, '2011-12-09 13:22:03', 1, 'im', 27),
('zoooom', 1, 2, '2011-12-09 13:22:11', 1, 'im', 28),
('fuuuuck', 2, 1, '2011-12-09 13:22:25', 1, 'im', 29),
('bam', 2, 1, '2011-12-09 13:22:30', 1, 'im', 30),
('crappoholic', 2, 1, '2011-12-09 13:22:37', 1, 'im', 31),
('DING', 2, 1, '2011-12-09 13:33:17', 1, 'im', 32),
('regfgf', 2, 1, '2011-12-09 18:17:37', 1, 'message', 33),
('ddfg', 1, 2, '2011-12-09 19:34:51', 0, 'message', 34),
('yesy', 1, 2, '2012-02-10 12:01:16', 0, 'im', 35),
('hi', 1, 2, '2012-02-14 10:58:30', 0, 'im', 36),
('vroom\r\nsdfds \r\nsdfdsf \r\nsdf', 2, 1, '2012-02-25 09:24:23', 1, 'message', 37),
('hey', 1, 2, '2012-02-29 16:49:55', 0, 'im', 38),
('Your court reservation for Central Centerrat 2012-02-27 13:36:00 has been approved', 0, 1, '2012-03-04 17:05:02', 0, 'system', 39);

-- --------------------------------------------------------

--
-- Table structure for table `networks`
--

DROP TABLE IF EXISTS `networks`;
CREATE TABLE IF NOT EXISTS `networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'owner/admin',
  `pic` varchar(40) NOT NULL,
  `status` varchar(90) DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `lat` decimal(10,0) DEFAULT NULL,
  `long` decimal(10,0) DEFAULT NULL,
  `approved` int(11) NOT NULL DEFAULT '0' COMMENT 'pending approval ',
  `sport_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `profile_pic` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `networks`
--

INSERT INTO `networks` (`id`, `user_id`, `pic`, `status`, `name`, `zip`, `lat`, `long`, `approved`, `sport_id`, `description`, `profile_pic`) VALUES
(1, 1, '', 'networking things', 'First Network', '97402', NULL, NULL, 1, 1, 'The first network added to getxp', 'network_1.jpg'),
(2, 0, '', NULL, 'Second Network', '97402', NULL, NULL, 0, 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='news items for the site' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `news`
--


-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `center_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `caption` varchar(50) NOT NULL,
  `posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `url` varchar(90) DEFAULT NULL,
  `file` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='photos for various albums' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `center_id`, `user_id`, `profile_id`, `network_id`, `caption`, `posted`, `url`, `file`) VALUES
(3, NULL, 1, NULL, 1, 'Sunset', '2012-02-27 23:44:59', '', '1_tamarindo_sunset.jpg'),
(4, NULL, 1, NULL, 1, 'Second', '2012-02-28 00:16:25', '', '1_Anna117.jpg'),
(5, NULL, 1, 1, NULL, 'Me', '2012-02-28 00:23:13', '', '1_5763406714841812535.jpeg'),
(6, 1, 1, NULL, NULL, 'random', '2012-02-28 00:23:58', '', '1_birds.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE IF NOT EXISTS `profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `sport_id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `profile_pic` varchar(60) DEFAULT NULL,
  `hometown` text NOT NULL,
  `level` enum('Absolute Beginner','Advanced Beginner','Intermediate','Upper Intermediate','Advanced','Highly Advanced') NOT NULL,
  `lf_recreation` tinyint(1) NOT NULL,
  `lf_socializing` tinyint(1) NOT NULL,
  `lf_competition` tinyint(1) NOT NULL,
  `lf_relationship` tinyint(1) NOT NULL,
  `occupation` varchar(60) DEFAULT NULL,
  `education` varchar(60) DEFAULT NULL,
  `favorites` text,
  `zip` varchar(10) NOT NULL,
  `status` text NOT NULL,
  `age` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usersport` (`user_id`,`sport_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `sport_id`, `name`, `profile_pic`, `hometown`, `level`, `lf_recreation`, `lf_socializing`, `lf_competition`, `lf_relationship`, `occupation`, `education`, `favorites`, `zip`, `status`, `age`) VALUES
(1, 1, 1, 'Tennis Ryan', 'profile_1.jpg', 'Oregon, USA', 'Absolute Beginner', 1, 1, 0, 0, 'Developer', 'College', 'Hitting the ball', '97402', 'New status', 26),
(2, 3, 1, 'Test Ryan', NULL, 'Portland, OR', 'Intermediate', 0, 0, 0, 0, 'Programmer', 'some', '', '', '', NULL),
(3, 4, 1, 'sefsefes', NULL, 'fsefsefess', 'Absolute Beginner', 0, 0, 0, 0, 'dsfdsfsddsf', '', '', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `profile_updateds`
--

DROP TABLE IF EXISTS `profile_updateds`;
CREATE TABLE IF NOT EXISTS `profile_updateds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sport_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile_id` (`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='last activity on profile, used to determine who is ''online''' AUTO_INCREMENT=7065 ;

--
-- Dumping data for table `profile_updateds`
--

INSERT INTO `profile_updateds` (`id`, `sport_id`, `profile_id`, `user_id`, `modified`) VALUES
(1, 1, 1, 1, '2012-03-05 02:36:09'),
(53, 1, 2, 3, '2012-02-25 13:19:51'),
(2891, 1, 3, 4, '2012-02-27 16:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `center_id` int(11) NOT NULL,
  `court_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved` int(11) NOT NULL DEFAULT '0',
  `start` datetime NOT NULL,
  `stop` datetime NOT NULL,
  `comment` text COMMENT 'optional comment with request',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='court reservations' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`center_id`, `court_id`, `profile_id`, `created`, `approved`, `start`, `stop`, `comment`, `id`) VALUES
(1, 1, 1, '2011-12-12 20:41:53', 0, '2011-12-12 20:32:00', '2011-12-12 23:32:00', 'Testing reservation', 1),
(1, 2, 1, '2012-02-27 12:39:34', 1, '2012-02-27 13:36:00', '2012-02-27 15:36:00', 'I''d like to play miniature golf', 2),
(1, 2, 1, '2012-02-29 17:58:29', 0, '2012-02-23 18:00:00', '2012-02-23 19:30:00', 'evening match', 3);

-- --------------------------------------------------------

--
-- Table structure for table `shop_items`
--

DROP TABLE IF EXISTS `shop_items`;
CREATE TABLE IF NOT EXISTS `shop_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `center_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `embed` text NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='paypal items for proshop. Add paypal embed code for each item' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `sports`
--

DROP TABLE IF EXISTS `sports`;
CREATE TABLE IF NOT EXISTS `sports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT 'full sport name for display',
  `tag` varchar(10) NOT NULL COMMENT 'short name used for url and folder paths',
  `logo` varchar(50) DEFAULT NULL COMMENT 'path to logo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sports`
--

INSERT INTO `sports` (`id`, `name`, `tag`, `logo`) VALUES
(1, 'Tennis', 'tennis', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

DROP TABLE IF EXISTS `tournaments`;
CREATE TABLE IF NOT EXISTS `tournaments` (
  `host_id` int(11) NOT NULL,
  `center_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `current_round` int(11) NOT NULL DEFAULT '0',
  `format` varchar(20) NOT NULL COMMENT 'single_elim,double_elim etc',
  `max_teams` int(11) NOT NULL,
  `pools` int(11) DEFAULT NULL,
  `rounds` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `registration` varchar(15) NOT NULL COMMENT 'open,closed,invite',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `starts` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`host_id`, `center_id`, `network_id`, `current_round`, `format`, `max_teams`, `pools`, `rounds`, `name`, `description`, `registration`, `id`, `starts`) VALUES
(1, 1, NULL, 0, 'single elim', 8, 2, 0, 'Test Elim', 'simple single elim', 'closed', 1, '2011-12-31 00:00:00'),
(1, 1, NULL, 0, 'single elim', 7, NULL, 0, 'Uneven Elim', 'Sample single elimination with uneven # of teams (testing use of bye''s)', 'closed', 2, '2011-12-17 17:39:00'),
(1, 1, NULL, 0, 'single elim', 8, NULL, 0, 'Profile Test', 'test single elim with profile accounts', 'open', 3, '2011-12-20 10:51:00'),
(1, 1, NULL, 0, 'double elim', 6, NULL, 0, 'Test Double', 'Simple Double Elim', 'closed', 4, '2011-12-30 11:13:00'),
(1, 1, NULL, 0, 'round robin', 6, 2, 0, 'Simple RR', 'Basic Round Robin Tournament', 'open', 5, '2012-12-30 15:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `middle_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(35) NOT NULL,
  `email` varchar(60) DEFAULT NULL COMMENT 'email is username',
  `password` varchar(60) NOT NULL,
  `birth` date NOT NULL,
  `reference` text COMMENT 'how they heard about it',
  `username` varchar(60) NOT NULL COMMENT 'email address',
  `admin` int(11) NOT NULL DEFAULT '0' COMMENT 'is site admin',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `birth`, `reference`, `username`, `admin`) VALUES
(1, 'Ryan', '', 'Guthrie', 'ryan@ryanguthrie.com', '5fa8a27eed2199b42cb1ad5f8e7313b890c27bf3', '2011-11-01', NULL, 'ryan@ryanguthrie.com', 1),
(3, 'Ryan', 'A', 'Guthrie', 'sokercap@gmail.com', '5fa8a27eed2199b42cb1ad5f8e7313b890c27bf3', '2011-11-28', NULL, 'sokercap@gmail.com', 0),
(4, 'Ryan', 'A', 'Guppy', 'sokerdev@yahoo.com', '5fa8a27eed2199b42cb1ad5f8e7313b890c27bf3', '2011-12-09', NULL, 'sokerdev@yahoo.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
CREATE TABLE IF NOT EXISTS `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `center_id` int(11) DEFAULT NULL,
  `network_id` int(11) DEFAULT NULL,
  `caption` varchar(60) NOT NULL,
  `posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `embed` text,
  `url` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='videos store embed code or url' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `user_id`, `profile_id`, `center_id`, `network_id`, `caption`, `posted`, `embed`, `url`) VALUES
(1, 1, NULL, NULL, 1, 'Sunlight', '2012-02-28 00:41:34', '<iframe width="450" height="315" src="http://www.youtube.com/embed/Bparw9Jo3dk" frameborder="0" allowfullscreen></iframe>', 'http://youtu.be/Bparw9Jo3dk');

-- --------------------------------------------------------

--
-- Table structure for table `waves`
--

DROP TABLE IF EXISTS `waves`;
CREATE TABLE IF NOT EXISTS `waves` (
  `profile_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sport_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `waves`
--

