Session
=======

Simple session manager that allow you to create a session with cookie support, i.e persistence of the session, even if the browser is closed, the only way to delete it is doing a logout.
Note that you can not use the cookies, if you want.


Usage
=====
`````php
<?php
require 'session.php';
$_session = Session::connect(); //singleton method.
$_session->run('tomi' /*the user name or something*/, true /*false if you dont want to use cookies*/);

//(...)
$_session->destroy();
`````

IMPORTANT
=========
*This class is very poor and not secure*, it's under construction.

Pretty soon it will have new options and will be more secure to use.

Here is a list of some methods that the class will have in the future:

- Options that will be applied from a method.
- serialize and unserialize the session and cookie.
- Get session and cookie keys.
- Encrypt data.
- The class will be namespaced.
- Better architecture.
