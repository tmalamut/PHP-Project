# PHP-Project

Project completed for my Introduction to Server-Side Web Application Development class (Fall 2019). 

## Description

Web-based application built using HTML, CSS, and PHP. Also utilizes a MySQL server owned by the university.  

## Functionalities

* Header
  * Welcomes back user if logged in. 
* Navigation bar
  * Displays login if user is not logged in, otherwise displays logout.
* Registration form
  * Allows user to register. User information is inserted into the MySQL database.
  * Checks if username is taken.
  * Checks if email is valid.
  * Checks if password and confirmation password are the same.
  * Hashes the password using Blowfish.
  
* Login page
  * Allows user to login if username and password are in the database. 

* User authentication 
  * Does not allow user to add content and view management page if not logged in.
  * Allows user to update and delete their own content. Update form is pre-populated with user's previous entries.
  
 * Logout page
   * Confirms that user has logged out.
   
* Public page
  * Allows everyone to view all current entries posted by users. Updated when users update and/or delete their content.

### Note

The connect.inc.php page has been redacted for security purposes. 
