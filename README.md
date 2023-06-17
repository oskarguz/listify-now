## Listify.now

Created in Laravel, web application where you can easily create and manage any kind of lists.

## Quick Specification
- Project is dockerized, docker-compose.yml as well as Dockerfiles are extracted from Sail and ready for production,
- high code coverage with tests,
- full CRUD for the lists and list items,
- lists can be created by anonymous users, but only the logged ones have access to all features
- 3 ways to sign in:
  - via Discord,
  - via Google,
  - via Twitter,
  - standalone registration form is not implemented
- all lists are public, anyone can view and modify but only owner can delete - for now,

that`s the current, live version. Available here: https://listify.bieda.it/

## TODOs
1. [ ] there is a text input on home page, a quick way to create a list, make it works :)
2. [x] new list type: <strong>personal (private)</strong>. View and edit rights allowed only for list's owner,
3. [ ] a way to allow any user to view and/or edit personal lists (list privileges system),
4. [ ] maybe new types of lists with richer content (images/videos),
5. [ ] and more...
