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

## Error Codes ##
### Less than 10
Access error
### 20s 
Authentication error
### 30s
Adding error
