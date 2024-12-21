# Dagger Symfony CLI module

## Installation

``` bash
dagger install https://daggerverse.dev/mod/github.com/dagger-php/symfony-cli-module@main
```

## Direct usage

> Assuming your `source` code is locally here, then it will mount the whole path of `.` and then within that it will test the `.` directory within the current directory

### Run Symfony CLI commands

``` bash
dagger shell -c 'github.com/dagger-php/symfony-cli-module | symfony --cmd=help'
```

### Run Symfony CLI commands

``` bash
dagger shell -c 'github.com/dagger-php/symfony-cli-module | console --cmd=list'
```

### Run Symfony CLI commands

``` bash
dagger shell -c 'github.com/dagger-php/symfony-cli-module | console --cmd=list'
```

## Usage in your project

You can install the symfony-cli module, into your local project's dagger module, and then invoke it from inside the PHP source code

``` bash
dagger install https://daggerverse.dev/mod/github.com/dagger-php/symfony-cli-module@main
```

Add this code to your project, to begin calling the module from code. For example

``` php
<?php
    #[DaggerFunction]
    #[Doc('Run cache:clear on the current directory')]
    // dagger call --source=. cache-clear
    public function cacheClear(): Container
    {
        return dag()
            ->cacheClear()
    }
```

