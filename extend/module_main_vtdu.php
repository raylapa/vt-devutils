<?php

	class module_main_vtdu extends module_main_vtdu_parent
	{

		public function resetModuleSettings()
		{
			$myConfig = $this->getConfig();
			$oDb = oxDb::getDb();

			$sModuleId = $this->getEditObjectId();
			$sModuleQ = $oDb->quote("module:" . $sModuleId);

			$sShopId = $myConfig->getShopId();
			$sShopIdQ = $oDb->quote($sShopId);

			// delete all settings from oxconfig
			$sQoxconfig = "DELETE FROM oxconfig WHERE oxshopid=$sShopIdQ AND oxmodule=$sModuleQ";
			//var_dump($sQoxconfig);
			$oDb->execute($sQoxconfig);

			// delete from oxconfigdisplay
			$sQoxconfigdisplay = "DELETE FROM oxconfigdisplay WHERE OXCFGMODULE=$sModuleQ";
			//var_dump($sQoxconfigdisplay);
			$oDb->execute($sQoxconfigdisplay);

			// adding module settings
			$oModule = oxnew("oxmodule");
			$oModule->load($sModuleId);
			$oModule->addModuleSettings();

			$this->_aViewData["message"] = "module settings for $sModuleId were resetted";
		}

		public function resetModuleExtends()
		{
			$cfg = oxRegistry::getConfig();
			$sModuleId = $this->getEditObjectId();

			/** @var oxModule $oModule */
			$oModule = oxNew('oxModule');
			$oModule->load($sModuleId);
			$aAddModules = $oModule->getInfo("extend");
			$sModulePath = $oModule->getModulePath();

			$aModules = $oModule->getAllModules();

			// clearing modules array
			foreach( $aModules as $sClass => $aExtends)
			{
				foreach($aExtends as $key => $sExtend)
				{
					if(strpos($sExtend,$sModulePath) === 0)
						unset($aModules[$sClass][$key]);

				}
				if(empty($aModules[$sClass]))
					unset($aModules[$sClass]);
			}

			// merging modules array with actual data from metadata.php
			$aModules = $oModule->mergeModuleArrays($aModules,$aAddModules);
			$aModules = $oModule->buildModuleChains($aModules);

			// saving conf vars
			$cfg->setConfigParam('aModules', $aModules);
			$cfg->saveShopConfVar('aarr', 'aModules', $aModules);

			$this->_aViewData["message"] = "class extensions for $sModuleId were resetted";
		}

		public function resetModuleFiles()
		{
			$myConfig = $this->getConfig();
			$sModuleId = $this->getEditObjectId();

			$aFiles = (array)$this->getConfig()->getConfigParam('aModuleFiles');
			// deleting file records for this module if exist
			if (array_key_exists($sModuleId, $aFiles))
			{
				unset($aFiles[$sModuleId]);
				$myConfig->setConfigParam('aModuleFiles', $aFiles); // saving vars
				$myConfig->saveShopConfVar('aarr', 'aModuleFiles', $aFiles);
			}
			// adding files again
			$oModule = oxnew("oxmodule");
			$oModule->load($sModuleId);
			$oModule->addModuleFiles();

			$this->_aViewData["message"] = "module files for $sModuleId were resetted";
		}

		public function resetModuleTemplates()
		{
			$myConfig = $this->getConfig();
			$sModuleId = $this->getEditObjectId();

			$aTemplates = (array)$this->getConfig()->getConfigParam('aModuleTemplates');
			// deleting template records for this module if exist
			if (array_key_exists($sModuleId, $aTemplates))
			{
				unset($aTemplates[$sModuleId]);
				$myConfig->setConfigParam('aModuleTemplates', $aTemplates); // saving vars
				$myConfig->saveShopConfVar('aarr', 'aModuleTemplates', $aTemplates);
			}

			// adding module Blocks
			$oModule = oxnew("oxmodule");
			$oModule->load($sModuleId);
			$oModule->addModuleTemplates();

			$this->_aViewData["message"] = "module templates for $sModuleId were resetted";
		}

		public function resetTemplateBlocks()
		{
			$myConfig = $this->getConfig();
			$oDb = oxDb::getDb();

			$sModuleId = $this->getEditObjectId();
			$sModuleQ = $oDb->quote($sModuleId);

			$sShopId = $myConfig->getShopId();
			$sShopIdQ = $oDb->quote($sShopId);

			$sQoxtplblocks = "DELETE FROM oxtplblocks WHERE oxshopid=$sShopIdQ AND oxmodule=$sModuleQ";
			//var_dump($sQoxtplblocks);
			$oDb->execute($sQoxtplblocks);

			// adding module Blocks
			$oModule = oxnew("oxmodule");
			$oModule->load($sModuleId);
			$oModule->addTemplateBlocks();

			$this->_aViewData["message"] = "template blocks for $sModuleId were resetted";
		}

	}