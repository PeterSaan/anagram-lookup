# anagram-lookup

### Prerequisites

You must have PHP, Composer and Bun executables on your
machine as well as Docker for running the project with Sail.

### Starting the app

Run (or just copy from) the `setup.sh` script if this is a
fresh clone.

```bash
# from project root
vendor/bin/sail up [-d] # -d for detached
vendor/bin/sail artisan migrate
```

### Closing the app

```bash
vendor/bin/sail down [-v] # -v for deleting volumes
```
