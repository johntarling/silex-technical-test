White October Technical Test Solution by John Tarling
=====================================================

## Introduction
This is my solution created for the [White October Technical Test](Test.md).  I am breaking the solution down into releases and have noted my [proposed features for each release](Releases.md).

## Changelog
* Version 1.0 - First release (Meet basic task requirements)
* Version 1.1 - Switched to using Doctrine ORM, added new fields to news and created streamlined setup and update processes.

## Setup

> *UPDATE: As of version 1.1 you can ignore the step to set up the SQLite data, the application will now determine if this is needed when you visit the [home route](http://33.33.33.3/).*

The setup for my solution is the same as for the initial [test](Test.md) if running as a new install. 

If you are going to copy the solution files into an existing test install then you will need to run:

	php composer.phar update

This will download the new dependencies and update the autoload file to include them and the new application namespace.

## Usage

* You can visit the home route at [http://33.33.33.3](http://33.33.33.3)
* You can view a news article if you know its id by visiting http://33.33.33.3/id e.g. [http://http://33.33.33.3/1](http://33.33.33.3/1)

## Tests
There are a number of behat test scenarios, these can be run as follows:
	
**Test DB setup process**

	bin/behat --tags 'newssetup'

**Test update news table process**

	bin/behat --tags 'update'

**Test viewing the application**

	bin/behat --tags 'viewing'

## Branches
The [Master](https://github.com/johntarling/silex-technical-test) branch will contain the latest stable build, other milestone releases are available as separate branches.

* [Release v1.0](http://github.com/johntarling/silex-technical-test/tree/solution)
* [Release v1.1](http://github.com/johntarling/silex-technical-test/tree/solution-1.1)