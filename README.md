# anagram-lookup

### Prerequisites

You must have PHP, Composer and Bun (or Node and NPM) executables
on your machine as well as Docker for running the project with Sail.

### Running the app with Sail

- Change working dir to this project

- Install packages:
```bash
composer i && bun i
```

- Build app image:
```bash
vendor/bin/sail build
```

- Create a `.env` file. An example is provided, so just clone that.
- Generate APP_KEY value:
```bash
php artisan key:generate
```

- Make sure ports for the app, MySQL and Redis are open (80, 3306 and 6379, respectively, in the example).

- Run Sail:
```bash
vendor/bin/sail up [-d] # -d for detached (optional)
vendor/bin/sail artisan migrate
bun run build
```

- To close sail:
```bash
vendor/bin/sail down [-v] # -v for deleting volumes (optional)
```

### Running import jobs

- Pick which suits you better
```bash
# to listen for incoming jobs without quitting
vendor/bin/sail artisan queue:listen --queue=import [--timeout=int] # --timeout for max seconds a job is allowed take (optional)
# to run the first job that comes up and then quit
vendor/bin/sail artisan queue:work --queue=import [--timeout=int]
```
