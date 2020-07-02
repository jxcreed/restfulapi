How to install:
Go to

1. C:/xampp/htdocs/restfulapi
   Install the folder inside your htdocs

2. C:\xampp\apache\conf\extra\httpd-vhosts.conf

Add the host configuration below:

<VirtualHost *:80>
    ServerName dev.restfulapi.com
    DocumentRoot "c:/xampp/htdocs/restfulapi/public/"
    <Directory  "c:/xampp/htdocs/restfulapi/public/">
        Options +Indexes +Includes +FollowSymLinks +MultiViews
        AllowOverride All
        Require local
    </Directory>
</VirtualHost>

3. C:\Windows\System32\drivers\etc\hosts.conf

Add the domain configuration below:

127.0.0.1       dev.restfulapi.com


4. Start the wamp/xampp server (Apache, MYSQL)

5. Import the restfulapi_db in your phpmyadmin


To Test the RESTful API use POSTMAN:

1. Registration = [POST] http://dev.restfulapi.com/api/user-register
2. User Activation(This should be on your email notification link, the link below is just a sample) 
= [GET] http://dev.restfulapi.com/api/email-verification/YDOVJRIUrQN25nHJoDXb9DuSyyGYCMeJK0wd4jFpcAH9WAFzubhQTBzmnDo0ZRQy6ZXlQBisSAH69D508wXMzxDg9mv3XGF45jOc
3. Login = [POST] http://dev.restfulapi.com/api/login
4. Users list = [GET] http://dev.restfulapi.com/api/users-list
5. Change password = [PUT] http://dev.restfulapi.com/api/user-changepassword/1