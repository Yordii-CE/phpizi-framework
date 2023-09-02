# Getting started

1. Dowload this framework.
2. Rename 'easyflow' to your project name.
3. Working in the App folder (Ignoring Core).

```sh
app
├── config
├── controllers
├── libs
├── models
├── public
├── views
└── main.php
```

# How to work with easyflow?
you have to follow a series of naming rules to work with easyflow.
### Files

- Controllers: Point nomenclature and in lower case, all must end in '.controller'.

- Models: Point nomenclature and in lower case, all must end in '.model'.

- Views: Create a folder for each controller and within all the actions|functions that the controller has defined:

```sh
app
├── controllers
│    └── articles.controller.php
├── models
│    └── articles.model.php
├── views
│    └── articles
│        └── index.php
│        └── news.php
├── ...
```


### Classes

- Controllers: UpperCamelCase, all must end in 'Controller'.

```php
class ArticlesController extends Controller
{ 
}
```

- Models class name: UpperCamelCase, all must end in 'Model'.

```php
class ArticlesModel extends Model
{ 
}
```

  NOTE: When we reference a controller or model from the code, we do so without its prefixes.


# Automatic creation
You can create the files manually following these naming rules but we have failed a .bat that allows you to create controllers, models and views in an autonomous way thus avoiding errors.

### Controller, Model and View

```
.bat articles
``` 

### Only Controller and Model

```
.bat articles -true -false
```

### Only Controller

```
.bat articles -false -false
```
# View variables

You can access these variables in any view.

```php
echo $PROJECT_PATH;
echo $BASE_URL;
echo $CONTROLLER;
echo $ACTION;
```


# Config pattern

You can config a default pattern in the main.php file.

```php
DefaultUrl::$pattern = "{articles}/{index}/{1}";
```

# Actions

### view()

you can display a view like this:

```php
class ArticlesController extends Controller
{
    function index(): View
    {  
        return view();
    }
}
```
### json()

You can respond with a json like this:

```php
class ArticlesController extends Controller
{
    function index(): Json
    {  
        return json("Hello world!");
    }
}
```
### redirectToAction()

you can redirect to a controller action like this:

```php
class ArticlesController extends Controller
{
    function index(): Redirect
    {  
        return redirectToAction('index', 'Login');
    }
}
```
### redirectToUrl()

you can redirect to a web url like this:

```php
class ArticlesController extends Controller
{
    function index(): Redirect
    {  
        return redirectToUrl('https://www.youtube.com/');
    }
}
```

# Prefix

### For app
you can set a url prefix at application level like this:
```php
DefaultUrl::$pattern = "application/{articles}/{index}/{1}";
```

### For controller
you can set a url prefix at controller level like this:
```php
class ArticlesController extends Controller
{
    function __construct()
    {
      prefix("statistics");
    }
    ...
}
```

![Descripción de la imagen](/screenshots/data_in_view.png)

### Specify view and data

You can speciy the view name and the data name.

![Descripción de la imagen](/screenshots/view_with_both.png)

### View variables

You can access these variables in any view: $CONTROLLER, $ACTION, $BASE_URL.

### Use params

You can send parameters to a certain action
We can obtain the parameters as follows.

- articles/index/1

![Descripción de la imagen](/screenshots/params.png "articles/index/1")

- articles/index/1/Yordii

![Descripción de la imagen](/screenshots/params_two.png "articles/index/1/Yordii")

# Template (shared folder)

There will always be files 'footer' and 'header', these will wrap all views. You can add other components like 'menu' etc and add them to any part of your template stored in your 'shared' folder.

### Omit template 

You can omit the template that wraps the view with a third parameter.

![Descripción de la imagen](/screenshots/omit_template.png)

# Connect to Mysql

1. In the config file you can set your connection data.

![Descripción de la imagen](/screenshots/config.png)

2. You will have to load your database in your model, the load is not automatic because not all models can obtain data from a database, so resources are saved.

![Descripción de la imagen](/screenshots/model_to_mysql.png)

3. Finally in your controller you can use your model and access your data as follows.

![Descripción de la imagen](/screenshots/controller_to_mysql.png)

# Contributions

Your contributions are welcome! If you want to collaborate in this project, please follow the steps below:

1. Fork this repository and clone your own copy to your local machine.
2. Create a new branch to make your changes: `git checkout -b additional`.
3. Make changes and improvements to the code.
4. Make sure your changes follow the style guides and best practices of the project.
5. Commit your changes: `git commit -m "Description of changes"`.
6. Push your changes to your remote repository: `git push origin additional`.
7. Open a Pull Request to this main repository and describe the changes you have made.
8. Wait for your changes to be reviewed and merged.

# Contact

If you have any questions or suggestions related to this project, feel free to contact me. You can find me at [yokdy360@gmail.com](mailto:yokdy360@gmail.com).

# License

This project is licensed under the [MIT](https://opensource.org/licenses/MIT) License. If you use this code, please include the LICENSE file in your project.

