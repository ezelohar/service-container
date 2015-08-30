<?php
/**
 * APP file
 */

require_once 'config.php';

if (ENVIRONMENT === 'dev') {
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
}



/*Auto loader*/
require_once 'vendor/autoload.php';


echo '########### APP IS RUNNING ###########';

echo '<br />';

echo '<span style="color:blue;">1. Implement class Container</span>';

/* initialize our service container/provider */
$container = new \Service\Container();

/* first test setts */
$container->set("book", "Lord of the flies");
$container->set("number", 317);
$container->set("now", function() {
	return date("F j, Y, g:i a");
});
$container->set("hello", function($firstName, $lastName) {
	return "Hello {$firstName} {$lastName}";
});

echo '<br />';
echo '<br />';


/* First set echos */
echo 'Book value is: ' . $container->get("book");
echo '<br />';
echo 'Number value is: ' . $container->get("number");
echo '<br />';
echo 'Now value: ' . $container->get("now");
echo '<br />';
echo $container->get("hello", array("John", "Doe"));
echo '<br />';

/* SECOND TESTS */
echo '<br />';
echo '<br />';
echo '<span style="color:blue;">2. Implement accessing and changing providers as property</span>';

$container->book = "Lord of the flies 2";
echo '<br />';
echo $container->book; // Prints "Lord of the flies 2"
echo '<br />';
echo $container->now; // Prints now date ("August 23, 2015, 7:28 am")
echo '<br />';


/* THIRD */

echo '<br />';
echo '<span style="color:blue;">3. Implement accessing and changing providers as array </span>';
$container["book"] = "Lord of the flies 3";
echo '<br />';
echo $container['book']; // Prints "Lord of the flies 3"
echo '<br />';
echo $container['now']; // Prints now date ("August 23, 2015, 7:28 am")
echo '<br />';

/* FORTH */

echo '<br />';
echo '<span style="color:blue;">4. Implement accessing providers as function</span>';
echo '<br />';
echo $container->book(); // Prints "Lord of the flies"
echo '<br />';
echo $container->number(); // Prints 317
echo '<br />';
echo $container->now(); // Prints now date ("August 23, 2015, 7:28 am")
echo '<br />';
echo $container->hello("John", "Doe"); // Prints "Hello John Doe"
echo '<br />';


/* FIFTH */
echo '<br />';
echo '<span style="color:blue;">5. Implement Singleton access</span>';
echo '<br />';
/* Works but i don't have valid db data to show */
$container->set("db", function() {
	return 'Some db connection';
}, true);
$db = $container->get("db"); // Always returns the same instance

$container->set("MAX_BUFFER_SIZE", 200, true);
$container->set("hash", function() {
	return md5(gethostname() . time());
}, true);
$value = $container->MAX_BUFFER_SIZE;
echo $value; // Prints 200
echo '<br />';
$container->MAX_BUFFER_SIZE = 300;

$value = $container->MAX_BUFFER_SIZE;
echo $value; // Still prints 200
echo '<br />';
$value = $container->hash();
echo $value; // Prints "c5fbeb164b784672ae118d0442aa7be6"
echo '<br />';

/* TO show same time after time pass */
sleep (1);
$value = $container->hash();
echo $value; // Still prints "c5fbeb164b784672ae118d0442aa7be6"

/* SIXTH */
echo '<br />';
echo '<span style="color:blue;">6. Implement provider interface</span>';
echo '<br />';


/* ALL DATA IS HARDCODED*/
$container->register(new \Service\User\UserServiceProvider());

echo $container->get("UserService")->getUser(317);
echo '<br />';
echo $container->get("UserApplicationService")->getUserApplications(317);
echo '<br />';


