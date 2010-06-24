# phoursquare

**Done:**

* User retrieval
* Friend(s) retrieval
* Get Check-in from User
* Get Venue (also from User via Check-in)
* Get Check-in Stats

**ToDo:**

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
    $user = $service->getUser($uid = 666);


**Friend retrieval:**

    $myself  = $service->getAuthenticatedUser();
    $friends = $myself->getFriends()

    // OR
    // Uses the User from Authentication
    $friends = $service->getFriends();

    // OR
    $friends = $service->getFriends($friendsId = 666);


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
    $friends = $this->getUser($uid = 666)->getFriends();

    foreach($friends as $friend) {

        // see User Methods
        $user = $this->getFullUser();
        
    }


**Check-in & venue retrieval:**

    $myself   = $service->getAuthenticatedUser();
    $checkins = $myself->getCheckins($limit = 25, $sinceId = null);

    foreach($checkins as $checkin) {

        $msg   = $checkin->getDisplayMessage();
        $venue = $checkin->getVenue();

        $adress => array(
            'address    => $venue->getAddress(),
            'city'      => $venue->getCity(),
            'zip-code'  => $venue->getZipCode()
        );
    }

    // OR

    $venue = $service->getVenue($venueId = 666);



**Avaliable Methods on User Objects:**

// On the authenticated User 

* getId
* getFirstname
* getLastname
* getPhoto
* getGender
* getTwitter
* getFacebook
* hasTwitter
* hasFacebook
* getEmail
* getPhone
* getCheckins

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
* hasTwitter
* hasFacebook
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
* hasTwitter
* hasFacebook


**Avaliable Methods on Check-ins:**

* getId
* getVenue
* getCreated
* getTimezone
* hasVenue
* getVenue


**Avaliable Methods on Venues:**

* getId
* getName
* getAddress
* getCity
* getZipCode
* getState
* getGeoLantide
* getGeoLongitude