/*
MySQL Data Transfer
Source Host: localhost
Source Database: polls
Target Host: localhost
Target Database: polls
Date: 06/22/2010 09:12:41
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for pollanswers
-- ----------------------------
CREATE TABLE `pollanswers` (
  `pollAnswerID` int(11) NOT NULL AUTO_INCREMENT,
  `pollID` int(11) DEFAULT NULL,
  `pollAnswerValue` varchar(250) DEFAULT NULL,
  `pollAnswerPoints` int(11) DEFAULT NULL,
  `pollAnswerListing` int(11) DEFAULT NULL,
  PRIMARY KEY (`pollAnswerID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for polls
-- ----------------------------
CREATE TABLE `polls` (
  `pollID` int(11) NOT NULL AUTO_INCREMENT,
  `pollQuestion` varchar(250) DEFAULT NULL,
  `pollStatus` tinyint(4) DEFAULT NULL COMMENT '0:passive - 1:active',
  PRIMARY KEY (`pollID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `pollanswers` VALUES ('1', '1', 'Answer1 for Poll1', '29', '1');
INSERT INTO `pollanswers` VALUES ('2', '1', 'Answer2 for Poll1', '73', '2');
INSERT INTO `pollanswers` VALUES ('3', '1', 'Answer3 for Poll1', '39', '3');
INSERT INTO `polls` VALUES ('1', 'Poll Question 1', '1');
INSERT INTO `polls` VALUES ('2', 'Poll Question 2', '1');
