# Share Food

A local PHP/MySQL application for sharing food, clothes, and pet help with NGOs and people in need.

## Description

This project helps users:

- register and log in
- share food donations
- share clothes donations
- request dog help
- manage NGO-related posts and reports

It includes:

- `redim.php` — screenshot preview page for uploads
- `screenshots/` — captured page screenshots
- `uploads/` — uploaded food and NGO images

## Screenshot Preview

### Login page

![Login Page](screenshots/login.png)

### Registration page

![Register Page](screenshots/register.png)

### Share Food page (redirects to login when unauthenticated)

![Share Food Page](screenshots/share_food.png)

### Share Cloth page

![Share Cloth Page](screenshots/share_cloth.png)

### Share Dog Help page

![Share Dog Help Page](screenshots/share_dog_help.png)

## How to run

1. Copy the project into your XAMPP `htdocs` folder.
2. Start Apache and MySQL.
3. Open `http://localhost/share-food/` in your browser.
4. Use `register.php` to create a new account, then log in.

## Files Added

- `redim.php` — displays uploaded screenshots in the browser
- `screenshots/` — contains saved page screenshots
- `README.md` — this project description and screenshot gallery
