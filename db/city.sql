-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.27 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table touchbase_web_dev_2014.city_reference
CREATE TABLE IF NOT EXISTS `city_reference` (
  `id` int(11) NOT NULL,
  `province` varchar(200) NOT NULL,
  `city` varchar(200) NOT NULL,
  `provinceid` varchar(11) DEFAULT NULL,
  `n_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table touchbase_web_dev_2014.city_reference: ~349 rows (approximately)
/*!40000 ALTER TABLE `city_reference` DISABLE KEYS */;
INSERT INTO `city_reference` (`id`, `province`, `city`, `provinceid`, `n_status`) VALUES
	(130, '(not specified)', '(not specified)', '000', 0),
	(131, 'nanggroe aceh darussalam', 'aceh', '001', 0),
	(132, 'jawa tengah', 'ambarawa', '011', 0),
	(133, 'maluku', 'ambon', '019', 0),
	(134, 'bali', 'amlapura', '002', 0),
	(135, 'sumatera utara', 'asahan', '032', 0),
	(136, 'bali', 'badung', '002', 0),
	(137, 'kalimantan timur', 'balikpapan', '016', 0),
	(138, 'nanggroe aceh darussalam', 'banda aceh', '001', 0),
	(139, 'lampung', 'bandar lampung', '018', 0),
	(140, 'jawa barat', 'bandung', '010', 0),
	(141, 'bangka-belitung', 'bangka barat', '003', 0),
	(142, 'bangka-belitung', 'bangka-belitung', '003', 0),
	(143, 'jawa timur', 'bangkalan', '012', 0),
	(144, 'riau', 'bangkinang', '024', 0),
	(145, 'bali', 'bangli', '002', 0),
	(146, 'jawa barat', 'banjar', '010', 0),
	(147, 'kalimantan selatan', 'banjar baru', '014', 0),
	(148, 'jawa barat', 'banjar negara', '010', 0),
	(149, 'kalimantan selatan', 'banjarmasin', '014', 0),
	(150, 'sulawesi selatan', 'bantaeng', '026', 0),
	(151, 'banten', 'banten', '004', 0),
	(152, 'di yogyakarta', 'bantul', '033', 0),
	(153, 'jawa tengah', 'banyumas', '011', 0),
	(154, 'jawa timur', 'banyuwangi', '012', 0),
	(155, 'sulawesi selatan', 'barru', '026', 0),
	(156, 'kepulauan riau', 'batam', '017', 0),
	(157, 'jawa tengah', 'batang', '011', 0),
	(158, 'jambi', 'batanghari', '009', 0),
	(159, 'jawa timur', 'batu', '012', 0),
	(160, 'sumatera selatan', 'batu raja', '031', 0),
	(161, 'sumatera barat', 'batusangkar', '030', 0),
	(162, 'sulawesi tenggara', 'bau-bau', '028', 0),
	(163, '(not specified)', 'bayurit', '000', 0),
	(164, 'jawa barat', 'bekasi', '010', 0),
	(165, 'sumatera utara', 'belawan', '032', 0),
	(166, 'bengkulu', 'bengkulu', '005', 0),
	(167, 'bengkulu', 'bengkulu selatan', '005', 0),
	(168, 'kalimantan timur', 'berau', '016', 0),
	(169, 'nusa tenggara barat', 'bima', '021', 0),
	(170, 'sumatera utara', 'binjai', '032', 0),
	(171, 'nanggroe aceh darussalam', 'bireun', '001', 0),
	(172, 'jawa timur', 'blitar', '012', 0),
	(173, 'jawa tengah', 'blora', '011', 0),
	(174, 'jawa barat', 'bogor', '010', 0),
	(175, 'jawa timur', 'bojonegoro', '012', 0),
	(176, 'jawa timur', 'bondowoso', '012', 0),
	(177, 'sulawesi selatan', 'bone', '026', 0),
	(178, 'kalimantan timur', 'bontang', '016', 0),
	(179, 'jawa tengah', 'boyolali', '011', 0),
	(180, 'sumatera utara', 'brastagi', '032', 0),
	(181, 'jawa tengah', 'brebes', '011', 0),
	(182, 'sumatera barat', 'bukit tinggi', '030', 0),
	(183, 'bali', 'buleleng', '002', 0),
	(184, 'sulawesi selatan', 'bulukumba', '026', 0),
	(185, 'kalimantan tengah', 'buntok', '015', 0),
	(186, 'banten', 'carita', '004', 0),
	(187, 'jawa tengah', 'cepu', '011', 0),
	(188, 'jawa barat', 'ciamis', '010', 0),
	(189, 'jawa barat', 'cianjur', '010', 0),
	(190, 'jawa barat', 'cibinong', '010', 0),
	(191, 'jawa barat', 'cibitung', '010', 0),
	(192, 'jawa tengah', 'cilacap', '011', 0),
	(193, 'banten', 'ciledug', '004', 0),
	(194, 'banten', 'cilegon', '004', 0),
	(195, 'jawa barat', 'cimahi', '010', 0),
	(196, 'jawa barat', 'cirebon', '010', 0),
	(197, 'jawa barat', 'debotabek', '010', 0),
	(198, 'jawa tengah', 'demak', '011', 0),
	(199, 'bali', 'denpasar', '002', 0),
	(200, 'jawa barat', 'depok', '010', 0),
	(201, 'nusa tenggara barat', 'dompu', '021', 0),
	(202, 'riau', 'dumai', '024', 0),
	(203, 'sulawesi selatan', 'enrekang', '026', 0),
	(204, '(not specified)', 'gabahan', '000', 0),
	(205, 'jawa barat', 'garut', '010', 0),
	(206, 'bali', 'gianyar', '002', 0),
	(207, 'jawa tengah', 'gombong', '011', 0),
	(208, 'gorontalo', 'gorontalo', '006', 0),
	(209, 'sulawesi selatan', 'gowa', '026', 0),
	(210, 'jawa timur', 'gresik', '012', 0),
	(211, 'jawa tengah', 'grobogan', '011', 0),
	(212, 'kalimantan timur', 'grogot', '016', 0),
	(213, 'jawa barat', 'indramayu', '010', 0),
	(214, 'jawa barat', 'jabotabek', '010', 0),
	(215, 'jakarta raya', 'jakarta', '008', 0),
	(216, 'jambi', 'jambi', '009', 0),
	(217, 'papua', 'jayapura', '023', 0),
	(218, 'jawa timur', 'jember', '012', 0),
	(219, 'bali', 'jembrana', '002', 0),
	(220, 'sulawesi selatan', 'jeneponto', '026', 0),
	(221, 'jawa tengah', 'jepara', '011', 0),
	(222, 'bali', 'jimbaran', '002', 0),
	(223, 'jawa timur', 'jombang', '012', 0),
	(224, 'sumatera utara', 'kaban jahe', '032', 0),
	(225, 'riau', 'kampar', '024', 0),
	(227, 'jawa barat', 'karawang', '010', 0),
	(228, 'sumatera selatan', 'kayu agung', '031', 0),
	(229, 'jawa tengah', 'kebumen', '011', 0),
	(230, 'jawa timur', 'kediri', '012', 0),
	(231, 'jawa tengah', 'kedung turi', '011', 0),
	(232, '(not specified)', 'kelusa', '000', 0),
	(233, 'jawa tengah', 'kendal', '011', 0),
	(234, 'sulawesi tenggara', 'kendari', '028', 0),
	(236, 'jambi', 'kerinci', '009', 0),
	(237, 'kalimantan barat', 'ketapang', '013', 0),
	(238, 'sumatera utara', 'kisaran', '032', 0),
	(239, 'jawa tengah', 'klaten', '011', 0),
	(240, 'bali', 'klungkung', '002', 0),
	(241, 'sulawesi tenggara', 'kolaka', '028', 0),
	(242, 'sulawesi tenggara', 'konawe', '028', 0),
	(243, 'kalimantan selatan', 'kota baru', '014', 0),
	(244, 'sulawesi utara', 'kotamobagu', '029', 0),
	(245, 'jawa tengah', 'kudus', '011', 0),
	(246, 'kalimantan timur', 'kukar', '016', 0),
	(247, 'di yogyakarta', 'kulon progo', '033', 0),
	(248, 'jawa barat', 'kuningan', '010', 0),
	(249, 'nusa tenggara timur', 'kupang', '022', 0),
	(250, 'kalimantan timur', 'kutai barat', '016', 0),
	(251, 'kalimantan timur', 'kutai kartanegara', '016', 0),
	(252, 'sumatera utara', 'labuhan batu', '032', 0),
	(253, 'sumatera selatan', 'lahat', '031', 0),
	(254, 'jawa timur', 'lamongan', '012', 0),
	(255, 'lampung', 'lampung', '018', 0),
	(256, 'sumatera utara', 'langkat', '032', 0),
	(257, 'nanggroe aceh darussalam', 'langsa', '001', 0),
	(258, 'jawa tengah', 'larangan', '011', 0),
	(259, 'jawa timur', 'lawang', '012', 0),
	(260, 'jawa barat', 'lembang', '010', 0),
	(261, 'nanggroe aceh darussalam', 'lhokseumawe', '001', 0),
	(262, 'nusa tenggara barat', 'lombok', '021', 0),
	(263, '(not specified)', 'longkai', '000', 0),
	(264, 'sumatera selatan', 'lubuk linggau', '031', 0),
	(265, 'sumatera utara', 'lubuk pakam', '032', 0),
	(266, 'jawa timur', 'lumajang', '012', 0),
	(267, '(not specified)', 'lumteng', '000', 0),
	(268, 'sulawesi selatan', 'luwu', '026', 0),
	(269, 'sulawesi selatan', 'luwu timur', '026', 0),
	(270, 'sulawesi selatan', 'luwu utara', '026', 0),
	(271, 'jawa timur', 'madiun', '012', 0),
	(272, 'jawa timur', 'madura', '012', 0),
	(273, 'jawa tengah', 'magelang', '011', 0),
	(274, 'jawa timur', 'magetan', '012', 0),
	(275, 'jawa barat', 'majalengka', '010', 0),
	(276, 'jawa tengah', 'majenang', '011', 0),
	(277, 'sulawesi barat', 'majene', '025', 0),
	(279, 'jawa timur', 'malang', '012', 0),
	(280, '(not specified)', 'malbungo', '000', 0),
	(281, 'kalimantan timur', 'malinau', '016', 0),
	(282, 'sulawesi barat', 'mamuju', '025', 0),
	(283, 'sulawesi utara', 'manado', '029', 0),
	(284, 'sumatera utara', 'mandailing natal', '032', 0),
	(285, 'riau', 'mandau', '024', 0),
	(286, 'sulawesi selatan', 'mangkutana', '026', 0),
	(287, 'irian jaya barat', 'manokwari barat', '007', 0),
	(288, 'sulawesi selatan', 'maros', '026', 0),
	(289, 'kalimantan selatan', 'martapura', '014', 0),
	(290, 'nusa tenggara barat', 'mataram', '021', 0),
	(291, 'nusa tenggara timur', 'maumere', '022', 0),
	(292, 'sumatera utara', 'medan', '032', 0),
	(293, 'bali', 'mengwi', '002', 0),
	(294, 'papua', 'merauke', '023', 0),
	(295, 'lampung', 'metro', '018', 0),
	(296, 'sulawesi utara', 'minahasa', '029', 0),
	(297, 'jawa timur', 'mojokerto', '012', 0),
	(298, 'sumatera selatan', 'muara enim', '031', 0),
	(299, 'jawa tengah', 'muntilan', '011', 0),
	(300, 'sumatera selatan', 'muntok', '031', 0),
	(301, 'sumatera selatan', 'musi banyuasin', '031', 0),
	(302, 'papua', 'nabire', '023', 0),
	(303, 'bali', 'negara', '002', 0),
	(304, 'di yogyakarta', 'ngaglik', '033', 0),
	(305, 'jawa timur', 'nganjuk', '012', 0),
	(306, 'jawa timur', 'ngawi', '012', 0),
	(307, 'sumatera utara', 'nias', '032', 0),
	(309, 'kalimantan timur', 'nunukan', '016', 0),
	(310, 'bali', 'nusa dua', '002', 0),
	(311, 'sumatera selatan', 'oki', '031', 0),
	(312, 'jawa timur', 'pacitan', '012', 0),
	(313, 'sumatera barat', 'padang', '030', 0),
	(314, 'sumatera utara', 'padang sidempuan', '032', 0),
	(315, 'sumatera selatan', 'pagar alam', '031', 0),
	(316, '(not specified)', 'pairi', '000', 0),
	(317, 'kalimantan tengah', 'palangkaraya', '015', 0),
	(318, 'sumatera selatan', 'palembang', '031', 0),
	(319, 'sulawesi selatan', 'palopo', '026', 0),
	(320, 'sulawesi tengah', 'palu', '027', 0),
	(321, 'jawa timur', 'pamekasan', '012', 0),
	(322, '(not specified)', 'panakan', '000', 0),
	(323, 'banten', 'pandeglang', '004', 0),
	(324, 'bangka-belitung', 'pangkal pinang', '003', 0),
	(325, 'sulawesi selatan', 'pangkejene', '026', 0),
	(326, 'sulawesi selatan', 'pangkep', '026', 0),
	(327, '(not specified)', 'panjer', '000', 0),
	(328, 'sulawesi selatan', 'pare-pare', '026', 0),
	(329, 'kalimantan timur', 'pasir pengarayan', '016', 0),
	(330, 'jawa timur', 'pasuruan', '012', 0),
	(331, 'jawa tengah', 'pati', '011', 0),
	(332, 'sumatera utara', 'patumbak', '032', 0),
	(333, 'sumatera barat', 'payakumbuh', '030', 0),
	(334, 'jawa tengah', 'pekalongan', '011', 0),
	(335, 'riau', 'pekanbaru', '024', 0),
	(336, 'riau', 'pelalawan', '024', 0),
	(337, 'jawa tengah', 'pemalang', '011', 0),
	(338, 'sumatera utara', 'pematang siantar', '032', 0),
	(339, '(not specified)', 'pematang tebih', '000', 0),
	(340, '(not specified)', 'pengkor', '000', 0),
	(341, 'sumatera utara', 'perbaungan', '032', 0),
	(342, 'nanggroe aceh darussalam', 'pidie', '001', 0),
	(343, 'sulawesi selatan', 'pinrang', '026', 0),
	(344, 'sulawesi selatan', 'polmas', '026', 0),
	(345, '(not specified)', 'polmas semarang', '000', 0),
	(346, 'jawa timur', 'ponorogo', '012', 0),
	(347, 'kalimantan barat', 'pontianak', '013', 0),
	(348, 'sulawesi tengah', 'poso', '027', 0),
	(349, 'sumatera selatan', 'prabumulih', '031', 0),
	(350, 'jawa timur', 'probolinggo', '012', 0),
	(351, 'jawa tengah', 'purbalingga', '011', 0),
	(352, 'jawa barat', 'purwakarta', '010', 0),
	(353, 'jawa tengah', 'purwodadi', '011', 0),
	(354, 'jawa tengah', 'purwokerto', '011', 0),
	(355, 'jawa tengah', 'purworejo', '011', 0),
	(356, 'banten', 'rangkasbitung', '004', 0),
	(357, 'sumatera utara', 'rantau prapat', '032', 0),
	(358, 'sulawesi selatan', 'rappang', '026', 0),
	(359, 'bengkulu', 'rejang lebong', '005', 0),
	(360, 'jawa tengah', 'rembang', '011', 0),
	(361, 'riau', 'rengat', '024', 0),
	(362, 'riau', 'riau', '024', 0),
	(363, 'jawa tengah', 'salatiga', '011', 0),
	(364, 'kalimantan timur', 'samarinda', '016', 0),
	(365, 'jawa timur', 'sampang', '012', 0),
	(366, 'kalimantan tengah', 'sampit', '015', 0),
	(367, 'kalimantan barat', 'sanggau', '013', 0),
	(368, 'bali', 'sanur', '002', 0),
	(369, 'jawa barat', 'sawangan', '010', 0),
	(370, 'sumatera selatan', 'sekayu', '031', 0),
	(371, 'sulawesi selatan', 'selayar', '026', 0),
	(373, 'jawa tengah', 'semarang', '011', 0),
	(374, 'bali', 'seminyak', '002', 0),
	(375, 'sulawesi selatan', 'sengkang', '026', 0),
	(376, 'banten', 'serang', '004', 0),
	(377, 'sumatera utara', 'serdan badagai', '032', 0),
	(378, 'banten', 'serpong', '004', 0),
	(379, 'bali', 'sesetan', '002', 0),
	(380, '(not specified)', 'sianta', '000', 0),
	(381, 'sumatera utara', 'sibolga', '032', 0),
	(382, 'jawa timur', 'sidoarjo', '012', 0),
	(383, 'sulawesi selatan', 'sidrap', '026', 0),
	(384, 'nanggroe aceh darussalam', 'sigli', '001', 0),
	(385, 'bali', 'singaraja', '002', 0),
	(386, 'kalimantan barat', 'singkawang', '013', 0),
	(387, 'sulawesi selatan', 'sinjai', '026', 0),
	(388, 'jawa timur', 'situbondo', '012', 0),
	(389, 'jawa tengah', 'slawi', '011', 0),
	(390, 'di yogyakarta', 'sleman', '033', 0),
	(391, 'sumatera barat', 'solok', '030', 0),
	(392, 'sulawesi selatan', 'soppeng', '026', 0),
	(393, 'jawa barat', 'soreang', '010', 0),
	(394, 'irian jaya barat', 'sorong', '007', 0),
	(395, 'sulawesi selatan', 'sorowako', '026', 0),
	(396, 'jawa tengah', 'sragen', '011', 0),
	(397, 'sumatera utara', 'stabat', '032', 0),
	(398, 'jawa barat', 'subang', '010', 0),
	(399, 'jawa barat', 'sukabumi', '010', 0),
	(400, 'jawa timur', 'sukodono', '012', 0),
	(401, 'jawa tengah', 'sukoharjo', '011', 0),
	(402, 'nusa tenggara timur', 'sumba barat', '022', 0),
	(403, 'nusa tenggara barat', 'sumbawa', '021', 0),
	(404, 'jawa barat', 'sumedang', '010', 0),
	(405, 'jawa timur', 'sumenep', '012', 0),
	(406, 'sulawesi selatan', 'sungguminasa', '026', 0),
	(407, 'jawa timur', 'surabaya', '012', 0),
	(409, 'kalimantan selatan', 'tabalong', '014', 0),
	(410, 'bali', 'tabanan', '002', 0),
	(411, 'sulawesi selatan', 'takalar', '026', 0),
	(412, 'sulawesi selatan', 'tana toraja', '026', 0),
	(413, 'kalimantan timur', 'tanah grogot', '016', 0),
	(414, 'banten', 'tangerang', '004', 0),
	(415, 'sumatera utara', 'tanjung balai', '032', 0),
	(416, 'sumatera selatan', 'tanjung enim', '031', 0),
	(417, 'kepulauan riau', 'tanjung pinang', '017', 0),
	(418, 'sumatera selatan', 'tanjung raja', '031', 0),
	(419, 'kalimantan timur', 'tanjung redeb', '016', 0),
	(420, 'kalimantan timur', 'tanjung selor', '016', 0),
	(421, 'sumatera utara', 'tapanuli', '032', 0),
	(422, 'kalimantan timur', 'tarakan', '016', 0),
	(423, 'sumatera utara', 'tarutung', '032', 0),
	(424, 'jawa barat', 'tasikmalaya', '010', 0),
	(425, 'sumatera utara', 'tebing tinggi', '032', 0),
	(426, 'jawa tengah', 'tegal', '011', 0),
	(427, 'jawa tengah', 'temanggung', '011', 0),
	(429, 'riau', 'tembilahan', '024', 0),
	(430, 'kalimantan timur', 'tenggarong', '016', 0),
	(431, 'maluku utara', 'ternate', '020', 0),
	(432, 'maluku utara', 'ternate utama', '020', 0),
	(433, 'kepulauan riau', 'tg uban', '017', 0),
	(434, 'papua', 'timika', '023', 0),
	(435, 'sumatera utara', 'toba samosir', '032', 0),
	(436, 'sulawesi tengah', 'toli toli', '027', 0),
	(437, 'sulawesi utara', 'tomohon', '029', 0),
	(438, 'jawa timur', 'trenggalek', '012', 0),
	(439, '(not specified)', 'ts mandala', '000', 0),
	(440, 'jawa timur', 'tuban', '012', 0),
	(441, 'jawa timur', 'tulung agung', '012', 0),
	(442, 'sulawesi selatan', 'ujung pandang', '026', 0),
	(443, 'jawa tengah', 'ungaran', '011', 0),
	(444, 'sulawesi selatan', 'wajo', '026', 0),
	(445, 'papua', 'wamena', '023', 0),
	(446, 'jawa timur', 'waru', '012', 0),
	(447, 'di yogyakarta', 'wates', '033', 0),
	(448, 'jawa tengah', 'wonogiri', '011', 0),
	(449, 'di yogyakarta', 'wonosari', '033', 0),
	(450, 'jawa tengah', 'wonosobo', '011', 0),
	(451, 'di yogyakarta', 'yogyakarta', '033', 0),
	(452, 'nanggroe aceh darussalam', 'aceh timur', '001', 0),
	(453, 'bangka-belitung', 'bangka selatan', '003', 0),
	(454, 'papua', 'biak numfor', '023', 0),
	(455, 'sumatera utara', 'dairi', '032', 0),
	(456, 'jakarta raya', 'jakarta timur', '008', 0),
	(457, 'sumatera utara', 'karo', '032', 0),
	(458, 'lampung', 'lampung timur', '018', 0),
	(459, 'sumatera selatan', 'ogan ilir', '031', 0),
	(460, 'kalimantan timur', 'penajam paser utara', '016', 0),
	(461, 'sulawesi barat', 'polewali mandar', '025', 0),
	(462, 'riau', 'rokan hulu', '024', 0),
	(463, 'riau', 'siak', '024', 0),
	(464, 'sumatera utara', 'tapanuli utara', '032', 0),
	(465, 'sumatera utara', 'deli serdang', '032', 0),
	(466, 'bali', 'karang asem', '002', 0),
	(468, 'jawa tengah', 'solo', '011', 0),
	(470, 'jawa barat', 'jakarta', '010', 0),
	(471, 'di yogyakarta', 'magelang', '033', 0),
	(472, 'jawa tengah', 'sanggau', '011', 0),
	(473, 'bali', 'semarapura', '002', 0),
	(474, 'jawa tengah', 'sleman', '011', 0),
	(475, 'jawa barat', 'bayurit', '010', 0),
	(476, 'jawa tengah', 'cibinong', '011', 0),
	(477, 'kalimantan tengah', 'jepara', '015', 0),
	(478, 'kalimantan tengah', 'kudus', '015', 0),
	(479, 'jawa tengah', 'seminyak', '011', 0),
	(2179, 'jambi', '(not specified)', '009', 0),
	(2180, 'jawa barat', '(not specified)', '010', 0),
	(2181, 'kalimantan selatan', '(not specified)', '014', 0),
	(2182, 'lampung', '(not specified)', '018', 0),
	(2183, 'maluku utara', '(not specified)', '020', 0),
	(2184, 'sulawesi selatan', '(not specified)', '026', 0),
	(2185, 'sumatera utara', '(not specified)', '032', 0),
	(2186, 'nanggroe aceh darussalam', 'aceh singkil', '001', 0),
	(2187, 'nanggroe aceh darussalam', 'aceh tamiang', '001', 0),
	(2188, 'sumatera utara', 'aek natas', '032', 0),
	(2189, 'sumatera utara', 'aek nopan', '032', 0),
	(2190, 'riau', 'air molek', '024', 0),
	(2191, 'riau', 'air tiris', '024', 0),
	(2192, 'kalimantan selatan', 'amuntai', '014', 0),
	(2193, 'kalimantan timur', 'anggana', '016', 0),
	(2194, 'nusa tenggara timur', 'atambua', '022', 0),
	(2195, 'riau', 'bagan batu', '024', 0),
	(2196, 'riau', 'bagan siapiapi', '024', 0),
	(2197, 'nusa tenggara timur', 'bajawa', '022', 0),
	(2198, 'sumatera utara', 'balige', '032', 0),
	(2199, 'kalimantan timur', 'balikpapan selatan', '016', 0),
	(2200, 'kalimantan timur', 'balikpapan timur', '016', 0),
	(2201, 'sumatera utara', 'bandar baru', '032', 0),
	(2202, 'lampung', 'bandar jaya', '018', 0),
	(2204, 'jambi', 'bangko', '009', 0),
	(2205, 'kalimantan selatan', 'banjar', '014', 0),
	(2206, 'kalimantan selatan', 'banjarmasin timur', '014', 0),
	(2207, 'kalimantan selatan', 'banjarmasin utara', '014', 0),
	(2208, 'jawa tengah', 'banjarnegara', '011', 0),
	(2209, 'sumatera selatan', 'banyuasin', '031', 0),
	(2210, 'kalimantan selatan', 'barabai', '014', 0),
	(2211, 'kalimantan selatan', 'barito kuala', '014', 0),
	(2212, 'kalimantan selatan', 'barito timur', '014', 0),
	(2213, 'kalimantan timur', 'barongtongkok', '016', 0),
	(2215, 'riau', 'bengkalis', '024', 0),
	(2216, 'nanggroe aceh darussalam', 'blang pidie', '001', 0),
	(2217, 'kalimantan timur', 'bulungan', '016', 0),
	(2218, 'jawa barat', 'cicalengka', '010', 0),
	(2219, 'jawa barat', 'cigugur', '010', 0),
	(2220, 'banten', 'ciputat', '004', 0),
	(2222, 'nusa tenggara timur', 'ende', '022', 0),
	(2223, 'papua', 'fakfak', '023', 0),
	(2224, 'nusa tenggara timur', 'flores', '022', 0),
	(2226, 'kalimantan selatan', 'hulu sungai selatan', '014', 0),
	(2227, 'riau', 'indragiri hilir', '024', 0),
	(2228, 'jakarta raya', 'jakarta barat', '008', 0),
	(2229, 'jakarta raya', 'jakarta pusat', '008', 0),
	(2230, 'jakarta raya', 'jakarta selatan', '008', 0),
	(2232, 'jakarta raya', 'jakarta utara', '008', 0),
	(2233, 'lampung', 'kalianda', '018', 0),
	(2234, 'lampung', 'kalirejo', '018', 0),
	(2235, 'kalimantan tengah', 'kapuas', '015', 0),
	(2236, 'jawa tengah', 'karanganyar', '011', 0),
	(2237, 'jawa tengah', 'kartosuro', '011', 0),
	(2238, 'lampung', 'kedaton', '018', 0),
	(2239, 'jawa tengah', 'kertosono', '011', 0),
	(2240, 'lampung', 'kota gajah', '018', 0),
	(2241, 'lampung', 'kotabumi', '018', 0),
	(2242, 'lampung', 'krui', '018', 0),
	(2243, 'riau', 'kuala enok', '024', 0),
	(2244, 'kalimantan tengah', 'kuala kapuas', '015', 0),
	(2245, 'nanggroe aceh darussalam', 'kuala simpang', '001', 0),
	(2246, 'riau', 'kubu', '024', 0),
	(2247, 'sumatera barat', 'kuok', '030', 0),
	(2248, 'nanggroe aceh darussalam', 'kutacane', '001', 0),
	(2249, 'kalimantan timur', 'kutai timur', '016', 0),
	(2250, 'jawa tengah', 'kutoarjo', '011', 0),
	(2251, 'sumatera utara', 'lagu boti', '032', 0),
	(2252, 'lampung', 'lampung selatan', '018', 0),
	(2253, 'lampung', 'lampung tengah', '018', 0),
	(2254, 'lampung', 'lampung utara', '018', 0),
	(2255, 'kalimantan barat', 'landak', '013', 0),
	(2256, 'banten', 'lebak', '004', 0),
	(2257, 'nusa tenggara timur', 'lembata', '022', 0),
	(2258, 'sumatera barat', 'lima puluh kota', '030', 0),
	(2259, 'gorontalo', 'limboto', '006', 0),
	(2260, 'riau', 'lirik', '024', 0),
	(2261, 'lampung', 'liwa', '018', 0),
	(2262, 'kalimantan timur', 'loa janan', '016', 0),
	(2263, 'kalimantan timur', 'loa kulu', '016', 0),
	(2264, 'sulawesi tengah', 'luwuk', '027', 0),
	(2265, 'sulawesi selatan', 'makassar', '026', 0),
	(2266, 'papua', 'manokwari', '023', 0),
	(2267, 'kalimantan selatan', 'marabahan', '014', 0),
	(2268, 'sumatera utara', 'medan deli', '032', 0),
	(2269, 'sumatera utara', 'medan perjuangan', '032', 0),
	(2270, 'kalimantan timur', 'melak ulu', '016', 0),
	(2271, 'kalimantan barat', 'mempawah', '013', 0),
	(2272, 'jambi', 'merangin', '009', 0),
	(2273, 'nanggroe aceh darussalam', 'meulaboh', '001', 0),
	(2274, 'kalimantan timur', 'muara badak', '016', 0),
	(2275, 'kalimantan timur', 'muara jawa', '016', 0),
	(2276, 'kalimantan timur', 'muara kaman', '016', 0),
	(2277, 'jambi', 'muaro jambi', '009', 0),
	(2278, 'sumatera selatan', 'musi rawas', '031', 0),
	(2279, 'lampung', 'natar', '018', 0),
	(2280, 'kalimantan barat', 'ngabang', '013', 0),
	(2281, 'nusa tenggara timur', 'ngada', '022', 0),
	(2282, 'sumatera selatan', 'ogan komering ilir', '031', 0),
	(2283, 'sumatera selatan', 'oku timur', '031', 0),
	(2284, 'sumatera barat', 'padang panjang', '030', 0),
	(2285, 'kalimantan selatan', 'pagatan', '014', 0),
	(2286, 'lampung', 'pagelaran', '018', 0),
	(2287, 'di yogyakarta', 'pakem', '033', 0),
	(2288, 'jawa barat', 'pamanukan', '010', 0),
	(2289, 'jawa barat', 'pangandaran', '010', 0),
	(2290, 'kalimantan tengah', 'pangkalan bun', '015', 0),
	(2291, 'sumatera utara', 'parapat', '032', 0),
	(2292, 'jawa timur', 'pare', '012', 0),
	(2293, 'sulawesi tengah', 'parigi', '027', 0),
	(2294, 'kalimantan selatan', 'paringin', '014', 0),
	(2295, 'sumatera utara', 'pasar baru', '032', 0),
	(2296, 'jawa barat', 'pelabuhan ratu', '010', 0),
	(2297, 'kalimantan barat', 'pemangkat', '013', 0),
	(2299, 'kalimantan selatan', 'pleihari', '014', 0),
	(2300, 'kalimantan barat', 'pontianak selatan', '013', 0),
	(2301, 'kalimantan barat', 'pontianak timur', '013', 0),
	(2302, 'jawa timur', 'porong', '012', 0),
	(2303, 'lampung', 'pringsewu', '018', 0),
	(2304, 'nusa tenggara barat', 'pujut', '021', 0),
	(2305, 'di yogyakarta', 'pundong', '033', 0),
	(2306, 'jawa timur', 'purwosari', '012', 0),
	(2307, 'kalimantan barat', 'putussibau', '013', 0),
	(2308, 'riau', 'rokan', '024', 0),
	(2309, 'nusa tenggara barat', 'sakra barat', '021', 0),
	(2310, 'kalimantan timur', 'samarinda ilir', '016', 0),
	(2311, 'kalimantan timur', 'samarinda ulu', '016', 0),
	(2312, 'kalimantan timur', 'samarinda utara', '016', 0),
	(2313, 'kalimantan tengah', 'sambas', '015', 0),
	(2314, 'kalimantan timur', 'sanga-sanga', '016', 0),
	(2315, 'kalimantan timur', 'sangatta', '016', 0),
	(2316, 'kalimantan timur', 'sangkulirang', '016', 0),
	(2317, 'sumatera utara', 'saribu dolok', '032', 0),
	(2318, 'jambi', 'sarolangun', '009', 0),
	(2319, 'sumatera barat', 'sawahlunto', '030', 0),
	(2320, 'kalimantan timur', 'sebulu', '016', 0),
	(2321, 'sulawesi selatan', 'segeri', '026', 0),
	(2322, 'sumatera utara', 'sei rampah', '032', 0),
	(2323, 'kalimantan barat', 'sekura', '013', 0),
	(2324, 'riau', 'selat panjang', '024', 0),
	(2325, 'bali', 'sempidi', '002', 0),
	(2326, 'jambi', 'sengeti', '009', 0),
	(2327, 'sumatera utara', 'siborong-borong', '032', 0),
	(2328, 'sumatera utara', 'sidamanik', '032', 0),
	(2329, 'jawa timur', 'sidayu', '012', 0),
	(2330, 'jawa barat', 'singaparna', '010', 0),
	(2331, 'sumatera barat', 'singkarak', '030', 0),
	(2332, 'sulawesi selatan', 'sinjai utara', '026', 0),
	(2333, 'kalimantan barat', 'sintang', '013', 0),
	(2334, 'riau', 'sorek', '024', 0),
	(2335, 'lampung', 'sukadana', '018', 0),
	(2336, 'nusa tenggara timur', 'sumba', '022', 0),
	(2337, 'riau', 'sungai pakning', '024', 0),
	(2338, 'jambi', 'sungai penuh', '009', 0),
	(2339, 'sulawesi selatan', 'suroako', '026', 0),
	(2340, 'nanggroe aceh darussalam', 'takengon', '001', 0),
	(2341, 'kalimantan selatan', 'tanah bumbu', '014', 0),
	(2342, 'sumatera utara', 'tanah jawa', '032', 0),
	(2343, 'lampung', 'tanggamus', '018', 0),
	(2344, 'lampung', 'tanjung bintang', '018', 0),
	(2345, 'lampung', 'tanjung karang', '018', 0),
	(2346, 'sumatera utara', 'tanjung tiram', '032', 0),
	(2347, 'bangka-belitung', 'tanjungpandan', '003', 0),
	(2348, 'sumatera utara', 'tapanuli selatan', '032', 0),
	(2349, 'jambi', 'telanai pura', '009', 0),
	(2350, 'riau', 'teluk kuatan', '024', 0),
	(2352, 'riau', 'tg. balai karimun', '024', 0),
	(2353, 'riau', 'tg. batu', '024', 0),
	(2354, 'kalimantan timur', 'tg. redep', '016', 0),
	(2355, 'maluku utara', 'tidore', '020', 0),
	(2356, 'sumatera utara', 'tigarunggu', '032', 0),
	(2357, 'lampung', 'tulang bawang', '018', 0),
	(2358, 'jawa timur', 'tulungagung', '012', 0),
	(2359, 'jambi', 'tungkal ilir', '009', 0),
	(2360, 'sulawesi selatan', 'watampone', '026', 0),
	(2362, 'lampung', 'way jepara', '018', 0),
	(2363, 'lampung', 'way kanan', '018', 0),
	(2364, 'lampung', 'way terusan nunyai', '018', 0),
	(3117, 'jawa barat', 'test', '010', 0),
	(3840, '(not specified)', 'unknown', '000', 0),
	(3841, 'banten', 'serpong', '004', 0),
	(3846, 'sumatera barat', 'sb', '030', 0),
	(3847, 'luoyang', 'louyang.city', '080', 0),
	(3848, 'bali', 'reyboy', '002', 0),
	(6666, 'dki jakarta', 'head office', '008', 1);
/*!40000 ALTER TABLE `city_reference` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
