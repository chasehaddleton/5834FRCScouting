# FRC Stronghold Scouting Site

## API
API is currently located at https://riverdalerobotics.com/scouting/api

### Registration
Registration can be made through the API by POSTing to api/registration.php
Request should contain the following keys:

| POST Key   | Description               |
|------------|---------------------------|
| email      | Email address of the user |
| password   | Password of the user      |
| name       | Name of the user          |
| teamNumber | Team number of the user   |

Optional keys:

| POST Key         | Description               |
|------------------|---------------------------|
| phoneNumber      | Phone number of the user  |

#### Example Successful Return
```
    {"Success"}
```

### Authentication
Authentication should be made to api/authenticate.php
Request should be of type POST made with the following keys:

| POST Key | Description               |
|----------|---------------------------|
| email    | Email address of the user |
| password | Password of the user      |

#### Example Successful Return

``` 
{"user":
    {"userId":"4",
    "fullName":"test",
    "APIKey":"35c2b15b03040726248efbfce75a9225021d27c6d8222acb2c91c04ff63ab78a9b5d0e2933a65bc493482801163ba56966406fb8dc4e99d26fea0e4077a48855",
    "scoutTeamNumber":"5834"}}
```

### Normal Requests
Every other request (besides the ones made to the authentication and registration pages) should be made with the following keys:

| POST Key        | Description                                   |
|-----------------|-----------------------------------------------|
| apiKey          | User's API key                                |
| userId          | ID of a user (from the authentication object) |
| scoutTeamNumber | User's FRC team number                        |

### Adding Game Action
Adding a game action can be done through the api/add.php page. 
Different add actions require different POST keys, but all require the following (in addition to the keys required for a normal request):
 
| POST Key        | Description                                         |
|-----------------|-----------------------------------------------------|
| type            | The type of game action to add                      |
| compKey         | The key for the competition                         |
| matchNumber     | The match number at the competition                 |
| teamNumber      | The number of the team you are adding an action for |

#### Shot
To add a shot, the following keys are required:

| POST Key        | type                            |Description                                                         |
|-----------------|---------------------------------|--------------------------------------------------------------------|
| scored          | boolean                         | Whether or not the team was successful in scoring                  |
| auto            | boolean                         | Whether or not the shot was made during autonomous                 |
| rampShot        | boolean                         | Whether or not the shot was made from the bottom of the tower ramp |
| towerSide       | ENUM('LEFT', 'CENTER', 'RIGHT') | What side of the tower the shot was made from                      |
| towerGoal       | ENUM('HIGH', 'LOW')             | Which goal position the shot was made to                           |

#### Crossing
To add a crossing, the following keys are required:

| POST Key        | type                                                       |Description                                                         |
|-----------------|------------------------------------------------------------|--------------------------------------------------------------------|
| defenseName     | ENUM('PC', 'CF', 'RP', 'MT', 'DB', 'SP', 'RW', 'RT', 'LB') | The name of the defense that the team crossed                      |
| auto            | boolean                                                    | Whether or not the shot was made during autonomous                 |
| assist          | ENUM('NONE', 'OPEN', 'PUSH')                               | Was assistance required? What type?                                |

## Errors
The API returns a standard error object for any errors encountered.

#### Example Error
```
    {"msg":"Error, must POST name, email, team number, and password","code":10}
```

### Error Codes
#### Less than 10
Access error
#### 20s 
Authentication error
#### 30s
Adding error
