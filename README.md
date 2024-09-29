# lack-subscription
Library to manage subscriptions accross services



## Installation

```bash
composer install lack/subscription
```


### Adding to Brace App

01_di.php
```php

$app->addModule(
    new SubscriptionClientModule(
        "file:///opt/conf/",
        CONF_SUBSCRIPTION_CLIENT_ID,
        CONF_SUBSCRIPTION_CLIENT_SECRET
    )
);
```


10_middleware.php
```php
$app->setPipe([
    ...
    // Below RouterEvalMiddleware
    // Search for {subscription_id} in the route and load the subscription object
    new SubscriptionMiddleware(),
    ...
]);
```

## Usage

### In Controller Classes

The RouterEvalMiddleware will register a service `subscription` in the container. You can use this service to get the subscription object.

```php

public function loadSubscription(T_Subscritpion $subscription)
{
    return $subscription;
}
```


### In Routes (as Middleware)

```php
AppLoader::extend(function (BraceApp $app) {

    $mount = CONF_API_MOUNT;

    $subscriptionAuthValidatorMw = new SubscriptionBasicAuthValidationMiddleware();


    // Validate auth token from mailbox of subscription
    $app->router->registerClass($mount, DocFusionApiCtrl::class, [$subscriptionAuthValidatorMw]);
```
