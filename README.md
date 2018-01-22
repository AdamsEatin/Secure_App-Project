# Secure_App-Project
This task was undertaken as part of my 'Secure Application Development' module.

The core task was to create a secure login mechanism using PHP, MySQL and the XAMPP Software. In addition to this I utilised JavaScript as to implement the password complexity through the use of RegEx.



## Core Components ##
1. The ability to register with the system.
    1. Must allow for both a username and password.
    2. Passwords must have complexity rules applied.    
2. User must be able to login to the system using their chosen username and password.
    1. If they are unsuccessful in their authentication they must be presented with an error message
    2. While authenticated, there must be at least two different pages which only authenticated users can access.
3. System must implement a lockout mechanism for failed login attempts.
    1. If there are 3 failed attempts lockout for 5 minutes.    
4. The ability for a user to change their password.
    1. Must have same password complexity rules applied to them.
    2. User must have to re-authenticate after changing their password.
