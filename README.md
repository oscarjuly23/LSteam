# LSteam

The students at La Salle are very passionate when it comes to gaming. When they have some free time between classes o when they want to take a break from all the projects that they have to do, they sit down and enjoy gaming. However, recently most of them have been having problems when buying games, so the professors proposed a solution to this problem. The proposal is to create a Game Store web application. 

## Introduction

As web developers, you are going to create a web application where the students can create their own accounts to have access to a wide range of games. They will be able to buy games, have a wishlist with the games that they would like to buy in the future, have friends with whom they can play with and be updated with the latest interesting games.

## Pre-requisites and requirements

To be able to create this web app, you are going to need a local environment suited with:

1. Web server (Nginx)
2. PHP 8
3. MySQL
4. Composer
5. Git

You have to use the Docker local-environment set up that we have been using in class.

### Requirements
1. Use Slim as the underlying framework.
2. Create and configure services in the `dependencies.php` file. Examples of services are Controllers, Repositories, 'view', 'flash', ...
3. Use Composer to manage all the dependencies of your application. There must be at least two dependencies.
4. Use Twig as the main template engine.
5. Use a CSS to stylize your application. Optionally, you may use a CSS framework.
6. Use MySQL as the main database management system.
7. Use Git to collaborate with your teammates.
8. All the code must be uploaded to the private Bitbucket repository that has been assigned to your team.
9. You must use Namespaces, Classes, and Interface.
10. Each member of the team must collaborate in the project with at least 10 commits. Each member must commit, at least, code regarding to the View (twig), the Controller, and the Model.


## Sections

1. Landing page
2. Register
3. Login
4. Profile
5. LSteam wallet
6. Store
7. Wishlist
8. LSteam Friends
9. Cache


### Landing Page
This section describes the characteristics of the landing page of the application.

Endpoint  | Method
--------- | -------
 /  		| GET
 
The landing page does not require user authentication. You need to implement a simple landing page where you will show a brief description, the main features and functionalities of LSteam. Notice that this page is different from the Store (you will see it later). This page does not show a list of all the available games. 

For this section, you will need to define a base template that is going to be used across all the pages of the application. This template must contain at least the following blocks:

* head - contains the title and the meta information of the page
* styles - loads all the required CSS
* header - contains the navigation menu
* content
* footer

Feel free to add additional blocks as you consider necessary.

### Register
This section describes the process of registering a new user into the system.

Endpoints                 | Method
------------------------- | -------
 /register                | GET
 /register                | POST
 /activate?token=12345678 | GET

If the user is not logged in, the Register link is shown in the navigation menu in the header.

When a user accesses the **/register** endpoint you need to display the registration form. The information of the form must be sent to the same endpoint using a **POST** method. The registration form must contain the following inputs:

* username - required
* email - required
* password - required
* repeat password - required
* birthday - required
* phone - optional

When a **POST** request is sent to the **/register** endpoint, you must validate the information received from the form and register the user only if all the validations have passed. The requirements for each field are as follows:

