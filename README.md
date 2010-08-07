# phoursquare

**Done:**

* User retrieval
* Friend(s) retrieval
* Get Check-in from User
* Get Venue (also from User via Check-in)
* Get Check-in Stats
* Get Check-in Tips
* Get Major from Venue
* Caching
* Get Venue Categories
* List Categories
* Search for Venues (from you or veryone nearby)
* Search for Tips  (from you or veryone nearby)
* Search for Users (only non friends) by Name, Phone or Twitter Nickname
* Search for Friends by Name, Phone or Twitter Nickname
* Checkin at any Venue :) - finally!
  (clears the user object cache and history to have the checkin immediately in your history)
* Retrieve the score of any Checkin
* Add Tips
* Mark Tips as done
* Accept, deny, create Friend Requests

**ToDo:**

* Implement Badges on Checkins
* ... some more I forgot ;)

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


**Setup as Singleton:**

    $service = PhoursquareSingleton::getInstance();

    // if(!$service->hasAuth()) {
    if(!PhoursquareSingleton::hasAuthInstance()) {
        require_once 'Phoursquare/Auth/Http.php';
        $auth    = new Phoursquare_Auth_Http();
        $auth->setUsername('sven.eisenschmidt@gmail.com');
        $auth->setPassword('svenei86');
        $service->setAuth($auth);
    }

    // if(!$service->hasCache()) {
    if(!PhoursquareSingleton::hasCacheInstance()) {
        require_once 'Zend/Cache.php';
        $cache = Zend_Cache::factory(
            'Core', 'File',
            array(
                'lifetime' => 360,
                'automatic_serialization' => true
            ),
            array(
                'cache_dir' => sys_get_temp_dir()
            )
        );
        $service->setCache($cache);
    }


**Caching:**

You can use your own Cache Class by only extending it from 
Phoursquare_Cache_AbstractCache or use Zend_Cache

    require_once 'Zend/Cache.php';
    $cache = Zend_Cache::factory(
        'Core', 'File',
        array(
            'lifetime' => 360,
            'automatic_serialization' => true
        ),
        array(
            'cache_dir' => sys_get_temp_dir()
        )
    );

    $service->setCache($cache);

**Search:**

You can search through an fluid interface which loads the results
when you iterate "over" it.

    $results = $service->search()
                       ->query(Phoursquare_Query::TIP) //OR Phoursquare::VENUE
                       ->nearby() //OR me()
                       ->limit(10)
                       ->geolat(52.516805)
                       ->geolong(13.380854);

    foreach($results as $result) {

        print $result->getText();

        // loads the related venue
        // Be careful when loading related venues, this can be very time consuming!
        print $result->getRelatedVenue()
                     ->getName();

    }

Search for Friends/NonFriends

    $results = $service->search()
                       ->query(Phoursquare_Query::FRIEND_NAME)
                       ->limit(50)
                       ->keyword('sven');


Shortcut methods

* use ::venue() instead of ::query(Phoursquare_Query::VENUE)
* use ::tip() instead of ::query(Phoursquare_Query::TIP)
* use ::friendByName() instead of ::query(Phoursquare_Query::FRIEND_NAME)
* use ::nonFriendByName() instead of ::query(Phoursquare_Query::NON_FRIEND_NAME)
* use ::nonFriendByPhone() instead of ::query(Phoursquare_Query::NON_FRIEND_PHONE)
* use ::nonFriendByTwitter() instead of ::query(Phoursquare_Query::NON_FRIEND_TWITTER)


Instead of using ::geolat() and ::geolong() you can set an address via:

       ... // Query
       ->address(array(
           'Pariser Platz 4A',
           'Berlin',
           '10117',
           'Germany'
       ));

The correspondending geo location will be retrieved via Google Maps Location API.
Beware, the Request will fail and maybe throw an Exception when no correct addrress(es)
could be found.



**GeoLocation retrieval:**

    // expect $service is a fully initialized instance of Phoursquare
    $foundAddresses = $service->geocode(array(
                         'Pariser Platz 4A',
                         'Berlin',
                         '10117',
                         'Germany'
                      ));

foundAddresses is a collection of GeoLocation Objects with these following methods:

* getFormattedAddress
* getLatitude
* getLongitude


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


**Category retrieval & traversing:**

    $cineplex = $service->getCategories()
                        ->find(78973);
    $name = $cineplex->getNodename();

    // OR

    $cineplex = $service->getCategory(78973);

    // You can also iterate over Siblings

    $siblings = $service->getCategory(78973)
                        ->getSiblings();

    foreach($siblings as $sibling) {

        // Should never print 78973
        print $sibling->getId();
    }

    // Traversing

    $cineplex = $service->getCategory(78973);
    $theater  = $cineplex->getParentCategory();
    

**Check-in & Venue retrieval:**

    $myself   = $service->getAuthenticatedUser();
    $checkins = $myself->getCheckins($limit = 25, $sinceId = null);

    foreach($checkins as $checkin) {

        $msg   = $checkin->getDisplayMessage();
        $venue = $checkin->getVenue();

        $address => array(
            'address    => $venue->getAddress(),
            'city'      => $venue->getCity(),
            'zip-code'  => $venue->getZipCode()
        );
    }

    // OR
    // Note: This venue will not be attached to any checkin
    //       If you load a venue via a Checkin the venue will
    //       stay in relation to a Checkin
    $venue = $service->getVenue($venueId = 666);

    // check-in into this venue
    $venue->checkinHere(array(
        'shout' => 'Whoohoo, my first checkin!',
        'twitter' => true
    ));

**Venue Statistics:**

    $stats = $service->getAuthenticatedUser()
                     ->getLastCheckin()
                     ->getVenue()
                     ->getSatistics();

    // For an overview of the avialable Methods, scroll down ;)


**Venue Tips:**

    $tips = $service->getAuthenticatedUser()
                    ->getLastCheckin()
                    ->getVenue()
                    ->getTips();


    foreach($tips as $tip) {

        $creator = $tip->getCreator();
    }

    // For an overview of the avialable Methods, scroll down ;)


**Venue Categories:**

    $cats = $service->getAuthenticatedUser()
                    ->getLastCheckin()
                    ->getVenue()
                    ->getCategories();


    foreach($cats as $category) {

        $name = $category->getNodename();
    }

    // For an overview of the avialable Methods, scroll down ;)


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
* getLastCheckin


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


**Avaliable Methods for Check-ins:**

* getId
* getVenue
* getCreated
* getTimezone
* hasVenue
* getVenue


**Avaliable Methods for Venues:**

* getId
* getName
* getAddress
* getCity
* getZipCode
* getState
* getGeoLantide
* getGeoLongitude
* getStatistics
* getTips
* checkinHere


**Avaliable Methods for Venue Statistics:**

* getCheckinCount
* getPeopleCountHereCheckedIn
* beenHere
* hasMayor
* getMayor
* getRelatedVenue


**Avaliable Methods for Venue Tips:**

* getCreator
* getRelatedVenue
* getAllTipsFromSameVenue
* getText
* getCreated


**Avaliable Methods for (Venue) Categories:**

* getId
* getRelatedVenue
* getNodename
* getFullNodepath
* getIconUrl
* hasParentCategory
* getParentCategory
* find
* filter
