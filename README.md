White October Technical Test Solution by John Tarling
=====================================================

## Introduction
This is my solution created for the [White October Technical Test](Test.md).  I am breaking the solution down into releases and have noted my [proposed features for each release](Releases.md).

## Setup
The setup for my solution is the same as for the initial [test](Test.md) if running as a new install.  If you are going to copy the solution files into an existing test install then you will need to run:

	php composer.phar update

This will download the new dependencies and update the autoload file to include them and the new application namespace.

## Tests
To run the behat tests type:
	
	bin/behat

Note: The tests will show failures if http://33.33.33.3/setup has not been visited to set up the database yet.  Once the database has been setup these tests will pass.

## Branches
The master branch will contain the latest stable build, other milestone releases are available as separate branches.