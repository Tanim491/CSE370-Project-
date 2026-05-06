SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `study_group_finder`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` varchar(50) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `resource_link` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `resource_link`) VALUES
('CSE110', 'Programming Language I', NULL),
('CSE111', 'Programming Language II', NULL),
('CSE220', 'Data Structures', NULL),
('CSE221', 'Algorithms', NULL),
('CSE230', 'Discrete Mathematics', NULL),
('CSE250', 'Circuits and Electronics', NULL),
('CSE251', 'Electronic Devices and Circuits', NULL),
('CSE260', 'Digital Logic Design', NULL),
('CSE320', 'Data Communications', NULL),
('CSE321', 'Operating System', NULL),
('CSE330', 'Numerical Methods', NULL),
('CSE331', 'Automata and Computability', NULL),
('CSE340', 'Computer Architecture', NULL),
('CSE341', 'Microprocessor', '../uploads/CSE341_1778004061.pdf'),
('CSE350', 'Digital Electronics and Pulse Techniques', NULL),
('CSE360', 'Computer Interfacing', NULL),
('CSE370', 'Database System', '../uploads/CSE370_1778004033.pdf'),
('CSE420', 'Compiler Design', NULL),
('CSE421', 'Computer Networks', NULL),
('CSE422', 'Artificial Intelligence', NULL),
('CSE423', 'Computer Graphics', NULL),
('CSE460', 'VLSI Design', NULL),
('CSE461', 'Introduction to Robotics', NULL),
('CSE470', 'Software Engineering', NULL),
('CSE471', 'Systems Analysis and Design', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `id` int(11) NOT NULL,
  `G_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `Role` enum('Leader','Member') DEFAULT 'Member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`id`, `G_id`, `user_id`, `Role`) VALUES
(1, 6, 0, 'Leader'),
(2, NULL, 1, 'Leader'),
(3, NULL, 1, 'Leader'),
(4, NULL, 1, 'Leader'),
(5, NULL, 1, 'Leader'),
(6, NULL, 1, 'Leader'),
(7, NULL, 1, 'Leader'),
(8, NULL, 1, 'Leader'),
(9, NULL, 1, 'Leader'),
(10, NULL, 1, 'Leader'),
(11, NULL, 1, 'Leader'),
(12, NULL, 1, 'Leader'),
(13, NULL, 1, 'Leader'),
(14, NULL, 1, 'Leader'),
(15, NULL, 1, 'Leader'),
(16, NULL, 1, 'Leader'),
(17, NULL, 1, 'Leader'),
(18, NULL, 1, 'Leader'),
(19, NULL, 1, 'Leader'),
(20, NULL, 1, 'Leader'),
(21, NULL, 1, 'Leader'),
(22, NULL, 1, 'Leader'),
(23, NULL, 1, 'Leader'),
(24, NULL, 1, 'Leader');

-- --------------------------------------------------------

--
-- Table structure for table `join_req`
--

CREATE TABLE `join_req` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `join_req`
--

INSERT INTO `join_req` (`id`, `user_id`, `group_id`, `status`, `created_at`) VALUES
(8, 1, 6, 'Approved', '2026-05-05 21:37:05');

-- --------------------------------------------------------

--
-- Table structure for table `m_ship`
--

CREATE TABLE `m_ship` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `role` enum('Member','Admin') DEFAULT 'Member',
  `added_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `token` varchar(120) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `table_id` varchar(10) NOT NULL,
  `building` varchar(50) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_schedule`
--

