<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\Http\HttpClient;
use Ant\ChateaClient\Client\IApi;
use Ant\ChateaClient\Client\ApiException;

/**
 * This class represent the one chat API, this is single abstraction 
 * for all API methods.
 * 
 * This class cannot connect with server, 
 * this responsibility it is class that implement IHttpClient for example HttpClient
 * 
 * @author Xabier Fernández Rodríguez in Ant-Web S.L.
 * 
 * @see Ant\ChateaClient\Http\IHttpClient;
 * @see Ant\ChateaClient\Http\HttpClient;
 * @see Ant\ChateaClient\Client\IApi;
 * @see Ant\ChateaClient\Client\ApiException;
 */
class Api implements IApi {

	private $httClient;

	/**
	 * Create a ne API objet 
	 * 
	 * @param IHttpClient 
	 * 			$httpClient the httclient send request to api and retrive response in correct format 
	 */
	public function __construct(IHttpClient $httpClient) 
	{
		if (null === $httpClient) {
			$client = new HttpClient(IHttpClient::SERVER_ENDPOINT);
		}
		$this->httClient = $httpClient;
	}

	private function httpClientSend($response_type = 'json') 
	{
		try {
			return $this->httClient->send($response_type);
		} catch (HttpClientException $ex) {
			throw new ApiException($ex->getMessage());
		}
	}
	
/******************************************************************************/
/***********************       CHANNEL METHODS    *****************************/
/******************************************************************************/

	//GET /api/channel/ // GET /api/user/{id}/channels 
	/**
	 * List all the channels for one user, if $user_id is null show all channels
	 * 
	 * @param number $user_id
	 */
	public function showChannels($user_id = null) 
	{
		$this->httClient->addGet(IApi::URI_CHANNELS_SHOW);
		return $this->httpClientSend();
	}
	/**
	 * Create a channel
	 * 
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 * 
	 */
	public function addChanel($name, $title = '', $description = '') 
	{
		if (!is_string($name) || 0 >= strlen($name)) {
			throw new ApiException(
					"ApiException::addChanel name field needs to be a non-empty string");
		}

		$data = array('channel' => array('name' => $name));
		if (!empty($title)) {
			$data['channel']['title'] = $title;
		}
		if (!empty($title)) {
			$data['channel']['description'] = $description;
		}

		$this->httClient->addPost(IApi::URI_CHANNEL_ADD, $data);
		return $this->httpClientSend();

	}
	// 	PUT|PATCH /api/channel/{id}
	/**
	 * Update a channel
	 * 
	 * @param number $channel_id
	 * @param string $name
	 * @param string $title
	 * @param string $description
	 */
	public function updateChannel($id, $name, $title = '', $description = '') 
	{
		if (!is_numeric($id) || 0 >= $id) {
			throw new ApiException(
					"ApiException::updateChannel id field should be positive integer");
		}
		if (!is_string($name) || 0 >= strlen($name)) {
			throw new ApiException(
					"ApiException::updateChannel name field needs to be a non-empty string");
		}

		$data = array('channel' => array('name' => $name));
		if (!empty($title)) {
			$data['channel']['title'] = $title;
		}
		if (!empty($title)) {
			$data['channel']['description'] = $description;
		}
		$this->httClient->addPatch(IApi::URI_CHANNEL_UPDATE . $id, $data);
		return $this->httpClientSend();
	}
	// 	DELETE /api/channel/{id}
	/**
	 * Delete a channel
	 *
	 * @param number $channel_id
	 */	
	public function delChannel($channel_id) 
	{
		if (!is_numeric($channel_id) || 0 >= $channel_id) {
			throw new ApiException(
					"ApiException::updateChannel channel_id field should be positive integer");
		}
		$this->httClient->addDelete(IApi::URI_CHANNEL_DEL . $channel_id);
		return $this->httpClientSend();
	}
	// 	GET /api/channel/{id}
	/**
	 * show a channel by id
	 *
	 * @param number $id
	 */	
	public function showChannel($channel_id) 
	{
		if (!is_numeric($channel_id) || 0 >= $channel_id) {
			throw new ApiException(
					"ApiException::showChannel channel_id field should be positive integer");
		}
		$this->httClient->addGet(IApi::URI_CHANNEL_SHOW . $channel_id);
		return $this->httpClientSend();
	}
	//api/channel/{id}/fans
	/**
	 * Show all fans of a channel
	 *
	 * @param number $channel_id
	 */	
	public function showChannelFans($channel_id) 
	{
		if (!is_numeric($channel_id) || 0 >= $channel_id) {
			throw new ApiException(
					"ApiException::showChannelFans channel_id field should be positive integer");
		}
		$this->httClient->addGet(sprintf(IApi::URI_CHANNEL_FANS_SHOW, $channel_id));
		return $this->httpClientSend();
	
	}
	//POST	/api/me/channel/{id}/fan
	/**
	 * Make user a channel fan if $user_id is null make me fan
	 *
	 * @param number $user_id
	 */	
	public function addChannelFan($channel_id, $user_id = null) 
	{	
		throw \Exception("This method is not implement yet");
	}
	//DELETE /api/me/channel/{id}/fan
	/**
	 * Remove user as a channel fan if $user_id is null remove who fam me.
	 *
	 * @param number $channel_id
	 * @param number $user_id
	 */	
	public function delChannelFan($channel_id, $user_id = null) 
	{
		throw \Exception("This method is not implement yet");
	
	}	
	
	
/******************************************************************************/
/***********************       Friends METHODS    *****************************/
/******************************************************************************/
		
