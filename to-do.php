sql log:

	 public function sqllog($title, $comment) {
		 $oDb = oxDb::getDb();
		 $qTitle = $oDb->quote($title);
		 $qComment = $oDb->quote($comment);
		 $sQuery = "INSERT INTO hdi_log (title, comment) VALUES ( $qTitle, $qComment)";
		 $oDb->execute($sQuery);
	 }

$this->sqllog($sFile, "searching blox");
$this->sqllog($sFile, "blox found: ". $rs->fiels['OXMODULE'] . " => " . $rs->fields['OXFILE']);



################################################################################