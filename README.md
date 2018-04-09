#Simple MVC app for an interview for a developer position

App handles only these request:

User controller: 
```   
/?r=user/register- to create new user   
/?r=user/login - to log in with login & password   
/?r=user/logout - to log out from app   
```
```
Vacation controller:    
/?r=vacation/request - to request vacation    
/?r=vacation/status - to check current user's vacation. Also, user can delete vacation requests here, until they are processed.    
/?r=vacation/manage - to update request's status, until request is not updated.
```   
And Default controller:    
/ - home(welcome) page    

Class app\core\AppCore - is main core class. With help of a bunch of classes from app\core\service it parses the request and loads propper controller's action.
Settings are placed in config files in core\config folder, and accessible via AppCore::$settings property;
Also, there are some service classes are globally accessable via AppCore's static variables ($request, $response, $session etc.)
Autoload is provided by composer. $logger is instance of monolog/monolog logging mechanism. Can be used on a purpose.

Simple RBAC to rule permissions of users. 

Thanks for your time!
