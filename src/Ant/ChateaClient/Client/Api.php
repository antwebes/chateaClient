<?php
namespace Ant\ChateaClient\Client;

use Ant\ChateaClient\Client\IApi;
use Ant\ChateaClient\Service\Client\ChateaGratisClient;
use Exception;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Service\Command\CommandInterface;
use InvalidArgumentException;


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
class Api
{

    private $client;

    /**
     * Create a ne API objet
     *
     * @param ChateaGratisClient $client the Httclient send request to api and response in json format
     *
     */
    public function __construct(ChateaGratisClient $client)
    {
        $this->client = $client;
    }

    public function register($username, $email, $new_password, $repeat_new_password)
    {


        if (!is_string($username) || 0 >= strlen($username)) {
            throw new InvalidArgumentException("username must be a non-empty string");
        }
        if (!is_string($email) || 0 >= strlen($email)) {
            throw new InvalidArgumentException("email must be a non-empty string");
        }
        if (!is_string($new_password) || 0 >= strlen($new_password)) {
            throw new InvalidArgumentException("new_password must be a non-empty string");
        }

        if (!is_string($repeat_new_password) || 0 >= strlen($repeat_new_password)) {
            throw new InvalidArgumentException(
                "repeat_new_password must be a non-empty string");
        }

        if (strcmp($new_password, $repeat_new_password)) {
            throw new InvalidArgumentException(
                "the new_password and repeat_new_password isn't equals");
        }

        $command = $this->client->getCommand(
            "Register",
            array(
                'user_registration' =>
                array(
                    'email' => $email,
                    'username' => $username,
                    'plainPassword' => array(
                        'first' => $new_password,
                        'second' => $repeat_new_password
                    )
                )
            )
        );

        return $this->executeCommand($command);

    }