CREATE TABLE `room_schedule` (
  `schedule_id` int(11) NOT NULL,
  `table_id` varchar(10) DEFAULT NULL,
  `day_of_week` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `session_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `s_grp`
--

CREATE TABLE `s_grp` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `subject` varchar(120) NOT NULL,
  `department` varchar(100) NOT NULL,
  `schedule` varchar(120) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `room_id` varchar(10) DEFAULT NULL,
  `day_of_week` varchar(15) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `table_id` varchar(10) DEFAULT NULL,
  `max_members` int(11) DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `s_grp`
--

INSERT INTO `s_grp` (`id`, `name`, `subject`, `department`, `schedule`, `created_by`, `created_at`, `room_id`, `day_of_week`, `start_time`, `end_time`, `table_id`, `max_members`) VALUES
(6, 'El clasico with 221', 'Algorithms', 'CSE', 'Saturday (01:23 - 02:53)', NULL, '2026-05-05 19:22:51', NULL, NULL, NULL, NULL, 'TBL00004', 3);

-- --------------------------------------------------------

--
-- Table structure for table `s_partner`
--

CREATE TABLE `s_partner` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `partner_user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `table_id` varchar(10) NOT NULL,
  `capacity` int(11) DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`table_id`, `capacity`) VALUES
('TBL00001', 3),
('TBL00002', 3),
('TBL00003', 3),
('TBL00004', 3),
('TBL00005', 3),
('TBL00006', 3),
('TBL00007', 3),
('TBL00008', 3),
('TBL00009', 3),
('TBL00010', 3),
('TBL00011', 3),
('TBL00012', 3),
('TBL00013', 3),
('TBL00014', 3),
('TBL00015', 3),
('TBL00016', 3),
('TBL00017', 3),
('TBL00018', 3),
('TBL00019', 3),
('TBL00020', 3),
('TBL00021', 3),
('TBL00022', 3),
('TBL00023', 3),
('TBL00024', 3),
('TBL00025', 3),
('TBL00026', 3),
('TBL00027', 3),
('TBL00028', 3),
('TBL00029', 3),
('TBL00030', 3),
('TBL00031', 3),
('TBL00032', 3),
('TBL00033', 3),
('TBL00034', 3),
('TBL00035', 3),
('TBL00036', 3),
('TBL00037', 3),
('TBL00038', 3),
('TBL00039', 3),
('TBL00040', 3),
('TBL00041', 3),
('TBL00042', 3),
('TBL00043', 3),
('TBL00044', 3),
('TBL00045', 3),
('TBL00046', 3),
('TBL00047', 3),
('TBL00048', 3),
('TBL00049', 3),
('TBL00050', 3),
('TBL00051', 3),
('TBL00052', 3),
('TBL00053', 3),
('TBL00054', 3),
('TBL00055', 3),
('TBL00056', 3),
('TBL00057', 3),
('TBL00058', 3),
('TBL00059', 3),
('TBL00060', 3),
('TBL00061', 3),
('TBL00062', 3),
('TBL00063', 3),
('TBL00064', 3),
('TBL00065', 3),
('TBL00066', 3),
('TBL00067', 3),
('TBL00068', 3),
('TBL00069', 3),
('TBL00070', 3),
('TBL00071', 3),
('TBL00072', 3),
('TBL00073', 3),
('TBL00074', 3),
('TBL00075', 3),
('TBL00076', 3),
('TBL00077', 3),
('TBL00078', 3),
('TBL00079', 3),
('TBL00080', 3),
('TBL00081', 3),
('TBL00082', 3),
('TBL00083', 3),
('TBL00084', 3),
('TBL00085', 3),
('TBL00086', 3),
('TBL00087', 3),
('TBL00088', 3),
('TBL00089', 3),
('TBL00090', 3),
('TBL00091', 3),
('TBL00092', 3),
('TBL00093', 3),
('TBL00094', 3),
('TBL00095', 3),
('TBL00096', 3),
('TBL00097', 3),
('TBL00098', 3),
('TBL00099', 3),
('TBL00100', 3),
('TBL00101', 3),
('TBL00102', 3),
('TBL00103', 3),
('TBL00104', 3),
('TBL00105', 3),
('TBL00106', 3),
('TBL00107', 3),
('TBL00108', 3),
('TBL00109', 3),
('TBL00110', 3),
('TBL00111', 3),
('TBL00112', 3),
('TBL00113', 3),
('TBL00114', 3),
('TBL00115', 3),
('TBL00116', 3),
('TBL00117', 3),
('TBL00118', 3),
('TBL00119', 3),
('TBL00120', 3),
('TBL00121', 3),
('TBL00122', 3),
('TBL00123', 3),
('TBL00124', 3),
('TBL00125', 3),
('TBL00126', 3),
('TBL00127', 3),
('TBL00128', 3),
('TBL00129', 3),
('TBL00130', 3),
('TBL00131', 3),
('TBL00132', 3),
('TBL00133', 3),
('TBL00134', 3),
('TBL00135', 3),
('TBL00136', 3),
('TBL00137', 3),
('TBL00138', 3),
('TBL00139', 3),
('TBL00140', 3),
('TBL00141', 3),
('TBL00142', 3),
('TBL00143', 3),
('TBL00144', 3),
('TBL00145', 3),
('TBL00146', 3),
('TBL00147', 3),
('TBL00148', 3),
('TBL00149', 3),
('TBL00150', 3),
('TBL00151', 3),
('TBL00152', 3),
('TBL00153', 3),
('TBL00154', 3),
('TBL00155', 3),
('TBL00156', 3),
('TBL00157', 3),
('TBL00158', 3),
('TBL00159', 3),
('TBL00160', 3),
('TBL00161', 3),
('TBL00162', 3),
('TBL00163', 3),
('TBL00164', 3),
('TBL00165', 3),
('TBL00166', 3),
('TBL00167', 3),
('TBL00168', 3),
('TBL00169', 3),
('TBL00170', 3),
('TBL00171', 3),
('TBL00172', 3),
('TBL00173', 3),
('TBL00174', 3),
('TBL00175', 3),
('TBL00176', 3),
('TBL00177', 3),
('TBL00178', 3),
('TBL00179', 3),
('TBL00180', 3),
('TBL00181', 3),
('TBL00182', 3),
('TBL00183', 3),
('TBL00184', 3),
('TBL00185', 3),
('TBL00186', 3),
('TBL00187', 3),
('TBL00188', 3),
('TBL00189', 3),
('TBL00190', 3),
('TBL00191', 3),
('TBL00192', 3),
('TBL00193', 3),
('TBL00194', 3),
('TBL00195', 3),
('TBL00196', 3),
('TBL00197', 3),
('TBL00198', 3),
('TBL00199', 3),
('TBL00200', 3),
('TBL00201', 3),
('TBL00202', 3),
('TBL00203', 3),
('TBL00204', 3),
('TBL00205', 3),
('TBL00206', 3),
('TBL00207', 3),
('TBL00208', 3),
('TBL00209', 3),
('TBL00210', 3),
('TBL00211', 3),
('TBL00212', 3),
('TBL00213', 3),
('TBL00214', 3),
('TBL00215', 3),
('TBL00216', 3),
('TBL00217', 3),
('TBL00218', 3),
('TBL00219', 3),
('TBL00220', 3),
('TBL00221', 3),
('TBL00222', 3),
('TBL00223', 3),
('TBL00224', 3),
('TBL00225', 3),
('TBL00226', 3),
('TBL00227', 3),
('TBL00228', 3),
('TBL00229', 3),
('TBL00230', 3),
('TBL00231', 3),
('TBL00232', 3),
('TBL00233', 3),
('TBL00234', 3),
('TBL00235', 3),
('TBL00236', 3),
('TBL00237', 3),
('TBL00238', 3),
('TBL00239', 3),
('TBL00240', 3),
('TBL00241', 3),
('TBL00242', 3),
('TBL00243', 3),
('TBL00244', 3),
('TBL00245', 3),
('TBL00246', 3),
('TBL00247', 3),
('TBL00248', 3),
('TBL00249', 3),
('TBL00250', 3);

