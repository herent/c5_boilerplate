<?php
defined('C5_EXECUTE') or die(_('Access Denied'));

class DumpSampleTable extends Job {

	//This must be defined. This text will show in the Name column on the automated jobs page
	public function getJobName(){
		return t('Dump Sample Table');
	}

	//This must be defined. This text will show in the Description column on the automated jobs page
	public function getJobDescription(){
		return t('Deletes all records from the BoilerplateSample table.');
	}

	//You must implement the run method.
	//This method is called automatically and takes care of running all logic for your job.
	//You can provide custom output by returning a string.
	public function run(){
		$db = Loader::db();

		$count = $db->GetOne('SELECT COUNT(*) FROM BoilerplateSample');

		if($count > 0){
			$db->Execute('DELETE FROM BoilerplateSample');
			if($count == 1){
				return t('Job complete. 1 record was deleted');
			}
			else{
				return t('Job complete. ' . $count . ' records were deleted.');
			}
		}

		//the count was 0 so there were no records to delete - return status message
		return t('Job complete. No records were deleted.');

		//---- Error Handling -----

		//Handle errors in this method by using throw(). A contrived example:

		// if(1 == 1) {
		//     throw new Exception(t('Job failed because 1 is equal to 1'));
		// }

		// throwing an exception will cause the job to stop running
	}
}