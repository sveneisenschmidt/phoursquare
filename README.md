# phoursquare

**Done:**

* User retrieval
* Friend(s) retrieval

**ToDo:**

* Get last Check-ins
* Get Major Status
* Able to Check-in somewhere
* Search for Venues
* ... somemore I forgot ;)

## Basic Usage

**Setup:** 

    $auth = new Phoursquare_Auth_Http();
    $auth->setUsername('user@isp.tld');
    $auth->setPassword('mysecretpassword');

    $service = new Phoursquare($auth);

    // Partly implemented

    $auth = new Phoursquare_Auth_Oauth();
    $auth->setUsername('user@isp.tld');
    $auth->setPassword('mysecretpassword');

    // Before the first connection the username & password
    // is exchanged for token & secret token
    $service = new Phoursquare($auth);


**User retrieval:**

    // Authenticated User
    $myself = $service->getAuthenticatedUser();

    // Another User (by id)
    $user = $service->getUser(666);


**Friend retrieval:**

    $myself  = $service->getAuthenticatedUser();
    $friends = $myself->getFriends()

    // OR
    // Uses the User from Authentication
    $friends = $service->getFriends();

    // OR

    $id      = 666; // some user id
    $friends = $service->getFriends($id);


    // Iterate over Friends
    // You have to know that the Friend Objects returned are only
    // Stubs of real Users, so you have to call the method getFullUser()
    $myself  = $service->getAuthenticatedUser();
    $friends = $myself->getFriends()
    $stub    = $friends->current();
    $user    = $stub->getFullUser();

    // OR short

    $user = $this->getAuthenticatedUser()
                 ->getFriends()
                 ->current()
                 ->getFullUser();

    // Foreach:

    $friends = $this->getAuthenticatedUser()->getFriends(); // OR
    $friends = $this->getUser(666)->getFriends();

    foreach($friends as $friend) {

        $user = $this->getFullUser();
        
    }


**Avaliable Methods on User Objects:**

// On the authenticated User 

* getId
* getFirstname
* getLastname
* getPhoto
* getGender
* getTwitter
* getFacebook
* getEmail
* getPhone

(coming)

* check-ins, etc


// On Friend from List

* getId
* getFirstname
* getLastname
* getFullUser


// On User with 'friendship'-relation

* getId
* getFirstname
* getLastname
* getPhoto
* getGender
* getTwitter
* getFacebook
* getEmail
* getPhone
* getFullUser


// On 'non'-unrelated User

* getId
* getFirstname
* getLastname
* getPhoto
* getGender
* getTwitter
* getFacebook