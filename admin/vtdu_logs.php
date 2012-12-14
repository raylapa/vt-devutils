<?php

class vtdu_logs extends oxAdminView {

   protected $_tpl = 'vtdu_logs.tpl';
   protected $_sExLogPath;
   protected $_sErrorLogPath;
   protected $_sMysqlLogPath;
   protected $_sMailsLogPath;
   
   
   public function init() {
		parent::init();
      $cfg = $this->getConfig();
      $this->_sExLogPath = $cfg->getConfigParam('sShopDir') . '/log/EXCEPTION_LOG.txt';
      $this->_sErrorLogPath = ( $cfg->getConfigParam('sErrorLogPath') != '' ) ? $cfg->getConfigParam('sErrorLogPath') : false;
      $this->_sMysqlLogPath = $cfg->getConfigParam('sMysqlLogPath');
      $this->_sMailsLogPath = $cfg->getConfigParam('sMailsLogPath');
   }

   public function render() {
      $myConfig = $this->getConfig();

      
      var_dump($this->_sExLogPath);
      echo "<hr/>";
      var_dump($this->_sErrorLogPath);
      echo "<hr/>";
      var_dump($this->_sMysqlLogPath);
      echo "<hr/>";
      var_dump($this->_sMailsLogPath);
      
      $this->getErrorLog();
      
      $this->_aViewData['ExceltionLog'] = "logs";

      parent::render();

      return $this->_tpl;
   }

   public function getExceptionLog() {
      $cfg = $this->getConfig();
      $sExLog = $cfg->getConfigParam('sShopDir') . '/log/EXCEPTION_LOG.txt';
      
      $mdaExs = array();
      $arrX = 0;
      foreach (file($sExLog) as $line) {
         if(strpos($line,"--------------------------------------------") !== false) {
            $arrX++;
         } elseif ( strlen(trim($line)) != 0) {
            $mdaExs[$arrX][] = trim($line);
         }
      }
      echo"<pre>";
      foreach($mdaExs as $aEx) {
         $xTime = strpos($aEx[0], "(time:")+7;
         $yTime = strpos($aEx[0],"): [0]");
         echo "<p><strong>$xTime -> $yTime</strong></br>";
         echo substr($aEx[0], $xTime, $yTime-$xTime) ." ".substr($aEx[0], $yTime+7)."<br/>".end($aEx)."<br/>";
         // getting filename
         $sExFile = substr($aEx[2], 3, strpos($aEx[2], "(")-3);
         $iExRow = substr($aEx[2], strpos($aEx[2], "(")+1, strpos($aEx[2], ")")-strpos($aEx[2], "(")-1);
         echo "Datei: $sExFile<br/>";
         echo "Zeile: $iExRow<br/>";
         $aExFile = file($sExFile);
         echo "<pre>";
         echo ($iExRow-3)."   ".$aExFile[$iExRow-3];
         echo ($iExRow-2)."   ".$aExFile[$iExRow-2];
         echo ($iExRow-1)."   <strong style='color:red;'>".$aExFile[$iExRow-1]."</strong>";
         echo ($iExRow)  ."   ".$aExFile[$iExRow];
         echo ($iExRow+1)."   ".$aExFile[$iExRow+1];
         echo"</pre>";
         echo"</p><hr/>";
      }
      //var_dump($aContent);
      echo"</pre>";
   }
	
	public function getErrorLog() {
		if(!$this->_sErrorLogPath) return false;
		$aErrorLog = file($this->_sErrorLogPath);
		$aParsedLog = array();
		$x = 0;
		/*foreach($aErrorLog as $row) {
			$aParsedLog[] = array( );
		} */
		
		echo"<pre>";
		for($i = count($aErrorLog)-10;$i<count($aErrorLog);$i++) {
			echo $aErrorLog[$i];
		}
		echo "</pre>";
	}

}