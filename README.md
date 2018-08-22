Dorcas Starter Template (Laravel)
=====
This project is the starter template for building web apps on the Dorcas API using the [Laravel](https://laravel.com/) framework.

## Setup

1. Clone the repository to your working directory `git clone https://gitlab.com/emmanix2002/dorcas-starter`    
2. Rename it to whatever you want it to be.    
3. `cd` into the cloned repository `cd /path/to/renamed-starter-project`    
4. Rename the `.env.example` configuration file to`.env` 
5. Set your database connection settings in the `.env` configuration file
6. Set write permissions to the `storage`, and `bootstrap/cache` directories
7. Install dependencies `composer install`
8. Run default migrations `./artisan migrate` - to import the queue tables
9. Add your Dorcas API client `id`, and `secret`

## What's included 

- Authentication via the Dorcas API is included (by default); just spin up the login page 
- Dorcas PHP SDK
- Some useful Dorcas middleware
- Dorcas Authentication via URL token (using the `DorcasAuthViaUrlToken` middleware)
- A few helper functions


*NB*: Users will normally sign up at the main Dorcas URL.

## What's Next

- Adding a channel for sending email notifications through Dorcas to user(s)
