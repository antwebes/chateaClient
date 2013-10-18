<?php

namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\Service\Client\ClientInterface;

interface IApi 
{

/******************************************************************************/
/******************************************************************************/
			
	/**
	 *  create a user
	 *
	 * @param string $username
	 * @param string $email
	 * @param string $new_password
	 * @param string $repeat_new_password
	 */
	 public function register($username, $email,$new_password, $repeat_new_password);


	/**
	* Request reset user password, in the request is mandatory send username or email (dont work from nelmio api)
	 *
	 * @param string $username
	 */
	 public function forgotPassword($username);
	 	

/******************************************************************************/
/*				  				  PROFILE METHODS    	   					  */
/******************************************************************************/

	 /**
	  * Show a profile of an user
	 */
	 public function showAccount();

	 /**
	  * Update a profile of an user
	  */
	 public function updateAccount($username, $email, $current_password);

	 /**
	  * Change user password
	  *
	  * @param string $current_password
	  * @param string $new_password
	  * @param String $repeat_new_password
	  */
	 public function changePassword($current_password, $new_password, $repeat_new_password);
	  
	  	 	 
/******************************************************************************/
/*				  				  CHANNEL METHODS    	   					  */
/******************************************************************************/
	 	 
	/**
	 * List all the channels
	 */
	public function showChannels();	

	/**
	 * Create a channel
	 *
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 */
	public function addChanel($name ,$title = '',$description = '');
	/**
	 * Update a channel
	 *
	 * @param number $channel_id
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 */
	public function updateChannel($channel_id, $name, $title = '', $description = '');

	/**
	 * Delete a channel
	 *
	 * @param number $channel_id
	 */
	public function delChannel($channel_id);

	/**
	 * Show a channel by id
	 *
	 * @param number $channel_id
	 */
	public function showChannel($channel_id);
	/**
	 * Show all fans of a channel
	 *
	 * @param number $channel_id
	 */
	 public function showChannelFans($channel_id);
	/**
	 * Show list channels create of an user
	 * 
	 * @param number $user_id
	 */
	public function showUserChannels($user_id); 		
	/**
	 * Show all channels fan of an user
	 *  
	 * @param number $user_id
	 */
	public function showUserChannelsFan($user_id);
    /**
     * List all the channels type
     * @return mixed
     */
    public function showChannelsTypes();
	/**
	 * Make user a channel fan
	 *
	 * @param number $channel_id
     * @param number $user_id
	 */
	public function addUserChannelFan($channel_id, $user_id);
	/**
	 * Remove user as a channel fan.
	 *
	 * @param number $channel_id
	 * @param number $user_id
	 */
	public function delUserChannelFan($channel_id, $user_id);

/******************************************************************************/
/*			  			FRIENDSHIP METHODS    	   					  		  */
/******************************************************************************/	

	/**
	 * returns the friends of user
	 */
	 public function showFriends($user_id);
	 /**
	 * sends a friendship request to a given user
	 *
	 * @param number $user_id
	 * @param number $friend_id
	 */
	 public function addFriends($user_id,$friend_id);
	 /**
	 * returns the friends that they are pending accept by an user
	 */
	 public function showFriendshipsPending($user_id);
// 	 GET api/users/{id}/friends/requests
	 /**
	 * returns the requests friendships that one user doesn't have accepted
	 *
	 * @param number $user_id
	 */
	 public function showFriendshipsRequest ($user_id);
	 /**
	 * accepts a friendship request
	 *
	 * @param number $user_id
	 * @param number $user_accept_id
	 */
	 public function addFriendshipRequest($user_id,$user_accept_id);
	 /**
	 * Decline a friendship request
	 *
	 * @param number $user_id
	 * @param number $user_decline_id
	 */
	 public function delFriendshipRequest($user_id,$user_decline_id);
	 /**
	 * Deletes a friendship
	 *
	 * @param number $user_id
	 * @param number $user_delete_id
	 */
	 public function delFriend($user_id, $user_delete_id);

/******************************************************************************/
/*			  				ME METHODS	    	   					  		  */
/******************************************************************************/	 
	 /**
	  * Get my user of session
	  */
	  public function whoami();
	  
	 /**
	  * Delete my user
	  */
	  public function delMe();

	  
/******************************************************************************/
/*			  				PHOTO METHODS    	   					  		  */
/******************************************************************************/

    /**
     * Delete a photo
     * @param number $photo_id
     */
    public function delPhoto($photo_id);

    /**
	  * Show a photo
	  *  
	  * @param number $photo_id
	  */
	  public function showPhoto($photo_id);	  
	  /**
	  * Show a vote of a photo
	  *  
	  * @param number $photo_id
	  */
	  public function showPhotoVotes($photo_id);		
	  /**
	   * List all photos of an user
	   * 
	   * @param number $user_id
	   */
	  public function showPhotos($user_id);	
	  /**
	   * Create a photo
	   *  
	   * @param number $user_id
       * @param string $imageTile 
	   * @param string $imageFile
	   */
	  public function addPhoto($user_id, $imageTile, $imageFile);	  
	  /**
	   * Show all votes of an user
	   * 
	   * @param number $user_id
	   */
	  public function showUserVotes($user_id);	  
	  /**
	   * Create a vote
	   *  
	   * @param number $user_id
	   * @param number $photo_id
	   * @param number $core
	   */
	  public function addPhotoVote($user_id,$photo_id,$core);	  
	  /**
	   * Delete a vote
	   * @param number $photo_id
	   */
      public function delPhotoVote($photo_id);


      
/******************************************************************************/
/*			  				THREADS METHODS    	   					  		  */
/******************************************************************************/
		/**
		 * 
		 * Creates a thread
		 * 
		 * @param number $user_id
		 * @param string $recipient
		 * @param string $subject
		 * @param string $body
		 */
		public function addThread($user_id, $recipient, $subject, $body);
		/**
		 * Lists threads with messages had been sent by one user
		 * 
		 * @param number $user_id
		 */
		public function showThreadsInbox($user_id);			
		/**
		 * Messages list in inbox one user. 
		 * 
		 * @param number $user_id
		 */
		public function showThreadsSent($user_id);
		/**
		 * The messages list a given thread 
		 *  
		 * @param number $thread_id
		 */
		public function showThread($thread_id);
		/**
		 * Replies a message to a given thread
		 *
		 * @param number $user_id
		 * @param number $thread_id
		 * @param string $body
		 */
		 public function addThreadMessage($user_id,$thread_id,$body);
		/**
		 * Deletes a thread
		 *
		 * @param number thread_id
		 */
		 public function delThread($thread_id);

		 
/******************************************************************************/
/*			  				USER METHODS    	   					  		  */
/******************************************************************************/		 
		/**
		 * Get all the users
		 */
    public function who($page = 1);
		/**
		 * Get the user
		 * @param number $user_id
		 */
		public function showUser($user_id);
		/**
		 * Get blocked users of the session user
		 * @param number $user_id
		 */
		public function showUsersBlocked($user_id);
		/**
		 * Blocks the given user for the session user
		 * 
		 * @param number $user_id
		 * @param number $user_blocked_id
		 */
		public function addUserBlocked($user_id,$user_blocked_id);
		/**
		 * Unblocks the given user for the session user
		 *
		 * @param number $user_id
		 * @param number $user_blocked_id
		 */		
		public function delUserBlocked($user_id,$user_blocked_id);
        /**
         * Show a profile
         *
         * @param $user_id
         * @return mixed
         */
        public function showUserProfile($user_id);

}