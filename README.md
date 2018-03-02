# Elgg User Webservices API

# Introduction

This a [webservices](http://learn.elgg.org/en/stable/guides/web-services.html) API for managing Elgg users.

# Functions

Currently, these functions exist:

* user.create
  * username: Username of new user
  * password: Password of new user
  * displayName: Name of new user
  * email: Email of new user
  * language: Language for new user
  * profileManagerJson: A JSON encoded object with values for fields of the profile_manager plugin
  * notifyUser: Notify the new user upon registration
  * avatar: The base64-encoded avatar of the user
* user.delete:
  * username: Username of the user to delete

# Advisory

This is curently beta software. We wrote it to migrate our users from our old software to Elgg. Please use at your own risk.