	// 	GET /api/me/friends
	/**
	 * returns the friends of the loged in user
	 */ 
	public function showMeFriends() 
	{
		$this->httClient->addGet(IApi::URI_ME_FRIENDS_SHOW);
		return $this->httpClientSend();

	}
	//POST /api/me/friends
	/**
	 * sends a friendship request to a given user
	 *
	 * @param number $user_id
	 */
	public function addMeFirend($user_id) {
		if (!is_numeric($user_id) || 0 >= $user_id) {
			throw new ApiException(
					"ApiException::addMeFirend id field should be positive integer");
		}
		$this->httClient
				->addPost(IApi::URI_ME_FRIEND_ADD, array('user_id' => $user_id));
		return $this->httpClientSend();
	}
	// 	GET /api/me/friends/pending
	/**
	 * returns the friendships request the loged in user sended that are pending for acceptance
	 */
	public function showFriendshipsPending() {
		$this->httClient->addGet(IApi::URI_ME_FRIENDSHIPS_PENDING_SHOW);
		return $this->httpClientSend();
	}
	// 	GET /api/me/friends/requests
	/**
	 * returns the friendship requests sended the loged in user pending to be accepted
	 *
	 */ 
	public function showFriendshipsRequest() 
	{

		$this->httClient->addGet(IApi::URI_ME_FRIENDSHIPS_REQUEST_SHOW);
		return $this->httpClientSend();

	}
	// 	PUT /api/me/friends/requests/{id}
	/**
	 * accepts a friendship request
	 * 
	 * @param number $id
	 */
	public function acceptsFriendshipRequest($id) 
	{
		if (!is_numeric($id) || 0 >= $id) {
			throw new ApiException(
					"ApiException::addMeFirend id field should be positive integer");
		}
		$this->httClient->addPut(IApi::URI_ME_FRIENDSHIPS_ACCEPTS . $id);
		return $this->httpClientSend();

	}
	// 	DELETE /api/me/friends/{id}
	/**
	 * Deletes a friendship
	 *
	 * @param number $id
	 */ 
	public function declineFriendshipRequest($id) 
	{

		if (!is_numeric($id) || 0 >= $id) {
			throw new ApiException(
					"ApiException::declineFriendshipRequest id field should be positive integer");
		}
		$this->httClient->addPut(IApi::URI_ME_FRIENDSHIPS_DECLINE . $id);
		return $this->httpClientSend();
	}
	// 	DELETE /api/me/friends/{id}
	/**
	 * Deletes a friendship
	 *
	 * @param number $id
	 */ 
	public function delFriendship($id = 0) 
	{

		if (!is_numeric($id) || 0 >= $id) {
			throw new ApiException(
					"ApiException::delFriendship id field should be positive integer");
		}
		$this->httClient->addDelete(IApi::URI_ME_FRIENDSHIPS_DEL . $id);
		return $this->httpClientSend();
	}
	
