-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2013 at 08:49 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `forma`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `a_level` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `a_level`) VALUES
(1, 'alex', '534b44a19bf18d20b71ecc4eb77c572f', 3),
(2, 'chris', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `a_id` int(10) NOT NULL AUTO_INCREMENT,
  `a_title` varchar(300) NOT NULL,
  `t_title` varchar(300) NOT NULL,
  `a_desc` mediumtext NOT NULL,
  `a_img` varchar(300) NOT NULL,
  `a_author` varchar(100) NOT NULL,
  `a_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `a_of_month` int(1) NOT NULL,
  `a_blurb` varchar(500) NOT NULL,
  PRIMARY KEY (`a_id`),
  KEY `t_title` (`t_title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`a_id`, `a_title`, `t_title`, `a_desc`, `a_img`, `a_author`, `a_date`, `a_of_month`, `a_blurb`) VALUES
(22, 'Sit Up Tips', 'Sit Ups', '<p>khfdkf</p>', 'test681.jpg', 'Alex Shortt', '2012-11-02 21:50:46', 0, ''),
(23, 'Body builders', 'Sit Ups', '<p>fsdafasd</p>', 'fitness.jpg', 'Milly Long', '2013-06-02 11:43:58', 0, ''),
(24, 'New Weights', 'builders', '<p>sdffsdf</p>', 'formalogo.png', 'Milly Long', '2013-06-02 11:44:13', 0, ''),
(25, 'shit', 'Snoop', '<p>dfsdf</p>', '', 'alex', '2013-06-02 11:48:51', 0, ''),
(26, 'Yolo', 'Dre', '<p>dsfgrg</p>', '', 'Alex Shortt', '2013-06-02 11:49:05', 0, ''),
(27, 'Weights man', 'Biggie', '<p>sderge</p>', '', 'Alex Shortt', '2013-06-02 11:49:21', 0, ''),
(29, 'Sit Up Tips', 'cena', '<p>dfsdfrgw</p>', '', 'Alex Shortt', '2013-06-02 11:49:59', 0, ''),
(30, 'Body builders', 'Biggie smalls', '<p>Yoool</p>', 'test547.jpg', 'Alex Shortt', '2013-06-02 11:53:28', 0, ''),
(31, 'alex', 'Biggie smalls', '<p>sdas</p>', 'fitness566.jpg', 'Alex Shortt', '2013-06-02 12:34:11', 0, ''),
(32, 'Alex', 'Sit Ups', '<p>sfsdf</p>', 'Dumbells.png', 'Alex Shortt', '2013-06-02 12:54:12', 0, ''),
(34, 'cxzcz', 'Dre', '<p>dasdas</p>', 'images.jpg', 'sdasd', '2013-06-02 14:51:38', 0, ''),
(35, 'Rowing', 'Fitness', '<p><strong style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;">The Battle of Free Weights versus the Machines</strong><br style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;" /><br style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;" /><span style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;">Did you know that using free weights will actually help you to see muscle growth faster than using the machines will? Why is that, you may ask? Let''s take a look at the basic exercise every weightlifter does: The Bench Press.</span><br style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;" /><br style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;" /><u style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;">Chest Press Machine</u><br style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;" /><br style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;" /><span style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;">Sit down in the comfy chair, adjust the weight of your chest press and push. The handles of the machine just go straight forward, and you strain and push to extend your arms. You bring the handles back to your chest, and you push again to complete your set. Your chest gets a great workout, and your shoulders and triceps are hit at the same time. All in all, it''s a good workout.</span><br style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;" /><br style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;" /><u style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;">Free Weight Bench Press</u><br style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;" /><br style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;" /><span style="color: #3e4752; font-family: ArimoRegular, Arial, Helvetica, sans-serif; font-size: 14px; line-height: 18px;">Lie back on the bench, reach up to grip the bar, and push the bar from its rest to hold it over your chest. Your shoulder and chest muscles twitch to keep the bar in its position as you adjust it, and your arms have to work with your core to keep the bar perfectly straight. Now, you slowly lower it to your chest, and you stop the weight just before your elbows crack a 90 degree angle. You heave to push it back up, and you have to use your arm muscles in tandem to ensure that the bar stays level and centered over your chest. You finally get the weight back up, and you repeat the exercise to get a good workout for your chest.</span></p>', 'Picture 420538.jpg', 'Alex Shortt', '2013-06-03 20:44:23', 1, 'Did you know that using free weights will actually help you to see muscle growth faster than using the machines will?');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `c_id` int(10) NOT NULL AUTO_INCREMENT,
  `c_title` varchar(10) NOT NULL,
  `c_first_name` varchar(200) NOT NULL,
  `c_last_name` varchar(200) NOT NULL,
  `c_gender` varchar(10) NOT NULL,
  `c_img` varchar(300) DEFAULT NULL,
  `c_telephone` varchar(50) DEFAULT NULL,
  `c_email` varchar(100) DEFAULT NULL,
  `c_dob` date NOT NULL,
  `c_age` int(3) NOT NULL,
  `c_username` varchar(200) NOT NULL,
  `c_password` varchar(32) NOT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`c_id`, `c_title`, `c_first_name`, `c_last_name`, `c_gender`, `c_img`, `c_telephone`, `c_email`, `c_dob`, `c_age`, `c_username`, `c_password`) VALUES
