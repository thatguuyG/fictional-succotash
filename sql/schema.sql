

CREATE TABLE `csv_data` (
  `id` int(11) NOT NULL,
  `invoiceNo` int(8) NOT NULL,
  `stockCode` varchar(55) NOT NULL,
  `quantity` int(8) NOT NULL,
  `invoiceDate` Date,
  `unitPrice` varchar(255) NOT NULL,
  `customerId` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `csv_data`
--
ALTER TABLE `csv_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `csv_data`
--
ALTER TABLE `csv_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
