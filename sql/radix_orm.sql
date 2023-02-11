CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `user_id`) VALUES
(1, 'Danial post 1', 1),
(2, 'Danial post 2', 1),
(3, 'Danial post 3', 1),
(4, 'Danial post 4', 1),
(5, 'Danial post 5', 1),
(6, 'Danial post 6', 1),
(7, 'Danial post 7', 1),
(8, 'Danial post 8', 1),
(9, 'Danial post 9', 1),
(10, 'Alex post 1', 2),
(11, 'Alex post 2', 2),
(12, 'Alex post 3', 2),
(13, 'Alex post 4', 2),
(14, 'Alex post 5', 2),
(15, 'Alex post 6', 2),
(16, 'Alex post 7', 2),
(17, 'Alex post 8', 2),
(18, 'Alex post 9', 2),
(19, 'Alex post 10', 2),
(20, 'James post 1', 3),
(21, 'James post 2', 3),
(22, 'James post 3', 3),
(23, 'James post 4', 3),
(24, 'James post 5', 3),
(25, 'Sara post 1', 4),
(26, 'Sara post 2', 4),
(27, 'Sara post 3', 4),
(28, 'Sara post 4', 4),
(29, 'Sara post 5', 4),
(30, 'Sara post 6', 4),
(31, 'Sara post 7', 4),
(32, 'Sara post 8', 4),
(33, 'Sara post 9', 4),
(34, 'Sara post 10', 4),
(35, 'Melina post 1', 5),
(36, 'Melina post 2', 5),
(37, 'Melina post 3', 5),
(38, 'Melina post 4', 5),
(39, 'Melina post 5', 5),
(40, 'Melina post 6', 5),
(41, 'Jack post 1', 6),
(42, 'Jack post 2', 6),
(43, 'Jack post 3', 6),
(44, 'Jack post 4', 6),
(45, 'Jack post 5', 6),
(46, 'Jack post 6', 6),
(47, 'Jack post 7', 6),
(48, 'Jack post 8', 6),
(49, 'Kyle post 1', 7),
(50, 'Kyle post 2', 7),
(51, 'Kyle post 3', 7),
(52, 'Kyle post 4', 7),
(53, 'Kyle post 5', 7),
(54, 'Kyle post 6', 7),
(55, 'Kyle post 7', 7),
(56, 'Kyle post 8', 7);

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `country` varchar(200) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `country`, `city`) VALUES
(1, 'Iran', 'Tehran'),
(2, 'Germany', 'Berlin'),
(3, 'USA', 'California'),
(4, 'France', 'Paris'),
(5, 'UK', 'London'),
(6, 'Switzerland', 'Bern'),
(7, 'Israel', 'Jerusalem');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `post_id`, `rating`) VALUES
(99, 1, 56, 2),
(100, 1, 20, 2),
(101, 1, 48, 3),
(102, 1, 42, 3),
(103, 1, 8, 5),
(104, 1, 38, 3),
(105, 1, 35, 1),
(106, 2, 33, 2),
(107, 2, 2, 5),
(108, 2, 5, 3),
(109, 2, 37, 5),
(110, 2, 12, 3),
(111, 2, 22, 2),
(113, 3, 50, 4),
(114, 3, 20, 1),
(115, 3, 43, 4),
(116, 3, 51, 4),
(117, 3, 21, 4),
(118, 3, 1, 2),
(119, 3, 29, 3),
(120, 4, 51, 5),
(121, 4, 17, 5),
(122, 4, 25, 1),
(123, 4, 11, 1),
(124, 4, 23, 1),
(125, 4, 54, 2),
(126, 4, 12, 4),
(127, 5, 8, 2),
(128, 5, 16, 2),
(129, 5, 41, 3),
(130, 5, 20, 4),
(131, 5, 38, 2),
(132, 5, 3, 1),
(133, 5, 45, 5),
(134, 6, 35, 2),
(135, 6, 18, 4),
(136, 6, 22, 4),
(137, 6, 15, 1),
(138, 6, 39, 1),
(139, 6, 19, 4),
(140, 6, 4, 2),
(141, 7, 14, 1),
(142, 7, 18, 5),
(143, 7, 24, 5),
(144, 7, 3, 5),
(145, 7, 33, 1),
(146, 7, 39, 5),
(147, 7, 45, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `age`) VALUES
(1, 'Danial', 40),
(2, 'Alex', 21),
(3, 'James', 42),
(4, 'Sara', 39),
(5, 'Melina', 27),
(6, 'Jack', 26),
(7, 'Kyle', 28),
(9, 'Nechno', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_posts_user_id_users_id` (`user_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_id_post_id` (`user_id`,`post_id`),
  ADD KEY `fk_ratings_post_id_posts_id` (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_user_id_users_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `fk_profiles_id_users_id` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `fk_ratings_post_id_posts_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ratings_user_id_users_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;