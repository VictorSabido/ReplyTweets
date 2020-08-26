-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 25-08-2020 a las 18:29:00
-- Versión del servidor: 5.7.24
-- Versión de PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `tweets` (
  `user_id` bigint(20) NOT NULL,
  `tweet_id` varchar(100) NOT NULL,
  `replied` tinyint(1) NOT NULL DEFAULT '0',
  `message` varchar(300) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tweets`
  ADD PRIMARY KEY (`tweet_id`);
COMMIT;