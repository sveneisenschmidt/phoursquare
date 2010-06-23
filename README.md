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
    // Uses thr User from Authetication
    $friends = $service->getFriends();

    // OR

    $id      = 666; // some user id
    $friends = $service->getFriends($id);