	public function showFriends($id) {
		// TODO: Auto-generated method stub
	
	}	
/******************************************************************************/
/***********************      PHOTO METHODS  	  *****************************/
/******************************************************************************/	
	// 	POST /api/photo
	/**
	 * create a photo
	 * 
	 * @param string $title
	 * @param string $image the path from image
	 */
	public function addPhoto($title, $image) 
	{

		if (!is_string($title) || 0 >= strlen($title)) {
			throw new ApiException(
					"ApiException::addPhoto title field needs to be a non-empty string");
		}
		if (!file_exists($image)) {
			throw new ApiException(
					sprintf("ApiException::addPhoto image: '%s'  not exists",
							$image));
		}
		$this->httClient
				->addPost(self::URI_ME_PHOTO_ADD, array('title' => $title),
						$image, 'image');

		return $this->httpClientSend();

	}
	//GET /api/me/photo/{id}
	/**
	 * show a photo
	 * @param number $id
	 */
	public function showPhoto($id) 
	{
		if (!is_numeric($id) || 0 >= $id) {
			throw new ApiException(
					"ApiException::showPhoto id field should be positive integer");
		}
		$this->httClient->addGet(IApi::URI_ME_PHOTO_SHOW . $id);
		return $this->httpClientSend();
	}
	// 	DELETE /api/photo/{id} 
	/**
	 * Delete a photo
	 * 
	 * @param number $id
	 */
	public function delPhoto($id) {
		if (!is_numeric($id) || 0 >= $id) {
			throw new ApiException(
					"ApiException::delPhoto id field should be positive integer");
		}
		$this->httClient->addDelete(IApi::URI_ME_PHOTO_DEL . $id);

		return $this->httpClientSend();
	}
	public function showPhotoVotes($photo_id) 
	{
		throw \Exception("This method is not implement yet");
	
	}
	public function showAllFotos($user_id = null) {
		throw \Exception("This method is not implement yet");
	
	}	
/******************************************************************************/
/***********************    Threads METHODS  	  *****************************/
/******************************************************************************/
		
	//POST /api/me/threads
	/**
	 * Creates a thread
	 *
	 * @param string $recipient
	 * @param string $subject
	 * @param string $body
	 */
	public function addThread($recipient, $subject, $body) 
	{

		if (!is_string($recipient) || 0 >= strlen($recipient)) {
			throw new ApiException(
					"ApiException::addThread recipient field needs to be a non-empty string");
		}
		if (!is_string($subject) || 0 >= strlen($subject)) {
			throw new ApiException(
					"ApiException::addThread subject field needs to be a non-empty string");
		}
		if (!is_string($body) || 0 >= strlen($body)) {
			throw new ApiException(
					"ApiException::addThread body field needs to be a non-empty string");
		}

		$data = array(
				'message' => array('recipient' => $recipient,
						'subject' => $subject, 'body' => $body));
		$this->httClient->addPost(IAPI::URI_ME_THREAD_ADD, $data);

		return $this->httpClientSend();
	}
	//GET /api/me/threads/inbox
	/**
	 * Lists threads with messages sended to the logged in user
	 */
	public function showThreadsInbox() 
	{
		$this->httClient->addGet(IAPI::URI_ME_THREAD_INBOX_SHOW);

		return $this->httpClientSend();
	}
	//GET /api/me/threads/sent
	/**
	 * Lists threads with messages sended by the logged in user
	 */
	public function showThreadsSent() 
	{
		$this->httClient->addGet(IAPI::URI_ME_THREAD_SENT_SHOW);

		return $this->httpClientSend();
	}
	//GET /api/me/threads/{id}
	/**
	 * Lists the messages of a given thread
	 *
	 * @param number $thread_id
	 */
	public function showThread($thread_id) 
	{
		if (!is_numeric($thread_id) || 0 >= $thread_id) {
			throw new ApiException(
					"ApiException::showThread thread_id field should be positive integer");
		}

		$this->httClient->addGet(IAPI::URI_ME_THREAD_SHOW . $thread_id);

		return $this->httpClientSend();
	}