    private function executeCommand(CommandInterface $command)
    {
        try {
            return $command->execute();
        }
        catch (ClientErrorResponseException $cerEx) {
            throw new ApiException($cerEx->getResponse()->getBody(), $cerEx->getResponse()->getStatusCode(), $cerEx);
        }catch (BadResponseException $brEx) {
            throw new ApiException($brEx->getResponse()->getBody(), $brEx->getResponse()->getStatusCode(), $brEx);
        }catch (CurlException $curlEx) {
            throw new ApiException($curlEx->getMessage(), $curlEx->getCode(), $curlEx);
        }catch (Exception $ex){
            throw new ApiException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    public function forgotPassword($username_or_email)
    {

        if (!is_string($username_or_email) || 0 >= strlen($username_or_email)) {
            throw new InvalidArgumentException("username_or_email must be a non-empty string");
        }

        if (strstr($username_or_email, '@')) {
            $command = $this->client->getCommand("RequestResetPassword", array('email' => $username_or_email));
        } else {
            $command = $this->client->getCommand("RequestResetPassword", array('username' => $username_or_email));
        }


        return $this->executeCommand($command);
    }

    /******************************************************************************/
    /*				  				  PROFILE METHODS    	   					  */
    /******************************************************************************/

    /**
     * Show a profile of an user
     */
    public function showAccount()
    {
        $command = $this->client->getCommand('ShowAccount');
        return $this->executeCommand($command);
    }

    public function updateAccount($username, $email, $current_password)
    {
        if (!is_string($username) || 0 >= strlen($username)) {
            throw new InvalidArgumentException(
                "ApiException::updateProfile username field needs to be a non-empty string");
        }
        if (!is_string($email) || 0 >= strlen($email)) {
            throw new InvalidArgumentException(
                "ApiException::updateProfile email field needs to be a non-empty string");
        }
        if (!is_string($current_password) || 0 >= strlen($current_password)) {
            throw new InvalidArgumentException(
                "ApiException::updateProfile current_password field needs to be a non-empty string");
        }

        $command = $this->client->getCommand(
            'UpdateAccount',
            array(
                'profile' => array(
                    'username' => $username,
                    'email' => $email,
                    'current_password' => $current_password
                )
            )
        );

        return $this->executeCommand($command);
    }

    public function changePassword(
        $current_password,
        $new_password,
        $repeat_new_password
    ) {
        if (!is_string($current_password) || 0 >= strlen($current_password)) {
            throw new InvalidArgumentException(
                "ApiException::changePassword() current_password must be a non-empty string");
        }

        if (!is_string($new_password) || 0 >= strlen($new_password)) {
            throw new InvalidArgumentException(
                "ApiException::changePassword() new_password must be a non-empty string");
        }

        if (!is_string($repeat_new_password)
            || 0 >= strlen($repeat_new_password)
        ) {
            throw new InvalidArgumentException(
                "ApiException::changePassword() repeat_new_password must be a non-empty string");
        }

        if (strcmp($new_password, $repeat_new_password)) {
            throw new InvalidArgumentException(
                "ApiException::changePassword() the new_password and repeat_new_password isn't equals");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'ChangePassword',
            array(
                'change_password' => array(
                    'current_password' => $current_password,
                    'plainPassword' => array('first' => $new_password, 'second' => $repeat_new_password)
                )
            )
        );

        return $this->executeCommand($command);
    }

    /******************************************************************************/
    /*				  				  CHANNEL METHODS    	   					  */
    /******************************************************************************/

    /**
     * List all channels register in Server
     *
     * @param int $limit  number of items to retrieve at most
     * @param int $offset The distance (displacement) from the start of a data
     * @param array $filter Associative array with format filter_name =>value_name
     * @return array|Collection Associative array with channels data
     * @throws \InvalidArgumentException This exception is thrown if any parameter is incorrect
     * @Guzzle\Http\Exception\BadResponseException This exception is thrown for errors in the parameters of  the request, for example, apply a filter that does not exist.
     * @throws \Guzzle\Http\Exception\ClientErrorResponseException This exception is thrown for errors in the server or we have errors in the parameters of  the request
     * @throws \Guzzle\Http\Exception\CurlException This exception is thrown if there is error in the request, for example wrong or server url fallen
     */
    public function showChannels($limit = 25, $offset = 0, array $filter = null)
    {

        if ($limit < 1) {
            throw new InvalidArgumentException(
                "Api::showChannels() limit must be a min 1 ");
        }
        if ($offset < 0) {
            throw new InvalidArgumentException(
                "Api::showChannels() $offset must be a positive number,  min 0 ");
        }
        $filterHash = '';
        if($filter !== null){

            foreach ($filter as $key => $value) {

                $filterHash .= $key . '=' . $value;

                if ($value != end($filter)) {
                    $filterHash .= ',';
                }
            }
            $filterHash;
        }
        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'GetChannels',
            array('limit' => $limit, 'offset' => $offset, 'filter' => $filterHash)
        );

        return $this->executeCommand($command);
    }

    public function addChanel($name, $title = '', $description = '', $channel_type = '')
    {
        if (!is_string($name) || 0 >= strlen($name)) {
            throw new InvalidArgumentException("addChanel name field needs to be a non-empty string");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'AddChannel',
            array('channel' =>
                array("name" => $name,
                      "title" => $title, "description" => $description,'channel_type'=>$channel_type))
        );

        return $this->executeCommand($command);
    }

    public function updateChannel($channel_id, $name, $title = '', $description = '', $channel_type = '')
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::updateChannel channel_id field should be positive integer");
        }
        if (!is_string($name) || 0 >= strlen($name)) {
            throw new InvalidArgumentException(
                "ApiException::updateChannel name field needs to be a non-empty string");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'UpdateChannel',
            array(
                "id" => $channel_id,
                'channel' => array("name" => $name, "title" => $title, "description" => $description, 'channel_type'=>$channel_type)
            )
        );

