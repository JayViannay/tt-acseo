<h1 align="center">Welcome to Contact App | Technical Test 💻</h1>
<p>
 This project is a technical test for the company Acseo. The goal is to create a contact form with Symfony framework and generate a JSON file for each contact request.
</p>

## Usage

- First, you need to clone the repository
- Install the dependencies with `composer install`
- Create a `.env.local` file and set the `DATABASE_URL` variable with your database credentials
- Create the database with `php bin/console doctrine:database:create`
- Execute migrations with `php bin/console doctrine:migrations:migrate`
- Load the fixtures with `php bin/console doctrine:fixtures:load`
- Launch the server with `symfony serve`
- Go to `http://localhost:8000`

## Usage

- Create a contact form with the following fields: name, email, question `http://localhost:8000/contact`
- Retrieve the list of contacts requests with the email you entered `http://localhost:8000/contact/contact-request`
- Create an account `http://localhost:8000/register`
- Sign in `http://localhost:8000/login`
- Create a contact form with the following fields: name, email, question `http://localhost:8000/contact`
- Retrieve the list of all contacts requests before and after you created your account `http://localhost:8000/contact/contact-request`

## Fake data

- You can use the following credentials to sign in as an admin: `admin@gmail.com` | `password`
- You can use the following credentials to sign in as a user: `user1|2|3|..10@gmail.com` | `password`
- You can use the following credentials to search contact requests from user who hasn't account : `random-user1|2|..5@gmail.com`

## Features

- User unauthenticated can create a contact | see the list of contacts created with his email
- User unauthenticated can create an account
- User authenticated can sign in | sign out
- User authenticated can create a contact | retrieve old contacts before the account creation | see the list of contacts created with his email
- User with the role `ROLE_ADMIN` can see the list of all contacts created
- User with the role `ROLE_ADMIN` can reply to a contact
- User with the role `ROLE_ADMIN` can archive a contact

- For all new contact, a JSON file is created in the `var/contact_requests` directory with the contact data (name, email, question, created_at) 
- After an user edited his contact, the JSON file is updated with the new data

## Author

👤 **Jay Viannay**

* Github: [@JayViannay](https://github.com/JayViannay)