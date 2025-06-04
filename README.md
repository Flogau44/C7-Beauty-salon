# C7-Beauty-salon

BeautySales API allows beauty salons in France to record sales figures and compare them with competitors.

Key features:

-   **JWT authentication** (registration, email confirmation)
-   **Personal space** (salon profile, sales history, previous month's entry)
-   **Email reminders** for last month's entries (starting the 5th)
-   **SQL database** for structured data storage
-   **Market statistics** updated per entry (national, regional, departmental averages)
-   **REST API** architecture

Access control:

-   **Logged-in users**: Can view only their own data
-   **Not logged-in users**: Can register, log in, or access documentation

Concise and to the point! 😊 Let me know if you'd like further refinements.

## Table of Contents

1. [Technical requirements](#Technicalrequirements)
2. [Installation](#installation)
3. [DataBase Create](#dataBase-create)
4. [Create JWT for authentication](#create-JWT-for-authentication)
5. [How to use this API](#how-to-use-this-api)
6. [Built With](#built-with)
7. [Contributing](#contributing)
8. [Authors](#authors)
9. [License](#license)

# Technical requirements

This project is a Symfony project with version 7.3 and PHP version 8.4 on Windows OS.

So you may need :

-   install PHP 8.4 or higher (you can also use xampp)
-   install composer (see the installation guide: [Composer Windows](hhttps://getcomposer.org/doc/00-intro.md#installation-windows))

If you want to check if your computer meets all requirements, open your console terminal and run this command :

```bash
symfony check:requirements
```

# Installation

To work on or read this project, you'll need to retrieve the code from Git and ask Composer to install the dependencies :

1. Clone the project to download its content

```bash
git clone ...
```

2. To install and run this project locally with Visual Studio Code, follow these steps (Prepare the Environment):

Install Scoop

```bash
Set-ExecutionPolicy RemoteSigned -scope CurrentUser
iwr -useb get.scoop.sh | iex
```

Install Symfony CLI

```bash
scoop install symfony-cli
```

Add Symfony CLI to PATH :

```bash
export PATH="$HOME/.symfony/bin:$PATH"
```

Download and install Node.js from nodejs.org. Then install Yarn :

```bash
cd beauty_salon/
npm install --global yarn
```

3. Make composer install dependencies

```bash
composer install
```

When working on a existing Symfony application for the first time, it may be useful to run this command which displays information about the project:

```bash
symfony console about
```

You'll probably also need to customize your ".env" or ".env.local" file and do a few other project-specific tasks :

## DataBase Create :

-   add a ".env.local" file to the project
-   copy/paste the .env file into the .env.local and configure :

```bash
DATABASE_URL="mysql://root:@127.0.0.1:3306/beauty_salon"
```

(if in phpmyadmin your user is "root" and your password is "")

-   create your database with Doctrine with this command :

```bash
php bin/console doctrine:database:create
```

and push it into phpmyadmin with the command :

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

-   then to simulate data in your database, use the command

```bash
php bin/console doctrine:fixtures:load
```

Now you can check your phpmyadmin, you should have all the data in beautysales database.

## Create JWT for authentication:

-   check in xampp/php/php.ini that your extension=sodium is uncomment,
-   add a new folder named "jwt" in Beauty_salon/config,
-   install JWT :

```bash
composer require lexik/jwt-authentication-bundle
```

-   create a public and private key with command (before openssl must be install):

```bash
php bin/console lexik:jwt:generate-keypair
```

-   confirm passphrase,
-   copy/paste the new paragraph about JWT in .env to .env.local
-   and in .env.local modify the value of JWT_PASSPHRASE with the real passphrase you choosed before

Now you should be able to request a token from api to authenticate.

# How to Use This API

With Postman, use the API route:
If you're using XAMPP, make sure to start Apache & MySQL, then run this command in your project:

```bash
symfony server:start
```

-   Go to http://127.0.0.1:8000/api/
-   You can explore and test all available endpoints, including their methods, parameters, and responses.

1. Register a New User

    To register in BeautySales:

-   Use the POST request on /api/register
-   Click "Try it out"
-   Modify the request with your own data (except for roles, as modifying them may restrict endpoint access)
-   Execute the request. The server will respond with a 201 status and return a JSON containing your submitted information.

2. Log In and Get Your Token

    Once registered, log in to retrieve your authentication token:

-   Use the POST request on /api/login
-   Click "Try it out"
-   Enter your registered username and password
-   Execute the request. The server will return a token
-   Copy the token value (without double quotes)
-   Click the "Authorize" button
-   In the input field, enter Bearer followed by your token value, then click "Authorize" again.

## Built With

-   [API-Platform](https://api-platform.com/) - Framework Api
-   [Symfony](https://symfony.com/) - Framework back-end
-   [Visual Studio Code](https://code.visualstudio.com/) - IDE

## Contributing

Contributions make the open-source community an incredible place to learn, inspire, and create. Any contributions you make are greatly appreciated.

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Authors

[@Flogau44](https://github.com/Flogau44)

## License

This project is licensed under the MIT License. See LICENSE for more details.