	//POST /api/me/threads/{id}
	/**
	 * Replies a message to a given thread
	 *
	 * @param number $thread_id
	 * @param string $body
	 */
	public function addThreadMessage($thread_id, $body) 
	{
		if (!is_numeric($thread_id) || 0 >= $thread_id) {
			throw new ApiException(
					"ApiException::addThreadMessage thread_id field should be positive integer");
		}
		if (!is_string($body) || 0 >= strlen($body)) {
			throw new ApiException(
					"ApiException::addThreadMessage body field needs to be a non-empty string");
		}

		$this->httClient
				->addPost(IAPI::URI_ME_THREAD_MESSAGE_ADD . $thread_id,
						array('message' => array('body' => $body)));
		return $this->httpClientSend();
	}
	//DELETE /api/me/threads/{id}
	/**
	 * Deletes a thread
	 *
	 * @param number $thread_id
	 */
	public function delThread($thread_id) 
	{
		if (!is_numeric($thread_id) || 0 >= $thread_id) {
			throw new ApiException(
					"ApiException::delThread thread_id field should be positive integer");
		}

		$this->httClient->addDelete(IAPI::URI_ME_THREAD_DEL . $thread_id);

		return $this->httpClientSend();
	}
	
/******************************************************************************/
/***********************     Votes METHODS  	  *****************************/
/******************************************************************************/
		
	//GET /api/me/vote
	/**
	 * show a vote of an user
	 */
	public function showVotes() 
	{
		$this->httClient->addGet(IAPI::URI_ME_VOTE_SHOW);
		return $this->httpClientSend();
	}
	//POST /api/me/vote create a vote
	/**
	 *
	 * @param number $photo_id
	 * @param float $score not nevative 1.5 vote; one vote between 1 to 10
	 */
	public function addVote($photo_id, $score = 1) 
	{
		if (!is_numeric($photo_id) || 0 >= $photo_id) {
			throw new ApiException(
					"ApiException::addVote photo_id field should be positive integer");
		}
		if (!is_numeric($score) || ($score < 1) || ($score > 10)) {
			throw new ApiException(
					"ApiException::addVote score field should be positive float, between 1 to 10");
		}

		$data = array('vote' => array('photo' => $photo_id, 'score' => $score));

		$this->httClient->addPost(IAPI::URI_ME_VOTE_ADD, $data);
		return $this->httpClientSend();
	}
	// DELETE /api/me/vote/{id}
	/**
	 * Delete a vote
	 *
	 * @param number $photo_id 
	 * 			The photo id what you remove vote.
	 */
	public function delVote($photo_id) 
	{
		if (!is_numeric($photo_id) || 0 >= $photo_id) {
			throw new ApiException(
					"ApiException::delVote photo_id field should be positive integer");
		}

		$this->httClient->addDelete(IApi::URI_ME_VOTE_DEL . $photo_id);

		return $this->httpClientSend();
	}
	/**
	 * show a vote of an user
	 */	
	public function showMeVotes() {
		throw \Exception("This method is not implement yet");
	
	}
		
/******************************************************************************/
/***********************     USERS METHODS  	  *****************************/
/******************************************************************************/
		
