<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\Http\IHttpClient;
interface IApi
{
	const URI_CHANNELS_SHOW						= 'api/channel/';
	const URI_CHANNEL_ADD	 					= 'api/channel/';
	const URI_CHANNEL_UPDATE					= 'api/channel/';
	const URI_CHANNEL_DEL	 					= 'api/channel/';
	const URI_CHANNEL_SHOW	 					= 'api/channel/';		
	const URI_CHANNEL_FANS_SHOW					= 'api/channel/%d/fans';
	//--------------------------------------------------------------------------
	const URI_ME_FRIENDS_SHOW					= 'api/me/friends';
	const URI_ME_FRIEND_ADD						= 'api/me/friends';
	const URI_ME_FRIENDSHIPS_PENDING_SHOW   	= 'api/me/friends/pending';
	const URI_ME_FRIENDSHIPS_REQUEST_SHOW		= 'api/me/friends/requests';
	const URI_ME_FRIENDSHIPS_ACCEPTS			= 'api/me/friends/requests/';
	const URI_ME_FRIENDSHIPS_DECLINE			= 'api/me/friends/requests/';
	const URI_ME_FRIENDSHIPS_DEL				= 'api/me/friends/';
	//--------------------------------------------------------------------------
	const URI_ME_PHOTO_ADD						= 'api/me/photo';
	const URI_ME_PHOTO_SHOW						= 'api/me/photo/';
	const URI_ME_PHOTO_DEL						= 'api/me/photo/';
	//--------------------------------------------------------------------------
	const URI_ME_THREAD_ADD						= 'api/me/threads';
	const URI_ME_THREAD_INBOX_SHOW				= 'api/me/threads/inbox';									
	const URI_ME_THREAD_SENT_SHOW				= 'api/me/threads/sent';
	const URI_ME_THREAD_SHOW					= 'api/me/threads/';
	const URI_ME_THREAD_MESSAGE_ADD				= 'api/me/threads/';
	const URI_ME_THREAD_DEL						= 'api/me/threads/';	
	//--------------------------------------------------------------------------
	const URI_ME_VOTE_SHOW						= 'api/me/vote';
	const URI_ME_VOTE_DEL						= 'api/me/vote/';
	const URI_ME_VOTE_ADD						= 'api/me/vote';
	//--------------------------------------------------------------------------
	const URI_PORFILE_SHOW 						= 'api/profile/';	
	const URI_PORFILE_UPDATE					= 'api/profile/';
	const URI_PORFILE_CHANGE_PASSWORD 			= 'api/profile/change-password';
	//--------------------------------------------------------------------------	
	const URI_ME_SHOW	 						= 'api/me/';
	const URI_ME_DEL							= 'api/me/';	
	const URI_USER_DISABLE						= 'user/%d/disable';
	const URI_USER_ENABLE						= 'user/%d/enable';
	const URI_USER_FRIENDS_SHOW					= 'api/users/%d/friends';
	const URI_USERS_SHOW						= 'api/user/list';
	const URI_USER_DEL 							= "user/";
	
	//--------------------------------------------------------------------------	
	const URI_REGISTER							= "register";
	const URI_RESETTING_PASSWORD 				= "resetting/send-email";	

	//GET /api/channel/ // GET /api/user/{id}/channels 
	/**
	 * List all the channels for one user, if $user_id is null show all channels
	 * 
	 * @param number $user_id
	 */
	public function showChannels($user_id = null);	
	// 	POST /api/channel/ 
	/**
	 * Create a channel
	 * 
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 */
	public function addChanel($name ,$title = '',$description = '');
	// 	PUT|PATCH /api/channel/{id} 
	/**
	 * Update a channel
	 * 
	 * @param number $channel_id
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 */
	public function updateChannel($channel_id, $name, $title = '', $description = '');
	// 	DELETE /api/channel/{id}
	/**
	 * Delete a channel
	 * 
	 * @param number $channel_id
	 */
	public function delChannel($channel_id);	
	// 	GET /api/channel/{id} 
	/**
	 * show a channel by id
	 * 
	 * @param number $id
	 */
	public function showChannel($channel_id);	
	//api/channel/{id}/fans
	/**
	 * Show all fans of a channel
	 * 
	 * @param number $channel_id
	 */
	public function showChannelFans($channel_id);
	//POST	/api/me/channel/{id}/fan		
	/**
	 * Make user a channel fan if $user_id is null make me fan 
	 * 
	 * @param number $user_id
	 */
	public function addChannelFan($channel_id, $user_id = null);
	//DELETE /api/me/channel/{id}/fan
	/**
	 * Remove user as a channel fan if $user_id is null remove who fam me.
	 * 
	 * @param number $channel_id
	 * @param number $user_id
	 */
	public function delChannelFan($channel_id, $user_id = null);

