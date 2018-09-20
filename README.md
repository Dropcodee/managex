Dorcas Starter Template (Laravel)
=====
This project is the starter template for building web apps on the Dorcas API using the [Laravel](https://laravel.com/) framework.

## Setup

1. Clone the repository to your working directory `git clone https://gitlab.com/emmanix2002/dorcas-starter folder-name`        
2. `cd` into the cloned repository `cd /path/to/folder-name`    
3. Duplicate/Rename the `.env.example` configuration file to `.env` : `cp .env.example .env`
4. Set your database connection settings in the `.env` configuration file
5. Set write permissions to the `storage`, and `bootstrap/cache` directories
6. Install dependencies `composer install`
7. Run default migrations `./artisan migrate` - to import the queue tables
8. Add your Dorcas API client `id`, and `secret`
9. Optionally, update the remote URL for your new repository: `git remote set-url origin https://github.com/username/new-repository-id`

## What's included 

- Authentication via the Dorcas API is included (by default); just spin up the login page 
- Dorcas PHP SDK
- Some useful Dorcas middleware
- Dorcas Authentication via URL token (using the `DorcasAuthViaUrlToken` middleware)
- A few helper functions


*NB*: Users will normally sign up at the main Dorcas URL.

## What's Next

- Adding a channel for sending email notifications through Dorcas to user(s)