-- --------------------------------------------------------

--
-- Table structure for table `table_schedule`
--

CREATE TABLE `table_schedule` (
  `schedule_id` int(11) NOT NULL,
  `table_id` varchar(10) DEFAULT NULL,
  `day_of_week` varchar(15) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `semester` varchar(40) NOT NULL,
  `skill_level` enum('Beginner','Intermediate','Advanced') NOT NULL,
  `department` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_type` enum('Resource','Group','Contributor') DEFAULT 'Resource'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `semester`, `skill_level`, `department`, `created_at`, `user_type`) VALUES
(1, 'noor', '125noor116@gmail.com', '$2y$10$VY5GFwpcAq2NK9JvrxByteabe7mN8vzbOoUa/cMlKUj5602IYHmbO', '3rd', 'Beginner', 'cse', '2026-04-28 18:58:18', 'Resource'),
(2, 'tanim', 'tanim@gmail.com', '$2y$10$yCR8Fm5VQeMO/NbaVCMqaeyjBGnYY5ABYG0/cc75PXH9MhUNT8lJK', '3rd', 'Advanced', 'cse', '2026-05-05 20:16:35', 'Resource'),
(3, 'asik', 'asik@gmail.com', '$2y$10$zM.ICd.mXC7uSXOnVGETQOKBmLPNg99NEbxVvmUMsbFGRzAw4sjrq', '3rd', 'Intermediate', 'cse', '2026-05-05 21:11:13', 'Resource');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `G_id` (`G_id`);

--
-- Indexes for table `join_req`
--
ALTER TABLE `join_req`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_joinreq_user` (`user_id`),
  ADD KEY `fk_joinreq_group` (`group_id`);

--
-- Indexes for table `m_ship`
--
ALTER TABLE `m_ship`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_membership` (`user_id`,`group_id`),
  ADD KEY `fk_mship_group` (`group_id`),
  ADD KEY `fk_mship_addedby` (`added_by`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_resource_user` (`user_id`),
  ADD KEY `fk_resource_group` (`group_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`table_id`);

--
-- Indexes for table `room_schedule`
--
ALTER TABLE `room_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `room_schedule_ibfk_1` (`table_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sessions_user` (`user_id`),
  ADD KEY `fk_sessions_group` (`group_id`);

--
-- Indexes for table `s_grp`
--
ALTER TABLE `s_grp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sgrp_creator` (`created_by`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `fk_group_table` (`table_id`);

--
-- Indexes for table `s_partner`
--
ALTER TABLE `s_partner`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_partner` (`user_id`,`partner_user_id`),
  ADD KEY `fk_partner_partner` (`partner_user_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`table_id`);

--
-- Indexes for table `table_schedule`
--
ALTER TABLE `table_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `join_req`
--
ALTER TABLE `join_req`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `m_ship`
--
ALTER TABLE `m_ship`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_schedule`
--
ALTER TABLE `room_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `s_grp`
--
ALTER TABLE `s_grp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `s_partner`
--
ALTER TABLE `s_partner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_schedule`
--
ALTER TABLE `table_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`G_id`) REFERENCES `s_grp` (`id`);

--
-- Constraints for table `join_req`
--
ALTER TABLE `join_req`
  ADD CONSTRAINT `fk_joinreq_group` FOREIGN KEY (`group_id`) REFERENCES `s_grp` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_joinreq_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `m_ship`
--
ALTER TABLE `m_ship`
  ADD CONSTRAINT `fk_mship_addedby` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_mship_group` FOREIGN KEY (`group_id`) REFERENCES `s_grp` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_mship_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `fk_resource_group` FOREIGN KEY (`group_id`) REFERENCES `s_grp` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_resource_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_schedule`
--
ALTER TABLE `room_schedule`
  ADD CONSTRAINT `room_schedule_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `rooms` (`table_id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `fk_sessions_group` FOREIGN KEY (`group_id`) REFERENCES `s_grp` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `s_grp`
--
ALTER TABLE `s_grp`
  ADD CONSTRAINT `fk_group_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`table_id`),
  ADD CONSTRAINT `fk_sgrp_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `s_grp_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`table_id`);

--
-- Constraints for table `s_partner`
--
ALTER TABLE `s_partner`
  ADD CONSTRAINT `fk_partner_partner` FOREIGN KEY (`partner_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_partner_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `table_schedule`
--
ALTER TABLE `table_schedule`
  ADD CONSTRAINT `table_schedule_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`table_id`);
COMMIT;