	// 	PUT|PATCH /api/profile/
	/**
	 * Update a profile of an user
	 * 
	 * @param string $username the new username
	 * 
	 * @param string $email the new email
	 * 
	 * @param String $current_password the password of user in session.
	 * 	if you will change password use: @link #changePassword
	 * 
 	 * @throws ApiException
	 * 		The exception that is thrown when
	 * 		$username, $email, $current_password is not valid string
	 
	 */ 
	public function updateProfile($username, $email, $current_password) 
	{
		if (!is_string($username) || 0 >= strlen($username)) {
			throw new ApiException(
					"ApiException::updateProfile username field needs to be a non-empty string");
		}
		if (!is_string($email) || 0 >= strlen($email)) {
			throw new ApiException(
					"ApiException::updateProfile email field needs to be a non-empty string");
		}
		if (!is_string($current_password) || 0 >= strlen($current_password)) {
			throw new ApiException(
					"ApiException::updateProfile current_password field needs to be a non-empty string");
		}

		$data = array(
				'profile' => array('username' => $username, 'email' => $email,
						'current_password' => $current_password));

		$this->httClient->addPatch(IApi::URI_PORFILE_UPDATE, $data);
		return $this->httpClientSend();
	}
	// 	GET /api/profile/
	/**
	 * Show a profile of an user
	 */ 
	public function showProfile() 
	{
		$this->httClient->addGet(IApi::URI_PORFILE_SHOW);
		return $this->httpClientSend();
	}
	// 	PATCH /api/profile/change-password
	/**
	 * Change user password
	 *
	 * @param string $current_password
	 * @param string $new_password
	 * @param String $repeat_new_password
 	 * @throws ApiException
	 * 		The exception that is thrown when
	 * 		$current_password, $new_password, $new_password, $repeat_new_password is not valid string
	 * 		or $new_password not equals to $repeat_new_password
	 * 		or API error send for server  
	 */
	public function changePassword($current_password, $new_password,
			$repeat_new_password) {
		if (!is_string($current_password) || 0 >= strlen($current_password)) {
			throw new ApiException(
					"ApiException::changePassword() current_password must be a non-empty string");
		}

		if (!is_string($new_password) || 0 >= strlen($new_password)) {
			throw new ApiException(
					"ApiException::changePassword() new_password must be a non-empty string");
		}

		if (!is_string($repeat_new_password)
				|| 0 >= strlen($repeat_new_password)) {
			throw new ApiException(
					"ApiException::changePassword() repeat_new_password must be a non-empty string");
		}

		if (strcmp($new_password, $repeat_new_password)) {
			throw new ApiException(
					"ApiException::changePassword() the new_password and repeat_new_password isn't equals");
		}
		$data = array(
				'change_password' => array(
						'current_password' => $current_password,
						'plainPassword' => array('first' => $new_password,
								'second' => $repeat_new_password)));
		
		$this->httClient->addPatch(IApi::URI_PORFILE_CHANGE_PASSWORD, $data);
		return $this->httpClientSend();
	}
	// 	DELETE /api/user/
	/**
	 * Delete me user
	 */ 
	public function delMeUser() 
	{
		$this->httClient->addDelete(IApi::URI_ME_DEL);
		return $this->httpClientSend();

	}
	// 	GET /api/user/list
	/**
	 * Get all the users
	 */ 
	public function who() 
	{
		$this->httClient->addGet(IApi::URI_USERS_SHOW);
		return $this->httpClientSend();
	}
	/**
	 * Get am I
	 */
	public function whoami() 
	{
		$this->httClient->addGet(IApi::URI_ME_SHOW);
		return $this->httpClientSend();

	}
	/**
	 * Disable an user by id
	 *
	 * @param number $id
	 * 		The id of the user to be disable
	 * @throws ApiException
	 * 		The exception that is thrown when $user_id is not namber or it is negative number
	 */ 
	public function disableUser($user_id) 
	{
		if (!is_numeric($user_id) || 0 >= $user_id) {
			throw new ApiException(
					"ApiException::disableUser photo_id field should be positive integer");
		}

		ld($this->httClient->getUrl());
		$this->httClient->addPatch(sprintf(IApi::URI_USER_DISABLE, $user_id));
		return $this->httpClientSend();

	}
	// 	PATCH  /user/enable/{id}
	/**
	 * Enable an user by id
	 *
	 * @param number $id
	 * 		The id of the user to be enabled
	 * @throws ApiException
	 * 		The exception that is thrown when $user_id is not namber or it is negative number
	 */ 
	public function enableUser($user_id) 
	{
		if (!is_numeric($user_id) || 0 >= $user_id) {
			throw new ApiException(
					"ApiException::enableUser user_id field should be positive integer");
		}

		$this->httClient->addPatch(sprintf(IApi::URI_USER_ENABLE, $user_id));

		return $this->httpClientSend();

	}
	// 	DELETE  /user/{id}
	/**
	 * Delete an user
	 * 
	 * @param number $user_id 
	 * 		The id of the user to be deleted 
	 * @throws ApiException
	 * 		The exception that is thrown when $user_id is not namber or it is negative number  
	 */
	public function delUser($user_id) 
	{
		if (!is_numeric($user_id) || 0 >= $user_id) {
			throw new ApiException(
					"ApiException::delUser user_id field should be positive integer");
		}

		$this->httClient->addDelete(IApi::URI_USER_DEL . $user_id);
		return $this->httpClientSend();

	}
	//DELETE /api/me/
	/**
	 * Delete my user
	 */	
	public function delMe() 
	{
		$this->httClient->addDelete(IApi::URI_ME_DEL);
		return $this->httpClientSend();
	
	}	
	/**
	 * Create a user in server API
	 * 
	 * @param IHttpClient $httpClient
	 * @param string $username
	 * @param string $email
	 * @param string $new_password
	 * @param string $repeat_new_password
	 * 
	 * @throws ApiException
	 * 		The exception that is thrown when $httpClient is null 
	 * 		or $username, $email, $new_password, $repeat_new_password is not valid string
	 * 		or $new_password not equals to $repeat_new_password
	 * 		or API error send for server 
	 */
	public static function register(IHttpClient $httpClient, $username, $email,
			$new_password, $repeat_new_password) 
	{

		if ($httpClient == null) {
			throw new ApiException("httpclient is not null");
		}
		if (!is_string($username) || 0 >= strlen($username)) {
			throw new ApiException("username must be a non-empty string");
		}
		if (!is_string($email) || 0 >= strlen($email)) {
			throw new ApiException("email must be a non-empty string");
		}
		if (!is_string($new_password) || 0 >= strlen($new_password)) {
			throw new ApiException("new_password must be a non-empty string");
		}

		if (!is_string($repeat_new_password)
				|| 0 >= strlen($repeat_new_password)) 
		{
			throw new ApiException(
					"repeat_new_password must be a non-empty string");
		}

		if (strcmp($new_password, $repeat_new_password)) 
		{
			throw new ApiException(
					"the new_password and repeat_new_password isn't equals");
		}
		$data = json_encode(
				array(
						'user_registration' => array('username' => $username,
								'email' => $email,
								'plainPassword' => array(
										'first' => $new_password,
										'second' => $repeat_new_password))));
		$httpClient->setBaseUrl(IHttpClient::SERVER_ENDPOINT);
		$httpClient->addPost(IApi::URI_REGISTER, $data, 'application/json');

		try {
			return $httpClient->send();
		} catch (HttpClientException $ex) {
			throw new ApiException($ex->getMessage());
		}
	}
	
	/**
	 * Request reset user password, in the request is mandatory send 
	 * username or email (dont work from nelmio api)
	 * 
	 * @param IHttpClient $httpClient
	 * @param string $username
	 * @throws ApiException
	 * 		The exception that is thrown when $httpClient is null 
	 * 		or $username is not valid string 
	 * 		or API error send for server.
	 */
	public static function requestResetpassword(IHttpClient $httpClient, $username) {
		if ($httpClient == null) {
			throw new ApiException("httpclient is not null");
		}
		if (!is_string($username) || 0 >= strlen($username)) {
			throw new ApiException("username must be a non-empty string");
		}

		$data = json_encode(array('username' => $username));

		$httpClient->setBaseUrl(IHttpClient::SERVER_ENDPOINT);
		$httpClient
				->addPost(IApi::URI_RESETTING_PASSWORD, $data,
						'application/json');

		try {
			return $httpClient->send();
		} catch (HttpClientException $ex) {
			throw new ApiException($ex->getMessage());
		}
	}

}
