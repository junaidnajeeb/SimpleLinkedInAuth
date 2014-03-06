SimpleLinkedInAuth
==================

Description
--------------
This is a basic implementation of how to simply connect to LinkedIn API and authenticate your App users with LinkedIn. No need to write your own custom login feature. 

List of files
--------------
- index.php
- linkedInAuth/LinkedIn.php
- linkedInAuth/config.php
- linkedInAuth/linkedin-button.png
- linkedInAuth/linkedin_login_get_code.php


index.php
--------------
This comtains a simple demo of how to authenticate and makes a simple API call to fetch authenticated LinkedIn user data.

linkedInAuth/LinkedIn.php
--------------
Simple wrapper class to make pretty API calls


linkedInAuth/config.php
--------------
Stores constants


linkedInAuth/linkedin-button.png
--------------
Added a image on the link to make things more 21st century :)

linkedInAuth/linkedin_login_get_code.php
--------------
This do a two step authentication as described in LinkedIn API Docs
-  **First** it will get a status code
-  **Second** it will use that code to authenticate the user.
  
  