        return $this->executeCommand($command);

    }

    public function delChannel($channel_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::updateChannel channel_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('DeleteChannel', array("id" => $channel_id));

        return $this->executeCommand($command);
    }

    public function showChannel($channel_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ShowChannel channel_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannel', array('id' => $channel_id));

        return $this->executeCommand($command);
    }

    public function showChannelFans($channel_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelFans channel_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelFans', array("id" => $channel_id));

        return $this->executeCommand($command);
    }

    public function showChannelsTypes()
    {
        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelsType');

        return $this->executeCommand($command);
    }

    public function showUserChannels($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelsByUser user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetChannelsCreatedByUser', array("id" => $user_id));

        return $this->executeCommand($command);
    }

    public function showUserChannelsFan($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::showChannelsFan user_id field should be positive integer");
        }
        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('GetFavoritesChannelsByUser', array('id' => $user_id));

        return $this->executeCommand($command);
    }

    public function addUserChannelFan($channel_id, $user_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::addChannelFan channel_id field should be positive integer");
        }
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::addChannelFan user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'SetChannelFan',
            array('channel_id' => $channel_id, 'user_id' => $user_id)
        );

        return $this->executeCommand($command);

    }

    public function delUserChannelFan($channel_id, $user_id)
    {
        if (!is_numeric($channel_id) || 0 >= $channel_id) {
            throw new InvalidArgumentException(
                "ApiException::addChannelFan channel_id field should be positive integer");
        }
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ApiException::addChannelFan user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'DeleteChannelFan',
            array('channel_id' => $channel_id, 'user_id' => $user_id)
        );

        return $this->executeCommand($command);
    }

    /**********************************************************************************************************************/

    public function showFriends($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ShowFriends user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('ShowFriends', array('id' => $user_id));

        return $this->executeCommand($command);
    }

    public function addFriends($user_id, $friend_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addFriends user_id field should be positive integer");
        }

        if (!is_numeric($friend_id) || 0 >= $friend_id) {
            throw new InvalidArgumentException(
                "addFriends friend_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('AddFriends', array('id' => $user_id, 'user_id' => $friend_id));

        return $this->executeCommand($command);
    }

    public function showFriendshipsPending($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "showFriendshipsPending user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('ShowFriendshipsPending', array('id' => $user_id));

        return $this->executeCommand($command);
    }

    public function showFriendshipsRequest($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "showFriendshipsRequest user_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand('ShowFriendshipsRequest', array('id' => $user_id));

        return $this->executeCommand($command);
    }

    public function addFriendshipRequest($user_id, $user_accept_id)
    {

        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addFriendshipRequest user_id field should be positive integer");
        }

        if (!is_numeric($user_accept_id) || 0 >= $user_accept_id) {
            throw new InvalidArgumentException(
                "addFriendshipRequest user_accept_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'AddFriendshipRequest',
            array('id' => $user_id, 'user_accept_id' => $user_accept_id)
        );

        return $this->executeCommand($command);
    }

    public function delFriendshipRequest($user_id, $user_decline_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addFriendshipRequest user_id field should be positive integer");
        }

        if (!is_numeric($user_decline_id) || 0 >= $user_decline_id) {
            throw new InvalidArgumentException(
                "delFriendshipRequest user_decline_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'DeleteFriendshipRequest',
            array('id' => $user_id, 'user_accept_id' => $user_decline_id)
        );

        return $this->executeCommand($command);
    }

    public function delFriend($user_id, $user_delete_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "delFriend user_id field should be positive integer");
        }

        if (!is_numeric($user_delete_id) || 0 >= $user_delete_id) {
            throw new InvalidArgumentException(
                "delFriend user_delete_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'DeleteFriends',
            array('id' => $user_id, 'user_delete_id' => $user_delete_id)
        );

        return $this->executeCommand($command);
    }

    /**********************************************************************************************************************/

    /**
     * Get my user of session
     */
    public function whoami()
    {
        $command = $this->client->getCommand('Whoami');

        return $this->executeCommand($command);
    }

    /**
     * Delete my user
     */
    public function delMe()
    {
        $command = $this->client->getCommand('DelMe');

        return $this->executeCommand($command);
    }

    /**********************************************************************************************************************/

    public function addReportPhoto($photo_id, $reason)
    {
        if (!is_numeric($photo_id) || 0 >= $photo_id) {
            throw new InvalidArgumentException(
                "addReportPhoto photo_id field should be positive integer");
        }

        if (!is_string($reason) || 0 >= strlen($reason)) {
            throw new InvalidArgumentException("addReportPhoto reason field needs to be a non-empty string");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'AddReportPhoto',
            array('id' => $photo_id, 'reason' => $reason)
        );

        return $this->executeCommand($command);
    }


    public function delPhoto($photo_id)
    {
        if (!is_numeric($photo_id) || 0 >= $photo_id) {
            throw new InvalidArgumentException(
                "delPhoto photo_id field should be positive integer");
        }

        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'delPhoto',
            array('id' => $photo_id)
        );

        return $this->executeCommand($command);
    }

    public function addAlbum($user_id, $title, $description='')
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addAlbum user_id field should be positive integer");
        }

        if (!is_string($title) || 0 >= strlen($title)) {
            throw new InvalidArgumentException("addAlbum reason title field needs to be a non-empty string");
        }


        //@var $command Guzzle\Service\Command\AbstractCommand
        $command = $this->client->getCommand(
            'AddAlbum',
            array('id' => $user_id,'ant_photo_album'=>array('title'=>$title,'description'=>$description)));

        return $this->executeCommand($command);
    }
    public function addPhoto($user_id, $imageTile, $imageFile)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addPhoto user_id field should be positive integer");
        }

        if (!is_string($imageTile) || 0 >= strlen($imageTile)) {
            throw new InvalidArgumentException("addPhoto imageTile title field needs to be a non-empty string");
        }

        if (!is_string($imageFile) || 0 >= strlen($imageFile)) {
            throw new InvalidArgumentException("addPhoto imageFile title field needs to be a non-empty string");
        }
        if(!file_exists($imageFile)){
            throw new InvalidArgumentException("addPhoto '.$imageFile.' not exist or It do not read");
        }

        /* @var $command \Guzzle\Service\Command\OperationCommand */
        $command = $this->client->getCommand(
            'AddPhoto',
            array('id' => $user_id,'ant_photo'=>array('title'=>$imageTile)));


        return $this->executeCommand($command);
    }
    public function showPhoto($photo_id)
    {
        // TODO: Implement showPhoto() method.
    }

    public function showPhotoVotes($photo_id)
    {
        // TODO: Implement showPhotoVotes() method.$user_id
    }

    public function showPhotos($user_id)
    {
        // TODO: Implement showPhotos() method.
    }



    public function showUserVotes($user_id)
    {
        // TODO: Implement showUserVotes() method.
    }

    public function addPhotoVote($user_id, $photo_id, $core)
    {
        // TODO: Implement addPhotoVote() method.
    }

    public function delPhotoVote($photo_id)
    {
        // TODO: Implement delPhotoVote() method.
    }



    /**********************************************************************************************************************/

    public function showReports()
    {
        // TODO: Implement delPhoto() method.
    }

    public function showReport($report_id)
    {

    }

    public function addAsReviewedReport($report_id)
    {

    }

    /**********************************************************************************************************************/

    public function addThread($user_id, $recipient, $subject, $body)
    {
        // TODO: Implement addThread() method.
    }

    public function showThreadsInbox($user_id)
    {
        // TODO: Implement showThreadsInbox() method.
    }

    public function showThreadsSent($user_id)
    {
        // TODO: Implement showThreadsSent() method.
    }

    public function showThread($thread_id)
    {
        // TODO: Implement showThread() method.
    }

    public function addThreadMessage($user_id, $thread_id, $body)
    {
        // TODO: Implement addThreadMessage() method.
    }

    public function delThread($thread_id)
    {
        // TODO: Implement delThread() method.
    }

    /**********************************************************************************************************************/

    /**
     * Get all the users
     */
    public function who($page = 1)
    {
        $command = $this->client->getCommand('Who', array('page' => $page));

        return $this->executeCommand($command);
    }

    public function showUser($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "showUser user_id field should be positive integer", 404);
        }

        $command = $this->client->getCommand('ShowUser', array('id' => $user_id));

        return $this->executeCommand($command);
    }

    public function showUsersBlocked($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "showUsersBlocked user_id field should be positive integer", 404);
        }

        $command = $this->client->getCommand('showUsersBlocked', array('id' => $user_id));

        return $this->executeCommand($command);
    }

    public function addUserBlocked($user_id, $user_blocked_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addUserBlocked user_id field should be positive integer", 404);
        }

        if (!is_numeric($user_blocked_id) || 0 >= $user_blocked_id) {
            throw new InvalidArgumentException(
                "addUserBlocked user_blocked_id field should be positive integer", 404);
        }

        $command = $this->client->getCommand('AddUserBlocked', array('id' => $user_id, 'user_id' => $user_blocked_id));

        return $this->executeCommand($command);
    }

    public function updateUserProfile($user_id, $about, $sexualOrientation)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addUserProfile user_id field should be positive integer", 404);
        }
        if (!is_string($about) || 0 >= strlen($about)) {
            throw new InvalidArgumentException(
                "about must be a non-empty string", 404);
        }
        if (!is_string($sexualOrientation) || 0 >= strlen($sexualOrientation)) {
            throw new InvalidArgumentException(
                "sexualOrientation must be a non-empty string", 404);
        }

        $command = $this->client->getCommand(
            'UpdateUserProfile',
            array(
                'id' => $user_id,
                'social_profile' => array('about' => $about, 'sexualOrientation' => $sexualOrientation)
            )
        );

        return $this->executeCommand($command);
    }

    public function showUserProfile($user_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "ShowUserProfile user_id field should be positive integer", 404);
        }

        $command = $this->client->getCommand('ShowUserProfile', array('id' => $user_id));

        return $this->executeCommand($command);
    }

    public function addUserProfile($user_id, $about, $sexualOrientation)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "addUserProfile user_id field should be positive integer", 404);
        }
        if (!is_string($about) || 0 >= strlen($about)) {
            throw new InvalidArgumentException(
                "about must be a non-empty string", 404);
        }
        if (!is_string($sexualOrientation) || 0 >= strlen($sexualOrientation)) {
            throw new InvalidArgumentException(
                "sexualOrientation must be a non-empty string", 404);
        }

        $command = $this->client->getCommand(
            'AddUserProfile',
            array(
                'id' => $user_id,
                'social_profile' => array('about' => $about, 'sexualOrientation' => $sexualOrientation)
            )
        );

        return $this->executeCommand($command);
    }

    public function addUserReports($user_id, $reason)
    {
        throw new \Exception("TThis method is not supported yet");
    }

    public function delUserBlocked($user_id, $user_blocked_id)
    {
        if (!is_numeric($user_id) || 0 >= $user_id) {
            throw new InvalidArgumentException(
                "DeleteUserBlocked user_id field should be positive integer", 404);
        }

        if (!is_numeric($user_blocked_id) || 0 >= $user_blocked_id) {
            throw new InvalidArgumentException(
                "DeleteUserBlocked user_blocked_id field should be positive integer", 404);
        }

        $command = $this->client->getCommand(
            'DeleteUserBlocked',
            array('user_id' => $user_id, 'blocked_user_id' => $user_blocked_id)
        );

        return $this->executeCommand($command);
    }

    public function showUserVisitors($user_id, $maxResult)
    {
        throw new \Exception("TThis method is not supported yet");
    }


}
