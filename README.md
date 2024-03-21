<h1 align="center">Welcome to Contact App | Technical Test Acseo 👋</h1>
<p>
 This project is a technical test for the company Acseo. The goal is to create a contact form with Symfony framework. Follow the instructions below to read the instructions of the test.
</p>

> https://github.com/acseo/contact-form

## Usage

- First, you need to clone the repository
- Install the dependencies with `composer install`
- Create a `.env.local` file and set the `DATABASE_URL` variable with your database credentials
- Create the database with `php bin/console doctrine:database:create`
- Execute migrations with `php bin/console doctrine:migrations:migrate`
- Load the fixtures with `php bin/console doctrine:fixtures:load`
- Launch the server with `symfony serve`
- Go to `http://localhost:8000` and enjoy the app

## Features

- User unauthenticated can create a contact | see the list of contacts created with his email
- User unauthenticated can create an account
- User authenticated can sign in | sign out
- User authenticated can create a contact | retrieve old contacts before the account creation | see the list of contacts created with his email
- User with the role `ROLE_ADMIN` can see the list of all contacts created
- User with the role `ROLE_ADMIN` can reply to a contact
- User with the role `ROLE_ADMIN` can archive a contact

- For all new contact, a JSON file is created in the `var/contact_requests` directory with the contact data (name, email, question, created_at) 

## Author

👤 **Jay Viannay**

* Github: [@JayViannay](https://github.com/JayViannay)