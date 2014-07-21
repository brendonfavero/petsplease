<?php
// ampse


/**
 * @ package ppNews 
 */
 
class addon_ppNews_util extends addon_ppNews_info
{
	public function registerUser( $email, $fname, $lname ) { // adds supplied user to hz list
		
		$apikey = 'bc23d328f625c1e250be2d52682380a4-us1';
		
		// A List Id to run examples against. use lists() to view all
		// Also, login to MC account, go to List, then List Tools, and look for the List ID entry
		$listId = 'ceb20b1b8f'; // horsezone newsletter

		require_once dirname(__FILE__) . '/lib/mailchimp/MCAPI.class.php';
		
		$api = new MCAPI($apikey);
		
		$mergeVars = array('FNAME'=> $fname, 
						   'LNAME'=> $lname, 
                    	  );
		// By default this sends a confirmation email - you will not see new members
		// until the link contained in it is clicked!
		$listResult = $api->listSubscribe( $listId, $email, $mergeVars );
		
		if ($api->errorCode){
			return array(
			 "\tCode=".$api->errorCode."\n",
			 "\tMsg=".$api->errorMessage."\n",
			 );
		} else {
			return true;
		}
		
		return true;
	}
	
	
		#### User Core Events ####
	
	/**
	 * This is called when a new user registers or is created by the admin.
	 * This is done in addition to,
	 * NOT instead of the normal procedures.  It is called once
	 * the registration process is finished and the user is activated.
	 * Note that nothing is expected to be returned.
	 *
	 * @param mixed $vars An associative array containing new users data
	 */
	public function core_user_register ($vars)
	{
		error_log( 'core_user_register:');
		error_log ( print_r( $vars, true ) );
	}
	
	/**
	 * This is called when a user's info is changed.
	 * This is done in addition to,
	 * NOT instead of the normal procedures.  It is called once
	 * the changes are already made.
	 * Note that nothing is expected to be returned.
	 *
	 * @param mixed $vars An associative array containing changed user's data
	 */
	public function core_user_edit ($vars)
	{
		error_log( 'core_user_edit:');
		error_log ( print_r( $vars, true ) );
	}
	
	#### Registration Core Events ####
	
	/**
	 * This is called when the registration form is being displayed, and allows
	 * an addon to add additional fields to be displayed on the registration
	 * form.
	 * 
	 * @param array $registered_variables The registered vars set in the
	 *   registration class.
	 * @return array See method's inline comments for expected array
	 * @since Geo Version 4.0.9
	 */
	public function core_registration_add_field_display ($registered_variables)
	{
		//Easiest is to return an array like below, with an index for "label" and "value":
		error_log (' making checkbox (i hope) ');
		return array ('label' => 'Subscribe to Pets Please newsletter', 'value' => '<input type="checkbox" name="c[optional_field_10]" value="1" />');
		
		//or return false to skip over
	}
	
	/**
	 * Allow setting of "registered_variables" according to post params (user
	 * input), so that they can be accessed later when saving the data.
	 * 
	 * @param array $user_input
	 * @return array See comments in method's source
	 * @since Geo Version 4.0.9
	 */
	public function core_registration_add_variable ($user_input)
	{
		//User input is an array of user input as sent in the $_POST['c'] param,
		//sent through clean inputs of course which geoString::specialChars()
		//all the string vars to prevent XSS.
		error_log( 'core_registration_add_variable:');
		error_log ( print_r( $user_input, true ) );
		//Expected to return an array like so:
		return false;
		return  array (
			'name' => 'newsletter',
			'value' => intval($user_input['newsletter'])
		);
		
		//you can return an array of those as well, like this:
	
		//returning false since this is example addon
		return false;
	}
	
	/**
	 * This is a notify type core event, to allow saving of user input registration
	 * information.  This can be called in 3 different scenarios, either inserting
	 * user into the DB directly (the user ID will be specified), inserting
	 * into the registration waiting approval, or moving from waiting approval
	 * to user data (registration approved)
	 * 
	 * @param array $vars See comments in method's source
	 * @since Geo Version 4.0.9
	 */
	public function core_registration_add_field_update ($vars)
	{
		error_log( 'core_registration_add_field_update:');
		error_log ( print_r( $vars, true ) );
		if( $vars['registration_variables']['optional_field_10'] == 1 ) { // add user to the list
			$util = geoAddon::getUtil('ppNews');
			$result = $util->registerUser( 	$vars['registration_variables']['email'],
									 		$vars['registration_variables']['firstname'],
									 		$vars['registration_variables']['lastname']
									 );
			if( $result ) { 
				if( is_array($result) ) {
					error_log( 'api error with this email address: ' . $vars['registration_variables']['email'] );	
					foreach( $result as $entry) {
						error_log( $entry );	
					}
				}else {
					error_log( 'got user with this email address: ' . $vars['registration_variables']['email'] );	
				}
			}else {
				error_log( 'internal error with this email address: ' . $vars['registration_variables']['email'] );	
			}
		}
		return true;
		
	}

}