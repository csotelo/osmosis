-- phpMyAdmin SQL Dump
-- version 2.11.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 03, 2008 at 01:04 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `osmosis`
--

-- --------------------------------------------------------

--
-- Table structure for table `quiz_choice_choices`
--

CREATE TABLE IF NOT EXISTS `quiz_choice_choices` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `choice_question_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL COMMENT 'Related Choice Question',
  `text` text NOT NULL COMMENT 'Text for this choce',
  `position` tinyint(3) NOT NULL default '0' COMMENT 'The position of the choice as will appear in the question',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Choice Questions'' choices';

-- --------------------------------------------------------

--
-- Table structure for table `quiz_choice_questions`
--

CREATE TABLE IF NOT EXISTS `quiz_choice_questions` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `body` text NOT NULL COMMENT 'The questions wording',
  `shuffle` tinyint(1) NOT NULL COMMENT 'Set the order of the choices randomly',
  `max_choices` int(11) NOT NULL COMMENT 'Maximum number of choices that the candidate is allowed to select. If maxChoices is 0 then there is no restriction. If maxChoices is greater than 1 (or 0) then the interaction must be bound to a response with multiple cardinality',
  `min_choices` int(11) default NULL COMMENT 'Minimum number of choices that the candidate is required to select to form a valid response. If minChoices is 0 then the candidate is not required to select any choices. minChoices must be less than or equal to the limit imposed by maxChoices.',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Selection one or more of the available choices';

-- --------------------------------------------------------

--
-- Table structure for table `quiz_choice_questions_quizzes`
--

CREATE TABLE IF NOT EXISTS `quiz_choice_questions_quizzes` (
  `id` char(36) NOT NULL,
  `choice_question_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `quiz_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relates a Choice Question with a Quiz (many-to-many)';

-- --------------------------------------------------------

--
-- Table structure for table `quiz_cloze_questions`
--

CREATE TABLE IF NOT EXISTS `quiz_cloze_questions` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'The questions title',
  `body` text NOT NULL COMMENT 'The questions wording',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Embedded Answer Question (Cloze format)';

-- --------------------------------------------------------

--
-- Table structure for table `quiz_cloze_questions_quizzes`
--

CREATE TABLE IF NOT EXISTS `quiz_cloze_questions_quizzes` (
  `id` int(11) NOT NULL auto_increment,
  `cloze_question_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `quiz_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Relates a Cloze Question with a Quiz (many-to-many)';

-- --------------------------------------------------------

--
-- Table structure for table `quiz_matching_choices`
--

CREATE TABLE IF NOT EXISTS `quiz_matching_choices` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `text` text NOT NULL COMMENT 'Text for this choce',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Matching Questions'' choices';

-- --------------------------------------------------------

--
-- Table structure for table `quiz_matching_choices_matching_questions`
--

CREATE TABLE IF NOT EXISTS `quiz_matching_choices_matching_questions` (
  `id` char(36) NOT NULL,
  `matching_question_id` char(36) NOT NULL,
  `source` char(36) NOT NULL COMMENT 'Source Choice (from quiz_matching_choices)',
  `target` char(36) NOT NULL COMMENT 'Target Choice (from quiz_matching_choices)',
  `position` tinyint(3) NOT NULL default '0' COMMENT 'The position of the choice as will appear in the question',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_matching_questions`
--

CREATE TABLE IF NOT EXISTS `quiz_matching_questions` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `body` text NOT NULL COMMENT 'The questions wording',
  `shuffle` tinyint(1) NOT NULL COMMENT 'Set the ordr of the choices randomly',
  `max_associations` int(11) NOT NULL COMMENT 'Maximum number of choices that the candidate is allowed to select. If maxChoices is 0 then there is no restriction. If maxChoices is greater than 1 (or 0) then the interaction must be bound to a response with multiple cardinality',
  `min_associations` int(11) default NULL COMMENT 'Minimum number of choices that the candidate is required to select to form a valid response. If minChoices is 0 then the candidate is not required to select any choices. minChoices must be less than or equal to the limit imposed by maxChoices.',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Matching: Associate pairs of choices from two sets';

-- --------------------------------------------------------

--
-- Table structure for table `quiz_matching_questions_quizzes`
--

CREATE TABLE IF NOT EXISTS `quiz_matching_questions_quizzes` (
  `id` char(36) NOT NULL,
  `matching_question_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `quiz_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Relates a Matching Question with a Quiz (many-to-many)';

-- --------------------------------------------------------

--
-- Table structure for table `quiz_ordering_choices`
--

CREATE TABLE IF NOT EXISTS `quiz_ordering_choices` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `ordering_question_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL COMMENT 'Related Choice Question',
  `text` text NOT NULL COMMENT 'Text for this choce',
  `position` tinyint(3) NOT NULL default '0' COMMENT 'The position of the choice as will appear in the question',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Ordering Questions'' choices';

-- --------------------------------------------------------

--
-- Table structure for table `quiz_ordering_questions`
--

CREATE TABLE IF NOT EXISTS `quiz_ordering_questions` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `body` text NOT NULL COMMENT 'The questions wording',
  `shuffle` tinyint(1) NOT NULL COMMENT 'Set the ordr of the choices randomly',
  `max_choices` int(11) NOT NULL COMMENT 'Maximum number of choices that the candidate is allowed to select. If maxChoices is 0 then there is no restriction. If maxChoices is greater than 1 (or 0) then the interaction must be bound to a response with multiple cardinality',
  `min_choices` int(11) default NULL COMMENT 'Minimum number of choices that the candidate is required to select to form a valid response. If minChoices is 0 then the candidate is not required to select any choices. minChoices must be less than or equal to the limit imposed by maxChoices.',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='The candidate''s task is to select one or more of the choices';

-- --------------------------------------------------------

--
-- Table structure for table `quiz_ordering_questions_quizzes`
--

CREATE TABLE IF NOT EXISTS `quiz_ordering_questions_quizzes` (
  `id` char(36) NOT NULL,
  `ordering_question_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `quiz_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Relates a Choice Question with a Quiz (many-to-many)';

-- --------------------------------------------------------

--
-- Table structure for table `quiz_quizzes`
--

CREATE TABLE IF NOT EXISTS `quiz_quizzes` (
  `id` char(36) collate utf8_unicode_ci NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL COMMENT 'Title of the quiz',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Registered Quizzes';

-- --------------------------------------------------------

--
-- Table structure for table `quiz_text_questions`
--

CREATE TABLE IF NOT EXISTS `quiz_text_questions` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'The questions title',
  `body` text NOT NULL COMMENT 'The questions wording',
  `format` varchar(5) NOT NULL COMMENT 'plain, pre or xhtml',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_text_questions_quizzes`
--

CREATE TABLE IF NOT EXISTS `quiz_text_questions_quizzes` (
  `id` char(36) NOT NULL,
  `text_question_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `quiz_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `text_question_id` (`text_question_id`,`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relates a Text Question with a Quiz (many-to-many)';
