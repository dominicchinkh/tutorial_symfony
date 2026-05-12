# Useful commands

## Symfony CLI
Install the Symfony CLI tool to manage your Symfony projects more efficiently. You can download it from the official Symfony website: [Symfony CLI](https://symfony.com/download).

The symfony binary provides a tool to check if your computer meets all requirements. Open your console terminal and run this command:

```bash
symfony check:requirements
```
This command will check if your system has all the necessary extensions and configurations to run Symfony applications smoothly. It will also provide recommendations on how to fix any issues it finds.

Make sure to run this command before starting your Symfony project to ensure that your environment is properly set up.

When working on an existing Symfony application for the first time, it may be useful to run this command which displays information about the project:

```bash
php bin/console about
```
This command provides details about the Symfony version, installed bundles, and other relevant information about the project. It can help you understand the current state of the application and its dependencies.

Symfony CLI provides a command to check whether your project's dependencies contain any known security vulnerability:

```bash
symfony security:check
```
This command will analyze your project's dependencies and report any known security vulnerabilities. It is important to regularly run this command to ensure that your application is secure and up-to-date with the latest security patches.

In continuous integration services you can check security vulnerabilities by running the composer audit command. This uses the same data internally as check:security but does not require installing the entire Symfony CLI during CI or on CI workers.

```bash
composer audit
```
This command will analyze your project's dependencies and report any known security vulnerabilities. It is a good practice to include this command in your CI pipeline to ensure that your application remains secure throughout its development lifecycle.

To get a list of all of the routes in your system, use the debug:router command

```bash
php bin/console debug:router
```
This command will display a list of all the routes defined in your Symfony application, along with their corresponding controllers and HTTP methods. It can be helpful for debugging and understanding the routing configuration of your application.

The other command is called router:match and it shows which route will match the given URL. It's useful to find out why some URL is not executing the controller action that you expect

```bash
php bin/console route:match <route>
```
