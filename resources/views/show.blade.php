<!DOCTYPE html>
<html>
 <head>
 <title>User {{ $user->id }}</title>
 </head>
 <body>
 <h1>Name {{ $user->name }}</h1>
 <ul>
 <li>Email: {{ $user->email }}</li>
 <li>Password: {{ $user->password }}</li>
 <li>Created: {{ $user->created_at }}</li>
 </ul>
 </body>
</html>
