# FRC Stronghold Scouting Site

## API
API is currently located at riverdalerobotics.com/scouting/api

### Authentication
Authentication should be made to api/authenticate.php
Request should be of type POST made with the following fields:

| POST Key | Description               |
|----------|---------------------------|
| email    | Email address of the user |
| password | Password of the user      |

#### Example Return

``` 
{"user":
    {"userId":"4",
    "fullName":"test",
    "APIKey":"35c2b15b03040726248efbfce75a9225021d27c6d8222acb2c91c04ff63ab78a9b5d0e2933a65bc493482801163ba56966406fb8dc4e99d26fea0e4077a48855",
    "scoutTeamNumber":"5834"}}
```


### Normal Requests
Every other request (besides the ones made to the authentication page) should be made with the following fields:

| POST Key        | Description                                   |
|-----------------|-----------------------------------------------|
| apiKey          | User's API key                                |
| userId          | ID of a user (from the authentication object) |
| scoutTeamNumber | User's FRC team number                        |

## Error Codes ##
### Less than 10
Access error
### 20s 
Authentication error
### 30s
Adding error