	// 	GET /api/me/friends
	/**
	 * returns the friends of the loged in user
	 */
	public function showMeFriends();
	//POST /api/me/friends
	/**
	 * sends a friendship request to a given user
	 *
	 * @param number $user_id
	 */
	 public function addMeFirend($user_id);	
	// 	GET /api/me/friends/pending
	/**
	 * returns the friendships request the loged in user sended that are pending froor acceptance
	 */		
	 public function showFriendshipsPending();	
	// 	GET /api/me/friends/requests 
	/**
	 * returns the friendship requests sended the loged in user pending to be accepted
	 * 
	 * @param number $user_id
	 */
	public function showFriendshipsRequest ();
	// 	PUT /api/me/friends/requests/{id}
	/**
	 * accepts a friendship request
	 * 
	 * @param number $id
	 */
	public function acceptsFriendshipRequest($id);
	// 	DELETE /api/me/friends/requests/{id}
	/**
	 * Decline a friendship request
	 * 
	 * @param number $i
	 */
	public function declineFriendshipRequest($id);
	// 	DELETE /api/me/friends/{id} 
	/**
	 * Deletes a friendship
	 * 
	 * @param number $id
	 */
	public function delFriendship($id = 0);
	// 	POST /api/photo
	/**
	 * create a photo
	 * 
	 * @param string $title
	 * @param byte[] $image
	 */
	public function addPhoto($title,$image);
	//GET /api/me/photo/{id}
	/**
	 * show a photo
	 * @param number $id
	 */
	public function showPhoto($id);
	// 	DELETE /api/photo/{id} 
	/**
	 * Delete a photo
	 * 
	 * @param number $id
	 */
	public function delPhoto($id);
	//GET	/api/photo/{id}/vote
	/**
	 * Show a vote of a photo
	 * 
	 * @param number $photo_id
	 */
	public function showPhotoVotes($photo_id);
	//GET /api/me/photos && 	//GET /api/user/{id}/photos	
	/**
	 * List all photos by user if $user_id is null show me photos
	 * 
	 * @param number $user_id if use
	 */
	public function showAllFotos($user_id = null);
	//POST /api/me/threads
	/**
	 * Creates a thread
	 *
	 * @param string $recipient
	 * @param string $subject
	 * @param string $body
	 */
	 public function addThread($recipient, $subject, $body);
	//GET /api/me/threads/inbox
	/**
	 * Lists threads with messages sended to the logged in user
	 */	
	public function showThreadsInbox();	 
	//GET /api/me/threads/sent 
	/**
	 * Lists threads with messages sended by the logged in user 
	 */
	public function showThreadsSent(); 
	//GET /api/me/threads/{id}
	/**
	 * Lists the messages of a given thread
	 * 
	 * @param number $thread_id
	 */
	public function showThread($thread_id);

	//POST /api/me/threads/{id}
	/**
	 * Replies a message to a given thread
	 * 
	 * @param number $id
	 * @param string $body
	 */
	public function addThreadMessage($thread_id,$body);
	//DELETE /api/me/threads/{id}
    /**
     * Deletes a thread
     *  
     * @param number $id
     */
	public function delThread($thread_id);
	 
	//GET /api/me/vote
	/**
	 * show a vote of an user
	 */
	public function showMeVotes(); 

	//POST /api/me/vote create a vote 
	/**
	 * 
	 * @param number $photo_id
	 * @param float $score not nevative 1.5 vote 
	 */
	public function addVote($photo_id,$score);
	// DELETE /api/me/vote/{id} 
	/**
	 * Delete a vote
	 * 
	 * @param number $id photo id
	 */
	public function delVote($id);
	// 	GET /api/profile/
	/**
	 * Show a profile of an user
	 */
	 public function showProfile();
	// 	PUT|PATCH /api/profile/
	/**
	 * Update a profile of an user
	 */
	public function updateProfile($username, $email, $current_password);
	// 	PATCH /api/profile/change-password 
	/**
	 * Change user password
	 * 
	 * @param string $current_password
	 * @param string $new_password
	 * @param String $repeat_new_password
	 */
	public function changePassword($current_password, $new_password, $repeat_new_password);
	// 	DELETE /api/user/
	/**
	 * Delete my user
	 */
	public function delMeUser();
	// 	GET /api/users/
	/**
	 * Get all the users
	 */
	public function who();	
	// 	GET /api/me
	/**
	 * Get the user of session
	 */
	public function whoami();
	//DELETE /api/me/
	/**
	 * Delete my user
	 */
	public function delMe();	
	// 	GET /api/users/{id}/friends
	/**
	 * accepts a friendship request
	 * 
	 * @param number $id
	 */	
	public function showFriends($id);
	// 	PATCH  /user/{id}disable 
	/**
	 * disable an user by id
	 * 
	 * @param number $id
	 */
	public function disableUser($id);
	// 	PATCH  /user/{id}/enable
	/**
	 * Enable an user by id CONFIGURE ANOTTATION @SECURE because mandatory redirect to login
	 * 
	 * @param number $id
	 */
	public function enableUser($id);
	// 	DELETE  /user/{id}
	/**
	 * delete an user
	 * 
	 * @param number $id
	 */
	public function delUser($id);	
	// 	POST /register
	/**
	 *  create a user
	 *
	 * @param IHttpClient $httpClient
	 * @param string $username
	 * @param string $email
	 * @param string $new_password
	 * @param string $repeat_new_password
	 */
	 public static function register(IHttpClient $httpClient,  $username, $email,$new_password, $repeat_new_password);
	 // 	POST /resetting/send-email
	/**
	* Request reset user password, in the request is mandatory send username or email (dont work from nelmio api)
	 *
	 * @param IHttpClient $httpClient
	 * @param string $username
	 */
	 public static function requestResetpassword(IHttpClient $httpClient, $username);	
}