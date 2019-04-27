# Light Framework

Light Framework is a PHP framework intended for small projects with low resources. The framework use a special design pattern similar to MVC/MVVM.

## Requirements
You have to use **PHP 7.2.x** or higher and execute **dump.sql** to use the application properly.

## Configure application

### Database connection and language configuration
You just have to go in **config/** folder and create a simple **configs.php** file as follows:
    
    <?php
    
    return [
        'database' => [
            'hostname' => '',
            'port' => '3306',
            'database' => '',
            'username' => '',
            'password' => '',
            'charset' => 'utf8'
        ],
        'languages' => [
            'English' => 'en',
            'French' => 'fr'
        ],
        'default_language' => 'en'
    ];

### Create a page reachable
#### Create a view
You just have to create a **.phtml** file in **public/view/**. *(eg: **contact.phtml**)*.
**Warning:** All your pages will take the **public/view/template/app.phtml** skeleton, please don't declare **html**, **head** or **body** markups which are already in this file.
By the way, if you want to use the same header/footer on every pages, there are already two files ready in **public/view/template/**

#### Create a controller
You have to:
- go in the folder **app/local/Controller/**
- create a class which extends **AbstractController** from **App\Core\Controller** and whose file name ends with Controller *(eg: **ContactController.php**)*
- create a method ends with Action *(eg: **indexAction()**)*
- return an instance of **ViewModel** in your method with your view in parametter

Here is your ContactController:
    
    <?php
    
    namespace App\Local\Controller;
    
    use App\Core\Controller\AbstractController;
    use App\Core\ViewModel;
    
    class ContactController extends AbstractController
    {
        /**
         * Index controller
         *
         * @return ViewModel
         */
        public function indexAction() : ViewModel
        {
            return new ViewModel('contact.phtml');
        }
    }

#### Create a route
Depending on if you want to create a **GET** or a **POST** route, there are static methods in the class **Router** to create route. Go to your **config/routes.php** and add your route add the end of the file !

- The parameter of **Router::get** or **Router::post** method is the route you want to create.
- The second parameter is an array with
 - The name of the controller
 - The name of the method called in your controller

Here is an example:
    
    <?php
    
    use App\Core\Router;
    
    Router::get(
        '/',
        ['HomeController', 'indexAction']
    );
    
    Route::get(
        '/language',
        ['HomeController', 'languageAction']
    );
    
    Router::get(
        '/contact',
        ['ContactController', 'indexAction']
    );
*If you want to create a POST route, you have to use the same process and replace **Route::get** by **Route::post***

#### Create a model
To prevent overloading of backend in views, please create a model and put your PHP code into the model. You can call model and use methods in views.

How to correctly write a Model:
    
    <?php
    
    namespace App\Local\Model;
    
    use App\Core\Model\AbstractModel;
    
    class ContactModel extends AbstractModel
    {
        /**
         * Get email by username
         *
         * @param string $username
         *
         * @return string
         */
        public function getEmail(string $username) : string
        {
            $selectContact = $this->db->prepare('SELECT email FROM contacts WHERE username = :username');
            $selectContact->execute([
                'username' => $username
            ]);
            $contact = $selectContact->fetch();
        
            return $contact['email'];
        }
    }
How to use it in view:
    
    <?php $contactModel = \App\Core\Light::getModel('Contact'); ?>
    
    <p>Email of the User "Foo": <?php echo $contactModel->getEmail('Foo');</p>

NB: The name you have to put into getModel method (*\App\Core\Light::getModel('Contact')*) is the name of your file. Here, the file's name is ContactModel. If you create a FooBarModel, you will call it with **\App\Core\Light::getModel('FooBar')** !

#### Create a translation
If you want to create a new language, you have to add it in **config/configs.php** following the others and create a new folder named with the language code in **app/language/** and create a **Global.csv**
    
    'languages' => [
        'English' => 'en',
        'French' => 'fr',
        'NewLanguage' => 'nl'
    ],
You can add every translations in **Global.csv**, but it's better to separate translations for every pages you create. For this, you just have to add a **protected $translationFile** in your Model.
    
    class ContactModel extends AbstractModel
    {
    /**
     * Contains translation file name
     *
     * @var string $translationFile
     */
    protected $translationFile = 'Contact';
Then, create a **Contact.csv** in all your languages **app/language/{language code}**. and add your translations as follow:
`"Default language sentence","New language sentence"`

## Edit application
Please don't touch the core part of the application located in **app/core/** and only use the **app/local/** to create your content if you don't know how to edit the core properly.

## Contribuate
I would love to receive contributions because the code is obviously not perfect and not finish, everything is good to take! The only rule is to respect the will of the "Light Framework": less is more!