(12, 'Mrs', 'Milly', 'Long', 'Female', '526534_10151273155962727_1540181797_n.jpg', '01295123456', 'milly@bobo.com', '1992-07-26', 20, 'milly', '64b67fd105697b8fa98a1131926facff'),
(15, 'Dr', 'Bill', 'Doubtfire', 'Male', 'default_profile_m.png', '01295123456', 'shortty99@hotmail.co.uk', '1951-01-01', 62, 'Bill.Doubtfire5', 'emvkm2j3'),
(16, 'Mr', 'John', 'Cena', 'Male', 'John-Cena-john-cena-12141542-816-1023.jpg', '01295123456', 'jon@cena.com', '1979-09-18', 33, 'bobo', 'ca2cd2bcc63c4d7c8725577442073dde');

-- --------------------------------------------------------

--
-- Table structure for table `client_bookings`
--

CREATE TABLE IF NOT EXISTS `client_bookings` (
  `cb_id` int(10) NOT NULL AUTO_INCREMENT,
  `c_id` int(10) NOT NULL,
  `cb_session_length` varchar(10) NOT NULL,
  `cb_day_type` varchar(50) NOT NULL,
  `cb_datetime` datetime NOT NULL,
  `cb_datetime_end` datetime NOT NULL,
  `cb_package` varchar(100) NOT NULL,
  `cb_location` varchar(250) NOT NULL,
  `cb_session_type` varchar(50) NOT NULL,
  PRIMARY KEY (`cb_id`),
  KEY `c_id` (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=116 ;

--
-- Dumping data for table `client_bookings`
--

INSERT INTO `client_bookings` (`cb_id`, `c_id`, `cb_session_length`, `cb_day_type`, `cb_datetime`, `cb_datetime_end`, `cb_package`, `cb_location`, `cb_session_type`) VALUES
(106, 12, '45', 'Peak', '2013-06-10 09:00:00', '2013-06-10 09:45:00', '0', 'Gym', 'Class'),
(108, 16, '45', 'Peak', '2013-06-10 09:00:00', '2013-06-10 09:45:00', '10', 'Gym', 'Class'),
(110, 16, '30', 'Peak', '2013-06-10 09:45:00', '2013-06-10 10:15:00', '10', 'Gym', 'Personal'),
(113, 16, '45', 'Off-Peak', '2013-06-11 10:00:00', '2013-06-11 10:45:00', '11', 'Gym', 'Class'),
(114, 15, '30', 'Off-Peak', '2013-06-11 10:45:00', '2013-06-11 11:15:00', '0', 'Gym', 'Personal'),
(115, 16, '60', 'Off-Peak', '2013-06-14 10:00:00', '2013-06-14 11:00:00', '11', 'Gym', 'Personal');

-- --------------------------------------------------------

--
-- Table structure for table `client_booking_reqs`
--

CREATE TABLE IF NOT EXISTS `client_booking_reqs` (
  `br_id` int(10) NOT NULL AUTO_INCREMENT,
  `br_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `c_id` int(10) NOT NULL,
  `br_type` varchar(25) NOT NULL,
  PRIMARY KEY (`br_id`),
  KEY `c_id` (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `client_completed_goals`
--

CREATE TABLE IF NOT EXISTS `client_completed_goals` (
  `c_id` int(10) NOT NULL,
  `cg_id` int(10) NOT NULL,
  `cg_goal` varchar(500) NOT NULL,
  `cg_target_date` datetime NOT NULL,
  `cg_set_date` datetime NOT NULL,
  `cg_date_completed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `c_id` (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client_completed_goals`
--

INSERT INTO `client_completed_goals` (`c_id`, `cg_id`, `cg_goal`, `cg_target_date`, `cg_set_date`, `cg_date_completed`) VALUES
(16, 2, '<p>sadsadsa</p>', '2013-06-26 00:00:00', '2013-06-07 20:23:03', '2013-06-07 19:39:41');

-- --------------------------------------------------------

--
-- Table structure for table `client_goals`
--

CREATE TABLE IF NOT EXISTS `client_goals` (
  `cg_id` int(10) NOT NULL AUTO_INCREMENT,
  `c_id` int(10) NOT NULL,
  `cg_goal` varchar(500) NOT NULL,
  `cg_target_date` datetime NOT NULL,
  `cg_set_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cg_id`),
  KEY `c_id` (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `client_goals`
--

INSERT INTO `client_goals` (`cg_id`, `c_id`, `cg_goal`, `cg_target_date`, `cg_set_date`) VALUES
(1, 16, '<p>ghhfg</p>', '2013-06-30 00:00:00', '2013-06-07 19:14:49');

-- --------------------------------------------------------

--
-- Table structure for table `client_measurements`
--

CREATE TABLE IF NOT EXISTS `client_measurements` (
  `c_id` int(10) NOT NULL,
  `date_taken` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `c_weight` varchar(5) NOT NULL,
  `c_height` varchar(5) NOT NULL,
  KEY `c_id` (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client_measurements`
--

INSERT INTO `client_measurements` (`c_id`, `date_taken`, `c_weight`, `c_height`) VALUES
(15, '2013-05-30 18:18:33', '79', '150'),
(12, '2013-05-30 18:19:24', '70', ''),
(15, '2013-05-30 18:21:41', '70', ''),
(15, '2013-05-30 18:22:22', '13', '13');

-- --------------------------------------------------------

--
-- Table structure for table `client_packages`
--

CREATE TABLE IF NOT EXISTS `client_packages` (
  `cp_id` int(10) NOT NULL AUTO_INCREMENT,
  `c_id` int(10) NOT NULL,
  `cp_package` varchar(250) NOT NULL,
  `cp_sessions` int(10) NOT NULL,
  `cp_classes` int(10) NOT NULL,
  PRIMARY KEY (`cp_id`),
  KEY `c_id` (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `client_packages`
--

INSERT INTO `client_packages` (`cp_id`, `c_id`, `cp_package`, `cp_sessions`, `cp_classes`) VALUES
(11, 16, 'Businessman:Â 5Â WeeksÂ Â (2Â xÂ 15Â minutesÂ +Â 1Â classÂ perÂ week)', 8, 4),
(13, 12, '5 x 30 Minutes', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `client_program`
--

CREATE TABLE IF NOT EXISTS `client_program` (
  `cprog_id` int(10) NOT NULL AUTO_INCREMENT,
  `cprog_dateset` datetime NOT NULL,
  `cprog_datecompleted` datetime NOT NULL,
  `c_id` int(10) NOT NULL,
  PRIMARY KEY (`cprog_id`),
  KEY `c_id` (`c_id`),
  KEY `c_id_2` (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `client_program`
--

INSERT INTO `client_program` (`cprog_id`, `cprog_dateset`, `cprog_datecompleted`, `c_id`) VALUES
(2, '2013-06-10 13:04:40', '0000-00-00 00:00:00', 16),
(3, '2013-06-10 19:28:11', '0000-00-00 00:00:00', 15);

-- --------------------------------------------------------

--
-- Table structure for table `client_program_progress`
--

CREATE TABLE IF NOT EXISTS `client_program_progress` (
  `cpp_id` int(50) NOT NULL AUTO_INCREMENT,
  `cprog_id` int(10) NOT NULL,
  `ro_id` int(10) NOT NULL,
  `e_id` int(10) NOT NULL,
  `reps_achieved` int(50) NOT NULL,
  `w_d_achieved` int(100) NOT NULL,
  `e_type` varchar(50) NOT NULL,
  `cpp_date` datetime NOT NULL,
  PRIMARY KEY (`cpp_id`),
  KEY `cprog_id` (`cprog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `client_program_progress`
--

INSERT INTO `client_program_progress` (`cpp_id`, `cprog_id`, `ro_id`, `e_id`, `reps_achieved`, `w_d_achieved`, `e_type`, `cpp_date`) VALUES
(5, 2, 39, 17, 3, 3, 'Reps', '2013-06-13 22:53:39'),
(6, 2, 39, 17, 3, 3, 'Reps', '2013-06-13 22:53:39'),
(7, 2, 39, 17, 3, 3, 'Reps', '2013-06-13 22:53:39'),
(8, 2, 39, 18, 9, 8, 'Reps', '2013-06-13 22:53:39'),
(9, 2, 39, 18, 9, 9, 'Reps', '2013-06-13 22:53:39'),
(10, 2, 39, 18, 9, 7, 'Reps', '2013-06-13 22:53:39'),
(11, 2, 39, 19, 10, 0, 'Mins', '2013-06-13 22:53:39'),
(12, 2, 39, 19, 20, 0, 'Mins', '2013-06-13 22:53:39'),
(13, 2, 39, 19, 10, 0, 'Mins', '2013-06-13 22:53:39'),
(14, 2, 39, 21, 8, 9, 'Reps', '2013-06-13 22:53:39'),
(15, 2, 39, 21, 9, 8, 'Reps', '2013-06-13 22:53:39'),
(16, 2, 39, 21, 7, 7, 'Reps', '2013-06-13 22:53:39');

-- --------------------------------------------------------

--
-- Table structure for table `cprog_routine_link`
--

CREATE TABLE IF NOT EXISTS `cprog_routine_link` (
  `cprog_id` int(10) NOT NULL,
  `ro_id` int(10) NOT NULL,
  KEY `cprog_id` (`cprog_id`,`ro_id`),
  KEY `ro_id` (`ro_id`),
  KEY `cprog_id_2` (`cprog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cprog_routine_link`
--

INSERT INTO `cprog_routine_link` (`cprog_id`, `ro_id`) VALUES
(1, 33),
(1, 39),
(2, 32),
(2, 38),
(2, 39),
(3, 38);

-- --------------------------------------------------------

--
-- Table structure for table `exercise`
--

CREATE TABLE IF NOT EXISTS `exercise` (
  `e_id` int(10) NOT NULL AUTO_INCREMENT,
  `e_title` varchar(250) NOT NULL,
  `e_cat_title` varchar(250) NOT NULL,
  `e_desc` varchar(1000) NOT NULL,
  PRIMARY KEY (`e_id`),
  KEY `e_cat_title` (`e_cat_title`),
  KEY `e_title` (`e_title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `exercise`
--

INSERT INTO `exercise` (`e_id`, `e_title`, `e_cat_title`, `e_desc`) VALUES
(13, 'Chest ffd', 'boats', ''),
(14, 'Leg www', 'Chest', ''),
(17, 'Chest Press', 'Chest', ''),
(18, 'Chest Fly', 'Chest', 'Im wirting this because i can and yeah this is an amzaing stie '),
(19, 'Press up', 'Chest', 'dsssdf'),
(21, 'Dumbbell Chest Press', 'Chest', 'The dumbbell chest press closely mimics the bench press â€” the favourite exercise among serious weightlifters everywhere. This exercise works your chest muscles, shoulders, and triceps. If you have shoulder, elbow, or lower-back problems, limit the range of motion. You should lower and lift the dumbbells only a few inches to avoid overstraining these joints.');

-- --------------------------------------------------------

--
-- Table structure for table `exercise_cats`
--

CREATE TABLE IF NOT EXISTS `exercise_cats` (
  `e_cat_id` int(10) NOT NULL AUTO_INCREMENT,
  `e_cat_title` varchar(250) NOT NULL,
  PRIMARY KEY (`e_cat_id`),
  KEY `e_cat_title` (`e_cat_title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `exercise_cats`
--

INSERT INTO `exercise_cats` (`e_cat_id`, `e_cat_title`) VALUES
(5, 'boats'),
(3, 'Bum'),
(1, 'Chest'),
(2, 'Legs');

-- --------------------------------------------------------

--
-- Table structure for table `exercise_steps`
--

CREATE TABLE IF NOT EXISTS `exercise_steps` (
  `e_title` varchar(250) NOT NULL,
  `e_id` int(10) NOT NULL,
  `e_step_id` int(10) NOT NULL AUTO_INCREMENT,
  `e_step_num` int(10) NOT NULL,
  `e_step_img` varchar(500) DEFAULT NULL,
  `e_step_desc` varchar(500) NOT NULL,
  PRIMARY KEY (`e_step_id`),
  KEY `e_title` (`e_title`),
  KEY `e_id` (`e_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `exercise_steps`
--

INSERT INTO `exercise_steps` (`e_title`, `e_id`, `e_step_id`, `e_step_num`, `e_step_img`, `e_step_desc`) VALUES
('Chest ffd', 13, 25, 1, '', 'dds'),
('Leg www', 14, 26, 1, '', 'ww'),
('Leg www', 14, 27, 2, NULL, 'wwedd'),
('Chest Press', 17, 31, 1, 'Blue hills490.jpg', 'dfdfkj'),
('Chest Press', 17, 32, 2, 'fitness333.jpg', 'grgrg'),
('Chest Fly', 18, 33, 1, '', 'gfsg'),
('Chest Fly', 18, 34, 2, 'formalogo610.png', 'sdg'),
('Press up', 19, 35, 1, 'John-Cena-john-cena-12141542-816-1023605.jpg', 'dsfdf'),
('Press up', 19, 36, 2, 'fitness112570.jpg', 'dsftrger'),
('Dumbbell Chest Press', 21, 43, 1, '', 'Lie on the bench with a dumbbell in each hand and your feet flat on the floor. You can rest your feet up on the bench if itâ€™s more comfortable.'),
('Dumbbell Chest Press', 21, 44, 2, '101317.png', 'Push the dumbbells up so that your arms are directly over your shoulders and your palms are up.'),
('Dumbbell Chest Press', 21, 45, 3, '', 'Pull your abdominals in, and tilt your chin toward your chest.'),
('Dumbbell Chest Press', 21, 46, 4, '101317dsd.png', 'Lower the dumbbells down and a little to the side until your elbows are slightly below your shoulders.'),
('Dumbbell Chest Press', 21, 47, 5, '', 'Roll your shoulder blades back and down, like youâ€™re pinching them together and accentuating your chest.'),
('Dumbbell Chest Press', 21, 48, 6, '101317346.png', 'Push the weights back up, taking care not to lock your elbows or allow your shoulder blades to rise off the bench.');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `m_id` int(10) NOT NULL AUTO_INCREMENT,
  `m_desc` varchar(1000) NOT NULL,
  `m_from` varchar(500) NOT NULL,
  `m_to` varchar(500) NOT NULL,
  PRIMARY KEY (`m_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE IF NOT EXISTS `recipes` (
  `r_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_title` varchar(300) NOT NULL,
  `r_ing` varchar(500) NOT NULL,
  `r_img` varchar(250) NOT NULL,
  `r_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `r_of_month` int(1) NOT NULL,
  PRIMARY KEY (`r_id`),
  KEY `r_title` (`r_title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`r_id`, `r_title`, `r_ing`, `r_img`, `r_date`, `r_of_month`) VALUES
(2, 'Chicken Dinner', 'dfsds', 'Photo02_00507.jpg', '2013-06-01 18:04:26', 0),
(3, 'Cheese Toasty', '<ul><li><span style="font-size: 1.1em;">Pineapple</span><br></li><li><span style="font-size: 1.1em;">Bread</span><br></li><li><span style="font-size: 1.1em;">Cheese</span><br></li></ul>', 'Photo04_1.jpg', '2013-06-01 18:04:26', 1),
(4, 'Cheese Toastyass', '<p>saadsad</p>', '30371_10152339702590615_1369994253_n978.jpg', '2013-06-01 18:03:39', 0),
(5, 'Cheese Toastyass', '<p>saadsad</p>', '30371_10152339702590615_1369994253_n429.jpg', '2013-06-01 18:03:33', 0),
(6, 'fsdgsd', '<p>sdgdsg</p>', 'default_profile_m318.png', '2013-06-01 18:03:49', 0),
(7, 'dsfs', '<p>sdfsdf</p>', 'default_profile_m247.png', '2013-06-01 18:03:43', 0),
(8, 'Chicken Dinner', '<p>sdasd</p>', 'test.jpg', '2013-06-02 10:35:13', 0),
(9, 'Chicken Dinner', '<p>sdasd</p>', 'test762.jpg', '2013-06-02 10:36:01', 0),
(10, 'Chicken Dinner', '<p>sdasd</p>', 'test84.jpg', '2013-06-02 10:36:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_steps`
--

CREATE TABLE IF NOT EXISTS `recipe_steps` (
  `r_step_id` int(10) NOT NULL AUTO_INCREMENT,
  `r_step_desc` varchar(500) NOT NULL,
  `r_step_num` int(10) NOT NULL,
  `r_title` varchar(250) NOT NULL,
  PRIMARY KEY (`r_step_id`),
  KEY `r_title` (`r_title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `recipe_steps`
--

INSERT INTO `recipe_steps` (`r_step_id`, `r_step_desc`, `r_step_num`, `r_title`) VALUES
(1, 'sda', 1, 'Cheese Toastyass'),
(2, 'asddas', 2, 'Cheese Toastyass'),
(3, 'sda', 1, 'Cheese Toastyass'),
(4, 'asddas', 2, 'Cheese Toastyass'),
(5, 'dgds', 1, 'fsdgsd'),
(6, 'dsfsf', 1, 'dsfs'),
(7, 'asdad', 1, 'Chicken Dinner'),
(8, 'asdsd', 2, 'Chicken Dinner'),
(9, 'asdad', 1, 'Chicken Dinner'),
(10, 'asdsd', 2, 'Chicken Dinner'),
(11, 'asdad', 1, 'Chicken Dinner'),
(12, 'asdsd', 2, 'Chicken Dinner');

-- --------------------------------------------------------

--
-- Table structure for table `routine`
--

CREATE TABLE IF NOT EXISTS `routine` (
  `ro_id` int(10) NOT NULL AUTO_INCREMENT,
  `ro_title` varchar(250) NOT NULL,
  `ro_desc` varchar(1000) NOT NULL,
  `ro_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ro_of_month` int(1) NOT NULL,
  `ro_focus` varchar(50) NOT NULL,
  `ro_level` varchar(50) NOT NULL,
  `ro_warm` varchar(750) NOT NULL,
  PRIMARY KEY (`ro_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `routine`
--

INSERT INTO `routine` (`ro_id`, `ro_title`, `ro_desc`, `ro_date`, `ro_of_month`, `ro_focus`, `ro_level`, `ro_warm`) VALUES
(32, 'Poppy Routine', '<p>An amazing routine for all you dogs</p>', '2013-06-05 14:18:47', 0, '', '', ''),
(33, 'Bulking', '<p>dfsdfd</p>', '2013-06-10 13:01:24', 0, 'Full Body', 'Hard', '<ul>\n<li>Rower - 10 Resistence - 500 m Distance - Duration &lt;2mins</li>\n<li>Crocodiles - 3 Sets - 10 Reps - Progression - Use dumbells</li>\n</ul>'),
(34, 'dadas', '<p>asda</p>', '2013-06-05 15:22:52', 0, 'Full Body', 'Beginner', '<p>sdad</p>'),
(35, 'Bulkingsd', '<p>dsad</p>', '2013-06-05 15:41:49', 0, 'Upper Body', 'Intermediate', '<p>asdas</p>'),
(36, 'Poppy Routine', '<p>adasd</p>', '2013-06-05 15:42:18', 0, 'Lower Body', 'Intermediate', '<p>asdad</p>'),
(37, 'Poppy Routine', '<p>adasd</p>', '2013-06-05 20:16:55', 0, 'Lower Body', 'Intermediate', '<p>asdad</p>'),
(38, 'New Test', '<p>dadsdfg</p>', '2013-06-10 13:01:32', 0, 'Full Body', 'Beginner', '<ul>\n<li>Rower Res 10 distance 500 Duration &lt;2mins</li>\n</ul>'),
(39, 'Hardcore', '<p>sdasdasd</p>', '2013-06-10 13:01:50', 1, 'Full Body', 'Beginner', '<ul>\n<li>Rower - 10 Resistence - 500 m Distance - Duration &lt;2mins</li>\n<li>Crocodiles - 3 Sets - 10 Reps - Progression - Use dumbells</li>\n</ul>');

-- --------------------------------------------------------

--
-- Table structure for table `routine_exercise`
--

CREATE TABLE IF NOT EXISTS `routine_exercise` (
  `ro_id` int(10) NOT NULL,
  `e_id` int(10) NOT NULL,
  `sets` int(10) NOT NULL,
  `reps` int(10) NOT NULL,
  `reps_type` varchar(50) NOT NULL,
  `ro_exer_desc` varchar(1000) NOT NULL,
  `ro_type_title` varchar(500) NOT NULL,
  `burn` int(1) DEFAULT NULL,
  KEY `ro_id` (`ro_id`,`e_id`),
  KEY `e_id` (`e_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `routine_exercise`
--

INSERT INTO `routine_exercise` (`ro_id`, `e_id`, `sets`, `reps`, `reps_type`, `ro_exer_desc`, `ro_type_title`, `burn`) VALUES
(32, 17, 1, 10, '', 'blahh', 'Bulking', 0),
(32, 18, 3, 20, '', 'blueee', 'Power', 0),
(33, 17, 1, 10, '', 'hello amazing', 'Bulking', 0),
(33, 18, 5, 5, '', 'blue lahf kd', 'Power', 0),
(34, 17, 3, 5, 'Reps', 'asdds', 'Bulking', 0),
(35, 19, 1, 500, 'Metres', 'dasd', 'Cutting', 0),
(36, 17, 3, 10, 'Mins', 'dasd', 'Bulking', 1),
(37, 17, 3, 10, 'Mins', 'dasd', 'Bulking', 1),
(37, 18, 3, 10, 'Reps', 'dsadsa', 'Cutting', NULL),
(38, 17, 3, 10, 'Reps', 'dfsfdsf', 'Cutting', NULL),
(38, 19, 3, 5, 'Reps', 'dfdds', 'Cutting', NULL),
(38, 18, 3, 10, 'Reps', 'fdsfsd', 'Bulking', NULL),
(39, 17, 3, 10, 'Reps', 'ffdfs', 'Cutting', 0),
(39, 18, 3, 10, 'Reps', 'dfdgfg', 'Cutting', 0),
(39, 19, 3, 10, 'Mins', 'fdsfsd', 'Speed', 0),
(39, 21, 3, 12, 'Reps', 'fsdfrg', 'Power', 1);

-- --------------------------------------------------------

--
-- Table structure for table `routine_type`
--

CREATE TABLE IF NOT EXISTS `routine_type` (
  `rt_id` int(10) NOT NULL AUTO_INCREMENT,
  `rt_title` varchar(300) NOT NULL,
  PRIMARY KEY (`rt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `routine_type`
--

INSERT INTO `routine_type` (`rt_id`, `rt_title`) VALUES
(1, 'Bulking'),
(2, 'Power'),
(3, 'Speed'),
(4, 'Strength'),
(5, 'Stamina'),
(6, 'Cutting'),
(7, 'General');

-- --------------------------------------------------------

--
-- Table structure for table `routine_type_link`
--

CREATE TABLE IF NOT EXISTS `routine_type_link` (
  `ro_id` int(10) NOT NULL,
  `rt_id` int(10) NOT NULL,
  KEY `r_id` (`ro_id`,`rt_id`),
  KEY `rt_id` (`rt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `routine_type_link`
--

INSERT INTO `routine_type_link` (`ro_id`, `rt_id`) VALUES
(32, 1),
(32, 2),
(33, 1),
(33, 2),
(34, 1),
(35, 6),
(36, 1),
(36, 6),
(37, 1),
(37, 6),
(38, 1),
(38, 6),
(39, 2),
(39, 3),
(39, 6);

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
  `t_id` int(10) NOT NULL AUTO_INCREMENT,
  `t_title` varchar(300) NOT NULL,
  `t_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`t_id`),
  KEY `t_title` (`t_title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`t_id`, `t_title`, `t_date`) VALUES
(24, 'Sit Ups', '2013-06-02 11:43:44'),
(25, 'builders', '2013-06-02 11:44:13'),
(26, 'Snoop', '2013-06-02 11:48:51'),
(27, 'Dre', '2013-06-02 11:49:05'),
(28, 'Biggie', '2013-06-02 11:49:21'),
(30, 'cena', '2013-06-02 11:49:59'),
(31, 'Biggie smalls', '2013-06-02 11:53:28'),
(32, 'Fitness', '2013-06-02 20:50:46');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`t_title`) REFERENCES `topics` (`t_title`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_bookings`
--
ALTER TABLE `client_bookings`
  ADD CONSTRAINT `client_bookings_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `clients` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_booking_reqs`
--
ALTER TABLE `client_booking_reqs`
  ADD CONSTRAINT `client_booking_reqs_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `clients` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_completed_goals`
--
ALTER TABLE `client_completed_goals`
  ADD CONSTRAINT `client_completed_goals_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `clients` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_program`
--
ALTER TABLE `client_program`
  ADD CONSTRAINT `client_program_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `clients` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cprog_routine_link`
--
ALTER TABLE `cprog_routine_link`
  ADD CONSTRAINT `cprog_routine_link_ibfk_1` FOREIGN KEY (`cprog_id`) REFERENCES `cprog_routine_link` (`cprog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cprog_routine_link_ibfk_2` FOREIGN KEY (`ro_id`) REFERENCES `routine` (`ro_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exercise`
--
ALTER TABLE `exercise`
  ADD CONSTRAINT `exercise_ibfk_1` FOREIGN KEY (`e_cat_title`) REFERENCES `exercise_cats` (`e_cat_title`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exercise_steps`
--
ALTER TABLE `exercise_steps`
  ADD CONSTRAINT `exercise_steps_ibfk_1` FOREIGN KEY (`e_title`) REFERENCES `exercise` (`e_title`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exercise_steps_ibfk_2` FOREIGN KEY (`e_id`) REFERENCES `exercise` (`e_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipe_steps`
--
ALTER TABLE `recipe_steps`
  ADD CONSTRAINT `recipe_steps_ibfk_1` FOREIGN KEY (`r_title`) REFERENCES `recipes` (`r_title`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `routine_exercise`
--
ALTER TABLE `routine_exercise`
  ADD CONSTRAINT `routine_exercise_ibfk_1` FOREIGN KEY (`ro_id`) REFERENCES `routine` (`ro_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `routine_exercise_ibfk_2` FOREIGN KEY (`e_id`) REFERENCES `exercise` (`e_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `routine_type_link`
--
ALTER TABLE `routine_type_link`
  ADD CONSTRAINT `routine_type_link_ibfk_1` FOREIGN KEY (`ro_id`) REFERENCES `routine` (`ro_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `routine_type_link_ibfk_2` FOREIGN KEY (`rt_id`) REFERENCES `routine_type` (`rt_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