* email: It must be a valid email address. Only emails from the domain @salle.url.edu are accepted. The email must be unique among all users of the application.
* username: It must be alphanumeric. The username must be unique among all users of the application.
* password: It must contain more than 6 characters. It must contain both upper and lower case letters. It must contain numbers. It must be stored using a hash algorithm.
* birthday: It must be a valid date. Only users of legal age (more than 18 years) can be registered.
* phone: It must follow the [Spanish numbering plan](https://en.wikipedia.org/wiki/Telephone_numbers_in_Spain).

If there is any error, you need to display the register form again. All of the information entered by the user must be kept and shown in the form together with all the errors below the corresponding inputs.

An activation link will be sent through email when the user has registered all the information correctly. The activation link has to contain a previously generated token that will be used to identify and activate the user. 

The token must be send as a query parameter in the URL (check the routing definition). Once a token is used, it must be invalidated. If the same token is used  (by visiting the same link again), an error must be displayed.

Once the user's account is activated, the system will send another email to confirm the registration with a button to redirect the user to the Login page and 50€ will be added to the user's virtual wallet, which will be explained further in another section.

#### Email Activation
To send an email using PHP you have multiple options. The easiest one is to use the internal [mail](https://www.php.net/manual/en/function.mail.php). On the other hand, you can look for some good libraries available at **Packagist** like [PHPMailer](https://github.com/PHPMailer/PHPMailer). You just need to install the package using Composer. In addition to installing the package, you will have to configure an SMTP server to send emails. We recommend you to use use [SMTP Bucket](https://www.smtpbucket.com/).

**SMTP Bucket** implements the Simple Mail Transfer Protocol (SMTP), but it does not try to deliver the e-mails it receives. It's just a fake SMTP server that captures all the e-mails it receives and makes them available through the SMPT Bucket REST API and website.

In the sample code below, you can see that we are using SMTP Bucket as Host for the mail server.

```php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                //Enable verbose debug output
    $mail->isSMTP();                                      //Send using SMTP
    $mail->Host       = 'mail.smtpbucket.com';            //Set the SMTP server to send through
    $mail->Port       = 8025;                              //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
    $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
```

### Login
This section describes the process of logging in and logging out of the system.

Endpoints        | Method
---------------- | -------
 /login          | GET
 /login          | POST
 /logout         | POST
 
If the user is not logged in, the Login button is shown in the navigation menu in the header.

If the user is logged in, the Logout button is shown in the navigation menu of the header.

When a user accesses the **/login** URL you need to display the login form. The information of the form must be sent to the same endpoint using a POST method. The login form must contain the following inputs:

* email/username - required
* password - required

When the application receives a POST request in the **/login** endpoint, it must validate the information received from the form and if all the validations have passed, the system will try to log in the user. The validations of the inputs must be exactly the same as in the registration.

If there is any error or if the user does not exist, you need to display the form  again with all the information provided by the user and display a **generic** error.

Note: Only users with an active account must be able to log in.

After logging in, the user will be redirected to the Store which is described in section 5. Also, once the user is logged in, you need to display his profile picture (you will need to have a default profile picture in case the user has not uploaded any) in the navigation menu. This image must be a link to the user's profile page described in the next section.

Finally, if the user clicks on the Log Out icon, you need to logout the user from the system and redirect him to the Landing page.

Note: To implement the Logout, you may need to use an invisible form (meaning that you may want to hide input elements). The action of this form is going to be **/logout** with a **POST** method. Alternatively, you can tap on the onclick event with Javascript and send an AJAX request.

You can easily hide elements by adding `display: none;` in the CSS.

### Profile
This section describes the visualization and update of the user's personal information.

Endpoints                     | Method
----------------------------- | -------
 /profile                     | GET
 /profile                     | POST
 /profile/changePassword      | GET
 /profile/changePassword      | POST

If a user tries to access any of these endpoints manually without being authenticated, the web application must redirect him to the Login page with a warning message.

When a logged user accesses to the **/profile** endpoint, you need to display a form containing the following inputs:

* username
* email
* birthday
* phone
* profile_picture

The username, email, birthday and phone fields must be filled with the current stored information. The **username, email address and the birthday cannot be updated** so the inputs must be disabled.

The new input profile_picture must allow users to upload a profile picture. The requirements of the image are listed below:

1. The size of the image must be less than 1MB.
2. Only png and jpg images are allowed.
3. The image dimensions must be 500x500.
4. You need to generate a [UUID](https://github.com/ramsey/uuid) for the image.

When the form is submitted, you need to validate the phone and profile_picture. If there is any error, you need to display them below the corresponding input.

**Note**: All the images must be stored inside an uploads folder inside the public folder of the server in order to be able to display them.

Below the form, you need to display a link named "Change Password" pointing to the endpoint **/profile/changePassword**.

When a logged user accesses the /profile/changePassword endpoint, you need to display a "Reset Password" form containing the following inputs:

* old_password
* new_password
* confirm_password

When the form is submitted, you need to do the following validations:

1. The **old_password** must match the current password stored in the database.
2. The **new_password** format must be the same used in the registration form
3. The **confirm_password** must match the value introduced in the **new_password**

If there is any error, you need to display again the form (all the inputs must be empty) and display a generic error. If all the validations have passed, the password of the user must be updated accordingly and you need to display a success message below the form.

**Note**: Remember to store the password using the same hashing algorithm used in the registration.

### LSteam wallet
This section describes the process of loading money into the users virtual wallet.

Endpoints               | Method
----------------------- | -------
 /user/wallet           | GET
 /user/wallet			    | POST
 
If a user tries to access one of these URLs manually without being authenticated, the application must redirect him to the login page with a warning message.

When a user accesses the `/user/wallet` endpoint, the user will be able to see how much money he has in his wallet. By default, when the user confirms the registration, 50€ will be added to his wallet.

Aside from the user balance, there must be a form with one input:

* amount: the amount of money the user wants to add to his wallet.

The amount should be greater than 0. If there is any error, it will redirect the user to the same page, clearing the amount of money that was entered before. 

When the "Add to wallet" button is clicked, a **POST** request will be sent to the same endpoint to add money to the user's wallet.

### Store
This section shows a list of games the user can buy from the application.

Endpoints               | Method
----------------------- | -------
 /store                 | GET
 /store/buy/{gameId}    | POST
 /user/myGames          | GET
 
The games to be shown in the **/store** URL will come from [CheapShark API](https://www.cheapshark.com/api/1.0/deals) about deals. You will need to use [Guzzle](https://docs.guzzlephp.org/en/stable/). To be able to buy a game, the user must have money in his virtual wallet. In this page, he will be able to pick the game that he wants to buy. When the "Buy" button has been clicked, a POST method will be sent to the **/store/buy/{gameId}** endpoint. The system will then check if the user has enough money in his virtual wallet. If so, it will be added to the user's Owned Games. If not, the user is redirected to the store with a Flash message to let the user know about the error.

**Note:** If you want to do the "Cache" section, we recommend you to use the Repository Pattern you have used previously to implement the access to the database. You should create an Interface and a concrete implementation that uses Guzzle to call the CheapShark API. These set of interface and concrete implementation should **only** have the responsibility/functionality to interact with the CheapShark API.

You need to show the following information for each game:

* Title
* Game ID - the same id used in the [CheapShark API](https://apidocs.cheapshark.com/).
* Normal Price
* Thumbnail

By accessing the **/user/myGames** URL, the user will be able to see a list of all the games that he has bought from the Store. In this page, you will also need to show all the game details.

### Wishlist
There are games that are interesting to you, but you don't want to buy them right now. You can add games to your wishlist.

Endpoints                    | Method
---------------------------- | -------
 /user/wishlist              | GET
 /user/wishlist/{gameId}     | GET
 /user/wishlist/{gameId}     | POST
 /user/wishlist/{gameId}     | DELETE
 
If a user tries to access one of these URLs without being logged in, the application must redirect him to the Login page.

Users have to go to the **/user/wishlist** URL to see the list of games that they have previously added to the wishlist. If the wishlist is empty, a message must be clearly shown on the screen with a link to the Store so that they can browse games to either buy or to add them to their wishlist.
 
For each product in the wishlist, the user must be able to access the **/user/wishlist/{gameId}** endpoint, where all the information about the game is displayed. Going back to the Wishlist page, the user must also be able to buy the game (by clicking a link to the  **/store/buy/{gameId}** endpoint implemented in the previous section.
 
 
Once the game is bought from the wishlist page, it will be removed from the wishlist.
 
To be able to add a game to the wishlist, the user must click the link from the Store and this will send a POST request to the  **/user/wishlist/{gameId}** endpoint.
 
To create a better experience for the user, you add the Delete funcionality wherein the user can remove a game from the wishlist without buying the game. For that, you will have to send a DELETE request to the **/user/wishlist/{gameId}** endpoint.
 

### LSteam Friends
To let the gamers socialize with other gamers, LSteam lets you send and receive friend requests.

#### Friends list

Endpoints                                | Method
---------------------------------------- | -------
 /user/friends                           | GET
 
If a user tries to access one of these URLs without being logged in, the application must redirect him to the Login page.
 
When the **/user/friends** endpoint is accessed, the user must be able to see a list of his friends. An LSteam friend is someone who has either accepted the user's friend request or the user has accepted the other user's friend request. The friends list must show the following user information:

* username
* accept_date: date when the friend request was accepted 

#### Friend Requests

Endpoints                                | Method
---------------------------------------- | -------
 /user/friendRequests                    | GET
 /user/friendRequests/send               | GET
 /user/friendRequests/send               | POST
 /user/friendRequests/accept/{requestId} | GET

In the LSteam Friends page, there must be two links:

1. View and accept LSteam friend requests
2. Send an LSteam friend request

When the user selects the first link (view and accept LSteam friend requests), he must be able to see all of his friend requests through the **/user/friendRequests** URL. This page must show the username and a button to accept the each friend request. If the user decides to accept the friend request, a **POST** request will be sent to the **/user/friendRequests/accept/{requestId}** endpoint. The system will register the user as his new friend and the next time the user visits the **/user/friends** URL, the new friend will appear on the list. Also, you should either remove the accepted request from the list at **/user/friendRequests**.

**Important:** Each friend request has its own request ID. This means that its unique for each user. Therefore, there are some restrictions that are explained in the following scenario: User A sends a friend request to User B. If User C tries to access the **/user/friendRequests/accept/{requestId}** endpoint with the id of the request that User A has sent to User B, the system must not allow the operation. You must display an error and User B should not be added to the User C's friends list.

When the user selects the second link to access the **/user/friendRequests/send** URL, he must be redirected to another page that has a form. The form contains the following inputs:

* username: the username of the user to whom we are going to send the friend request

Username validation must be applied, just like in the Register and Login pages.

When the user clicks on the "Send friend request" button, a **POST** request will be sent to the same **/user/friendRequests/send** endpoint and the system will check the following conditions:

1. **The user must exist.** This means that the user must have an **active** LSteam account. 
2. **The user is not an LSteam friend.** This means that the user to whom we want to send a request does not appear in the list of LSteam friends of the logged user.
3. **There is no existing friend request to the user (declined or not).** This means that the user has not sent a friend request previously.

If any of these conditions are not met, the user will be redirected to the "Send Friend Request" page again and a message will be shown to indicate the corresponding error.

If all of the conditions are met, then the friend request will be sent correctly and it will be pending for acceptance by the other user.

### Cache
Because we want our application to be faster, we want implement a caching mechanism to avoid making requests to the CheapShark API. Remember that you should have created an interface and a concrete implementation to interact with the CheapShark API during the development of the store section.

The next step is to create another concrete implementation of the interface that implements the caching mechanism. This concrete cache implementation will **decorate** the implementation used to request information to the CheapShark API. See the following explanations:

* [Repositories and Decorators](https://hunterskrasek.com/programming/meetup/talks/2015/02/21/repositories-and-decorators-laravel/)
* [Decorator Pattern in .NET Core 3.1](https://www.programmingwithwolfgang.com/decorator-pattern-in-net-core-3-1/)
* [Wanna Cache? Decorate!!](http://www.beabetterdeveloper.com/2013/03/wanna-cache-decorate.html)

After browsing both examples and looking at the diagrams, observe the [Repositories and Decorators](https://hunterskrasek.com/programming/meetup/talks/2015/02/21/repositories-and-decorators-laravel/). It shows the following piece of code:

```php
public function getAll($limit = 10, $offset = 0) 
{
	// Pull the users out of cache, if it exists...
	return $this->cache->remember('users.all', 60, function() use ($limit, $offset) {
	    // If cache has expired, grab the users out of the database
	    return $this->repository->getAll($limit, $offset);
	});
}
```

This code checks if the file 'users.all' exists. If the file exists, it returns the contents of the file. Otherwise it quieries the database, saves the contents in the 'users.all' file and the return the response from the database. The next time the method 'getAll' is called, the file 'users.all' will exist and instead of quering the database, the result will be read from the file.

In this section, instead of database, you will query an HTTP API.

Remember to wire correctly both decoratee and decorator in your `dependencies.php` file. You should not need to change anything in your controller because what you get in the constructor of your controller is the interface, not the concrete implementations.


## Delivery

Since you are using Git, and also we want to make this project as real as possible, you are going to use annotated tags in order to release new versions of your application. You can check the official [git documentation](https://git-scm.com/book/en/v2/Git-Basics-Tagging) on how to create tags and use them. Remember to push your tags to the Bitbucket repository, otherwise they will only stay in your local computer.

This project is going to be delivered in three phases. As you may have noticed, this project has 9 different sections and they are ordered sequentially. There will be checkponts were you will need to deliver a new release/version of your application containing the next three sections. You can check here the dates with all the expected deliveries.

* `v1.0.0` on April 18 - Sections from 1 to 3
* `v2.0.0` on May 2 - Sections from 4 to 6
* `v3.0.0` on May 23 - All sections

The first two deliveries are considered as Continuous Evaluation exercises. The last delivery, `v.3.0.0` is the one we are going to use to evaluate the final project and a final score will be given for the whole web application. This means that you can optionally skip the first two releases, but keep in mind that you are going to sacrifice the proportional Continuous Evaluation scores. Furthermore, feedback will be given for the first two deliveries.

## Evaluation

1. To evaluate the project, we will use the release `v.3.0.0` of your repository.
2. In May, all the teams that have delivered the final release on time, will be interviewed by the teachers.
3. In this interview we are going to validate that each team member have worked and collaborated as expected in the project.
4. Those team members who do not pass the interview, will have to take a small test in June.
5. Those of you who fail the test will need to take it again in July.

#### Evaluation criteria

`v1.0.0`

To score the first release `v1.0.0`, the distribution of points are as follows:

* Landing - 2p
* Register - 4p
* Login - 3p
* Other criterias (clean code quality, clean design,...) - 1p

`v2.0.0`

To score the second release `v2.0.0`, the distribution of points are as follows:

* Profile - 4p
* LSteam wallet - 2.5p
* Store - 2.5p
* Other criterias (Cclean code quality, clean design,...) - 1p

`v3.0.0`

As mentioned above, the last delivery `v3.0.0` will have the final grade of the whole web application (LSteam). It must have ALL the sections implemented. The distribution of points are as follows:

* Landing - 0.25p
* Register - 1.5p
* Login - 0.75p
* Profile - 1.75p
* LSteam wallet - 0.75p
* Store - 0.75p
* Wishlist - 1p
* LSteam Friends - 1.75p
* Cache - 0.5p
* Other criterias (clean code quality, clean design (excluding cache),...) - 1p****
